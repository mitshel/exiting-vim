<?php


namespace Core\Repository;

use Core\Entity\Verbs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class VerbsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Verbs::class);
    }

    public function word1($word)
    {
        $word = mb_strtolower($word);
        $query = $this->createQueryBuilder('v')
            ->select('v')
            ->where('upper(v.word) like upper(:search)')
            ->setParameter('search', '%' . $word . '%')->setMaxResults(1);

        try {
            return $query->getQuery()->getSingleResult();
        } catch (\Exception $e) {
            dump($e->getMessage());
            return null;
        }
    }

    public function word2($word)
    {
        $word = mb_strtolower(trim($word));
        $query = $this->createQueryBuilder('v')
            ->select('v')
            ->where('upper(v.word) like upper(:search)')
            ->setParameter('search', $word)->setMaxResults(1);
        try {
            return $query->getQuery()->getSingleResult();
        } catch (\Exception $e) {
            return null;
        }
    }
}