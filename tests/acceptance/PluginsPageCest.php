<?php

class PluginsPageCest {

	/**
	 * Login and navigate to plugins.php.
	 *
	 * @param AcceptanceTester $I The Codeception test actor.
	 */
	public function _before( AcceptanceTester $I ) {
		$I->loginAsAdmin();

		$I->amOnPluginsPage();
	}

	/**
	 * Verify the name of the plugin has been set.
	 *
	 * @param AcceptanceTester $I The Codeception test actor.
	 */
	public function testPluginsPageForName( AcceptanceTester $I ) {

		$I->canSee( 'IP Address Condition for WooCommerce Conditional Shipping and Payments' );
	}

	/**
	 * @param AcceptanceTester $I The Codeception test actor.
	 */
	public function testPluginDescriptionHasBeenSet( AcceptanceTester $I ) {

		$default_plugin_description = "This is a short description of what the plugin does. It's displayed in the WordPress admin area.";

		$I->cantSee( $default_plugin_description );
	}

}
