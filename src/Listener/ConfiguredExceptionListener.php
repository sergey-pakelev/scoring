<?php

namespace App\Listener;

use App\Service\ExceptionConfigResolver;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ConfiguredExceptionListener
{
    public function __construct(
        private readonly ExceptionConfigResolver $exceptionConfigResolver
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $config = $this->exceptionConfigResolver->resolve($throwable::class);

        if (!$config) {
            return;
        }

        $code = $config->getCode();
        $message = $config->isHidden() ? Response::$statusTexts[$code] : $throwable->getMessage();

        $response = new Response(content: $message, status: $code);

        $event->setResponse($response);
    }
}
