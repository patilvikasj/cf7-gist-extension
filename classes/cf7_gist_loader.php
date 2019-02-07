<?php
/**
 * Contact form 7 gist extension Loader.
 *
 * @package cf7-gist
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'Cf7_Gist_Loader' ) ) {

	class Cf7_Gist_Loader {

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
			add_filter( 'wpcf7_editor_panels', __CLASS__ . '::render_options_metabox' );
			add_action( 'wpcf7_after_save', array( $this, 'save_gist_settings' ) );
		}

		function add_gist_tracking_script() {

			wp_enqueue_script( 'cf7-gist-scripts', cf7_gist_PLUGIN_URL . '/assets/js/frontend.js', array( 'jquery' ), cf7_gist_VERSION, true );
		}

		public static function render_options_metabox( $panels ) {

			$options_page = array(
				'Gist-Extension' => array(
					'title' => __( 'Gist', 'contact-form-7' ),
					'callback' => array( self::get_instance(), 'gist_settings' )
				)
			);

			$panels = array_merge( $panels, $options_page );

			return $panels;
		}

		function gist_settings( $args ) {

			$cf7_gist_defaults = array();
			$cf7_gist = get_option( 'cf7_gist_'.$args->id(), $cf7_gist_defaults );

			?>

			<div class="cf7_gist_fields">
				<p class="enable_gist_sync">
					<input type="checkbox" id="cf7-gist-enabled" name="cf7-gist[enabled]" value="1"<?php echo ( isset( $cf7_gist['enabled'] ) ) ? ' checked="checked"' : ''; ?> />

       				 <label for="cf7-gist-enabled">
       				 	<?php echo esc_html( __( 'Sync form data to Gist.', 'cf7-gist' ) ); ?>
       				 </label>
				</p>
			</div>

			<?php
		}

		function save_gist_settings( $args ) {

			if ( ! empty( $_POST ) ) {

				update_option( 'cf7_gist_'.$args->id(), $_POST['cf7-gist'] );
			}
		}

	}

	Cf7_Gist_Loader::get_instance();
}