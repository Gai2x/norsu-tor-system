<?php

// Load Composer autoload and environment variables for the entire application.
// This file should be included before any config files that depend on $_ENV values.
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
