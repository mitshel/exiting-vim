<?php


namespace Core\Repository;


use Core\Entity\Participles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class ParticiplesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participles::class);
    }

    public function word($word)
    {
        $query = $this->createQueryBuilder('w')
            ->select('w')
            ->where('lower(w.word) like lower(:search)')
            ->setParameter('search', $word . '%')
            ->setMaxResults(1);


        try {
            return $query->getQuery()->getSingleResult();
        } catch (\Exception $e) {
            return null;
        }
    }
}