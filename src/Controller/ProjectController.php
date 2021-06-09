<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
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
        $position = $this->repository->projectCount();

        return $this->render('admin/project/index.html.twig', [
            'projects' => $projectRepository->findBy([],['position'=>'ASC']),
            'position' => $position,
        ]);
    }

    /**
     * @Route("/admin/projects/json", name="admin_project_json", methods={"GET"})
     * @param ProjectRepository $projectRepository
     * @return Response
     */
    public function getProjectsAction(ProjectRepository $projectRepository, SerializerInterface $serializer): Response
    {
        $response = new Response();
        $response->setContent($serializer->serialize($projectRepository->findAll(), 'json', ['groups' => 'show_projects']));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

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
            $position = $this->repository->projectCount();

            $project->setPosition(intval($position) + 1);
            $this->em->persist($project);
            $this->em->flush();
            $this->addFlash('success', 'Project created with success !');

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
            $this->em->flush();
            $this->addFlash('success', 'The project has been modified with success !');

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
            $this->em->remove($project);
            $projects = $this->repository->projectsHigherPosition($project->getPosition());

            foreach ($projects as $project) {
                $project->setPosition($project->getPosition() - 1);
            }

            $this->em->flush();
            $this->addFlash('success', 'The project has been deleted with success !');
        }

        return $this->redirectToRoute('admin_project_index');
    }

    /**
     * @Route("/admin/move/position", name="admin_move_position", methods={"POST"})
     * @param  EntityManagerInterface $manager
     * @param  Request $request
     * @return Response
     */
    public function movePosition(EntityManagerInterface $manager, Request $request): Response
    {
        $direction = $request->request->get('direction');
        $id        = $request->request->get('id');
        $project   = $manager
            ->getRepository(Project::class)
            ->findOneBy(['id' => $id]);

        if ($project && ($direction == "UP" || $direction =="DOWN")) {
            if ($direction == "UP") {
                $newProject  = $project->getPosition()-1;
                $nextProject = $manager->getRepository(Project::class)->findOneBy(['position'=>$newProject]);

                $nextProject->setPosition($nextProject->getPosition()+1);
                $project->setPosition($newProject);
                $manager->persist($nextProject);

            } elseif ($direction == "DOWN") {
                $newProject      = $project->getPosition()+1;
                $previousProject = $manager
                    ->getRepository(Project::class)
                    ->findOneBy(['position'=>$newProject]);

                $previousProject->setPosition($previousProject->getPosition()-1);
                $project->setPosition($newProject);
                $manager->persist($previousProject);

            }

            $manager->persist($project);
            $manager->flush();
            $response = "Le projet est à la place ".$project->getPosition();
        } else {
            $response = false;
        }

        return new Response(
            $response
        );
    }
}
