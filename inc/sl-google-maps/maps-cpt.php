<?php
/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Register a custom taxonomy and apply it to a private custom post type
* @since 0.1
*/

// Start custom post type sl_slider_block
function scale_lite_google_maps_custom_post_type() {

  $singular = 'Map';
  $plural   = 'Maps';

  $labels = array(
    'name'            => $plural,
    'singular_name'   => $singular,
    'add_name'        => 'Add new ',
    'add_new_item'    => 'Add New Google Map',
    'edit'            => 'Edit ',
    'edit_item'       => 'Edit ' . $singular,
    'new_item'        => 'New ' . $singular,
    'view'            => 'View ' . $singular,
    'view_item'       => 'View ' . $singular,
    'search_term'     => 'Search ' . $plural,
    'parent'          => 'Parent ' . $plural,
    'not_found'       => 'No ' . $plural . ' found',
    'not_found_in_trash' => 'No ' . $plural . 'in Trash'
  );

  $args = array(
    'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
    'rewrite' => false,  // it shouldn't have rewrite rules because it is not public
    'publicly_queriable' => true,  // you should be able to query it
    'show_ui' => true,  // you should be able to edit it in wp-admin
    'show_in_admin_bar' => true,
    'exclude_from_search' => true,  // you should exclude it from search results
    'show_in_nav_menus' => false,  // selectable in nav menus
    'show_in_menu' => 'slt_google_maps_config_menu',
    'has_archive' => false,  // it shouldn't have archive page
    'labels'            => $labels,
    'capabilty_type'    => 'post',
    'menu_icon'         => 'dashicons-location-alt',
    'map_meta_cap'      => 'true',
    'supports' => array( 'title','thumbnail'),
    'taxonomies' => array()
  );

  register_post_type('slt_gmap', $args);
}
add_action('init', 'scale_lite_google_maps_custom_post_type');
// End custom post type slt_gmaps
