<?php

namespace App\Discord;

use DateTime;
use Woeler\DiscordPhp\Webhook\DiscordWebhook;
use Woeler\DiscordPhp\Message\DiscordEmbedMessage;

class DiscordWebhookHelper
{
    private DiscordWebhook $webhook;
     
    function __construct($discordWebhook)
    {
        $this->webhook = new DiscordWebhook($discordWebhook);
    }

    public function sendEmbedMessage(string $username, string $title, string $content, string $color = "#FFFFFF",  string $image = null, string $avatar = null, string $thumbnail = null)
    {
        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $color);

        $message = (new DiscordEmbedMessage())
            ->setTitle($title)
            ->setDescription($content)
            ->setThumbnail(is_null($thumbnail)?"":$thumbnail)
            ->setAvatar("https://cdn.discordapp.com/app-icons/728616613120835625/689515f489fbab74481f58170f933b28.png")
            ->setUsername("PunkProject")
            ->setColor(hexdec($hexStr))
            ->setImage(is_null($image)?"":$image)
            ->setTimestamp(new DateTime())
            ->setAuthorName($username)
            ->setAuthorUrl("https://beta.punkproject.xyz")
            ->setAuthorIcon(is_null($avatar)?"":$avatar);

        $this->webhook->send($message);
    }

}