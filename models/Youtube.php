<?php
class Youtube {

	public $videos = [];
	public $playlist = [];

	function videosByPlaylistId($data, $part, $params) {
		$params = array_filter($params);
	    $response = $data->playlistItems->listPlaylistItems(
	        $part,
	        $params
	    );

	    foreach ($response['items'] as $video) {
	    	array_push($this->videos, array(
	    		'videoId' => $video['contentDetails']['videoId'],
	    		'title' => $video['snippet']['title'],
	    		'description' => $video['snippet']['description'],
	    		'channelTitle' => $video['snippet']['channelTitle']
	    		)
	    	);
	    }

	    return $this->videos;

	}

    function channelsListByUsername($data, $part, $params) {
	    $params = array_filter($params);
	    $response = $data->channels->listChannels(
	        $part,
	        $params
	    );

	    array_push($this->playlist, array(
	    	'id' => $response['items'][0]['contentDetails']['relatedPlaylists']['uploads'],
	    	'title' => $response['items'][0]['snippet']['title'],
	    	'description' => $response['items'][0]['snippet']['description']
	    	)
		);

	    return $this->playlist[0];

	}
}