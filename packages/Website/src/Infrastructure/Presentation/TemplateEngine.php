<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\Presentation;

use Twig_Environment;

class TemplateEngine implements TemplateEngineInterface
{
    /** @var Twig_Environment */
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render($template, array $variables = [])
    {
        return $this->twig->render($template, $variables);
    }
}
