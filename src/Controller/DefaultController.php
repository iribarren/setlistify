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
     * @Route("/{_locale}", name="homepage", locale="en", requirements={"_locale":"en|es"})
     */
    public function index() {
        return $this->render('default/index.html.twig', [
                    'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/dashboard/{_locale}", name="dashboard", locale="en", requirements={"_locale":"en|es"})
     * @IsGranted("ROLE_USER")
     */
    public function dashboard() {
        return $this->render('default/dashboard.html.twig', [
                    'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/contact/{_locale}", methods={"GET"}, name="contact", locale="en", requirements={"_locale":"en|es"})
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
        
        $user = $this->getUser();
        if ($user == null) {
            $email = $request->request->get('email');
            $template = 'default/contact_success_cover.html.twig';
        } else {
            $email = $user->getEmail();
            $template = 'default/contact_success.html.twig';
        }
        $subject = $request->request->get('subject');
        $body = $request->request->get('message');
        
        $message = (new Swift_Message($subject))
                ->setFrom($email)
                ->setTo('contact@setlistify.org')
                ->setBody($this->renderView('emails/contact.html.twig',['message' => $body]),
                         'text/html');

        $mailer->send($message);

        return $this->render($template, [
                    'controller_name' => 'DefaultController',
        ]);
    }
    
    /**
     * @Route("/change_locale", name="change_locale")
     */
    public function changeLocale(Request $request) {
        
        $locale = $request->query->get('locale');
        $redirect = $request->query->get('redirect');
        $params = $request->query->get('params');
        $params['_locale'] = $locale;
        $request->setLocale($locale);
        
        return $this->redirectToRoute ($redirect, $params);
    }
}
