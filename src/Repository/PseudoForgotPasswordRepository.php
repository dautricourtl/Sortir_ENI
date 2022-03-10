<?php

namespace App\Repository;

use App\Entity\PseudoForgotPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PseudoForgotPassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method PseudoForgotPassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method PseudoForgotPassword[]    findAll()
 * @method PseudoForgotPassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PseudoForgotPasswordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PseudoForgotPassword::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PseudoForgotPassword $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(PseudoForgotPassword $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return PseudoForgotPassword[] Returns an array of PseudoForgotPassword objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PseudoForgotPassword
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
