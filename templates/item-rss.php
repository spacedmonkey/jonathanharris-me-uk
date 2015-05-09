<?php global $sm_data; 

$string = substr(strip_tags($sm_data->get_description()),0,90).'...';

if ($enclosure = $sm_data->get_enclosure()){
	$type = $enclosure->get_type();
	switch ($type) {
	    case 'image/jpeg':
	    case 'image/png':
	    case 'image/gif':
	      $string = sprintf('<div class="thumbnail"><a href="%s" title="%s"><img src="%s" /></a></div>',$sm_data->get_permalink(),$sm_data->get_title(),$enclosure->get_link());
	    break;
    }
	
	
}
	
?>


<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 box hentry">
     <h2 class="entry-title"><a href="<?php echo $sm_data->get_permalink();?>"><?php echo $sm_data->get_title();?></a></h2>
     <div class="entry-summary"><p><?php echo $string;?></p></div>
     <div class="icon-corner genericon genericon-feed"></div>
</div>