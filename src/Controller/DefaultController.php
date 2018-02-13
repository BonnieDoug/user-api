<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function register(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $input = json_decode($request->getContent());
        $username = $input->username;
        $password = $input->password;
        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();
        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

    /**
     * @Route("logout", name="logout", defaults={"_format": "json"})
     * @Method({"POST", "GET"})
     */
    public function logout()
    {
        return new Response(null);
    }
    /**
     * @Route("user_check", name="user_check", defaults={"_format": "json"})
     * @Method({"GET"})
     */
    public function loginCheckAction()
    {
        return new Response(json_encode(["user" => $this->getUser()->toArray()]));
    }
}