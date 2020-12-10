<?php
namespace BH_WC_CSP_Condition_IP_Address\includes;

use BH_WC_CSP_Condition_IP_Address\WPPB\WPPB_Loader_Interface;
use Codeception\Stub\Expected;

class BH_WC_CSP_Condition_IP_Address_Test extends \Codeception\Test\Unit {

	/**
	 * On construction, one action and one filter should be registered with the loader.
	 */
	public function test_construct() {

		$mock_loader = $this->makeEmpty(
			WPPB_Loader_Interface::class,
			array(
				'add_action' => Expected::once(),
				'add_filter' => Expected::once(),
			)
		);

		new BH_WC_CSP_Condition_IP_Address( $mock_loader );

	}

	public function test_run() {

		$mock_loader = $this->makeEmpty(
			WPPB_Loader_Interface::class,
			array(
				'run' => Expected::once(),
			)
		);

		$sut = new BH_WC_CSP_Condition_IP_Address( $mock_loader );

		$sut->run();

	}

	public function test_get_loader() {

		$mock_loader = $this->makeEmpty( WPPB_Loader_Interface::class );

		$sut = new BH_WC_CSP_Condition_IP_Address( $mock_loader );

		$loader = $sut->get_loader();

		$this->assertSame( $mock_loader, $loader );

	}

}
