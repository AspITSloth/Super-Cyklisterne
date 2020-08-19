<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LoginController extends AbstractController
{
    public function newUserFormBuilder($user)
    {
        $form = $this->createFormBuilder($user)
        ->add("username", TextType::class, array("attr" => array("class" => "form-control", "placeholder" => "Indtast navn")))
        ->add("password", PasswordType::class, array("attr" => array("class" => "form-control", "placeholder" => "Indtast password")))
        ->add("save", SubmitType::class, array("label" => "Opret Bruger", "attr" => array("class" => "btn btn-primary mt-3")))
        ->getForm();

        return $form;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('target_path');
        }

        $user = new User();

        $form = $this->newUserFormBuilder($user);

        $form->handleRequest($request);        
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("member_list");
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'form' => $form->createView()]);
    }

    /**
     * @Route("/signup")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {   
        $user = new User();

        $form = $this->newUserFormBuilder($user);

        $form->handleRequest($request);        
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("member_list");
        }

        return $this->render('security/signup.html.twig', array("form" => $form->createView()));
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
