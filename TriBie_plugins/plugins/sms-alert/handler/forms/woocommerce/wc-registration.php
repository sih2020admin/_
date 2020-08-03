<?php
if (! defined( 'ABSPATH' )) exit;
	class WooCommerceRegistrationForm extends FormInterface
	{
		private $formSessionVar = FormSessionVars::WC_DEFAULT_REG;
		private $formSessionVar2 = FormSessionVars::WC_REG_POPUP;
		private $otpType;
		private $generateUserName;
		private $generatePassword;
		private $redirectToPage;
		private $popupEnabled;
		
		function handleForm()
		{
			$this->popupEnabled 	= (smsalert_get_option('register_otp_popup_enabled', 'smsalert_general')=="on") ? TRUE : FALSE;
			$this->otpType = get_option('mo_customer_validation_wc_enable_type');
			$this->generateUserName = get_option( 'woocommerce_registration_generate_username' );
			$this->generatePassword = get_option( 'woocommerce_registration_generate_password' );  
			$this->redirectToPage = get_option('mo_customer_validation_wc_redirect');
			if(isset($_REQUEST['register'])){
				add_filter('woocommerce_registration_errors', array($this,'woocommerce_site_registration_errors'),10,3);
			}
			
			//add on 15/05/2020
			if(is_plugin_active('dokan-lite/dokan.php'))
			{
				add_action( 'dokan_reg_form_field', array($this,'smsalert_add_dokan_phone_field') );
			}elseif(is_plugin_active('dc-woocommerce-multi-vendor/dc_product_vendor.php')) 
			{
				add_action( 'wcmp_vendor_register_form', array($this,'smsalert_add_phone_field') );
			}else 
			{
				add_action( 'woocommerce_register_form', array($this,'smsalert_add_phone_field') );
			}
			
			add_action( 'woocommerce_created_customer', array( $this, 'wc_user_created' ), 10, 2 );
			
			if($this->popupEnabled==TRUE){
			 add_action( 'woocommerce_register_form_end', array($this,'add_modal_html_register_otp') );
			 add_action( 'woocommerce_register_form_end', array($this,'smsalert_display_registerOTP_btn') );
			}
			
			//added on 30-01-2019 for user registeration
		add_action('smsalert_after_update_new_user_phone', array( $this,  'smsalert_after_user_register'), 10, 2 );
		
			$this->routeData();
		}
		
		
		/*send sms on user registration dated 30-01-2019*/
		function smsalert_after_user_register($user_id,$billing_phone) 
		{
		  $user 						= get_userdata($user_id);
		  $role 						= (!empty($user->roles[0])) ? $user->roles[0] : '';
		  $role_display_name			= (!empty($role)) ? self::get_user_roles($role): '';
		  $smsalert_reg_notify 			= smsalert_get_option( 'wc_user_roles_'.$role, 'smsalert_signup_general', 'off');
		  $sms_body_new_user 			= smsalert_get_option( 'signup_sms_body_'.$role, 'smsalert_signup_message' , SmsAlertMessages::showMessage('DEFAULT_NEW_USER_REGISTER') );

			
		  
		  $smsalert_reg_admin_notify 	= smsalert_get_option( 'admin_registration_msg', 'smsalert_general', 'off');
		  $sms_admin_body_new_user 		= smsalert_get_option( 'sms_body_registration_admin_msg', 'smsalert_message', SmsAlertMessages::showMessage('DEFAULT_ADMIN_NEW_USER_REGISTER') );
		  $admin_phone_number     		= smsalert_get_option( 'sms_admin_phone', 'smsalert_message', '' );
		  
		  $store_name 					= trim(get_bloginfo()); 	
		  /*let's send message to user on new registration*/
		  if($smsalert_reg_notify=='on' && $billing_phone!='')
		  {
				$search = array(
					'[username]',
					'[store_name]',
					'[email]',
					'[billing_phone]'
				);
				
				$replace = array(
					$user->user_login,
					$store_name,
					$user->user_email,
					$billing_phone
				);
				
				
				$sms_body_new_user 			= str_replace($search,$replace,$sms_body_new_user);
				do_action('sa_send_sms', $billing_phone, $sms_body_new_user);
				
		  }
		  
		  /*let's send message to admin on new registration*/
		  if($smsalert_reg_admin_notify=='on' && $admin_phone_number!='')
		  {
				$search=array(
					'[username]',
					'[store_name]',
					'[email]',
					'[billing_phone]',
					'[role]',
				);
				
				$replace=array(
					$user->user_login,
					$store_name,
					$user->user_email,
					$billing_phone,
					$role_display_name
				);
				
				$sms_admin_body_new_user = str_replace($search,$replace,$sms_admin_body_new_user);
				$nos = explode(",",$admin_phone_number);
			    $admin_phone_number = array_diff($nos,array("postauthor","post_author"));
			    $admin_phone_number = implode(",",$admin_phone_number);
				do_action('sa_send_sms', $admin_phone_number, $sms_admin_body_new_user);
		  }
		}
		
		public static function isFormEnabled()
		{
			return (smsalert_get_option('buyer_signup_otp', 'smsalert_general')=="on") ? true : false;
		}
		
		/*popup in modal*/
		function routeData()
		{
			if(!array_key_exists('option', $_REQUEST)) return;
			switch (trim($_REQUEST['option'])) 
			{
				case "smsalert_register_otp_validate_submit":
					$this->handle_ajax_register_validate_otp($_REQUEST);			break;
			}
		}
		
		
				
		function handle_ajax_register_validate_otp($data)
		{
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar2])) return;
			
			if(strcmp($_SESSION['phone_number_mo'], $data['billing_phone']))
				wp_send_json( SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('PHONE_MISMATCH'),'error'));
			else
				do_action('smsalert_validate_otp','phone');
		}
		
		
		
		public static function smsalert_display_registerOTP_btn() 
		{
			$otp_resend_timer = smsalert_get_option( 'otp_resend_timer', 'smsalert_general', '15');
			echo '<input type="submit" class="woocommerce-Button button smsalert_register_with_otp sa-otp-btn-init" name="register" value="Register" >';
			 
			 echo '<script>
			
			jQuery("[name=register]").not(".smsalert_register_with_otp").hide();
			jQuery(document).on("click", ".register .sa-otp-btn-init",function(){
				var current_form = jQuery(this).parents("form");
				var data = current_form.serialize()+"&register=Register";
				var action_url = "'.site_url().'/?option=smsalert_register_with_otp";
				saInitOTPProcess(
					this,
					action_url,
					data,
					'.$otp_resend_timer.',
					function(resp){
					},
					function(){
						current_form.find("#register_with_otp_extra_fields").html("<input type=\"hidden\" name=\"register\" value=\"Register\">"),
						current_form.submit()
					}
				
				);
				return false;
			});	  								
			
			jQuery(document).on("click", ".register .smsalert_otp_validate_submit",function(){
				var current_form = jQuery(this).parents("form");
				var action_url = "'.site_url().'/?option=smsalert-validate-otp-form";
				var data = current_form.serialize()+"&otp_type=phone&from_both=&register=Register";
				sa_validateOTP(this,action_url,data,function(){
					jQuery("#register_with_otp_extra_fields").html("<input type=\"hidden\" name=\"register\" value=\"Register\">"),
					current_form.submit()
				});
				
				return false;		   
			});			
			 
			 </script>';
			
		}
		
		function enqueue_reg_js_script()
		{
			wp_register_script( 'smsalert-auth', SA_MOV_URL . 'js/otp-sms.min.js', array('jquery'), SmsAlertConstants::SA_VERSION, true );
			wp_enqueue_script('smsalert-auth');
		}
		
		function add_modal_html_register_otp()
		{
			//if($this->guestCheckOutOnly && is_user_logged_in())  return;
			$otp_resend_timer = smsalert_get_option( 'otp_resend_timer', 'smsalert_general', '15');
			$otp_template_style =  smsalert_get_option( 'otp_template_style', 'smsalert_general', 'otp-popup-1.php');
			echo get_smsalert_template('template/'.$otp_template_style,$params=array());
			echo '<div id="register_with_otp_extra_fields"></div>';
			$this->enqueue_reg_js_script();	
			
		}
		/*popup in modal*/
		
		
		
		//this function created for updating and create a hook created on 29-01-2019
		public function wc_user_created($user_id, $data)
		{
			$post_data = wp_unslash( $_POST );
			
			if(array_key_exists('billing_phone', $post_data))
			{
				$billing_phone = $post_data['billing_phone'];
				update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $billing_phone ) );
				do_action('smsalert_after_update_new_user_phone',$user_id,$billing_phone);
			}
		}
		
		function show_error_msg($error_hook = NULL, $err_msg = NULL, $type = NULL)
		{
			if(isset($_SESSION[$this->formSessionVar2]))
			{
				wp_send_json( SmsAlertUtility::_create_json_response($err_msg,$type));
			}
			else
			{
				return new WP_Error( $error_hook,$err_msg);
			}
		}
		
		function woocommerce_site_registration_errors($errors,$username,$email)
		{
			SmsAlertUtility::checkSession();
			if(isset($_SESSION['sa_mobile_verified']))
			{
				unset($_SESSION['sa_mobile_verified']);
				return $errors;
			}
			$password = !empty($_REQUEST['password']) ? $_REQUEST['password'] :'';
			if(!SmsAlertUtility::isBlank(array_filter($errors->errors))) return $errors;
			if(isset($_REQUEST['option']) && $_REQUEST['option']=='smsalert_register_with_otp')
			{
				SmsAlertUtility::initialize_transaction($this->formSessionVar2); //set only when no error
			}
			else
			{
				SmsAlertUtility::initialize_transaction($this->formSessionVar);
			}
			
			if(smsalert_get_option('allow_multiple_user', 'smsalert_general')!="on" && !SmsAlertUtility::isBlank( $_POST['billing_phone']) ) {
				if(sizeof(get_users(array('meta_key' => 'billing_phone', 'meta_value' => $_POST['billing_phone']))) > 0 ) 
				{
					return new WP_Error('registration-error-number-exists',__( 'An account is already registered with this mobile number. Please login.', 'woocommerce' ));
				}
			}
		
		
			if ( isset($_POST['billing_phone']) && SmsAlertUtility::isBlank( $_POST['billing_phone']) ){
				
				return new WP_Error( "registration-error-invalid-phone",__( 'Please enter phone number.', 'woocommerce' ));
			}
			
			do_action( 'woocommerce_register_post', $username, $email, $errors );
			
			if($errors->get_error_code())
			{
				throw new Exception( $errors->get_error_message() );
			}
			
			//process and start the OTP verification process
			return $this->processFormFields($username,$email,$errors,$password); 	
		}
		
		function processFormFields($username,$email,$errors,$password)
		{
			global $phoneLogic;
			
			$phone_num = preg_replace('/[^0-9]/', '', $_POST['billing_phone']);
			
			if ( !isset( $_POST['billing_phone'] ) || !SmsAlertUtility::validatePhoneNumber($phone_num))
				return new WP_Error( 'billing_phone_error', str_replace("##phone##",$_POST['billing_phone'],$phoneLogic->_get_otp_invalid_format_message()) );
			smsalert_site_challenge_otp($username,$email,$errors,$phone_num,"phone",$password);
		}
		
		function smsalert_add_phone_field()
		{
			
			echo '<p class="form-row form-row-wide">
					<label for="reg_billing_phone">'.SmsAlertMessages::showMessage('Phone').'<span class="required">*</span></label>
					<input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="'.(!empty( $_POST['billing_phone'] ) ? $_POST['billing_phone'] : "").'" />
			  	  </p>';
			
		}
		
		//add on 15/05/2020
		function smsalert_add_dokan_phone_field()
		{
			
			echo '<p class="form-row form-row-wide">
					<label for="reg_billing_phone">'.SmsAlertMessages::showMessage('Phone').'<span class="required">*</span></label>
					<input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="'.(!empty( $_POST['billing_phone'] ) ? $_POST['billing_phone'] : "").'" />
			  	  </p>';
		?>		  
		<script>
			jQuery( window ).load(function() {
				jQuery('.user-role input[type="radio"]').change(function(e){
					if(jQuery(this).val() == "seller") {
						jQuery('#reg_billing_phone').parent().hide();
					}
					else {
						jQuery('#reg_billing_phone').parent().show();
					}
				});
				jQuery( "#shop-phone" ).change(function() {
					jQuery('#reg_billing_phone').val(this.value);
				});
			});
		</script>
		<?php
		}

		function handle_failed_verification($user_login,$user_email,$phone_number)
		{
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar]) && !isset($_SESSION[$this->formSessionVar2])) return;
			if(isset($_SESSION[$this->formSessionVar]))
				smsalert_site_otp_validation_form($user_login,$user_email,$phone_number,SmsAlertUtility::_get_invalid_otp_method(),"phone",FALSE);
			if(isset($_SESSION[$this->formSessionVar2]))
				wp_send_json( SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'),'error'));
			
		}

		function handle_post_verification($redirect_to,$user_login,$user_email,$password,$phone_number,$extra_data)
		{
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar]) && !isset($_SESSION[$this->formSessionVar2])) return;
			$_SESSION['sa_mobile_verified'] = true;
			if(isset($_SESSION[$this->formSessionVar2]))
				wp_send_json( SmsAlertUtility::_create_json_response("OTP Validated Successfully.",'success'));
		}
		
		public function unsetOTPSessionVariables()
		{
			unset($_SESSION[$this->txSessionId]);
			unset($_SESSION[$this->formSessionVar]);
			unset($_SESSION[$this->formSessionVar2]);
		}

		public function is_ajax_form_in_play($isAjax)
		{
			SmsAlertUtility::checkSession();
			return isset($_SESSION[$this->formSessionVar2]) ? TRUE : $isAjax;
		}

		function handleFormOptions()
		{
			add_action( 'sa_addTabs', array( $this, 'addTabs' ), 10 );
			add_filter('sAlertDefaultSettings', array( $this, 'addDefaultSetting'),1);
			
			update_option('mo_customer_validation_wc_default_enable',
				isset( $_POST['mo_customer_validation_wc_default_enable']) ? $_POST['mo_customer_validation_wc_default_enable'] : 0);
			update_option('mo_customer_validation_wc_enable_type',
				isset( $_POST['mo_customer_validation_wc_enable_type']) ? $_POST['mo_customer_validation_wc_enable_type'] : '');
			update_option('mo_customer_validation_wc_redirect',
				isset( $_POST['page_id']) ? get_the_title($_POST['page_id']) : 'My Account');
		}
		
		
		public static function addTabs($tabs=array())
		{
			$tabs['wc_register']['title']		= __("Sign Up Temp.",SmsAlertConstants::TEXT_DOMAIN);
			$tabs['wc_register']['tab_section']	= 'signup_templates';
			$tabs['wc_register']['tabContent']	= 'views/signup_template.php';
			$tabs['wc_register']['icon']		= 'dashicons-products';		
			return $tabs;
		}
		
		/*add default settings to savesetting in setting-options*/
		public static function addDefaultSetting($defaults=array())
		{
			$wc_user_roles = self::get_user_roles();
			foreach($wc_user_roles as $role_key => $role)
			{
				$defaults['smsalert_signup_general']['wc_user_roles_'.$role_key]	= 'off';
				$defaults['smsalert_signup_message']['signup_sms_body_'.$role_key]	= '';
			}
			return $defaults;
		}
		
		public static function get_user_roles($system_name=null)
		{
			global $wp_roles;
			$roles = $wp_roles->roles;
			
			if(!empty($system_name) && array_key_exists($system_name,$roles))
				return $roles[$system_name]['name'];
			else 
				return $roles;
		}
		
	}
	new WooCommerceRegistrationForm;