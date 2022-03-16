<?php 

class WZA_Account_Menu {

    public static function wza_add_menu(){
        add_menu_page(
            __( 'WooCommerce Zoho Account', WZA_NAME ),
			__( 'WooCommerce Zoho Account', WZA_NAME ),
			'manage_options',
			'woocommerce_zoho_account',
			'WZA_Account_Menu::wza_setup_page',
			'',
			89,
        );
    }

    public static function wza_setup_page(){
        require_once( WZA_ADMIN_PARTIALS . '/woocommerce-zoho-account-admin-display.php' );
    }

}