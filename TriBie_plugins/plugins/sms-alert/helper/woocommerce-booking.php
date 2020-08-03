<?php

if (! defined( 'ABSPATH' )) exit;

class SmsAlertWcBooking
{
		
	public function __construct() {
		require_once WP_PLUGIN_DIR.'/woocommerce-bookings/includes/wc-bookings-functions.php';
		add_filter('sAlertDefaultSettings',  __CLASS__ .'::addDefaultSetting',1);	
		self::addActionForBookingStatus();	
		add_action( 'sa_addTabs', array( $this, 'addTabs' ), 10 );
	}
	
	/*add tabs to smsalert settings at backend*/
	public static function addTabs($tabs=array())
	{
		$tabs['wcbk_customer']['title']			= 'Booking Cust. Temp';
		$tabs['wcbk_customer']['tab_section'] 	= 'wcbkcsttemplates';
		$tabs['wcbk_customer']['tabContent']	= 'views/booking_customer_template.php';
		$tabs['wcbk_customer']['icon']			= 'dashicons-admin-users';
		
		$tabs['wcbk_admin']['title']			= 'Booking Admin Temp';
		$tabs['wcbk_admin']['tab_section'] 		= 'wcbkadmintemplates';
		$tabs['wcbk_admin']['tabContent']		= 'views/booking_admin_template.php';
		$tabs['wcbk_admin']['icon']				= 'dashicons-list-view';
		return $tabs;
	}
	
	/*add action for booking statuses*/
	public static function addActionForBookingStatus()
	{
		$wcbk_order_statuses = SmsAlertWcBooking::get_booking_statuses();
		foreach($wcbk_order_statuses as $wkey => $booking_status){
			add_action( 'woocommerce_booking_'.$booking_status, __CLASS__ .'::wcbkStatusChanged');
		}
	}
	/*trigger sms on status change of booking*/
	public static function wcbkStatusChanged($booking_id)
	{
		$output = SmsAlertWcBooking::triggerSms($booking_id);
	}
		
	/*add default settings to savesetting in setting-options*/
	public function addDefaultSetting($defaults=array())
	{
		$wcbk_order_statuses 	= self::get_booking_statuses();
	
		foreach($wcbk_order_statuses as $ks => $vs)
		{
			$defaults['smsalert_wcbk_general']['wcbk_admin_notification_'.$vs]	= 'off';
			$defaults['smsalert_wcbk_general']['wcbk_order_status_'.$vs]		= 'off';
			$defaults['smsalert_wcbk_message']['wcbk_admin_sms_body_'.$vs]		= '';
			$defaults['smsalert_wcbk_message']['wcbk_sms_body_'.$vs]			= '';
		}			
		return $defaults;
	}
		
	/*
		display woocommerce booking variable at smsalert setting page
	*/	
	public static function getWCBookingvariables()
	{			
		 $variables = array(
						'[order_id]' 		=> 'Order Id',
						'[store_name]' 		=> 'Store Name',
						'[booking_id]' 		=> 'Booking Id',
						'[booking_status]' 	=> 'Booking status',
						'[product_name]' 	=> 'Product Name',
						'[booking_cost]' 	=> 'Booking Amt',
						'[booking_start]' 	=> 'Booking Start',
						'[booking_end]' 	=> 'Booking End',
						'[first_name]' 		=> 'Billing First Name',
						'[last_name]' 		=> 'Billing Last Name',
						'[booking_persons]' => 'Person Counts',
						'[resource_name]' 	=> 'Resource Name',
		);
		$ret_string = '';
		foreach($variables as $vk => $vv)
		{
			$ret_string .= sprintf( "<a href='#' val='%s'>%s</a> | " , $vk , __($vv,SmsAlertConstants::TEXT_DOMAIN));
		}
		return $ret_string;
	}
	
	/*get woocommerce booking status*/
	public static  function get_booking_statuses() {
		$status = get_wc_booking_statuses( 'user', true );		
		return array_keys($status);
	}
		
	/*trigger sms when woocommerce booking status is changed*/	
	public static function triggerSms( $booking_id ) 
	{
		if ( $booking_id ) {
			
			// Only send the booking email for booking post types, not orders, etc
			if ( 'wc_booking' !== get_post_type( $booking_id ) ) {
				return;
			}
			
			$object = get_wc_booking( $booking_id );
			if ( ! is_object( $object )) {
				return;
			}
			
			$booking_status			= $object->status;
			$admin_message			= smsalert_get_option( 'wcbk_admin_sms_body_'.$booking_status, 'smsalert_wcbk_message', '');
			$is_enabled				= smsalert_get_option( 'wcbk_order_status_'.$booking_status, 'smsalert_wcbk_general');
			$admin_phone_number     = smsalert_get_option( 'sms_admin_phone', 'smsalert_message', '' );	
			$buyer_mob 				= $product_name = $order_id = $booking_amt= $first_name= $last_name='';
			$bookings				= get_post_custom($booking_id);
			$booking_start			= date('M d,Y H:i',strtotime(array_shift($bookings['_booking_start'])));
			$booking_end			= date('M d,Y H:i',strtotime(array_shift($bookings['_booking_end'])));
			$person_counts 			= $object->get_persons_total();
			$resource_name 			= ($object->get_resource()) ? $object->get_resource()->post_title : '';
			$booking_amt			= array_shift($bookings['_booking_cost']);
			$order  				= $object->get_order()->get_data();
			$buyer_mob 				= $order['billing']['phone'];
			$first_name 			= $order['billing']['first_name'];
			$last_name 				= $order['billing']['last_name'];
			
			if ( $object->get_product() ) {
				$product_name = $object->get_product()->get_title();
			}
			
			if ( $object->get_order() ) {
				$order_id = $object->get_order()->get_order_number();
			}
			
			$variables = array(
							'[order_id]' 		=> $order_id,
							'[booking_id]' 		=> $booking_id,
							'[booking_status]' 	=> $booking_status,
							'[product_name]' 	=> $product_name,
							'[booking_cost]' 	=> $booking_amt,
							'[booking_start]' 	=> $booking_start,
							'[booking_end]' 	=> $booking_end,
							'[first_name]' 		=> $first_name,
							'[last_name]' 		=> $last_name,
							'[store_name]' 		=> get_bloginfo(),
							'[booking_persons]' => $person_counts,
							'[resource_name]' 	=> $resource_name,
			);
			
			if($buyer_mob!='' && $is_enabled=='on')
			{
				$buyer_message=smsalert_get_option( 'wcbk_sms_body_'.$booking_status, 'smsalert_wcbk_message', '');
				$content = str_replace(array_keys($variables), array_values($variables), $buyer_message);
				do_action('sa_send_sms', $buyer_mob, $content);
			}
			
			if(smsalert_get_option( 'wcbk_admin_notification_'.$booking_status, 'smsalert_wcbk_general') == 'on' && $admin_phone_number!='')
			{
				$admin_message = smsalert_get_option( 'wcbk_admin_sms_body_'.$booking_status, 'smsalert_wcbk_message', '');
				$content = str_replace(array_keys($variables), array_values($variables), $admin_message);
				do_action('sa_send_sms', $admin_phone_number, $content);
			}
		}
	}
}
new SmsAlertWcBooking;
