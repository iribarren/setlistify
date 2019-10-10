<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController {

    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index() {
        return $this->render('default/index.html.twig', [
                    'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     * @IsGranted("ROLE_USER")
     */
    public function dashboard() {
        return $this->render('default/dashboard.html.twig', [
                    'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/contact", methods={"GET"}, name="contact")
     */
    public function contact() {
        $template = $this->getUser() == null ? 'default/contact_cover.html.twig' : 'default/contact.html.twig';

        return $this->render($template, [
                    'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/contact", methods={"POST"}, name="contact_post")
     */
    public function contactPost(Request $request, Swift_Mailer $mailer) {
        
        $subject = $request->request->get('subject');
        $message = $request->request->get('message');
        $email = $request->request->get('email');
        
        $message = (new Swift_Message($subject))
                ->setFrom($email)
                ->setTo('contact@setlistify.org')
                ->setBody($this->renderView('emails/contact.html.twig',['message' => $message]),
                         'text/html');

        $mailer->send($message);

        $template = $this->getUser() == null ? 'default/contact_success_cover.html.twig' : 'default/contact_success.html.twig';

        return $this->render($template, [
                    'controller_name' => 'DefaultController',
        ]);
    }

}
