<?php 

class Kasha_Sms_Dashboard {

	/**
	* Default method - Inital method.
	*/
	public function kasha_sms_inital_function() {
		$sms_templates = $this->kasha_template_fetch();
		$all_users = get_users( array( 'fields' => array( 'ID' ) ) );
		$kasha_sms_short_codes = $this->kasha_sms_short_codes();
		$operational_sms_event = $this->kasha_operational_sms_event();
		$all_created_contact_groups = get_option( 'contact_list_of_kasha' );
		if( isset( $_POST ) && !empty( $_POST ) ) {
			if( isset( $_POST['kasha_selected_template_for_operational_sms'] ) ) {
				if( isset( $_POST['kasha_template_update'] ) ) {
					foreach ( $sms_templates as $sms_templates_key => $sms_templates_value ) {
						if( $_POST['kasha_selected_template_for_operational_sms'] == $sms_templates_value->template_id ) {
							$search = "/\[([^\]]*)\]/";
							$replace = "[]";
							$sms_content_change = false;
							$sms_content = $sms_templates_value->template_data;
							$sms_content = preg_replace( $search, $replace, $sms_content );
							foreach ( $_POST['kasha_replace'] as $key => $value ) {
								if( $value != 'None' && !empty( $value ) ) {
									$value = str_replace('[', '(', $value);
									$value = str_replace(']', ')', $value);
									$sms_content = preg_replace( "/\[([^\]]*)\]/", $value, $sms_content, 1 );
									$sms_content_change = true;
								}
							}
						}
					}
				}
				
				if( $sms_content_change ) {
					$sms_content = str_replace('(', '[', $sms_content);
					$sms_content = str_replace(')', ']', $sms_content);
					foreach ( $operational_sms_event as $value ) {
						$value = str_replace(' ', '_', $value );
						if( array_key_exists ( $value, $_POST ) ) {
							update_option( $value, $sms_content );
							if( isset( $_POST['kasha_sms_checkbox_actiavate'] ) ) { 
								update_option( $value.'_status',$_POST['kasha_sms_checkbox_actiavate']);
							} else {
								update_option( $value.'_status','off');
							}
						}
					}
					$message = 'SMS Template Updated Successfully.';
					$message_color = 'green';
				} else {
					foreach ( $operational_sms_event as $value ) {
						$value = str_replace(' ', '_', $value );
						if( array_key_exists ( $value, $_POST ) ) {
							if( isset( $_POST['kasha_sms_checkbox_actiavate'] ) ) { 
								update_option( $value.'_status',$_POST['kasha_sms_checkbox_actiavate']);
							} else {
								update_option( $value.'_status','off');
							}
						}
					}
					if (strpos($sms_content, '[') !== true) {
				    	update_option( $value, $sms_content );
					}
					$message = 'SMS Template Updated Successfully.';
					$message_color = 'green';
				}
			}

			if( isset( $_POST['kasha_send_sms_promotional_type'] ) ) {
				foreach ( $sms_templates as $sms_templates_key => $sms_templates_value ) {
					if( $_POST['kasha_selected_template_for_promotional_sms'] == $sms_templates_value->template_id ) {
						$search = "/\[([^\]]*)\]/";
						$replace = "[]";
						$sms_content = $sms_templates_value->template_data;
						$sms_content = preg_replace( $search, $replace, $sms_content );
						foreach ( $_POST['kasha_replace'] as $key => $value ) {
							$sms_content = preg_replace( "/\[([^\]]*)\]/", $value, $sms_content, 1 );
						}
					}
				}
				$send_list = array();

				if( isset( $_FILES['kasha_promotional_csv']['tmp_name'] ) && !empty( $_FILES['kasha_promotional_csv']['tmp_name'] ) ){
					$handle = fopen( $_FILES['kasha_promotional_csv']['tmp_name'], "r" );
					$headers = fgetcsv( $handle, 1000, "," );

					if( !empty( $headers ) ) {
					 
						while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) 
						{
							$data[0] = sanitize_text_field($data[0]);
						 	if( isset( $_POST['kasha_promotional_sms'] ) && !empty( $_POST['kasha_promotional_sms'] ) ) {
						 		array_push( $send_list, $data[0] );
						 	}else {
						 		$send_list[] = $data[0];
						 	}
						}
						fclose( $handle );
					}
				}

				if( isset( $_POST['kasha_promotional_contact_list_manual'] ) ){
					$manu_contact_list = preg_replace( '/[^A-Za-z0-9\,]/', '', $_POST['kasha_promotional_contact_list_manual'] );
					$manu_contact_list = explode( ',', $manu_contact_list );
					foreach ( $manu_contact_list as $key => $value ) {
						$send_list[] = $value;
					}
				}

				if( isset( $_POST['promotional_sms_group'] ) ) {
					$to_list_from_group = $all_created_contact_groups[$_POST['promotional_sms_group']];
					foreach ( $to_list_from_group as $to_list_from_group_key => $to_list_from_group_value ) {
						$wp_user_billing_phone_number = get_user_meta( $to_list_from_group_value, 'billing_phone', true );
						if( !empty( $wp_user_billing_phone_number ) ) {
							$send_list[] = $wp_user_billing_phone_number;
						}else{
							$send_list[] = $to_list_from_group_value;
						}
					}
				}

				$endpoint = KASHA_SMS_API_URL.'create/sms'; 
				if( !empty( $sms_content ) ) {
					foreach ( $send_list as $send_list_key => $send_list_value ) {
						if( !empty( $send_list_value ) ) {
							$admin_customer [] = array(
				            	'to_phone' => $send_list_value,
				                'message' => $sms_content,
				                'is_auto' => true,
				                'sms_type' => 2,
				                'provider' => NULL
				            );
						}
					}
					$response = $this->kasha_promotional_sms_send_api( $admin_customer, $endpoint );
					$message = 'Promotional SMS sent successfully';
					$message_color = 'green';
			    }
			}

			if( isset( $_POST['kasha_blacklist_add'] ) ) {
		    	$send_list = array();
				if( isset( $_FILES['kasha_blacklist_csv']['tmp_name'] ) && !empty( $_FILES['kasha_blacklist_csv']['tmp_name'] ) ){
					$handle = fopen( $_FILES['kasha_blacklist_csv']['tmp_name'], "r" );
					$headers = fgetcsv( $handle, 1000, "," );
					if( !empty( $headers ) ) {
					 
						while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) 
						{
							$data[0] = sanitize_text_field($data[0]);
						 	if( isset( $_POST['kasha_blacklist_csv'] ) && !empty( $_POST['kasha_blacklist_csv'] ) ) {
						 		array_push( $send_list, $data[0] );
						 	}else {
						 		$send_list[] = $data[0];
						 	}
						}
						fclose( $handle );
					}
				}

				if( isset( $_POST['kasha_blacklist_contact_list_manual'] ) ){
					$manu_contact_list = preg_replace( '/[^A-Za-z0-9\,]/', '', $_POST['kasha_blacklist_contact_list_manual'] );
					$manu_contact_list = explode( ',', $manu_contact_list );
					foreach ( $manu_contact_list as $key => $value ) {
						$send_list[] = $value;

					}
				}

				if( !empty( $send_list ) ) {
					$hit_bulk_black_list_api = $this->kasha_sms_blacklist_bluk_create( $send_list );
					$message = 'Phonenumbers are added to the blacklist successfully';
					$message_color = 'green';
				}
		    }

		    if ( isset( $_POST['white_list_numbers'] ) ) {
		    	if( !empty( $_POST['kasha_remove_number_to_block_list'] ) ) {
		    		$send_list = $_POST['kasha_remove_number_to_block_list'];
		    		$hit_bulk_white_list_api = $this->kasha_sms_white_list_bulk_create( $send_list ); 
		    		$message = 'Phonenumbers are removed from the blacklist successfully';
		    		$message_color = 'green';
		    	}
		    }

			if( isset( $_POST['group_name'] ) && isset( $_POST['kasha_promotional_sms'] ) ) {
				$_POST['group_name'] = sanitize_text_field( $_POST['group_name'] );
				$old_contact_groups = get_option( 'contact_list_of_kasha' );
				if( isset( $old_contact_groups ) && !empty( $old_contact_groups ) ) {
					$old_contact_groups[$_POST['group_name']] = $_POST['kasha_promotional_sms'];
					update_option( 'contact_list_of_kasha' , $old_contact_groups );
				}else{
					$new_contact_group = array( $_POST['group_name'] => $_POST['kasha_promotional_sms'] );
				}	
			}

			if( isset( $_POST['kasha_create_new_contact_list'] ) ) {

				if( isset( $_FILES['kasha_csv']['tmp_name'] ) && !empty($_FILES['kasha_csv']['tmp_name'] ) ) { 
					$handle = fopen( $_FILES['kasha_csv']['tmp_name'], "r" );
					$headers = fgetcsv( $handle, 1000, "," );

					if( !empty( $headers ) ) {
					 
						while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) 
						{
						 	if( isset( $_POST['kasha_promotional_sms'] ) && !empty( $_POST['kasha_promotional_sms'] ) ) {
						 		array_push( $_POST['kasha_promotional_sms'], $data[0] );
						 	}else{
						 		$_POST['kasha_promotional_sms'][] = $data[0];
						 	}
						}
						fclose( $handle );
					}
				}

				if( isset( $_POST['kasha_contact_list_manual'] ) ) {
					$manual_contact_list = preg_replace( '/[^A-Za-z0-9\,]/', '', $_POST['kasha_contact_list_manual'] );
					$manual_contact_list = explode( ',', $manual_contact_list );
					foreach ( $manual_contact_list as $manual_contact_list_key => $manual_contact_list_value ) {
						$_POST['kasha_promotional_sms'][] = $manual_contact_list_value;
					}
				}

				$old_contact_groups = get_option( 'contact_list_of_kasha' );
				if( isset( $old_contact_groups ) && !empty( $old_contact_groups ) ) {
					$old_contact_groups[$_POST['group_name']] = $_POST['kasha_promotional_sms'];
					update_option( 'contact_list_of_kasha', $old_contact_groups );
					$message = 'Contact Group created Successfully.';
					$message_color = 'green';
				}else{
					$new_contact_group = array( $_POST['group_name'] => $_POST['kasha_promotional_sms'] );
					update_option( 'contact_list_of_kasha',$new_contact_group );
					$message = 'Contact Group created Successfully.';
					$message_color = 'green';
				}
			}
			$history_data = $this->kasha_sms_history();
			$black_list_data = $this-> kasha_sms_blacklist();
			$history_data = $this->kasha_sms_history();
			$all_created_contact_groups = get_option( 'contact_list_of_kasha' );
		}else {
			$black_list_data = $this-> kasha_sms_blacklist();
			$history_data = $this->kasha_sms_history();
		}
		include( KASHA_SMS_INCLUDE_PATH . "/kasha_sms_dashboard.php" );
	}

	/**
	* To send promotional type sms.
	* @param      array    $body    contain 'to_phone','message','is_auto','sms_type','provider'.
	* @param      string    $endpoint    API endpoint to send sms.
	* API reference https://sms-staging.kasha.co.ke/redoc/#operation/create_sms_create
	*/
	public function kasha_promotional_sms_send_api( $body = '', $endpoint ) {
		$args = array(
            'httpversion' => '1.1',
            'blocking'    => true,
            'headers'     => array(
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Token '.KASHA_SMS_TOKEN,
            ),
            'body' => json_encode ( $body )
        );
        $response = wp_remote_post( $endpoint, $args );
        return $response;
	}

	/**
	* All available sms event.
	*/
	public function kasha_operational_sms_event() {
		$operational_sms_event =  array( 'New order',
			'Back order made but product is out of stock',
			'Payment complete',
			'Order status change pending payment',
			'Order status change processing',
			'Order status change verified',
			'Order status change dispatched',
			'Order status change on hold',
			'Order status change completed',
			'Order status change cancelled',
			'Order status change refunded',
			'Order status change failed',
			'New customer'
		);
		return $operational_sms_event;
	}
	
	/**
	* All available shortcodes.
	*/
	public function kasha_sms_short_codes() {
		$kasha_sms_short_codes = array(
			'Shop'=>array(
				'',
				'[shop_email]',
				'[shop_name]',
				'[shop_domain]',
				'[shop_currency]'
			),
			'Order'=>array(
				'[order_id]',
				'[long_order_id]',
				'[order_ref]',
				'[order_currency]',
				'[order_payment]',
				'[order_date1]',
				'[order_date2]',
				'[order_date3]',
				'[order_date4]',
				'[order_date5]',
				'[order_date6]',
				'[order_date7]',
				'[order_time]',
				'[order_time1]',
				'[order_total_paid]'
			),
			'Customer'=>array(
				'[username]',
				'[customer_id]',
				'[customer_message]',
				'[customer_company]',
				'[customer_lastname]',
				'[customer_firstname]',
				'[customer_lastname_vokativ]',
				'[customer_firstname_vokativ]',
				'[customer_address]',
				'[customer_postcode]',
				'[customer_city]',
				'[customer_country_id]',
				'[customer_state]',
				'[customer_country]',
				'[customer_phone]',
				'[customer_mobile]',
				'[customer_invoice_company]',
				'[customer_invoice_lastname]',
				'[customer_invoice_firstname]',
				'[customer_invoice_firstname_vokativ]',
				'[customer_invoice_lastname_vokativ]',
				'[customer_invoice_address]',
				'[customer_invoice_postcode]',
				'[customer_invoice_city]',
				'[customer_invoice_country_id]',
				'[customer_invoice_state]',
				'[customer_invoice_country]',
				'[customer_invoice_phone]',
				'[customer_invoice_mobile]',
				'[customer_email]',
				'[customer_name]'
			)
		);
		return $kasha_sms_short_codes; 
	}


	/**
	* To fetch sms templates from the API server..
	* API reference https://sms-staging.kasha.co.ke/redoc/#operation/templates_list
	*/
	public function kasha_template_fetch() {
		$endpoint_for_templates = KASHA_SMS_API_URL.'templates';
		$args = array(
	        'httpversion' => '1.1',
	        'blocking'    => true,
	        'headers'     => array(
	            'Content-Type'  => 'application/json',
	            'Accept'        => 'application/json',
	            'Authorization' => 'Token '.KASHA_SMS_TOKEN,
	        ),
	        'body' => array(), 
	    );
	    $response_for_templates = wp_remote_get( $endpoint_for_templates, $args );

	    if( isset( $response_for_templates->errors ) ) {
	    	$this->server_down( $endpoint_for_templates );
	    }
	    if( isset( $response_for_templates['body'] ) && !empty( $response_for_templates['body'] ) ) {
	    	return( json_decode( $response_for_templates['body'] ) );
	    }
	}

	/**
	* To display serverdown issue.
	* @param      string    $endpoint    API endpoint which makes serverdown.
	*/
	public function server_down( $endpoint='' ) {
		echo '</br> <b style="color:red"> Server is down. Please try after sometime. </b></br>';
		echo 'API failed here : '.$endpoint;
    	die();
	}

	/**
	* To add bulk phonenumbers to the blacklist.
	* @param      array    $send_list    phonenumbers.
	* API reference https://sms-staging.kasha.co.ke/redoc/#operation/blacklist_bulk_create
	*/
	public function kasha_sms_blacklist_bluk_create( $send_list ) {
		$endpoint_for_blacklist = KASHA_SMS_API_URL.'blacklist/bulk/';

	    $args = array(
	        'httpversion' => '1.1',
	        'blocking'    => true,
	        'headers'     => array(
	            'Content-Type'  => 'application/json',
	            'Accept'        => 'application/json',
	            'Authorization' => 'Token '.KASHA_SMS_TOKEN,
	        ),
	        'body' => json_encode ( array('phonenumbers' =>  $send_list ,'smstypes'=> array(2) ) ),
	    );
		$black_list_data_response = wp_remote_post( $endpoint_for_blacklist, $args );
		if( isset( $black_list_data_response->errors ) ) {
	    	$this->server_down(  $endpoint_for_blacklist );
	    }	
	    return true;
	}


	/**
	* To remove bulk phonenumbers from the blacklist.
	* @param      array    $send_list    phonenumbers.
	* API reference https://sms-staging.kasha.co.ke/redoc/#operation/whiltelist_bulk_create
	*/
	public function kasha_sms_white_list_bulk_create( $send_list = array() ) {
		$endpoint_for_whitelist = KASHA_SMS_API_URL.'whitelist/bulk/';
	    $args = array(
	        'httpversion' => '1.1',
	        'blocking'    => true,
	        'headers'     => array(
	            'Content-Type'  => 'application/json',
	            'Accept'        => 'application/json',
	            'Authorization' => 'Token '.KASHA_SMS_TOKEN,
	        ),
	        'body' => json_encode ( array('phonenumbers' => $send_list ) ),
	    );
		$white_list_data_response = wp_remote_post( $endpoint_for_whitelist, $args );
		if( isset( $white_list_data_response->errors ) ) {
	    	$this->server_down( $endpoint_for_whitelist );
	    }
	    return true;
	}

	/**
	* To fetch all blacklist phonenumbers from the server
	* Default smstype as of now 2, But in the future if we expand the service it will support 4 also..
	* API reference https://sms-staging.kasha.co.ke/redoc/#operation/blacklist_list
	*/
    public function kasha_sms_blacklist() {
    	$endpoint_for_blacklist = KASHA_SMS_API_URL.'blacklist/';
	    $args = array(
	        'httpversion' => '1.1',
	        'blocking'    => true,
	        'headers'     => array(
	            'Content-Type'  => 'application/json',
	            'Accept'        => 'application/json',
	            'Authorization' => 'Token '.KASHA_SMS_TOKEN,
	        ),
	        'body' => json_encode(array('list'=>array(2) )),
	    );
	    $black_list_data = wp_remote_get( $endpoint_for_blacklist, $args );

	    if( isset( $black_list_data->errors ) ) {
	    	$this->server_down( $endpoint_for_blacklist );
	    }
	    $black_list_data = json_decode( $black_list_data['body'] );
	    return $black_list_data;
    }

    /**
	* To fetch all the history from the server
	* Default period will be last 30 days.
	* API reference https://sms-staging.kasha.co.ke/redoc/#tag/history
	*/
    public function kasha_sms_history() {
    	$endpoint_for_history = KASHA_SMS_API_URL.'history/';
		$args = array(
	        'httpversion' => '1.1',
	        'blocking'    => true,
	        'headers'     => array(
	            'Content-Type'  => 'application/json',
	            'Accept'        => 'application/json',
	            'Authorization' => 'Token '.KASHA_SMS_TOKEN,
	        ),
	        'body' => json_encode ( array(
	        	'date_from' => date('Y-m-d', strtotime('today - 30 days')),
	            )
	        )
	    );

	    $response_of_history = wp_remote_post( $endpoint_for_history, $args );

	    if( isset( $response_of_history->errors ) ) {
	    	$this->server_down( $endpoint_for_history );
	    }

	    if( isset($response_of_history['body']) && is_array( json_decode( $response_of_history['body'] ) ) ) {
	    	$history_data = array_reverse( json_decode( $response_of_history['body'] ) );
	    	return $history_data;
	    }else{
	    	return array();
	    }
    }
}

$kasha_sms_class_call = new Kasha_Sms_Dashboard();
$kasha_sms_class_call->kasha_sms_inital_function();