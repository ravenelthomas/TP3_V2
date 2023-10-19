<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SessionRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(SessionRepository $session, TaskRepository $task): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'sessions' => $session->findAll(), 
            'tasks' => $task->findAll(),
        ]);
    }

}
