<?php

declare(strict_types=1);

namespace App\Core;

use Twig\Environment;

class View implements ViewInterface
{
    private array $parameters;
    private Environment $twig;

    private string $template;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->template = 'home.twig';
        $this->parameters = [];
    }

    public function addParameter(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function setParametersForView(array $data): void
    {
        /*
        if (empty($data)) {
            $this->parameters = [];
        } else {
            foreach ($data as $key => $value) {
                $this->parameters[$key] = $value;
            }
        }
          */
        foreach ($data as $key => $value) {
            $this->parameters[$key] = $value;
        }

    }
    public function getTemplate(): string
    {
        return $this->template;
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