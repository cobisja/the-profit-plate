<?php

declare(strict_types=1);

namespace App\Controller\Admin\Products;

use App\Entity\Product;
use App\Exception\Shared\InvalidPictureException;
use App\Exception\Shared\PictureNotUploadedException;
use App\Form\ProductFormType;
use App\Service\Admin\Shared\PictureUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class ProductsNewController extends AbstractController
{
    public function __construct(
        private readonly PictureUploaderService $pictureUploaderService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/products/new', name: 'app_admin_products_new', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): RedirectResponse|Response
    {
        try {
            $product = new Product();

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

                $this->entityManager->persist($product);
                $this->entityManager->flush();
                $this->addFlash('success', 'Product created');

                return $this->redirectToRoute('app_admin_products_show', ['id' => $product->getId()]);
            }
        } catch (InvalidPictureException|PictureNotUploadedException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_admin_products_index');
        }

        return $this->render('admin/products/new.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
