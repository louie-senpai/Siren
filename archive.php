<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) : ?>

			<?php if(akina_option('patternimg') || !z_taxonomy_image_url()) { ?>
			<header class="page-header">
				<h1 class="cat-title"><?php single_cat_title('', true); ?></h1>
			<span class="cat-des">
			<?php 
				if(category_description() !== ""){ 
					echo "" . category_description(); 
				} 
			?>
			</span>
			</header><!-- .page-header -->
			<?php } // page-header ?>

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post(); 
			?>
			
			<?php 
					/*
					* 如果选择分类ID  那么这个页面将输出works样式
					*/
				$cat_array = akina_option('works_multicheck');
				$huluwa = array();
				foreach ($cat_array as $key=>$works_multicheck){
					if ($works_multicheck==1) $huluwa[]=$key;
				} 				
				if ( is_category($huluwa) ){
					include(TEMPLATEPATH . '/tpl/works-list.php');
				} else {
					/*
					* Include the Post-Format-specific template for the content.
					* If you want to override this in a child theme, then include a file
					* called content-___.php (where ___ is the Post Format name) and that will be used instead.
					*/
					get_template_part( 'tpl/content', get_post_format() );
				}
				
				endwhile; ?>
				<div class="clearer"></div>

		<?php else :

			get_template_part( 'tpl/content', 'none' );

		endif; ?>

		</main><!-- #main -->
		<?php if ( akina_option('pagenav_style') == 'ajax') { ?>
		<div id="pagination"><?php next_posts_link(__('加载更多')); ?></div>
		<?php }else{ ?>
		<nav class="navigator">
        <?php previous_posts_link('<i class="iconfont">&#xe611;</i>') ?><?php next_posts_link('<i class="iconfont">&#xe60f;</i>') ?>
		</nav>
		<?php } ?>
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
