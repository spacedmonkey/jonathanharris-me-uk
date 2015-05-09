<?php global $sm_data, $post; 
	if(isset($sm_data)){
		$tmp_post = $post;
		$post = $sm_data;
		setup_postdata( $post );
	}
	
	$format = get_post_format();


?>
<article id="post-<?php the_ID(); ?>" <?php post_class("col-lg-3 col-md-4 col-sm-6 col-xs-12 box"); ?>>
	<h2 class="entry-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
	<div class="entry-summary"><?php the_excerpt(); ?></div>  
	<p><a class="btn btn-primary" href="<?php echo twentythirteen_get_link_url(); ?>">Link &raquo;</a></p>
	
	<div class="icon-corner genericon genericon-<?php echo $format;?>"></div>
</article>
<?php if(isset($sm_data)){ $post = $tmp_post; } ?>
