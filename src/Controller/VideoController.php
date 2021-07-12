<?php

namespace App\Controller;

use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /**
     * @Route("/admin/video/{id}", name="admin_video_delete", methods="DELETE")
     * @param  Video $video
     * @param  Request $request
     * @return RedirectResponse
     */
    public function deleteAction(Video $video, Request $request): RedirectResponse
    {
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
