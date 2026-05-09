<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../service/GoogleAuthService.php';

$googleConfig = require __DIR__ . '/../config/google.php';
$googleAuthService = new GoogleAuthService($googleConfig);
$client = $googleAuthService->getClient();
