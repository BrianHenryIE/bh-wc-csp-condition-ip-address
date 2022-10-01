<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package  brianhenryie/bh-wc-csp-condition-ip-address
 */

namespace BrianHenryIE\WC_CSP_Condition_IP_Address\WP_Includes;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 */
class I18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain(): void {

		load_plugin_textdomain(
			'bh-wc-csp-condition-ip-address',
			false,
			plugin_basename( dirname( __FILE__, 3 ) ) . '/languages/'
		);
	}

}
