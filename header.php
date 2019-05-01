<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="hfeed  site">
		<header id="masthead" class="site-header navbar navbar-inverse <?php echo ( is_admin_bar_showing() ) ? '' : 'navbar-fixed-top';?>" role="banner">
			<div class="container">
		        	<div class="navbar-header">
				          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
				            <span class="icon-bar"></span>
				            <span class="icon-bar"></span>
				            <span class="icon-bar"></span>
				          </button>
						  <a class="home-link navbar-brand site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
		        	</div>
					<nav id="navbar" class="navbar-collapse collapse" role="navigation">
						<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav navbar-nav nav-menu' ) ); ?>


						<?php if ( has_nav_menu( 'social' ) ) {

							wp_nav_menu(
								array(
									'theme_location'  => 'social',
									'container'       => 'div',
									'container_id'    => 'menu-social',
									'container_class' => 'menu',
									'menu_id'         => 'menu-social-items',
									'menu_class'      => 'nav navbar-nav navbar-right',
									'depth'           => 1,
									'link_before'     => '<span class="sr-only">',
									'link_after'      => '</span>',
									'walker'          => new wp_bootstrap_navwalker_social(),
									'fallback_cb'     => '',
								)
							);

						} ?>
					</nav><!-- #navbar -->
				</div>
			</header><!-- #masthead -->

			<section id="main" class="site-main container">
							<div class="row ">
