<?php

namespace Workbench\App\Geocoding;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Workbench\App\Geocoding\Api\DataTransferObject\FeatureCollection;

class HttpGeocode implements Geocode
{
    public function __construct(
        private readonly string $apiUrl,
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function geocode(string $address): FeatureCollection
    {
        $url = Str::finish($this->apiUrl, '/').'search';

        /** @var Response $response */
        $response = Http::get($url, [
            'q' => $address,
            'type' => 'housenumber',
        ]);

        $body = $response->body();

        if ($response->failed()) {
            $this->logger->error('Geocoding failed', ['response' => $body]);
        }

        return $this->serializer->deserialize($body, FeatureCollection::class, 'json');
    }
}
