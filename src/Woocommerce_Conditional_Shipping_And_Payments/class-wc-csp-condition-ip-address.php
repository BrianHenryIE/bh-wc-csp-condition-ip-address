<?php
/**
 * WC_CSP_Condition_IP_Address class
 *
 * @author   Brian Henry <brianhenryie@gmail.com>
 * @package  bh-wc-csp-condition-ip-address
 * @since    1.0.0
 */

namespace BH_WC_CSP_Condition_IP_Address\Woocommerce_Conditional_Shipping_And_Payments;

use BH_WC_CSP_Condition_IP_Address\IPLib\Factory as IPFactory;
use WC_CSP_Condition;
use WC_Geolocation;

/**
 * IP Address
 *
 * @see https://github.com/mlocati/ip-lib
 *
 * @class    WC_CSP_Condition_IP_Address
 * @version  1.0.0
 */
class WC_CSP_Condition_IP_Address extends WC_CSP_Condition {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id                             = 'ip_address';
		$this->title                          = __( 'IP Address', 'bh-wc-csp-condition-ip-address' );
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

		if ( $this->modifier_is( $data['modifier'], array( 'in' ) ) ) {
			$message = __( 'not available', 'bh-wc-csp-condition-ip-address' );
		} elseif ( $this->modifier_is( $data['modifier'], array( 'not-in' ) ) ) {
			$message = __( 'not available', 'bh-wc-csp-condition-ip-address' );
		}

		return $message;
	}

	/**
     * Get the customer's IP address and check it against the specified rules.
	 *
	 * Fails-safe when config is bad or when IP cannot be determined.
	 *
	 * @param  array $data  { 'condition_id', 'value', 'modifier' }
	 * @param  array $args  (Optional arguments passed by restrictions.) Not in use here.
	 * @return boolean
	 */
	public function check_condition( $data, $args ) {

		// Empty conditions always apply (not evaluated).
		if ( empty( $data['value'] ) ) {
			return true;
		}

		$external_ip_address = WC_Geolocation::get_external_ip_address();

		$ip_address = IPFactory::addressFromString( $external_ip_address );

		if ( is_null( $ip_address ) ) {
			return true;
		}

		$restricted_ips = array_map( array( $this, 'sanitize_ip' ), $data['value'] );

		$ip_is_in_a_restricted_range = false;

		foreach ( $restricted_ips as $restricted_ip ) {

			$restricted_ip_obj = IPFactory::addressFromString( $restricted_ip );

			if ( ! is_null( $restricted_ip_obj ) && $ip_address->getComparableString() === $restricted_ip_obj->getComparableString() ) {
				$ip_is_in_a_restricted_range = true;
				break;
			}

			$restricted_range = IPFactory::rangeFromString( $restricted_ip );
			if ( ! is_null( $restricted_range ) && $restricted_range->contains( $ip_address ) ) {
				$ip_is_in_a_restricted_range = true;
				break;
			}
		}

		if ( $this->modifier_is( $data['modifier'], array( 'in' ) ) && $ip_is_in_a_restricted_range ) {
			return true;
		} elseif ( $this->modifier_is( $data['modifier'], array( 'not-in' ) ) && ! $ip_is_in_a_restricted_range ) {
			return true;
		}

		return false;
	}

	/**
	 *
	 *
	 * @param string $value Presumably an ip address or range.
	 *
	 * @return string|bool Returns the sanitized value or false which array_filter can then remove.
	 */
	public function sanitize_ip( $value ) {

		$value = trim( $value );

		$ip = IPFactory::addressFromString( $value );

		$range = IPFactory::rangeFromString( $value );

		if ( is_null( $ip ) && is_null( $range ) ) {

			return false;
		}

		return $value;
	}

	/**
	 * Validate, process and return condition fields.
	 *
	 * @param  array $posted_condition_data
	 * @return array
	 */
	public function process_admin_fields( $posted_condition_data ) {

		$processed_condition_data = array();

		if ( ! empty( $posted_condition_data['value'] ) ) {
			$processed_condition_data['condition_id'] = $this->id;
			$processed_condition_data['value']        = array_filter( array_map( array( $this, 'sanitize_ip' ), explode( "\r\n", $posted_condition_data['value'] ) ) );
			$processed_condition_data['modifier']     = stripslashes( $posted_condition_data['modifier'] );

			return $processed_condition_data;
		}

		return false;
	}

	/**
	 * Get ip-address condition content for global restrictions.
	 *
	 * @param  int   $index
	 * @param  int   $condition_index
	 * @param  array $condition_data
	 */
	public function get_admin_fields_html( $index, $condition_index, $condition_data ) {

		$modifier     = '';
		$ip_addresses = '';

		if ( ! empty( $condition_data['value'] ) && is_array( $condition_data['value'] ) ) {
			$ip_addresses = implode( "\n", $condition_data['value'] );
		}

		if ( ! empty( $condition_data['modifier'] ) ) {
			$modifier = $condition_data['modifier'];
		}

		?>
		<input type="hidden" name="restriction[<?php echo $index; ?>][conditions][<?php echo $condition_index; ?>][condition_id]" value="<?php echo $this->id; ?>" />
		<div class="condition_row_inner">
			<div class="condition_modifier">
				<div class="sw-enhanced-select">
					<select name="restriction[<?php echo $index; ?>][conditions][<?php echo $condition_index; ?>][modifier]">
						<option value="in" <?php selected( $modifier, 'in', true ); ?>><?php echo __( 'is', 'bh-wc-csp-condition-ip-address' ); ?></option>
						<option value="not-in" <?php selected( $modifier, 'not-in', true ); ?>><?php echo __( 'is not', 'bh-wc-csp-condition-ip-address' ); ?></option>
					</select>
				</div>
			</div>
			<div class="condition_value">
				<textarea class="input-text" name="restriction[<?php echo $index; ?>][conditions][<?php echo $condition_index; ?>][value]" placeholder="<?php _e( 'List 1 IP address or range per line&hellip;', 'bh-wc-csp-condition-ip-address' ); ?>" cols="25" rows="5"><?php echo $ip_addresses; ?></textarea>
				<span class="description"><?php _e( 'IPv4, IPv6 addresses, as well as IP ranges, in CIDR formats (like <code>::1/128</code> or <code>127.0.0.1/32</code>) and in pattern format (like <code>::*:*</code> or <code>127.0.*.*</code>) are supported.', 'bh-wc-csp-condition-ip-address' ); ?></span>
			</div>
		</div>
		<?php
	}
}
