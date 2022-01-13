<?php

namespace App\Controller\Front;

use App\Entity\Contact;
use App\Form\ContactType;
use Flasher\Toastr\Prime\ToastrFactory;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_index")
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function index(Request $request, Swift_Mailer $mailer, ToastrFactory $flasher): Response
    {
        $contact = new Contact();
        $form    = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = new Swift_Message();
            $message
                ->setTo('dylan.corroyer@wanadoo.fr')
                ->setFrom('contact.dcorroyer@gmail.com')
                ->setSubject('Acidarts: ' . $contact->getSubject())
                ->setBody($bodyHtml = $this->renderView('contact/email.html.twig', [
                    'contact' => $contact,
                ]), 'text/html')
            ;

            $mailer->send($message);

            $flasher->addSuccess('Email sent successfully!');

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
