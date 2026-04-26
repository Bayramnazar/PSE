<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactMessageType;
use App\Repository\ContactMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EsenovPageController extends AbstractController
{
    #[Route('/esenov', name: 'esenov_page')]
    public function index(
        Request $request, 
        EntityManagerInterface $entityManager,
        ContactMessageRepository $contactRepository
    ): Response {
        
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contactMessage);
            $entityManager->flush();

            $this->addFlash('success', 'Hocam mesajın başarıyla kaydedildi!');
            return $this->redirectToRoute('esenov_page');
        }

        // Sayfanın altında eski mesajları da listeleyelim
        $oldMessages = $contactRepository->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('esenov_page/index.html.twig', [
            'esenov_form' => $form->createView(),
            'messages' => $oldMessages
        ]);
    }
}