<?php

declare(strict_types=1);

namespace App\Controller\Admin\ConversionFactors;

use App\Entity\ConversionFactor;
use App\Form\ConversionFactorFormType;
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
class ConversionFactorsNewController extends AbstractController
{
    public function __construct(
        private readonly ActionButtonRendererService $actionButtonRendererService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/conversion_factors/new', name: 'app_admin_conversion_factors_new', methods: ['GET', 'POST'])]
    public function new(Request $request): RedirectResponse|Response
    {
        try {
            $conversionFactor = new ConversionFactor();
            $form = $this->createForm(ConversionFactorFormType::class, $conversionFactor, [
                'action' => $request->getRequestUri()
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($conversionFactor);
                $this->entityManager->flush();

                if ($request->isXmlHttpRequest()) {
                    $response = [
                        $conversionFactor->getOriginUnit(),
                        $conversionFactor->getTargetUnit(),
                        $conversionFactor->getFactor(),
                        $this->actionButtonRendererService->execute([
                            $this->generateUrl('app_admin_conversion_factors_edit', [
                                'id' => $conversionFactor->getId()
                            ]),
                            $this->generateUrl('app_admin_conversion_factors_delete', [
                                'id' => $conversionFactor->getId()
                            ]),
                        ], (string)$conversionFactor->getId())
                    ];

                    return $this->json($response, Response::HTTP_CREATED);
                }

                return $this->redirectToRoute('app_admin_conversion_factors_index');
            }

            $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'new.html.twig';

            return $this->render('admin/conversion_factors/' . $template, [
                'conversionFactor' => $conversionFactor,
                'form' => $form->createView()
            ], new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_CREATED));
        } catch (LoaderError|SyntaxError|RuntimeError $exception) {
            $exceptionMessage = $exception->getMessage();

            if ($request->isXmlHttpRequest()) {
                return new Response($exceptionMessage, Response::HTTP_BAD_REQUEST);
            }

            $this->addFlash('error', $exceptionMessage);

            return $this->redirectToRoute('app_admin_conversion_factors_index');
        }
    }
}