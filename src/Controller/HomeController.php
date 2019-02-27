<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index(ProjectRepository $repository): Response
    {
        $projects = $repository->findLatest();
        return $this->render('pages/home.html.twig', [
            'projects' => $projects
        ]);
    }
}