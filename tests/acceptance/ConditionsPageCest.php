<?php

class ConditionsPageCest {

	public function _before( AcceptanceTester $I ) {
	}


	/**
	 *
	 * @param AcceptanceTester $I
	 */
	public function testConditionIsAvailable( AcceptanceTester $I ) {

		$I->loginAsAdmin();

		$I->amOnAdminPage( 'admin.php?page=wc-settings&tab=restrictions&section=payment_gateways&add_rule=1' );

		$I->selectOption( '.condition_select select', 'ip_address' );

	}

	// Can't really test much more here without JavaScript.
}
