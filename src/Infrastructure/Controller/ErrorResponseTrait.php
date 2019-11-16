<?php

namespace App\Infrastructure\Controller;


use App\Application\Service\Client\ApplicationException;
use DomainException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ErrorResponseTrait
{
    /**
     * @param Exception $exception
     * @return JsonResponse
     */
    private function errorResponse(Exception $exception)
    {
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof DomainException || $exception instanceof ApplicationException) {
            $status = Response::HTTP_BAD_REQUEST;
        }

        return $this->json([
            'error' => true,
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ], $status);
    }
}