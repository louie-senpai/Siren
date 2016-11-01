<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */
get_header();
?>
   
   <?php if ( akina_option('head_notice') != '0'){ ?>
	<div class="notice">
	   <i class="iconfont">&#xe607;</i>  
		<div class="notice-content">
		<?php echo akina_option('notice_title');?>
		</div>
	</div>
	<?php } ?>
	
	<?php 
		if(akina_option('top_feature')=='1'){
			get_template_part('layouts/feature');
		}
	?>
	
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">	
		<h1 class="main-title">Posts</h1>
		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) : ?>
			<header>
				<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
			</header>

			<?php
			endif;

			/* Start the Loop */
			while ( have_posts() ) : the_post();
				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'tpl/content', get_post_format() );
			endwhile; ?>
		<?php else : get_template_part( 'tpl/content', 'none' ); endif; ?>
		</main><!-- #main -->
		<?php if ( akina_option('pagenav_style') == 'ajax') { ?>
		<div id="pagination"><?php next_posts_link(__('Previous')); ?></div>
		<?php }else{ ?>
		<nav class="navigator">
		<?php previous_posts_link('<i class="iconfont">&#xe611;</i>') ?><?php next_posts_link('<i class="iconfont">&#xe60f;</i>') ?>
		</nav>
		<?php } ?>
	</div><!-- #primary -->
<?php
get_footer();