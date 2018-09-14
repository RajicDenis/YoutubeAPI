<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/models/Youtube.php';
require_once __DIR__ . '/config/Config.php';

session_start();

$client = new Google_Client();

$client->setAuthConfig('client_secret.json');
$client->addScope(Google_Service_Youtube::YOUTUBE_READONLY);
$client->setAccessType('offline');
$client->setApprovalPrompt('consent');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

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
	$playlist = $youtube->channelsListByUsername($data, 'snippet,contentDetails,statistics', array('forUsername' => $defaultChannel));

	if($playlist != false) {
		// Get videos based on the channels playlist id
		if(isset($_GET['pageToken'])) {
			$videos = $youtube->videosByPlaylistId($data, 'snippet,contentDetails', array('playlistId' => $playlist['id'], 'maxResults' => 5, 'pageToken' => $_GET['pageToken']));
		} else {
			$videos = $youtube->videosByPlaylistId($data, 'snippet,contentDetails', array('playlistId' => $playlist['id'], 'maxResults' => 5));
		}
	} else {
		// If there is no channel by the entered name, return empty array for videos
		$videos = [];
	}
	
	//echo json_encode($videos['pagination']); die();
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

	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Mukta" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Cantarell:400i" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="public/css/main.css">
</head>
	<body>
		
		<nav class="navbar navbar-light dark">
			<div class="container">
				<a href="<?php echo APPROOT .'/'; ?>" class="navbar-brand mb-0 h1 text-white">YouTube API</a>
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

		<?php if($playlist != false): ?>

		<div class="container mt-5">

			<div class="row">
				<div class="col-7">
					<iframe style="width: 100%; min-height: 400px;" src="https://www.youtube.com/embed/<?php echo $videos['videos'][0]['videoId'] ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					<span class="fs19 mt-3"><?php echo $videos['videos'][0]['title']; ?></span>
					<hr>
					<p class="text-muted video-desc hide" id="videoDesc"><?php echo nl2br($videos['videos'][0]['description']); ?></p>
					<hr>
					<div class="d-flex justify-content-center">
					<span class="show-more" id="show">SHOW MORE</span>
					</div>
				</div>

				<div class="col-5">
					<div class="container">
					<?php foreach($videos['videos'] as $video): ?>
					<div class="row mb-4">
						<div class="col-7">
							<img style="width: 100%; height: auto;" src="<?php echo $video['thumbnail'] ?>">
						</div>

						<div class="col-5 d-flex flex-column justify-content-between">
							<p><strong><?php echo $video['title']; ?></strong></p>
							<p class="text-muted"><?php echo $video['channelTitle']; ?></p>
							<p class="text-muted"><?php echo $video['viewCount'].' views'; ?></p>
						</div>
					</div>
					<?php endforeach; ?>
					
					<hr>
					<div class="text-center">
						<!-- Check if previous page exists -->
						<?php if($videos['pagination']['prevPage'] != null): ?>
							<?php if(isset($_GET['channel'])): ?>
				 				<a href="?channel=<?php echo $_GET['channel']; ?>&pageToken=<?php echo $videos['pagination']['prevPage']; ?>" class="next mr-3"><?php echo '<< PREVIOUS'; ?></a>
				 			<?php else: ?>
								<a href="?pageToken=<?php echo $videos['pagination']['prevPage']; ?>" class="next mr-3"><?php echo '<< PREVIOUS'; ?></a>
				 			<?php endif; ?>
						<?php endif; ?>

						<!-- Check if next page exists -->
						<?php if($videos['pagination']['nextPage'] != null): ?>
							<?php if(isset($_GET['channel'])): ?>
				 				<a href="?channel=<?php echo $_GET['channel']; ?>&pageToken=<?php echo $videos['pagination']['nextPage']; ?>" class="next ml-3">NEXT>></a>
				 			<?php else: ?>
								<a href="?pageToken=<?php echo $videos['pagination']['nextPage']; ?>" class="next ml-3">NEXT>></a>
				 			<?php endif; ?>
				 		<?php endif; ?>
					</div>

					</div>

				</div>
			</div>
		</div>

		<?php else: ?>

		<div class="container d-flex flex-column align-items-center position-relative mt-5">

			<img class="empty-img" src="public/images/empty.png">

			<hr class="w-100 mt-5">

			<h4 class="empty-msg">There is no channel with that name!!</h4>

		</div>

		<?php endif; ?>

		<!-- Load main js file-->
		<script type="text/javascript" src="public/js/main.js"></script>

		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	</body>
</html>



