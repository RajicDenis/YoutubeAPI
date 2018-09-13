<?php
class Youtube {

	public $videos = [];
	public $playlistId;

	function videosByPlaylistId($data, $part, $params) {
		$params = array_filter($params);
	    $response = $data->playlistItems->listPlaylistItems(
	        $part,
	        $params
	    );

	    foreach ($response['items'] as $video) {
	    	array_push($this->videos, $video['contentDetails']['videoId']);
	    }

	    return $this->videos;

	}

    function channelsListByUsername($data, $part, $params) {
	    $params = array_filter($params);
	    $response = $data->channels->listChannels(
	        $part,
	        $params
	    );

	    $this->playlistId = $response['items'][0]['contentDetails']['relatedPlaylists']['uploads'];

	    return $this->playlistId;

	}
}