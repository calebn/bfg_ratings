<?php
require_once('util.php');
if(isset($_GET['action'])){
	$c_rating = new c_Rating($cache_dir);
	$action = $_GET['action'];
	$start = (isset($_GET['start']) && strlen($_GET['start']>1)) ? $_GET['start'] : null;
	$end = (isset($_GET['end']) && strlen($_GET['end']>1)) ? $_GET['end'] : null;
	switch ($action) {
		case 'average':
			$weighted = isset($_GET['weighted']) ? $_GET['weighted'] : 'false';
			echo json_encode($c_rating->getAverage($weighted,$start,$end));
			break;
		case 'lowest':
			echo json_encode($c_rating->getLowest($start,$end));
			break;
		case 'highest':
			echo json_encode($c_rating->getHighest($start,$end));
			break;
		case 'popular':
			echo json_encode($c_rating->getMostPopular($start,$end));
			break;
		case 'unpopular':
			echo json_encode($c_rating->getLeastPopular($start,$end));
			break;
		default:
			die(json_encode(array("status"=>'failure','message'=>"Unrecognized action parameter '$action' supplied to rating.php")));
	}
}else{
	die(json_encode(array("status"=>'failure','message'=>"No 'action' parameter supplied to rating.php")));
}

?>