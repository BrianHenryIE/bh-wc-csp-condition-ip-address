<?php
/**
 * Tests for BH_WC_CSP_Condition_IP_Address main setup class. Tests the actions are correctly added.
 *
 * @package BH_WC_CSP_Condition_IP_Address
 * @author  Brian Henry <BrianHenryIE@gmail.com>
 */

namespace BrianHenryIE\WC_CSP_Condition_IP_Address\WooCommerce_Conditional_Shipping_And_Payments;

use WC_Payment_Gateway;

class BH_WC_CSP_Condition_IP_Address_Checkout_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * Enable all the payment gateways, check which are available, restrict one, check which are available, compare.
	 */
	public function test_checkout_gateways() {

		/** @var WC_Payment_Gateway[] $payment_gateways */
		$payment_gateways = WC()->payment_gateways->payment_gateways();
		foreach ( $payment_gateways as $gateway ) {
			$gateway->update_option( 'enabled', 'yes' );
		}

		\WC_Payment_Gateways::instance()->init();

		$before_payment_gateways = \WC_Payment_Gateways::instance()->get_available_payment_gateways();

		$ip_address = \WC_Geolocation::get_ip_address();

		$csp_settings = array(
			'payment_gateways' => array(
				array(
					'gateways'       => array( 'cheque' ),
					'description'    => 'Admin role',
					'restriction_id' => 'payment_gateways',
					'index'          => 0,
					'enabled'        => 'yes',
					'conditions'     => array(
						array(
							'condition_id' => 'ip_address',
							'value'        => array( $ip_address ),
							'modifier'     => 'in',
						),
					),
					'wc_26_shipping' => 'yes',
				),
			),
		);

		$option = 'wccsp_restrictions_global_settings';

		add_filter(
			"pre_option_{$option}",
			function( $value, $option, $default ) use ( $csp_settings ) {
				return $csp_settings;
			},
			10,
			3
		);

		/**
		 * @see \WC_CSP_Restrict_Payment_Gateways::exclude_payment_gateways()
		 */
		define( 'WOOCOMMERCE_CHECKOUT', true );
		add_filter( 'woocommerce_is_checkout', '__return_true' );

		$after_payment_gateways = \WC_Payment_Gateways::instance()->get_available_payment_gateways();

		$this->assertNotEquals( count( $before_payment_gateways ), count( $after_payment_gateways ) );

	}

}
