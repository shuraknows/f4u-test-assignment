<?php

namespace App\Infrastructure\Persistence\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity="App\Infrastructure\Persistence\Entity\ShippingAddress", mappedBy="client")
     */
    private $shippingAddresses;


    public function __construct($id, $firstName, $lastName)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->shippingAddresses = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return Collection|ShippingAddress
     */
    public function getShippingAddresses(): Collection
    {
        return $this->shippingAddresses;
    }


    public function addShippingAddress(ShippingAddress $shippingAddress)
    {
        $shippingAddress->setClient($this);
        $this->shippingAddresses->add($shippingAddress);

        return $this;
    }
}