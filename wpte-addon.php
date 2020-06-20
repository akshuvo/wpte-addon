<?php
/**
 * Plugin Name: Wpte - WooCommerce Product Table for Elementor
 * Description: Wpte - WooCommerce Product Table for Elementor is a extenstion where you can get a product table from a variable product
 * Plugin URI:  https://elementor.com/
 * Version:     1.0.0
 * Author:      Elementor
 * Author URI:  https://elementor.com/
 * Text Domain: wpte-addon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! defined( 'WPTE_ADDON_PLUGIN_URL' ) ) {
	define( 'WPTE_ADDON_PLUGIN_URL', plugins_url( '/', __FILE__ ));
}

/**
 * Main Elementor Test Extension Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class WPTE_ADDON_MAIN_CLASS {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const WPTE_ADDON_VERSION = '1.0.10';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_Test_Extension The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'wpte-addon' );

	}

	/**
	 * Register Scripts
	 */
	function register_scripts(){

	    wp_register_style( 'wpte-addon', WPTE_ADDON_PLUGIN_URL . 'assets/css/wpte.css', null, WPTE_ADDON_MAIN_CLASS::WPTE_ADDON_VERSION );
	    wp_register_script( 'wpte-addon', WPTE_ADDON_PLUGIN_URL . 'assets/js/wpte.js', array('jquery'), WPTE_ADDON_MAIN_CLASS::WPTE_ADDON_VERSION );

	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Add Plugin actions
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

		//Register Widget Styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'register_scripts' ] );

		// Register Widget Scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'wpte-addon' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'wpte-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'wpte-addon' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'wpte-addon' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'wpte-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'wpte-addon' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'wpte-addon' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'wpte-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'wpte-addon' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

		// Include Widget files
		require_once( __DIR__ . '/widgets/wpte-widget.php' );

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WPTE_WIDGET_CLASS() );

	}

}

WPTE_ADDON_MAIN_CLASS::instance();

function move_variation_price() {
    remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
    add_action( 'woocommerce_before_variations_form', 'woocommerce_single_variation', 10 );
}
add_action( 'woocommerce_before_add_to_cart_form', 'move_variation_price' );

add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'filter_dropdown_option_html', 12, 2 );
function filter_dropdown_option_html( $html, $args ) {
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' );
    $show_option_none_html = '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

    $html = str_replace($show_option_none_html, '', $html);

    $output = "<div class='wpte-qty'>
	    <span class='wpte-minus'>-</span>
	    	{$html}
	    <span class='wpte-plus'>+</span>
    </div>";

    $html = $output;


    return $html;
}

function wpte_product_desc(){
	global $product;

	if( $product->get_type() != "variable" ){
		return;
	}

	echo "<div class='wpte_product_desc'>";
	echo $product->get_description();
	echo "</div>";
}
add_action( 'woocommerce_after_add_to_cart_quantity', 'wpte_product_desc', 10 );

function wpte_addon_inline_style(){

	$output = "
    .wpte_product_desc li:before {
    	content: '';
	    background-image: url(".WPTE_ADDON_PLUGIN_URL."assets/list-icon.png);
	}";
	wp_add_inline_style( 'wpte-addon', $output );
}
add_action( 'wp_enqueue_scripts', 'wpte_addon_inline_style', 200 );

/**
 * Trim zeros in price decimals
 **/
 add_filter( 'woocommerce_price_trim_zeros', '__return_true' );