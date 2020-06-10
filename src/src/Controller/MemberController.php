<?php 
namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MemberController extends AbstractController
{
    /**
     * @Route("/")
     * @Method({"POST"})
    */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/Login")
     * @Method({"POST"})
     */
    public function create()
    {        
        return $this->render('members/membercreation.html.twig');
    }


    /**
     * @Route("/user/new")
     */
    public function save()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setName('User1');
        $user->setEmail('user1@test.com');
        $user->setPassword('1234');

        $entityManager->persist($user);

        $entityManager->flush();

        return new Response("User ID {$user->getId()}");
    }

    /**
     * @Route("/Members")
     */
    public function memberList()
    {
        return $this->render('members/memberList.html.twig');
    }
}
