<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/models/Youtube.php';
require_once __DIR__ . '/config/Config.php';

session_start();

$client = new Google_Client();

$client->setAuthConfig('client_secret.json');
$client->addScope(Google_Service_Youtube::YOUTUBE_READONLY);
$client->setAccessType('offline');
$client->setApprovalPrompt('force');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    // Logout
    if(isset($_REQUEST['logout'])) {
    	$client->revokeToken();
    	unset($_SESSION['access_token']);
    	session_destroy();

    	header('Location: '. APPROOT);
    }

    // Instantiate Google and Youtube object
    $data = new Google_Service_YouTube($client);
	$youtube = new Youtube;

	// Set first channel to load
	if(isset($_GET['channel'])) {
		$defaultChannel = htmlentities($_GET['channel']);
	} else {
		//Set default channel
		$defaultChannel = CHANNEL;
	}
    
    // Get the channel details
	$channel = $youtube->channelsListByUsername($data, 'snippet,contentDetails,statistics', array('forUsername' => $defaultChannel));

	// Get the playlist from the selected channel
	if($channel != false) {
		if(isset($_GET['pageToken'])) {
			$playlist = $youtube->getPlaylists($data, 'snippet,contentDetails', array('channelId' => $channel['channelId'], 'maxResults' => 9, 'pageToken' => $_GET['pageToken']));
		} else {
			$playlist = $youtube->getPlaylists($data, 'snippet,contentDetails', array('channelId' => $channel['channelId'], 'maxResults' => 9));
		}
	} else {
		// If there is no channel by the entered name, return empty array for playlist
		$playlist = [];
	}

	if(isset($_GET['playlistId'])) {

		if($channel != false) {
			// Get videos based on the channels channel id
			if(isset($_GET['pageToken'])) {
				$videos = $youtube->videosByPlaylistId($data, 'snippet,contentDetails', array('playlistId' => $_GET['playlistId'], 'maxResults' => 5, 'pageToken' => $_GET['pageToken']));
			} else {
				$videos = $youtube->videosByPlaylistId($data, 'snippet,contentDetails', array('playlistId' => $_GET['playlistId'], 'maxResults' => 5));
			}

			file_put_contents('public/videoData.json', json_encode($videos));
		} else {
			// If there is no channel by the entered name, return empty array for videos
			$videos = [];
		}
		//echo json_encode($videos); die();
	} 

// If user is not authenticated, redirect to authentication page	
} else {

	$redirect_uri = APPROOT.'/oauth2callback';

	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>YoutubeAPI</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Mukta" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Cantarell:400i" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="public/css/main.css">
	<link rel="stylesheet" type="text/css" href="public/css/mobile.css">

	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
	<body>
		
		<nav class="navbar navbar-light dark">
			<div class="container">
				<a href="<?php echo APPROOT .'/'; ?>" class="navbar-brand mb-0 h1 text-white brand">YouTube API</a>
				<form action="<?php echo APPROOT; ?>" method="GET" id="logout">
					<input type="hidden" name="logout">
					<i class="fas fa-power-off logout-btn" onclick="document.getElementById('logout').submit(); "></i>
				</form>
			</div>	
		</nav>

		<!-- User input - search for channel -->
		<div class="container">
			<div class="row justify-content-center mt-5">
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET">
					<input id="srchChannel" type="text" name="channel" placeholder="...Search Channel...">
				</form>
			</div>
		</div>

		<!-- If playlist was NOT selected, show playlists -->
		<?php if(!isset($_GET['playlistId'])): ?>

			<?php if($channel != false): ?>
			
				<?php include 'public/include/playlist.php'; ?>

			<?php else: ?>

				<div class="container d-flex flex-column align-items-center position-relative mt-5">

					<img class="empty-img" src="public/images/empty.png">

					<hr class="w-100 mt-5">

					<h4 class="empty-msg">There is no channel with that name!!</h4>

				</div>

			<?php endif; ?>
		
		<!-- If playlist was selected, show videos -->
		<?php else: ?>

			<?php include 'public/include/videos.php'; ?>

		<?php endif; ?>

		<!-- Load main js file-->
		<script type="text/javascript" src="public/js/main.js"></script>

		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	</body>
</html>



