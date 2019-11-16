<?php

namespace App\Application\Service\Client\ShippingAddress;

use App\Application\Service\Client\AbstractService;
use App\Application\Service\Client\ClientNotFoundException;
use Doctrine\ORM\ORMException;
use Exception;

class AddShippingAddressService extends AbstractService
{

    /**
     * @param string $clientId
     * @param array $addressData
     * @return array
     * @throws ORMException
     * @throws ClientNotFoundException
     * @throws Exception
     */
    public function run($clientId, $addressData)
    {
        $client = $this->getClient($clientId);
        $shippingAddressId = $this->clientRepository->getShippingAddressId();

        $client->addShippingAddress(
            $shippingAddressId,
            $addressData['country'] ?? null,
            $addressData['city'] ?? null,
            $addressData['zipCode'] ?? null,
            $addressData['street'] ?? null
        );

        $this->clientRepository->update($client);

        return $this->clientOutputService->shippingAddessIdToArray($shippingAddressId);
    }
}