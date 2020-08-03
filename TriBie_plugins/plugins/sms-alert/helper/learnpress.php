<?php

if (! defined( 'ABSPATH' )) exit;

class SmsAlertLearnPress
{
		
		public function __construct() {
			
			add_action( 'learn-press/user-enrolled-course/notification', __CLASS__ . '::smsalert_lp_send_sms_user_enroll', 10, 3);
			
			add_action( 'learn-press/order/status-changed', __CLASS__ . '::smsalert_lp_send_sms_on_changedStatus', 10, 3);
			
			
			add_action( 'set_user_role', __CLASS__ . '::smsalert_lp_after_become_teacher', 10, 3);
			add_action( 'learn-press/user-course-finished/notification', __CLASS__ . '::smsalert_lp_user_course_finished', 10, 3);
			
			add_action( 'learn-press/payment-form',  __CLASS__ .'::smsalert_show_button_at_checkout', 15 );
			
			add_action( 'learn-press/checkout-order-processed',  __CLASS__ .'::smsalert_lp_saved_billing_phone', 10,2 );
			add_filter('sAlertDefaultSettings',  __CLASS__ .'::addDefaultSetting',1,2);
			add_action( 'sa_addTabs', array( $this, 'addTabs' ), 10 );
		}
		
		/*add tabs to smsalert settings at backend*/
		public static function addTabs($tabs=array())
		{
			$tabs['lp_customer']['title']		= 'LPress Cust. Temp';
			$tabs['lp_customer']['tab_section'] = 'lpresscsttemplates';
			$tabs['lp_customer']['tabContent']	= 'views/lpress_customer_template.php';
			$tabs['lp_customer']['icon']		= 'dashicons-admin-users';
			
			$tabs['lp_admin']['title']			= 'LPress Admin Temp';
			$tabs['lp_admin']['tab_section'] 	= 'lpressadmintemplates';
			$tabs['lp_admin']['tabContent']		= 'views/lpress_admin_template.php';
			$tabs['lp_admin']['icon']			= 'dashicons-list-view';
			
			
			return $tabs;
		}
		
		
		/*get variables*/
		public static function getLPRESSvariables($type='')
		{
			if($type=='courses')
			{
				$variables = array(
					'[username]' 		=> 'User Name',
					'[course_name]' 	=> 'Course Name'
				);
			}
			else if($type=='teacher')
			{
				$variables = array(
					'[username]' 		=> 'User Name',
				);
			}
			else
			{
				$variables = array(
					'[order_currency]' 			=> 'Order Currency',
					'[payment_method_title]' 	=> 'Payment Method Title',
					'[checkout_email]' 			=> 'Checkout Email',
					'[order_total]' 			=> 'Order Total',
					'[order_status]' 			=> 'Order Status',
					'[order_id]' 				=> 'Order Id',
					'[username]' 				=> 'User Name',
				);
			}
			
			
			$ret_string = '';
			foreach($variables as $vk => $vv)
			{
				$ret_string .= sprintf( "<a href='#' val='%s'>%s</a> | " , $vk , __($vv,SmsAlertConstants::TEXT_DOMAIN));
			}
			return $ret_string;
	   }
		
		/*add default settings to savesetting in setting-options*/
		public function addDefaultSetting($defaults=array())
		{
			$wpam_statuses=self::get_learnpress_status();
			foreach($wpam_statuses as $ks => $vs)
			{
				$defaults['smsalert_lpress_general']['lpress_admin_notification_'.$vs]='off';
				$defaults['smsalert_lpress_general']['lpress_order_status_'.$vs]='off';
				$defaults['smsalert_lpress_message']['lpress_admin_sms_body_'.$vs]='';
				$defaults['smsalert_lpress_message']['lpress_sms_body_'.$vs]='';			
			}
			
			$defaults['smsalert_lpress_general']['course_enroll']='off';
			$defaults['smsalert_lpress_message']['sms_body_course_enroll']='';
			$defaults['smsalert_lpress_general']['admin_course_enroll']='off';
			$defaults['smsalert_lpress_message']['sms_body_course_enroll_admin_msg']='';
			
			$defaults['smsalert_lpress_general']['course_finished']='off';
			$defaults['smsalert_lpress_message']['sms_body_course_finished']='';
			$defaults['smsalert_lpress_general']['admin_course_finished']='off';
			$defaults['smsalert_lpress_message']['sms_body_course_finished_admin_msg']='';
			
			$defaults['smsalert_lpress_general']['become_teacher']='off';
			$defaults['smsalert_lpress_message']['sms_body_become_teacher_msg']='';
			$defaults['smsalert_lpress_general']['admin_become_teacher']='off';
			$defaults['smsalert_lpress_message']['sms_body_admin_become_teacher_msg']='';
			return $defaults;
		}
		
		public static function get_learnpress_status()
		{
			$order_statues = array();
			$order_statues['pending']='pending';
			$order_statues['processing']='processing';
			$order_statues['completed']='completed';
			$order_statues['cancelled']='cancelled';
			$order_statues['failed']='failed';
			return $order_statues;

		}
		
		public static function parse_sms_content($order_id=NULL,$content=NULL,$new_status=NULL,$user_id=NULL,$course_id=NULL)
		{
			
			$order_id = (!empty($order_id)) ? $order_id : 0;
			$order_variables	= get_post_custom($order_id);
			
			$user 				= get_user_by('ID',$user_id);
			$username 			= (is_object($user))?$user->user_login:'';
			$course_name 		= get_the_title($course_id);
			
			$find = array(
				'[order_id]',
				'[order_status]',
				'[store_name]',
				'[username]',
				'[course_name]',
			);
			
			$replace = array(
				$order_id,
				$new_status,
				get_bloginfo(),
				$username,
				$course_name,
			);
			
			$content = str_replace( $find, $replace, $content );
			
			foreach ($order_variables as &$value) {
				$value = $value[0];
			}
			unset($value);
		
			$order_variables = array_combine(
				array_map(function($key){ return '['.ltrim($key, '_').']'; }, array_keys($order_variables)),
				$order_variables
			);
			$content = str_replace( array_keys($order_variables), array_values($order_variables), $content );
			return $content;
		}
		
		
		public static function smsalert_show_button_at_checkout()
		{
			$user_id = get_current_user_id();
			$billing_phone = get_user_meta($user_id,'billing_phone',true);
			
			echo '<div id="checkout-billing_phone" style="border: 1px solid #DDD;padding: 20px;margin: 0 0 20px 0;">
			<h4 class="form-heading">Billing Phone</h4>
			<p class="form-desc">To get Order Notification on your mobile.</p>
			<input class="input-text" type="billing_phone" value="'.$billing_phone.'" name="billing_phone"/>
			</div>
			';
	
		}
		
		public static function  smsalert_lp_saved_billing_phone($order_id,$data)
		{
			$billing_phone = !empty($_POST['billing_phone']) ? $_POST['billing_phone'] :'';
			if($billing_phone!='')
			{
				update_post_meta( $order_id, '_billing_phone', $billing_phone);
			}
		}
		
		
		
		/*smsalert_lp_send_sms_user_enroll*/
		public static  function smsalert_lp_send_sms_user_enroll($course_id, $user_id, $user_course) {
			$billing_phone = get_user_meta($user_id,'billing_phone',true);
			
			$buyer_sms_notify = smsalert_get_option( 'course_enroll', 'smsalert_lpress_general', 'on' );
			$admin_sms_notify = smsalert_get_option( 'admin_course_enroll', 'smsalert_lpress_general', 'on' );
				
			if($buyer_sms_notify=='on')
			{
				
				$buyer_sms_content = smsalert_get_option( 'sms_body_course_enroll', 'smsalert_lpress_message', '');
				do_action('sa_send_sms', $billing_phone, self::parse_sms_content(NULL,$buyer_sms_content,NULL,$user_id,$course_id));
				
			}
			
			if($admin_sms_notify=='on')
			{
				$admin_phone_number     = smsalert_get_option( 'sms_admin_phone', 'smsalert_message', '' );
				if($admin_phone_number!='')
				{
					$admin_sms_content = smsalert_get_option( 'sms_body_course_enroll_admin_msg', 'smsalert_lpress_message', '');
					do_action('sa_send_sms', $admin_phone_number, self::parse_sms_content(NULL,$admin_sms_content,NULL,$user_id,$course_id));
					
				}
			}
		}
		
		
		/*smsalert_lp_user_course_finished*/
		//params : 332,111,11
		
		public static function smsalert_lp_user_course_finished($course_id, $user_id, $user_item_id) {
			$billing_phone = get_user_meta($user_id,'billing_phone',true);
			
			$buyer_sms_notify = smsalert_get_option( 'course_finished', 'smsalert_lpress_general', 'on' );
			$admin_sms_notify = smsalert_get_option( 'admin_course_finished', 'smsalert_lpress_general', 'on' );
				
			if($buyer_sms_notify=='on')
			{
				$buyer_sms_content = smsalert_get_option('sms_body_course_finished', 'smsalert_lpress_message', '');
				do_action('sa_send_sms', $billing_phone, self::parse_sms_content(NULL,$buyer_sms_content,NULL,$user_id,$course_id));
			}
			
			if($admin_sms_notify=='on')
			{
				$admin_phone_number     = smsalert_get_option( 'sms_admin_phone', 'smsalert_message', '' );
				if($admin_phone_number!='')
				{
					$admin_sms_content = smsalert_get_option( 'sms_body_course_finished_admin_msg', 'smsalert_lpress_message', '');
					do_action('sa_send_sms', $admin_phone_number, self::parse_sms_content(NULL,$admin_sms_content,NULL,$user_id,$course_id));
				}
			}
		}
		
		
		
		/*smsalert_lp_send_sms_on_changedStatus*/
		//when order created then old_status is pending then new_status is blank
		//when order updated then old_status is 
		public static  function smsalert_lp_send_sms_on_changedStatus($order_id, $old_status, $new_status) {
			if($old_status!='' && ($old_status!=$new_status))
			{
				$buyer_sms_notify = smsalert_get_option( 'lpress_order_status_'.$new_status, 'smsalert_lpress_general', 'on' );
				$admin_sms_notify = smsalert_get_option( 'lpress_admin_notification_'.$new_status, 'smsalert_lpress_general', 'on' );
				$user_id  			= get_post_meta( $order_id, '_user_id', true );
				
				if($buyer_sms_notify=='on')
				{
					$buyer_sms_content = smsalert_get_option( 'lpress_sms_body_'.$new_status, 'smsalert_lpress_message', sprintf(__('Hello %s, status of your %s with %s has been changed to %s.',SmsAlertConstants::TEXT_DOMAIN), '[username]', '[order_id]', '[store_name]', '[order_status]') );
					$billing_phone = get_post_meta($order_id,'_billing_phone',true);
					do_action('sa_send_sms', $billing_phone, self::parse_sms_content($order_id,$buyer_sms_content,$new_status,$user_id));
					
				}
				
				if($admin_sms_notify=='on')
				{
					$admin_phone_number     = smsalert_get_option( 'sms_admin_phone', 'smsalert_message', '' );
					if($admin_phone_number!='')
					{
						$admin_sms_content = smsalert_get_option( 'lpress_admin_sms_body_'.$new_status, 'smsalert_lpress_message', sprintf(__('%s status of order %s has been changed to %s.',SmsAlertConstants::TEXT_DOMAIN), '[store_name]:', '#[order_id]', '[order_status]') );
						do_action('sa_send_sms', $admin_phone_number, self::parse_sms_content($order_id,$admin_sms_content,$new_status,$user_id));
					}
				}
			}				
			
		}
		
		//108,lp_teacher,Array ( [0] => customer ) 
		public static  function smsalert_lp_after_become_teacher($user_id, $role, $old_roles) {
			
			$buyer_sms_notify = smsalert_get_option( 'become_teacher', 'smsalert_lpress_general', 'on' );
			if($buyer_sms_notify=='on')
			{
				$billing_phone = get_user_meta($user_id,'billing_phone',true);
				$buyer_sms_content= smsalert_get_option( 'sms_body_become_teacher_msg', 'smsalert_lpress_message', '' );
				if($role=='lp_teacher')
				{
					do_action('sa_send_sms', $billing_phone, self::parse_sms_content(NULL,$buyer_sms_content,NULL,$user_id));
					
				}
			}
			
			$admin_sms_notify = smsalert_get_option( 'admin_become_teacher', 'smsalert_lpress_general', 'on' );
			if($admin_sms_notify=='on')
			{
				$admin_phone_number     = smsalert_get_option( 'sms_admin_phone', 'smsalert_message', '' );
				$admin_sms_content= smsalert_get_option( 'sms_body_admin_become_teacher_msg', 'smsalert_lpress_message', '' );
				if($role=='lp_teacher')
				{
					do_action('sa_send_sms', $billing_phone, self::parse_sms_content(NULL,$admin_sms_content,NULL,$user_id));
				}
			}
		}
}
new SmsAlertLearnPress;
