<?php
/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Registers new SL HTML Block Widget
* @since 0.1
*/

namespace Scale_Lite;

class Html_Block_Shortcode_Widget extends \WP_Widget {
  public function __construct(){
    $widget_details = array(
        'classname'   => 'sl_html_block_widget',
        'description' => 'Registers new HTML Block for shortcodes.',
        'customize_selective_refresh' => true
    );

    $control_options = array(
      'width'  => 400
    );

    parent::__construct( 'sl_html_block_widget', 'HTML Block', $widget_details, $control_options );
  }

  public function widget( $args, $instance ) {
    if (isset($instance['textarea'])) {
        echo do_shortcode($instance['textarea']);
    }
  }

  public function form($instance) {
    // Check values
    if( $instance) {
        $title = esc_attr($instance['title']);
        $textarea = esc_textarea($instance['textarea']);
    } else {
        $title = '';
        $textarea = '';
    }
    ?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Block description', 'wp_widget_plugin'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('textarea'); ?>"><?php _e('Shortcode:', 'wp_widget_plugin'); ?></label>
        <textarea class="widefat" rows="7" cols="20" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo $textarea; ?></textarea>
    </p><?php
  }

  // update widget
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    // Fields
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['textarea'] = strip_tags($new_instance['textarea']);
    return $instance;
  }
}

add_action( 'widgets_init', __NAMESPACE__ . '\\scale_lite_shortcode_widget_init' );
function scale_lite_shortcode_widget_init() {
  register_widget( __NAMESPACE__ . '\\Html_Block_Shortcode_Widget' );
}
