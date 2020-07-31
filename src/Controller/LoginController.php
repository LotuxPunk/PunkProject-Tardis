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
        return $this->render('login/index.html.twig');
    }

    /**
     * @Route("/login/discord", name="discord")
     */
    public function discord(UrlGeneratorInterface $urlGeneratorInterface)
    {
        $url = $urlGeneratorInterface->generate('client', [], UrlGeneratorInterface::ABSOLUTE_URL);
        return new RedirectResponse("https://discord.com/api/oauth2/authorize?client_id=$this->discordClientId&redirect_uri=$url&response_type=code&scope=identify%20email");
    }

    
}
