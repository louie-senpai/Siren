<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Akina
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Cache-Control" content="no-transform " /> 
<title itemprop="name"><?php global $page, $paged;wp_title( '-', true, 'right' );
bloginfo( 'name' );$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) ) echo " - $site_description";if ( $paged >= 2 || $page >= 2 ) echo ' - ' . sprintf( __( '第 %s 页'), max( $paged, $page ) );?>
</title>
<?php
if (akina_option('akina_meta') == true) {
	$keywords = '';
	$description = '';
	if ( is_singular() ) {
		$keywords = '';
		$tags = get_the_tags();
		$categories = get_the_category();
		if ($tags) {
			foreach($tags as $tag) {
				$keywords .= $tag->name . ','; 
			};
		};
		if ($categories) {
			foreach($categories as $category) {
				$keywords .= $category->name . ','; 
			};
		};
		$description = mb_strimwidth( str_replace("\r\n", '', strip_tags($post->post_content)), 0, 240, '…');
	} else {
		$keywords = akina_option('akina_meta_keywords');
		$description = akina_option('akina_meta_description');
	};
?>
<meta name="description" content="<?php echo $description; ?>" />
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<link rel="shortcut icon" href="<?php echo esc_url( get_template_directory_uri() ); ?>/images/favicon.ico"/>
<?php wp_head(); ?>
<!--[if lt IE 11]>
<meta http-equiv="Refresh" Content="3; url=https://www.liaronce.win/ieupdate.html" />
<![endif]-->
</head>
<body <?php body_class(); ?>>
	<section id="main-container">
		<?php 
		if(!akina_option('head_focus')){ 
		$filter = akina_option('focus_img_filter');
		?>
		<div class="headertop <?php echo $filter; ?>">
			<?php get_template_part('layouts/imgbox'); ?>
		</div>	
		<?php } ?>
		<div id="page" class="site wrapper">
			<header class="site-header" role="banner">
				<div class="site-top">
					<div class="site-branding">
						<?php if (akina_option('akina_logo')){ ?>
						<div class="site-title"><a href="<?php echo esc_url( home_url() );?>" ><img src="<?php echo akina_option('akina_logo'); ?>"></a></div>
						<?php }else{ ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url() );?>" ><?php bloginfo('name');?></a></h1>	
						<?php } ?><!-- logo end -->
					</div><!-- .site-branding -->
					<?php header_user_menu(); if(akina_option('top_search') == 'yes') { ?>
					<div class="searchbox"><i class="iconfont js-toggle-search iconsearch">&#xe65c;</i></div>
					<?php } ?>
					<div class="lower"><?php if(!akina_option('shownav')){ ?>
						<div id="show-nav" class="showNav">
							<div class="line line1"></div>
							<div class="line line2"></div>
							<div class="line line3"></div>
						</div><?php } ?>
						<nav><?php wp_nav_menu( array( 'depth' => 2, 'theme_location' => 'primary', 'container' => false ) ); ?></nav><!-- #site-navigation -->
					</div>	
				</div>
			</header><!-- #masthead -->
			<?php the_headPattern(); ?>
		    <div id="content" class="site-content">