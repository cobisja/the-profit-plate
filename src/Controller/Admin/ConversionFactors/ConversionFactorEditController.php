<?php

declare(strict_types=1);

namespace App\Controller\Admin\ConversionFactors;

use App\Exception\ConversionFactor\ConversionFactorNotFoundException;
use App\Form\ConversionFactorFormType;
use App\Service\Admin\ConversionFactor\ConversionFactorShowService;
use App\Service\Admin\Shared\ActionButtonRendererService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route('/admin')]
class ConversionFactorEditController extends AbstractController
{
    public function __construct(
        private readonly ConversionFactorShowService $conversionFactorShowService,
        private readonly ActionButtonRendererService $actionButtonRendererService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/conversion_factors/{id}/edit', name: 'app_admin_conversion_factors_edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request): RedirectResponse|Response
    {
        try {
            $conversionFactor = $this->conversionFactorShowService->execute($id);
            $form = $this->createForm(ConversionFactorFormType::class, $conversionFactor, [
                'action' => $request->getRequestUri()
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                if ($request->isXmlHttpRequest()) {
                    $response = [
                        $conversionFactor->getOriginUnit(),
                        $conversionFactor->getTargetUnit(),
                        $conversionFactor->getFactor(),
                        $this->actionButtonRendererService->execute([
                            $request->getRequestUri(),
                            $this->generateUrl('app_admin_conversion_factors_delete', compact('id')),
                        ], (string)$conversionFactor->getId(), exclude: ActionButtonRendererService::SHOW_BUTTON)
                    ];

                    return $this->json($response);
                }

                $this->addFlash('success', 'Conversion Factor updated');

                return $this->redirectToRoute('app_admin_conversion_factors_index');
            }

            $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'edit.html.twig';

            return $this->render('admin/conversion_factors/' . $template, [
                'conversionFactor' => $conversionFactor,
                'form' => $form->createView()
            ], new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
        } catch (ConversionFactorNotFoundException|LoaderError|SyntaxError|RuntimeError $exception) {
            if ($request->isXmlHttpRequest()) {
                return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
            }

            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_admin_conversion_factors_index');
        }
    }
}