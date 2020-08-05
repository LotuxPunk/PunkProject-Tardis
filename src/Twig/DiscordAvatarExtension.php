<?php
namespace App\Twig;

use App\Entity\User;
use Twig\TwigFunction;
use App\Discord\DiscordAvatarHelper;
use Twig\Extension\AbstractExtension;

class DiscordAvatarExtension extends AbstractExtension
{
    private DiscordAvatarHelper $helper;

    public function __construct(DiscordAvatarHelper $helper)
    {
        $this->helper = $helper;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('avatar', [$this, 'getDiscordAvatar']),
        ];
    }

    public function getDiscordAvatar(User $user){
        return $this->helper->getAvatarFromUser($user);
    }

}