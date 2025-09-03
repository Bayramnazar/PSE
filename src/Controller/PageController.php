<?php

namespace App\Controller;

use App\Entity\PageContent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    // Frontend: show all
    #[Route('/page', name: 'page')]
    public function index(EntityManagerInterface $em): Response
    {
        $contents = $em->getRepository(PageContent::class)->findAll();

        return $this->render('page/index.html.twig', [
            'contents' => $contents,
        ]);
    }

    // Admin: pages
    #[Route('/admin/pages', name: 'pages_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $contents = $em->getRepository(PageContent::class)->findAll();

        return $this->render('page/list.html.twig', [
            'contents' => $contents
        ]);
    }

    // Admin: new page
    #[Route('/admin/page/new', name: 'page_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        // Hardcoded user & password
        $user = $request->getUser();
        $password = $request->getPassword();
        if ($user !== 'admin' || $password !== '1234') {
            return new Response('Unauthorized', 401, ['WWW-Authenticate' => 'Basic realm="Protected Area"']);
        }

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $contentText = $request->request->get('content');

            $content = new PageContent();
            $content->setTitle($title);
            $content->setContent($contentText);

            $em->persist($content);
            $em->flush();

            return $this->redirectToRoute('pages_list');
        }

        return $this->render('page/new.html.twig');
    }

    // Admin: edit single page
    #[Route('/admin/page/edit/{id}', name: 'page_edit_single')]
    public function editSingle(Request $request, EntityManagerInterface $em, $id): Response
    {
        // Hardcoded user & password
        $user = $request->getUser();
        $password = $request->getPassword();
        if ($user !== 'admin' || $password !== '1234') {
            return new Response('Unauthorized', 401, ['WWW-Authenticate' => 'Basic realm="Protected Area"']);
        }

        $content = $em->getRepository(PageContent::class)->find($id);

        if (!$content) {
            throw $this->createNotFoundException('Content not found');
        }

        if ($request->isMethod('POST')) {
            $content->setTitle($request->request->get('title'));
            $content->setContent($request->request->get('content'));

            $em->persist($content);
            $em->flush();

            return $this->redirectToRoute('pages_list');
        }

        return $this->render('page/edit.html.twig', [
            'content' => $content
        ]);
    }
}
