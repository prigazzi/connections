<?php
declare(strict_types=1);

namespace Connections\Website\Infrastructure\Presentation;

interface TemplateEngineInterface
{
    public function render($template, array $variables = []);
}