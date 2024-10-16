<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            $password = $data['password'];

            // Check if user exists and password is correct
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user && password_verify($password, $user->getPassword())) {
                // Redirect to the welcome page
                return $this->redirectToRoute('app_welcome', ['username' => $email]);
            } else {
                $this->addFlash('error', 'Invalid credentials. Please try again.');
            }
        }

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
            'last_username' => $authenticationUtils->getLastUsername(),
        ]);
    }

    #[Route(path: '/welcome', name: 'app_welcome')]
    public function welcome(Request $request): Response
    {
        $firstname = $request->get('firstname');
        return $this->render('home/index.html.twig', [
            'firstname' => $firstname,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}