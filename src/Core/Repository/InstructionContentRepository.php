<?php


namespace Core\Repository;


use Core\Entity\Instruction;
use Core\Entity\InstructionContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InstructionContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstructionContent::class);
    }

    public function findArr($post, $section)
    {
        $query = $this->createQueryBuilder('i')
            ->select('i, ii.id ins, s.name sec, item.name')
            ->join('i.section', 's')
            ->join('i.instruction', 'ii')
            ->join('i.item', 'item')
            ->where('s.id = :section')
            ->andWhere('ii.id = :id')
            ->setParameter('section', $section)
            ->setParameter('id', $post);

        return $query->getQuery()->getArrayResult();
    }

}