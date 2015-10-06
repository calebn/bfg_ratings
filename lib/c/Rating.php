<?php
require_once('util.php');
class c_Rating extends BaseLibClass{
	private $cache_path;
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
		$this->cache_path=$cache_path;
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
}
?>