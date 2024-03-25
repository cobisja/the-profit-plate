<?php

declare(strict_types=1);

namespace App\Controller\Admin\Products;

use App\Event\Product\PriceChangedEvent;
use App\Exception\Product\ProductNotFoundException;
use App\Exception\Shared\InvalidPictureException;
use App\Exception\Shared\PictureNotUploadedException;
use App\Form\ProductFormType;
use App\Service\Admin\Product\ProductShowService;
use App\Service\Admin\Shared\PictureUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class ProductsEditController extends AbstractController
{
    public function __construct(
        private readonly ProductShowService $productShowService,
        private readonly PictureUploaderService $pictureUploaderService,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    #[Route('/products/{id}/edit', name: 'app_admin_products_edit', methods: ['GET', 'POST'])]
    public function __invoke(string $id, Request $request): RedirectResponse|Response
    {
        try {
            $product = $this->productShowService->execute($id);
            $currentPrice = $product->getPricePerUnit();
            $form = $this->createForm(ProductFormType::class, $product);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $productPicture = $form->get('picture')->getData();

                if ($productPicture) {
                    $uploadsPath = $this->getParameter('uploads_folder') . '/products';
                    $pictureFilename = $this->pictureUploaderService->execute(
                        picture: $productPicture,
                        uploadsPath: $uploadsPath,
                        filename: $product->getPicture()
                    );

                    $product->setPicture($pictureFilename);
                }

                $product->updateUpdatedAt();

                $this->entityManager->flush();

                $newPrice = $product->getPricePerUnit();

                if ($currentPrice !== $newPrice) {
                    $this->eventDispatcher->dispatch(
                        new PriceChangedEvent($product, $currentPrice, $newPrice)
                    );
                }

                $this->addFlash('success', 'Product updated');

                return $this->redirectToRoute('app_admin_products_show', compact('id'));
            }
        } catch (ProductNotFoundException|InvalidPictureException|PictureNotUploadedException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_admin_products_index');
        }

        return $this->render('admin/products/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
