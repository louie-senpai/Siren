<?php
 
	/**
	 * COMMENTS TEMPLATE
	 */
	/*if('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die(esc_html__('Please do not load this page directly.', 'akina'));*/
	if(post_password_required()){
		return;
	}
?>

	<?php if(comments_open()): ?>

	<section id="comments" class="comments">

		<div class="commentwrap comments-hidden">
			<div class="notification"><i class="iconfont">&#xe731;</i><?php esc_html_e('查看评论', 'akina'); ?> -
			<span class="noticom"><?php comments_number('NOTHING', '1 条评论', '% 条评论'); ?> </span>
			</div>
		</div>

		<div class="comments-main">
		 <h3 id="comments-list-title">Comments | <span class="noticom"><?php comments_number('NOTHING', '1 条评论', '% 条评论'); ?> <a href="#respond">直接下去评论</a> </span></h3> 
		<div id="loading-comments"><span></span></div>
			<?php if(have_comments()): ?>

				<ul class="commentwrap">
					<?php wp_list_comments('type=comment&callback=akina_comment_format'); ?>	
				</ul>

          <nav id="comments-navi">
				<?php paginate_comments_links('prev_text=« Older&next_text=Newer »');?>
			</nav>

			 <?php else : ?>

				<?php if(comments_open()): ?>
					<div class="commentwrap">
						<div class="notification-hidden"><i class="iconfont">&#xe731;</i> <?php esc_html_e('暂无评论', 'akina'); ?></div>
					
					</div>
				<?php endif; ?>

			<?php endif; ?>

			<?php
				if(comments_open()){
					$robot_comments = '<label class="siren-checkbox-label"><input class="siren-checkbox-radio" type="checkbox" name="no-robot"><span class="siren-no-robot-checkbox siren-checkbox-radioInput"></span>滴！网络好卡</label>';
					$private_ms = akina_option('open_private_message') ? '<label class="siren-checkbox-label"><input class="siren-checkbox-radio" type="checkbox" name="is-private"><span class="siren-is-private-checkbox siren-checkbox-radioInput"></span>私密评论</label>' : '';
					$args = array(
						'id_form' => 'commentform',
						'id_submit' => 'submit',
						'title_reply' => '',
						'title_reply_to' => '<div class="graybar"><i class="fa fa-comments-o"></i>' . esc_html__('Leave a Reply to', 'akina') . ' %s' . '</div>',
						'cancel_reply_link' => esc_html__('Cancel Reply', 'akina'),
						'label_submit' => esc_html__('Post Comment', 'akina'),
						'comment_field' => '<textarea placeholder="' . esc_attr__('Type in your comments', 'akina') . ' ..." name="comment" class="commentbody" id="comment" rows="5" tabindex="4"></textarea><p class="page-ignore"><div class="OwO"></div></p>',
						'comment_notes_after' => '',
						'comment_notes_before' => '',
						'fields' => apply_filters( 'comment_form_default_fields', array(
							'author' =>
								'<input type="text" placeholder="' . esc_attr__('昵称', 'akina') . ' ' . ( $req ?  '(' . esc_attr__('必须', 'akina') . ')' : '') . '" name="author" id="author" value="' . esc_attr($comment_author) . '" size="22" tabindex="1" ' . ($req ? "aria-required='true'" : '' ). ' />',
							'email' =>
								'<input type="text" placeholder="' . esc_attr__('邮箱', 'akina') . ' ' . ( $req ? '(' . esc_attr__('必须', 'akina') . ')' : '') . '" name="email" id="email" value="' . esc_attr($comment_author_email) . '" size="22" tabindex="1" ' . ($req ? "aria-required='true'" : '' ). ' />',
							'url' =>
								'<input type="text" placeholder="' . esc_attr__('网站', 'akina') . '" name="url" id="url" value="' . esc_attr($comment_author_url) . '" size="22" tabindex="1" />' . $robot_comments . $private_ms 
							)
						)
					);
					comment_form($args);
				}
			?>

		</div>


	</section>

<?php endif; ?>