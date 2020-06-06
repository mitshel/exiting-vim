<?php

namespace Core\Repository;

use Core\Entity\Words;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WordsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Words::class);
    }

    public function word($word)
    {
        $query = $this->createQueryBuilder('w')
            ->select('w')
            ->where('upper(w.word) like upper(:search)')
            ->setParameter('search', $word . '%');

        return $query->getQuery()->getResult();
    }

    public function wordOne($word)
    {
        $query = $this->createQueryBuilder('w')
            ->select('w')
            ->where('upper(w.word) = upper(:search)')
            ->setParameter('search', $word);

        $result = $query->getQuery()->getResult();

        return count($result) > 0 ? $result[0] : null;
    }
}