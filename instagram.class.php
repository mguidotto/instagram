<?php

class Instagram {

	public	$list 			= array();
	private $searchType	= ''; //could be tags, location, geographies, users
	private $searchArray	= array(); //list of values
	private $blackList		= array();
	private $clientId 		= '';
	private $clientSecret	= '';
	private $allowedSearchTypes = array('tags', 'location', 'geographies', 'users');
	
	public function setClientId($clientId) {
		if (!empty($clientId)) {
			$this->clientId = $clientId;
		} else {
			throw new Exception("Client ID must be set");
		}
	}
	
	public function setClientSecret($clientSecret) {
		if (!empty($clientSecret)) {
			$this->clientSecret = $clientSecret;
		} else {
			throw new Exception("Client Secret must be set");
		}
	}
	
	//Costruttore
	public function __construct( $searchType, $searchArray, $blacklist = NULL, $clientId = NULL, $clientSecret = NULL){
		if (!in_array($searchType, $this->allowedSearchTypes)) {
			throw new Exception("$searchType is mandatory field");
		}
		if (!is_array($searchArray) || !count($searchArray)) {
			throw new Exception("$searchArray is mandatory fiel");
		}
		if (!is_array($blacklist)) {
			throw new Exception("BlackList must be an array");
		}
		
		$this->searchType	= $searchType;
		$this->searchArray	= $searchArray;
		$this->blackList 	= $blacklist;
		$this->clientId		= $clientId;
		$this->clientSecret = $clientSecret;
	}
	public function clearList() {
		$this->list = array();
	}
	
	public function getAllImages ( $recent = false ){
		foreach ( $this->searchArray as $search ){
			$this->getImagesByTag( $search, null, $recent );
		}
	}

	public function getImagesByTag ( $search, $url = null, $recent = false ){

		if ( empty($url) ) {
			$url = "https://api.instagram.com/v1/".$this->searchType."/". $search ."/media/recent?client_id=" . $this->clientId;
		}
		$dataStream = file_get_contents( $url );
		if ($dataStream === FALSE) {
			throw new Exception("Cannot get information from url " . $url . " - check system requirements for file_get_contents");
		}
		$content = json_decode( $dataStream );
		
		$datas = $content->data;

		foreach ( $datas as $data ) {

			if ((is_array($this->blackList) && count($this->blackList) && !in_array($data->user->username, $this->blackList) ) || (!is_array($this->blackList))) {

				$item = array();
				$item['id'] = $data->caption->id;
				$item['created_time'] = $data->created_time;
				$item['username'] = $data->user->username;
				$item['text'] = !empty($data->caption->text) ? $data->caption->text : '';
				$item['standard_resolution'] = $data->images->standard_resolution->url;
				$item['thumbnail'] = $data->images->thumbnail->url;
				
				array_push($this->list, $item);
			}
		}
		if ( $recent === false && $content->pagination->next_url != "" ) {
			$this->getImagesByTag( $search, $content->pagination->next_url);
		}
	}
}

?>