<div id="centerbg" class="centerbg" style="<?php if (akina_option('focus_img')) { echo 'background-image: url('.akina_option('focus_img').');'; } if(akina_option('focus_height')){echo 'background-position: center center;background-attachment: inherit;';} ?>">
	<?php if ( akina_option('focus_infos') == false){ ?>
	<div class="focusinfo">
   		<?php if (akina_option('focus_logo')):?>
	     <div class="header-tou"><a href="<?php bloginfo('url');?>" ><img src="<?php echo akina_option('focus_logo', ''); ?>"></a></div>
	  	<?php else :?>
         <div class="header-tou" ><a href="<?php bloginfo('url');?>"><img src="<?php bloginfo('template_url'); ?>/images/avatar.jpg"></a></div>	
      	<?php endif; ?>
		<div class="header-info"><p><?php echo akina_option('admin_des', 'Carpe Diem and Do what I like'); ?></p></div>
		<div class="top-social">
		<?php if (akina_option('wechat')){ ?>
				<li class="wechat"><a href="#"><img src="<?php bloginfo('template_url'); ?>/images/sns/wechat.png"/></a>
			<div class="wechatInner">
				<img src="<?php echo akina_option('wechat', ''); ?>" alt="微信公众号">
			</div>
		  </li>
			<?php } ?> 
	    <?php if (akina_option('sina')){ ?>
				<li><a href="<?php echo akina_option('sina', ''); ?>" target="_blank" class="social-sina" title="sina"><img src="<?php bloginfo('template_url'); ?>/images/sns/sina.png"/></a></li>
			<?php } ?>
		<?php if (akina_option('qq')){ ?>
				<li class="qq"><a href="//wpa.qq.com/msgrd?v=3&uin=<?php echo akina_option('qq', ''); ?>&site=qq&menu=yes" target="_blank" title="Initiate chat ?"><img src="<?php bloginfo('template_url'); ?>/images/sns/qq.png"/></a></li>
			<?php } ?>	
		<?php if (akina_option('qzone')){ ?>
				<li><a href="<?php echo akina_option('qzone', ''); ?>" target="_blank" class="social-qzone" title="qzone"><img src="<?php bloginfo('template_url'); ?>/images/sns/qzone.png"/></a></li>
			<?php } ?>
		<?php if (akina_option('github')){ ?>
				<li><a href="<?php echo akina_option('github', ''); ?>" target="_blank" class="social-github" title="github"><img src="<?php bloginfo('template_url'); ?>/images/sns/github.png"/></a></li>
			<?php } ?>	
		<?php if (akina_option('lofter')){ ?>
				<li><a href="<?php echo akina_option('lofter', ''); ?>" target="_blank" class="social-lofter" title="lofter"><img src="<?php bloginfo('template_url'); ?>/images/sns/lofter.png"/></a></li>
			<?php } ?>	
		<?php if (akina_option('bili')){ ?>
				<li><a href="<?php echo akina_option('bili', ''); ?>" target="_blank" class="social-bili" title="bilibili"><img src="<?php bloginfo('template_url'); ?>/images/sns/bilibili.png"/></a></li>
			<?php } ?>
		<?php if (akina_option('youku')){ ?>
				<li><a href="<?php echo akina_option('youku', ''); ?>" target="_blank" class="social-youku" title="youku"><img src="<?php bloginfo('template_url'); ?>/images/sns/youku.png"/></a></li>
			<?php } ?>
		<?php if (akina_option('wangyiyun')){ ?>
				<li><a href="<?php echo akina_option('wangyiyun', ''); ?>" target="_blank" class="social-wangyiyun" title="CloudMusic"><img src="<?php bloginfo('template_url'); ?>/images/sns/wangyiyun.png"/></a></li>
			<?php } ?>		
	  </div>		 
	</div>
	<?php } ?>
</div>
<?php
echo bgvideo(); //BGVideo 