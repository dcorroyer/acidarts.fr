<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProjectController extends AbstractController
{
    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ProjectRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em         = $em;
    }

    ### FRONT-OFFICE ###
    /**
     * @Route("/projects/{slug}", name="project_show", methods={"GET"}, requirements={"slug": "[a-z0-9\-]*"})
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
            'projects' => $projectRepository->findBy([],['position'=>'ASC']),
        ]);
    }

    /**
     * @Route("/admin/projects/json", name="admin_project_json", methods={"GET"})
     * @param ProjectRepository $projectRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function getProjectsAction(ProjectRepository $projectRepository, SerializerInterface $serializer): Response
    {
        $response = new Response();
        $response->setContent($serializer->serialize($projectRepository->findBy([],['position'=>'ASC']), 'json', ['groups' => 'show_projects']));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @Route("/admin/projects/new", name="admin_project_new", methods={"GET","POST"})
     * @param Request $request
     * @param ToastrFactory $flasher
     * @return Response
     */
    public function newAction(Request $request, ToastrFactory $flasher): Response
    {
        $project = new Project();
        $form    = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $position = $this->repository->projectCount();

            $project->setPosition(intval($position) + 1);
            $this->em->persist($project);
            $this->em->flush();
            
            $flasher->addSuccess('Project created successfully!');

            return $this->redirectToRoute('admin_project_index');
        }

        return $this->render('admin/project/new.html.twig', [
            'project' => $project,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/projects/{id}/edit", name="admin_project_edit", methods={"GET","POST"}, options={"expose" = true})
     * @param Request $request
     * @param Project $project
     * @param ToastrFactory $flasher
     * @return Response
     */
    public function editAction(Request $request, Project $project, ToastrFactory $flasher): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            
            $flasher->addSuccess('Project edited successfully!');

            return $this->redirectToRoute('admin_project_index');
        }

        return $this->render('admin/project/edit.html.twig', [
            'project' => $project,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/projects/{id}/{token}", name="admin_project_delete", methods={"GET","DELETE"}, options={"expose" = true})
     * @param Request $request
     * @param Project $project
     * @param ToastrFactory $flasher
     * @param $token
     * @return Response
     */
    public function deleteAction(Request $request, Project $project, ToastrFactory $flasher, $token): Response
    {
        if ($this->isCsrfTokenValid($this->getUser()->getId(), $token)) {
            $this->em->remove($project);
            $projects = $this->repository->projectsHigherPosition($project->getPosition());

            foreach ($projects as $project) {
                $project->setPosition($project->getPosition() - 1);
            }

            $this->em->flush();
            
            $flasher->addSuccess('Project deleted successfully!');
        }

        return $this->redirectToRoute('admin_project_index');
    }

    /**
     * @Route("/admin/projects/move", name="admin_project_move", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function moveAction(Request $request): Response
    {
        $projects = json_decode($request->getContent())->projects;
        
        for ($i = 0; $i < count($projects); $i++) {
            $project = $this->repository->find($projects[$i]->id);
            $project->setPosition($i+1);
        }

        $this->em->flush();

        return new Response("", 204);
    }
}
