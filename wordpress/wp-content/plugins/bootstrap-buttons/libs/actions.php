<?php
/*
  Built using My Shortcodes Pro
  (C) 2012 - David Cramer
  All Rights Reserved
*/
add_action('widgets_init', 'bootstrapbuttons_bsbutton_init');
add_action('wp_loaded', 'bootstrapbuttons_process');
add_action('admin_footer-widgets.php', 'bootstrapbuttons_widgetjs');
add_action('admin_head-widgets.php', 'bootstrapbuttons_widgetcss');

add_action('wp_footer', 'bootstrapbuttons_footer');
add_action('wp_head', 'bootstrapbuttons_header');
add_action('media_buttons', 'bootstrapbuttons_button', 11);
add_filter('widget_text', 'do_shortcode');



add_shortcode('bsbutton', 'bootstrapbuttons_doShortcode');

if(is_admin() === true){
    add_action('wp_ajax_bootstrapbuttons_load_elementConfig', 'bootstrapbuttons_load_elementConfig');
}
add_action('wp_ajax_my_shortcode_ajax', 'mspro_shortcode_ajax');
add_action('wp_ajax_nopriv_my_shortcode_ajax', 'mspro_shortcode_ajax');

?>