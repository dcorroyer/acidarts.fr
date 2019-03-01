<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
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
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProjectRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/projects", name="project.index")
     * @return Response
     */
    public function index(): Response
    {
        $projects = $this->repository->findAll();
        return $this->render('project/index.html.twig', [
            'current_menu' => 'projects',
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/projects/{slug}-{id}", name="project.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Project $project
     * @param string $slug
     * @return Response
     */
    public function show(Project $project, string $slug): Response
    {
        if($project->getSlug() !== $slug) {
            return $this->redirectToRoute('project.show', [
               'id' => $project->getId(),
               'slug' => $project->getSlug()
            ], 301);
        }
        return $this->render('project/show.html.twig', [
            'project' => $project,
           'curren_menu' => 'projects'
        ]);
    }
}