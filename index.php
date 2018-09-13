<!--<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>YoutubeAPI</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="public/css/main.css">
</head>
<body>
	
	<nav class="navbar navbar-light dark">
		<div class="container">
			<span class="navbar-brand mb-0 h1 text-white">YouTube API</span>
		</div>	
	</nav>
	
	<div class="container mt-5">
		<div class="row">
			<div class="col-9"></div>

			<div class="col-3"></div>
	</div>
	</div>
	

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>-->
<?php
// Call set_include_path() as needed to point to your client library.
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/models/Youtube.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('client_secret.json');
$client->addScope(Google_Service_Youtube::YOUTUBE_READONLY);
$client->setAccessType("offline");


if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    // Instantiate Google and Youtube object
    $data = new Google_Service_YouTube($client);
	$youtube = new Youtube;

    $defaultChannel = 'thenewboston';

	$playlistId = $youtube->channelsListByUsername($data, 'snippet,contentDetails,statistics', array('forUsername' => $defaultChannel));
	$videos = $youtube->videosByPlaylistId($data, 'snippet, contentDetails', array('playlistId' => $playlistId, 'maxResults' => 10));


} else {

	$redirect_uri = 'http://localhost/YoutubeAPI/oauth2callback';

	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));

}

