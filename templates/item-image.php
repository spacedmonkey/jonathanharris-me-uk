<?php global $sm_data, $post; 
	if(isset($sm_data)){
		$tmp_post = $post;
		$post = $sm_data;
		setup_postdata( $post );
	}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class("col-lg-3 col-md-4 col-sm-6 col-xs-12 box"); ?>>
	<div class="thumbnail">
		<a href="<?php the_permalink();?>" title="<?php the_title();?>">
			<?php get_spacedmonkey_image("thumbnail");?>
		</a>
		<div class="caption sr-only">
			<h3 class="entry-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
		</div>
	</div>
	<div class="icon-corner genericon genericon-image"></div>
</article>
<?php if(isset($sm_data)){ $post = $tmp_post; } ?>
