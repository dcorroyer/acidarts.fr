<?php
namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
     * @Route("/admin", name="admin.project.index", methods={"GET"})
     * @param ProjectRepository $projectRepository
     * @return Response
     */
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('admin/project/index.html.twig', [
            'projects' => $projectRepository->findBy([],['position'=>'ASC']),
        ]);
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
            $position = $this->repository->projectCount();
            $project->setPosition(intval($position[0][1])+1);
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
     * @Route("/admin/{id}", name="admin.project.edit", methods="GET|POST", requirements={"id"="\d+"})
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

    /**
     * @Route("/admin/move/position", name="admin_move_position", methods={"POST"})
     * @param ObjectManager $manager
     * @param Request $request
     * @return Response
     */
    public function movePosition(ObjectManager $manager, Request $request)
    {
        $direction = $request->request->get('direction');
        $id = $request->request->get('id');
        $project = $manager->getRepository(Project::class)->findOneBy(['id'=>$id]);
        if($project && ($direction == "UP" || $direction =="DOWN")){
            if($direction == "UP"){
                $newProject = $project->getPosition()-1;
                $nextProject = $manager->getRepository(Project::class)->findOneBy(['position'=>$newProject]);
                $nextProject->setPosition($nextProject->getPosition()+1);
                $project->setPosition($newProject);
                $manager->persist($nextProject);

            }elseif($direction == "DOWN"){
                $newProject = $project->getPosition()+1;
                $previousProject = $manager->getRepository(Project::class)->findOneBy(['position'=>$newProject]);
                $previousProject->setPosition($previousProject->getPosition()-1);
                $project->setPosition($newProject);
                $manager->persist($previousProject);
            }
            $manager->persist($project);
            $manager->flush();
            $response = "Le projet est la place ".$project->getPosition();
        }else{
            $response = false;
        }

        return new Response(
            $response
        );
    }
}