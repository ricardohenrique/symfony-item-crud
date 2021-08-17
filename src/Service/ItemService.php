<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private $entityManager;

    /**
     * ItemService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getAll(User $user): array
    {
        $items = $this->entityManager->getRepository(Item::class)->findBy(['user' => $user]);
        $items = $this->buildItemList($items);
        return $items;
    }

    /**
     * @param User $user
     * @param string $data
     */
    public function create(User $user, string $data): void
    {
        $item = new Item();
        $item->setUser($user);
        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    /**
     * @param array $data
     * @return object
     */
    public function update(array $data): object
    {
        $item = $this->entityManager->getRepository(Item::class)->find($data['id']);

        if ($item === null) {
            return null;
        }

        $item->setData($data['data']);
        $this->entityManager->flush();

        return $item;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $item = $this->entityManager->getRepository(Item::class)->find($id);

        if ($item === null) {
            return null;
        }

        $manager = $this->entityManager;
        $manager->remove($item);
        $manager->flush();

        return true;
    }

    /**
     * @param $items
     * @return array
     */
    public function buildItemList($items)
    {
        $allItems = [];

        foreach ($items as $item) {
            $oneItem['id'] = $item->getId();
            $oneItem['data'] = $item->getData();
            $oneItem['created_at'] = $item->getCreatedAt();
            $oneItem['updated_at'] = $item->getUpdatedAt();
            $allItems[] = $oneItem;
        }

        return $allItems;
    }
}
