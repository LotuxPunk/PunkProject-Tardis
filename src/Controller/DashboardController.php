<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Form\AssetType;
use App\Entity\AssetCategory;
use App\Form\AssetCategoryType;
use App\Repository\AssetRepository;
use App\Discord\DiscordAvatarHelper;
use App\Discord\DiscordWebhookHelper;
use App\Form\AssetEditType;
use Symfony\Component\Asset\Packages;
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
    public function index(AssetRepository $assetRepository)
    {
        return $this->render('dashboard/index.html.twig', [
            'assets' => $assetRepository->findLatestsByUser($this->getUser(), 4),
        ]);
    }

    /**
     * @Route("/dashboard/addasset", name="add_asset")
     */
    public function addAsset(Request $request, SluggerInterface $slugger, EntityManagerInterface $manager, DiscordAvatarHelper $avatarHelper, DiscordWebhookHelper $webhookHelper, Packages $assetsManager)
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
            $asset->setAdded(false);

            $manager->persist($asset);
            $manager->flush();

            $this->addFlash('success', 'Asset added!');

            $thumbnailFile = $asset->getThumbnailFilename();

            $webhookHelper->sendEmbedMessage(
                $this->getUser()->getUsername(),
                $form->get('title')->getNormData(),
                $form->get('comment')->getNormData(),
                "#3AC98A",
                //$assetsManager->getUrl("uploads/thumbnails/$thumbnailFile"),
                null,
                $avatarHelper->getAvatarFromUser($this->getUser())
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

    /**
     * @Route("/dashboard/admin/add_asset_category", name="add_category")
     */
    public function addCategory(Request $request, EntityManagerInterface $manager)
    {
        $category = new AssetCategory();
        $form = $this->createForm(AssetCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();

            $this->addFlash('success', 'Category added!');

            return $this->redirect($this->generateUrl('add_category'));
        }
        return $this->render('dashboard/admin/admin.addcategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/dashboard/edit_asset/{id}", name="edit_asset")
     */
    public function editAsset(Asset $asset, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AssetEditType::class, $asset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($asset);
            $manager->flush();

            $this->addFlash('success', 'Asset edited!');

            return $this->redirect($this->generateUrl('my_assets'));
        }

        return $this->render('dashboard/edit.asset.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
