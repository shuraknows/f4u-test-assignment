<?php

namespace App\DataFixtures;

use App\Infrastructure\Persistence\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Ramsey\Uuid\Uuid;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $exampleClients = [
            ['uuid' => '01ca31e0-2c30-4b26-9089-b365c09d4905', 'name' => 'Liam', 'surname' => 'Clark'],
            ['uuid' => '574637cf-5f8a-4990-b122-06aeef7a03cd', 'name' => 'Farah', 'surname' => 'Zafar'],
            ['uuid' => '6fe7ac75-479b-4aa0-a7b3-9b0705918a8b', 'name' => 'Robert', 'surname' => 'Evans'],
        ];

        foreach ($exampleClients as $exampleClient) {
            $client = new Client(Uuid::fromString($exampleClient['uuid']), $exampleClient['name'], $exampleClient['surname']);
            $manager->persist($client);
        }

        $manager->flush();
    }
}
