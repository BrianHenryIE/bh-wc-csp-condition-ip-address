<?php
/**
 * Tests for the root plugin file.
 *
 * @package brianhenryie/bh-wp-autologin-urls
 * @author Brian Henry <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\WC_CSP_Condition_IP_Address;

use BrianHenryIE\WC_CSP_Condition_IP_Address\WP_Logger\Logger;
use Psr\Log\NullLogger;

class Plugin_Unit_Test extends \Codeception\Test\Unit {

	protected function setup(): void {
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		\Patchwork\restoreAll();
	}

	/**
	 * Verifies the plugin initialization.
	 */
	public function test_plugin_include(): void {

		// Prevents code-coverage counting, and removes the need to define the WordPress functions that are used in that class.
		\Patchwork\redefine(
			array( BH_WC_CSP_Condition_IP_Address::class, '__construct' ),
			function( $settings ) {}
		);

		global $plugin_root_dir;

		\Patchwork\redefine(
			array( Logger::class, 'instance' ),
			function() {
				return new NullLogger(); }
		);

		\WP_Mock::userFunction(
			'plugin_dir_path',
			array(
				'times'  => 1,
				'args'   => array( \WP_Mock\Functions::type( 'string' ) ),
				'return' => $plugin_root_dir . '/',
			)
		);

		\WP_Mock::userFunction(
			'plugin_basename',
			array(
				'times'  => 1,
				'args'   => array( \WP_Mock\Functions::type( 'string' ) ),
				'return' => 'bh-wc-csp-condition-ip-address/bh-wc-csp-condition-ip-address.php',
			)
		);

		ob_start();

		include $plugin_root_dir . '/bh-wc-csp-condition-ip-address.php';

		$printed_output = ob_get_contents();

		ob_end_clean();

		$this->assertEmpty( $printed_output );

		$this->assertArrayHasKey( 'bh_wc_csp_condition_ip_address', $GLOBALS );

		$this->assertInstanceOf( BH_WC_CSP_Condition_IP_Address::class, $GLOBALS['bh_wc_csp_condition_ip_address'] );
	}

}
