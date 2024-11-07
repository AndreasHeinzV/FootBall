<?php

declare(strict_types=1);

namespace App\Core;

use Twig\Environment;

class View implements ViewInterface
{
    /**
     * @var array<mixed> Parameters to be used in the view.
     */
    protected array $parameters = [];
    protected string $template = 'home.twig';

    public string $test;

    public function __construct(
        private readonly Environment $twig,
        public SessionHandlerInterface $sessionHandler,
    ) {
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
        $this->addParameter('status', $this->sessionHandler->getStatus());
        if ($this->sessionHandler->getStatus()) {
            $this->addParameter('userDto', $this->sessionHandler->getUserDTO());
        }
        if (isset($_ENV['test'])) {
            $this->test = $this->twig->render($this->template, $this->parameters);
        }

        echo $this->twig->render($this->template, $this->parameters);
    }
}