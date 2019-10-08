<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
    private $security;
    
    public function __construct(Security $security) {
        $this->security = $security;
    }
    
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     * @IsGranted("ROLE_USER")
     */
    public function dashboard()
    {
        return $this->render('default/dashboard.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    
    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        $template = $this->getUser() == null ? 'default/contact_cover.html.twig' : 'default/contact.html.twig' ;
        
        return $this->render($template, [
            'controller_name' => 'DefaultController',
        ]);
    }
}
