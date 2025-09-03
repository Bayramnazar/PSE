<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonalPageController extends AbstractController
{
    #[Route('/bayramnazaresenov', name: 'personal_page')]
    public function index(): Response
    {
        return $this->render('personal_page/index.html.twig');
    }
}
