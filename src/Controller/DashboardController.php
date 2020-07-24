<?php

namespace App\Controller;

use App\Discord\DiscordAvatarHelper;
use App\Discord\DiscordWebhookHelper;
use App\Entity\Asset;
use App\Form\AssetType;
use App\Repository\AssetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/dashboard/addasset", name="add_asset")
     */
    public function addAsset(Request $request, SluggerInterface $slugger, EntityManagerInterface $manager, DiscordAvatarHelper $avatarHelper, DiscordWebhookHelper $webhookHelper)
    {
        $asset = new Asset();
        $form = $this->createForm(AssetType::class, $asset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnailFile = $form->get('thumbnail')->getData();
            $assetFile = $form->get('asset')->getData();

            if ($thumbnailFile) {
                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$thumbnailFile->guessExtension();

                try {
                    $thumbnailFile->move(
                        $this->getParameter('thumbnails_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error while adding asset');
                    return $this->redirect($this->generateUrl('dashboard'));
                }

                $asset->setThumbnailFilename($newFilename);
            }

            if ($assetFile) {
                $originalFilename = pathinfo($assetFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$assetFile->guessExtension();

                try {
                    $assetFile->move(
                        $this->getParameter('assets_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error while adding asset');
                    return $this->redirect($this->generateUrl('dashboard'));
                }

                $asset->setAssetFilename($newFilename);
            }

            $asset->setAuthor($this->getUser());

            $manager->persist($asset);
            $manager->flush();

            $this->addFlash('success', 'Asset added!');

            $thumbnailFile = $asset->getThumbnailFilename();

            $webhookHelper->sendEmbedMessage(
                $this->getUser()->getUsername(),
                $form->get('title')->getNormData(),
                $form->get('comment')->getNormData(),
                "#3AC98A",
                null,
                $avatarHelper->getAvatarFromUser($this->getUser()),
                "https://beta.punkproject.xyz/public/uploads/thumbnails/$thumbnailFile"
            );

            return $this->redirect($this->generateUrl('dashboard'));
        }
        return $this->render('dashboard/addasset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/assets", name="my_assets")
     */
    public function myAssets(AssetRepository $assetRepository)
    {
        return $this->render('dashboard/myassets.html.twig', [
            'assets' => $assetRepository->findByUser($this->getUser())
        ]);
    }

    
}
