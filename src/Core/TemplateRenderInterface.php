<?php

declare(strict_types=1);

namespace App\Core;

use Twig\Environment;

interface TemplateRenderInterface
{
    public function getRender(): Environment;
}
