<?php

namespace App\Application\Service\Client\ShippingAddress;

use App\Application\Service\Client\AbstractService;
use App\Application\Service\Client\ClientNotFoundException;
use App\Domain\Model\ShippingAddress\ShippingAddressId;
use Doctrine\ORM\ORMException;

class DeleteShippingAddressService extends AbstractService
{
    /**
     * @param string $clientId
     * @param string $shippingAddressId
     * @throws ORMException
     * @throws ClientNotFoundException
     */
    public function run($clientId, $shippingAddressId)
    {
        $client = $this->getClient($clientId);
        $client->deleteShippingAddress(new ShippingAddressId($shippingAddressId));
        $this->clientRepository->update($client);
    }
}