<?php


namespace Core\Repository;

use Core\Entity\Instruction;
use Core\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findBetween($min, $max)
    {
        $query = $this->createQueryBuilder('i')
            ->select('i')
            ->where('i.id >= :min')
            ->andWhere('i.id <= :max')
            ->setParameter('min', $min)
            ->setParameter('max', $max)
        ;

        return $query->getQuery()->getResult();
    }

}