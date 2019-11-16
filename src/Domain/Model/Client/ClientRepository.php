<?php

namespace App\Domain\Model\Client;

interface ClientRepository
{
    public function getById($id): ?Client;

    public function update(Client $client): ?Client;
}