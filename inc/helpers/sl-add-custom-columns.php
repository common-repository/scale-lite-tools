<?php

/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Register custom columns
* @phpcredits() http://www.ibenic.com/add-custom-column-wordpress-oop/
* @since 0.1
*/

require_once( plugin_dir_path(__FILE__) . 'class-helper-add-post-column.php' );
require_once( plugin_dir_path(__FILE__) . 'class-helper-add-post-columns.php' );


function scale_lite_register_shortcode_block_column( $post_id, $column_key) {
  $css_wrapper_class = get_post_meta(get_the_ID($post_id),'html_block_options_css-wrapper-classes',true);
  echo "<span class='sl-shortcode-string' id='html-block-".$post_id."'>[html_block class='".$css_wrapper_class."' post-id='".$post_id."']</span><input type='button' onclick=\"Scale_Lite_Copy_To_Clipboard('html-block-".$post_id."')\" class='button' value='Copy shortcode to clipboard' >";
}

function scale_lite_register_shortcode_map_column( $post_id, $column_key) {
  echo "<span class='sl-shortcode-string' id='sl-gmap-".$post_id."'>[sl_gmaps post-id='".$post_id."']</span><input type='button' onclick=\"Scale_Lite_Copy_To_Clipboard('sl-gmap-".$post_id."')\" class='button' value='Copy shortcode to clipboard' >";
}

$sl_shortcode_block_column = new Scale_Lite\AddColumn(
  array(
    'post_type'=> 'sl_html_block',
    'column_key' => 'scale_lite_block_shortcode',
    'column_title' => __( 'Shortcode', 'scale_lite_domain' ),
    'column_display' => 'scale_lite_register_shortcode_block_column'
));

$sl_shortcode_map_column = new Scale_Lite\AddColumn(
  array(
    'post_type'=> 'slt_gmap',
    'column_key' => 'scale_lite_map_shortcode',
    'column_title' => __( 'Shortcode', 'scale_lite_domain' ),
    'column_display' => 'scale_lite_register_shortcode_map_column'
));

$sl_add_shortcode_column = new Scale_Lite\AddColumns (
  array(
    $sl_shortcode_block_column,
    $sl_shortcode_map_column
  )
);

$sl_add_shortcode_column->add_columns();
// End of register ID columns
