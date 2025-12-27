<?php

namespace App\OpenTelemetry;

use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Trace\Sampler\AlwaysOnSampler;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;

class OpenTelemetryService
{
    private const SERVICE_NAME = 'pure-php-app';
    private TracerProvider $tracerProvider;
    private TracerInterface $tracer;

    public function __construct(private string $endpoint)
    {
        $resource = ResourceInfo::create(
            Attributes::create([
                'service.name' => self::SERVICE_NAME,
            ])
        );

        $transport = (new OtlpHttpTransportFactory())->create(
            endpoint: $this->endpoint,
            contentType: 'application/x-protobuf'
        );

        $exporter = new SpanExporter($transport);

        $this->tracerProvider = new TracerProvider(
            sampler: new AlwaysOnSampler(),
            spanProcessors: [new SimpleSpanProcessor($exporter)],
            resource: $resource
        );

        $this->tracer = $this->tracerProvider->getTracer(self::SERVICE_NAME);
    }

    public function getTracer(): TracerInterface
    {
        return $this->tracer;
    }

    public function shutdown(): void
    {
        $this->tracerProvider->shutdown();
    }
}
