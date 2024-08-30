<?php

declare(strict_types=1);

namespace App\Core;

use Twig\Environment;

class View implements ViewInterface
{
    protected array $parameters = [];
    protected string $template = 'home.twig';

    public function __construct(
        private readonly Environment $twig
    )
    {
    }

    public function addParameter(string $key, mixed $value): void
    {
        if (is_object($value)) {
            $value = (array)$value;
        }

        $this->parameters[$key] = $value;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function display(): void
    {
        echo $this->twig->render($this->template, $this->parameters);
    }
}