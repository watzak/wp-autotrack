<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/*

Plugin Name: wp-autotrack
Description: Autotrack is a JavaScript library built on top of analytics.js that makes it easier for web developers to track the user interactions that are common to most websites.
Plugin URI: http://github.com/watzak/wp-autotrack
Author: Klaus Zahiragic
Author URI: http://www.kamod.ch
Version: 0.0.1
*/

  require_once( 'includes/class-wp-autotrack.php' );

  new Wp_Autotrack(__FILE__);

?>
