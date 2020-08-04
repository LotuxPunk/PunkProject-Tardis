<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/users", name="user_index")
     */
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('user/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_ban", methods={"BAN"})
     */
    public function ban(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('ban'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setBanned(true);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User banned!');
        }

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/{id}", name="user_unban", methods={"UNBAN"})
     */
    public function unban(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('unban'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setBanned(false);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User unbanned!');
        }

        return $this->redirectToRoute('dashboard');
    }
}
