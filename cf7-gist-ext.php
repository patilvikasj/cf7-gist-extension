<?php
/*
Plugin Name: Contact Form 7 Gist Extension
Description: Integrate Contact Form 7 with Gist.
Author: Vikas Patil
Author URI: https://patilvikasj.wordpress.com/
Text Domain: contact-form-7
Domain Path: /languages/
Version: 1.0.0
*/

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

define( 'CF7_GIST_VERSION', '1.0.0' );
define( 'CF7_GIST_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'CF7_GIST_PLUGIN_NAME', trim( dirname( CF7_GIST_PLUGIN_BASENAME ), '/' ) );
define( 'CF7_GIST_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'CF7_GIST_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

require_once( CF7_GIST_PLUGIN_DIR . '/classes/cf7_gist_loader.php' );

