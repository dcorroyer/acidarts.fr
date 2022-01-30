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
     * Count projects
     *
     * @return int
     */
    public function projectCount(): int
    {
        try {
            return $this->createQueryBuilder('p')
                ->select('COUNT(p)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 1;
        }
    }

    /**
     * Retrieve the project with the higher position
     *
     * @param $position
     *
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
}
