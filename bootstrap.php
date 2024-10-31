<?php

/**
* @author : Ionut Morariu moraryou@gmail.com
* @date : 11.01.2017
* @description : Bootstrap file for loading modules
* @since 0.1
*/

define('SCALE_LITE_TOOLS_VERSION','1.0.9.1');

// scale lite global variables
$scale_lite_tools = array();
$scale_lite_root_path = $scale_lite_tools['folder_root_path'] = plugin_dir_path(__FILE__);
$scale_lite_url_path = $scale_lite_tools['url_root_path'] = plugin_dir_url( __FILE__ );

// Add settings link to settings page in view installed plugins
function scale_lite_settings_action_link( $links ) {
  global $scale_lite_tools, $scale_lite_root_path, $scale_lite_url_path;
  $links = array_merge( $links, array(
    '<a href="' . esc_url( admin_url( '/options-general.php?page=scale-lite-tools-options' ) ) . '">' . __( 'Settings', 'scale_lite' ) . '</a>'
  ));
  return $links;
}
add_action('plugin_action_links_scale-lite-tools/scale-lite-tools.php','scale_lite_settings_action_link' );

// Load Scale Lite CSS within Admin
if (is_admin()):
  function scale_lite_global_admin_css() {
    global $scale_lite_tools, $scale_lite_root_path, $scale_lite_url_path;
    wp_enqueue_style(
      'sl-tools-golbal-admin-css',
      $scale_lite_url_path. '/assets/css/admin.sl-dasboard.css',
      array(),
      '1.0.5',
      'all'
    );
  }
  add_action('admin_enqueue_scripts', 'scale_lite_global_admin_css');
endif;

// import dashboard settings
require_once( plugin_dir_path(__FILE__) . 'inc/sl-main-dashboard/main.php' );

// get stored settings values
if (is_array(get_option('scale_lite_tools_options_option_name'))):
  $scale_lite_tools_options_options = get_option('scale_lite_tools_options_option_name'); // Array of All Options
else:
  $scale_lite_tools_options_options = array();
endif;

if ($scale_lite_tools_options_options == null):
  return;
endif;

// retrieve option value from settings array to store it later in a var
function scale_lite_check_dashboard_option($option) {
  global $scale_lite_tools_options_options;
  if (is_array($scale_lite_tools_options_options) &&
    array_key_exists($option,$scale_lite_tools_options_options)):
    $scale_lite_tools_options_options[$option] = $option;
  else: $option = false;
  endif;
  return $option;
}

// get stored settings options - checked/unchecked
$scale_lite_api_key_0 = scale_lite_check_dashboard_option("scale_lite_api_key_0");
$enable_html_blocks_1 = scale_lite_check_dashboard_option("enable_html_blocks_1");
$enable_gallery_manager_2 = scale_lite_check_dashboard_option("enable_gallery_manager_2");
$enable_google_maps_v3_4 = scale_lite_check_dashboard_option("enable_google_maps_v3_4");
$enable_social_share_5 = scale_lite_check_dashboard_option("enable_social_share_5");
$enable_layout_tools_9 = scale_lite_check_dashboard_option("enable_layout_tools_9");
$enable_debug_tools_12 = scale_lite_check_dashboard_option("enable_debug_tools_12");

// HTML Block module
if($enable_html_blocks_1) {
  require_once( $scale_lite_root_path . 'inc/sl-html-block/cpt.php' );
  require_once( $scale_lite_root_path . 'inc/sl-html-block/Class_Html_Block_Meta_Fields.php' );
  require_once( $scale_lite_root_path . 'inc/sl-html-block/widget.php' );
  function scale_lite_sl_block_js() {
    global $scale_lite_tools, $scale_lite_root_path, $scale_lite_url_path;
    wp_enqueue_script( 'scale-lite-lsl-block-js', $scale_lite_url_path. '/assets/js/admin.sl-block.js', array('jquery'), '1.0.5', 1 );
  }
  add_action('admin_enqueue_scripts', 'scale_lite_sl_block_js');
}

// Gallery manager module enabled
if($enable_gallery_manager_2){
  function scale_lite_enqueue_gallery_manager_scripts() {
    global $scale_lite_tools, $scale_lite_root_path, $scale_lite_url_path;
    wp_enqueue_style( 'scale-lite-baguetteBox-css',  $scale_lite_url_path . 'assets/css/frontend.baguetteBox.css', array(),'1.0.5', 'all' );
    wp_enqueue_style( 'scale-lite-baguetteBox-custom-css',  $scale_lite_url_path . 'assets/css/frontend.baguetteBox-custom.css', array(),'1.0.5', 'all' );
    wp_enqueue_script( 'scale-lite-gallery-manager-js', $scale_lite_url_path . 'assets/js/frontend.baguetteBox.js', array('jquery'), '1.0.5', false );
  }
  add_action('wp_enqueue_scripts', 'scale_lite_enqueue_gallery_manager_scripts', 1);
}

// Google Maps module
if($enable_google_maps_v3_4) {
  require_once( $scale_lite_root_path . 'inc/sl-google-maps/maps-cpt.php' );
  require_once( $scale_lite_root_path . 'inc/sl-google-maps/markers-cpt.php' );
  require_once( $scale_lite_root_path . 'inc/sl-google-maps/render-cpt.php' );
  require_once( $scale_lite_root_path . 'inc/sl-google-maps/admin-menu.php' );
  require_once( $scale_lite_root_path . 'inc/sl-google-maps/class-sl-maps-sub-menu.php' );
  require_once( $scale_lite_root_path . 'inc/sl-google-maps/class-slt-gmap-meta-fields.php' );
  require_once( $scale_lite_root_path . 'inc/sl-google-maps/class-slt-gmap-markers-meta-fields.php' );
}
// Layout tools manager module enabled
if($enable_layout_tools_9){
  function scale_lite_enqueue_layout_scripts() {
    global $scale_lite_tools, $scale_lite_root_path, $scale_lite_url_path;
    wp_enqueue_script( 'scale-lite-layout-js', $scale_lite_url_path . 'assets/js/v2.1.8.vue.min.js', array('jquery'), '1.0.5', false );
  }
  add_action('wp_enqueue_scripts', 'scale_lite_enqueue_layout_scripts', 1);
}

// Debug module
if($enable_debug_tools_12){
  require_once( $scale_lite_root_path . 'inc/sl-debug/debug.php' );
  function scale_lite_enqueue_debug_scripts() {
    global $scale_lite_tools, $scale_lite_root_path, $scale_lite_url_path;
    wp_enqueue_script( 'scale-lite-debug-js', $scale_lite_url_path . 'assets/js/frontend.debug-tools.js', array('jquery'), '1.0.5', false );
    wp_enqueue_style( 'sl-tools-debug-css',  $scale_lite_url_path. '/assets/css/frontend.scale-debug.css', array(),'1.0.5', 'all' );
  }
  add_action('wp_enqueue_scripts', 'scale_lite_enqueue_debug_scripts', 1);
}

// import helper objects
require_once( $scale_lite_root_path. 'inc/helpers/sl-reveal-category-id.php' );
require_once( $scale_lite_root_path. 'inc/helpers/sl-add-custom-columns.php' );
// import global shortcodes
require_once( $scale_lite_root_path . 'inc/shortcode-tools/shortcodes.php' );

// Remove version number from scripts and styles that do not specify it
// Google maps api key call depends on this
// Remove Wordpress version number
function sl_remove_version_from_style_js( $src ) {
  if (strpos( $src, 'ver=' . get_bloginfo('version')))
    $src = remove_query_arg( 'ver', $src );
  return $src;
}
add_filter( 'style_loader_src', 'sl_remove_version_from_style_js');
add_filter( 'script_loader_src', 'sl_remove_version_from_style_js');
// Remove version number from RSS feed
function sl_remove_version_generator() {return '';}
add_filter('the_generator', 'sl_remove_version_generator');
// End of remove wordpress version


