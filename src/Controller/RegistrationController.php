<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/registration', name: 'app_registration')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password (assumes you have configured password encoding in security.yaml)
            $password = $form->get('password')->getData();
            $encodedPassword = password_hash($password, PASSWORD_BCRYPT);
            $user->setPassword($encodedPassword);

            // Set default roles
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // Redirect to login or welcome page after successful registration
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}


/*namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/registration', name: 'app_registration')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password (assumes you have configured password encoding in security.yaml)
            $password = $form->get('password')->getData();
            $encodedPassword = password_hash($password, PASSWORD_BCRYPT);
            $user->setPassword($encodedPassword);

            // Set default roles
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // Redirect to welcome page after successful registration
            return $this->redirectToRoute('app_welcome', ['firstname' => $user->getFirstName()]);
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/welcome', name: 'app_welcome')]
    public function welcome(Request $request): Response
    {
        $firstname = $request->query->get('firstname');

        return $this->render('home/index.html.twig', [
            'firstname' => $firstname,
        ]);
    }
}*/