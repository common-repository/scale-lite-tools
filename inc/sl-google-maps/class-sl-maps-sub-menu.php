<?php
/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Register google maps submenu
* @since 0.1
*/

namespace Scale_Lite;

class slt_gmaps_settings {
  private $slt_gmaps_settings_options;

  public function __construct() {
    add_action( 'admin_menu', array( $this, 'slt_gmaps_settings_add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'slt_gmaps_settings_page_init' ) );
  }

  public function slt_gmaps_settings_add_plugin_page() {
    add_submenu_page(
      'slt_google_maps_config_menu',
      'Google maps settings', // page_title
      'API Key', // menu_title
      'manage_options', // capability
      'slt-gmaps-settings', // menu_slug
      array( $this, 'slt_gmaps_settings_create_admin_page' ), // function
      'dashicons-admin-generic', // icon_url
      3 // position
    );
  }

  public function slt_gmaps_settings_create_admin_page() {
    $this->slt_gmaps_settings_options = get_option( 'slt_gmaps_settings_option_name' ); ?>

    <div class="wrap">
      <h2>Google maps settings</h2>
      <p></p>
      <?php settings_errors(); ?>

      <form method="post" action="options.php">
        <?php
          settings_fields( 'slt_gmaps_settings_option_group' );
          do_settings_sections( 'slt-gmaps-settings-admin' );
          submit_button();
        ?>
      </form>
    </div>
  <?php }

  public function slt_gmaps_settings_page_init() {
    register_setting(
      'slt_gmaps_settings_option_group', // option_group
      'slt_gmaps_settings_option_name', // option_name
      array( $this, 'slt_gmaps_settings_sanitize' ) // sanitize_callback
    );

    add_settings_section(
      'slt_gmaps_settings_setting_section', // id
      'Settings', // title
      array( $this, 'slt_gmaps_settings_section_info' ), // callback
      'slt-gmaps-settings-admin' // page
    );

    add_settings_field(
      'google_maps_api_key_0', // id
      'Google Maps API Key', // title
      array( $this, 'google_maps_api_key_0_callback' ), // callback
      'slt-gmaps-settings-admin', // page
      'slt_gmaps_settings_setting_section' // section
    );

  }

  public function slt_gmaps_settings_sanitize($input) {
    $sanitary_values = array();
    if ( isset( $input['google_maps_api_key_0'] ) ) {
      $sanitary_values['google_maps_api_key_0'] = sanitize_text_field( $input['google_maps_api_key_0'] );
    }

    if ( isset( $input['zoom_level_1'] ) ) {
      $sanitary_values['zoom_level_1'] = sanitize_text_field( $input['zoom_level_1'] );
    }

    return $sanitary_values;
  }

  public function slt_gmaps_settings_section_info() {

  }

  public function google_maps_api_key_0_callback() {
    printf(
      '<input class="semi-large-text" type="text" name="slt_gmaps_settings_option_name[google_maps_api_key_0]" id="google_maps_api_key_0" value="%s">
      <p class="description">Find here how to get a API Key from <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Google</a></p>',
      isset( $this->slt_gmaps_settings_options['google_maps_api_key_0'] ) ? esc_attr( $this->slt_gmaps_settings_options['google_maps_api_key_0']) : ''
    );
  }


}
if ( is_admin() )
  $slt_gmaps_settings = new slt_gmaps_settings();

/*
 * Retrieve this value with:
 * $slt_gmaps_settings_options = get_option( 'slt_gmaps_settings_option_name' ); // Array of All Options
 * $google_maps_api_key_0 = $slt_gmaps_settings_options['google_maps_api_key_0']; // Google Maps API Key
 */
