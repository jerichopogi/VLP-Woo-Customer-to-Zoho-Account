<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.vlpmedia.agency
 * @since             1.0.0
 * @package           VLP_WC_Customer_to_Zoho_Account
 *
 * @wordpress-plugin
 * Plugin Name:       VLP Create Zoho Account for WooCommerce Customers
 * Description:       This plugin create accounts from WooCommerce checkout account creation to Zoho Accounts through Zoho API
 * Version:           1.0.0
 * Author:            VLP Media Ltd
 * Author URI:        https://www.vlpmedia.agency
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vlp-wc-customer-to-zoho-account
 * Domain Path:       /languages
 * Requires at least: 4.6
 * Tested up to:      5.9
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WZA_VERSION', '1.0.0' );
define( 'WZA_PLUGIN', 'vlp-wc-customer-to-zoho-account' );
define( 'WZA_NAME', 'VLP Create Zoho Account for WooCommerce Customers' );
define( 'WZA_DIR', __DIR__ );
define( 'WZA_PATH', dirname( __FILE__ ) );
define( 'WZA_PUBLIC_DIR', __DIR__ . 'public/' );
define( 'WZA_ADMIN_DIR', __DIR__ . 'admin/' );
define( 'WZA_PUBLIC_URI', plugin_dir_url( __FILE__ ) . 'public/' );
define( 'WZA_ADMIN_URI', plugin_dir_url( __FILE__ ) . 'admin/' );
define( 'WZA_PUBLIC_PARTIALS', __DIR__ . '/public/partials/' );
define( 'WZA_ADMIN_PARTIALS', __DIR__ . '/admin/partials/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-zoho-account-activator.php
 */
function activate_woocommerce_zoho_account() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-zoho-account-activator.php';
	Woocommerce_Zoho_Account_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-zoho-account-deactivator.php
 */
function deactivate_woocommerce_zoho_account() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-zoho-account-deactivator.php';
	Woocommerce_Zoho_Account_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_zoho_account' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_zoho_account' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-zoho-account.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_zoho_account() {

	$plugin = new Woocommerce_Zoho_Account();
	$plugin->run();

}
run_woocommerce_zoho_account();
