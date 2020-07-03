<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginController extends AbstractController
{
    private $discordClientId;

    public function __construct($discordClientId)
    {
        $this->discordClientId = $discordClientId;
    }


    /**
     * @Route("/login", name="login")
     */
    public function index()
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    /**
     * @Route("/login/discord", name="discord")
     */
    public function discord(UrlGeneratorInterface $urlGeneratorInterface)
    {
        $url = $urlGeneratorInterface->generate('client', [], UrlGeneratorInterface::ABSOLUTE_URL);
        return new RedirectResponse("https://discord.com/api/oauth2/authorize?client_id=$this->discordClientId&redirect_uri=$url&response_type=code&scope=identify%20email");
        //https://discord.com/api/oauth2/authorize?client_id=728616613120835625&redirect_uri=https%3A%2F%2Fpunkproject.xyz%2Fauth&response_type=code&scope=identify%20email
    }

    
}
