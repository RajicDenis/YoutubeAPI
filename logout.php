<?php

require_once "vendor/autoload.php";
require_once __DIR__ . '/config/Config.php';

$client = new Google_Client();
$client->revokeToken();

header('Location: '. APPROOT);