<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getItems($user)
    {
        $users = $this->createQueryBuilder('u')
            ->select('u.id', 'u.data', 'u.createdAt', 'u.updatedAt')
            ->where('u.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        return $users;
    }
}
