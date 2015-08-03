<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| COPYRIGHT NOTICE                                                     
| Copyright 2014-2015 JROX Technologies, Inc.  All Rights Reserved.    
| -------------------------------------------------------------------------                                                                        
| This script may be only used and modified in accordance to the license      
| agreement attached (license.txt) except where expressly noted within      
| commented areas of the code body. This copyright notice and the  
| comments above and below must remain intact at all times.  By using this 
| code you agree to indemnify JROX Technologies, Inc, its corporate agents   
| and affiliates from any liability that might arise from its use.                                                        
|                                                                           
| Selling the code for this program without prior written consent is       
| expressly forbidden and in violation of Domestic and International 
| copyright laws.  
|	
| -------------------------------------------------------------------------
| FILENAME - module_login_testing_model.php
| -------------------------------------------------------------------------     
| 
|
*/

class Module_Login_Testing_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _run_module_testing($type = 'public', $id = '')
	{
		//load an api from config/api.php
		
		echo '<h1>This is the login testing module being run after the affiliate user logs into his account/h1>';
		
		$data = $this->members_api->_get_member_details($id);
		//echo $this->encrypt->decode($data['member_data']['password']); exit();
		echo '<pre>'; print_r($data); exit();
		
		/* -------------------------------------------

		Array
			(
				[memberships] => Array
					(
						[0] => Array
							(
								[id] => 7
								[member_id] => 2
								[product_id] => 4
								[expires] => 1241420399
								[invoice_date] => 1241204400
								[invoice_id] => 14
								[total_intervals] => 0
								[intervals_left] => 0
								[subscription_id] => 
								[product_type] => membership
								[product_sku] => KGCS7I
								[product_status] => 0
								[product_name] => membership 1
								[product_featured] => 1
								[upsell_product] => 0
								[recommended_product] => 0
								[product_keywords] => 
								[product_new] => 1
								[product_trial_price] => 0.00
								[product_price] => 19.95
								[date_added] => 1237329996
								[date_modified] => 1237329996
								[date_available] => 1237329996
								[date_expires] => 
								[enable_product_trial] => 0
								[product_trial_interval] => 0
								[product_trial_interval_type] => 
								[recurring_onetime] => 0
								[recurring_interval] => 1
								[recurring_interval_type] => month
								[recurring_interval_ends] => 0
								[use_video_default] => 0
								[download_location_1] => 
								[download_location_2] => 
								[download_location_3] => 
								[download_location_4] => 
								[download_location_5] => 
								[download_location_6] => 
								[download_location_7] => 
								[download_location_8] => 
								[download_location_9] => 
								[download_location_10] => 
								[download_expires_days] => 0
								[max_downloads_per_user] => 0
								[membership_url_redirect] => 
								[product_weight] => 0
								[manufacturer_id] => 0
								[vendor_id] => 0
								[post_purchase_module_id] => 0
								[signup_module_id] => 0
								[expires_module_id] => 0
								[add_affiliate_group] => 0
								[add_discount_group] => 0
								[add_mailing_list] => 0
								[product_views] => 6
								[enable_product_inventory] => 2
								[product_inventory] => 0
								[add_cart_for_price] => 0
								[login_for_price] => 0
								[min_quantity_ordered] => 0
								[max_quantity_ordered] => 0
								[product_overview] => 
								[product_description_1] => 
								[product_description_2] => 
								[no_commission] => 0
								[enable_custom_commission] => 0
							)
			
					)
			
				[downloads] => 
				[mailing_lists] => Array
					(
						[0] => Array
							(
								[id] => 9
								[mailing_list_id] => 3
								[member_id] => 2
								[sequence_id] => 1
								[send_date] => 1238201287
							)
			
					)
			
				[photos] => 
				[affiliate_groups] => Array
					(
						[0] => Array
							(
								[id] => 3
								[group_type] => affiliate
								[group_id] => 1
								[member_id] => 2
							)
			
					)
			
				[discount_groups] => Array
					(
						[1] => Array
							(
								[id] => 4
								[group_type] => discount
								[group_id] => 2
								[member_id] => 2
							)
			
					)
			
				[member_data] => Array
					(
						[member_id] => 2
						[sponsor_id] => 0
						[status] => 1
						[fname] => Joe
						[lname] => Test
						[username] => joetest
						[password] => ZgEIhqhtXcxWSubPDhTs/Ryec0pBwjyknXSBR9F3G3ux7+jaLvQEBP1yhb9j5ksKMWopPbfz5Qlvz0rIsAIceQ==
						[company] => JROX
						[billing_address_1] => 123 Test Drive.
						[billing_address_2] => #101
						[billing_city] => New York
						[billing_state] => New York
						[billing_country] => 223
						[billing_postal_code] => 90210
						[payment_name] => 
						[payment_address_1] => 
						[payment_address_2] => 
						[payment_city] => 
						[payment_state] => 
						[payment_country] => 240
						[payment_postal_code] => 
						[home_phone] => 
						[work_phone] => 
						[mobile_phone] => 
						[fax] => 
						[payment_preference_amount] => 
						[primary_email] => test@jrox.com
						[paypal_id] => 
						[safepay_id] => 
						[moneybookers_id] => 
						[alertpay_id] => 
						[custom_id] => 
						[bank_transfer] => 
						[enable_affiliate_marketing] => 2
						[enable_custom_url] => 0
						[custom_url_link] => 
						[website] => 
						[jrox_custom_field_1] => 
						[jrox_custom_field_2] => 
						[jrox_custom_field_3] => 
						[jrox_custom_field_4] => 
						[jrox_custom_field_5] => 
						[jrox_custom_field_6] => 
						[jrox_custom_field_7] => 
						[jrox_custom_field_8] => 
						[jrox_custom_field_9] => 
						[jrox_custom_field_10] => example_1
						[profile_description] => 
						[alert_downline_signup] => 2
						[alert_new_commission] => 2
						[alert_payment_sent] => 2
						[allow_downline_view] => 2
						[allow_downline_email] => 2
						[last_login_date] => 1238765332
						[last_login_ip] => 192.168.15.7
						[signup_date] => 1237413510
						[updated_on] => 1238723618
						[updated_by] => 0
						[login_status] => 0
						[confirm_id] => V32CZ
						[shipping_default] => 1
						[shipping_fname] => Joe
						[shipping_lname] => Test
						[shipping_address_1] => 123 Test Drive.
						[shipping_address_2] => #101
						[shipping_city] => New York
						[shipping_state] => New York
						[shipping_country] => 223
						[shipping_postal_code] => 90210
						[member_credits] => 
					)
			
			)

		
		----------------------------------------------*/
	}
	
	// ------------------------------------------------------------------------	
}
?>