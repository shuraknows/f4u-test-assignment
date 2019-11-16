<?php

namespace App\Application\Service\Client\ShippingAddress;

use App\Application\Service\Client\AbstractService;
use App\Application\Service\Client\ClientNotFoundException;
use App\Domain\Model\ShippingAddress\ShippingAddressId;

class GetShippingAddressDataService extends AbstractService
{

    /**
     * @param string $clientId
     * @param string $addressId
     * @return array
     * @throws ClientNotFoundException
     */
    public function run($clientId, $addressId)
    {
        $client = $this->getClient($clientId);
        $shippingAddress = $client->getShippingAddress(new ShippingAddressId($addressId));

        return $this->clientOutputService->shippingAddressToArray($shippingAddress);
    }
}