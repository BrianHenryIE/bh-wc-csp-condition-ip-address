<?php
/**
 * Tests for BH_WC_CSP_Condition_IP_Address main setup class. Tests the actions are correctly added.
 *
 * @package BH_WC_CSP_Condition_IP_Address
 * @author  Brian Henry <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\WC_CSP_Condition_IP_Address\Includes;

use BrianHenryIE\WC_CSP_Condition_IP_Address\woocommerce_conditional_shipping_and_payments\WC_CSP_Conditions;

/**
 * Class Develop_Test
 */
class BH_WC_CSP_Condition_IP_Address_Develop_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Verify filter to call the add_condition function is added.
	 */
	public function test_action_woocommerce_csp_conditions_add_condition() {

		$action_name       = 'woocommerce_csp_conditions';
		$expected_priority = 10;
		$class_type        = WC_CSP_Conditions::class;
		$method_name       = 'add_condition';

		global $wp_filter;

		$this->assertArrayHasKey( $action_name, $wp_filter, "$method_name definitely not hooked to $action_name" );

		$actions_hooked = $wp_filter[ $action_name ];

		$this->assertArrayHasKey( $expected_priority, $actions_hooked, "$method_name definitely not hooked to $action_name priority $expected_priority" );

		$hooked_method = null;
		foreach ( $actions_hooked[ $expected_priority ] as $action ) {
			$action_function = $action['function'];
			if ( is_array( $action_function ) ) {
				if ( $action_function[0] instanceof $class_type ) {
					$hooked_method = $action_function[1];
				}
			}
		}

		$this->assertNotNull( $hooked_method, "No methods on an instance of $class_type hooked to $action_name" );

		$this->assertEquals( $method_name, $hooked_method, "Unexpected method name for $class_type class hooked to $action_name" );

	}
}
