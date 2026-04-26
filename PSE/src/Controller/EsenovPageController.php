<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EsenovPageController extends AbstractController
{
    #[Route('/esenov', name: 'esenov_page')]
    public function index(): Response
    {
        return $this->render('esenov_page/index.html.twig', [
            'controller_name' => 'EsenovPageController',
        ]);
    }
}
