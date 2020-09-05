<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * frontend-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BH_WC_CSP_Condition_IP_Address
 * @subpackage BH_WC_CSP_Condition_IP_Address/Includes
 */

namespace BH_WC_CSP_Condition_IP_Address\Includes;

use BH_WC_CSP_Condition_IP_Address\Woocommerce_Conditional_Shipping_And_Payments\WC_CSP_Conditions;
use BH_WC_CSP_Condition_IP_Address\WPPB\WPPB_Loader_Interface;
use BH_WC_CSP_Condition_IP_Address\WPPB\WPPB_Object;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * frontend-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    BH_WC_CSP_Condition_IP_Address
 * @subpackage BH_WC_CSP_Condition_IP_Address/Includes
 * @author     Brian Henry <BrianHenryIE@gmail.com>
 */
class BH_WC_CSP_Condition_IP_Address extends WPPB_Object {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPPB_Loader_Interface    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the frontend-facing side of the site.
	 *
	 * @since    1.0.0
	 *
	 * @param WPPB_Loader_Interface $loader The WPPB class which adds the hooks and filters to WordPress.
	 */
	public function __construct( $loader ) {
		if ( defined( 'BH_WC_CSP_CONDITION_IP_ADDRESS_VERSION' ) ) {
			$this->version = BH_WC_CSP_CONDITION_IP_ADDRESS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'bh-wc-csp-condition-ip-address';

		parent::__construct( $this->plugin_name, $this->version );

		$this->loader = $loader;

		$this->set_locale();
		$this->define_wcsp_hooks();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	protected function set_locale() {

		$plugin_i18n = new I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to WooCommerce Conditional Shipping and Payments.
	 *
	 * @since    1.0.0
	 */
	protected function define_wcsp_hooks() {

		$plugin_wc_csp_conditions = new WC_CSP_Conditions();

		$this->loader->add_filter( 'woocommerce_csp_conditions', $plugin_wc_csp_conditions, 'add_condition' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WPPB_Loader_Interface    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}
