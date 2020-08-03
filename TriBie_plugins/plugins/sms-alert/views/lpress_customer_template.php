<?php 
$lpress_statuses = SmsAlertLearnPress::get_learnpress_status();
$become_teacher 					= smsalert_get_option('become_teacher', 'smsalert_lpress_general', 'on');
$student_notification_course_enroll = smsalert_get_option('course_enroll', 'smsalert_lpress_general', 'on');
$student_notification_course_finished = smsalert_get_option('course_finished', 'smsalert_lpress_general', 'on');
$sms_body_become_teacher_msg 		= smsalert_get_option( 'sms_body_become_teacher_msg', 'smsalert_lpress_message', SmsAlertMessages::showMessage('DEFAULT_NEW_TEACHER_REGISTER') );
$sms_body_course_enroll_msg 		= smsalert_get_option( 'sms_body_course_enroll', 'smsalert_lpress_message', SmsAlertMessages::showMessage('DEFAULT_USER_COURSE_ENROLL') );
$sms_body_course_finished_msg 		= smsalert_get_option( 'sms_body_course_finished', 'smsalert_lpress_message', SmsAlertMessages::showMessage('DEFAULT_USER_COURSE_FINISHED') );
?>
<!-- accordion -->	
<div class="cvt-accordion">
	<div class="accordion-section">
	<?php 
	 foreach($lpress_statuses as $ks => $vs)
	 {
		 $current_val = (is_array($lpress_statuses) && array_key_exists($vs, $lpress_statuses)) ? $lpress_statuses[$vs] : $vs;
		 ?>		
		<a class="cvt-accordion-body-title" href="javascript:void(0)" data-href="#accordion_cust_<?php echo $ks; ?>"><input type="checkbox" name="smsalert_lpress_general[lpress_order_status_<?php echo $vs; ?>]" id="smsalert_lpress_general[lpress_order_status_<?php echo $vs; ?>]" class="notify_box" 
		<?php echo ((smsalert_get_option( 'lpress_order_status_'.$vs, 'smsalert_lpress_general', 'on')=='on')?"checked='checked'":''); ?>/><label><?php _e( 'when Order is '.ucwords(str_replace('-', ' ', $vs )), SmsAlertConstants::TEXT_DOMAIN ) ?></label>
		<span class="expand_btn"></span>
		</a>		 
		<div id="accordion_cust_<?php echo $ks; ?>" class="cvt-accordion-body-content">
			<table class="form-table">
				<tr valign="top">
				<td><div class="smsalert_tokens"><?php echo SmsAlertLearnPress::getLPRESSvariables(); ?></div>
				<textarea name="smsalert_lpress_message[lpress_sms_body_<?php echo $vs; ?>]" id="smsalert_lpress_message[lpress_sms_body_<?php
				echo $vs; ?>]" <?php echo(($current_val==$vs)?'' : "readonly='readonly'"); ?>><?php echo smsalert_get_option('lpress_sms_body_'.$vs, 'smsalert_lpress_message', sprintf(__('Hello %s, status of your %s with %s has been changed to %s.',SmsAlertConstants::TEXT_DOMAIN), '[username]', '[order_id]', '[store_name]', '[order_status]')); ?></textarea>
				</td>
				</tr>
			</table>
		</div>
		 <?php
	 }
	 ?>
	 
	<!--course enroll student-->
	<a class="cvt-accordion-body-title" href="javascript:void(0)" data-href="#accordion_course_enroll">
	<input type="checkbox" name="smsalert_lpress_general[course_enroll]" id="smsalert_lpress_general[course_enroll]" class="notify_box" <?php echo (($student_notification_course_enroll=='on')?"checked='checked'":'')?>/>
	<label><?php _e( 'When a student enrolls course', SmsAlertConstants::TEXT_DOMAIN ) ?></label>
	<span class="expand_btn"></span>
	</a>
	<div id="accordion_course_enroll" class="cvt-accordion-body-content">
		<table class="form-table">
			<tr valign="top">
			<td>
			<div class="smsalert_tokens"><?php echo SmsAlertLearnPress::getLPRESSvariables('courses'); ?></div>
			<textarea name="smsalert_lpress_message[sms_body_course_enroll]" id="smsalert_lpress_message[sms_body_course_enroll]"><?php echo $sms_body_course_enroll_msg; ?></textarea>
			</td>
			</tr>
		</table>
	</div>
	<!--/course enroll student-->
	
	<!--course finished student-->
	<a class="cvt-accordion-body-title" href="javascript:void(0)" data-href="#accordion_course_finished">
	<input type="checkbox" name="smsalert_lpress_general[course_finished]" id="smsalert_lpress_general[course_finished]" class="notify_box" <?php echo (($student_notification_course_finished=='on')?"checked='checked'":'')?>/>
	<label><?php _e( 'When a student finishes course', SmsAlertConstants::TEXT_DOMAIN ) ?></label>
	<span class="expand_btn"></span>
	</a>
	<div id="accordion_course_finished" class="cvt-accordion-body-content">
		<table class="form-table">
			<tr valign="top">
			<td>
			<div class="smsalert_tokens"><?php echo SmsAlertLearnPress::getLPRESSvariables('courses'); ?></div>
			<textarea name="smsalert_lpress_message[sms_body_course_finished]" id="smsalert_lpress_message[sms_body_course_finished]"><?php echo $sms_body_course_finished_msg; ?></textarea>
			</td>
			</tr>
		</table>
	</div>
	<!--/course finished student-->
	
	<!--become_a_teacher-->
	<a class="cvt-accordion-body-title" href="javascript:void(0)" data-href="#accordion_become_a_teacher">
	<input type="checkbox" name="smsalert_lpress_general[become_teacher]" id="smsalert_lpress_general[become_teacher]" class="notify_box" <?php echo (($become_teacher=='on')?"checked='checked'":'')?>/>
	<label><?php _e( 'When new teacher created', SmsAlertConstants::TEXT_DOMAIN ) ?></label>
	<span class="expand_btn"></span>
	</a>
	<div id="accordion_become_a_teacher" class="cvt-accordion-body-content">
		<table class="form-table">
			<tr valign="top">
			<td>
			<div class="smsalert_tokens"><?php echo SmsAlertLearnPress::getLPRESSvariables('teacher'); ?></div>
			<textarea name="smsalert_lpress_message[sms_body_become_teacher_msg]" id="smsalert_lpress_message[sms_body_become_teacher_msg]"><?php echo $sms_body_become_teacher_msg; ?></textarea>
			</td>
			</tr>
		</table>
	</div>
	<!--/become_a_teacher-->	 
	</div>
</div>
<!--end accordion-->	