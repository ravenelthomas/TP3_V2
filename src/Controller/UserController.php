<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SessionRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user_dashboard')]
    public function index(SessionRepository $sessionRepository, TaskRepository $taskRepository): Response
    {
        $user = $this->getUser();
        $allSessionForUser = $sessionRepository->findByUser($user->getId());
        if (empty($allSessionForUser)) {
            $allTaskForSession = [];
        } else {
            $allTaskForSession = $taskRepository->findBySession($allSessionForUser[0]->getId());
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'tasks' => $allTaskForSession,
            'sessions' => $allSessionForUser,
            'user' => $user,
        ]);
    }

    #[Route('/admin/delete_user/{id}', name: 'delete_user', methods: ['POST', 'GET'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
    
            $entityManager->remove($user);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/user/task_done/{idSession}', name: 'task_done', methods: ['POST'])]
public function setTaskDone(Request $request, TaskRepository $taskRepository, EntityManagerInterface $entityManager, int $idSession): Response
{
    $tasks = $taskRepository->find($request->request->get('task'));
    if (is_array($tasks) && count($tasks) > 0 && is_object($tasks[0])) {
        foreach($tasks as $task) {
            $task->setDone(true);
            $entityManager->persist($task);
        }
    } else {
        $tasks->setDone(true);
        $entityManager->persist($tasks);
    }

    return $this->redirectToRoute('user_dashboard');
}


}
