<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProjController extends AbstractController
{
    #[Route('/proj', name: 'project')]
    public function projIndex(): Response
    {
        return $this->render('proj/home.html.twig');
    }
    #[Route('/proj/about', name: 'project_about')]
    public function projAbout(): Response
    {
        return $this->render('proj/about.html.twig');
    }
    #[Route('/proj/api', name: 'project_api')]
    public function projApi(): Response
    {
        return $this->render('proj/api.html.twig');
    }
    #[Route('/proj/about/database', name: 'project_about_database')]
    public function projAboutDatabase(): Response
    {
        return $this->render('proj/database.html.twig');
    }
}
