 
<?php global $sm_data; 
	$sm_data->get_permalink();
	$embed_code = wp_oembed_get($sm_data->get_permalink());
	if(!$embed_code){
		return;
	}
	

?>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 padding-top box format-youtube">
	<div class="icon-corner genericon genericon-youtube"></div>
	<?php echo $embed_code;?>
</div>