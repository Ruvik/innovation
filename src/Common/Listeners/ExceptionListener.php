<?php

declare(strict_types=1);

namespace App\Common\Listeners;

use App\Kernel;
use Doctrine\ORM\EntityNotFoundException;
use DomainException;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * @psalm-suppress UnusedClass
 */
class ExceptionListener
{
    public function __construct(private readonly string $env)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($this->env === Kernel::ENVIRONMENT_DEV) {
            return;
        }
        $exception = $event->getThrowable();

        if ($exception instanceof EntityNotFoundException) {
            $event->setResponse(
                new JsonResponse(
                    ['message' => $exception->getMessage()],
                    Response::HTTP_NOT_FOUND
                )
            );
        } elseif ($exception instanceof InvalidArgumentException) {
            $event->setResponse(
                new JsonResponse(
                    ['message' => $exception->getMessage()],
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    []
                )
            );
        } elseif ($exception instanceof DomainException) {
            $event->setResponse(
                new JsonResponse(
                    ['message' => $exception->getMessage()],
                    Response::HTTP_BAD_REQUEST
                )
            );
        } else {
            $data = [
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
            ];
            $event->setResponse(new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }
}
