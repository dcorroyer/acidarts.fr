<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    /**
     * @param $project
     * @return Picture[]
     */
    public function retrievePicturesFromProject($project): array
    {
        return $this->createQueryBuilder('p')
                ->leftJoin('p.project', 'project')
                ->where('project.id = :id')
                ->setParameter('id', $project)
                ->getQuery()
                ->execute();
    }

    /**
     * @param $position
     * @return Picture[]
     */
    public function picturesHigherPosition($position, $project): array
    {
        $qb = $this->createQueryBuilder('p')
                ->leftJoin('p.project', 'project')
                ->where('project.id = :id')
                ->setParameter('id', $project)
                ->andWhere('p.position > :position')
                ->setParameter('position', $position)
                ->orderBy('p.position', 'ASC')
                ->getQuery();

        return $qb->execute();
    }
}
