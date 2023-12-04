<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/edit_user', name: 'app_edit_user')]

    public function showUser(UserRepository $userRepository): Response
    {

        return $this->render('admin/show_user.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/admin/edit_user/{id}', name: 'app_edit_user_id')]
    public function modifyUser(EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository, $id): Response
    {
        $user = new User;
        $user = $userRepository->find($id);
        // if (!$user) {
        //     throw $this->createNotFoundException('User not found');
        // }
    
        $form = $this->createForm(UserType::class, $user);
        $form->add('roles', ChoiceType::class, [
            'choices' => [
                'User' => 'USER',
                'Admin' => 'ADMIN',
            ],
            'multiple' => true,
            'expanded' => false,
            'label' => 'Roles',
        ]);
        
        $form->add('save', SubmitType::class, [
            'label' => 'Modifier',
            'attr' => ['class' => 'btn btn-primary'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_edit_user', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    
}
