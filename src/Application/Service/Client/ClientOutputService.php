<?php

namespace App\Application\Service\Client;

use App\Domain\Model\Client\Client;
use App\Domain\Model\ShippingAddress\ShippingAddress;
use App\Domain\Model\ShippingAddress\ShippingAddressId;

class ClientOutputService
{

    /**
     * @param Client $client
     * @return array
     */
    public function clientToArray(Client $client): array
    {
        $shippingAddresses = [];

        /** @var ShippingAddress $shippingAddress */
        foreach ($client->getShippingAddresses() as $key => $shippingAddress) {
            $shippingAddresses[$key] = $this->shippingAddressToArray($shippingAddress);
        }

        return [
            'firstName' => $client->getFirstName(),
            'lastName' => $client->getLastName(),
            'shippingAddresses' => $shippingAddresses
        ];
    }

    /**
     * @param ShippingAddress $shippingAddress
     * @return array
     */
    public function shippingAddressToArray(ShippingAddress $shippingAddress): array
    {
        return [
            'id' => $shippingAddress->getId()->toString(),
            'country' => $shippingAddress->getCountry(),
            'city' => $shippingAddress->getCity(),
            'zipCode' => $shippingAddress->getZipCode(),
            'street' => $shippingAddress->getStreet(),
            'isDefault' => $shippingAddress->isDefault(),
        ];
    }

    /**
     * @param ShippingAddressId $shippingAddressId
     * @return array
     */
    public function shippingAddessIdToArray(ShippingAddressId $shippingAddressId)
    {
        return [
            'id' => $shippingAddressId->toString()
        ];
    }
}