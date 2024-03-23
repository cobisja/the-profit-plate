<?php

declare(strict_types=1);

namespace App\Service\Admin\Shared;

use App\Exception\Shared\InvalidPictureException;
use App\Exception\Shared\PictureNotUploadedException;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;

class PictureUploaderService
{
    private const MAX_WIDTH = 300;
    private const MAX_HEIGHT = 300;

    public function __construct(
        private readonly Imagine $imagine,
        private readonly SluggerInterface $slugger
    ) {
    }

    /**
     * @throws InvalidPictureException
     * @throws PictureNotUploadedException
     */
    public function execute(?File $picture, string $uploadsPath, ?string $filename = null): string
    {
        if (!$picture) {
            throw new InvalidPictureException();
        }

        $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $filename ?: ($safeFilename . '-' . uniqid() . '.' . $picture->guessExtension());

        try {
            $picture->move($uploadsPath, $newFilename);
        } catch (FileException $exception) {
            throw new PictureNotUploadedException($exception->getMessage());
        }

        $this->resize($uploadsPath . '/' . $newFilename);

        return $newFilename;
    }

    private function resize(string $filename): void
    {
        list($iWidth, $iHeight) = getimagesize($filename);

        $ratio = $iWidth / $iHeight;
        $width = self::MAX_WIDTH;
        $height = self::MAX_HEIGHT;

        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $picture = $this->imagine->open($filename);

        $picture->resize(new Box($width, $height))->save($filename);
    }
}
