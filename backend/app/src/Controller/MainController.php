<?php

namespace App\Controller;

use OpenTelemetry\API\Trace\SpanKind;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\Sampler\AlwaysOnSampler;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;

class MainController extends AbstractController
{
    #[Route('/')]
    public function homepage(): Response
    {
        $this->testOtpl();
        $json = ['message' => 'Welcome to the homepage!'];

        return $this->json($json);
    }

    private function testOtpl(): void
    {
        /**
         * Resource
         */
        $resource = ResourceInfo::create(
            Attributes::create([
                'service.name' => 'pure-php-app',
            ])
        );

        /**
         * OTLP HTTP transport (CORRECT)
         */
        $transport = (new OtlpHttpTransportFactory())->create(
            endpoint: 'http://otel-collector:4318/v1/traces',
            contentType: 'application/x-protobuf'
        );

        /**
         * Exporter
         */
        $exporter = new \OpenTelemetry\Contrib\Otlp\SpanExporter($transport);

        /**
         * TracerProvider
         */
        $tracerProvider = new TracerProvider(
            sampler: new AlwaysOnSampler(),
            spanProcessors: [
                new SimpleSpanProcessor($exporter),
            ],
            resource: $resource
        );


        /**
         * Tracer
         */

        $span = $tracerProvider->getTracer('pure-index-php')->spanBuilder('example-span')
            ->setSpanKind(SpanKind::KIND_SERVER)
            ->startSpan();

        $scope = $span->activate();

        try {
            usleep(200_000);
        } finally {
            $span->end();
            $scope->detach();
            $tracerProvider->shutdown();
        }
    }
}
