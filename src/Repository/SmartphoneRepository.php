<?php

namespace App\Repository;

use App\Entity\Smartphone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Smartphone>
 *
 * @method Smartphone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Smartphone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Smartphone[]    findAll()
 * @method Smartphone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmartphoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Smartphone::class);
    }

    public function save(Smartphone $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Smartphone $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Smartphone[] Returns an array of Smartphone objects
     */
    public function searchAndPaginate(int $limit, int $offset, string $search = null): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->where($qb->expr()->like('LOWER(s.name)', ':search'))
        ->orWhere($qb->expr()->like('LOWER(brand.name)', ':search'))
        ->setParameter('search', '%' . strtolower($search) . '%')
        ->orderBy('s.createdAt', 'DESC')
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->innerJoin('s.range', 'range')
        ->innerJoin('range.brand', 'brand')
        ;
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Smartphone[] Returns an array of Smartphone objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Smartphone
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
