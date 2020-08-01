<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_show", methods={"GET"})
     */
    public function user(User $user): Response
    {
        return $this->render('user/user.html.twig', [
            'user' => $user,
            'assets' => $user ->getAssets()
        ]);
    }
}
