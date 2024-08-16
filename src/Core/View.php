<?php
declare(strict_types=1);
namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View implements ViewInterface
{
    private array $parameters;
    private Environment $twig;

    public function __construct()
    {
        $templatePath = __DIR__ . '/../View';
        $loader = new FilesystemLoader($templatePath);
        $this->twig = new Environment($loader, []);
    }

    public function addParameter(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function display(string $template): void
    {
        echo $this->twig->render($template, $this->parameters);
    }
}