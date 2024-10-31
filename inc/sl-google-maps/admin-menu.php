<?php

// register menu in dashboard
add_action( 'admin_menu', 'scale_lite_add_admin_menu' );
function scale_lite_add_admin_menu() {
  add_menu_page(
    'SL Google Maps',
    'SL Google Maps',
    'manage_options',
    'slt_google_maps_config_menu',
    'class-sl-maps-sub-menu.php',
    'dashicons-location-alt',
    25
  );
}
