<?php
/**
 * WC_CSP_Condition_Behind_VPN class
 *
 * @author   Brian Henry <brianhenryie@gmail.com>
 * @package  brianhenryie/bh-wc-csp-condition-ip-address
 * @since    1.0.0
 */

namespace BrianHenryIE\WC_CSP_Condition_IP_Address\WooCommerce_Conditional_Shipping_And_Payments;

use BrianHenryIE\WC_CSP_Condition_IP_Address\Curl\Curl;
use BrianHenryIE\WC_CSP_Condition_IP_Address\IPLib\Factory as IPFactory;
use BrianHenryIE\WC_CSP_Condition_IP_Address\Usox\IpIntel\Exception\ServiceException;
use BrianHenryIE\WC_CSP_Condition_IP_Address\Usox\IpIntel\IpIntel;
use BrianHenryIE\WC_CSP_Condition_IP_Address\WP_Logger\Logger;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use WC_CSP_Condition;
use WC_Geolocation;

/**
 * IP Address
 *
 * @see https://github.com/mlocati/ip-lib
 */
class WC_CSP_Condition_Behind_VPN extends WC_CSP_Condition {
	use LoggerAwareTrait;

	/**
	 * Constructor.
	 *
	 * @param ?LoggerInterface $logger A PSR logger.
	 */
	public function __construct( LoggerInterface $logger = null ) {
		$this->setLogger( $logger ?? Logger::instance() );

		$this->id                             = 'ip_behind_vpn';
		$this->title                          = __( 'IP VPN', 'bh-wc-csp-condition-ip-address' );
		$this->supported_product_restrictions = array( 'shipping_countries', 'payment_gateways', 'shipping_methods' );
		$this->supported_global_restrictions  = array( 'shipping_countries', 'payment_gateways', 'shipping_methods' );
	}

	/**
	 * Return condition field-specific resolution message which is combined along with others into a single restriction "resolution message".
	 *
	 * @param  array $data  Condition field data.
	 * @param  array $args  Optional arguments passed by restriction.
	 * @return string|false
	 */
	public function get_condition_resolution( $data, $args ) {

		// Empty conditions always return false (not evaluated).
		if ( empty( $data['value'] ) ) {
			return false;
		}

		$message = false;

		if ( $this->modifier_is( $data['modifier'], array( 'is' ) ) ) {
			$message = __( 'not available', 'bh-wc-csp-condition-ip-address' );
		} elseif ( $this->modifier_is( $data['modifier'], array( 'is-not' ) ) ) {
			$message = __( 'not available', 'bh-wc-csp-condition-ip-address' );
		}

		return $message;
	}

	/**
	 * Get the customer's IP address and check is it behind a VPN.
	 *
	 * Fails-safe (true) when config is bad or when IP cannot be determined.
	 *
	 * @param  array{condition_id:string, modifier:string} $data
	 * @param  array<mixed>                                $args  (Optional arguments passed by restrictions.) Not in use here.
	 * @return boolean
	 */
	public function check_condition( $data, $args ) {

		// Empty conditions always apply (not evaluated).
		// if ( empty( $data['value'] ) ) {
		// return true;
		// }

		// TODO: Should just be using Cloudflare plugin.
		if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
			$_SERVER['HTTP_X_REAL_IP'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
		}

		$current_user_ip_address = WC_Geolocation::get_ip_address();

		$ip_address = IPFactory::addressFromString( $current_user_ip_address );

		if ( is_null( $ip_address ) ) {
			return true;
		}

		// @see https://getipintel.net/free-proxy-vpn-tor-ip-lookup/

		$transient_name = "csp-condition-behind-vpn-ipintel-{$current_user_ip_address}";

		$result = get_transient( $transient_name );

		if ( empty( $result ) ) {

			$client = new IpIntel(
				new Curl(),
				get_option( 'woocommerce_email_from_address' ) // The store manager's email address.
			);

			try {
				$ip_behind_vpn = ! $client->validate( $current_user_ip_address );
			} catch ( ServiceException $service_exception ) {
				$this->logger->error( $service_exception->getMessage(), array( 'exception' => $service_exception ) );
				// TODO: This should configurable.
				// Current defaults to not-a-vpn.
				return false;
			}

			$expires_in = WEEK_IN_SECONDS;

			set_transient( $transient_name, ( $ip_behind_vpn ? 'is-behind-vpn' : 'is-not-behind-vpn' ), $expires_in );

		} else {
			$ip_behind_vpn = ( 'is-behind-vpn' === $result );
		}

		// @see IPHub API
		// try {
		// $block = IPHub::isBadIP( $current_user_ip_address, "IP HUB API Key (free up to 1000/day)" );
		// if($block == 1){
		// die("Request blocked as you appear to be browsing from a VPN/Proxy/Server. Please contact support if you believe this is a mistake.");
		// }
		// } catch (\Exception $e) {
		// $block = false;
		// }

		if ( $this->modifier_is( $data['modifier'], array( 'is' ) ) && $ip_behind_vpn ) {
			return true;
		} elseif ( $this->modifier_is( $data['modifier'], array( 'is-not' ) ) && ! $ip_behind_vpn ) {
			return true;
		}

		return false;
	}

	/**
	 * Validate, process and return condition fields.
	 *
	 * @param  array $posted_condition_data
	 * @return array
	 */
	public function process_admin_fields( $posted_condition_data ) {

		$processed_condition_data                 = array();
		$processed_condition_data['condition_id'] = $this->id;
		$processed_condition_data['modifier']     = stripslashes( $posted_condition_data['modifier'] );

		return $processed_condition_data;
	}

	/**
	 * "IP VPN" – "Customer IP is a VPN"
	 *
	 * @param  int                    $index
	 * @param  int                    $condition_index
	 * @param  array{modifier:string} $condition_data
	 */
	public function get_admin_fields_html( $index, $condition_index, $condition_data ) {

		$modifier = '';

		if ( ! empty( $condition_data['modifier'] ) ) {
			$modifier = $condition_data['modifier'];
		}

		?>
		<input type="hidden" name="restriction[<?php echo esc_attr( $index ); ?>][conditions][<?php echo esc_attr( $condition_index ); ?>][condition_id]" value="<?php echo esc_attr( $this->id ); ?>" />
		<div class="condition_row_inner">
		<div class="condition_modifier">
			<div class="sw-enhanced-select">
				<select name="restriction[<?php echo esc_attr( $index ); ?>][conditions][<?php echo esc_attr( $condition_index ); ?>][modifier]">
					<option value="is" <?php selected( $modifier, 'is', true ); ?>><?php echo esc_html( __( 'Customer IP is a VPN', 'bh-wc-csp-condition-ip-address' ) ); ?></option>
					<option value="is-not" <?php selected( $modifier, 'is-not', true ); ?>><?php echo esc_html( __( 'Customer IP is not a VPN', 'bh-wc-csp-condition-ip-address' ) ); ?></option>
				</select>
			</div>
		</div>
		<div class="condition_value condition--disabled"></div>
		</div>
		<?php
	}
}
