<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__ . '/config/Config.php';

session_start();

$client = new Google_Client();
$client->setAuthConfigFile('client_secret.json');
$client->setRedirectUri(APPROOT.'/oauth2callback');
$client->addScope(Google_Service_Youtube::YOUTUBE_READONLY);
$client->setAccessType('offline');
$client->setApprovalPrompt('force');

if (! isset($_GET['code'])) {

  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));

} else {

  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = APPROOT;

  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}