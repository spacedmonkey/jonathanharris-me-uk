
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 box format-instagram">
          <div class="thumbnail">
<?php global $sm_data; 
	$image = $sm_data->images->low_resolution;
	$imageLarge = ''; //strip_tags($sm_data->caption->text);

	printf('<a href="%s" title="%s"><img src="%s" width="%s" height="%s" alt="%s"/></a>',$sm_data->link,$imageLarge,$image->url,$image->width,$image->height, $imageLarge);
	
	
?>
          </div>
          <div class="icon-corner genericon genericon-instagram"></div>
</div>