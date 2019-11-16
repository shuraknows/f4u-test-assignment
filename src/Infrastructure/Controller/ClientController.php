<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\Client\GetClientDataService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClientController extends AbstractController
{
    use ErrorResponseTrait;

    /**
     * @param string $clientId
     * @param GetClientDataService $clientDataService
     * @return JsonResponse
     */
    public function getClientData($clientId, GetClientDataService $clientDataService)
    {
        try {
            $clientData = $clientDataService->run($clientId);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }

        return $this->json(['error' => false, 'data' => $clientData]);
    }
}