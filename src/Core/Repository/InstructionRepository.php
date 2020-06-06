<?php


namespace Core\Repository;


use Core\Entity\Instruction;
use Core\Entity\Participles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InstructionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instruction::class);
    }

    public function findArr($post)
    {
        $query = $this->createQueryBuilder('i')
            ->select('distinct i, ii.name, item.name it')
            ->join('i.post', 'ii')
            ->join('i.item', 'item')
            ->where('i.post = :post')
            ->setParameter('post', $post);

        return $query->getQuery()->getArrayResult();
    }

}