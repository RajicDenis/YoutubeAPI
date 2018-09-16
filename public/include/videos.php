
<div class="container mt-5">

	<h2 class="text-center mb-3"><?php echo $_GET['name']; ?></h2>

	<hr class="mb-5">

	<div class="row videos-ctr">
		<div class="col-7 mw75">

			<iframe class="video-iframe" style="width: 100%; min-height: 400px;" src="https://www.youtube.com/embed/<?php echo $videos['videos'][0]['videoId'] ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
			<strong><span class="fs21 mt-3 video-title"><?php echo $videos['videos'][0]['title']; ?></span></strong>
			<hr>
			<p class="text-muted video-desc hide" id="videoDesc"><?php echo nl2br($videos['videos'][0]['description']); ?></p>
			<hr>
			<div class="d-flex justify-content-center">
				<span class="show-more" id="show">SHOW MORE</span>
			</div>

		</div>

		<div class="col-5 mobile-margin">
			<div class="container">
				<?php foreach($videos['videos'] as $video): ?>
				<div class="row mb-4 thumb-box vid" id="vid_<?php echo $video['videoId']; ?>" data-video-id="<?php echo $video['videoId']; ?>">
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
			 				<a href="?playlistId=<?php echo $_GET['playlistId']; ?>&name=<?php echo $_GET['name']; ?>&channel=<?php echo $_GET['channel']; ?>&pageToken=<?php echo $videos['pagination']['prevPage']; ?>" class="next mr-3"><i class="fas fa-chevron-circle-left page-btn"></i></a>
			 			<?php else: ?>
							<a href="?playlistId=<?php echo $_GET['playlistId']; ?>&name=<?php echo $_GET['name']; ?>&pageToken=<?php echo $videos['pagination']['prevPage']; ?>" class="next mr-3"><i class="fas fa-chevron-circle-left page-btn"></i></a>
			 			<?php endif; ?>
					<?php endif; ?>

					<!-- Check if next page exists -->
					<?php if($videos['pagination']['nextPage'] != null): ?>
						<?php if(isset($_GET['channel'])): ?>
			 				<a href="?playlistId=<?php echo $_GET['playlistId']; ?>&name=<?php echo $_GET['name']; ?>&channel=<?php echo $_GET['channel']; ?>&pageToken=<?php echo $videos['pagination']['nextPage']; ?>" class="next ml-3"><i class="fas fa-chevron-circle-right page-btn"></i></a>
			 			<?php else: ?>
							<a href="?playlistId=<?php echo $_GET['playlistId']; ?>&name=<?php echo $_GET['name']; ?>&pageToken=<?php echo $videos['pagination']['nextPage']; ?>" class="next ml-3"><i class="fas fa-chevron-circle-right page-btn"></i></a>
			 			<?php endif; ?>
			 		<?php endif; ?>
				</div>

			</div>

		</div>
	</div>
</div>