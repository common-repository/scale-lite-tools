<?php
/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Register a custom taxonomy and apply it to a private custom post type
* @since 0.1
*/

// Register Google Maps callback function for the backend
function scale_lite_admin_google_maps() {
  global $scale_lite_tools, $scale_lite_root_path, $scale_lite_url_path;
  wp_enqueue_script(
    'scale-lite-google-maps-admin-js',
    $scale_lite_url_path.'/assets/js/admin.sl-maps.js',
    array(),
    '1.0.5',
    1
  );
}
add_action('admin_enqueue_scripts', 'scale_lite_admin_google_maps');

// Register Google maps JS v3 for the backend
function scale_lite_load_gmaps_v3() {
  global $scale_lite_url_path;
  wp_enqueue_script(
    'scale-lite-google-maps-js',
    "https://maps.googleapis.com/maps/api/js",
    array(),
    '',
    true
  );
  wp_enqueue_style(
    'scale_lite_google_maps_css',
    $scale_lite_url_path . 'assets/css/fronted.google-maps.css',
    array(),
    '1.0.5',
    'all'
  );
}
add_action('admin_enqueue_scripts', 'scale_lite_load_gmaps_v3', 50 );

// Register Google Maps callback function for the frontend
function scale_lite_load_sl_maps_js() {
  global $scale_lite_tools, $scale_lite_root_path, $scale_lite_url_path;
  wp_enqueue_script('sl-google-maps-frontend',
    $scale_lite_url_path.'assets/js/frontend.sl-google-maps.js',
    array(),
    '1.0.5',
    true
  );
}

// store all the markers in an array
$markersArray = array();
function scale_lite_build_markers_array() {
  $args = array(
    // Fetch all markers
    'post_type' => 'slt_gmap_marker'
  );
  $query = new WP_Query( $args );

  if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
    // post meta
    $marker_lat = get_post_meta(get_the_ID(),'slt_gmarker_slt_meta-latitude',true);
    $marker_lng = get_post_meta(get_the_ID(),'slt_gmarker_slt_meta-longitude',true);
    $title = get_post_meta(get_the_ID(),'slt_gmarker_slt_meta-title',true);
    $description = get_post_meta(get_the_ID(),'slt_gmarker_slt_meta-description',true);
    $icon = get_post_meta(get_the_ID(),'slt_gmarker_slt_meta-icon',true);
    $icon_color = get_post_meta(get_the_ID(),'slt_gmarker_slt_marker-icon-color',true);

    global $markersArray;
    $markersArray[get_the_ID()] = array(
      'marker_lat'=>$marker_lat,
      'marker_lng'=>$marker_lng,
      'title'=>$title,
      'description'=>$description,
      'icon'=>$icon,
      'icon_color'=>$icon_color
    );
    endwhile; wp_reset_postdata(); // Restore original post data.
  else:
    echo 'No marker foundd!';
  endif;
}

// Save all markers in JS var and pass it to sl-google-maps-frontend
function register_markers_array() {
  global $markersArray;
  wp_localize_script('sl-google-maps-frontend', 'scaleLiteMarkersBuffer',$markersArray);
}

// add async and defer attributs to google maps script google maps JS
add_filter('clean_url','unclean_url',10,3);
function unclean_url( $good_protocol_url, $original_url, $_context){
  $slt_gmaps_settings_options = get_option( 'slt_gmaps_settings_option_name' ); // Array of All Options
  $google_maps_api_key_0 = $slt_gmaps_settings_options['google_maps_api_key_0']; // Google Maps API Key
  if (false !== strpos($original_url, 'maps.googleapis.com/maps/api/js')){
    remove_filter('clean_url','unclean_url',10,3);
    return wp_specialchars_decode($good_protocol_url).
    '?key='.$google_maps_api_key_0."&callback=slt_gmaps_init' async='async' defer='defer";
  }
  return $good_protocol_url;
}


// Enque scripts only if shortcode is used
function scale_lite_load_maps_if_shortcode() {
  global $post;
  if(true) { // has_shortcode($post->post_content,'sl_gmaps')
    // here you need to loop also through sidebars content and find if there is a ahortcode
    // see wp_get_sidebars_widgets
    // execute code only if there is a sl_gmaps shortcode on the page
    add_action('wp_enqueue_scripts', 'scale_lite_build_markers_array', 9 );
    add_action('wp_enqueue_scripts', 'register_markers_array', 10 );
    add_action('wp_enqueue_scripts', 'scale_lite_load_gmaps_v3', 50 );
    add_action('wp_enqueue_scripts', 'scale_lite_load_sl_maps_js', 9 );
  }
}
add_action('wp_enqueue_scripts','scale_lite_load_maps_if_shortcode',0);


// store all the maps data
$mapsArray = array();

/**
 * SL Google Maps shortcode
 * [sl_gmaps post-id="10"]
 */

function scale_lite_google_maps_shortcode($atts = array(), $content = null, $tag = ''){
  // normalize attribute keys, lowercase
  $sl_attrs = array_change_key_case((array)$atts, CASE_LOWER);
  if(!array_key_exists("post-id",$sl_attrs) || $sl_attrs['post-id'] == null) {
    return;
  }

  // output buffer start
  ob_start();

  $args = array(
    // Select maps that are assigned to category slug my-locations
    'p' => $sl_attrs['post-id'],
    'post_type' => 'slt_gmap'
  );
  $query = new WP_Query( $args );

  // start output
  $o = '';
  if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
    // post meta
    $map_latitude = get_post_meta(get_the_ID(),'slt_gmap_slt_meta-latitude',true);
    $map_longitude = get_post_meta(get_the_ID(),'slt_gmap_slt_meta-longitude',true);
    $map_markers = explode(',', get_post_meta(get_the_ID(),'slt_gmap_slt_meta-markers',true));
    $html_id = get_post_meta(get_the_ID(),'slt_gmap_slt_meta-html-id',true);
    $width = get_post_meta(get_the_ID(),'slt_gmap_slt_meta-width',true);
    $height = get_post_meta(get_the_ID(),'slt_gmap_slt_meta-height',true);
    $map_type = get_post_meta(get_the_ID(),'slt_gmap_slt_meta-map-type',true);
    $zoom = get_post_meta(get_the_ID(),'slt_gmap_slt_meta-zoom',true);
    $show_info_titles = get_post_meta(get_the_ID(),'slt_gmap_slt_meta-show-marker-info-box',true);
    $kml_files = get_post_meta(get_the_ID(),'slt_gmap_slt_meta-kml-file',true);

    global $mapsArray;
    $mapsArray += array(
        get_the_ID() => array(
        'map_latitude'=>$map_latitude,
        'map_longitude'=>$map_longitude,
        'map_markers'=>$map_markers,
        'html_id'=>$html_id,
        'width'=>$width,
        'height'=>$height,
        'map_type'=>$map_type,
        'zoom'=>$zoom,
        'show_info_titles'=>$show_info_titles,
        'kml_files'=>$kml_files
      )
    );

    // register javascript var scaleLiteMarkerVars that holds the markers
    wp_localize_script('sl-google-maps-frontend', 'scaleLiteMapsBuffer',
      $mapsArray
    );
    // push to markers array
    // marker output
    $o .= '<div id="'.$html_id.'">';
    $o .= '</div>';
    endwhile; wp_reset_postdata(); // Restore original post data.
  endif;
  $o .= '</div>';
  echo $o;
  $o = ob_get_contents();
  ob_end_clean();
  return $o;
}
// Register google_maps shortcode
add_shortcode('sl_gmaps', 'scale_lite_google_maps_shortcode');
