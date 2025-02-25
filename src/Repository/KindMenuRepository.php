<?php

namespace App\Repository;

use App\Entity\KindMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeMenu>
 *
 * @method TypeMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeMenu[]    findAll()
 * @method TypeMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KindMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KindMenu::class);
    }

    public function getQueryForAllKindMenus(): ?string
    {
        return $this->createQueryBuilder('k')
            ->getQuery()->getDQL();
    }

//    /**
//     * @return TypeMenu[] Returns an array of TypeMenu objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeMenu
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
