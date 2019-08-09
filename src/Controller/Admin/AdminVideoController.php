<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/video")
 */
class AdminVideoController extends AbstractController {

    /**
     * @Route("/{id}", name="admin.video.delete", methods="DELETE")
     * @param Video $video
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Video $video, Request $request) {
        $projectId = $video->getProject()->getId();
        if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($video);
            $em->flush();
        }
        return $this->redirectToRoute('admin.project.edit', [
            'id' => $projectId
        ]);
    }

}