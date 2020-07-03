<?php

namespace App\Discord;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class DiscordProvider
{
    private $discordClientId;
    private $discordClientSecret;
    private $httpClient;

    public function __construct($discordClientId, $discordClientSecret, HttpClientInterface $httpClient)
    {
        $this->discordClientId = $discordClientId;
        $this->discordClientSecret = $discordClientSecret;
        $this->httpClient = $httpClient;
    }

    public function loadUserFromDiscord(string $code)
    {
        $url = sprintf("https://discord.com/api/oauth2/token?client_id=%s&client_secret=%s&code=%s&scope=email identify", $this->discordClientId, $this->discordClientSecret, $code);
        $response = $this->httpClient->request("POST", $url, [
            'headers'=> [
                'Accept'=> "application/json"
            ]
        ]);

        dd($response->toArray());
    }
}