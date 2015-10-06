<?php
require_once('util.php');
class c_Rating extends BaseLibClass{
	/**
	 * Game tiles and rating
	 * @var array
	 */
	private $rating_info;
	/**
	 * Class to get ratings pages from apple
	 * @var class
	 */
	private $m_rating;
	

	public function __construct($cache_path){
		parent::__construct();
		$this->m_rating = new m_Rating($cache_path);
	}

	public function refreshRatingInfo(){
		$m_response = $this->m_rating->getIphoneAppPages();
		if($m_response['status']=='failure'){
			return $m_response;
		}
		$m_response = $this->m_rating->getRatingInfo($m_response['data']);
		return $m_response;

	}

	public function clearCache(){
		try{
			unlink($this->$cached_processed_game_urls);
			unlink($this->$cached_unprocessed_game_urls);
			unlink($this->$cached_ratings);
		}catch(Exception $e){
			return $this->failureMessage("",$this->printException($e));
		}
	}
}
?>