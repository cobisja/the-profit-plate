<?php

declare(strict_types=1);

namespace App\Controller\Admin\Recipes;

use App\Entity\Recipe;
use App\Exception\Shared\InvalidPictureException;
use App\Exception\Shared\PictureNotUploadedException;
use App\Service\Admin\Shared\PictureUploaderService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;

final readonly class RecipeImageUpdater
{
    public function __construct(
        private PictureUploaderService $pictureUploaderService,
        private ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @throws InvalidPictureException
     * @throws PictureNotUploadedException
     */
    public function execute(?File $recipePicture, Recipe $recipe): void
    {
        if (!$recipePicture) {
            return;
        }

        $uploadsPath = $this->parameterBag->get('uploads_folder') . '/recipes';
        $pictureFilename = $this->pictureUploaderService->execute(
            picture: $recipePicture,
            uploadsPath: $uploadsPath,
            filename: $recipe->getPicture()
        );

        $recipe->setPicture($pictureFilename);
    }
}