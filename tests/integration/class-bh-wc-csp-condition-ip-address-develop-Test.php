<?php
/**
 * Class Plugin_Test. Tests the root plugin setup.
 *
 * @package BH_WC_CSP_Condition_IP_Address
 * @author     Brian Henry <BrianHenryIE@gmail.com>
 */

namespace BH_WC_CSP_Condition_IP_Address;

use BH_WC_CSP_Condition_IP_Address\includes\BH_WC_CSP_Condition_IP_Address;

/**
 * Verifies the plugin has been instantiated and added to PHP's $GLOBALS variable.
 */
class Plugin_Develop_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Test the main plugin object is added to PHP's GLOBALS and that it is the correct class.
	 */
	public function test_plugin_instantiated() {

		$this->assertArrayHasKey( 'bh_wc_csp_condition_ip_address', $GLOBALS );

		$this->assertInstanceOf( BH_WC_CSP_Condition_IP_Address::class, $GLOBALS['bh_wc_csp_condition_ip_address'] );
	}

}
