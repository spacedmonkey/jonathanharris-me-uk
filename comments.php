<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php
				
				printf( _nx( 'Comment %1$s', 'Comments %1$s', get_comments_number() , 'comments title', 'twentythirteen' ),
					 '<small class="label label-primary">' . get_comments_number() . '</small>' );
			?>
		</h3>
		<hr />
		<ol class="comment-list media-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 74,
					'walker' => new Roots_Walker_Comment
				) );
			?>
		</ol><!-- .comment-list -->

		<?php
			// Are there comments to navigate through?
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
		?>
		<nav class="navigation comment-navigation" role="navigation">
			<ul class="pager">
				<li class="nav-previous previous"><?php previous_comments_link( __( '<span class="meta-nav glyphicon glyphicon-chevron-left"></span> Older Comments', 'twentythirteen' ) ); ?></li>
				<li class="nav-next next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav glyphicon glyphicon-chevron-right"></span>', 'twentythirteen' ) ); ?></li>
			</ul>
		</nav><!-- .comment-navigation -->
		<?php endif; // Check for comment navigation ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
			<p class="no-comments"><?php _e( 'Comments are closed.' , 'twentythirteen' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php 
	if(comments_open()){
		echo '<hr />';
	}
		spacedmonkey_comment_form();
	
	
	
	?>

</div><!-- #comments -->