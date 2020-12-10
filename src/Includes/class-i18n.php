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
 * @package    BH_WC_CSP_Condition_IP_Address
 * @subpackage BH_WC_CSP_Condition_IP_Address/includes
 */

namespace BH_WC_CSP_Condition_IP_Address\includes;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    BH_WC_CSP_Condition_IP_Address
 * @subpackage BH_WC_CSP_Condition_IP_Address/includes
 * @author     Brian Henry <BrianHenryIE@gmail.com>
 */
class I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bh-wc-csp-condition-ip-address',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
