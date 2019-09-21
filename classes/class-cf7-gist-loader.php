<?php
/**
 * Contact form 7 gist extension Loader.
 *
 * @package cf7-gist
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'Cf7_Gist_Loader' ) ) {

	/**
	 * Loads tracking scripts and settings.
	 */
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

			add_action( 'admin_notices', array( $this, 'cf7_inactive_notice' ) );

			$this->define_constants();

			add_action( 'wp_enqueue_scripts', array( $this, 'add_gist_tracking_script' ) );
			add_filter( 'wpcf7_editor_panels', __CLASS__ . '::render_options_metabox' );
			add_action( 'wpcf7_after_save', array( $this, 'save_gist_settings' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}

		/**
		 * Display admin notice when Contact Form 7 plugin is not active.
		 *
		 * @return void
		 */
		function cf7_inactive_notice() {

			if ( class_exists( 'WPCF7' ) ) {
				return;
			}
			?>
			<div class="notice notice-info is-dismissible">
				<?php /* translators: 1: strong open tag, 2: strong close */ ?>
				<p><?php printf( __( 'The %1$sContact Form 7%2$s plugin must be installed and active for the %3$sContact Form 7 GIST Extension%4$s plugin to work.', 'contact-form-7-gist' ), '<strong>', '</strong>', '<strong>', '</strong>' ); ?></p>
			</div>
			<?php
		}

		/**
		 * Define constants.
		 *
		 * @return void
		 */
		function define_constants() {

			define( 'CF7_GIST_VERSION', '1.0.1' );
			define( 'CF7_GIST_PLUGIN_NAME', trim( dirname( CF7_GIST_PLUGIN_BASENAME ), '/' ) );
		}

		/**
		 * Load Gist tracking scripts.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		function add_gist_tracking_script() {

			if ( ! class_exists( 'WPCF7' ) ) {
				return;
			}

			$args = array(
				'post_type'      => 'wpcf7_contact_form',
				'posts_per_page' => -1,
			);

			$cf7_forms = get_posts( $args );
			$form_data = array();

			if ( ! empty( $cf7_forms ) ) {

				foreach ( $cf7_forms as $form ) {

					$id = $form->ID;

					$is_gist_enabled = get_option( 'cf7_gist_' . $id, false );

					if ( $is_gist_enabled ) {
						$form_data[ $id ] = $is_gist_enabled;
					}
				}
			}

			wp_enqueue_script( 'cf7-gist-scripts', CF7_GIST_PLUGIN_URL . '/assets/js/frontend.js', array( 'jquery' ), CF7_GIST_VERSION, true );

			wp_localize_script(
				'cf7-gist-scripts',
				'cf7_gist_vars',
				array(
					'form_data' => $form_data,
				)
			);
		}

		/**
		 * Add options page inside cf7 settings.
		 *
		 * @param array $panels existing cf7 Panels.
		 * @since 1.0.0
		 * @return array
		 */
		public static function render_options_metabox( $panels ) {

			$options_page = array(
				'Gist-Extension' => array(
					'title'    => __( 'GIST (Formerly ConvertFox)', 'contact-form-7-gist' ),
					'callback' => array( self::get_instance(), 'gist_settings' ),
				),
			);

			$panels = array_merge( $panels, $options_page );

			return $panels;
		}

		/**
		 * Callback to metabox settings.
		 *
		 * @since 1.0.0
		 * @param array $args arguments.
		 * @return void
		 */
		function gist_settings( $args ) {

			$cf7_gist_defaults = array();
			$cf7_gist          = (int) get_option( 'cf7_gist_' . $args->id(), $cf7_gist_defaults );

			?>

			<div class="cf7_gist_fields">
				<p class="enable_gist_sync">
					<input type="checkbox" id="cf7-gist-enabled" name="cf7-gist[enabled]" value="1"<?php echo ( isset( $cf7_gist ) && 1 === $cf7_gist ) ? ' checked="checked"' : ''; ?> />
					<label for="cf7-gist-enabled">
					<?php echo esc_html( __( 'Sync form data to GIST.', 'contact-form-7-gist' ) ); ?>
					</label>
				</p>
			</div>

			<?php
		}

		/**
		 * Update settings in option database.
		 *
		 * @since 1.0.0
		 * @param array $args arguments.
		 * @return void
		 */
		function save_gist_settings( $args ) {

			if ( ! empty( $_POST ) && isset( $_POST['cf7-gist'] ) ) {
				update_option( 'cf7_gist_' . $args->id(), (int) $_POST['cf7-gist']['enabled'] );
			} else {
				delete_option( 'cf7_gist_' . $args->id() );
			}
		}

		/**
		 * Load plugin textdomain.
		 *
		 * @since 1.0.1
		 */
		function load_textdomain() {
			load_plugin_textdomain( 'contact-form-7-gist', false, CF7_GIST_PLUGIN_DIR . '/languages' );
		}
	}

	Cf7_Gist_Loader::get_instance();
}
