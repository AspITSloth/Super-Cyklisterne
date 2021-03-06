<?php 
namespace App\Controller;

use App\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MemberController extends AbstractController
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

    
    // /**
    //  * @Route("/login")
    //  * @Method({"GET", "POST"})
    //  */
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

        return $this->render('members/membercreation.html.twig', array("form" => $form->createView()));
    }

    /**
     * @Route("/", name="index")
     * @Method({"GET", "POST"})
    */
    public function index(Request $request)
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

        return $this->render("index.html.twig", array("form" => $form->createView()));
    }

    /**
     * @Route("/user/edit", name="edit_user")
     */
    public function edit()
    {
        //return $this->render();
    }

    /**
     * @Route("/members", name="member_list")
     */
    public function memberList()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('members/memberList.html.twig', array("users" => $users));
    }
}
