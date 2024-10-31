<?php
/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Register custom meta fields for slt_gmap_marker
* @since 0.1
*/

namespace Scale_Lite;

class Google_Marker_Meta_Box {
  private $screens = array(
    'slt_gmap_marker',
  );
  private $fields = array(
    array(
      'id' => 'slt_meta-location-address',
      'label' => 'Location address',
      'type' => 'textarea',
    ),
    array(
      'id' => 'slt_meta-latitude',
      'label' => 'Latitude',
      'type' => 'text',
    ),
    array(
      'id' => 'slt_meta-longitude',
      'label' => 'Longitude',
      'type' => 'text',
    ),
    array(
      'id' => 'slt_meta-title',
      'label' => 'Info window title',
      'type' => 'text',
    ),
    array(
      'id' => 'slt_meta-description',
      'label' => 'Info window description',
      'type' => 'textarea',
    ),
    array(
      'id' => 'slt_meta-icon',
      'label' => 'Google Material Icon',
      'type' => 'text',
    ),
    array(
      'id' => 'slt_marker-icon-color',
      'label' => 'HEX color code',
      'type' => 'text',
    )
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
        'map-options',
        __( 'Marker Options', 'slt_gmap_marker' ),
        array( $this, 'add_meta_box_callback' ),
        $screen,
        'advanced',
        'default'
      );
    }
  }

  /**
   * Generates the HTML for the meta box
   *
   * @param object $post WordPress post object
   */
  public function add_meta_box_callback( $post ) {
    echo '<div id="preview_marker_container" class="preview_marker_container"></div>';
    wp_nonce_field( 'slt_gmarker_data', 'slt_gmarker_nonce' );
    $this->generate_fields( $post );
  }

  /**
   * Generates the field's HTML for the meta box.
   */
  public function generate_fields( $post ) {
    $output = '';
    foreach ( $this->fields as $field ) {
      $label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
      $db_value = get_post_meta( $post->ID, 'slt_gmarker_' . $field['id'], true );
      switch ( $field['type'] ) {
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
        case 'textarea':
          switch ( $field['id'] ) {
            case 'slt_meta-location-address':
              if(empty($db_value)) {
                $db_value = '350 5th Ave, New York, NY 10118, USA';
              }
              $field['placeholder'] = (array_key_exists('placeholder',$field)) ? $field['placeholder']:'';
              $input = sprintf(
                '<div class="slt_meta-location-address-container">
                  <div class="slt_meta-location-address">
                    <textarea placeholder="%s" class="large-text" id="%s" name="%s" rows="4">%s</textarea><p class="description">Paste here your address, then click preview marker.</p>
                  </div>
                  <input name="slt-marker-preview" type="button" class="button button-primary button-large" id="slt-marker-preview" value="Preview marker">
                </div>

                ',
                $field['placeholder'],
                $field['id'],
                $field['id'],
                $db_value
              );
              break;
            default:
              $field['placeholder'] = (array_key_exists('placeholder',$field)) ? $field['placeholder']:'';
              $input = sprintf(
                '<textarea placeholder="%s" class="large-text" id="%s" name="%s" rows="4">%s</textarea>',
                $field['placeholder'],
                $field['id'],
                $field['id'],
                $db_value
              );
          }
          break;
        default:
          $input = sprintf(
            '<input %s id="%s" name="%s" type="%s" value="%s">',
            $field['type'] !== 'color' ? 'class="semi-large-text"' : '',
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
    if ( ! isset( $_POST['slt_gmarker_nonce'] ) )
      return $post_id;

    $nonce = $_POST['slt_gmarker_nonce'];
    if ( !wp_verify_nonce( $nonce, 'slt_gmarker_data' ) )
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
        update_post_meta( $post_id, 'slt_gmarker_' . $field['id'], $_POST[ $field['id'] ] );
      } else if ( $field['type'] === 'checkbox' ) {
        update_post_meta( $post_id, 'slt_gmarker_' . $field['id'], '0' );
      }
    }
  }
}
new Google_Marker_Meta_Box;
