<?php 
/**
 Template Name: Register
 */

get_header();
if( !empty($_POST['register_reg']) ) {
	$error = '';
	$sanitized_user_login = sanitize_user( $_POST['user_login'] );
	$user_email = apply_filters( 'user_registration_email', $_POST['user_email'] );

	// Check the username
	if ( $sanitized_user_login == '' ) {
	  $error .= '<strong>错误</strong>：请输入用户名。<br />';
	} elseif ( ! validate_username( $sanitized_user_login ) ) {
	  $error .= '<strong>错误</strong>：此用户名包含无效字符，请输入有效的用户名。<br />';
	  $sanitized_user_login = '';
	} elseif ( username_exists( $sanitized_user_login ) ) {
	  $error .= '<strong>错误</strong>：该用户名已被注册。<br />';
	}

	// Check the e-mail address
	if ( $user_email == '' ) {
	  $error .= '<strong>错误</strong>：请填写电子邮件地址。<br />';
	} elseif ( ! is_email( $user_email ) ) {
	  $error .= '<strong>错误</strong>：电子邮件地址不正确。<br />';
	  $user_email = '';
	} elseif ( email_exists( $user_email ) ) {
	  $error .= '<strong>错误</strong>：该电子邮件地址已经被注册。<br />';
	}

	// Check the password
	if(strlen($_POST['user_pass']) < 6)
	  $error .= '<strong>错误</strong>：密码长度至少6位。<br />';
	elseif($_POST['user_pass'] != $_POST['user_pass2'])
	  $error .= '<strong>错误</strong>：两次输入的密码不一致。<br />';

	  if($error == '') {
	  $user_id = wp_create_user( $sanitized_user_login, $_POST['user_pass'], $user_email );

	  if ( ! $user_id ) {
	    $error .= sprintf( '<strong>错误</strong>：无法完成注册请求... 请联系<a href=\"mailto:%s\">管理员</a>！<br />', get_option( 'admin_email' ) );
	  }
	  else if (!is_user_logged_in()) {
	    $user = get_userdatabylogin($sanitized_user_login);
	    $user_id = $user->ID;

	    // 自动登录
	    wp_set_current_user($user_id, $user_login);
	    wp_set_auth_cookie($user_id);
	    do_action('wp_login', $user_login);
	  }
	}
}
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if(akina_option('ex_register_open')) : ?>
		<?php if(!is_user_logged_in()){ ?>
			<div class="ex-register">
				<div class="ex-register-title">
					<h3>New Account</h3>
				</div>
				<form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">  
					<p><input type="text" name="user_login" tabindex="1" id="user_login" class="input" value="<?php if(!empty($sanitized_user_login)) echo $sanitized_user_login; ?>" placeholder="用户名" required /></p>
					<p><input type="text" name="user_email" tabindex="2" id="user_email" class="input" value="<?php if(!empty($user_email)) echo $user_email; ?>" size="25" placeholder="电子邮箱" required /></p>
					<p><input id="user_pwd1" class="input" tabindex="3" type="password" tabindex="21" size="25" value="" name="user_pass" placeholder="密码" required /></p>
					<p><input id="user_pwd2" class="input" tabindex="4" type="password" tabindex="21" size="25" value="" name="user_pass2" placeholder="确认密码" required /></p>
					<input type="hidden" name="register_reg" value="ok" />
					<?php if(!empty($error)) { echo '<p class="user-error">'.$error.'</p>'; } ?>
					<input class="button register-button" name="submit" type="submit" value="注 册">
				</form>
			</div>
		<?php }else{ ?>
			<script>window.location.href='<?php echo akina_option('exlogin_url') ?>';</script>
		<?php } ?>
		<?php else : ?>
			<div class="register-close"><p>暂未开放注册。</p></div>
		<?php endif; ?>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
