<?php
/**
 * Custom function
*/

// 允许分类、标签描述添加html代码
remove_filter('pre_term_description', 'wp_filter_kses');
remove_filter('term_description', 'wp_kses_data');
// 去除顶部工具栏
show_admin_bar(false);


/*
 * 视频
 */
function bgvideo(){
  if(!akina_option('focus_amv') || akina_option('focus_height')){$dis = 'display:none;';}
  $html = '<div id="video-container" style="'.$dis.'">'; 
  $html .= '<video id="bgvideo" class="video" video-name="" src="" width="auto" preload="auto"></video>';
  $html .= '<div id="video-btn" class="loadvideo videolive"></div>';
  $html .= '<div id="video-add"></div>';
  $html .= '<div class="video-stu"></div>';
  $html .= '</div>';
  return $html;
}


/*
 * 使用本地图片作为头像，防止外源抽风问题
 */
function get_avatar_profile_url(){ 
  if(akina_option('focus_logo')){
    $avatar = akina_option('focus_logo');
  }else{
    $avatar = get_avatar_url(get_the_author_meta( 'ID' ));
  }
  return $avatar;
}


/*
 * 获取每日必应图片
 */
function bingimage(){
  $str = curl_init();
  curl_setopt($str, CURLOPT_URL, 'http://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1');
  curl_setopt($str, CURLOPT_RETURNTRANSFER, 1);
  $re = curl_exec($str);
  $re = json_decode($re,1);
  return ''.$re['images'][0]['url'].'';
}


/*
 * 订制时间样式
 * poi_time_since(strtotime($post->post_date_gmt));
 * poi_time_since(strtotime($comment->comment_date_gmt), true );
 */
function poi_time_since( $older_date, $comment_date = false, $text = false ) {
  $chunks = array(
    array( 24 * 60 * 60, __( ' 天前', 'akina' ) ),
    array( 60 * 60, __( ' 小时前', 'akina' ) ),
    array( 60, __( ' 分钟前', 'akina' ) ),
    array( 1, __( ' 秒前', 'akina' ) )
  );

  $newer_date = time();
  $since = abs( $newer_date - $older_date );
  if($text){
    $output = '';
  }else{
    $output = '发布于 ';
  }

  if ( $since < 30 * 24 * 60 * 60 ) {
    for ( $i = 0, $j = count( $chunks ); $i < $j; $i ++ ) {
      $seconds = $chunks[ $i ][0];
      $name    = $chunks[ $i ][1];
      if ( ( $count = floor( $since / $seconds ) ) != 0 ) {
        break;
      }
    }
    $output .= $count . $name;
  } else {
    $output .= $comment_date ? date( 'Y-m-d H:i', $older_date ) : date( 'Y-m-d', $older_date );
  }

  return $output;
}


/*
 * 首页不显示指定的分类文章
 */
if(akina_option('classify_display')){

  function classify_display($query){
    $source = akina_option('classify_display');
    $cats = explode(',', $source);
    $cat = '';
    if ( $query->is_home ) {
      foreach($cats as $k => $v) {
        $cat .= '-'.$v.','; //重组字符串
      }
      $cat = trim($cat,',');
      $query->set( 'cat', $cat);
    }
    return $query;
  }
  add_filter( 'pre_get_posts', 'classify_display' ); 
  
}


/*
 * 评论添加@
 */
function comment_add_at( $comment_text, $comment = '') {
  if( $comment->comment_parent > 0) {
    $comment_text = '<a href="#comment-' . $comment->comment_parent . '" class="comment-at">@'.get_comment_author( $comment->comment_parent ) . '</a><br/> ' . $comment_text;
  }
  return $comment_text;
}
add_filter( 'comment_text' , 'comment_add_at', 20, 2);


/*
 * Ajax评论
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) { wp_die('请升级到4.4以上版本'); }
if(!function_exists('fa_ajax_comment_err')) {
    function fa_ajax_comment_err($a) {
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: text/plain;charset=UTF-8');
        echo $a;
        exit;
    }
}
if(!function_exists('fa_ajax_comment_callback')) {

    function fa_ajax_comment_callback(){
        $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
        if ( is_wp_error( $comment ) ) {
            $data = $comment->get_error_data();
            if ( ! empty( $data ) ) {
              fa_ajax_comment_err($comment->get_error_message());
            } else {
                exit;
            }
        }
        $user = wp_get_current_user();
        do_action('set_comment_cookies', $comment, $user);
        $GLOBALS['comment'] = $comment; //根据你的评论结构自行修改，如使用默认主题则无需修改
        ?>
        <li <?php comment_class(); ?> id="comment-<?php echo esc_attr(comment_ID()); ?>">
           <div class="contents">
        <div class="comment-arrow">
          <div class="main shadow">
              <div class="profile">
                  <a href="<?php comment_author_url(); ?>"><?php echo get_avatar( $comment->comment_author_email, '80', '', get_comment_author() ); ?></a>
              </div>
              <div class="commentinfo">
                <section class="commeta">
                  <div class="left">
                    <h4 class="author"><a href="<?php comment_author_url(); ?>"><?php echo get_avatar( $comment->comment_author_email, '80', '', get_comment_author() ); ?><?php comment_author(); ?> <span class="isauthor" title="<?php esc_attr_e('Author', 'akina'); ?>"></span></a></h4>
                  </div>
                  <div class="right">
                    <div class="info"><time datetime="<?php comment_date('Y-m-d'); ?>"><?php echo poi_time_since(strtotime($comment->comment_date_gmt), true );//comment_date(get_option('date_format')); ?></time></div>
                  </div>
                </section>
              </div>
              <div class="body">
                <?php comment_text(); ?>
              </div>
          </div>
          <div class="arrow-left"></div>
        </div>
      </div>
        </li>
        <?php die();
    }

}
add_action('wp_ajax_nopriv_ajax_comment', 'fa_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'fa_ajax_comment_callback');


/*
 * 前台登陆
 */
// 指定登录页面
if(akina_option('exlogin_url')){
  add_action('login_enqueue_scripts','login_protection');
  function login_protection(){
    if($_GET['word'] != 'press'){
    $admin_url = akina_option('exlogin_url');
    wp_redirect( $admin_url );
    exit;
    }
  }
}

// 登陆跳转
function Exuser_center(){ ?>
  <script language='javascript' type='text/javascript'> 
    var secs = 5; //倒计时的秒数 
    var URL;
    var TYPE; 
    function gopage(url,type){ 
        URL = url; 
        if(type == 1){
          TYPE = '管理后台';
        }else{
          TYPE = '主页';
        }
        for(var i=secs;i>=0;i--){ 
            window.setTimeout('doUpdate(' + i + ')', (secs-i) * 1000); 
        } 
    } 
    function doUpdate(num){ 
        document.getElementById('login-showtime').innerHTML = '空降成功，'+num+'秒后自动转到'+TYPE; 
        if(num == 0) { window.location=URL; } 
    } 
  </script>    
  <?php if(current_user_can('level_10')){ ?>
  <div class="admin-login-check">
    <?php echo login_ok(); ?>
    <?php if(akina_option('login_urlskip')){ ?><script>gopage("<?php bloginfo('url'); ?>/wp-admin/",1);</script><?php } ?>
  </div>
  <?php }else{ ?>
  <div class="user-login-check">
    <?php echo login_ok(); ?>
    <?php if(akina_option('login_urlskip')){ ?><script>gopage("<?php bloginfo('url'); ?>",0);</script><?php } ?>
  </div>
<?php 
  }
}

// 登录成功
function login_ok(){ 
  global $current_user;
  get_currentuserinfo();
?>
  <p class="ex-login-avatar"><a href="http://cn.gravatar.com/" title="更换头像" target="_blank" rel="nofollow"><?php echo get_avatar( $current_user->user_email, '110' ); ?></a></p>
  <p class="ex-login-username">你好，<strong><?php echo $current_user->display_name; ?></strong></p>
  <?php if($current_user->user_email){echo '<p>'.$current_user->user_email.'</p>';} ?>
  <p id="login-showtime"></p>
  <p class="ex-logout">
    <a href="<?php bloginfo('url'); ?>" title="首页">首页</a>
    <?php if(current_user_can('level_10')){  ?>
    <a href="<?php bloginfo('url'); ?>/wp-admin/" title="后台" target="_top">后台</a> 
    <?php } ?>
    <a href="<?php echo wp_logout_url(get_bloginfo('url')); ?>" title="登出" target="_top">登出？</a>
  </p>
<?php 
}


/*
 * 文章，页面头部背景图
 */
function headPattern(){
  $t = ''; // 标题
  $full_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
  if(is_single()){
    $full_image_url = $full_image_url[0];
    if (have_posts()) : while (have_posts()) : the_post();
    $center = 'single-center';
    $header = 'single-header';
    $ava = akina_option('focus_logo', '') ? akina_option('focus_logo', '') : get_avatar_url(get_the_author_meta('user_email'));
    $t .= the_title( '<h1 class="entry-title">', '</h1>', false);
    $t .= '<p class="entry-census"><span><a href="'. get_author_posts_url(get_the_author_meta('ID')) .'"><img src="'. $ava .'"></a></span><span><a href="'. get_author_posts_url(get_the_author_meta('ID')) .'">'. get_the_author() .'</a></span><span class="bull">·</span>'. poi_time_since(get_post_time('U', true),false,true) .'<span class="bull">·</span>'. get_post_views(get_the_ID()) .' 次阅读</p>';
    endwhile; endif;
  }elseif(is_page()){
    $full_image_url = $full_image_url[0];
    $t .= the_title( '<h1 class="entry-title">', '</h1>', false);
  }elseif(is_archive()){
    $full_image_url = z_taxonomy_image_url();
    $des = category_description() ? category_description() : ''; // 描述
    $t .= '<h1 class="cat-title">'.single_cat_title('', false).'</h1>';
    $t .= ' <span class="cat-des">'.$des.'</span>';
  }elseif(is_search()){
    $full_image_url = akina_option('focus_img');
    $t .= '<h1 class="entry-title search-title"> 关于“ '.get_search_query().' ”的搜索结果</h1>';
  }
  if(akina_option('patternimg')) $full_image_url = false;
  if(!is_home() && $full_image_url) : ?>
  <div class="pattern-center <?php if(is_single()){echo $center;} ?>">
    <div class="pattern-attachment-img" style="background-image: url(<?php echo $full_image_url; ?>)"> </div>
    <header class="pattern-header <?php if(is_single()){echo $header;} ?>"><?php echo $t; ?></header>
  </div>
  <?php else :
    echo '<div class="blank"></div>';
  endif;
  
}


/*
 * 导航栏用户菜单
 */
function header_user_menu(){
  global $current_user;get_currentuserinfo(); 
  if(is_user_logged_in()){
    $ava = akina_option('focus_logo') ? akina_option('focus_logo') : get_avatar_url( $current_user->user_email );
    ?>
    <div class="header-user-avatar">
      <img src="<?php echo $ava; ?>" width="30" height="30">
      <div class="header-user-menu">
        <div class="herder-user-name">Signed in as 
          <div class="herder-user-name-u"><?php echo $current_user->display_name; ?></div>
        </div>
        <div class="user-menu-option">
          <?php if (current_user_can('level_10')) { ?>
            <a href="<?php bloginfo('url'); ?>/wp-admin/" target="_top">管理中心</a>
            <a href="<?php bloginfo('url'); ?>/wp-admin/post-new.php" target="_top">撰写文章</a>
          <?php } ?>
          <a href="<?php bloginfo('url'); ?>/wp-admin/profile.php" target="_top">个人资料</a>
          <a href="<?php echo wp_logout_url(get_bloginfo('url')); ?>" target="_top">退出登录</a>
        </div>
      </div>
    </div>
  <?php
  }else{ 
    $ava = get_template_directory_uri().'/images/none.png';
    $login_url = akina_option('exlogin_url') ? akina_option('exlogin_url') : get_bloginfo('url').'/wp-login.php';
  ?>
  <div class="header-user-avatar">
    <a href="<?php echo $login_url; ?>">
      <img src="<?php echo $ava; ?>" width="30" height="30">
    </a>
    <div class="header-user-menu">
      <div class="herder-user-name no-logged">Whether to log in now ?
        <a href="<?php echo $login_url; ?>">Sign in</a>
      </div>
    </div>
  </div>
  <?php 
  }
}


/*
 * 获取相邻文章缩略图
 * 特色图 -> 文章图 -> 首页图
 */
// 上一篇
function get_prev_thumbnail_url() { 
  $prev_post = get_previous_post(); 
  if ( has_post_thumbnail($prev_post->ID) ) { 
    $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $prev_post->ID ), 'large'); 
    return $img_src[0]; // 特色图
  } 
  else { 
    $content = $prev_post->post_content; 
    preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER); 
    $n = count($strResult[1]); 
    if($n > 0){ 
      return $strResult[1][0];  // 文章图
    }else{
      return akina_option('focus_img'); // 首页图
    } 
  } 
}

// 下一篇
function get_next_thumbnail_url() { 
  $next_post = get_next_post(); 
  if ( has_post_thumbnail($next_post->ID) ) { 
    $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $next_post->ID ), 'large'); 
    return $img_src[0]; 
  } 
  else { 
    $content = $next_post->post_content; 
    preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER); 
    $n = count($strResult[1]); 
    if($n > 0){ 
      return $strResult[1][0];   
    }else{
      return akina_option('focus_img');
    } 
  } 
}


/*
 * SEO优化
 */
// 外部链接自动加nofollow
add_filter( 'the_content', 'l_auto_link_nofollow');
function l_auto_link_nofollow( $content ) {
  $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
  if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
    if( !empty($matches) ) {
      $srcUrl = get_option('siteurl');
      for ($i=0; $i < count($matches); $i++){
        $tag = $matches[$i][0];
        $tag2 = $matches[$i][0];
        $url = $matches[$i][0];
        $noFollow = '';
        $pattern = '/target\s*=\s*"\s*_blank\s*"/';
        preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
        if( count($match) < 1 )
            $noFollow .= ' target="_blank" ';
        $pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
        preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
        if( count($match) < 1 )
            $noFollow .= ' rel="nofollow" ';
        $pos = strpos($url,$srcUrl);
        if ($pos === false) {
            $tag = rtrim ($tag,'>');
            $tag .= $noFollow.'>';
            $content = str_replace($tag2,$tag,$content);
        }
      }
    }
  }
   
  $content = str_replace(']]>', ']]>', $content);
  return $content;
}

// 图片自动加标题
add_filter('the_content', 'l_auto_images_alt');
function l_auto_images_alt($content) {
  global $post;
  $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
  $replacement = '<a$1href=$2$3.$4$5 alt="'.$post->post_title.'" title="'.$post->post_title.'"$6>';
  $content = preg_replace($pattern, $replacement, $content);
  return $content;
}

// 分类页面全部添加斜杠，利于SEO
function nice_trailingslashit($string, $type_of_url) {
    if ( $type_of_url != 'single' )
      $string = trailingslashit($string);
    return $string;
}
add_filter('user_trailingslashit', 'nice_trailingslashit', 10, 2);


/*
 * 去除链接显示categroy
 */
add_action( 'load-themes.php',  'no_category_base_refresh_rules');
add_action('created_category', 'no_category_base_refresh_rules');
add_action('edited_category', 'no_category_base_refresh_rules');
add_action('delete_category', 'no_category_base_refresh_rules');
function no_category_base_refresh_rules() {
  global $wp_rewrite;
  $wp_rewrite -> flush_rules();
}
 
// Remove category base
add_action('init', 'no_category_base_permastruct');
function no_category_base_permastruct() {
  global $wp_rewrite, $wp_version;
  if (version_compare($wp_version, '3.4', '<')) {   } else {           $wp_rewrite -> extra_permastructs['category']['struct'] = '%category%';
  }
}
// Add our custom category rewrite rules
add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
function no_category_base_rewrite_rules($category_rewrite) {
  //var_dump($category_rewrite); // For Debugging
  $category_rewrite = array();
  $categories = get_categories(array('hide_empty' => false));
  foreach ($categories as $category) {
    $category_nicename = $category -> slug;
    if ($category -> parent == $category -> cat_ID)// recursive recursion
      $category -> parent = 0;
    elseif ($category -> parent != 0)
      $category_nicename = get_category_parents($category -> parent, false, '/', true) . $category_nicename;
    $category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
    $category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
    $category_rewrite['(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
  }
  // Redirect support from Old Category Base
  global $wp_rewrite;
  $old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
  $old_category_base = trim($old_category_base, '/');
  $category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';
 
  //var_dump($category_rewrite); // For Debugging
  return $category_rewrite;
}
 
// Add 'category_redirect' query variable
add_filter('query_vars', 'no_category_base_query_vars');
function no_category_base_query_vars($public_query_vars) {
  $public_query_vars[] = 'category_redirect';
  return $public_query_vars;
}
 
// Redirect if 'category_redirect' is set
add_filter('request', 'no_category_base_request');
function no_category_base_request($query_vars) {
  //print_r($query_vars); // For Debugging
  if (isset($query_vars['category_redirect'])) {
    $catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
    status_header(301);
    header("Location: $catlink");
    exit();
  }
  return $query_vars;
}


/**
 * 更改作者页链接为昵称显示
 */
// Replace the user name using the nickname, query by user ID
add_filter( 'request', 'siren_request' );
function siren_request( $query_vars ){
    if ( array_key_exists( 'author_name', $query_vars ) ) {
        global $wpdb;
        $author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='nickname' AND meta_value = %s", $query_vars['author_name'] ) );
        if ( $author_id ) {
            $query_vars['author'] = $author_id;
            unset( $query_vars['author_name'] );    
        }
    }
    return $query_vars;
}
 
// Replace a user name in a link with a nickname
add_filter( 'author_link', 'siren_author_link', 10, 3 );
function siren_author_link( $link, $author_id, $author_nicename ){
    $author_nickname = get_user_meta( $author_id, 'nickname', true );
    if ( $author_nickname ) {
        $link = str_replace( $author_nicename, $author_nickname, $link );
    }
    return $link;
}


/*
 * 私密评论
 * @bigfa
 */
function siren_private_message_hook($comment_content , $comment){
    $comment_ID = $comment->comment_ID;
    $parent_ID = $comment->comment_parent;
    $parent_email = get_comment_author_email($parent_ID);
    $is_private = get_comment_meta($comment_ID,'_private',true);
    $email = $comment->comment_author_email;
    $current_commenter = wp_get_current_commenter();
    if ( $is_private ) $comment_content = '#私密# ' . $comment_content;
    if ( $current_commenter['comment_author_email'] == $email || $parent_email == $current_commenter['comment_author_email'] || current_user_can('delete_user') ) return $comment_content;
    if ( $is_private ) return '该评论为私密评论';
    return $comment_content;
}
add_filter('get_comment_text','siren_private_message_hook',10,2);

function siren_mark_private_message($comment_id){
    if ( $_POST['is-private'] ) {
        update_comment_meta($comment_id,'_private','true');
    }
}
add_action('comment_post', 'siren_mark_private_message');


/*
 * 删除后台某些版权和链接
 * @wpdx
 */
add_filter('admin_title', 'wpdx_custom_admin_title', 10, 2);
function wpdx_custom_admin_title($admin_title, $title){
    return $title.' &lsaquo; '.get_bloginfo('name');
}
//去掉Wordpress LOGO
function remove_logo($wp_toolbar) {
    $wp_toolbar->remove_node('wp-logo');
}
add_action('admin_bar_menu', 'remove_logo', 999);

//去掉Wordpress 底部版权
function change_footer_admin () {return '';}  
add_filter('admin_footer_text', 'change_footer_admin', 9999);  
function change_footer_version() {return '';}  
add_filter( 'update_footer', 'change_footer_version', 9999);

//去掉Wordpres挂件
function disable_dashboard_widgets() {   
    //remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');//近期评论 
    //remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal');//近期草稿
    remove_meta_box('dashboard_primary', 'dashboard', 'core');//wordpress博客  
    remove_meta_box('dashboard_secondary', 'dashboard', 'core');//wordpress其它新闻  
    remove_meta_box('dashboard_right_now', 'dashboard', 'core');//wordpress概况  
    //remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');//wordresss链入链接  
    //remove_meta_box('dashboard_plugins', 'dashboard', 'core');//wordpress链入插件  
    //remove_meta_box('dashboard_quick_press', 'dashboard', 'core');//wordpress快速发布   
}  
add_action('admin_menu', 'disable_dashboard_widgets');