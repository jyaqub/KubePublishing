<?php
/*
Plugin Name: Bootstrap Buttons
Plugin URI: http://cramer.co.za/bootstrap-buttons
Description: The full set of buttons from Twitters Bootstrap available to use in your content via a shortcode.
Author: David Cramer
Version: 1.00
Author URI: http://cramer.co.za
*/
/*
  Built using My Shortcodes Pro
  (C) 2012 - David Cramer
  All Rights Reserved
*/
//initilize plugin
define('BOOTSTRAPBUTTONS_PATH', plugin_dir_path(__FILE__));
define('BOOTSTRAPBUTTONS_URL', plugin_dir_url(__FILE__));

require_once BOOTSTRAPBUTTONS_PATH.'libs/functions.php';
require_once BOOTSTRAPBUTTONS_PATH.'libs/actions.php';
require_once BOOTSTRAPBUTTONS_PATH.'libs/widget_bsbutton.php';


register_activation_hook( __FILE__, 'bootstrapbuttons_setup');
register_deactivation_hook( __FILE__, 'bootstrapbuttons_exit');
?>