<?php

declare(strict_types=1);

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateRender implements TemplateRenderInterface
{
    private Environment $templateRender;

    public function __construct()
    {
        $loader               = new FilesystemLoader(__DIR__ . '/../view');
        $this->templateRender = new Environment($loader, []);
    }

    public function getRender(): Environment
    {
        return $this->templateRender;
    }
}
