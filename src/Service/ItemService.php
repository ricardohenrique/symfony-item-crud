<?php

namespace App\Service;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Security\Core\Security;

class ItemService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var Security
     */
    private $security;

    /**
     * ItemService constructor.
     * @param EntityManagerInterface $entityManager
     * @param CacheInterface $cache
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $entityManager, CacheInterface $cache, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
        $this->security = $security;
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     */
    public function getAll(): array
    {
        $itemsCached = $this->cache->get('items.user.' . $this->security->getUser()->getId(), function (ItemInterface $item) {
            $items = $this->entityManager->getRepository(Item::class)->findBy(['user' => $this->security->getUser()]);
            return $this->itemsToArray($items);
        });
        return $itemsCached;
    }

    /**
     * @param string $data
     * @throws InvalidArgumentException
     */
    public function create(string $data): void
    {
        $item = new Item();
        $item->setUser($this->security->getUser());
        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        $this->cache->delete('items.user.' . $this->security->getUser()->getId());
    }

    /**
     * @param array $data
     * @return object|null
     * @throws InvalidArgumentException
     */
    public function update(array $data): ?object
    {
        $item = $this->entityManager->getRepository(Item::class)->findOneBy([
            'id' => $data['id'],
            'user' => $this->security->getUser(),
        ]);

        if ($item === null) {
            return null;
        }

        $item->setData($data['data']);
        $this->entityManager->flush();

        $this->cache->delete('items.user.' . $this->security->getUser()->getId());

        return $item;
    }

    /**
     * @param int $id
     * @return bool|null
     * @throws InvalidArgumentException
     */
    public function delete(int $id): ?bool
    {
        $item = $this->entityManager->getRepository(Item::class)->findOneBy([
            'id' => $id,
            'user' => $this->security->getUser(),
        ]);

        if ($item === null) {
            return null;
        }

        $manager = $this->entityManager;
        $manager->remove($item);
        $manager->flush();

        $this->cache->delete('items.user.' . $this->security->getUser()->getId());

        return true;
    }

    /**
     * @param array $items
     * @return array
     */
    public function itemsToArray(array $items): array
    {
        $data = [];

        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'data' => $item->getData(),
                'created_at' => $item->getCreatedAt(),
                'updated_at' => $item->getUpdatedAt()
            ];
        }

        return $data;
    }
}
