<?php
/**
 * Bootstrap file for integration tests. Since WooCommerce needs initial activation setup.
 *
 * @package           BH_WC_CSP_Condition_IP_Address
 */

activate_plugin( 'woocommerce/woocommerce.php' );

activate_plugin( 'woocommerce-conditional-shipping-and-payments/woocommerce-conditional-shipping-and-payments.php' );
