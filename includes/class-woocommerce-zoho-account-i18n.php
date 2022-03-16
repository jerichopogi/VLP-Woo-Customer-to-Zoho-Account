<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.vlpmedia.agency
 * @since      1.0.0
 *
 * @package    VLP_Woocommerce_Zoho_Account
 * @subpackage VLP_Woocommerce_Zoho_Account/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    VLP_Woocommerce_Zoho_Account
 * @subpackage VLP_Woocommerce_Zoho_Account/includes
 * @author     VLP Media Ltd
 */
class Woocommerce_Zoho_Account_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'vlp-wc-customer-to-zoho-account',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
