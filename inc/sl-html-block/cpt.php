<?php
/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Register a custom taxonomy and apply it to a private custom post type
* @since 0.1
*/

// Start taxonomy
add_action( 'init', 'scale_lite_create_block_taxonomy' );
function scale_lite_create_block_taxonomy() {
  $labels = array(
    'name'                           => 'Block sidebar',
    'singular_name'                  => 'Block',
    'search_items'                   => 'Search blocks',
    'all_items'                      => 'All blocks',
    'edit_item'                      => 'Edit block',
    'update_item'                    => 'Update block',
    'add_new_item'                   => 'Add new block category',
    'new_item_name'                  => 'New block name',
    'menu_name'                      => 'Block category',
    'view_item'                      => 'View block',
    'popular_items'                  => 'Popular block',
    'separate_items_with_commas'     => 'Separate blocks with commas',
    'add_or_remove_items'            => 'Add or remove blocks',
    'choose_from_most_used'          => 'Choose from the most used blocks',
    'not_found'                      => 'No blocks found'
  );

  register_taxonomy(
    'sl_html_block_category',
    'sl_html_block', // apply to the custom post type sl_cblock instead of post
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

  // Add sl_html_block_category taxonomy to sl_cblock custom post type
  register_taxonomy_for_object_type( 'sl_html_block_category','sl_html_block');
}

// Start custom post type sl_cblock
function scale_lite_html_block_post_type() {

  $singular = 'HTML Block';
  $plural   = 'HTML Blocks';

  $labels = array(
    'name'            => 'HTML Blocks',
    'singular_name'   => $singular,
    'add_name'        => 'Add new ',
    'add_new_item'    => 'Add New ' . $singular,
    'edit'            => 'Edit',
    'edit_item'       => 'Edit ' . $singular,
    'new_item'        => 'New ' . $singular,
    'view'            => 'View ' . $singular,
    'view_item'       => 'View ' . $singular,
    'search_term'     => 'Search ' . $plural,
    'parent'          => 'Parent ' . $plural,
    'not_found'       => 'No ' . $plural . ' found',
    'not_found_in_trash' => 'No ' . $plural . ' in Trash'
  );

  $args = array(
    'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
    'rewrite' => false,  // it shouldn't have rewrite rules because it is not public
    'publicly_queriable' => true,  // you should be able to query it
    'show_ui' => true,  // you should be able to edit it in wp-admin
    'show_in_admin_bar' => true,
    'exclude_from_search' => true,  // you should exclude it from search results
    'show_in_nav_menus' => false,  // selectable in nav menus
    'show_in_rest'       => true,
    'has_archive' => false,  // it shouldn't have archive page
    'labels'            => $labels,
    'capabilty_type'    => 'post',
    'menu_icon'         => 'dashicons-schedule',
    'map_meta_cap'      => 'true',
    'supports' => array( 'title','editor','author','revisions','thumbnail'),
    'taxonomies' => array( 'sl_html_block_category')
  );

  register_post_type('sl_html_block', $args);
}
add_action('init', 'scale_lite_html_block_post_type');
// End custom post type sl_cblock

// Disable wordpress adding unwanted p tags http://stackoverflow.com/questions/11248628/disable-wordpress-from-adding-p-tags
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

/**
 * SL HTMl Block shortcode
 * [html_block class="my-custom-class" post-id="269"/]
 */
function scale_lite_html_block_shortcode($atts = array(), $content = null, $tag = ''){
  // normalize attribute keys, lowercase
  $sl_attrs = array_change_key_case((array)$atts, CASE_LOWER);
  if(!array_key_exists("post-id",$sl_attrs) || $sl_attrs['post-id'] == null) {
    return;
  }
  if(!array_key_exists("class",$sl_attrs)) {
    $sl_attrs['class']='';
  }

  // output buffer start
  ob_start();

  $args = array(
    // Query sl_cblock with ID
    'p' => $sl_attrs['post-id'],
    'post_type' => 'sl_html_block'
  );
  $query = new WP_Query($args);
  $o = '';
  if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
    $hide_in_rest_api = get_post_meta(get_the_ID(),'html_block_options_hide-in-rest-api',true);
    $show_title = get_post_meta(get_the_ID(),'html_block_options_show-block-title',true);
    $block_title = esc_html__(get_post_meta(get_the_ID(),'html_block_options_html-title-tag',true), 'sl_html_title_tag'); // escape block title
    $html_title_tag = ($block_title) ? $block_title : "p";
    $css_wrapper_classes = esc_html__(get_post_meta(get_the_ID(),'html_block_options_css-wrapper-classes',true), 'sl_html_wrapper_classes'); // escape css classes
    // start output
    $o = '';
    // start box
    $o .= '<div id="html-block-'.esc_html($sl_attrs['post-id']).'" class="html-block '. $css_wrapper_classes.' '.esc_html($sl_attrs['class']).'">';
    $o .=  ($show_title) ? "<".$html_title_tag.">".apply_filters( 'wp_title', get_the_title())."</".$html_title_tag.">" : "" ;
    $o .= $content = apply_filters( 'the_content', get_the_content());// so you can run shortcodes like contact form 7, also see https://codeseekah.com/2012/02/26/what-the_content-goes-through/
    $o .= '</div>';
    endwhile; wp_reset_postdata(); // Restore original post data.
  else:
    $o .= '<div class="html-block">No block found matching ID: '.esc_html($sl_attrs['post-id']).'</div>';
  endif;
  echo $o;
  $o = ob_get_contents();
  ob_end_clean();

  // return output
  return $o;
}

// Regster cblock shortcode
add_shortcode('html_block', 'scale_lite_html_block_shortcode');
