<?php

function customizer_css() { ?>
<style type="text/css">
<?php // Style Settings
if ( akina_option('shownav') ) { ?>
.site-top .lower nav {display: block !important;}
<?php } // Style Settings ?>
<?php // theme-skin
if ( akina_option('theme_skin') ) { ?>
/* Site Color */
.author-profile i , .post-like a , .post-share .show-share , .sub-text , .we-info a , span.sitename , .post-more i:hover , #pagination a:hover{ color: <?php echo akina_option('theme_skin'); ?> }
.feature i , .feature-title span , .download , .navigator i:hover , .links ul li:before , .ar-time i , span.ar-circle , .object , .comment .comment-reply-link { background: <?php echo akina_option('theme_skin'); ?> }
::-webkit-scrollbar-thumb { background: <?php echo akina_option('theme_skin'); ?> }
.download , .navigator i:hover , .link-title , .links ul li:hover , #pagination a:hover { border-color: <?php echo akina_option('theme_skin'); ?> }
.entry-content a:hover , .site-info a:hover , .comment h4 a , #comments-navi a.prev , #comments-navi a.next , .comment h4 a:hover , .site-top ul li a:hover , .entry-title a:hover , #archives-temp h3 , span.page-numbers.current , .sorry li a:hover , .site-title a:hover , i.iconfont.js-toggle-search.iconsearch:hover{ color: <?php echo akina_option('theme_skin'); ?> }
<?php } // theme-skin ?>
<?php // Custom style
if ( akina_option('site_custom_style') ) {
  echo akina_option('site_custom_style');
} 
// Custom style end ?>
<?php // liststyle
if ( akina_option('list_type') == 'square') { ?>
.feature img{border-radius: 0px; !important;}
.feature i {border-radius: 0px; !important;}
<?php } // liststyle ?>
<?php // comments
if ( akina_option('toggle-menu') == 'no') { ?>
.comments .comments-main {display:block !important;}
.comments .comments-hidden {display:none !important;}
<?php } // comments ?>
</style>
<?php }
add_action('wp_head', 'customizer_css');

/*
*ajax点赞
*/
add_action('wp_ajax_nopriv_specs_zan', 'specs_zan');
add_action('wp_ajax_specs_zan', 'specs_zan');
function specs_zan(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'ding'){
        $specs_raters = get_post_meta($id,'specs_zan',true);
        $expire = time() + 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
        setcookie('specs_zan_'.$id,$id,$expire,'/',$domain,false);
        if (!$specs_raters || !is_numeric($specs_raters)) {
            update_post_meta($id, 'specs_zan', 1);
        } 
        else {
            update_post_meta($id, 'specs_zan', ($specs_raters + 1));
        }
        echo get_post_meta($id,'specs_zan',true);
    } 
    die;
}


function get_the_link_items($id = null){
  $bookmarks = get_bookmarks('orderby=date&category=' .$id );
  $output = '';
  if ( !empty($bookmarks) ) {
      $output .= '<ul class="link-items fontSmooth">';
      foreach ($bookmarks as $bookmark) {
          $output .=  '<li class="link-item"><a class="link-item-inner effect-apollo" href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" ><span class="sitename">'. $bookmark->link_name .'</span><div class="linkdes">'. $bookmark->link_description .'</div></a></li>';
      }
      $output .= '</ul>';
  }
  return $output;
}

function get_link_items(){
  $linkcats = get_terms( 'link_category' );
  if ( !empty($linkcats) ) {
      foreach( $linkcats as $linkcat){            
          $result .=  '<h3 class="link-title">'.$linkcat->name.'</h3>';
          if( $linkcat->description ) $result .= '<div class="link-description">' . $linkcat->description . '</div>';
          $result .=  get_the_link_items($linkcat->term_id);
      }
  } else {
      $result = get_the_link_items();
  }
  return $result;
}

function shortcode_link(){
  return get_link_items();
}
add_shortcode('bigfalink', 'shortcode_link');

//图片七牛云缓存
add_filter( 'upload_dir', 'wpjam_custom_upload_dir' );
function wpjam_custom_upload_dir( $uploads ) {
	$upload_path = '';
	$upload_url_path = akina_option('qiniu_cdn');

	if ( empty( $upload_path ) || 'wp-content/uploads' == $upload_path ) {
		$uploads['basedir']  = WP_CONTENT_DIR . '/uploads';
	} elseif ( 0 !== strpos( $upload_path, ABSPATH ) ) {
		$uploads['basedir'] = path_join( ABSPATH, $upload_path );
	} else {
		$uploads['basedir'] = $upload_path;
	}

	$uploads['path'] = $uploads['basedir'].$uploads['subdir'];

	if ( $upload_url_path ) {
		$uploads['baseurl'] = $upload_url_path;
		$uploads['url'] = $uploads['baseurl'].$uploads['subdir'];
	}
	return $uploads;
}

//删除自带小工具
function unregister_default_widgets() {
unregister_widget("WP_Widget_Pages");
unregister_widget("WP_Widget_Calendar");
unregister_widget("WP_Widget_Archives");
unregister_widget("WP_Widget_Links");
unregister_widget("WP_Widget_Meta");
unregister_widget("WP_Widget_Search");
unregister_widget("WP_Widget_Text");
unregister_widget("WP_Widget_Categories");
unregister_widget("WP_Widget_Recent_Posts");
unregister_widget("WP_Widget_Recent_Comments");
unregister_widget("WP_Widget_RSS");
unregister_widget("WP_Widget_Tag_Cloud");
unregister_widget("WP_Nav_Menu_Widget");
}
add_action("widgets_init", "unregister_default_widgets", 11);

  



