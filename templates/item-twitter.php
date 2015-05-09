<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 padding-top box">
          <div class="alert alert-info">
<?php global $sm_data; 

	printf('<p class="tweet">%s<br /><small class="text-muted">%s</small></p>',tweet_to_link_text($sm_data->text),$sm_data->created_at);


?>
			</div>
		   	<span class="triangle-down"></span>
		   	<div class="icon-corner genericon genericon-twitter"></div>
</div>