<?php

namespace App\Domain\Model\ShippingAddress;

class ShippingAddress
{

    /**
     * @var ShippingAddressId
     */
    private $id;
    private $country;
    private $city;
    private $zipCode;
    private $street;
    private $isDefault = false;

    /**
     * @param ShippingAddressId $id
     * @param string $country
     * @param string $city
     * @param string $zipCode
     * @param string $street
     * @param bool $isDefault
     */
    public function __construct(ShippingAddressId $id, $country, $city, $zipCode, $street, $isDefault = false)
    {
        $this->id = $id;
        $this->country = $country;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->street = $street;
        $this->isDefault = $isDefault;
    }

    public function update($country, $city, $zipCode, $street, $isDefault)
    {
        $this->country = $country;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->street = $street;
        $this->isDefault = $isDefault;
    }

    public function setDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }

    public function getId(): ?ShippingAddressId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function edit($country, $city, $zipCode, $street)
    {
        $this->country = $country;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->street = $street;
    }
}