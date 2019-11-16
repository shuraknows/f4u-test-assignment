<?php

namespace App\Application\Service\Client;

class GetClientDataService extends AbstractService
{
    /**
     * @param $clientId
     * @return array
     * @throws ClientNotFoundException
     */
    public function run($clientId)
    {
        $client = $this->getClient($clientId);

        return $this->clientOutputService->clientToArray($client);
    }
}