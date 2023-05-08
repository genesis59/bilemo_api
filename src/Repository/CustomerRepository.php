<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly Security $security)
    {
        parent::__construct($registry, Customer::class);
    }

    public function save(Customer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Customer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Customer[] Returns an array of Customer objects
     */
    public function searchAndPaginate(int $limit, int $offset, string $search = null): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where($qb->expr()->like('LOWER(c.firstName)', ':search'))
            ->orWhere($qb->expr()->like('LOWER(c.lastName)', ':search'))
            ->orWhere($qb->expr()->like('LOWER(c.email)', ':search'))
            ->andWhere('c.reseller = :reseller')
            ->setParameter('search', '%' . strtolower($search) . '%')
            ->setParameter('reseller', $this->security->getUser())
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
        ;
        return $qb->getQuery()->getResult();
    }

    /**
     * @param array<mixed> $args
     * @return array<Customer>
     */
    public function getSimilarEmailForReseller(array $args): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.email = :email')
            ->andWhere('c.reseller = :reseller')
            ->setParameter(':email', $args['email'])
            ->setParameter(':reseller', $this->security->getUser())
            ->getQuery()->getResult();
    }


//    /**
//     * @return Customer[] Returns an array of Customer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Customer
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
