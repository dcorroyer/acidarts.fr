<?php

namespace App\Controller\Front;

use App\Form\ContactType;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_index")
     *
     * @param Request $request
     * @param MailerInterface $mailer
     * @param ToastrFactory $flasher
     *
     * @return Response
     *
     * @throws TransportExceptionInterface
     */
    public function index(Request $request, MailerInterface $mailer, ToastrFactory $flasher): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
            $message         = (new Email())
                ->from($contactFormData['email'])
                ->to('dylan.corroyer@wanadoo.fr')
                ->subject($contactFormData['subject'])
                ->html($this->renderView('contact/email.html.twig', [
                    'contact' => $contactFormData,
                ]), 'text/html')
            ;

            $mailer->send($message);

            $flasher->addSuccess('Email sent successfully!');

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/index.html.twig', [
            'form'    => $form->createView()
        ]);
    }
}
