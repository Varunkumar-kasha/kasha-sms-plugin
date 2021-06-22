<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       kasha.co.ke
 * @since      1.0.0
 *
 * @package    Kasha_sms
 * @subpackage Kasha_sms/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kasha_sms
 * @subpackage Kasha_sms/admin
 * @author     Varunkumar <v-varunkumar@kasha.co>
 */
class Kasha_sms_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'admin_menu', array($this, 'kasha_sms_menu'), 1);
		add_action( 'woocommerce_new_order', array($this, 'kasha_send_sms_on_new_order'),  1, 1  );
		add_action( 'woocommerce_order_status_changed', array( $this, 'kasha_order_status_changed' ) );
		add_action( 'user_register', array( $this, 'kasha_new_customer_register'), 10, 1 );
	}

	public function kasha_new_customer_register( $user_id ) {
		// Template is not used in the initial version.
	}

	/**
	* Woocommerce method call back ( action -  woocommerce_order_status_changed ).
	* @param      int     $order_id   Woocommerce order id.
	* @param      string    $checkout    Checkout status.
	*/
	public function kasha_order_status_changed( $order_id, $checkout=null ) {
	   	global $woocommerce;
	   	$order = $this->kasha_get_order_data( $order_id );
	   	if( $order->get_status() == 'on-hold' ) {
	   		$sms_content = str_replace( ' ', '_', 'Order status change on hold' );
			$sms_content_of_operation_event = get_option( $sms_content );
			$sms_template_status = get_option( $sms_content.'_status' );
			if( $sms_template_status == 'on' ) {
	   			$this->kasha_sms_api_function($order_id,$sms_content_of_operation_event);
	   		}
	   	}
	   if( $order->get_status() == 'pending' ) {
	   		$sms_content = str_replace( ' ', '_', 'Order status change pending payment');
	   		$sms_content_of_operation_event = get_option( $sms_content );
			$sms_template_status = get_option( $sms_content.'_status' );
			if( $sms_template_status == 'on' ) {
	   			$this->kasha_sms_api_function($order_id,$sms_content_of_operation_event);
	   		}
	   	}
	   	if( $order->get_status() == 'failed' ) {
	   		$sms_content = str_replace( ' ', '_', 'Order status change failed');
	   		$sms_content_of_operation_event = get_option( $sms_content );
			$sms_template_status = get_option( $sms_content.'_status' );
			if( $sms_template_status == 'on' ) {
	   			$this->kasha_sms_api_function($order_id,$sms_content_of_operation_event);
	   		}
	   	}
	   	if( $order->get_status() == 'processing' ) {
	   		$sms_content = str_replace( ' ', '_', 'Order status change processing');
	   		$sms_content_of_operation_event = get_option( $sms_content );
			$sms_template_status = get_option( $sms_content.'_status' );
			if( $sms_template_status == 'on' ) {
	   			$this->kasha_sms_api_function($order_id,$sms_content_of_operation_event);
	   		}
	   	}
	   	if( $order->get_status() == 'completed' ) {
	   		$sms_content = str_replace( ' ', '_', 'Order status change completed');
	   		$sms_content_of_operation_event = get_option( $sms_content );
			$sms_template_status = get_option( $sms_content.'_status' );
			if( $sms_template_status == 'on' ) {
	   			$this->kasha_sms_api_function($order_id,$sms_content_of_operation_event);
	   		}
	   	}
	   	if( $order->get_status() == 'refunded' ) {
	   		$sms_content = str_replace( ' ', '_', 'Order status change refunded');
	   		$sms_content_of_operation_event = get_option( $sms_content );
			$sms_template_status = get_option( $sms_content.'_status' );
			if( $sms_template_status == 'on' ) {
	   			$this->kasha_sms_api_function($order_id,$sms_content_of_operation_event);
	   		}
	   	}
	   	if( $order->get_status() == 'cancelled' ) {
	   		$sms_content = str_replace( ' ', '_', 'Order status change cancelled');
	   		$sms_content_of_operation_event = get_option( $sms_content );
			$sms_template_status = get_option( $sms_content.'_status' );
			if( $sms_template_status == 'on' ) {
	   			$this->kasha_sms_api_function($order_id,$sms_content_of_operation_event);
	   		}
	   	}
	   	if( $order->get_status() == 'verified' ) {
	   		$sms_content = str_replace( ' ', '_', 'Order status change verified');
	   		$sms_content_of_operation_event = get_option( $sms_content );
			$sms_template_status = get_option( $sms_content.'_status' );
			if( $sms_template_status == 'on' ) {
	   			$this->kasha_sms_api_function($order_id,$sms_content_of_operation_event);
	   		}
	   	}
	  	if( $order->get_status() == 'dispatched' ) {
	  		$sms_content = str_replace( ' ', '_', 'Order status change dispatched');
	   		$sms_content_of_operation_event = get_option( $sms_content );
			$sms_template_status = get_option( $sms_content.'_status' );
			if( $sms_template_status == 'on' ) {
	   			$this->kasha_sms_api_function($order_id,$sms_content_of_operation_event);
	   		}
	   	}
	}

	/**
	* Woocommerce method call back ( action -  woocommerce_order_status_changed ).
	* @param      int     $orderId  Woocommerce order id.
	* @return 	obj Orderdata. 
	*/
	public function kasha_get_order_data( $orderId ) {
        if ( !class_exists( 'WC_Order' ) ) {
            return false;
        }
        return new WC_Order( $orderId );    
    }

    /**
	* To send operational type sms.
	* @param      int    $order_id   Wocommerce order id.
	* @param      string    $message_content    SMS content which needs to deliver to the customer.
	* API reference https://sms-staging.kasha.co.ke/redoc/#operation/create_sms_create
	*/
    public function kasha_sms_api_function( $order_id, $message_content ) {
    	if( !empty( $message_content )	) {
	    	$order = new WC_Order( $order_id );
			$endpoint = KASHA_SMS_API_URL.'create/sms'; 
			$order_data = $order->get_data();
			$the_user = get_user_by( 'email', get_option( 'woocommerce_email_from_address' ) );
			$the_user_id = $the_user->ID;

			// Shop shortcodes replacing...
			$message_content = str_replace( '[shop_domain]', get_site_url(), $message_content);
			$message_content = str_replace( '[shop_currency]', get_woocommerce_currency_symbol(), $message_content);
			$message_content = str_replace( '[shop_email]', get_option( 'woocommerce_email_from_address' ), $message_content);
			$message_content = str_replace( '[shop_name]', get_the_title( get_option( 'woocommerce_shop_page_id' ) ), $message_content);

			//Order shortcodes replacing...
			$message_content = str_replace( '[order_id]', $order_id, $message_content );
			$message_content = str_replace( '[long_order_id]', $order->get_order_number(), $message_content );
			$message_content = str_replace( '[order_ref]', $order->get_order_key(), $message_content );
			$message_content = str_replace( '[order_currency]', $order->get_currency(), $message_content );
			$message_content = str_replace( '[order_payment]', $order->get_payment_method(), $message_content );
			$message_content = str_replace( '[order_date]', date( 'Y-m-d H:i:s', strtotime( get_post( $order->get_id())->post_date ) ), $message_content );
			$message_content = str_replace( '[order_time]', date( 'H:i:s', strtotime( get_post( $order->get_id() )->post_date ) ), $message_content );
			$message_content = str_replace( '[order_total_paid]', wc_format_decimal( $order->get_total(), 2 ), $message_content );

			//Customer shortcodes replacing 
			$message_content = str_replace( '[customer_id]',$the_user->ID, $message_content );
			$message_content = str_replace( '[username]',$the_user->user_login, $message_content );
			$message_content = str_replace( '[customer_company]',$order_data['billing']['company'], $message_content );
			$message_content = str_replace( '[customer_lastname]',$order_data['billing']['last_name'], $message_content );
			$message_content = str_replace( '[customer_firstname]',$order_data['billing']['first_name'], $message_content );
			$message_content = str_replace( '[customer_message]',$order_data['customer_note'], $message_content );
			$message_content = str_replace( '[customer_lastname_vokativ]',$order_data['billing']['last_name'], $message_content );
			$message_content = str_replace( '[customer_firstname_vokativ]',$order_data['billing']['first_name'], $message_content );
			$message_content = str_replace( '[customer_address]',$order_data['billing']['address_1'].' '.$order_data['billing']['address_2'], $message_content );
			$message_content = str_replace( '[customer_postcode]',$order_data['billing']['postcode'], $message_content );
			$message_content = str_replace( '[customer_city]',$order_data['billing']['city'], $message_content );
			$message_content = str_replace( '[customer_state]',$order_data['billing']['state'], $message_content );
			$message_content = str_replace( '[customer_phone]',$order_data['billing']['phone'], $message_content );
			$message_content = str_replace( '[customer_mobile]',$order_data['billing']['phone'], $message_content );
			$message_content = str_replace( '[customer_invoice_company]',$order_data['billing']['company'], $message_content );
			$message_content = str_replace( '[customer_invoice_lastname]',$order_data['billing']['last_name'], $message_content );
			$message_content = str_replace( '[customer_invoice_firstname]',$order_data['billing']['first_name'], $message_content );
			$message_content = str_replace( '[customer_invoice_lastname_vokativ]',$order_data['billing']['last_name'], $message_content );
			$message_content = str_replace( '[customer_invoice_firstname_vokativ]',$order_data['billing']['first_name'], $message_content );
			$message_content = str_replace( '[customer_country]',WC()->countries->countries[ $order->get_shipping_country() ], $message_content );
			$message_content = str_replace( '[customer_invoice_address]',$order_data['billing']['address_1'].' '.$order_data['billing']['address_2'], $message_content );
			$message_content = str_replace( '[customer_invoice_postcode]',$order_data['billing']['postcode'], $message_content );
			$message_content = str_replace( '[customer_invoice_city]',$order_data['billing']['city'], $message_content );
			$message_content = str_replace( '[customer_invoice_state]',$order_data['billing']['state'], $message_content );
			$message_content = str_replace( '[customer_invoice_phone]',$order_data['billing']['phone'], $message_content );
			$message_content = str_replace( '[customer_invoice_mobile]',$order_data['billing']['phone'], $message_content );
			$message_content = str_replace( '[customer_email]',get_option( 'woocommerce_email_from_address' ), $message_content );
			$message_content = str_replace( '[customer_name]',$order_data['billing']['first_name'] .' '.$order_data['billing']['last_name'], $message_content );
			$message_content = str_replace( '[customer_invoice_country_id]',$order_data['billing']['country'], $message_content );
			$message_content = str_replace( '[customer_country_id]',$order_data['billing']['country'], $message_content );
			$message_content = str_replace( '[customer_invoice_country]',WC()->countries->countries[ $order->get_shipping_country() ], $message_content );

			if( !empty( $order_data['billing']['phone'] ) ){
				$admin_customer[] = array(
	            	'to_phone' => $order_data['billing']['phone'],
	                'message' => $message_content,
	                'is_auto' => true,
	                'sms_type' => 1,
	                'provider' => NULL
	            );
			} else{
				$to_phone_number = get_user_meta( $the_user_id, 'billing_phone', true );
				$admin_customer [] = array(
	            	'to_phone' => $to_phone_number,
	                'message' => $message_content,
	                'is_auto' => true,
	                'sms_type' => 1,
	                'provider' => NULL
	            );
			}	
			
			$args = array(
	            'httpversion' => '1.1',
	            'blocking'    => true,
	            'headers'     => array(
	                'Content-Type'  => 'application/json',
	                'Accept'        => 'application/json',
	                'Authorization' => 'Token '.KASHA_SMS_TOKEN,
	            ),
	            'body' => json_encode ( $admin_customer )
	        );

	        $response = wp_remote_post( $endpoint, $args );
	    }
    }

    /**
	* Woocommerce method call back ( action -  woocommerce_new_order ).
	* @param      int     $orderId  Woocommerce order id.
	*/
	public function kasha_send_sms_on_new_order( $order_id ) {
		$message_content = get_option( 'New order' );
	   	$this->kasha_sms_api_function( $order_id, $message_content );
	}

	/**
	* Adding sidebar menu to access kasha sms dashboard.
	*/
	public function kasha_sms_menu() {
        $id = get_current_user_id();
        $user = new WP_User($id);
        $auth = FALSE;
        $user_role = $user->roles;
        $user_roles_default = array("administrator");
        foreach($user_role as $value)
        {
            if(in_array($value, $user_roles_default))
            {
                $auth = TRUE;
            }
        }
        if($auth)
        {
            if(in_array("administrator", $user_role))
            {
                $cap = "administrator";
            }
            
            add_menu_page(__('Kasha SMS','wsdesk'), "Kasha SMS", $cap, "kasha_sms", array($this, "kasha_sms_main_menu_callback"), "dashicons-email-alt", 25);
        }
    }



    function kasha_sms_main_menu_callback() {
    	if( isset( $_GET['page'] ) && $_GET['page'] == 'kasha_sms' ) {
        	include_once( KASHA_SMS_INCLUDE_PATH . "/kasha_sms_main_home_page.php" );
        }
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kasha_sms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kasha_sms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		
		if( isset( $_GET['page'] ) && $_GET['page'] == 'kasha_sms' ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/kasha_sms-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kasha_sms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kasha_sms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		*/
		if( isset( $_GET['page'] ) && $_GET['page'] == 'kasha_sms' ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/kasha_sms-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'jquery' );
		}
	}

}
