<?php
if (! defined( 'ABSPATH' )) exit;
	class WPLoginForm extends FormInterface
	{
		private $formSessionVar  	= FormSessionVars::WP_LOGIN_REG_PHONE;
		private $formSessionVar2 	= FormSessionVars::WP_DEFAULT_LOGIN;
		private $formSessionVar3 	= FormSessionVars::WP_LOGIN_WITH_OTP;
		private $phoneNumberKey;

		function handleForm()
		{	
			$this->phoneNumberKey = 'billing_phone';
			if(!empty($_REQUEST['learn-press-register-nonce'])){return;}
			$enabled_login_popup = smsalert_get_option( 'login_popup', 'smsalert_general', 'on');
			$this->routeData();
			$enabled_login_with_otp = smsalert_get_option( 'login_with_otp', 'smsalert_general', 'on');
			
			if($enabled_login_popup=='on')
			{
				add_action( 'woocommerce_login_form_end',	array($this,'smsalert_display_login_button_popup') );
			}
			else 
			{
				if($enabled_login_with_otp=='on')
				{
					add_action( 'woocommerce_login_form_end',	array($this,'smsalert_display_login_with_otp') );
					add_action( 'um_after_login_fields',  		array($this,'smsalert_display_login_with_otp'), 1002 );
				}
					
				add_filter( 'authenticate', 				array($this,'_handle_smsalert_wp_login'), 99, 4 );	
				
			}
			
			
			
			add_action( 'wp_footer',	array($this,'sa_login_handle_js_script') );
			
		}
		
		function routeData()
		{
			if(!array_key_exists('option', $_REQUEST)) return;
			switch (trim($_REQUEST['option'])) 
			{
				case "smsalert-ajax-otp-generate":
					$this->_handle_wp_login_ajax_send_otp($_POST);				break;
				case "smsalert-ajax-otp-validate":
					$this->_handle_wp_login_ajax_form_validate_action($_POST);	break;
				case "smsalert_ajax_form_validate":
					$this->_handle_wp_login_create_user_action($_POST);			break;
				case "smsalert_ajax_login_with_otp":
					$this->handle_login_with_otp();			break;
				case "smsalert_ajax_login_popup":
					$this->handle_login_popup();			break;
				case "smsalert_verify_login_with_otp":
					$this->process_login_with_otp();		break;
			}
		}
		
		/**login popup **/
		
		function handle_login_popup()
		{
			$username = !empty($_REQUEST['username']) ? $_REQUEST['username'] : '';
			$password = !empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
			
			//check user with username and password
			$user 					= $this->getUserIfUsernameIsPhoneNumber(NULL, $username, $password, $this->phoneNumberKey);
			
			if(!$user)
			{
				$user = wp_authenticate($username, $password);
			}
			
			if(is_wp_error($user))
				wp_send_json(SmsAlertUtility::_create_json_response("Invalid Username or Password",'error'));
			
			$user_meta 				= get_userdata($user->data->ID);
			$user_role 				= $user_meta->roles;
			$phone_number 			= get_user_meta($user->data->ID, $this->phoneNumberKey,true);
			
			if($this->byPassLogin($user_role)) return $user;
			
			SmsAlertUtility::initialize_transaction($this->formSessionVar3);
			smsalert_site_challenge_otp($username,null,null,$phone_number,"phone",$password,SmsAlertUtility::currentPageUrl(),true);
		}
		
		public function smsalert_display_login_button_popup() 
		{			
			$otp_resend_timer = smsalert_get_option( 'otp_resend_timer', 'smsalert_general', '15');
			echo '<input type="button" class="button smsalert_login_popup sa-otp-btn-init" name="smsalert_login_popup" value="Login">';
			echo '<script>
					jQuery(".smsalert_login_popup").parents("form").find("[type=\"submit\"]").css({"display":"none"});
					</script>';	
			$this->add_login_with_otp_popup();
			$this->enqueue_login_js_script();
		}
		/**login popup ends**/
		
		
		function handle_login_with_otp()
		{
			if(empty($_REQUEST['username']))
			{
				wp_send_json( SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('PHONE_NOT_FOUND'),'error'));
			}
			else
			{
				$phone_number = !empty($_REQUEST['username']) ? $_REQUEST['username'] : '';
				if($phone_number!='')
				{
					$user_info = $this->getUserFromPhoneNumber($phone_number,$this->phoneNumberKey);
					$user_login = ($user_info) ? $user_info->data->user_login : '';
				}
				
				if(!empty($user_login))
				{
					//SmsAlertUtility::checkSession();
					//$this->unsetOTPSessionVariables();
					//$_SESSION[$this->formSessionVar3]=true;
					SmsAlertUtility::initialize_transaction($this->formSessionVar3);
					smsalert_site_challenge_otp(null,null,null,$phone_number,"phone",null,SmsAlertUtility::currentPageUrl(),true);
				}
				else
				{
					wp_send_json( SmsAlertUtility::_create_json_response( SmsAlertMessages::showMessage('PHONE_NOT_FOUND'),'error'));
				}				
			}
		}

		public function smsalert_display_login_with_otp()
		{
			$otp_resend_timer = smsalert_get_option( 'otp_resend_timer', 'smsalert_general', '15');
			echo '<input type="button" class="button sa_myaccount_btn" name="sa_myaccount_btn_login" value="Login with OTP">';
			echo '<script>
			var parentForm =  jQuery(".sa_myaccount_btn").closest("form");
			
			jQuery(\'<form class="sa-lwo-form" method="post"></form>\').insertAfter( parentForm.not(".saParentForm"));
			parentForm.addClass("saParentForm");
			
			jQuery(".sa-lwo-form").hide();
			jQuery(document).on("click",".sa_myaccount_btn",function(){
				var parentForm =  jQuery(this).parents("form");
				parentForm.hide();
				parentForm.next(".sa-lwo-form").show();
			});
			
			
			jQuery(document).on("click",".sa_default_login_form",function(){
				parentForm.show();
				jQuery(this).parents("form").hide();
			});
			
			
			
			</script>';

			if(is_checkout())
			{
			echo '<script>
			jQuery(".showlogin").parents(".woocommerce-info").hide();
			 var parentForm =  jQuery(".showlogin").closest("div");
			jQuery(\'<div class="sa-woocommerce-info woocommerce-info" >Returning customer? <a href="#" class="sa-showlogin">Click here to login</a></div>\').insertAfter( parentForm );jQuery(".sa-woocommerce-info").parents(".woocommerce").find("form.sa-lwo-form").hide();
			jQuery(".sa-showlogin").click(function(){
			jQuery(".sa-lwo-form").toggle();
			return false;
			});
			</script>';
			}
			$this->add_login_with_otp_popup();
			$this->enqueue_login_js_script();
		}

		
		function add_login_with_otp_popup()
		{
			//if($this->guestCheckOutOnly && is_user_logged_in())  return;
			$otp_resend_timer = smsalert_get_option( 'otp_resend_timer', 'smsalert_general', '15');
			$enabled_login_popup = smsalert_get_option( 'login_popup', 'smsalert_general', 'on');
			$otp_template_style =  smsalert_get_option( 'otp_template_style', 'smsalert_general', 'otp-popup-1.php');
			
			if($enabled_login_popup=='on')
			{
				echo get_smsalert_template('template/'.$otp_template_style,$params=array());
				echo '<div class="login_with_otp_extra_fields"></div>';
			}
			else
			{
				echo '<div class="sa-lwo-form-holder">';
				echo get_smsalert_template('template/login_with_otp_form.php',array());
				echo get_smsalert_template('template/'.$otp_template_style,$params=array());
				echo '<div class="login_with_otp_extra_fields"></div>';
				echo '</div>';
			}
			
			
			echo '<script>
							var login_with_otp_form = jQuery(".sa-lwo-form-holder").html();
							jQuery(".sa-lwo-form").html(login_with_otp_form);
							jQuery(".sa-lwo-form-holder").remove();
			</script>';
			
			$otp_resend_timer = smsalert_get_option( 'otp_resend_timer', 'smsalert_general', '15');
		}
		
		function enqueue_login_js_script()
		{
			wp_register_script( 'smsalert-auth', SA_MOV_URL . 'js/otp-sms.min.js', array('jquery'), SmsAlertConstants::SA_VERSION, true );
			wp_enqueue_script('smsalert-auth');
		}
		function sa_login_handle_js_script()
		{
			$otp_resend_timer = smsalert_get_option( 'otp_resend_timer', 'smsalert_general', '15');
			$enabled_login_with_otp = smsalert_get_option( 'login_with_otp', 'smsalert_general', 'on');
			
			echo '<script>		
			var login_with_otp_form = jQuery(".sa-lwo-form-holder").html();
			jQuery(".sa-lwo-form").html(login_with_otp_form);
			jQuery(".sa-lwo-form-holder").remove();
			
			jQuery(".sa-lwo-form").each(function(i,item){
				if (jQuery(this).is(":empty")) { 
					jQuery(this).remove();
				} 
			});';
			
			echo '
			jQuery(document).on("click", ".sa-lwo-form .sa-otp-btn-init",function(){
				jQuery(this).parents(".smsalertModal").hide();
				var data = jQuery(this).parents("form").serialize()+"&login=Login";
				var action_url = "'.site_url().'/?option=smsalert_ajax_login_with_otp";
				saInitOTPProcess(this,action_url, data,'.$otp_resend_timer.');
			});
			
			jQuery(document).on("click", ".woocommerce-form-login .sa-otp-btn-init, .sa_popup .sa-otp-btn-init",function(){
				jQuery(this).parents(".smsalertModal").hide();
				var data = jQuery(this).parents("form").serialize()+"&login=Login";
				var action_url = "'.site_url().'/?option=smsalert_ajax_login_popup";
				saInitOTPProcess(this,action_url, data,'.$otp_resend_timer.');
			});
				
			
			
				 
			jQuery(document).on("click", ".woocommerce-form-login .smsalert_otp_validate_submit, .sa_popup .smsalert_otp_validate_submit, .sa-lwo-form .smsalert_otp_validate_submit",function(){
				var current_form = jQuery(this).parents("form");
				var action_url = "'.site_url().'/?option=smsalert-validate-otp-form";
				var data = current_form.serialize()+"&otp_type=phone&from_both=&login=Login";
				sa_validateOTP(this,action_url,data,function(){
					current_form.find(".login_with_otp_extra_fields").html("<input type=\"hidden\" name=\"login\" value=\"Login\">"), 
									
					((current_form.find(".sa_mobileno").length>0) ? submitLoginWithOTPForm(current_form) : current_form.submit())
					
				});
				return false;		   
			});
			';
			
			
			echo '
			function submitLoginWithOTPForm(parent_form)
			{
				
				$mo.ajax({
										url:"'.site_url().'/?option=smsalert_verify_login_with_otp",type:"POST",
										data:parent_form.serialize(),
										crossDomain:!0,
										dataType:"json",
										success:function(o){("success"==o.result && o.message=="Login successful")?
										(window.location.href=o.redirect):
										(
										current_form.find(".blockUI").hide(),
										current_form.find("#smsalert_login_message").empty().addClass("woocommerce-error"),
										current_form.find("#smsalert_login_message").append(o.message),
										current_form.find("#smsalert_login_message").removeClass("woocommerce-message"),
										current_form.find("#smsalert_login_customer_validation_otp_token").focus())
										},
										error:function(o,e,m)
										{
											alert("error found here");
										}
					});
					
					return false;
			}			
			</script>';
		}
		
		public static function isFormEnabled() 
		{
			//return (smsalert_get_option('buyer_login_otp', 'smsalert_general')=="on") ? true : false; //commented on 01-07-2019
			
			return (smsalert_get_option('buyer_login_otp', 'smsalert_general')=="on" || smsalert_get_option('login_with_otp', 'smsalert_general')=="on") ? true : false;
		}

		function check_wp_login_register_phone() 
		{
			return true; //get_option('mo_customer_validation_wp_login_register_phone') ? true : false;
		}

		function check_wp_login_by_phone_number()                                 
		{
			return true;//get_option('mo_customer_validation_wp_login_allow_phone_login') ? true : false;
		}
		
		function byPassLogin($user_role)
		{
			$current_role 		= array_shift($user_role);
			$excluded_roles 	= smsalert_get_option('admin_bypass_otp_login', 'smsalert_general',array());
			if(!is_array($excluded_roles) && $excluded_roles=='on')
			{
				$excluded_roles = ($current_role=='administrator') ? array('administrator') : array();
			}
			return in_array($current_role,$excluded_roles) ? true : false;			
		}

		function check_wp_login_restrict_duplicates()
		{
			return (smsalert_get_option('allow_multiple_user', 'smsalert_general')=="on") ? true : false;
		}

		function _handle_wp_login_create_user_action($postdata)
		{
			$redirect_to = isset($postdata['redirect_to'])?$postdata['redirect_to']:null;//added this line on 28-11-2018 due to affiliate login redirect issue
			
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar]) 
				|| $_SESSION[$this->formSessionVar]!='validated') 	return;

			$user = is_email( $postdata['log'] ) ? get_user_by("email",$postdata['log']) : get_user_by("login",$postdata['log']);
			if(!$user)
				$user = is_email( $postdata['username'] ) ? get_user_by("email",$postdata['username']) : get_user_by("login",$postdata['username']);
			
			update_user_meta($user->data->ID, $this->phoneNumberKey ,sanitize_text_field($postdata['mo_phone_number']));
			$this->login_wp_user($user->data->user_login,$redirect_to);
		}

		function login_wp_user($user_log, $extra_data=null)
		{
			$user = get_user_by("login",$user_log);
			wp_set_auth_cookie($user->data->ID);
			$this->unsetOTPSessionVariables();
			do_action( 'wp_login', $user->user_login, $user );	
			$redirect = SmsAlertUtility::isBlank($extra_data) ? site_url() : $extra_data;
			wp_redirect($redirect);
			exit;
		}
		
		//new function
		function process_login_with_otp()
		{
			SmsAlertUtility::checkSession();
			/*login with otp*/
			$login_with_otp_enabled = (smsalert_get_option('login_with_otp', 'smsalert_general')=="on") ? true : false;
			if(empty($password))
			{
				if(!empty($_REQUEST['username']))
				{
					$phone_number 	= !empty($_REQUEST['username'])?$_REQUEST['username']:'';
					$user_info 		= $this->getUserFromPhoneNumber($phone_number,$this->phoneNumberKey);
					$user_login 	= ($user_info) ? $user_info->data->user_login : '';
				}
			}
			
			if($login_with_otp_enabled && empty($password) && !empty($user_login) && !empty($_SESSION['login_otp_success']))
			{
				if ( ! empty( $_POST['redirect'] ) ) {
					$redirect 		= wp_sanitize_redirect( $_POST['redirect'] );
				} elseif ( wc_get_raw_referer() ) {
					$redirect 		= wc_get_raw_referer();
				} else {
					$redirect 		= wc_get_page_permalink( 'myaccount' );
				}
				unset($_SESSION['login_otp_success']);
				
				$user = get_user_by("login",$user_login);
				wp_set_auth_cookie($user->data->ID);
				$this->unsetOTPSessionVariables();
				$msg = SmsAlertUtility::_create_json_response("Login successful",'success');
				$msg['redirect'] = $redirect;
				wp_send_json( $msg);
				exit();
				
				
			}
			/*login with otp ends here*/
		}

		function _handle_smsalert_wp_login($user, $username, $password)
		{
			SmsAlertUtility::checkSession();
			/*login with otp*/
			$login_with_otp_enabled = (smsalert_get_option('login_with_otp', 'smsalert_general')=="on") ? true : false;
			
			if(empty($password))
			{
				if(!empty($_REQUEST['username']))
				{
					$phone_number 	= !empty($_REQUEST['username'])?$_REQUEST['username']:'';
					$user_info 		= $this->getUserFromPhoneNumber($phone_number,$this->phoneNumberKey);
					$user_login 	= ($user_info) ? $user_info->data->user_login : '';
				}
			}
			
			if($login_with_otp_enabled && empty($password) && !empty($user_login) && !empty($_SESSION['login_otp_success']))
			{
				if ( ! empty( $_POST['redirect'] ) ) {
					$redirect 		= wp_sanitize_redirect( $_POST['redirect'] );
				} elseif ( wc_get_raw_referer() ) {
					$redirect 		= wc_get_raw_referer();
				} else {
					$redirect 		= wc_get_page_permalink( 'myaccount' );
				}
				unset($_SESSION['login_otp_success']);
				$this->login_wp_user($user_login,$redirect);
			}
			/*login with otp ends here*/
			
			
			if((array_key_exists($this->formSessionVar,$_SESSION) && strcasecmp($_SESSION[$this->formSessionVar],'validated')==0) && !empty($_POST['mo_phone_number']))
			{
				update_user_meta($user->data->ID, $this->phoneNumberKey ,sanitize_text_field($_POST['mo_phone_number']));
				$this->unsetOTPSessionVariables();
			}
			
			if(isset($_SESSION['sa_login_mobile_verified']))
			{
				unset($_SESSION['sa_login_mobile_verified']);
				return $user;
			}
			
			$user 					= $this->getUserIfUsernameIsPhoneNumber($user, $username, $password, $this->phoneNumberKey);
			
			if(is_wp_error($user)) 
				return $user;
			
			$user_meta 				= get_userdata($user->data->ID);
			$user_role 				= $user_meta->roles;
			$phone_number 			= get_user_meta($user->data->ID, $this->phoneNumberKey,true);
			if($this->byPassLogin($user_role)) return $user;
			$this->askPhoneAndStartVerification($user,$this->phoneNumberKey,$username,$phone_number);
			$this->fetchPhoneAndStartVerification($user,$this->phoneNumberKey,$username,$password,$phone_number);
			return $user;
		} 

		function getUserIfUsernameIsPhoneNumber($user, $username, $password, $key)
		{
			if(!$this->check_wp_login_by_phone_number() || !SmsAlertUtility::validatePhoneNumber($username)) return $user;
			$user_info 				= $this->getUserFromPhoneNumber($username,$key);
			$username 				= is_object($user_info) ? $user_info->data->user_login : $username; //added on 20-05-2019			
			return wp_authenticate_username_password(NULL, $username, $password);
		}

		function getUserFromPhoneNumber($username,$key)
		{
			
			
			global $wpdb;
			
			$wcc_ph 		= SmsAlertcURLOTP::checkPhoneNos($username);
			$wocc_ph    	= SmsAlertcURLOTP::checkPhoneNos($username,false);
			$wth_pls_ph    	= '+'.$wcc_ph;
			
			$results 				= $wpdb->get_row("SELECT `user_id` FROM {$wpdb->base_prefix}usermeta inner join {$wpdb->base_prefix}users on ({$wpdb->base_prefix}users.ID = {$wpdb->base_prefix}usermeta.user_id) WHERE `meta_key` = '$key' AND `meta_value` in('$wcc_ph','$wocc_ph','$wth_pls_ph') order by user_id desc");
			$user_id 				= (!empty($results)) ? $results->user_id : 0;
			return get_userdata($user_id);
		}

		function askPhoneAndStartVerification($user,$key,$username,$phone_number)
		{
			if(!SmsAlertUtility::isBlank($phone_number)) return;
			if(!$this->check_wp_login_register_phone() )
				smsalert_site_otp_validation_form(null,null,null, SmsAlertMessages::showMessage('PHONE_NOT_FOUND'),null,null);
			else
			{
				SmsAlertUtility::initialize_transaction($this->formSessionVar);
				smsalert_external_phone_validation_form(SmsAlertUtility::currentPageUrl(), $user->data->user_login, __('A new security system has been enabled for you. Please register your phone to continue.',SmsAlertConstants::TEXT_DOMAIN), $key, array('user_login'=>$username));
			}					
		}

		function fetchPhoneAndStartVerification($user,$key,$username,$password,$phone_number)
		{
			if((array_key_exists($this->formSessionVar,$_SESSION) && strcasecmp($_SESSION[$this->formSessionVar],'validated')==0)
				|| (array_key_exists($this->formSessionVar2,$_SESSION) && strcasecmp($_SESSION[$this->formSessionVar2],'validated')==0)) return;
			SmsAlertUtility::initialize_transaction($this->formSessionVar2);
			
			//smsalert_site_challenge_otp($username,null,null,$phone_number[0],"phone",$password,$_REQUEST['redirect_to'],false);
			//smsalert_site_challenge_otp($username,null,null,$phone_number[0],"phone",$password,SmsAlertUtility::currentPageUrl(),false); //commented on 03-12-2018 get_user_meta set true
			smsalert_site_challenge_otp($username,null,null,$phone_number,"phone",$password,SmsAlertUtility::currentPageUrl(),false);
		}

		function _handle_wp_login_ajax_send_otp($data)
		{
			SmsAlertUtility::checkSession();
			if($this->check_wp_login_restrict_duplicates() 
				&& !SmsAlertUtility::isBlank($this->getUserFromPhoneNumber($data['billing_phone'],$this->phoneNumberKey)))
				wp_send_json(SmsAlertUtility::_create_json_response(__('Phone Number is already in use. Please use another number.',SmsAlertConstants::TEXT_DOMAIN),SmsAlertConstants::ERROR_JSON_TYPE));
			elseif(isset($_SESSION[$this->formSessionVar]))
			{
				smsalert_site_challenge_otp('ajax_phone','',null, trim($data['billing_phone']),"phone",null,$data, null);
			}
		}

		function _handle_wp_login_ajax_form_validate_action($data)
		{
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar])&&!isset($_SESSION[$this->formSessionVar2])&&!isset($_SESSION[$this->formSessionVar3])) return;
			
			if(strcmp($_SESSION['phone_number_mo'], $data['billing_phone']) && isset($data['billing_phone']))
				wp_send_json( SmsAlertUtility::_create_json_response( SmsAlertMessages::showMessage('PHONE_MISMATCH'),'error'));
			else
				do_action('smsalert_validate_otp','phone');
		}

		function handle_failed_verification($user_login, $user_email, $phone_number)
		{
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar]) && !isset($_SESSION[$this->formSessionVar2]) && !isset($_SESSION[$this->formSessionVar3])) return;

			if(isset($_SESSION[$this->formSessionVar])){	
				$_SESSION[$this->formSessionVar] = 'verification_failed';
				wp_send_json( SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'),'error'));
			}
			if(isset($_SESSION[$this->formSessionVar2]))
				smsalert_site_otp_validation_form($user_login,$user_email,$phone_number,SmsAlertMessages::showMessage('INVALID_OTP'),"phone",FALSE);
			if(isset($_SESSION[$this->formSessionVar3])){
				wp_send_json( SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'),'error'));
			}			
		}

		function handle_post_verification($redirect_to,$user_login,$user_email,$password,$phone_number,$extra_data)
		{
				SmsAlertUtility::checkSession();
				if(!isset($_SESSION[$this->formSessionVar]) && !isset($_SESSION[$this->formSessionVar2]) && !isset($_SESSION[$this->formSessionVar3])) return;
				
				if(isset($_SESSION[$this->formSessionVar]))
				{
					$_SESSION['sa_login_mobile_verified']=true;
					$_SESSION[$this->formSessionVar] = 'validated';
					wp_send_json( SmsAlertUtility::_create_json_response('successfully validated','success') );
				}
				elseif(isset($_SESSION[$this->formSessionVar3]))
				{
					$_SESSION['login_otp_success']=true;
					wp_send_json( SmsAlertUtility::_create_json_response("OTP Validated Successfully.",'success'));
					/* $user_info = $this->getUserFromPhoneNumber($phone_number,$this->phoneNumberKey);
					unset($_SESSION[$this->formSessionVar3]);
					
					if($user_info->data->user_login!='')
					{
						//$this->login_wp_user($user_info->data->user_login);
						$this->login_wp_user($user_info->data->user_login,$redirect_to); //for ultimate member
					} */
					
				}
				else
				{	
					$_SESSION['sa_login_mobile_verified']=true;
				}
		}

		public function unsetOTPSessionVariables()
		{
			unset($_SESSION[$this->txSessionId]);
			unset($_SESSION[$this->formSessionVar]);
			unset($_SESSION[$this->formSessionVar2]);
			unset($_SESSION[$this->formSessionVar3]);
		}

		public function is_ajax_form_in_play($isAjax)
		{
			SmsAlertUtility::checkSession();
			//return isset($_SESSION[$this->formSessionVar]) ? TRUE : $isAjax;
			return (isset($_SESSION[$this->formSessionVar]) || isset($_SESSION[$this->formSessionVar3])) ? TRUE : $isAjax;
		}

		function handleFormOptions()
	    {
			update_option('mo_customer_validation_wp_login_enable',
				isset( $_POST['mo_customer_validation_wp_login_enable']) ? $_POST['mo_customer_validation_wp_login_enable'] : 0);
			update_option('mo_customer_validation_wp_login_register_phone',
				isset( $_POST['mo_customer_validation_wp_login_register_phone']) ? $_POST['mo_customer_validation_wp_login_register_phone'] : '');
			update_option('mo_customer_validation_wp_login_bypass_admin',
				isset( $_POST['mo_customer_validation_wp_login_bypass_admin']) ? $_POST['mo_customer_validation_wp_login_bypass_admin'] : '');
			update_option('mo_customer_validation_wp_login_key',
				isset( $_POST['wp_login_phone_field_key']) ? $_POST['wp_login_phone_field_key'] : '');
			update_option('mo_customer_validation_wp_login_allow_phone_login',
				isset( $_POST['mo_customer_validation_wp_login_allow_phone_login']) ? $_POST['mo_customer_validation_wp_login_allow_phone_login'] : '');
			update_option('mo_customer_validation_wp_login_restrict_duplicates',
				isset( $_POST['mo_customer_validation_wp_login_restrict_duplicates']) ? $_POST['mo_customer_validation_wp_login_restrict_duplicates'] : '');
	    }
	}
	new WPLoginForm;