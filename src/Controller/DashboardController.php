<?php

namespace App\Controller;

use App\Discord\DiscordAvatarHelper;
use App\Discord\DiscordWebhookHelper;
use App\Entity\Asset;
use App\Form\AssetType;
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
                    // ... handle exception if something happens during file upload
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
                    // ... handle exception if something happens during file upload
                }

                $asset->setAssetFilename($newFilename);
            }

            $asset->setAuthor($this->getUser());

            $manager->persist($asset);
            $manager->flush();

            $webhookHelper->sendEmbedMessage(
                $this->getUser()->getUsername(),
                $form->get('title')->getNormData(),
                $form->get('comment')->getNormData(),
                "#3AC98A",
                null,
                $avatarHelper->getAvatarFromUser($this->getUser())                
            );

            return $this->redirect($this->generateUrl('dashboard'));
        }
        return $this->render('dashboard/addasset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
