<?php 

class WZA_Zoho_setup_page {

    public static $wza_settings_page = 'woocommerce_zoho_account';
    public static $zoho_scopes = 'ZohoCRM.modules.ALL,ZohoCRM.settings.ALL,ZohoCRM.modules.leads.ALL,ZohoCRM.users.ALL,ZohoCRM.Files.CREATE';

    //Get Client Server
    public static function get_client_server() {
        return get_option('wza_zoho_client_server');
    }

    //Get Client ID
    public static function get_client_id() {
        return get_option('wza_zoho_client_id');
    }

    //Get Client Secret
    public static function get_client_secret() {
        return get_option('wza_zoho_client_secret');
    }

    //Get Redirect URI
    public static function get_redirect_uri() {
        return admin_url() . 'admin.php?page=' . self::$wza_settings_page;
    }


    //CURL 
    public static function zoho_curl($post_url, $post_fields, $post_header = false, $post_type = 'GET') {
        // setup cURL request
        $ch = curl_init();
    
        // do not return header information
        curl_setopt($ch, CURLOPT_HEADER, 0);
    
        // submit data in header if specified
        if (is_array($post_header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
        }
    
        // do not return status info
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
    
        // return data
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        // cancel ssl checks
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
        // if using GET, POST or PUT
        if ($post_type == 'POST') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        } else if ($post_type == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
        } else if ($post_type == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            if ($post_fields) {
                $post_url .= '?' . http_build_query($post_fields);
            }
        }
    
        // specified endpoint
        curl_setopt($ch, CURLOPT_URL, $post_url);
    
        // execute cURL request
        $response = curl_exec($ch);
    
        // return errors if any
        if($response === false) {
            $output = curl_error($ch);
        } else {
            $output = $response;
        }
    
        // close cURL handle
        curl_close($ch);
    
        // output
        return $output;
    }


    //Is token expired?
    public static function is_access_token_expired() {
        $time_now = time();
        $time_generated = isset(get_option('wza_zoho_access_token')['time_generated']) ? get_option('wza_zoho_access_token')['time_generated'] : false;
        $time_expires_in = isset(get_option('wza_zoho_access_token')['expires_in']) ? get_option('wza_zoho_access_token')['expires_in'] : false;
        
        if($time_generated && $time_expires_in) {
            $time_expiry = $time_generated + $time_expires_in;
            
            if($time_now > $time_expiry) {
                return true;
            }
        }
        
        return false;
    }


    //Custom Log for refresh token
    public static function custom_log($log_msg) {
        /** Disable logging **/
        return false;
        /** Disable logging **/
        
        $log_filename = dirname(__FILE__);
    	// $log_msg = var_export($log_msg, true);
        if (!file_exists($log_filename)) {
            mkdir($log_filename, 0777, true);
        }
        $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
        file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
    }

    
    //Get Refresh Token
    public static function refresh_access_token() {
        $client_id = self::get_client_id();
        $client_secret = self::get_client_secret();
        $refresh_token = get_option('wza_zoho_refresh_token');
        
        if($client_id && $client_secret && $refresh_token) {
            $post_params = array(
                'refresh_token' => $refresh_token,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'grant_type' => 'refresh_token',
            );
            
            $authorization_url = self::get_zoho_server_url() . '/oauth/v2/token';
    		
            $response_json = self::zoho_curl($authorization_url, $post_params, false, 'POST');
    		$response_arr = json_decode($response_json, true);
            
    		if($response_arr) {
    		    $response_arr['time_generated'] = time();
    		}
    		
    		self::custom_log(current_time('Y-m-d H:i:s'));
    		self::custom_log(var_export($response_arr, true));
    		self::custom_log('===========================================================');
    		
    		return update_option('wza_zoho_access_token', $response_arr);
        } else {
    		//update_option('wza_zoho_access_token', '');
    		return false;
        }
    }


    //Get Access Token
    public static function get_access_token($all = false) {
        $access_token = get_option('wza_zoho_access_token');
        
        if( isset($access_token['access_token']) ) {
            if(self::is_access_token_expired()) {
                self::refresh_access_token();
            }
        }
        
        if($all) return $access_token;
        
        if( isset($access_token['access_token']) ) {
            $access_token = $access_token['access_token'] ?? false;
            
            return $access_token;
        }
        
        return false;
    }


    //Get Server URL
    public static function get_zoho_server_url() {
	    $server = self::get_client_server();
	    $url = 'https://accounts.zoho.' . $server;
	    
	    return $url;
    }


    //Page submit from admin page display
    public static function zoho_setup_page_submit() {
        if(isset($_POST['wza_submit'])) {
            $client_id = $_POST['wza_zoho_client_id'];
            $client_secret = $_POST['wza_zoho_client_secret'];
            $client_server = $_POST['wza_zoho_client_server'];
            
            update_option('wza_zoho_client_id', $client_id);
            update_option('wza_zoho_client_secret', $client_secret);
            update_option('wza_zoho_client_server', $client_server);
        }
        
        if(isset($_POST['wza_authorize'])) {
            $client_id = $_POST['wza_zoho_client_id'];
            $client_secret = $_POST['wza_zoho_client_secret'];
            $client_server = $_POST['wza_zoho_client_server'];
            
            update_option('wza_zoho_client_id', $client_id);
            update_option('wza_zoho_client_secret', $client_secret);
            update_option('wza_zoho_client_server', $client_server);
            
            $authorization_url = self::get_authorization_url();
            if($authorization_url) {
                wp_redirect($authorization_url);
                die();
            }
        }
        
        if(isset($_GET['code']) && isset($_GET['page']) && $_GET['page'] == self::$wza_settings_page) {
            self::generate_access_token($_GET['code'], $_GET['accounts-server']);
            wp_redirect(self::get_redirect_uri());
            die();
        }
    }


    //Get Auth URL
    public static function get_authorization_url() {
        $client_id = self::get_client_id();
        $client_secret = self::get_client_secret();
        $redirect_uri = self::get_redirect_uri();
        $scopes = self::$zoho_scopes;
        $authorization_url = false;
        
        /* Check if access token expired and not regenerated */
        if($client_id && $client_secret && $redirect_uri) {
    		// Array to post
    		$get_params = array(
    		    'client_id' => $client_id,
    		    'redirect_uri' => $redirect_uri,
    		    'response_type' => 'code',
    		    'scope' => $scopes,
                'access_type' => 'offline',
                'prompt' => 'consent'
    		);
    		
    		$authorization_url = self::get_zoho_server_url() . '/oauth/v2/auth';
    		
    		return $authorization_url . '?' . http_build_query($get_params);
        }
        
        return $authorization_url;
    }


    //Generate Access Token
    public static function generate_access_token($code) {
        $client_id = self::get_client_id();
        $client_secret = self::get_client_secret();
        $redirect_uri = self::get_redirect_uri();
        $scopes = self::$zoho_scopes;
        
        if($client_id && $client_secret && $redirect_uri && $code) {
            $post_params = array(
                'code' => $code,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $redirect_uri,
                'scope' => $scopes,
            );
            
    		$authorization_url = self::get_zoho_server_url() . '/oauth/v2/token';
    		
            $response_json = self::zoho_curl($authorization_url, $post_params, false, 'POST');
    		$response_arr = json_decode($response_json, true);
    		
    		if($response_arr) {
    		    $response_arr['time_generated'] = time();
    		}
    		
    		update_option('wza_zoho_refresh_token', $response_arr['refresh_token']);
    		return update_option('wza_zoho_access_token', $response_arr);
        }
        
        return false;
    }


    //Create account on woocommerce checkout
    public static function create_account($lead_data, $module = 'Accounts') {
        $access_token = self::get_access_token();
        $account_fname = $_POST['billing_first_name'];
        $account_lname = $_POST['billing_last_name'];
        $account_name = $account_fname .' '. $account_lname;
        $account_site = $_POST['billing_company'];
        $phone = $_POST['billing_phone'];
        $website = get_site_url();
        $billing_street1 = $_POST['billing_address_1'];
        $billing_street2 = $_POST['billing_address_2'];
        $billing_street = $billing_street1 .' '. $billing_street2;
        $billing_city = $_POST['billing_city'];
        $billing_state = $_POST['billing_state'];
        $billing_code = $_POST['billing_postcode'];
        $billing_country = $_POST['billing_country'];
        $description = $_POST['order_comments'];
        
        if($access_token) {
            $api_url = 'https://www.zohoapis.'.self::get_client_server().'/crm/v2/'.$module;
            $header_data = array('Authorization: Zoho-oauthtoken ' . $access_token);

            $post_data = [
                'data' => [
                    [
                        'Account_Name'   => $account_name,
                        'Account_Site'   => $account_site . '',
                        'Account_Type'   => 'Customer',
                        'Phone'          => $phone,
                        'Website'        => $website,
                        'Billing_Street' => $billing_street,
                        'Billing_City'   => $billing_city,
                        'Billing_State'  => $billing_state,
                        'Billing_Code'   => $billing_code,
                        'Billing_Country'=> $billing_country,
                        'Shipping_Street' => $billing_street,
                        'Shipping_City'   => $billing_city,
                        'Shipping_State'  => $billing_state,
                        'Shipping_Code'   => $billing_code,
                        'Shipping_Country'=> $billing_country,
                        'Description'     => $description,
                    ]
                ]
            ];

            $params = json_encode( $post_data );
            $response_json = self::zoho_curl($api_url, $params, $header_data, 'POST');
    		$response_arr = json_decode($response_json, true);

            // var_dump($response_arr);
    		return $response_arr;
        }
        
        return false;
    }
    
}