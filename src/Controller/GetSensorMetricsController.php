<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Metric;
use App\Entity\Sensor;
use App\Repository\MeasurementRepository;
use OpenApi\Attributes as OAT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor $container is from symfony and will inject automatically*/
class GetSensorMetricsController extends AbstractController
{
    public function __construct(
        private readonly MeasurementRepository $measurementRepository
    ) {
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    #[Route('/api/v1/sensors/{sensor}/metrics', name: 'get_sensor_metrics', methods: 'GET')]
    #[OAT\Response(
        response: 200,
        description: 'Returns the sensor metrics',
        content: new OAT\JsonContent(
            properties: [
                            new OAT\Property(property: 'maxLast30Days', type: 'integer', example: 2200),
                            new OAT\Property(property: 'avgLast30Days', type: 'integer', example: 2100),
                        ]
        ),
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
    public function getSensorMetrics(Sensor $sensor): JsonResponse
    {
        $data = $this->measurementRepository->getCo2AverageAndMaxLast30Days($sensor);

        return $this->json(Metric::fromArray($data));
    }
}
