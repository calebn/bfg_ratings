<?php
include("util.php");
$v_headerfooter = new v_HeaderFooter();
$c_rating = new c_Rating($cache_dir);
echo $v_headerfooter->getHeader(array('title'=>'Test'));
echo "<pre>";
var_dump($c_rating->processRatingInfo());
echo "</pre>";
echo $v_headerfooter->getFooter();

?>