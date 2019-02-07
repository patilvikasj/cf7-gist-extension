<?php
/**
 * Contact form 7 gist extension Loader.
 *
 * @package cf7-gist
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'Cf7_Gist_Loader' ) ) {

	final class Cf7_Gist_Loader {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance = null;

		/**
		 *  Initiator
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self;

				do_action( 'cf7_gist_ext_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'add_gist_tracking_script' ) );
		}

		function add_gist_tracking_script() {

			wp_enqueue_script( 'cf7-gist-scripts', cf7_gist_PLUGIN_URL . '/assets/js/frontend.js', array( 'jquery' ), cf7_gist_VERSION, true );
		}

	}

	Cf7_Gist_Loader::get_instance();
}