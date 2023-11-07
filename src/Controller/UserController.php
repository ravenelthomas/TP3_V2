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
        public function index(SessionRepository $session, TaskRepository $task): Response
        {
            return $this->render('user/index.html.twig', [
                'controller_name' => 'UserController',
                
                'user'=> $this->getUser(),
            ]);
        }

        #[Route('/admin/delete_user/{id}', methods: ['POST', 'GET'])]

        public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
        {
            if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
                $entityManager->remove($user);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_admin');

        }
    }
