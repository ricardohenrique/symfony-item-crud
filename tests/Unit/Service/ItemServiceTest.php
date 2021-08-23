<?php

namespace App\Tests\Unit;

use App\Entity\Item;
use App\Entity\User;
use App\Service\ItemService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Security\Core\Security;

class ItemServiceTest extends TestCase
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    private $entityManager;

    /**
     * @var CacheInterface|MockObject
     */
    private $cache;

    /**
     * @var Security|MockObject
     */
    private $security;

    /**
     * @var ItemService
     */
    private $itemService;

    public function setUp(): void
    {
        /** @var EntityManagerInterface */
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->cache = $this->createMock(CacheInterface::class);
        $this->security = $this->createMock(Security::class);

        $this->itemService = new ItemService($this->entityManager, $this->cache, $this->security);
    }

    public function testGetAll(): void
    {
        /** @var User */
        $user = $this->createMock(User::class);

        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);
        $this->cache->expects($this->once())
            ->method('get')
            ->willReturn([]);

        $this->assertEquals([], $this->itemService->getAll());
    }

    public function testCreateItem(): void
    {
        /** @var User */
        $user = $this->createMock(User::class);
        $data = 'secret data';

        $expectedObject = new Item();
        $expectedObject->setUser($user);
        $expectedObject->setData($data);

        $this->security->expects($this->exactly(2))
            ->method('getUser')
            ->willReturn($user);
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($expectedObject);

        $return = $this->itemService->create($data);
        $this->assertNull($return);
    }

//    TODO
//    public function testUpdateItem(): void
//    {
//    }

//    TODO
//    public function testDeleteItem(): void
//    {
//    }
}
