<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Measurement;
use App\Entity\Sensor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    public function add(Measurement $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * @throws \UnexpectedValueException
     *
     * @return Measurement[]
     */
    public function getLast3MeasuresBySensor(Sensor $sensor): array
    {
        /** @var Measurement[] $measurements */
        $measurements = $this->findBy(
            ['sensor' => $sensor],
            ['time' => 'DESC'],
            3
        );

        return $measurements;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getCo2AverageAndMaxLast30Days(Sensor $sensor): array|false
    {
        $conn = $this->getEntityManager()
                     ->getConnection();
        $sql = 'SELECT ROUND(AVG(co2), 2) AS avgLast30Days, MAX(co2) AS maxLast30Days
            FROM measurement
            WHERE `sensor_id` = :id
            AND `time` <= NOW() - INTERVAL 30 day';
        $stmt   = $conn->prepare($sql);
        $result = $stmt->executeQuery(['id' => $sensor->getId()]);

        return $result->fetchAssociative();
    }
}
