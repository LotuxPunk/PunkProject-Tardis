<?php

namespace App\Discord;

use App\Entity\User;

class DiscordAvatarHelper
{
    public function getAvatarFromUser(User $user)
    {
        $userId = $user->getDiscordId();
        $avatarId = $user->getAvatar();
        return "https://cdn.discordapp.com/avatars/$userId/$avatarId.png";
    }
}