<?php

namespace App\Repository;

use App\Entity\Setlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Setlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setlist[]    findAll()
 * @method Setlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SetlistRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Setlist::class);
    }
    
    public function getAllSetlistsUser($user, $page = 1, $limit = 10)
    {
        $query = $this->createQueryBuilder('s')->getQuery();
        
        $paginator = new Paginator($query);
        
        $paginator->getQuery()
                  ->where('s.user_id = :user_id')
                  ->setParameter('user_id', $user->getId())
                  ->setFirstResult($limit * ($page - 1))
                  ->setMaxResults($limit);
        
        return $paginator;
    }

    // /**
    //  * @return Setlist[] Returns an array of Setlist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Setlist
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
