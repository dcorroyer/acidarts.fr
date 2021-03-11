<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    ### FRONT-OFFICE ###
    /**
     * @Route("/projects/{slug}-{id}", name="project_show", methods={"GET"}, requirements={"slug": "[a-z0-9\-]*"})
     * @Security("project.getSlug() == slug")
     * @param  Project $project
     * @return Response
     */
    public function showAction(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    ### BACK-OFFICE ###
    /**
     * @Route("/admin", name="admin_project_index", methods={"GET"})
     * @param  ProjectRepository $projectRepository
     * @return Response
     */
    public function indexAction(ProjectRepository $projectRepository): Response
    {
        return $this->render('admin/project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/projects/new", name="admin_project_new", methods={"GET","POST"})
     * @param  Request $request
     * @return Response
     */
    public function newAction(Request $request): Response
    {
        $project = new Project();
        $form    = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('admin_project_index');
        }

        return $this->render('admin/project/new.html.twig', [
            'project' => $project,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/projects/{id}/edit", name="admin_project_edit", methods={"GET","POST"})
     * @param  Request $request
     * @param  Project $project
     * @return Response
     */
    public function editAction(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_project_index');
        }

        return $this->render('admin/project/edit.html.twig', [
            'project' => $project,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/projects/{id}", name="admin_project_delete", methods={"DELETE"})
     * @param  Request $request
     * @param  Project $project
     * @return Response
     */
    public function deleteAction(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_project_index');
    }
}
