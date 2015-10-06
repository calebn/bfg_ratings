<?php
require_once('util.php');
class c_Rating extends BaseLibClass{
	/**
	 * Game tiles and rating
	 * @var array
	 */
	private $rating_info;
	/**
	 * Class to process rating info
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
		if($m_response['status']!='success'){
			return $m_response;
		}
		$rating_info = $m_response['data'];
		usort($rating_info,array('c_rating','dateSort'));
		return $this->successMessage($rating_info,"Sorted by date");
	}

	
	private static function dateSort($rating_a,$rating_b){
		return strcmp((string)$rating_a['last_update'],(string)$rating_b['last_update']);
	}

	private static function titleSort($rating_a,$rating_b){
		return strcmp((string)$rating_a['title'],(string)$rating_b['title']);
	}

	private static function ratingSort($rating_a,$rating_b){
		return strcmp((string)$rating_a['rating'],(string)$ratinb_b['rating']);
	}

	private static function numRatingSort($rating_a,$rating_b){
		return strcmp((string)$rating_a['num_ratings'],(string)$rating_b['num_ratings']);
	}
}
?>