<?php 

	/**
	 * DISQUS COMMENTS
	 */

?>

<?php if (!is_home()&&(comments_open()) ){ ?>
	<section class="duoshuowrapper comments">
		<div class="commentwrap comments-hidden">
			<div class="notification"><i class="iconfont">&#xe667;</i><?php esc_html_e('查看评论', 'akina'); ?></div>
		</div>
		<div class="comments-main">
			<div class="commentwrap">
				<?php comments_template('', true); ?>
			</div>
		</div>
	</section>
	<?php } ?>
