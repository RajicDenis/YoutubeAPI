
// Hide and show video description on click
var showMore = document.getElementById('show');
var videoDesc = document.getElementById('videoDesc');

if(typeof(showMore) != 'undefined' && showMore != null) {
	showMore.addEventListener('click', function() {
		if(videoDesc.classList.contains('hide')) {

			videoDesc.classList.remove('hide');
			videoDesc.classList.add('show');
			this.innerHTML = 'SHOW LESS';

		} else {

			videoDesc.classList.remove('show');
			videoDesc.classList.add('hide');
			setTimeout(function() {
				showMore.innerHTML = 'SHOW MORE';
			}, 200);
			
		}
	});
}

$videos = [];
$.getJSON('public/videoData.json', function(json) {
	for($i=0; $i<json.videos.length; $i++) {

		$videos.push(json.videos[$i]);
	}
});

window.onload = function() {
	$('#vid_'+$videos[0].videoId).addClass('select');
};



$('.vid').on('click', function() {

	$('.vid').each(function() {
		$(this).removeClass('select');
	});

	$videoId = $(this).data('video-id');

	$('#vid_'+$videoId).addClass('select');

	for($i=0; $i<$videos.length; $i++) {

		if($videos[$i].videoId == $videoId) {
			$('.video-iframe').attr('src', 'https://www.youtube.com/embed/'+$videos[$i].videoId);
			$('.video-title').text($videos[$i].title);
			$('.video-desc').html($videos[$i].description.replace(/\n/g,'<br />'));
		}
	}

});

