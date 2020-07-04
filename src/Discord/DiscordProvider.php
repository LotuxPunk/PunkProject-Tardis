<?php

namespace App\Discord;

use App\Entity\User;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DiscordProvider
{
    private $discordClientId;
    private $discordClientSecret;
    private $httpClient;
    private $urlGeneratorInterface;

    public function __construct($discordClientId, $discordClientSecret, HttpClientInterface $httpClient, UrlGeneratorInterface $urlGeneratorInterface)
    {
        $this->discordClientId = $discordClientId;
        $this->discordClientSecret = $discordClientSecret;
        $this->httpClient = $httpClient;
        $this->urlGeneratorInterface = $urlGeneratorInterface;
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

        $response = $this->httpClient->request("GET", "https://discord.com/api/users/@me", [
            'headers'=>[
                'Authorization'=>"Bearer $token"
            ]
        ]);

        //return new User($response->toArray());

        //TODO : Register the user if not in database and get it
    }
}