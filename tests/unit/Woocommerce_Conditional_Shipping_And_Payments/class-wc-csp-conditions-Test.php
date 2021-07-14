<?php

namespace BrianHenryIE\WC_CSP_Condition_IP_Address\WooCommerce_Conditional_Shipping_And_Payments;

class WC_CSP_Conditions_Test extends \Codeception\Test\Unit {

    /**
     * Verify the filter that will add the conditions correctly adds the class name to the string[].
     */
    public function test_add_ip_address_condition() {

        $sut = new WC_CSP_Conditions();

        $result = $sut->add_condition( array() );

        $this->assertIsString( $result[0] );

        error_log( json_encode( $result ));

        $this->assertContains( 'BrianHenryIE\WC_CSP_Condition_IP_Address\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Condition_IP_Address', $result );

    }


    /**
     * Verify the filter that will add the conditions correctly adds the class name to the string[].
     */
    public function test_add_vpn_condition() {

        $sut = new WC_CSP_Conditions();

        $result = $sut->add_condition( array() );

        $this->assertContains( 'BrianHenryIE\WC_CSP_Condition_IP_Address\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Condition_Behind_VPN', $result );

    }

    /**
     * Verify the filter that will add the conditions correctly adds the class name to the string[].
     */
    public function test_add_is_mobile_condition() {

        $sut = new WC_CSP_Conditions();

        $result = $sut->add_condition( array() );

        $this->assertContains( 'BrianHenryIE\WC_CSP_Condition_IP_Address\WooCommerce_Conditional_Shipping_And_Payments\WC_CSP_Condition_Browser', $result );

    }

}
