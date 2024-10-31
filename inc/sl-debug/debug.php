<?php
// wp head rocks
add_action('wp_footer', 'show_template');
function show_template() {
  global $template;
  global $post;

  $sl_template = explode('/',$template);
  $sl_post = $post;

  $o = '<div id="scale-lite-debug">';
  $o .= '<h1 id="sl-debug-header">SL DEBUG TEMPLATES</h1>';
  $o .= '<div id="sl-debug-content">';
  $o .= '<div class="template">ID: <strong>'.$sl_post->ID.'</strong></div>';
  $o .= '<div class="template">Check page template: is_page_template(\'<strong>'.end($sl_template).'</strong>\') ? '.'</div>';
  $o .= '<div class="template">Check page slug: is_page(\'<strong>'.$sl_post->post_name.'</strong>\') ? '.'</div>';
  $o .= '<div class="template">Test for frontpage always with: <strong>is_front_page()</strong></div>';
  $o .= '</div>';
  $o .= '</div>';
  echo $o;
}
