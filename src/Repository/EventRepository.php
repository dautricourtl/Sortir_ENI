<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Event $entity, bool $flush = true): void
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
    public function remove(Event $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function filterAndSearch($em, $name, $site, $dateDebut, $dateFin, $isOwner, $isInscrit, $isNotInscrit, $pastEvent)
    {

        $expr = $em->getExpressionBuilder();
        $em->createQueryBuilder();
        $qb = $this->createQueryBuilder('e');

        if($name)
        {
            $qb->andWhere('e.name LIKE :name')
            ->setParameter('name', '%'.$name.'%');
        }
         if($site)
         {
            $qb
            ->andWhere('e.owner_id')
            ->join('e.owner', 'u')
            ->join('u.site', 's')
            ->where('s.id = :id')
            ->setParameter('id', $site)
            ;
         }
        if($dateDebut && $dateFin)
        {
            $qb->andWhere('e.beginAt BETWEEN :dateDebut and :dateFin')
            ->setParameter('dateDebut', $dateDebut) 
            ->setParameter('dateFin', $dateFin); 
        }
        if($isOwner)
        {
            $qb->andWhere('e.owner = :id')
            ->setParameter('id', $isOwner);
        }
        if($isInscrit)
        {
            $qb
            ->join('e.participants', 'p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $isInscrit);
        }
        if($isNotInscrit)
        {
            $qb
            ->andWhere($expr->notIn(
                'e.id',
                $em->createQueryBuilder()
                                ->select('e2.id')
                                ->from(Event::class, 'e2')
                                ->join('e2.participants', 'p2')
                                ->andWhere('p2 = :user')
                        ->getDQL()
            ))
            ->setParameter(':user', $isNotInscrit)
            ;
        }

        if($pastEvent)
        {
            $qb->andWhere('e.state = :id')
            ->setParameter('id', $pastEvent);
        }

        return $qb
        ->getQuery()
        ->getResult()
        ;


    }
}
