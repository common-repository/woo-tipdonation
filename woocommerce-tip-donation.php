<?php

/*

 * Plugin Name: Woocommerce Tip/Donation

 * Plugin URI: https://profiles.wordpress.org/phpradar

 * Description: Toolkit for add tip amount during checkout.

 * Author: PHPRADAR

 * Text Domain: phpradar-wtd-setting

 * Version: 1.2

 * Requires at least: 4.4

 * Tested up to: 5.9

 */

defined( 'ABSPATH' ) or exit;
//WC check

$active_plugins = get_option( 'active_plugins', array() );
if( !in_array( 'woocommerce/woocommerce.php',$active_plugins ) ){
	
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
	
	deactivate_plugins( plugin_basename( __FILE__ ) );
	
	if( isset( $_GET['activate'] ))
      unset( $_GET['activate'] );

}
if (!class_exists('PHPRADAR_wc_tipDonation')) {
	// $wtd_plugin_dir = dirname(__FILE__) . "/";
	// $wtd_plugin_url = plugins_url()."/" . basename($wtd_plugin_dir) . "/";
	
	class PHPRADAR_wc_tipDonation
	{
		protected static $instance;

		protected $adminpage;
		
        private static $plugin_url;
        private static $plugin_dir;
		private static $plugin_slug = "phpradar-wtd-setting";
		
        private static $wtd_option_key = "phpradar-wtd-setting";
        private $wtd_settings;
		
		private static $wtd_dt_session = "PHPRADAR_wtd_dt_session";
		
		public function __construct()
		{
			global $wtd_plugin_url, $wtd_plugin_dir;
            /* plugin url and directory variable */
            self::$plugin_dir = $wtd_plugin_dir;
            self::$plugin_url = $wtd_plugin_url;
            /* load donation  setting */
            $this->wtd_settings = get_option(self::$wtd_option_key);
			//Cart Page Hooks
            $this->wtd_cart_hook = array('woocommerce_before_cart_contents'=>'Before Cart Content','woocommerce_after_cart_table'=>'After Cart Table','woocommerce_cart_coupon'=>'After Coupon','woocommerce_cart_collaterals'=>'After Cart Collateral','woocommerce_before_cart_totals'=>'Before Cart Total','woocommerce_cart_totals_before_shipping'=>'Before Shipping Total','woocommerce_cart_totals_after_shipping'=>'After Shipping Total','woocommerce_before_shipping_calculator'=>'Before Shipping Calculator','woocommerce_after_shipping_calculator'=>'After Shipping Calculator','woocommerce_cart_totals_before_order_total'=>'Before Order Total','woocommerce_cart_totals_after_order_total'=>'After Cart Total','woocommerce_proceed_to_checkout'=>'Before Checkout Button','woocommerce_after_cart_totals'=>'After Cart Total','woocommerce_review_order_after_submit'=>'After Submit');
			
			//Checkout Page Hooks
            $this->wtd_checkout_hook = array('woocommerce_before_checkout_form'=>'Before Checkout Form','woocommerce_checkout_before_customer_details'=>'Before Customer Details','woocommerce_before_checkout_billing_form'=>'Before Billing Form','woocommerce_after_checkout_billing_form'=>'After Billing Form','woocommerce_before_checkout_shipping_form'=>'Before Shipping Form','woocommerce_after_checkout_shipping_form'=>'After Shipping Form','woocommerce_before_order_notes'=>'Before Order Note','woocommerce_after_order_notes'=>'After Order Note','woocommerce_checkout_after_customer_details'=>'After Customer Details','woocommerce_checkout_before_order_review'=>'Before Order Review','woocommerce_review_order_before_payment'=>'Before Payment','woocommerce_review_order_before_submit'=>'Before Submit','woocommerce_review_order_after_payment'=>'After Payment','woocommerce_checkout_after_order_review'=>'After Review','After Checkout');
			
			$this->cart_default_hook = (empty($this->wtd_settings['wtd_cart_page_position']) ? 'woocommerce_before_cart_contents' : $this->wtd_settings['wtd_cart_page_position']);
			$this->checkout_default_hook = (empty($this->wtd_settings['wtd_checkout_page_position']) ? 'woocommerce_review_order_before_payment' : $this->wtd_settings['wtd_checkout_page_position']);
			add_action( 'admin_init', array( $this, 'PHPRADAR_woo_version_check' ) );
			add_action("wp_enqueue_scripts", array($this, "PHPRADAR_enqueue_scripts"), 10);
			add_action('admin_menu', array($this, 'PHPRADAR_add_menulink'));
			add_action("woocommerce_thankyou", array($this, "PHPRADAR_woocommerce_thankyou"));
			add_action( $this->cart_default_hook , array($this, 'PHPRADAR_woocommerce_donation_cart_form'),99);
			add_action( $this->checkout_default_hook , array($this, 'PHPRADAR_woocommerce_donation_checkout_form'),99);
			
            add_action( 'wp_ajax_update_fee', array($this, "PHPRADAR_update_fee") );
            add_action( 'wp_ajax_nopriv_update_fee', array($this, "PHPRADAR_update_fee") );
			add_action('woocommerce_cart_calculate_fees', array($this, 'PHPRADAR_add_fee'));
		}
		
		public function PHPRADAR_instance()
		{
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;	
		}
		
		public function PHPRADAR_woo_version_check()
		{
			global $woocommerce; 
			if ( version_compare( $woocommerce->version, '2.4.9', '<=' ) ) {
				
				add_action( 'admin_notices', array($this,'WMAMC_admin_notice_msg') );
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
				deactivate_plugins( plugin_basename( __FILE__ ) );
				return false;
				
			}

		}

        public function PHPRADAR_enqueue_scripts()
		{
            if(is_cart() || is_checkout()){
                wp_enqueue_script('phpradar-script', plugins_url('/assets/js/phpradar-script.js', __FILE__), array(), false, true);
				wp_localize_script( 'phpradar-script', 'phpradar',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
				
            }
            
        }

		public function PHPRADAR_add_menulink() 
		{



			 $this->adminpage = add_submenu_page(

						'woocommerce',

						__('Tip/Donation',self::$plugin_slug),

						__('Tip/Donation',self::$plugin_slug), 

						'manage_woocommerce',

						self::$plugin_slug,

						array($this, 'PHPRADAR_render_submenu_pages' ),

						'dashicons-format-video'

					);	

		}

		public function PHPRADAR_render_submenu_pages()
		{
            /* save donation setting */
			$error = new WP_Error();
            if (isset($_POST['btn-wtd-submit']) && isset($_POST[self::$plugin_slug])) {
				$integerRegx = '/^\d+(?:,\d+)*$/';
				if(wp_verify_nonce( $_POST["_phpradar_nonce"], 'phpradar-wtd-nonce' )){
					if ($_POST["wtd_pre_defined_amt"] && !preg_match($integerRegx,str_replace(' ','',$_POST["wtd_pre_defined_amt"])) ){
						$error->add('error','Please enter valid Pre-Defined Amount.');
					} else {
						$this->PHPRADAR_wtd_save_setting();
						//ADD SUCCESS MESSAGE
						$error->add('success','Setting save successfully');
					}
				} else {
					$error->add('error','Try Again.');
				}
				wp_safe_redirect(wp_get_referer());
            }
			include_once self::$plugin_dir . 'view/setting.php';
		}
 
		public function PHPRADAR_wtd_get_error_message($error)
		{
			$html='';
			if( is_wp_error( $error ) && $error->get_error_message()) {
				$html .= '<div id="message" class="updated notice '.$error->get_error_code().' is-dismissible">
							<p><strong>'.$error->get_error_message().'</strong></p>
							<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this message.</span></button>
						</div>';
			}
			return $html;
		}

        public function PHPRADAR_wtd_save_setting()
        {
            $arrayRemove = array(self::$plugin_slug, "btn-wtd-submit","_phpradar_nonce");
            $saveData = array();

            foreach ($_POST as $key => $value):
                if (in_array($key, $arrayRemove))
                    continue;
                $saveData[$key] = $value;
            endforeach;

            $this->wtd_settings = $saveData;
            update_option(self::$wtd_option_key, $saveData);
        }

		public function PHPRADAR_woocommerce_donation_cart_form()
		{
			$integerRegx = '/^\d+(?:,\d+)*$/';
            if ($this->PHPRADAR_get_wtd_setting("wtd_enable") != 1)
                return;

			$display_setting = $this->PHPRADAR_get_wtd_setting('wtd_display_fee');
            if ($this->PHPRADAR_get_wtd_setting('wtd_display_fee') && ($display_setting == 1 || $display_setting == 3)):
                $amount = 0;
                if ($this->PHPRADAR_get_fee() > 0) {
                    $amount = $this->PHPRADAR_get_fee();
                } else {
                    $amount = $this->PHPRADAR_get_wtd_setting('wtd_default_amt');
                }
				include_once self::$plugin_dir . 'view/cart-form.php';
			endif;
		}

		public function PHPRADAR_woocommerce_donation_checkout_form()
		{
			$integerRegx = '/^\d+(?:,\d+)*$/';
            if ($this->PHPRADAR_get_wtd_setting("wtd_enable") != 1)
                return;

			$display_setting = $this->PHPRADAR_get_wtd_setting('wtd_display_fee');
            if ($this->PHPRADAR_get_wtd_setting('wtd_display_fee') && ($display_setting == 2 || $display_setting == 3)):
                $amount = 0;
                if ($this->PHPRADAR_get_fee() > 0) {
                    $amount = $this->PHPRADAR_get_fee();
                } else {
                    $amount = $this->PHPRADAR_get_wtd_setting('wtd_default_amt');
                }
				include_once self::$plugin_dir . 'view/cart-form.php';
			endif;
		}
		
        public function PHPRADAR_add_fee()
        {
            global $woocommerce;
            if ($this->PHPRADAR_get_wtd_setting("wtd_enable") != 1)
                return;
            $fee = $this->PHPRADAR_get_fee();
            if ($fee && is_numeric($fee) && $fee > 0):
                $wtd_fee_title = $this->PHPRADAR_get_wtd_setting("wtd_fee_title");
                $taxable = $this->PHPRADAR_get_wtd_setting("wtd_taxable") ? true : false;
                $woocommerce->cart->add_fee(__($wtd_fee_title, 'wtd'), $fee, $taxable);
            endif;
        }
		
        public function PHPRADAR_update_fee()
        {
            if (isset($_POST["amount"]) && is_numeric($_POST["amount"]) && wp_verify_nonce( $_POST["_phpradar_nonce"], 'phpradar-wtd-nonce' ) ) {
                $amount = $_POST["amount"];
				global $woocommerce;
				$woocommerce->session->set(self::$wtd_dt_session, $amount);
				add_filter('add_to_cart_fragments',  array($this, 'woocommerce_header_add_to_cart_fragment'));
            }
        }
		
        public function PHPRADAR_get_fee()
        {
            global $woocommerce;
            $amount = $woocommerce->session->get(self::$wtd_dt_session);
            if ($amount && is_numeric($amount)) {
                return $amount;
            }
            return "0";
        }
		
        public function PHPRADAR_get_wtd_setting($key)
        {
            if (!$key || $key == "")
                return;

            if (!isset($this->wtd_settings[$key]))
                return;

            return $this->wtd_settings[$key];
        }

        public function PHPRADAR_woocommerce_thankyou()
        {
            global $woocommerce;
			$woocommerce->session->set(self::$wtd_dt_session,null);
        }
	}

}
function PHPRADAR_wc_tipDonation() {
	new PHPRADAR_wc_tipDonation();
}
PHPRADAR_wc_tipDonation();
?>