<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SetlistController extends AbstractController
{
    /**
     * @Route("/setlist", name="setlist")
     */
    public function index()
    {
        return $this->render('setlist/index.html.twig', [
            'controller_name' => 'SetlistController',
        ]);
    }
}
