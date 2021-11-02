<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class ItemControllerTest extends WebTestCase
{
    private $user;
    private $entityManager;

    /**
     * @var
     */
    private $client;

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->itemRepository = static::getContainer()->get(ItemRepository::class);
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $this->user = new User();
        $this->user->setUsername('Ana' . rand(999, 999999));
        $this->user->setPassword('AnaPassword');
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }

    public function testCreate(): void
    {
        $this->client->loginUser($this->user);

        $data = 'very secure new item data' . rand(999, 999999);

        $this->client->request('POST', '/item', ['data' => $data]);
        $this->client->request('GET', '/item');

        $responseContent = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($data, $responseContent);

        $newItem = $this->itemRepository->findOneByData($data);
        $this->assertEquals($data, $newItem->getData());

        $this->client->request('POST', '/item', ['data' => null]);

        $postResponseContent = $this->client->getResponse()->getContent();
        $this->assertEquals($postResponseContent, '{"error":"No data parameter"}');

        $this->client->request('GET', '/item');
        $newResponseContent = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertEquals($responseContent, $newResponseContent);
    }

    public function testUpdate(): void
    {
        $this->client->loginUser($this->user);

        $data = 'we update the data here' . rand(999, 999999);
        $this->client->request('POST', '/item', ['data' => $data]);

        $newItem = $this->itemRepository->findOneByData($data);
        $this->assertEquals($data, $newItem->getData());

        $updatedData = 'my update the data is here' . rand(999, 999999);
        $this->client->request('PUT', '/item', ['data' => $updatedData, 'id' => $newItem->getId()]);
        $this->assertResponseIsSuccessful();

        $oldItem = $this->itemRepository->findOneByData($data);
        $this->assertEmpty($oldItem, 'Item did not get updated!');

        $updatedItem = $this->itemRepository->findOneByData($updatedData);
        $this->assertEquals($updatedData, $updatedItem->getData());

        $this->client->request('GET', '/item');
        $responseContent = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($updatedData, $responseContent);
        $this->assertStringNotContainsString($data, $responseContent);
    }

    public function testDelete(): void
    {
        $this->client->loginUser($this->user);

        $data = 'we delete this next' . rand(999, 999999);
        $this->client->request('POST', '/item', ['data' => $data]);

        $newItem = $this->itemRepository->findOneByData($data);
        $this->assertEquals($data, $newItem->getData());

        $this->client->request('DELETE', '/item/' . $newItem->getId());
        $this->assertResponseIsSuccessful();

        $oldItem = $this->itemRepository->findOneByData($data);
        $this->assertEmpty($oldItem, 'Item did not get updated!');

        $this->client->request('GET', '/item');
        $responseContent = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertStringNotContainsString($data, $responseContent);
    }
}
