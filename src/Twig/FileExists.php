<?php

// src/Twig/FileExistsExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\Filesystem\Filesystem;

class FileExists extends AbstractExtension
{
    private $filesystem;
    private $kernelProjectDir;

    public function __construct(string $kernelProjectDir)
    {
        $this->filesystem = new Filesystem();
        $this->kernelProjectDir = $kernelProjectDir;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('file_exists', [$this, 'fileExists']),
        ];
    }

    public function fileExists(string $path): bool
    {
        $absolutePath = $this->kernelProjectDir . '/public/' . $path;
        return $this->filesystem->exists($absolutePath);
    }
}
