<?php
/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Register a custom taxonomy and apply it to a private custom post type
* @since 0.1
*/

// Start taxonomy
add_action( 'init', 'scale_lite_create_google_markers_taxonomy' );
function scale_lite_create_google_markers_taxonomy() {
  $labels = array(
    'name'                           => 'Markers',
    'singular_name'                  => 'Marker',
    'search_items'                   => 'Search markers',
    'all_items'                      => 'All markers',
    'edit_item'                      => 'Edit marker',
    'update_item'                    => 'Update marker',
    'add_new_item'                   => 'Add new marker category',
    'new_item_name'                  => 'New marker name',
    'menu_name'                      => 'marker category',
    'view_item'                      => 'View marker',
    'popular_items'                  => 'Popular marker',
    'separate_items_with_commas'     => 'Separate markers with commas',
    'add_or_remove_items'            => 'Add or remove markers',
    'choose_from_most_used'          => 'Choose from the most used markers',
    'not_found'                      => 'No markers found'
  );

  register_taxonomy(
    'slt_gmap_markers_category',
    'slt_gmap_marker', // apply to the custom post type slt_gmap_markers instead of post
    array(
      'labels' => $labels,
      'show_ui' => true,
      'hierarchical' => true,
      'public' => false,
      'rewrite' => false,
      'show_in_nav_menus' => false,
      'show_tagcloud' => false,
      'show_admin_column' => true
    )
  );

  // Add slt_gmap_markers_category taxonomy to slt_gmap_markers custom post type
  register_taxonomy_for_object_type( 'slt_gmap_markers_category','slt_gmap_marker');
}

// Start custom post type sl_slider_block
function scale_lite_google_markers_custom_post_type() {

  $singular = 'Marker';
  $plural   = 'Markers';

  $labels = array(
    'name'            => $plural,
    'singular_name'   => $singular,
    'add_name'        => 'Add new ',
    'add_new_item'    => 'Add New Google marker',
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
    'show_in_menu' => 'slt_google_maps_config_menu',
    'exclude_from_search' => true,  // you should exclude it from search results
    'show_in_nav_menus' => false,  // selectable in nav menus
    'has_archive' => false,  // it shouldn't have archive page
    'labels'            => $labels,
    'capabilty_type'    => 'post',
    'menu_icon'         => 'dashicons-location-alt',
    'marker_meta_cap'      => 'true',
    'supports' => array( 'title','thumbnail'),
    'taxonomies' => array( 'slt_gmap_markers_category') // Add here your custom taxonomy
  );

  register_post_type('slt_gmap_marker', $args);
}
add_action('init', 'scale_lite_google_markers_custom_post_type');
// End custom post type slt_gmap_markers

