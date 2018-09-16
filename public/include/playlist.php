
<div class="container mt-5">

	<h4 class="text-muted mb-4">Channel: <?php echo $playlist['items'][0]['snippet']['channelTitle']?></h4>

	<h2 class="text-center">Playlists</h2>

	<hr>

	<div class="row">

	<?php foreach($playlist['items'] as $list): ?>

		<div class="col-lg-4 col-md-6 ctr">

			<div class="playlist-box mt-5" onclick="document.getElementById('playlist-form_<?php echo $list['id']; ?>').submit(); ">

				<form action="<?php echo APPROOT; ?>" method="GET" id="<?php echo 'playlist-form_'. $list['id']; ?>">
					<input type="hidden" name="playlistId" value="<?php echo $list['id']; ?>">
					<input type="hidden" name="name" value="<?php echo $list['snippet']['title']; ?>">
				</form>

				<div class="d-flex justify-content-center position-relative thumb-img">

					<img class="thumbnail-image" src="<?php echo $list['snippet']['thumbnails']['medium']['url']; ?>">
					<div class="vid-count"><?php echo $list['contentDetails']['itemCount']; ?></div>

				</div>

				<h4 class="mt-4 mb-2 text-center"><strong><?php echo $list['snippet']['title']; ?></strong></h4>

				<div class="play-info">

					<p class="text-muted"><?php echo date('d.m.Y', strtotime($list['snippet']['publishedAt'])); ?></p>
					<p class="text-muted mr-3"><?php echo $list['snippet']['channelTitle']; ?></p>

				</div>

			</div>

		</div>

	<?php endforeach; ?>

		<hr>
		<div class="container d-flex justify-content-center mt-5">
			<div class="row">
				<!-- Check if previous page exists -->
				<?php if($playlist['prevPageToken'] != null): ?>
					
		 				<a href="?pageToken=<?php echo $playlist['prevPageToken']; ?>" class="next mr-3"><i class="fas fa-chevron-circle-left page-btn"></i></a>

				<?php endif; ?>

				<!-- Check if next page exists -->
				<?php if($playlist['nextPageToken'] != null): ?>
					
		 				<a href="?pageToken=<?php echo $playlist['nextPageToken']; ?>" class="next mr-3"><i class="fas fa-chevron-circle-right page-btn"></i></a>

				<?php endif; ?>
			</div>
		</div>
	</div>

</div>
