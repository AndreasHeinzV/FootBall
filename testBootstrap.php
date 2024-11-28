<?php

declare(strict_types=1);
//$_ENV['DATABASE'] = 'football_test';

use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;

require_once __DIR__ . "/vendor/autoload.php";

$schemaBuilder = new SchemaBuilder((new ORMSqlConnector()));
$schemaBuilder->createSchema();