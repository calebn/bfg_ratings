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

	private $app_store_launch_date = '07/10/2008';

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
			$m_response = $this->m_rating->getIphoneAppPages();
			if($m_response['status']!='success'){
				return $m_response;
			}
			$m_response = $this->m_rating->getRatingInfo();
			if($m_response['status']!='success'){
				return $m_response;
			}
			$this->rating_info = $m_response['data'];
			$this->sortRatings('rating');
			return $this->successMessage($this->rating_info,"Sorted by date");
		}catch(Exception $e){
			return $this->failureMessage('',$this->printException($e));
		}
	}

	public function getAverage($weighted='false',$start=null,$end=null){
		try{
			if(!isset($this->raiting_info)){
				$m_response = $this->m_rating->getRatingInfo();
				if($m_response['status']!='success'){
					return $m_response;
				}
				$this->rating_info = $m_response['data'];
			}
			$this->getRatingInfoSubset('date',$start,$end);
			if(count($this->subset_ratings)==0){
				return $this->warningMessage('', 'No ratings matched your criteria');
			}
			$average = $weighted==='false' ? $this->subset_average : $this->calcWeightedAverage();
			$is_weighted = $weighted=='false' ? '' : ' weighted';
			return $this->successMessage($average,"The$is_weighted average rating is $average");
		}catch(Exception $e){
			return $this->failureMessage($this->rating_info,$this->printException($e));
		}
	}

	public function getHighest($start=null,$end=null){
		return $this->getOneRating('highest','rating',$start,$end);
	}

	public function getLowest($start=null,$end=null){
		return $this->getOneRating('lowest','rating',$start,$end);
	}

	public function getMostPopular($start,$end){
		return $this->getOneRating('highest','popularity',$start,$end);
	}

	public function getLeastPopular($start,$end){
		return $this->getOneRating('lowest','popularity',$start,$end);
	}

	private function getOneRating($rating_kind,$sort,$start=null,$end=null){
		try{
			if(!isset($this->raiting_info)){
				$m_response = $this->m_rating->getRatingInfo();
				if($m_response['status']!='success'){
					return $m_response;
				}
				$this->rating_info = $m_response['data'];
			}
			$this->getRatingInfoSubset($sort,$start,$end);
			if(count($this->subset_ratings)==0){
				return $this->warningMessage('', 'No ratings matched your criteria');
			}
			switch ($rating_kind) {
				case 'highest':
					$requested_rating = $this->subset_ratings[count($this->subset_ratings)-1];
					break;
				case 'lowest':
					$requested_rating = $this->subset_ratings[0];
					break;
				default:
					throw new Exception("Unknown rating_kind '$rating_kind' in".__file__." on ".__line__);
					break;
			}
			return $this->successMessage($requested_rating,"Successfully got $rating_kind $sort");
		}catch(Exception $e){
			return $this->failureMessage($this->rating_info,$this->printException($e));
		}
	}

	private function calcWeightedAverage(){
		foreach($this->subset_ratings as $subset_rating){
			$this->weighted_average+=($subset_rating['rating']*$subset_rating['num_ratings'])/$this->subset_total_ratings;
		}
		return $this->weighted_average;
	}

	private function getRatingInfoSubset($sort,$start,$end){
		$this->subset_ratings=array();
		$this->subset_total_ratings=0;
		$idx = 0;
		if(isset($start) || isset($end)){
			$start =  isset($start) ? strtotime($start) : strtotime($this->app_store_launch_date);
			$idx = $this->getRatingStartIndex($start);
		}
		while($idx < count($this->rating_info)){
			$temp_rating = $this->rating_info[$idx];
			if(isset($end) && strtotime($end) < $temp_rating['date']){
				$idx = count($this->rating_info);
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
	}

	private function getRatingStartIndex($start_date){
		$this->sortRatings('date');
		return $this->binary_search($this->rating_info,0,sizeof($this->rating_info),array('date'=>$start_date),$this->getUsortSortVal('date'));
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
		return $rating_a['date'] - $rating_b['date'];
	}

	private static function titleSort($rating_a,$rating_b){
		return strcmp((string)$rating_a['title'],(string)$rating_b['title']);
	}

	private static function ratingSort($rating_a,$rating_b){
		return strcmp((string)$rating_a['rating'],(string)$rating_b['rating']);
	}

	private static function numRatingSort($rating_a,$rating_b){
		return $rating_a['num_ratings'] - $rating_b['num_ratings'];
	}

	/**
	 * Generic Binary Search
	 * @param  array  $a       The sorted haystack
	 * @param  mixed $first   First index of the array to be searched (inclusive).
	 * @param  mixed $last    Last index of the array to be searched (exclusive).
	 * @param  mixed $key     The key to be searched for.
	 * @param  string $compare A user defined function for comparison. Same definition as the one in usort
	 * @return integer         index of the search key if found, otherwise return (-insert_index - 1). insert_index is the index of smallest element that is greater than $key or sizeof($a) if $key is larger than all elements in the array.
	 * @link   https://terenceyim.wordpress.com/2011/02/01/all-purpose-binary-search-in-php/
	 */
	private function binary_search(array $a, $first, $last, $key, $compare) {
	    $lo = $first; 
	    $hi = $last - 1;

	    while ($lo <= $hi) {
	        $mid = (int)(($hi - $lo) / 2) + $lo;
	        $cmp = call_user_func($compare, $a[$mid], $key);
	        if ($cmp < 0) {
	            $lo = $mid + 1;
	        } elseif ($cmp > 0) {
	            $hi = $mid - 1;
	        } else {
	            return $mid;
	        }
	    }
	    return $lo;
	}
}
?>