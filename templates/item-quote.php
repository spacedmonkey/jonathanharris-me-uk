<?php global $sm_data, $post; 
	if(isset($sm_data)){
		$tmp_post = $post;
		$post = $sm_data;
		setup_postdata( $post );
	}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class("col-lg-3 col-md-4 col-sm-6 col-xs-12 box"); ?>>
	<blockquote class="post-quote">
		<div class="entry-summary"><?php the_excerpt(); ?></div> 
		<small><cite title="<?the_title();?>" class="entry-title"><?the_title();?></cite></small>
	</blockquote>
	<div class="icon-corner genericon genericon-quote"></div>
</article>
<?php if(isset($sm_data)){ $post = $tmp_post; } ?>
