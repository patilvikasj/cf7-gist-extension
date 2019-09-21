<?php
/**
 * Plugin Name: Gist (Formerly ConvertFox) Extension for Contact Form 7
 * Description: Integrate Contact Form 7 with Gist.
 * Author: patilvikasj
 * Author URI: https://patilvikasj.wordpress.com/
 * Text Domain: contact-form-7-gist
 * Domain Path: /languages/
 * Version: 1.0.1
 *
 * @package cf7-gist
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

define( 'CF7_GIST_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'CF7_GIST_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'CF7_GIST_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

require_once( CF7_GIST_PLUGIN_DIR . '/classes/class-cf7-gist-loader.php' );

