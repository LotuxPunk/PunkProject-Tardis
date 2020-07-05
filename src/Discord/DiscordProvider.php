<?php

namespace App\Discord;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DiscordProvider
{
    private $discordClientId;
    private $discordClientSecret;
    private $httpClient;
    private $urlGeneratorInterface;
    private UserRepository $repository;
    private EntityManagerInterface $manager;

    public function __construct($discordClientId, $discordClientSecret, HttpClientInterface $httpClient, UrlGeneratorInterface $urlGeneratorInterface, UserRepository $repository, EntityManagerInterface $manager)
    {
        $this->discordClientId = $discordClientId;
        $this->discordClientSecret = $discordClientSecret;
        $this->httpClient = $httpClient;
        $this->urlGeneratorInterface = $urlGeneratorInterface;
        $this->repository = $repository;
        $this->manager = $manager;
    }

    public function loadUserFromDiscord(string $code)
    {
        $url = sprintf("https://discord.com/api/oauth2/token?grant_type=authorization_code");
        $urlRedirect = $this->urlGeneratorInterface->generate('client', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $response = $this->httpClient->request("POST", $url, [
            'body'=>[
                'code'=>$code,
                'client_id'=>$this->discordClientId,
                'client_secret'=>$this->discordClientSecret,
                'redirect_uri'=>$urlRedirect
            ]
        ]);

        $token = $response->toArray()['access_token'];

        //dd($token);

        $response = $this->httpClient->request("GET", "https://discord.com/api/users/@me", [
            'headers'=>[
                'Authorization'=>"Bearer $token"
            ]
        ]);

        //return new User($response->toArray());

        //dd($response->toArray());

        $result = $this->repository->findByDiscordId($response->toArray()['id']);
        
        //dd($result);

        if (empty($result)) {
            $user = new User($response->toArray());
            $user->setRoles(["ROLE_USER"]);
            $this->manager->persist($user);
            $this->manager->flush();
            return $user;
        }
        return $result[0];
    }
}