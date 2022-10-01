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
 * @package  brianhenryie/bh-wc-csp-condition-ip-address
 */

namespace BrianHenryIE\WC_CSP_Condition_IP_Address;

use BrianHenryIE\WC_CSP_Condition_IP_Address\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Conditions;
use BrianHenryIE\WC_CSP_Condition_IP_Address\WP_Includes\I18n;
use Psr\Log\LoggerInterface;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * frontend-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 */
class BH_WC_CSP_Condition_IP_Address {

	protected LoggerInterface $logger;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the frontend-facing side of the site.
	 *
	 * @param LoggerInterface $logger A PSR logger.
	 */
	public function __construct( LoggerInterface $logger ) {
		$this->logger = $logger;

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
	protected function set_locale(): void {

		$plugin_i18n = new I18n();

		add_action( 'init', array( $plugin_i18n, 'load_plugin_textdomain' ) );
	}

	/**
	 * Register the hooks related to WooCommerce Conditional Shipping and Payments.
	 *
	 * @since    1.0.0
	 */
	protected function define_wcsp_hooks(): void {

		$plugin_wc_csp_conditions = new WC_CSP_Conditions();

		add_filter( 'woocommerce_csp_conditions', array( $plugin_wc_csp_conditions, 'add_condition' ) );
	}

}
