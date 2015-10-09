/*
Utility Functions
requires jquery
 */

/**
 * Gets all the elements of the page, and applies a 1 px outline to them with a random color
 * @author  https://gist.github.com/addyosmani/fd3999ea7fce242756b1
 */
function CSSLayerDebug(){
    [].forEach.call($("*"),function(a){
      a.style.outline="1px solid #"+(~~(Math.random()*(1<<24))).toString(16)
    });
}

/*
End Utility Functions
 */

 $(document).ready(function(){
	//CSSLayerDebug();
})