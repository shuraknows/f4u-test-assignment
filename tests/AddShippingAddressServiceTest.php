<?php

namespace App\Tests;

use App\Application\Service\Client\ClientNotFoundException;
use App\Application\Service\Client\ClientOutputService;
use App\Application\Service\Client\ShippingAddress\AddShippingAddressService;
use App\Domain\Model\CantAddShippingAddressException;
use App\Domain\Model\Client\Client;
use App\Domain\Model\Client\ClientId;
use App\Domain\Model\ShippingAddress\ShippingAddress;
use App\Domain\Model\ShippingAddress\ShippingAddressId;
use App\Infrastructure\Persistence\ClientRepository;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AddShippingAddressServiceTest extends TestCase
{
    /**
     * @var AddShippingAddressService
     */
    private $addShippingAddressService;

    /**
     * @var MockObject|ClientRepository
     */
    private $clientRepositoryMock;
    /**
     * @var ClientOutputService
     */
    private $clientOutputService;

    /**
     * @var Client
     */
    private $testClient;


    public function setUp()
    {
        $this->clientRepositoryMock = $this->getMockBuilder(ClientRepository::class)
            ->setMethods(['getById', 'update'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->clientOutputService = new ClientOutputService();

        $this->addShippingAddressService = new AddShippingAddressService(
            $this->clientRepositoryMock, $this->clientOutputService
        );

        $this->testClient = new Client(
            new ClientId('55bd59ec-a2ca-4b3f-bb2c-886e421ef6f0'),
            'John',
            'Doe'
        );
    }

    /**
     * @param array $testShippingAddressesData
     * @throws ClientNotFoundException
     * @throws ORMException
     * @dataProvider addShippingAddressDataProvider
     */
    public function testFirstShippingAddressCanBeAdded($testShippingAddressesData)
    {
        $countOfAddresses = count($testShippingAddressesData);
        $this->clientRepositoryMock->expects($this->exactly($countOfAddresses))
            ->method('getById')
            ->willReturn($this->testClient);

        if ($countOfAddresses > Client::MAX_SHIPPING_ADDRESSES_COUNT) {
            $this->expectException(CantAddShippingAddressException::class);
        } else {
            $this->clientRepositoryMock->expects($this->exactly($countOfAddresses))->method('update');
        }

        foreach ($testShippingAddressesData as $key => $testShippingAddressesDatum) {
            $result = $this->addShippingAddressService->run(
                $this->testClient->getId()->toString(),
                $testShippingAddressesDatum
            );

            $newShippingAddressId = $result['id'];
            $addedShippingAddress = $this->testClient->getShippingAddress(new ShippingAddressId($newShippingAddressId));
            $this->assertInstanceOf(ShippingAddress::class, $addedShippingAddress);

            $this->assertEquals($testShippingAddressesDatum['street'], $addedShippingAddress->getStreet());
            $this->assertEquals($testShippingAddressesDatum['zipCode'], $addedShippingAddress->getZipCode());
            $this->assertEquals($testShippingAddressesDatum['country'], $addedShippingAddress->getCountry());
            $this->assertEquals(($key === 0), $addedShippingAddress->isDefault());
        }
    }

    /**
     *
     * Data provider for testFirstShippingAddressCanBeAdded
     * @return array
     */
    public function addShippingAddressDataProvider()
    {
        return [
            [
                'testShippingAddressData' => [
                    [
                        'country' => 'United Kingdom',
                        'city' => 'London',
                        'zipCode' => '11122',
                        'street' => 'Albert Embankment',
                    ],
                ],
            ],
            [
                'testShippingAddressData' => [
                    [
                        'country' => 'United Kingdom',
                        'city' => 'London',
                        'zipCode' => '11122',
                        'street' => 'Albert Embankment',
                    ],
                    [
                        'country' => 'US',
                        'city' => 'Detroit',
                        'zipCode' => '33390',
                        'street' => 'Elm Street',
                    ],
                    [
                        'country' => 'Italy',
                        'city' => 'Milan',
                        'zipCode' => '02029',
                        'street' => 'Via de Ruggiero',
                    ],
                ]
            ],

            [
                'testShippingAddressData' => [
                    [
                        'country' => 'United Kingdom',
                        'city' => 'London',
                        'zipCode' => '11122',
                        'street' => 'Albert Embankment',
                    ],
                    [
                        'country' => 'US',
                        'city' => 'Detroit',
                        'zipCode' => '33390',
                        'street' => 'Elm Street',
                    ],
                    [
                        'country' => 'Italy',
                        'city' => 'Milan',
                        'zipCode' => '02029',
                        'street' => 'Via de Ruggiero',
                    ],
                    [
                        'country' => 'Italy',
                        'city' => 'Milan',
                        'zipCode' => '02029',
                        'street' => 'Via de Ruggiero',
                    ],
                ]
            ]
        ];
    }
}