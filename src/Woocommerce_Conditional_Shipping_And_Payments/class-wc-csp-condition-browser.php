<?php
/**
 * WC_CSP_Condition_Browser class
 *
 * @author   Brian Henry <brianhenryie@gmail.com>
 * @package  bh-wc-csp-condition-ip-address
 * @since    1.0.0
 */

namespace BrianHenryIE\WC_CSP_Condition_IP_Address\WooCommerce_Conditional_Shipping_And_Payments;

use BrianHenryIE\WC_CSP_Condition_IP_Address\Curl\Curl;
use BrianHenryIE\WC_CSP_Condition_IP_Address\IPLib\Factory as IPFactory;
use BrianHenryIE\WC_CSP_Condition_IP_Address\Usox\IpIntel\IpIntel;
use WC_CSP_Condition;
use WC_Geolocation;

/**
 * Check is the customer using a mobile browser or not.
 *
 * @class    WC_CSP_Condition_Browser
 * @version  1.0.0
 */
class WC_CSP_Condition_Browser extends WC_CSP_Condition {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id                             = 'browser';
		$this->title                          = __( 'Browser', 'bh-wc-csp-condition-ip-address' );
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
	 * @see wp_is_mobile()
	 *
	 * @param  array $data  { 'condition_id', 'value', 'modifier' }
	 * @param  array $args  (Optional arguments passed by restrictions.) Not in use here.
	 * @return boolean
	 */
	public function check_condition( $data, $args ) {

		// Empty conditions always apply (not evaluated).
		// if ( empty( $data['value'] ) ) {
		// return true;
		// }

		$is_mobile = wp_is_mobile();

		if ( $this->modifier_is( $data['modifier'], array( 'is-mobile' ) ) && $is_mobile ) {
			return true;
		} elseif ( $this->modifier_is( $data['modifier'], array( 'is-not-mobile' ) ) && ! $is_mobile ) {
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
	 * "Browser" – "Is mobile"
	 *
	 * @param  int   $index
	 * @param  int   $condition_index
	 * @param  array $condition_data
	 */
	public function get_admin_fields_html( $index, $condition_index, $condition_data ) {

		$modifier = '';

		if ( ! empty( $condition_data['modifier'] ) ) {
			$modifier = $condition_data['modifier'];
		}

		?>
		<input type="hidden" name="restriction[<?php echo $index; ?>][conditions][<?php echo $condition_index; ?>][condition_id]" value="<?php echo $this->id; ?>" />
		<div class="condition_row_inner">
		<div class="condition_modifier">
			<div class="sw-enhanced-select">
				<select name="restriction[<?php echo $index; ?>][conditions][<?php echo $condition_index; ?>][modifier]">
					<option value="is-mobile" <?php selected( $modifier, 'is-mobile', true ); ?>><?php echo __( 'Is mobile', 'bh-wc-csp-condition-ip-address' ); ?></option>
					<option value="is-not-mobile" <?php selected( $modifier, 'is-not-mobile', true ); ?>><?php echo __( 'Is not mobile', 'bh-wc-csp-condition-ip-address' ); ?></option>
				</select>
			</div>
		</div>
		<div class="condition_value condition--disabled"></div>
		</div>
		<?php
	}
}
