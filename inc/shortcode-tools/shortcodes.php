<?php
/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Register breadcrumb navigation shortcode
* @since 0.1
*/

function scale_lite_breadcrumb_navigation() {
  global $post;
  $separator = " / ";

  echo '<div class="scale-lite-breadcrumb-navigation">';
  if (!is_front_page()) {
    echo '<a href="'.get_option('home').'">'.bloginfo('name')."</a> ".$separator;
    if ( is_category() || is_single() ) {
      the_category(', ');
      if ( is_single() ) {
        echo $separator;
        the_title();
      }
    } elseif ( is_page() && $post->post_parent ) {
      $home = get_page(get_option('page_on_front'));
      for ($i = count($post->ancestors)-1; $i >= 0; $i--) {
        if (($home->ID) != ($post->ancestors[$i])) {
          echo '<a href="';
          echo get_permalink($post->ancestors[$i]);
          echo '">';
          echo get_the_title($post->ancestors[$i]);
          echo "</a>".$separator;
        }
      }
      echo the_title();
    } elseif (is_page()) {
      echo the_title();
    } elseif (is_404()) {
      echo "404";
    }
  } else {
    bloginfo('name');
  }
  echo '</div>';
}

// register shortcode to call a sidebar inside a post or a page [get_sidebar name="main-sidebar"]
// http://wordpress.stackexchange.com/questions/69386/shortcodes-output-before-content
// https://casabona.org/2014/03/include-sidebar-shortcode-wordpress/
function scale_lite_call_dynamic_sidebar($atts = array()) {
  ob_start();
  dynamic_sidebar($atts['name']);
  $sidebar= ob_get_contents();
  ob_end_clean();

  return $sidebar;
}
add_shortcode('get_sidebar', 'scale_lite_call_dynamic_sidebar');

// post pagination
function  scale_lite_tools_pagination($query) {
  $args = array(
    'base'                 => remove_query_arg('page', get_pagenum_link(1)).'%_%',  // Used to reference URL , which will create the paginate links
    'format'               => '?paged=%#%',  // User to create pagination structure
    'total'                => $query->max_num_pages,  // The total number of pages
    'current'              => max( 1, get_query_var('paged') ),  // Current Page
    'show_all'             => false,  // Whether to show all pages,
    'end_size'             => 3,  // How many pages to show on start or end of the list
    'mid_size'             => 3,  // How many pages to show around current page
    'prev_next'            => true,  // Whether to show the next and previous links
    'prev_text'            => __('« Previous'),  // Previous link text
    'next_text'            => __('Next »'),  // Next link text
    'type'                 => 'plain',  // Format to return “Plain”, “Array” or “List”
    'add_args'             => false,  // Array of optional query arguments
    'add_fragment'         => '',  // String to append to each link
    'before_page_number'   => '',  // Text to add before page number
    'after_page_number'    => ''  // Text to add after page number
  );
  return '<div class="sl-post-pagination">'.paginate_links($args).'</div>';
}






