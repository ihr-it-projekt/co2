<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sensor;
use OpenApi\Attributes as OAT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/** @psalm-suppress PropertyNotSetInConstructor $container is from symfony and will inject automatically*/
class GetSensorAlertController extends AbstractController
{
    /**
     * @throws \InvalidArgumentException
     */
    #[Route('/api/v1/sensors/{sensor}/alerts', name: 'get_sensor_alerts', methods: 'GET')]
    #[OAT\Response(
        response: 200,
        description: 'Returns the sensor alerts',
        content: new OAT\JsonContent(
            type: 'array',
            items: new OAT\Items(
                      properties: [
                                      new OAT\Property(
                                          property: 'startTime',
                                          type: 'string',
                                          example: '2019-02-01T18:55:47+00:00'
                                      ),
                                      new OAT\Property(
                                          property: 'endTime', type: 'string', example: '2019-02-02T20:00:47+00:00'
                                      ),
                                      new OAT\Property(property: 'measurement1', type: 'integer', example: 2100),
                                      new OAT\Property(property: 'measurement2', type: 'integer', example: 2200),
                                      new OAT\Property(property: 'measurement3', type: 'integer', example: 2100),
                                  ]
                  )
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
    public function getSensorAlert(Sensor $sensor, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize(
            $sensor->getAlerts()->getValues(),
            JsonEncoder::FORMAT,
            [DateTimeNormalizer::FORMAT_KEY => 'c']
        );

        return new Response($json);
    }
}
