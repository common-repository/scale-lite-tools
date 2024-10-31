<?php
/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Register custom meta fields for slt_gmap
* @since 0.1
*/

namespace Scale_Lite;

class Google_Maps_Meta_Box {
  private $screens = array(
    'slt_gmap',
  );
  private $fields = array(
    array(
      'id' => 'slt_meta-center-map-location',
      'label' => 'Center map location',
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
      'id' => 'slt_meta-markers',
      'label' => 'Add markers to map',
      'placeholder' => 'id1,id2,id3',
      'type' => 'textarea',
    ),
    array(
      'id' => 'slt_meta-html-id',
      'label' => 'HTML ID',
      'type' => 'text',
    ),
    array(
      'id' => 'slt_meta-width',
      'label' => 'Width of map',
      'type' => 'number',
    ),
    array(
      'id' => 'slt_meta-height',
      'label' => 'Height of map',
      'type' => 'number',
    ),
    array(
      'id' => 'slt_meta-map-type',
      'label' => 'Map type',
      'type' => 'select',
      'options' => array(
        'roadmap',
        'satellite',
        'hybrid',
        'terrain',
      ),
    ),
    array(
      'id' => 'slt_meta-zoom',
      'label' => 'Zoom',
      'type' => 'number',
    ),
    array(
      'id' => 'slt_meta-show-marker-info-box',
      'label' => 'Show marker info box',
      'type' => 'checkbox',
    ),
    array(
      'id' => 'slt_meta-kml-file',
      'label' => 'KML file',
      'type' => 'textarea',
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
        __( 'Preview map and set options', 'scale-lite' ),
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
    echo '<div id="preview_map_container" class="preview_map_container"></div>';
    wp_nonce_field( 'slt_gmap_data', 'slt_gmap_nonce' );
    $this->generate_fields( $post );
  }

  /**
   * Generates the field's HTML for the meta box.
   */
  public function generate_fields( $post ) {
    $output = '';
    foreach ( $this->fields as $field ) {
      $label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
      $db_value = get_post_meta( $post->ID, 'slt_gmap_' . $field['id'], true );
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
            case 'slt_meta-center-map-location':
              if(empty($db_value)) {
                $db_value = '350 5th Ave, New York, NY 10118, USA';
              }
              $field['placeholder'] = (array_key_exists('placeholder',$field)) ? $field['placeholder']:'';
              $input = sprintf(
                '<div class="slt-gmap-address-container">
                  <div class="slt-gmap-address">
                    <textarea placeholder="%s" class="large-text" id="%s" name="%s" rows="4">%s</textarea><p class="description">Paste here your address, then click preview map. If you only have one marker, map will be centered there. If you have multiple markers drag the preview map to center it.</p>
                  </div>
                  <input name="slt-map-preview" type="button" class="button button-primary button-large" id="slt-map-preview" value="Preview map">
                </div>

                ',
                $field['placeholder'],
                $field['id'],
                $field['id'],
                $db_value
              );
              break;
            case 'slt_slt_meta-markers':
              $field['placeholder'] = (array_key_exists('placeholder',$field)) ? $field['placeholder']:'';
              $input = sprintf(
                '<div class="slt-gmap-markers-container">
                  <div class="slt-gmap-markers">
                    <textarea placeholder="%s" class="large-text" id="%s" name="%s" rows="4">%s</textarea><p class="description">Marker IDs that will be added to the map.</p>
                  </div>
                  <input name="slt-add-marker" type="button" class="button button-primary button-large" id="slt-add-marker" value="Add marker to map">
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
          switch ( $field['id'] ) {
            case 'slt_slt_meta-html-id':
              $input = sprintf(
                '<input %s id="%s" name="%s" type="%s" value="%s"><p class="description">Unique HTML ID for the map.</p>',
                $field['type'] !== 'color' ? 'class="semi-large-text"' : '',
                $field['id'],
                $field['id'],
                $field['type'],
                $db_value
              );
              break;
            case 'slt_slt_meta-width':
              $input = sprintf(
                '<input %s id="%s" name="%s" type="%s" value="%s"><p class="description">Width of the map in pixels. e.g. 350. If empty, it defaults to 100%%.</p>',
                $field['type'] !== 'color' ? 'class="semi-large-text"' : '',
                $field['id'],
                $field['id'],
                $field['type'],
                $db_value
              );
              break;
            case 'slt_slt_meta-height':
              $input = sprintf(
                '<input %s id="%s" name="%s" type="%s" value="%s"><p class="description">Height of the map in pixels. e.g. 520</p>',
                $field['type'] !== 'color' ? 'class="semi-large-text"' : '',
                $field['id'],
                $field['id'],
                $field['type'],
                $db_value
              );
              break;
            case 'slt_slt_meta-latitude':
              $input = sprintf(
                '<input %s id="%s" name="%s" type="%s" value="%s"><p class="description">Latitude coordinate of where your map will be centered</p>',
                $field['type'] !== 'color' ? 'class="semi-large-text"' : '',
                $field['id'],
                $field['id'],
                $field['type'],
                $db_value
              );
              break;
            case 'slt_slt_meta-longitude':
              $input = sprintf(
                '<input %s id="%s" name="%s" type="%s" value="%s"><p class="description">Longitude coordinate of where your map will be centered</p>',
                $field['type'] !== 'color' ? 'class="semi-large-text"' : '',
                $field['id'],
                $field['id'],
                $field['type'],
                $db_value
              );
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
    if ( ! isset( $_POST['slt_gmap_nonce'] ) )
      return $post_id;

    $nonce = $_POST['slt_gmap_nonce'];
    if ( !wp_verify_nonce( $nonce, 'slt_gmap_data' ) )
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
        update_post_meta( $post_id, 'slt_gmap_' . $field['id'], $_POST[ $field['id'] ] );
      } else if ( $field['type'] === 'checkbox' ) {
        update_post_meta( $post_id, 'slt_gmap_' . $field['id'], '0' );
      }
    }
  }
}
new Google_Maps_Meta_Box;
