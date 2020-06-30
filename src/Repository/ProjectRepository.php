<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @return int
     */
    public function projectCount()
    {
        try {
            return $this->createQueryBuilder('p')
                ->select('COUNT(p)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 1;
        } catch (NonUniqueResultException $e) {
            return 1;
        }
    }

    /**
     * @param $position
     * @return Project[]
     */
    public function projectsHigherPosition($position): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.position > :position')
            ->setParameter('position', $position)
            ->orderBy('p.position', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    // /**
    //  * @return Project[] Returns an array of Project objects
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
    public function findOneBySomeField($value): ?Project
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
