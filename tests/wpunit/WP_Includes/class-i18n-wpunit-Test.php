<?php
/**
 * Tests for I18n. Tests load_plugin_textdomain.
 *
 * @package BH_WC_CSP_Condition_IP_Address
 * @author  Brian Henry <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\WC_CSP_Condition_IP_Address\WP_Includes;

/**
 * @coversDefaultClass \BrianHenryIE\WC_CSP_Condition_IP_Address\WP_Includes\I18n
 */
class I18n_WPUnit_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Checks if the filter run by WordPress in the load_plugin_textdomain() function is called.
	 *
	 * @covers ::load_plugin_textdomain
	 */
	public function test_load_plugin_textdomain_function() {

		$called        = false;
		$actual_domain = null;

		$filter = function( $locale, $domain ) use ( &$called, &$actual_domain ) {

			$called        = true;
			$actual_domain = $domain;

			return $locale;
		};

		add_filter( 'plugin_locale', $filter, 10, 2 );

		$i18n = new I18n();

		$i18n->load_plugin_textdomain();

		$this->assertTrue( $called, 'plugin_locale filter not called within load_plugin_textdomain() suggesting it has not been set by the plugin.' );
		$this->assertEquals( 'bh-wc-csp-condition-ip-address', $actual_domain );

	}
}
