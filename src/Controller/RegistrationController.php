<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer, TranslatorInterface $translator): Response
    {
        $user = new User();
        
        $user->setRoles(['USER']);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $adressMail = $form->get('email')->getData();

            $email = (new Email())
            ->from('contact@thomasravenel.fr')
            ->to($adressMail)
            ->subject('Please confirm your email')
            ->text('Sending emails is fun again!')
            ->html('<p>Click on the link below to confirm your email</p>
            <a href="http://localhost:8000/confirm_email/');

            $mailer->send($email);

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // $adressMail = $form->get('email')->getData();

            // $email = (new Email())
            // ->from('contact@thomasravenel.fr')
            // ->to($adressMail)
            // ->subject('Please confirm your email')
            // ->text('Sending emails is fun again!')
            // ->html('<p>Click on the link below to confirm your email</p>
            // <a href="http://localhost:8000/confirm_email/'.$user->getId().'">Confirm your email</a>');

            // $mailer->send($email);
            // check the role of the user and redirect accordingly
            if (in_array('ADMIN', $user->getRoles())) {
                return $this->redirectToRoute('admin_dashboard');
            } else {
                return $this->redirectToRoute('user_dashboard');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/confirm_email/{id}', name: 'app_confirm_email')]
    public function confirmEmail(EntityManagerInterface $entityManager, UserRepository $userRepository, $id): Response
    {
        $user = new User;
        $user = $userRepository->find($id);
        $user->setIsVerified(true);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_login');
    }
}
