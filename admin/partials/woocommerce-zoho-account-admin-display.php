<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://vlpmedia.agency
 * @since      1.0.0
 *
 * @package    VLP_Woocommerce_Zoho_Account
 * @subpackage VLP_Woocommerce_Zoho_Account/admin/partials
 */
?>
<div class="wza-form-container">
    <h1>WooCommerce Zoho Account</h1>
    <div class="wza-instructions">
        <ol>
            <li>Go to <a href="https://api-console.zoho.com" target="_blank">https://api-console.zoho.com</a> and create a server-based application.</li>
            <li>Enter your client name (can be freely assigned).</li>
            <li>Enter your Home URL</li>
            <li>From the Zoho Plugin settings page, copy the Authorization Redirect URI and click create.</li>
            <li>Copy the Client ID and the Client Secret into the plugin settings page</li>
            <li>Click save.</li>
            <li>Click Authorize.</li>
            <li>Now you are connected to Zoho!!!</li>
        </ol>
    </div>
    <form method="post" action="<?php echo esc_attr($_SERVER["REQUEST_URI"]); ?>">
    <?php wp_nonce_field('wza_settings_nonce'); ?>
    <div class="page">
        <div class="page__content">
            <div class="form">
                <div class="form__row">
                    <label class="form--label">Zoho Account Server</label>
                    <select name="wza_zoho_client_server">
                        <option value="com" <?php echo esc_attr(WZA_Zoho_setup_page::get_client_server() == 'com' ? 'selected': ''); ?>>COM</option>
                        <option value="eu" <?php echo esc_attr(WZA_Zoho_setup_page::get_client_server() == 'eu' ? 'selected': ''); ?>>EU</option>
                    </select>
                </div>
                <div class="form__row">
                    <label class="form--label">Client Id</label>
                    <input type="text" value="<?php echo esc_attr(WZA_Zoho_setup_page::get_client_id());?>" name="wza_zoho_client_id" class="form--input" id="wza_zoho_client_id" required/> <i class="form__row-info">Created in the developer console</i> 
                </div>
                <div class="form__row">
                    <label class="form--label">Client Secret</label>
                    <input type="text" value="<?php echo esc_attr(WZA_Zoho_setup_page::get_client_secret()); ?>" name="wza_zoho_client_secret" class="form--input" id="wza_zoho_client_secret"  required/> <i class="form__row-info">Created in the developer console</i> 
                </div>
                <div class="form__row">
                    <label class="form--label">Authorization Redirect URI</label>
                    <input type="text" id="wza_authorization_uri" readonly="readonly" name="wza_authorization_uri" class="form--input" value="<?php echo esc_attr(WZA_Zoho_setup_page::get_redirect_uri()); ?>" class="regular-text" readonly="readonly" required/> <i class="form__row-info">Copy this URL into Redirect URI field of your Client Id creation</i> 
                </div>
                <div class="form__row">
                    <label class="form--label">Access Token</label>
                    <input type="password" readonly="readonly" id="_access_token" class="form--input" value="<?php echo esc_attr(WZA_Zoho_setup_page::get_access_token()) ?>" class="regular-text" readonly="readonly" required/><span toggle="#access-token-field" class="fa fa-fw fa-eye field_icon toggle-access-token"></span>
                </div>
                <div class="form__row form__row-btn">
                    <input type="submit" name="wza_submit" id="wza_submit" class="btn button button-vlp-primary" value="Save"/> 
                    <input type="submit" name="wza_authorize" id="wza_authorize" class="btn button button-vlp-primary" value="Authorize"/> 
                </div>
            </div>
        </div>
    </div>
    </form>
</div>