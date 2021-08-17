<?php

namespace App\Tests;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ItemControllerTest extends WebTestCase
{
    public function testGetItems()
    {
        $data = 'NewItem';
        $client = $this->initItem($data);
        $client->request('GET', '/item');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($data, $client->getResponse()->getContent());
    }

    public function testCreateItem()
    {
        $data = 'very secure new item data';
        $client = $this->initItem($data);
        $client->request('GET', '/item');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($data, $client->getResponse()->getContent());

        $itemRepository = static::$container->get(ItemRepository::class);
        $item = $itemRepository->findOneByData($data);
        $this->assertInstanceOf(Item::class, $item);
    }

    public function testUpdateItem()
    {
        $data = 'Item to be updated';
        $client = $this->initItem($data);
        $client->request('GET', '/item');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($data, $client->getResponse()->getContent());

        $item = json_decode($client->getResponse()->getContent())[0];
        $client->request(
            'PUT', '/item/'.$item->id, [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['data' => $data])
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals('[]', $client->getResponse()->getContent());
    }

    private function initItem($data)
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');

        $client->loginUser($user);
        $client->request('POST', '/item', ['data' => $data]);

        return $client;
    }
}
