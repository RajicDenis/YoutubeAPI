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

	    	$vidResult = $data->videos->listVideos('statistics', array(
		    	'id' => $video['contentDetails']['videoId']
		    	)
			);

			$vidViewCount = $vidResult['items'][0]['statistics']['viewCount'];

	    	array_push($this->videos, array(
	    		'videoId' => $video['contentDetails']['videoId'],
	    		'title' => $video['snippet']['title'],
	    		'description' => $video['snippet']['description'],
	    		'channelTitle' => $video['snippet']['channelTitle'],
	    		'thumbnail' => $video['snippet']['thumbnails']['medium']['url'],
	    		'viewCount' => $vidViewCount
	    		)
	    	);

	    }

	    $pagination = array(
			'nextPage' => $response['nextPageToken'],
			'prevPage' => $response['prevPageToken'],
			'totalResults' => $response['pageInfo']['totalResults'],
			'resultsPerPage' => $response['pageInfo']['resultsPerPage']
		);

	    $array = array(
   			'videos' => $this->videos,
   			'pagination' => $pagination
   		);

   		return $array;

	}

    function channelsListByUsername($data, $part, $params) {
	    $params = array_filter($params);
	    $response = $data->channels->listChannels(
	        $part,
	        $params
	    );

	    if($response['items'] != null) {

	    	array_push($this->playlist, array(
		    	'id' => $response['items'][0]['contentDetails']['relatedPlaylists']['uploads'],
		    	'title' => $response['items'][0]['snippet']['title'],
		    	'description' => $response['items'][0]['snippet']['description'],
		    	)
			);

			return $this->playlist[0];

	    } else {

	    	return false;
	    }

	}
}