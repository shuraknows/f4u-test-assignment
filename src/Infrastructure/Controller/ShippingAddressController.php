<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\Client\ShippingAddress\AddShippingAddressService;
use App\Application\Service\Client\ShippingAddress\DeleteShippingAddressService;
use App\Application\Service\Client\ShippingAddress\EditShippingAddressService;
use App\Application\Service\Client\ShippingAddress\GetShippingAddressDataService;
use App\Application\Service\Client\ShippingAddress\SetDefaultShippingAddressService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ShippingAddressController extends AbstractController
{
    use ErrorResponseTrait;

    /**
     * @param string $clientId
     * @param Request $request
     * @param AddShippingAddressService $addressService
     * @return JsonResponse
     */
    public function add($clientId, Request $request, AddShippingAddressService $addressService)
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $shippingAddressData = $addressService->run($clientId, $requestData);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }

        return $this->json(['error' => false, 'data' => $shippingAddressData]);
    }

    /**
     * @param string $clientId
     * @param string $addressId
     * @param GetShippingAddressDataService $addressService
     * @return JsonResponse
     */
    public function getShippingAddress($clientId, $addressId, GetShippingAddressDataService $addressService)
    {
        try {
            $shippingAddressData = $addressService->run($clientId, $addressId);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }

        return $this->json($shippingAddressData);
    }

    /**
     * @param string $clientId
     * @param string $addressId
     * @param SetDefaultShippingAddressService $addressService
     * @return JsonResponse
     */
    public function setDefault($clientId, $addressId, SetDefaultShippingAddressService $addressService)
    {
        try {
            $addressService->run($clientId, $addressId);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }

        return $this->json(['error' => false]);
    }

    /**
     * @param string $clientId
     * @param string $addressId
     * @param DeleteShippingAddressService $addressService
     * @return JsonResponse
     */
    public function delete($clientId, $addressId, DeleteShippingAddressService $addressService)
    {
        try {
            $addressService->run($clientId, $addressId);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }

        return $this->json(['error' => false]);
    }

    /**
     * @param string $clientId
     * @param string $addressId
     * @param Request $request
     * @param EditShippingAddressService $addressService
     * @return JsonResponse
     */
    public function edit($clientId, $addressId, Request $request, EditShippingAddressService $addressService)
    {
        $requestData = json_decode($request->getContent(), true);

        try {
            $addressService->run($clientId, $addressId, $requestData);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }

        return $this->json(['error' => false]);
    }
}