<?php

namespace App\Controller\Admin;

use App\Entity\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPictureController extends AbstractController
{
    /**
     * @Route("/admin/picture/{id}", name="admin.picture.delete", methods="DELETE")
     * @param Picture $picture
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Picture $picture, Request $request): RedirectResponse
    {
        $projectId = $picture->getProject()->getId();
        
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($picture);
            $em->flush();
        }

        return $this->redirectToRoute('admin.project.edit', [
            'id' => $projectId
        ]);
    }

}