<?php
require_once('util.php');
require_once("lib/c/SimpleHtmlDom.php");
/**
* Gets ratings info from apple.com
*/
class m_Rating extends BaseLibClass{
	private $bfg_iphone = "https://itunes.apple.com/us/developer/big-fish-games-inc/id292594310?iPhoneSoftwarePage=";
	private $current_page;
	private $current_html;
	private $cached_rating_info;
	private $cached_processed_game_urls;
	private $cached_unprocessed_game_urls;
	private $cached_insufficient_ratings;
	private $rating_info;
	private $processed_game_urls;
	private $unprocessed_game_urls;
	private $insufficient_ratings;
	/**
	 * Search to use to find iphone ratings
	 * @var string
	 */
	private $iphone_div_search_string='div[metrics-loc=Titledbox_iPhone Apps]';
	private $title_search_string='div[id=title] h1';
	private $left_stack_search_string='div[id=left-stack]';
	private $last_update_search_string='span[itemprop=datePublished]';
	private $rating_search_string='span[itemprop=ratingValue]';
	private $num_ratings_search_string='span[itemprop=reviewCount]';

	public function __construct($cache_path,$clear_cache=false){
		set_time_limit(60);
		$this->cached_processed_game_urls=$cache_path."processed";
		$this->cached_unprocessed_game_urls=$cache_path."unprocessed";
		$this->cached_rating_info=$cache_path."rating_info";
		$this->cached_insufficient_ratings=$cache_path."insufficient_ratings";
		parent::__construct();
		$this->current_page=1;
		if($clear_cache){
			$this->clearCache();
		}
		$this->loadCache();
	}

	public function getRatingInfo(){
		try{

			foreach($this->unprocessed_game_urls as $key => $url){
				$html = file_get_html($url);
				$h1 = $html->find($this->title_search_string,0);
				$title = $h1->plaintext;
				$left_div = $html->find($this->left_stack_search_string,0);
				$rating_span= $left_div->find($this->rating_search_string,0);
				if($rating_span==null){
					$this->insufficient_ratings[]=$title;
					continue;
				}
				$rating = floatval($rating_span->plaintext);
				$num_ratings_span=$left_div->find($this->num_ratings_search_string,0);
				$num_ratings = intval(str_replace(array(' Ratings',' Rating'), '', $num_ratings_span->plaintext));
				$last_update_span=$left_div->find($this->last_update_search_string,0);
				$last_update = strtotime($last_update_span->plaintext);
				$this->rating_info[] = array(
					'title'=>$title,
					'rating'=>$rating,
					'num_ratings'=>$num_ratings,
					'last_update'=>$last_update
				);
				$this->processed_game_urls[]=$url;
				unset($this->unprocessed_game_urls[$key]);
			}
			$this->saveCache();
			return $this->successMessage($this->rating_info,"Got ratings for ".count($this->rating_info)."/".count($this->unprocessed_game_urls)+count($this->processed_game_urls)." games. ");
		}catch(Exception $e){
			$this->saveCache();
			return $this->failureMessage('',$this->printException($e));
		}
	}
	public function getIphoneAppPages(){
		try{
			if(count($this->unprocessed_game_urls)==0 && count($this->processed_game_urls)==0){
				$app_pages = array();
				do{
					$this->current_html = file_get_html($this->bfg_iphone.$this->current_page);
					$new_pages = $this->getIphoneGameURLs();
					$app_pages = array_merge($app_pages,$new_pages);
					$this->current_page++;
				}while($this->nextPageExists());
				$this->unprocessed_game_urls=$app_pages;
				$this->processed_game_urls=array();
				$this->insufficient_ratings=array();
				$this->rating_info=array();
				$this->saveCache();
				return $this->successMessage($app_pages,"Got ".count($app_pages)." game urls");
			}else{
				$this->saveCache();
				return $this->successMessage($this->unprocessed_game_urls,"Got ".count($this->unprocessed_game_urls)." unprocessed game urls");
			}
		}catch(Exception $e){
			$this->saveCache();
			return $this->failureMessage("",$this->printException($e));
		}
	}

	private function getIphoneGameURLs(){
		$iphone_div = $this->current_html->find($this->iphone_div_search_string,-1);
		foreach($iphone_div->find('div[class=lockup-info] a[class=name]') as $a){
			$game_urls[] = $a->href;
		}
		return $game_urls;
	}

	private function nextPageExists(){
		$next_button = $this->current_html->find($this->iphone_div_search_string.' a[class=paginate-more]',-1);
		return is_object($next_button);
	}

	private function loadCache(){
		if(file_exists($this->cached_rating_info)){
			$this->rating_info=unserialize(file_get_contents($this->cached_rating_info));
		}else{
			$this->rating_info=array();
		}
		if(file_exists($this->cached_processed_game_urls)){
			$this->processed_game_urls=unserialize(file_get_contents($this->cached_processed_game_urls));
		}else{
			$this->processed_game_urls=array();
		}
		if(file_exists($this->cached_unprocessed_game_urls)){
			$this->unprocessed_game_urls=unserialize(file_get_contents($this->cached_unprocessed_game_urls));
		}else{
			$this->unprocessed_game_urls=array();
		}
		if(file_exists($this->cached_insufficient_ratings)){
			$this->insufficient_ratings=unserialize(file_get_contents($this->cached_insufficient_ratings));
		}else{
			$this->insufficient_ratings=array();
		}
	}

	private function saveCache(){
		file_put_contents($this->cached_rating_info, serialize($this->rating_info));
		file_put_contents($this->cached_processed_game_urls, serialize($this->processed_game_urls));
		file_put_contents($this->cached_unprocessed_game_urls, serialize($this->unprocessed_game_urls));
		file_put_contents($this->cached_insufficient_ratings, serialize($this->insufficient_ratings));
	}

	private function clearCache(){
		file_put_contents($this->cached_rating_info,serialize(array()));
		file_put_contents($this->cached_processed_game_urls,serialize(array()));
		file_put_contents($this->cached_unprocessed_game_urls,serialize(array()));
		file_put_contents($this->cached_insufficient_ratings,serialize(array()));
	}
}
?>