<?php 
/**
 * AUTHOR PROFILE
 */
if ( akina_option('author_profile') == 'yes') {
?>
	<section class="author-profile">
		<div class="info" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
			<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="profile gravatar"><img src="//cn.gravatar.com/avatar/<?php echo esc_attr(md5(get_the_author_meta('user_email'))); ?>?s=80" itemprop="image" alt="<?php the_author(); ?>"></a>
			<div class="meta">
				<span class="title"><?php esc_html_e('Author', 'akina'); ?></span>	
				<h3 itemprop="name">
					<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" itemprop="url" rel="author"><?php the_author(); ?></a>
				</h3>						
			</div>
		</div>
		<hr>
		<p><i class="iconfont">&#xe61a;</i><?php echo get_the_author_meta( 'description' ) ? get_the_author_meta( 'description' ) :akina_option('admin_des', 'Carpe Diem and Do what I like'); ?></p>
	</section>

<?php } ?>