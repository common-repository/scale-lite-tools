<?php
/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Main dashboard options
* @since 1.0.5.5
*/

namespace Scale_Lite;

class ScaleLiteToolsOptions {
  private $scale_lite_tools_options_options;

  public function __construct() {
    add_action( 'admin_menu', array( $this, 'scale_lite_tools_options_add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'scale_lite_tools_options_page_init' ) );
  }

  public function scale_lite_tools_options_add_plugin_page() {
    add_options_page(
      'Scale Lite Tools Options', // page_title
      'Scale Lite Tools', // menu_title
      'manage_options', // capability
      'scale-lite-tools-options', // menu_slug
      array( $this, 'scale_lite_tools_options_create_admin_page' ) // function
    );
  }

  public function scale_lite_tools_options_create_admin_page() {
    $this->scale_lite_tools_options_options = get_option( 'scale_lite_tools_options_option_name' ); ?>
    <div class="scale-lite-tools-options-main-dashboard wrap">
      <form method="post" action="options.php">
        <?php
          settings_fields( 'scale_lite_tools_options_option_group' );
          do_settings_sections( 'scale-lite-tools-options-admin' );
          submit_button();
        ?>
      </form>
    </div>
  <?php }

  public function scale_lite_tools_options_page_init() {
    register_setting(
      'scale_lite_tools_options_option_group', // option_group
      'scale_lite_tools_options_option_name', // option_name
      array( $this, 'scale_lite_tools_options_sanitize' ) // sanitize_callback
    );

    add_settings_section(
      'scale_lite_tools_options_setting_section', // id
      'Enable modules by checking the options below', // title
      array( $this, 'scale_lite_tools_options_section_info' ), // callback
      'scale-lite-tools-options-admin' // page
    );
/*
    add_settings_field(
      'scale_lite_api_key_0', // id
      'Scale Lite API Key', // title
      array( $this, 'scale_lite_api_key_0_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );
*/
    add_settings_field(
      'enable_html_blocks_1', // id
      'HTML Blocks', // title
      array( $this, 'enable_html_blocks_1_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );

    add_settings_field(
      'enable_gallery_manager_2', // id
      'Gallery Manager', // title
      array( $this, 'enable_gallery_manager_2_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );
/*
    add_settings_field(
      'enable_slider_3', // id
      'Slider', // title
      array( $this, 'enable_slider_3_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );
*/
    add_settings_field(
      'enable_google_maps_v3_4', // id
      'Google Maps v3', // title
      array( $this, 'enable_google_maps_v3_4_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );
/*
    add_settings_field(
      'enable_social_share_5', // id
      'Social Share', // title
      array( $this, 'enable_social_share_5_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );

    add_settings_field(
      'enable_query_tools_6', // id
      'Query Tools', // title
      array( $this, 'enable_query_tools_6_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );

    add_settings_field(
      'enable_woocommerce_tools_7', // id
      'WooCommerce Tools', // title
      array( $this, 'enable_woocommerce_tools_7_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );

    add_settings_field(
      'enable_sidebar_manager_8', // id
      'Sidebar Manager', // title
      array( $this, 'enable_sidebar_manager_8_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );
*/

    add_settings_field(
      'enable_layout_tools_9', // id
      'Layout Tools', // title
      array( $this, 'enable_layout_tools_9_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );
/*
    add_settings_field(
      'enable_member_tools_10', // id
      'Member Tools', // title
      array( $this, 'enable_member_tools_10_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );

    add_settings_field(
      'enable_firewall_tools_11', // id
      'Firewall Tools', // title
      array( $this, 'enable_firewall_tools_11_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );
 */
    add_settings_field(
      'enable_debug_tools_12', // id
      'Debug Tools', // title
      array( $this, 'enable_debug_tools_12_callback' ), // callback
      'scale-lite-tools-options-admin', // page
      'scale_lite_tools_options_setting_section' // section
    );
  }

  public function scale_lite_tools_options_sanitize($input) {
    $sanitary_values = array();
    if ( isset( $input['scale_lite_api_key_0'] ) ) {
      $sanitary_values['scale_lite_api_key_0'] = sanitize_text_field( $input['scale_lite_api_key_0'] );
    }

    if ( isset( $input['enable_html_blocks_1'] ) ) {
      $sanitary_values['enable_html_blocks_1'] = $input['enable_html_blocks_1'];
    }

    if ( isset( $input['enable_gallery_manager_2'] ) ) {
      $sanitary_values['enable_gallery_manager_2'] = $input['enable_gallery_manager_2'];
    }

    if ( isset( $input['enable_slider_3'] ) ) {
      $sanitary_values['enable_slider_3'] = $input['enable_slider_3'];
    }

    if ( isset( $input['enable_google_maps_v3_4'] ) ) {
      $sanitary_values['enable_google_maps_v3_4'] = $input['enable_google_maps_v3_4'];
    }

    if ( isset( $input['enable_social_share_5'] ) ) {
      $sanitary_values['enable_social_share_5'] = $input['enable_social_share_5'];
    }

    if ( isset( $input['enable_query_tools_6'] ) ) {
      $sanitary_values['enable_query_tools_6'] = $input['enable_query_tools_6'];
    }

    if ( isset( $input['enable_woocommerce_tools_7'] ) ) {
      $sanitary_values['enable_woocommerce_tools_7'] = $input['enable_woocommerce_tools_7'];
    }

    if ( isset( $input['enable_sidebar_manager_8'] ) ) {
      $sanitary_values['enable_sidebar_manager_8'] = $input['enable_sidebar_manager_8'];
    }

    if ( isset( $input['enable_layout_tools_9'] ) ) {
      $sanitary_values['enable_layout_tools_9'] = $input['enable_layout_tools_9'];
    }

    if ( isset( $input['enable_member_tools_10'] ) ) {
      $sanitary_values['enable_member_tools_10'] = $input['enable_member_tools_10'];
    }

    if ( isset( $input['enable_firewall_tools_11'] ) ) {
      $sanitary_values['enable_firewall_tools_11'] = $input['enable_firewall_tools_11'];
    }

    if ( isset( $input['enable_debug_tools_12'] ) ) {
      $sanitary_values['enable_debug_tools_12'] = $input['enable_debug_tools_12'];
    }

    return $sanitary_values;
  }

  public function scale_lite_tools_options_section_info() {

  }
/*
  public function scale_lite_api_key_0_callback() {
    printf(
      '<input class="semi-large-text" type="text" name="scale_lite_tools_options_option_name[scale_lite_api_key_0]" id="scale_lite_api_key_0" value="%s"><br /><label for="scale_lite_tools_options_option_name[scale_lite_api_key_0]" class="description">leave empty</label>',
      isset( $this->scale_lite_tools_options_options['scale_lite_api_key_0'] ) ? esc_attr( $this->scale_lite_tools_options_options['scale_lite_api_key_0']) : ''
    );
  }
*/
  public function enable_html_blocks_1_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_html_blocks_1]" id="enable_html_blocks_1" value="enable_html_blocks_1" %s> <label for="enable_html_blocks_1" class="description">Enable HTML Blocks</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_html_blocks_1'] ) && $this->scale_lite_tools_options_options['enable_html_blocks_1'] === 'enable_html_blocks_1' ) ? 'checked' : ''
    );
  }

  public function enable_gallery_manager_2_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_gallery_manager_2]" id="enable_gallery_manager_2" value="enable_gallery_manager_2" %s> <label for="enable_gallery_manager_2" class="description">Loads baguetteBox.js on the frontend</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_gallery_manager_2'] ) && $this->scale_lite_tools_options_options['enable_gallery_manager_2'] === 'enable_gallery_manager_2' ) ? 'checked' : ''
    );
  }
/*
  public function enable_slider_3_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_slider_3]" id="enable_slider_3" value="enable_slider_3" %s> <label for="enable_slider_3" class="description">Enable Slider</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_slider_3'] ) && $this->scale_lite_tools_options_options['enable_slider_3'] === 'enable_slider_3' ) ? 'checked' : ''
    );
  }
*/
  public function enable_google_maps_v3_4_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_google_maps_v3_4]" id="enable_google_maps_v3_4" value="enable_google_maps_v3_4" %s> <label for="enable_google_maps_v3_4" class="description">requires Google Maps API Key</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_google_maps_v3_4'] ) && $this->scale_lite_tools_options_options['enable_google_maps_v3_4'] === 'enable_google_maps_v3_4' ) ? 'checked' : ''
    );
  }
/*
  public function enable_social_share_5_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_social_share_5]" id="enable_social_share_5" value="enable_social_share_5" %s> <label for="enable_social_share_5" class="description">Enable Social Share</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_social_share_5'] ) && $this->scale_lite_tools_options_options['enable_social_share_5'] === 'enable_social_share_5' ) ? 'checked' : ''
    );
  }

  public function enable_query_tools_6_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_query_tools_6]" id="enable_query_tools_6" value="enable_query_tools_6" %s> <label for="enable_query_tools_6" class="description">Enable Query Tools</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_query_tools_6'] ) && $this->scale_lite_tools_options_options['enable_query_tools_6'] === 'enable_query_tools_6' ) ? 'checked' : ''
    );
  }

  public function enable_woocommerce_tools_7_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_woocommerce_tools_7]" id="enable_woocommerce_tools_7" value="enable_woocommerce_tools_7" %s> <label for="enable_woocommerce_tools_7" class="description">Enable WooCommerce Tools</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_woocommerce_tools_7'] ) && $this->scale_lite_tools_options_options['enable_woocommerce_tools_7'] === 'enable_woocommerce_tools_7' ) ? 'checked' : ''
    );
  }

  public function enable_sidebar_manager_8_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_sidebar_manager_8]" id="enable_sidebar_manager_8" value="enable_sidebar_manager_8" %s> <label for="enable_sidebar_manager_8" class="description">Enable Sidebar Manager</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_sidebar_manager_8'] ) && $this->scale_lite_tools_options_options['enable_sidebar_manager_8'] === 'enable_sidebar_manager_8' ) ? 'checked' : ''
    );
  }
*/
  public function enable_layout_tools_9_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_layout_tools_9]" id="enable_layout_tools_9" value="enable_layout_tools_9" %s> <label for="enable_layout_tools_9" class="description">Loads vue.js 2.1.8 on the frontend</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_layout_tools_9'] ) && $this->scale_lite_tools_options_options['enable_layout_tools_9'] === 'enable_layout_tools_9' ) ? 'checked' : ''
    );
  }
/*
  public function enable_member_tools_10_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_member_tools_10]" id="enable_member_tools_10" value="enable_member_tools_10" %s> <label for="enable_member_tools_10" class="description">Enable Member Tools</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_member_tools_10'] ) && $this->scale_lite_tools_options_options['enable_member_tools_10'] === 'enable_member_tools_10' ) ? 'checked' : ''
    );
  }

  public function enable_firewall_tools_11_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_firewall_tools_11]" id="enable_firewall_tools_11" value="enable_firewall_tools_11" %s> <label for="enable_firewall_tools_11" class="description">Enable Firewall Tools</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_firewall_tools_11'] ) && $this->scale_lite_tools_options_options['enable_firewall_tools_11'] === 'enable_firewall_tools_11' ) ? 'checked' : ''
    );
  }
*/
  public function enable_debug_tools_12_callback() {
    printf(
      '<input type="checkbox" name="scale_lite_tools_options_option_name[enable_debug_tools_12]" id="enable_debug_tools_12" value="enable_debug_tools_12" %s> <label for="enable_debug_tools_12" class="description">Enable Debug Tools</label>',
      ( isset( $this->scale_lite_tools_options_options['enable_debug_tools_12'] ) && $this->scale_lite_tools_options_options['enable_debug_tools_12'] === 'enable_debug_tools_12' ) ? 'checked' : ''
    );
  }
}
if ( is_admin() )
  $scale_lite_tools_options = new ScaleLiteToolsOptions();

/*
 * Retrieve this value with:
 * $scale_lite_tools_options_options = get_option( 'scale_lite_tools_options_option_name' ); // Array of All Options
 * $scale_lite_api_key_0 = $scale_lite_tools_options_options['scale_lite_api_key_0']; // Scale Lite API Key
 * $enable_html_blocks_1 = $scale_lite_tools_options_options['enable_html_blocks_1']; // Enable HTML Blocks
 * $enable_gallery_manager_2 = $scale_lite_tools_options_options['enable_gallery_manager_2']; // Enable Gallery Manager
 * $enable_slider_3 = $scale_lite_tools_options_options['enable_slider_3']; // Enable Slider
 * $enable_google_maps_v3_4 = $scale_lite_tools_options_options['enable_google_maps_v3_4']; // Enable Google Maps v3
 * $enable_social_share_5 = $scale_lite_tools_options_options['enable_social_share_5']; // Enable Social Share
 * $enable_query_tools_6 = $scale_lite_tools_options_options['enable_query_tools_6']; // Enable Query Tools
 * $enable_woocommerce_tools_7 = $scale_lite_tools_options_options['enable_woocommerce_tools_7']; // Enable WooCommerce Tools
 * $enable_sidebar_manager_8 = $scale_lite_tools_options_options['enable_sidebar_manager_8']; // Enable Sidebar Manager
 * $enable_layout_tools_9 = $scale_lite_tools_options_options['enable_layout_tools_9']; // Enable Layout Tools
 * $enable_member_tools_10 = $scale_lite_tools_options_options['enable_member_tools_10']; // Enable Member Tools
 * $enable_firewall_tools_11 = $scale_lite_tools_options_options['enable_firewall_tools_11']; // Enable Firewall Tools
 * $enable_debug_tools_12 = $scale_lite_tools_options_options['enable_debug_tools_12']; // Enable Debug Tools
 */

