<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Form\Asset1Type;
use App\Repository\AssetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/asset")
 */
class AssetController extends AbstractController
{
    /**
     * @Route("/", name="asset_index", methods={"GET"})
     */
    public function index(AssetRepository $assetRepository): Response
    {
        return $this->render('asset/index.html.twig', [
            'assets' => $assetRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="asset_show", methods={"GET"})
     */
    public function show(Asset $asset): Response
    {
        return $this->render('asset/show.html.twig', [
            'asset' => $asset,
        ]);
    }

    /**
     * @Route("/{id}", name="asset_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Asset $asset): Response
    {
        if ($this->isCsrfTokenValid('delete'.$asset->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($asset);
            $entityManager->flush();

            $this->addFlash('success', 'Asset deleted!');
        }

        return $this->redirectToRoute('my_assets');
    }
}
