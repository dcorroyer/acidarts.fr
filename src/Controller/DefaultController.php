<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="project_index", methods={"GET"})
     * @param  ProjectRepository $projectRepository
     * @return Response
     */
    public function indexAction(ProjectRepository $projectRepository): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findBy([],['position'=>'ASC']),
        ]);
    }

    /**
     * @Route("/about", name="about_index")
     * @return Response
     */
    public function aboutShowAction(): Response
    {
        return $this->render('about/index.html.twig');
    }
}
