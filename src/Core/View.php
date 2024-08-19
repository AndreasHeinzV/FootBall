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

    public function getTemplate(): string{

        return $this->template;
    }
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }
    public function getParameters(): array{
        return $this->parameters;
    }
    public function display(string $template, array $dataArray): void
    {
        echo $this->twig->render($template, $dataArray);
    }
}