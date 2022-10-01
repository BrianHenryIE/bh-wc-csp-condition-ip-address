<?php

namespace BrianHenryIE\WC_CSP_Condition_IP_Address;

use BrianHenryIE\ColorLogger\ColorLogger;
use BrianHenryIE\WC_CSP_Condition_IP_Address\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Conditions;
use BrianHenryIE\WC_CSP_Condition_IP_Address\WP_Includes\I18n;
use WP_Mock\Matcher\AnyInstance;

/**
 * @coversDefaultClass \BrianHenryIE\WC_CSP_Condition_IP_Address\BH_WC_CSP_Condition_IP_Address
 */
class BH_WC_CSP_Condition_IP_Address_Test extends \Codeception\Test\Unit {

	protected function setup(): void {
		parent::setup();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		parent::tearDown();
		\WP_Mock::tearDown();
	}

	/**
	 * @covers ::set_locale
	 * @covers ::__construct
	 */
	public function test_set_locale_hooked(): void {

		\WP_Mock::expectActionAdded(
			'init',
			array( new AnyInstance( I18n::class ), 'load_plugin_textdomain' )
		);

		$logger = new ColorLogger();
		new BH_WC_CSP_Condition_IP_Address( $logger );
	}

	/**
	 * @covers ::define_wcsp_hooks
	 */
	public function test_define_wcsp_hooks(): void {

		\WP_Mock::expectFilterAdded(
			'woocommerce_csp_conditions',
			array( new AnyInstance( WC_CSP_Conditions::class ), 'add_condition' )
		);

		$logger = new ColorLogger();
		new BH_WC_CSP_Condition_IP_Address( $logger );
	}

}
