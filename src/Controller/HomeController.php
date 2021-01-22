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
     * @param ProjectRepository $projectRepository
     * @return Response
     */
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('pages/home.html.twig', [
            'projects' => $projectRepository->findBy([],['position'=>'ASC']),
        ]);
    }
}
