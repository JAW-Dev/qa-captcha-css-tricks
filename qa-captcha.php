<?php
/**
 * @package     QA_Captcha
 * @link      	https://github.com/jawittdesigns/
 * @copyright   Copyright (c) 2014, Jason Witt
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 * @author      Jason Witt <contact@jawittdesigns.com>
 *
 * @wordpress-plugin
 * Plugin Name:       QA Captcha
 * Plugin URI:        https://github.com/jawittdesigns/QA-Captcha
 * Description:       A question and answer captcha
 * Version:           1.0.0
 * Author:            Jason Witt
 * Author URI:        http://jawittdesigns.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       qac
 * Domain Path:       /languages
 */ 

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}
if( !class_exists( 'QA_Captcha' ) ) {
	class QA_Captcha {
		
		/**
		 * Instance of the class
		 *
		 * @since 1.0.0
		 * @var Instance of QAC class
		 */
		private static $instance;

		/**
		 * Instance of the plugin
		 *
		 * @since 1.0.0
		 * @static
		 * @staticvar array $instance
		 * @return Instance
		 */
		public static function instance() {
			if ( !isset( self::$instance ) && ! ( self::$instance instanceof QA_Captcha ) ) {
				self::$instance = new QA_Captcha;
				self::$instance->define_constants();
				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
				self::$instance->includes();
				self::$instance->init = new QA_Captcha_Init();
			}
		return self::$instance;
		}

		/**
		 * Define the plugin constants
		 *
		 * @since  1.0.0
		 * @access private
		 * @return voide
		 */
		private function define_constants() {
			// Plugin Version
			if ( ! defined( 'QAC_VERSION' ) ) {
				define( 'QAC_VERSION', '1.0.0' );
			}
			// Prefix
			if ( ! defined( 'QAC_PREFIX' ) ) {
				define( 'QAC_PREFIX', 'qac_' );
			}
			// Textdomain
			if ( ! defined( 'QAC_TEXTDOMAIN' ) ) {
				define( 'QAC_TEXTDOMAIN', 'qac' );
			}
			// Plugin Options
			if ( ! defined( 'QAC_OPTIONS' ) ) {
				define( 'QAC_OPTIONS', 'qac-options' );
			}
			// Plugin Directory
			if ( ! defined( 'QAC_PLUGIN_DIR' ) ) {
				define( 'QAC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}
			// Plugin URL
			if ( ! defined( 'QAC_PLUGIN_URL' ) ) {
				define( 'QAC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}
			// Plugin Root File
			if ( ! defined( 'QAC_PLUGIN_FILE' ) ) {
				define( 'QAC_PLUGIN_FILE', __FILE__ );
			}
		}

		/**
		 * Load the required files
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function includes() {
			$includes_path = plugin_dir_path( __FILE__ ) . 'includes/';
			require_once QAC_PLUGIN_DIR . 'admin/class-qac-options.php';
			require_once QAC_PLUGIN_DIR . 'includes/class-qac-init.php';
		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function load_textdomain() {
			$plugin_name_lang_dir = dirname( plugin_basename( QAC_PLUGIN_FILE ) ) . '/languages/';
			$plugin_name_lang_dir = apply_filters( 'QAC_lang_dir', $plugin_name_lang_dir );

			$locale = apply_filters( 'plugin_locale',  get_locale(), QAC_TEXTDOMAIN );
			$mofile = sprintf( '%1$s-%2$s.mo', QAC_TEXTDOMAIN, $locale );

			$mofile_local  = $plugin_name_lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/edd/' . $mofile;

			if ( file_exists( $mofile_local ) ) {
				load_textdomain( QAC_TEXTDOMAIN, $mofile_local );
			} else {
				load_plugin_textdomain( QAC_TEXTDOMAIN, false, $plugin_name_lang_dir );
			}
		}

		/**
		 * Throw error on object clone
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', QAC_TEXTDOMAIN ), '1.6' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', QAC_TEXTDOMAIN ), '1.6' );
		}

	}
}
/**
 * Return the instance 
 *
 * @since 1.0.0
 * @return object The Safety Links instance
 */
function QA_Captcha_Run() {
	return QA_Captcha::instance();
}
QA_Captcha_Run();