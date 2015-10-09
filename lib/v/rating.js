/*
	specific javascript for ratings
 */

$(document).ready(function(){
	$('#start-date').datepicker({
      changeMonth: true,
      changeYear: true
    });
	$('#end-date').datepicker({
      changeMonth: true,
      changeYear: true
    });

    $('#go').on('click', function(){
    	var rating_type = $('#rating-type option:selected').val();
    	var weighted = false;
    	var start = ($('#start-date').val().length == 0  ? $('#mobile-start-date').val() : $('#start-date').val());
    	var end = ($('#end-date').val().length == 0 ? $('#mobile-end-date').val() : $('#end-date').val());
    	if(rating_type.indexOf('average')>-1){
    		action_type = 'average';
    		if(rating_type.indexOf('weighted')>-1){
    			weighted = true;
    		}
    	}else{
    		action_type = rating_type;
    	}
    	$.ajax({
			url: "api_rating.php",
			type: 'GET',
			dataType: 'json',
			data : {
				'action': action_type,
				'weighted':weighted,
				'start': start,
				'end': end
			}
		}).done(function(response){
			$('#rating-response').html(response.message);
			if(rating_type == 'popular' || rating_type == 'unpopular' || rating_type == 'highest' || rating_type == 'lowest'){
				if(response['status']=='success'){
					outputRating(response['data']);
				}
			}
		}).fail(function(response){
			alert(response.responseText);
		})
    });
})

function outputRating(rating_info){
	var title = rating_info['title'];
	var rating = rating_info['rating'];
	var popularity = rating_info['num_ratings'];
	var date = new Date(rating_info['date']*1000).toString()
	$('#rating-response').html('');
	$('#rating-response').append('<span class="game_title">'+title+'</span><br>');
	$('#rating-response').append('<span class="game_title">Rating: '+rating+'</span><br>');
	$('#rating-response').append('<span class="game_title">Number of ratings: '+popularity+'</span><br>');
	$('#rating-response').append('<span class="game_title">Last Updated: '+date+'</span><br>');
}