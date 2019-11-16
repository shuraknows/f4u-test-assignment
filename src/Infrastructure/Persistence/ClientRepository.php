<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Model\Client\Client;
use App\Domain\Model\Client\ClientId;
use App\Domain\Model\ShippingAddress\ShippingAddress;
use App\Domain\Model\ShippingAddress\ShippingAddressId;
use App\Infrastructure\Persistence\Entity\Client as ClientEntity;
use App\Infrastructure\Persistence\Entity\ShippingAddress as ShippingAddressEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\ORMException;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ClientRepository
 * @package App\Infrastructure\Persistence
 */
class ClientRepository implements \App\Domain\Model\Client\ClientRepository
{
    /**
     * @var ServiceEntityRepository
     */
    private $clientRepository;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * ClientRepository constructor.
     *
     * @param RegistryInterface $registry
     * @param ObjectManager $objectManager
     */
    public function __construct(RegistryInterface $registry, ObjectManager $objectManager)
    {
        $this->clientRepository = new ServiceEntityRepository($registry, ClientEntity::class);
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $id
     * @return Client|null
     */
    public function getById($id): ?Client
    {
        $clientEntity = $this->clientRepository->find($id);

        /** @var ClientEntity $clientEntity */
        if (!empty($clientEntity)) {
            return $this->entityToModel($clientEntity);
        }

        return null;
    }

    /**
     * @param ClientEntity $clientEntity
     * @return Client
     */
    private function entityToModel(ClientEntity $clientEntity)
    {
        $shippingAddresses = [];

        /** @var ShippingAddressEntity $shippingAddressEntity */
        foreach ($clientEntity->getShippingAddresses() as $shippingAddressEntity) {
            $idString = $shippingAddressEntity->getId()->toString();
            $shippingAddresses[$idString] = new ShippingAddress(
                new ShippingAddressId($idString),
                $shippingAddressEntity->getCountry(),
                $shippingAddressEntity->getCity(),
                $shippingAddressEntity->getZipCode(),
                $shippingAddressEntity->getStreet(),
                $shippingAddressEntity->isDefault()
            );
        }

        return new Client(
            new ClientId($clientEntity->getId()->toString()),
            $clientEntity->getFirstName(),
            $clientEntity->getLastName(),
            $shippingAddresses
        );
    }

    /**
     * @param Client $client
     * @return Client|null
     * @throws ORMException
     */
    public function update(Client $client): ?Client
    {
        /** @var ClientEntity $clientEntity */
        $clientEntity = $this->clientRepository->find($client->getId()->toString());

        $this->updateAddressEntities($client, $clientEntity);
        $this->objectManager->flush();

        return $client;
    }

    /**
     * @param Client $client
     * @param ClientEntity $clientEntity
     * @throws ORMException
     */
    private function updateAddressEntities(Client $client, ClientEntity $clientEntity)
    {
        $indexedShippingAddressEntities = $this->getIndexedShippingAddresses($clientEntity->getShippingAddresses());
        $indexedShippingAddresses = $this->getIndexedShippingAddresses($client->getShippingAddresses());

        $newAddressIds = array_diff(array_keys($indexedShippingAddresses), array_keys($indexedShippingAddressEntities));
        $deletedAddressIds = array_diff(array_keys($indexedShippingAddressEntities), array_keys($indexedShippingAddresses));

        $this->addNewShippingAddressEntities($clientEntity, $newAddressIds);
        $this->syncShippingAddressModels($clientEntity, $indexedShippingAddresses, $deletedAddressIds);
    }

    /**
     * Prepare array indexed by address id
     *
     * @param ShippingAddress|ShippingAddressEntity $shippingAddresses
     * @return ShippingAddress[]|ShippingAddressEntity[]
     */
    private function getIndexedShippingAddresses($shippingAddresses)
    {
        $indexedAddresses = [];

        /** @var ShippingAddress|ShippingAddressEntity $shippingAddress */
        foreach ($shippingAddresses as $shippingAddress) {
            $indexedAddresses[$shippingAddress->getId()->toString()] = $shippingAddress;
        }

        return $indexedAddresses;
    }

    /**
     * Create entities for newly added shipping addresses
     *
     * @param ClientEntity $clientEntity
     * @param array $newAddressIds
     * @throws ORMException
     */
    private function addNewShippingAddressEntities(ClientEntity $clientEntity, $newAddressIds)
    {
        foreach ($newAddressIds as $newAddressId) {
            $shippingAddressEntity = new ShippingAddressEntity();
            $shippingAddressEntity->setId(Uuid::fromString($newAddressId));
            $clientEntity->addShippingAddress($shippingAddressEntity);
            $this->objectManager->persist($shippingAddressEntity);
        }
    }

    /**
     * @param ClientEntity $clientEntity
     * @param ShippingAddress[] $shippingAddresses
     * @param array $deletedAddressIds
     * @throws ORMException
     */
    private function syncShippingAddressModels(ClientEntity $clientEntity, $shippingAddresses, $deletedAddressIds)
    {
        /** @var ShippingAddressEntity $shippingAddressEntity */
        foreach ($clientEntity->getShippingAddresses() as $shippingAddressEntity) {
            // Deleted item
            if (in_array($shippingAddressEntity->getId()->toString(), $deletedAddressIds)) {
                $this->objectManager->remove($shippingAddressEntity);
                continue;
            }

            /** @var ShippingAddress $shippingAddressModel */
            $shippingAddressModel = $shippingAddresses[$shippingAddressEntity->getId()->toString()];

            $shippingAddressEntity
                ->setCity($shippingAddressModel->getCity())
                ->setCountry($shippingAddressModel->getCountry())
                ->setStreet($shippingAddressModel->getStreet())
                ->setZipCode($shippingAddressModel->getZipCode())
                ->setDefault($shippingAddressModel->isDefault());
        }
    }

    /**
     * Next shipping address id
     *
     * @param string|null $value
     * @return ShippingAddressId
     * @throws Exception
     */
    public function getShippingAddressId($value = null)
    {
        if (!empty($value)) {
            return new ShippingAddressId(Uuid::fromString($value));
        }

        return new ShippingAddressId(Uuid::uuid4());
    }
}