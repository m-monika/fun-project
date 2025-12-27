<?php

namespace App\OpenTelemetry;

use OpenTelemetry\API\Trace\SpanInterface;
use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\API\Trace\StatusCode;
use OpenTelemetry\Context\ScopeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class OpenTelemetrySubscriber implements EventSubscriberInterface
{
    private SpanInterface $span;
    private ScopeInterface $scope;

    public function __construct(
        private OpenTelemetryService $otel,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
            KernelEvents::RESPONSE => 'onResponse',
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        $this->span = $this->otel
            ->getTracer()
            ->spanBuilder($request->getMethod().' '.$request->getPathInfo())
            ->setSpanKind(SpanKind::KIND_SERVER)
            ->startSpan();

        $this->scope = $this->span->activate();
    }

    public function onResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();

        $this->span->setStatus(
            $response->isSuccessful()
                ? StatusCode::STATUS_OK
                : StatusCode::STATUS_ERROR
        );

        $this->span->end();
        $this->scope->detach();
        $this->otel->shutdown();
    }
}
