<?php
/**
 * @class Html_Block_Meta_Fields
 * @author : Ionut Morariu moraryou@gmail.com
 * @description : Register a meta fields for sl_html_block custom post type
 * @since 0.1
 */

namespace Scale_Lite;

class Html_Block_Meta_Fields {
  private $screens = array(
    'sl_html_block',
  );
  private $fields = array(
    array(
      'id' => 'css-wrapper-classes',
      'label' => 'CSS wrapper classes',
      'type' => 'text',
    ),
    array(
      'id' => 'show-block-title',
      'label' => 'Show block title',
      'type' => 'checkbox',
    ),
    array(
      'id' => 'html-title-tag',
      'label' => 'HTML title tag',
      'type' => 'select',
      'options' => array(
        'p',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
      ),
    ),
    array(
      'id' => 'hide-in-rest-api',
      'label' => 'Hide in rest API',
      'type' => 'checkbox',
    ),
  );

  /**
   * Class construct method. Adds actions to their respective WordPress hooks.
   */
  public function __construct() {
    add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
    add_action( 'save_post', array( $this, 'save_post' ) );
  }

  /**
   * Hooks into WordPress' add_meta_boxes function.
   * Goes through screens (post types) and adds the meta box.
   */
  public function add_meta_boxes() {
    foreach ( $this->screens as $screen ) {
      add_meta_box(
        'html-block-options',
        __( 'HTML Block Options', 'scale-lite-tools' ),
        array( $this, 'add_meta_box_callback' ),
        $screen,
        'advanced',
        'core'
      );
    }
  }

  /**
   * Generates the HTML for the meta box
   *
   * @param object $post WordPress post object
   */
  public function add_meta_box_callback( $post ) {
    wp_nonce_field( 'html_block_options_data', 'html_block_options_nonce' );
    $this->generate_fields( $post );
  }

  /**
   * Generates the field's HTML for the meta box.
   */
  public function generate_fields( $post ) {
    $output = '';
    foreach ( $this->fields as $field ) {
      $label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
      $db_value = get_post_meta( $post->ID, 'html_block_options_' . $field['id'], true );
      switch ( $field['type'] ) {
        case 'checkbox':
          $input = sprintf(
            '<input %s id="%s" name="%s" type="checkbox" value="1">',
            $db_value === '1' ? 'checked' : '',
            $field['id'],
            $field['id']
          );
          break;
        case 'select':
          $input = sprintf(
            '<select id="%s" name="%s">',
            $field['id'],
            $field['id']
          );
          foreach ( $field['options'] as $key => $value ) {
            $field_value = !is_numeric( $key ) ? $key : $value;
            $input .= sprintf(
              '<option %s value="%s">%s</option>',
              $db_value === $field_value ? 'selected' : '',
              $field_value,
              $value
            );
          }
          $input .= '</select>';
          break;
        default:
          $input = sprintf(
            '<input %s id="%s" name="%s" type="%s" value="%s">',
            $field['type'] !== 'color' ? 'class="regular-text"' : '',
            $field['id'],
            $field['id'],
            $field['type'],
            $db_value
          );
      }
      $output .= $this->row_format( $label, $input );
    }
    echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
  }

  /**
   * Generates the HTML for table rows.
   */
  public function row_format( $label, $input ) {
    return sprintf(
      '<tr><th scope="row">%s</th><td>%s</td></tr>',
      $label,
      $input
    );
  }
  /**
   * Hooks into WordPress' save_post function
   */
  public function save_post( $post_id ) {
    if ( ! isset( $_POST['html_block_options_nonce'] ) )
      return $post_id;

    $nonce = $_POST['html_block_options_nonce'];
    if ( !wp_verify_nonce( $nonce, 'html_block_options_data' ) )
      return $post_id;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return $post_id;

    foreach ( $this->fields as $field ) {
      if ( isset( $_POST[ $field['id'] ] ) ) {
        switch ( $field['type'] ) {
          case 'email':
            $_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
            break;
          case 'text':
            $_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
            break;
        }
        update_post_meta( $post_id, 'html_block_options_' . $field['id'], $_POST[ $field['id'] ] );
      } else if ( $field['type'] === 'checkbox' ) {
        update_post_meta( $post_id, 'html_block_options_' . $field['id'], '0' );
      }
    }
  }
}
new Html_Block_Meta_Fields;
