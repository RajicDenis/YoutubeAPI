// Hide and show video description on click
var showMore = document.getElementById('show');
var videoDesc = document.getElementById('videoDesc');
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