<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @var ProjectRepository
     */
    private $repository;
    
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ProjectRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em         = $em;
    }

    /**
     * @Route("/projects/{slug}-{id}", name="project.show", requirements={"slug": "[a-z0-9\-]*"})
     * @Security("project.getSlug() == slug")
     * @param Project $project
     * @return Response
     */
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project'      => $project,
            'current_menu' => 'projects'
        ]);
    }
}
