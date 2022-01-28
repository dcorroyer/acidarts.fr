<?php

namespace App\Controller\Back;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{
    /**
     * @var PictureRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PictureRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em         = $em;
    }

    /**
     * @Route("/admin/picture/{id}", name="admin_picture_delete", methods="DELETE")
     * @param Picture $picture
     * @param Request $request
     * @param ToastrFactory $flasher
     * @return RedirectResponse
     */
    public function deleteAction(Picture $picture, Request $request, ToastrFactory $flasher): RedirectResponse
    {
        $projectId = $picture->getProject()->getId();

        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $request->request->get('_token'))) {
            $this->em->remove($picture);
            $pictures = $this->repository->picturesHigherPosition($picture->getPosition(), $projectId);

            foreach ($pictures as $picture) {
                $picture->setPosition($picture->getPosition() - 1);
            }

            $this->em->flush();

            $flasher->addSuccess('Picture deleted successfully!');
        }

        return $this->redirectToRoute('admin_project_edit', [
            'id' => $projectId
        ]);
    }
}
