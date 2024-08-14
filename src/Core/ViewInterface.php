<?php

namespace App\Core;

interface ViewInterface
{ public function addParameter(string $key, mixed $value) : void;

    public function display(string $template) : void;

}