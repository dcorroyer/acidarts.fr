<?php

namespace App\Controller\Front;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/projects/{slug}", name="project_show", methods={"GET"}, requirements={"slug": "[a-z0-9\-]*"})
     * @param  Project $project
     * @return Response
     */
    public function showAction(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }
}
