<?php

/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Register custom columns
* @phpcredits() http://www.ibenic.com/add-custom-column-wordpress-oop/
* @since 0.1
*/

if ( ! function_exists( 'scale_lite_column' ) ):
/**
 * Prepend the new column to the columns array
 */
function scale_lite_column($cols) {
  $column_id = array( 'scale_lite_ids' => __( 'ID', 'scale-lite-ids' ) );
  $cols = array_slice( $cols, 0, 1, true ) + $column_id + array_slice( $cols, 1, NULL, true );
  return $cols;
}

endif; // scale_lite_column

if ( ! function_exists( 'scale_lite_value' ) ) :
/**
 * Echo the ID for the new column
 */
function scale_lite_value( $column_name, $id ) {
  if ( 'scale_lite_ids' == $column_name ) {
    echo $id;
  }
}
endif; // scale_lite_value


if ( ! function_exists( 'scale_lite_return_value' ) ) :
function scale_lite_return_value( $value, $column_name, $id ) {
  if ( 'scale_lite_ids' == $column_name ) {
    $value .= $id;
  }
  return $value;
}
endif; // scale_lite_return_value

function scale_lite_id_add() {
  // For Media Management
  add_action( "manage_media_columns" ,        'scale_lite_column' );
  add_filter( "manage_media_custom_column" , 'scale_lite_value' , 10 , 3 );

  // For Link Management
  add_action( 'manage_link_custom_column', 'scale_lite_value', 10, 2 );
  add_filter( 'manage_link-manager_columns', 'scale_lite_column' );

  // For Category Management
  add_action( 'manage_edit-link-categories_columns', 'scale_lite_column' );
  add_filter( 'manage_link_categories_custom_column', 'scale_lite_return_value', 10, 3 );

  // For Category, Tags and other custom taxonomies Management
  foreach( get_taxonomies() as $taxonomy ) {
    add_action( "manage_edit-${taxonomy}_columns" ,  'scale_lite_column' );
    add_filter( "manage_${taxonomy}_custom_column" , 'scale_lite_return_value' , 10 , 3 );
    add_filter( "manage_edit-${taxonomy}_sortable_columns" , 'scale_lite_column' );
  }

  foreach( get_post_types() as $ptype ) {
    add_action( "manage_edit-${ptype}_columns" , 'scale_lite_column' );
    add_filter( "manage_${ptype}_posts_custom_column" , 'scale_lite_value' , 10 , 3 );
    add_filter( "manage_edit-${ptype}_sortable_columns" , 'scale_lite_column' );
  }

  // For User Management
  add_action( 'manage_users_columns', 'scale_lite_column' );
  add_filter( 'manage_users_custom_column', 'scale_lite_return_value', 10, 3 );
  add_filter( "manage_users_sortable_columns" , 'scale_lite_column' );

  // For Comment Management
  add_action( 'manage_edit-comments_columns', 'scale_lite_column' );
  add_action( 'manage_comments_custom_column', 'scale_lite_value', 10, 2 );
  add_filter( "manage_edit-comments_sortable_columns" , 'scale_lite_column' );
}
add_action( 'admin_init', 'scale_lite_id_add' );
