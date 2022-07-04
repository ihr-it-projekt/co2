<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sensor;
use OpenApi\Attributes as OAT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor $container is from symfony and will inject automatically*/
class GetSensorStateController extends AbstractController
{
    #[Route('/api/v1/sensors/{sensor}', name: 'get_sensor_state', methods: 'GET')]
    #[OAT\Response(
        response: 200,
        description: 'Returns the sensor state',
        content: new OAT\JsonContent(
            properties: [
                            new OAT\Property(property: 'status', type: 'string', enum: ['OK', 'ALERT', 'WARN'], example: 'OK'),
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
    public function getSensorState(Sensor $sensor): JsonResponse
    {
        return $this->json(['status' => $sensor->getStatus()]);
    }
}
