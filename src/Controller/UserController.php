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
        $isCompleted = [];
        $sessionCurrent = false;
        if (empty($allSessionForUser)) {
            $allTaskForSession = [];
            $isCurrent = [false];
            $isCompleted = [false];
        } else {
            $allTaskForSession = $taskRepository->findBySession($allSessionForUser[0]->getId());
            foreach($allSessionForUser as $sessionUser){
                array_push($isCurrent, $sessionUser->isInSession());
                array_push($isCompleted, $sessionUser->isCompleted());
            }
            // $isCurrent = [$allSessionForUser[0]->isInSession(), $allSessionForUser[0]->getId()];
        }
        
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'tasks' => $allTaskForSession,
            'sessions' => $allSessionForUser,
            'user' => $user,
            'isCurrent' => $isCurrent,
            'isCompleted' => $isCompleted,
        ]);
    }

    #[Route('/admin/delete_user/{id}', name: 'delete_user', methods: ['POST', 'GET'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($user);
            $entityManager->flush();

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/task_done/{idSession}', name: 'task_done', methods: ['POST'])]
    public function setTaskDone(Request $request, TaskRepository $taskRepository, EntityManagerInterface $entityManager, int $idSession): Response
    {
        $requestData = $request->request->all();
        
        $allTasksForSession = $taskRepository->findBySession($idSession);
        foreach($allTasksForSession as $task){
            $task->setDone(false);
            $entityManager->persist($task);
            $entityManager->flush();
        }
        if (isset($requestData['task'])) {
            $tasks = $request->request->all()['task'];
            
            if(is_array($tasks)){
                foreach ($tasks as $task) {
                    $task = $taskRepository->find($task);
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
        }
        return $this->redirectToRoute('user_dashboard', [], Response::HTTP_SEE_OTHER);
        

    }

    
}
