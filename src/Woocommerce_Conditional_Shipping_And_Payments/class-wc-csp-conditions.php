<?php

namespace BH_WC_CSP_Condition_IP_Address\Woocommerce_Conditional_Shipping_And_Payments;

class WC_CSP_Conditions {

	/**
	 * Register the IP Address condition with the existing set of WC CSP conditions.
	 *
	 * @hooked woocommerce_csp_conditions
	 *
	 * @param string[] $conditions String array of classnames which will be instantiated.
	 *
	 * @return string[]
	 */
	public function add_condition( $conditions ): array {

		$conditions[] = WC_CSP_Condition_IP_Address::class;

		return $conditions;
	}

}