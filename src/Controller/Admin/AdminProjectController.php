<?php
namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProjectController extends AbstractController
{

    /**
     * @var ProjectRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProjectRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.project.index")
     * @return Response
     */
    public function index()
    {
        $projects = $this->repository->findAll();
        return $this->render('admin/project/index.html.twig', compact('projects'));
    }

    /**
     * @Route("/admin/project/create", name="admin.project.new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($project);
            $this->em->flush();
            $this->addFlash('success', 'Le projet a été créé avec succès');
            return $this->redirectToRoute('admin.project.index');
        }

        return $this->render('admin/project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/admin/{id}", name="admin.project.edit", methods="GET|POST")
     * @param Project $project
     * @param Request $request
     * @return Response
     */
    public function edit(Project $project, Request $request)
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Le projet a été modifié avec succès');
            return $this->redirectToRoute('admin.project.index');
        }

        return $this->render('admin/project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/{id}", name="admin.project.delete", methods="DELETE")
     * @param Project $project
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Project $project, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $project->getId(), $request->get('_token'))) {
            $this->em->remove($project);
            $this->em->flush();
            $this->addFlash('success', 'Le projet a été supprimé avec succès');
        }
        return $this->redirectToRoute('admin.project.index');
    }
}