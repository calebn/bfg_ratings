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

	private $bfg_founded_date = '2002';

	private $subset_total_ratings = 0;

	private $subset_average = 0;

	private $weighted_average = 0;

	private $sort_key_map = array(
		'date' => array('c_rating','dateSort'),
		'popularity' => array('c_rating','numRatingSort'),
		'rating'      => array('c_rating','ratingSort'),
		'title'       => array('c_rating','titleSort')
	);
	
	public function __construct($cache_path,$clear_cache=false){
		parent::__construct();
		$this->m_rating = new m_Rating($cache_path,$clear_cache);
	}

	public function processRatingInfo(){
		try{
			if(!isset($this->m_rating)){
				$this->m_rating = new m_Rating();
			}
			$m_response = $this->m_rating->getIphoneAppPages();
			if($m_response['status']!='success'){
				return $m_response;
			}
			$m_response = $this->m_rating->getRatingInfo();
			if($m_response['status']!='success'){
				return $m_response;
			}
			$this->rating_info = $m_response['data'];
			unset($m_response);
			usort($this->rating_info,array('c_rating','ratingSort'));
			return $this->successMessage($this->rating_info,"Sorted by date");
		}catch(Exception $e){
			return $this->failureMessage('',$this->printException($e));
		}
	}

	public function getAverage($weighted=false,$start=null,$end=null){
		try{
			if(!isset($this->raiting_info)){
				if(!isset($this->m_rating)){
					$this->m_rating = new m_Rating();
				}
				$m_response = $this->m_rating->getRatingInfo();
				if($m_response['status']!='success'){
					return $m_response;
				}
				$this->rating_info = $m_response['data'];
			}
			unset($m_response);
			$this->getRatingInfoSubset('date',$start,$end);
			unset($this->m_rating);//save some memory
			if(count($this->subset_ratings)==0){
				return $this->warningMessage('', 'No ratings matched your criteria');
			}
			$average = $weighted===false ? $this->subset_average : $this->calcWeightedAverage();
			return $this->successMessage($average,"The average rating is $average");
		}catch(Exception $e){
			echo $e->getTraceAsString();
			return $this->failureMessage('',$this->printException($e));
		}
	}

	private function calcWeightedAverage(){
		foreach($this->subset_ratings as $subset_rating){
			$this->weighted_average+=$subset_rating['rating']*($subset_rating['rating']/$this->subset_total_ratings);
		}
		return $this->weighted_average;
	}

	private function getRatingInfoSubset($sort,$start,$end){
		$this->subset_ratings=array();
		$this->subset_total_ratings=0;
		$idx = 0;
		if(isset($start) || isset($end)){
			$start =  isset($start) ? strtotime($start) : strtotime($this->bfg_founded_date);
			$idx = $this->getRatingStartIndex($start);
		}
		while($idx < count($this->rating_info)){
			$temp_rating = $this->rating_info[$idx];
			if(isset($end) && strtotime($end) > $temp_rating['date']){
				$idx = count($this->rating_info[$idx]);
			}else{
				$this->subset_average+=$temp_rating['rating'];
				$this->subset_total_ratings+=$temp_rating['num_ratings'];
				$this->subset_ratings[] = $temp_rating;
			}
			$idx++;
		}
		if(count($this->subset_ratings)>0){
			$this->subset_average = $this->subset_average/count($this->subset_ratings);
		}
		if($sort != 'date'){
			$this->sortRatings($sort,'subset_ratings');
		}
		//return $this->sortRatings($sort,'subset_ratings');
	}

	private function getRatingStartIndex($start_date){
		$this->sortRatings('date',$start_date);
		return binary_search($this->rating_info,0,sizeof($this->rating_info),$start_date,$this->getUsortSortVal());
	}

	private function sortRatings($by='title',$which_array='rating_info'){
		if($which_array=='rating_info'){
			usort($this->rating_info,$this->getUsortSortVal($by));
		}else{
			usort($this->subset_ratings,$this->getUsortSortVal($by));
		}
	}

	private function getUsortSortVal($key){
		return $this->sort_key_map[strtolower($key)];
	}

	private static function dateSort($rating_a,$rating_b){
		return strcmp((string)$rating_a['date'],(string)$rating_b['date']);
	}

	private static function titleSort($rating_a,$rating_b){
		return strcmp((string)$rating_a['title'],(string)$rating_b['title']);
	}

	private static function ratingSort($rating_a,$rating_b){
		return strcmp((string)$rating_a['rating'],(string)$rating_b['rating']);
	}

	private static function numRatingSort($rating_a,$rating_b){
		return strcmp((string)$rating_a['num_ratings'],(string)$rating_b['num_ratings']);
	}
}
?>