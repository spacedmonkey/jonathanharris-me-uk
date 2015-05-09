jQuery(function($) {
	doFitVid();
	
	
	
	
});

jQuery( document ).on( 'ready post-load', function($) {

		setInterval( doFitVid(), 1 );
});

function doFitVid(){
	jQuery("#main").fitVids();
}