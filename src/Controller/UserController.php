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
        $isCurrent = [];
        $sessionCurrent = false;
        if (empty($allSessionForUser)) {
            $allTaskForSession = [];
            $isCurrent = [false];
        } else {
            $allTaskForSession = $taskRepository->findBySession($allSessionForUser[0]->getId());
            foreach($allSessionForUser as $sessionUser){
                array_push($isCurrent, $sessionUser->isInSession());
            }
            // $isCurrent = [$allSessionForUser[0]->isInSession(), $allSessionForUser[0]->getId()];
        }
        
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'tasks' => $allTaskForSession,
            'sessions' => $allSessionForUser,
            'user' => $user,
            'isCurrent' => $isCurrent,
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
        $tasks = $request->request->get('task');
        if(is_array($tasks)){
            foreach ($tasks as $task) {

                if ($task) {
                    $task->setDone(true);
                    $entityManager->persist($task);
                    $entityManager->flush();
                    
                }
            }
        }
        else{
            $task = $taskRepository->find($tasks);
            if ($task) {
                $task->setDone(true);
                $entityManager->persist($task);
                $entityManager->flush();
                
            }
        }
        return $this->redirectToRoute('user_dashboard');

    }

    
}
