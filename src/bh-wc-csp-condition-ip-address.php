<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           BH_WC_CSP_Condition_IP_Address
 *
 * @wordpress-plugin
 * Plugin Name:       IP Address Condition for WooCommerce Conditional Shipping and Payments
 * Plugin URI:        http://github.com/username/bh-wc-csp-condition-ip-address/
 * Description:       Block by IPv4, IPv6 addresses, as well as IP ranges in CIDR formats and in pattern format.
 * Version:           1.0.1
 * Author:            Brian Henry
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bh-wc-csp-condition-ip-address
 * Domain Path:       /languages
 */

namespace BrianHenryIE\WC_CSP_Condition_IP_Address;

use BrianHenryIE\WC_CSP_Condition_IP_Address\Includes\BH_WC_CSP_Condition_IP_Address;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'autoload.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BH_WC_CSP_CONDITION_IP_ADDRESS_VERSION', '1.0.1' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function instantiate_bh_wc_csp_condition_ip_address() {

	$plugin = new BH_WC_CSP_Condition_IP_Address();

	return $plugin;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and frontend-facing site hooks.
 */
$GLOBALS['bh_wc_csp_condition_ip_address'] = instantiate_bh_wc_csp_condition_ip_address();
