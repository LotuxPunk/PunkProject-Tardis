<?php

namespace App\Controller;

use App\Repository\AssetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AssetRepository $assetRepository)
    {
        return $this->render('home/index.html.twig', [
            'assets' => $assetRepository->findLatests(4),
        ]);
    }
}
