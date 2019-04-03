<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DescriptionController extends AbstractController
{
    /**
     * @Route("/description", name="description.index")
     * @return Response
     */
    public function index()
    {
        return $this->render('pages/description.html.twig', [
            'current_menu' => 'description'
        ]);
    }
}