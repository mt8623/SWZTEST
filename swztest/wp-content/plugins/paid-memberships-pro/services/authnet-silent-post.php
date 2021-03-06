<?php
	global $isapage;
	$isapage = true;
	
	global $logstr;
	$logstr = "";
		
	//in case the file is loaded directly
	if(!defined("WP_USE_THEMES"))
	{
		define('WP_USE_THEMES', false);
		require_once(dirname(__FILE__) . '/../../../../wp-load.php');
	}
	
	global $wpdb;
	
	//some code taken from http://www.merchant-account-services.org/blog/handling-authorizenet-arb-subscription-failures/	
	// Flag if this is an ARB transaction. Set to false by default.
	$arb = false;

	// Store the posted values in an associative array
	$fields = array();
	foreach($_REQUEST as $name => $value)
	{
		// Create our associative array
		$fields[$name] = $value;
	 
		// If we see a special field flag this as an ARB transaction
		if($name == 'x_subscription_id')
		{
			$arb = true;
		}
	}
	
	$fields = apply_filters("pmpro_authnet_silent_post_fields", $fields);
	do_action("pmpro_before_authnet_silent_post", $fields);
	
	// If it is an ARB transaction, do something with it
	if($arb == true)
	{
		// okay, add an invoice. first lookup the user_id from the subscription id passed
		$old_order_id = $wpdb->get_var("SELECT id FROM $wpdb->pmpro_membership_orders WHERE subscription_transaction_id = '" . $wpdb->escape($fields['x_subscription_id']) . "' AND gateway = 'authorizenet' ORDER BY timestamp DESC LIMIT 1");
		$old_order = new MemberOrder($old_order_id);
		$user_id = $old_order->user_id;	
		$user = get_userdata($user_id);
		
		if($fields['x_response_code'] == 1)
		{					
			if($user_id)
			{
				//should we check for a dupe x_trans_id?
				
				//alright. create a new order/invoice
				$morder = new MemberOrder();
				$morder->user_id = $old_order->user_id;
				$morder->membership_id = $old_order->membership_id;
				$morder->InitialPayment = $fields['x_amount'];	//not the initial payment, but the class is expecting that
				$morder->PaymentAmount = $fields['x_amount'];
				$morder->payment_transaction_id = $fields['x_trans_id'];
				$morder->subscription_transaction_id = $fields['x_subscription_id'];
				
				$morder->FirstName = $fields['x_first_name'];
				$morder->LastName = $fields['x_last_name'];
				$morder->Email = $fields['x_email'];			
				$morder->Address1 = $fields['x_address'];
				$morder->City = $fields['x_city'];
				$morder->State = $fields['x_state'];
				$morder->CountryCode = $fields['x_country'];
				$morder->Zip = $fields['x_zip'];
				$morder->PhoneNumber = $fields['x_phone'];	
				
				$morder->billing->name = $fields['x_first_name'] . " " . $fields['x_last_name'];
				$morder->billing->street = $fields['x_address'];
				$morder->billing->city = $fields['x_city'];
				$morder->billing->state = $fields['x_state'];
				$morder->billing->zip = $fields['x_zip'];
				$morder->billing->country = $fields['x_country'];
				$morder->billing->phone = $fields['x_phone'];
				
				//get CC info that is on file
				$morder->cardtype = get_user_meta($user_id, "pmpro_CardType", true);
				$morder->accountnumber = hideCardNumber(get_user_meta($user_id, "pmpro_AccountNumber", true), false);
				$morder->expirationmonth = get_user_meta($user_id, "pmpro_ExpirationMonth", true);
				$morder->expirationyear = get_user_meta($user_id, "pmpro_ExpirationYear", true);	
				$morder->ExpirationDate = $morder->expirationmonth . $morder->expirationyear;
				$morder->ExpirationDate_YdashM = $morder->expirationyear . "-" . $morder->expirationmonth;
				
				//save
				$morder->saveOrder();
				$morder->getMemberOrderByID($morder->id);
								
				//email the user their invoice				
				$pmproemail = new PMProEmail();				
				$pmproemail->sendInvoiceEmail($user, $morder);								
			}	
		}
		elseif($fields['x_response_code'] == 2 || $fields['x_response_code'] == 3)
		{
			// Suspend the user's account
				//But we can't suspend the account, maybe a future feature

			//prep this order for the failure emails
			$morder = new MemberOrder();
			$morder->user_id = $user_id;
			$morder->billing->name = $fields['x_first_name'] . " " . $fields['x_last_name'];
			$morder->billing->street = $fields['x_address'];
			$morder->billing->city = $fields['x_city'];
			$morder->billing->state = $fields['x_state'];
			$morder->billing->zip = $fields['x_zip'];
			$morder->billing->country = $fields['x_country'];
			$morder->billing->phone = $fields['x_phone'];
			
			//get CC info that is on file
			$morder->cardtype = get_user_meta($user_id, "pmpro_CardType", true);
			$morder->accountnumber = hideCardNumber(get_user_meta($user_id, "pmpro_AccountNumber", true), false);
			$morder->expirationmonth = get_user_meta($user_id, "pmpro_ExpirationMonth", true);
			$morder->expirationyear = get_user_meta($user_id, "pmpro_ExpirationYear", true);										
						
			// Email the user and ask them to update their credit card information			
			$pmproemail = new PMProEmail();				
			$pmproemail->sendBillingFailureEmail($user, $morder);
			
			// Email admin so they are aware of the failure
			$pmproemail = new PMProEmail();				
			$pmproemail->sendBillingFailureAdminEmail(get_bloginfo("admin_email"), $morder);			
		}
		else
		{
			//response 4? send an email to the admin			
			$pmproemail = new PMProEmail();	
			$pmproemail->data = array("body"=>"<p>A payment is being held for review within Authorize.net.</p><p>Payment Information From Authorize.net:<br />" . nl2br(var_export($fields, true)));
			$pmproemail->sendEmail(get_bloginfo("admin_email"));			
		}
	}	

	do_action("pmpro_after_authnet_silent_post", $fields);