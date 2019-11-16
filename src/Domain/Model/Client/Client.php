<?php

namespace App\Domain\Model\Client;

use App\Domain\Model\CantAddShippingAddressException;
use App\Domain\Model\CantDeleteShippingAddressException;
use App\Domain\Model\CantEditShippingAddressException;
use App\Domain\Model\CantSetDefaultShippingAddressException;
use App\Domain\Model\ShippingAddress\ShippingAddress;
use App\Domain\Model\ShippingAddress\ShippingAddressId;
use App\Domain\Model\ShippingAddressNotExistsException;

class Client
{

    /**
     * max shipping address count
     */
    const MAX_SHIPPING_ADDRESSES_COUNT = 3;

    /**
     * @var ClientId
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var ShippingAddress[]
     */
    private $shippingAddresses;

    /**
     * @param ClientId $id
     * @param string $firstName
     * @param string $lastName
     * @param ShippingAddress[] $shippingAddresses
     */
    public function __construct(ClientId $id, $firstName, $lastName, $shippingAddresses = [])
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->shippingAddresses = $shippingAddresses;
    }

    /**
     * @return ClientId|null
     */
    public function getId(): ?ClientId
    {
        return $this->id;
    }

    /**
     * @param ClientId $id
     */
    public function setId(ClientId $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return ShippingAddress[]
     */
    public function getShippingAddresses(): array
    {
        return $this->shippingAddresses;
    }

    /**
     * @param ShippingAddressId $shippingAddressId
     * @return ShippingAddress
     */
    public function getShippingAddress(ShippingAddressId $shippingAddressId): ShippingAddress
    {
        if (empty($this->shippingAddresses[$shippingAddressId->toString()])) {
            throw new ShippingAddressNotExistsException('Client has no such shipping address');
        }

        return $this->shippingAddresses[$shippingAddressId->toString()];
    }

    /**
     * @param ShippingAddressId $id
     * @param string $country
     * @param string $city
     * @param string $zipCode
     * @param string $street
     * @throws CantAddShippingAddressException
     */
    public function addShippingAddress(ShippingAddressId $id, $country, $city, $zipCode, $street)
    {
        $this->assertCanAddShippingAddress();
        $isDefault = empty($this->shippingAddresses);
        $shippingAddress = new ShippingAddress($id, $country, $city, $zipCode, $street, $isDefault);
        $index = $shippingAddress->getId()->toString();

        $this->shippingAddresses[$index] = $shippingAddress;
    }

    /**
     * @throws CantAddShippingAddressException
     */
    private function assertCanAddShippingAddress()
    {
        if (count($this->shippingAddresses) >= self::MAX_SHIPPING_ADDRESSES_COUNT) {
            throw new CantAddShippingAddressException('Max count of shipping Addresses exceeded');
        }
    }

    /**
     * @param ShippingAddressId $shippingAddressId
     * @throws CantSetDefaultShippingAddressException
     */
    public function setDefaultShippingAddress(ShippingAddressId $shippingAddressId)
    {
        $this->assertCanSetDefaultShippingAddress($shippingAddressId);
        $index = $shippingAddressId->toString();

        foreach ($this->shippingAddresses as $id => $shippingAddress) {
            $shippingAddress->setDefault($id === $index);
        }
    }

    /**
     * @param ShippingAddressId $shippingAddressId
     * @throws CantSetDefaultShippingAddressException
     */
    private function assertCanSetDefaultShippingAddress(ShippingAddressId $shippingAddressId)
    {
        if (empty($this->shippingAddresses[$shippingAddressId->toString()])) {
            throw new CantSetDefaultShippingAddressException(
                'User has no address with such id [' . $shippingAddressId->toString() . ']'
            );
        }
    }

    /**
     * @param ShippingAddressId $shippingAddressId
     * @throws CantDeleteShippingAddressException
     */
    public function deleteShippingAddress(ShippingAddressId $shippingAddressId)
    {
        $this->assertCanDeleteShippingAddress($shippingAddressId);

        unset($this->shippingAddresses[$shippingAddressId->toString()]);
    }

    /**
     * @param ShippingAddressId $shippingAddressId
     * @throws CantDeleteShippingAddressException
     */
    private function assertCanDeleteShippingAddress(ShippingAddressId $shippingAddressId)
    {
        $index = $shippingAddressId->toString();
        $shippingAddress = $this->shippingAddresses[$index] ?? null;

        if (is_null($shippingAddress)) {
            throw new CantDeleteShippingAddressException('Shipping Address was not found');
        }

        if ($shippingAddress->isDefault()) {
            throw new CantDeleteShippingAddressException('Cant delete default shipping address');
        }
    }

    /**
     * @param ShippingAddressId $shippingAddressId
     * @param string $country
     * @param string $city
     * @param string $zipCode
     * @param string $street
     *
     * @throws CantEditShippingAddressException
     */
    public function editShippingAddress(ShippingAddressId $shippingAddressId, $country, $city, $zipCode, $street)
    {
        $this->assertCanEditShippingAddress($shippingAddressId);
        $shippingAddress = $this->shippingAddresses[$shippingAddressId->toString()];
        $shippingAddress->edit($country, $city, $zipCode, $street);
    }

    /**
     * @param ShippingAddressId $shippingAddressId
     * @throws CantEditShippingAddressException
     */
    private function assertCanEditShippingAddress(ShippingAddressId $shippingAddressId)
    {
        if (empty($this->shippingAddresses[$shippingAddressId->toString()])) {
            throw new CantEditShippingAddressException('Client has no shipping address with such id');
        }
    }
}