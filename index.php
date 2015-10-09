<?php
include("util.php");
$v_headerfooter = new v_HeaderFooter();
$c_rating = new c_Rating($cache_dir);
echo $v_headerfooter->getHeader(array('title'=>'BFG Ratings'));
ob_start();
?>
	<div class="grid">
		<div class="main-title col-1-1">Big Fish Rating Info</div>
		<div class="rating-options col-1-3 push-1-3 mobile-col-2-3">
			<div class="col-1-1">
				<div class="col-1-2 mobile-col-1-1">
					<label for="rating-type">Rating Type</label>
				</div>
				<div class="col-1-2 mobile-col-1-1">
					<select id="rating-type">
						<option selected="" value="average">Average</option>
						<option value="weighted-average">Weighted Average</option>
						<option value="highest">Highest rated game</option>
						<option value="lowest">Lowest rated game</option>
						<option value="popular">Most popular game</option>
						<option value="unpopular">Least popular game</option>
					</select>
				</div>
			</div>
			<div class="hide-on-mobile col-1-1">
				<div class="col-1-1">
					<div class="col-1-2">
						<label for="start-date">Start</label>
					</div>
					<div class="col-1-2">
						<input type="text" id="start-date">
					</div>
				</div>
				<div class="col-1-1">
					<div class="col-1-2">
						<label for="end-date">End</label>
					</div>
					<div class="col-1-2">
						<input type="text" id="end-date">
					</div>
				</div>
			</div>
			<div class="hide show-on-mobile-only mobile-col-1-1">
				<div class="mobile-col-1-1">
					<label for="mobile-start-date">Start</label>
					<input type="date" id="mobile-start-date">
				</div>
				<div class="mobile-col-1-1">
					<label for="mobile-end-date">End</label>
					<input type="date" id="mobile-end-date">
				</div>
			</div>
		</div>
		<div class="mobile-col-1-1 btn" id="go">GO</div>
	</div>
	<div class="rating-options col-1-3 push-1-3 mobile-col-2-3" id="rating-response">
	</div>
<?php
echo ob_get_clean();
$v_headerfooter->addScript('lib/v/rating.js');
echo $v_headerfooter->getFooter();

?>