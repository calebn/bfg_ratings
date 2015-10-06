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
	

	public function __construct($cache_path,$clear_cache=false){
		parent::__construct();
		$this->m_rating = new m_Rating($cache_path,$clear_cache);
	}

	public function processRatingInfo(){
		$m_response = $this->m_rating->getIphoneAppPages();
		if($m_response['status']!='success'){
			return $m_response;
		}
		$m_response = $this->m_rating->getRatingInfo();
		return $m_response;
	}
}
?>