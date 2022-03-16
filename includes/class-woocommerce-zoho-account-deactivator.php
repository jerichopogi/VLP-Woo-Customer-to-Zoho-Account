<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://vlpmedia.agency
 * @since      1.0.0
 *
 * @package    VLP_Woocommerce_Zoho_Account
 * @subpackage VLP_Woocommerce_Zoho_Account/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    VLP_Woocommerce_Zoho_Account
 * @subpackage VLP_Woocommerce_Zoho_Account/includes
 * @author     VLP Media Ltd
 */
class Woocommerce_Zoho_Account_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$fieldsData = array( 'wza_zoho_client_server', 'wza_zoho_client_id', 'wza_zoho_client_secret', 'wza_zoho_access_token', 'wza_zoho_refresh_token' ); // etc
 
		// Clear up our fields
		foreach ( $fieldsData as $fieldData ) {
			delete_option( $fieldData );
		}
	}

}
