<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Alert;
use App\Entity\Measurement;
use App\Entity\Sensor;
use App\Enum\SensorLevel;
use App\Repository\AlertRepository;
use App\Repository\MeasurementRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OAT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor $container is from symfony and will inject automatically*/
class AddMeasurementController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MeasurementRepository $measurementRepository,
        private readonly AlertRepository $alertRepository,
    ) {
    }

    /**
     * Stores a new measurement for a sensor.
     *
     * @throws \UnexpectedValueException
     * @throws \Exception
     */
    #[Route('/api/v1/sensors/{sensor}/measurements', name: 'add_measurements', methods: 'POST')]
    #[OAT\RequestBody(
        description: 'Body parameters',
        content: new OAT\JsonContent(
            properties: [
                            new OAT\Property(property: 'co2', type: 'integer', example: 2000),
                            new OAT\Property(property: 'time', type: 'string', example: '2022-07-03T18:55:47+00:00'),
                        ]
        ),
    )]
    #[OAT\Response(
        response: 201,
        description: 'Measurement was successfully stored',
    )]
    #[OAT\Response(
        response: 404,
        description: 'Sensor was not found',
        content: new OAT\JsonContent(
            properties: [
                            new OAT\Property(property: 'type', type: 'url', example: 'https://tools.ietf.org/html/rfc2616#section-10'),
                            new OAT\Property(property: 'title', type: 'string', example: 'An error occurred'),
                            new OAT\Property(property: 'status', type: 'integer', example: 404),
                            new OAT\Property(property: 'detail', type: 'string', example: 'Not Found'),
                        ]
        ),
    )]
    #[OAT\Parameter(name: 'sensor', description: 'the sensor id', in: 'path', required: true, example: 1)]
    public function addMeasurement(Sensor $sensor, Request $request): JsonResponse
    {
        $this->measurementRepository->add(Measurement::createFromSensorAndMeasureRequest($sensor, $request));

        $this->checkSensorState($sensor);

        $this->entityManager->flush();

        return $this->json([], 201);
    }

    /**
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function checkSensorState(Sensor $sensor): void
    {
        $lastThreeMeasures = $this->measurementRepository->getLast3MeasuresBySensor($sensor);

        $sensor->setStatus(SensorLevel::OK);
        $this->checkAndSetWarnStateIfNecessary($sensor, $lastThreeMeasures);

        $this->checkSetAndCreateAlertStateIfNecessary($sensor, $lastThreeMeasures);

        $this->entityManager->persist($sensor);
    }

    /**
     * @psalm-param Measurement[] $lastThreeMeasures
     */
    private function checkAndSetWarnStateIfNecessary(Sensor $sensor, array $lastThreeMeasures): void
    {
        if ($sensor->mustTriggerWarn(...$lastThreeMeasures))
        {
            $sensor->setStatus(SensorLevel::Warn);
        }
    }

    /**
     * @throws \InvalidArgumentException
     * @psalm-param Measurement[] $lastThreeMeasures
     */
    private function checkSetAndCreateAlertStateIfNecessary(Sensor $sensor, array $lastThreeMeasures): void
    {
        if ($sensor->mustTriggerAlert(...$lastThreeMeasures))
        {
            $this->alertRepository->add(Alert::createFromMeasurements($sensor, ...$lastThreeMeasures));
            $sensor->setStatus(SensorLevel::Alert);
        }
    }
}
