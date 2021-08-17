<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(User $user, string $data): void
    {
        $item = new Item();
        $item->setUser($user);
        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }
} 