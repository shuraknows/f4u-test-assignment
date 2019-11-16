<?php

namespace App\Application\Service\Client;

use App\Domain\Model\Client\Client;
use App\Infrastructure\Persistence\ClientRepository;

abstract class AbstractService
{
    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var ClientOutputService
     */
    protected $clientOutputService;

    /**
     * @param ClientRepository $clientRepository
     */
    public function __construct(ClientRepository $clientRepository, ClientOutputService $clientOutputService)
    {
        $this->clientRepository = $clientRepository;
        $this->clientOutputService = $clientOutputService;
    }

    /**
     * @param string $clientId
     * @return Client
     * @throws ClientNotFoundException
     */
    protected function getClient($clientId): Client
    {
        $client = $this->clientRepository->getById($clientId);

        if (empty($client)) {
            throw new ClientNotFoundException('Client with id [' . $clientId . '] not found');
        }

        return $client;
    }
}