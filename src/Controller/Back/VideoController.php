<?php

namespace App\Controller\Back;

use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin/video/{id}", name="admin_video_delete", methods="DELETE")
     *
     * @param  Video $video
     * @param  Request $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(Video $video, Request $request): RedirectResponse
    {
        $projectId = $video->getProject()->getId();

        if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {
            $this->em->remove($video);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin_project_edit', [
            'id' => $projectId
        ]);
    }
}
