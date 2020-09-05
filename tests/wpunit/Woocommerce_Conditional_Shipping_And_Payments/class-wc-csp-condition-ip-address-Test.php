<?php


namespace BH_WC_CSP_Condition_IP_Address\Woocommerce_Conditional_Shipping_And_Payments;

use BH_WC_CSP_Condition_IP_Address\IPLib\Factory as IPFactory;
use BH_WC_CSP_Condition_IP_Address\Logger;
use WC_CSP_Condition;
use WC_Geolocation;


class WC_CSP_Condition_IP_Address_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * When the IP address is explicitly blocked, the condition should return true.
	 */
	public function test_happy_path_single_ip_match() {

		$sut = new WC_CSP_Condition_IP_Address();

		$mock_client_ip_address = '159.134.237.6';
		$mock_config = array(
			'159.134.237.6'
		);

		$_SERVER['HTTP_X_REAL_IP'] = $mock_client_ip_address;
		set_transient("external_ip_address_$mock_client_ip_address", $mock_client_ip_address);

		$data = array();
		$data['condition_id'] = 'ip_address';
		$data['value']        = $mock_config;
		$data['modifier']     = 'in';

		$args = array();

		$result = $sut->check_condition( $data, $args );

		$this->assertTrue( $result );

	}

	/**
	 * When the IP address does not match the blocked IP, the condition should return false.
	 */
	public function test_happy_path_single_ip_no_match() {
		$sut = new WC_CSP_Condition_IP_Address();

		$mock_client_ip_address = '159.134.237.99';
		$mock_config = array(
			'159.134.237.6'
		);

		$_SERVER['HTTP_X_REAL_IP'] = $mock_client_ip_address;
		set_transient("external_ip_address_$mock_client_ip_address", $mock_client_ip_address);

		$data = array();
		$data['condition_id'] = 'ip_address';
		$data['value']        = $mock_config;
		$data['modifier']     = 'in';

		$args = array();

		$result = $sut->check_condition( $data, $args );

		$this->assertFalse( $result );


	}

	/**
	 * When the IP address is not in the specified range, the condition (block) should return true.
	 */
	public function test_happy_path_in_range() {

		$sut = new WC_CSP_Condition_IP_Address();

		$mock_client_ip_address = '159.134.237.7';
		$mock_config = array(
			'159.134.237.6/24'
		);

		$_SERVER['HTTP_X_REAL_IP'] = $mock_client_ip_address;
		set_transient("external_ip_address_$mock_client_ip_address", $mock_client_ip_address);

		$data = array();
		$data['condition_id'] = 'ip_address';
		$data['value']        = $mock_config;
		$data['modifier']     = 'in';

		$args = array();

		$result = $sut->check_condition( $data, $args );

		$this->assertTrue( $result );


	}


	/**
	 * When the IP address is not in the specified range, the condition (block) should return true.
	 */
	public function test_happy_path_not_in_range() {

		$sut = new WC_CSP_Condition_IP_Address();

		$mock_client_ip_address = '159.144.237.7';
		$mock_config = array(
			'159.134.237.6/24'
		);

		$_SERVER['HTTP_X_REAL_IP'] = $mock_client_ip_address;
		set_transient("external_ip_address_$mock_client_ip_address", $mock_client_ip_address);

		$data = array();
		$data['condition_id'] = 'ip_address';
		$data['value']        = $mock_config;
		$data['modifier']     = 'not-in';

		$args = array();

		$result = $sut->check_condition( $data, $args );

		$this->assertTrue( $result );

	}

	/**
	 * The condition check should fail-safe.
	 */
	public function test_bad_input() {

		$sut = new WC_CSP_Condition_IP_Address();

		$result = $sut->check_condition( [], [] );

		$this->assertTrue( $result );
	}

	/**
	 * The condition check should fail-safe.
	 */
	public function test_bad_ip() {

		$sut = new WC_CSP_Condition_IP_Address();

		$_SERVER['HTTP_X_REAL_IP'] = '123.456.789.0';
		set_transient("external_ip_address_123.456.789.0", 'null' );

		add_filter('pre_transient_external_ip_address_123.456.789.0', function( $return, $transient ) {
			return null;
		},10,2);

		$data = array();
		$data['condition_id'] = 'ip_address';
		$data['value']        = ['123.456.789.0'];
		$data['modifier']     = 'not-in';

		$result = $sut->check_condition( $data, [] );

		$this->assertTrue( $result );
	}


	/**
	 * A well-formed IPv4 address sanitized should return itself unchanged.
	 */
	public function test_sanitize_happy_ipv4() {

		$sut = new WC_CSP_Condition_IP_Address();

		$ip = '192.168.1.1';

		$result = $sut->sanitize_ip( $ip );

		$this->assertNotFalse( $result );

		$this->assertEquals( $ip, $result );
	}

	/**
	 * Any malformed input should return false.
	 */
	public function test_sanitize_bad_ipv4() {

		$sut = new WC_CSP_Condition_IP_Address();

		$ip = '192.168.1.';

		$result = $sut->sanitize_ip( $ip );

		$this->assertFalse( $result );

	}

	/**
	 * A CIDR IP range should return itself when sanitizing.
	 */
	public function test_sanitize_happy_ipv4_range() {

		$sut = new WC_CSP_Condition_IP_Address();

		$ip = '192.168.1.1/24';

		$result = $sut->sanitize_ip( $ip );

		$this->assertNotFalse( $result );

		$this->assertEquals( $ip, $result );

	}


	/**
	 * A malformed range should return false.
	 */
	public function test_sanitize_bad_ipv4_range() {

		$sut = new WC_CSP_Condition_IP_Address();

		$ip = '192.168.1.1/567';

		$result = $sut->sanitize_ip( $ip );

		$this->assertFalse( $result );

	}

	/**
	 * Well-formed ip should return itself.
	 */
	public function test_sanitize_happy_ipv6() {

		$sut = new WC_CSP_Condition_IP_Address();

		$ip = '2001:cdba:0000:0000:0000:0000:3257:9652';

		$result = $sut->sanitize_ip( $ip );

		$this->assertNotFalse( $result );

		$this->assertEquals( $ip, $result );

	}

	/**
	 *
	 */
	public function test_sanitize_happy_ipv6_range() {
		$sut = new WC_CSP_Condition_IP_Address();

		$ip = 'fec0::/10';

		$result = $sut->sanitize_ip( $ip );

		$this->assertNotFalse( $result );

		$this->assertEquals( $ip, $result );
	}


	/**
	 * The main function of process_admin_fields is to take the html textarea input, split it, sanitize it
	 * and return an array of the entries.
	 */
	public function tests_process_admin_fields_happy_single() {

		$sut = new WC_CSP_Condition_IP_Address();

		$input = array(
			'condition_id' => 'ip_address',
			'value' => "111.111.111.111\r\n222.222.222.222\r\n11.11.11.11/16",
			'modifier' => 'not-in'
		);

		$result = $sut->process_admin_fields( $input );

		$this->assertIsArray($result['value']);

		$this->assertCount( 3, $result['value'] );
	}

	/**
	 * If the input field is empty, false should be returned.
	 */
	public function tests_process_admin_fields_bad() {

		$sut = new WC_CSP_Condition_IP_Address();

		$input = array(
			'condition_id' => 'ip_address',
			'value' => '',
			'modifier' => 'not-in'
		);

		$result = $sut->process_admin_fields( $input );

		$this->assertFalse( $result );
	}

}