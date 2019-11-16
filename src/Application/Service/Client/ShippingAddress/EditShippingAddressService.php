<?php

namespace App\Application\Service\Client\ShippingAddress;

use App\Application\Service\Client\AbstractService;
use App\Application\Service\Client\ClientNotFoundException;
use Doctrine\ORM\ORMException;

class EditShippingAddressService extends AbstractService
{

    /**
     * @param string $clientId
     * @param string $shippingAddressId
     * @param array $shippingAddressData
     * @throws ORMException
     * @throws ClientNotFoundException
     * @throws \Exception
     */
    public function run($clientId, $shippingAddressId, $shippingAddressData)
    {
        $client = $this->getClient($clientId);
        $client->editShippingAddress(
            $this->clientRepository->getShippingAddressId($shippingAddressId),
            $shippingAddressData['country'] ?? null,
            $shippingAddressData['city'] ?? null,
            $shippingAddressData['zipCode'] ?? null,
            $shippingAddressData['street'] ?? null
        );

        $this->clientRepository->update($client);
    }
}