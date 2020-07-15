<?php

namespace App\Repository;

use App\Entity\ResultComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResultComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultComment[]    findAll()
 * @method ResultComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultComment::class);
    }

    public function findOneByQuizAndScore($quiz, $score): ?ResultComment
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.quiz = :quiz')
            ->andWhere('r.upperBound >= :score')
            ->andWhere('r.lowerBound <= :score')
            ->setParameter('quiz', $quiz)
            ->setParameter('score', $score)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return ResultComment[] Returns an array of ResultComment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResultComment
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
