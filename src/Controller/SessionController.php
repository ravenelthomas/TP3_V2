<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SessionController extends AbstractController
{
    #[Route('/admin/session/', name: 'app_session_index', methods: ['GET'])]
    public function index(SessionRepository $sessionRepository): Response
    {
        
        return $this->render('session/index.html.twig', [
            'sessions' => $sessionRepository->findAll(),
        ]);
    }

    #[Route('/admin/session/new', name: 'app_session_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('session/new.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }

    #[Route('/admin/session/{id}', name: 'app_session_show', methods: ['GET'])]
    public function show(Session $session): Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session,
        ]);
    }

    #[Route('/admin/session/{id}/edit', name: 'app_session_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Session $session, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('session/edit.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }

    #[Route('/admin/session/{id}/delete', name: 'app_session_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Session $session, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('session/show.html.twig', [
            'session' => $session,
        ]);
    }
    #[Route('/start_session/{id}', 'start_session', methods: ['GET', 'POST'])]

    public function startSession(Request $request, Session $session, EntityManagerInterface $entityManager): Response
    {   
        $startDate = new \DateTime();
        $session->changeStartTime($startDate);
        $session->setInSession(true);
        $entityManager->persist($session);
        $entityManager->flush();
        return $this->render('session/show_details_sessions.html.twig', [
            'session' => $session,
            'startDate' => $startDate,
            'isCurrent' => true,
            'tasks' => $session->getTasks(),
        ]);
    }

    #[Route('/stop_session/{id}', 'stop_session', methods: ['GET', 'POST'])]
    public function stopSession(Request $request, Session $session, EntityManagerInterface $entityManager): Response
    {
        $session->changeEndTime(new \DateTime());
        $session->setInSession(false);
        $entityManager->persist($session);
        $entityManager->flush();
        return $this->redirectToRoute('user_dashboard', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/app_session_show/{id}', 'app_session_show', methods: ['GET', 'POST'])]
    public function showSession(Request $request, Session $session, EntityManagerInterface $entityManager): Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session,
        ]);
    }
    
}
