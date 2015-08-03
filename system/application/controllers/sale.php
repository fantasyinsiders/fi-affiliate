<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| COPYRIGHT NOTICE                                                     
| Copyright 2007 - 2015 JROX Technologies, Inc.  All Rights Reserved.   
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
| FILENAME - sale.php
| -------------------------------------------------------------------------     
| 
| This controller file is for triggering commissions
|
*/
class Sale extends Sale_Controller {

	function __construct()
	{
		parent::Sale_Controller();	
		
		header($this->config->item('p3p_header'));
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		$comm_notes = '';
		
		//load the default language
		$this->lang->load('common', $this->config->item('sts_site_default_language'));
		
		//check if the sale is using GET or POST
		if ($this->uri->segment(2) != 'amount') { $seg = 3; } else { $seg = 2; }
		
		$sdata = $this->uri->uri_to_assoc($seg);	

		//check for post data
		if (!empty($_POST))
		{
			$sdata = array_merge($sdata, $_POST);
		}
		
		//parse variables from different sources (paypal?)
		if ($this->uri->segment(2) != 'amount')
		{
			//parse values for post type
			$sdata = $this->comms->_get_sale_values($this->uri->segment(2), $sdata);
		}
		
		//check security
		$this->comms->_run_comm_checks($sdata);
		
		//check for tracking cookie
		
		$cdata = $this->aff->_get_ad_tracker();
		
		if (!empty($cdata))
		{
			$aff_data = $this->aff->_validate_user($cdata['username']);
		}
		
		if (empty($aff_data))
		{
			$cdata = $this->aff->_get_tracking_cookie($sdata);
		}
		
		if (!empty($cdata)) { $sdata = array_merge($cdata, $sdata); }

		//check for lifetime sponsor
		if (!empty($sdata['lf_data']))
		{
			//check tracking log
			$lf_id = $this->aff->_get_lifetime_sponsor($sdata['lf_data']);
			if (!empty($lf_id)) { $sdata['member_id'] =  $lf_id; }
		}
		
		//check for manual program ID
		if (empty($sdata['program_id'])) 
		{ 
			if ($this->config->item('program_id_required') == true)
			{
				die('program ID required');	
			}
			else
			{
				$sdata['program_id'] = 1; 
			}
		}
		
		//get program data
		
		$prog = $this->programs_model->_get_program_basic('program_id', $sdata['program_id']);
		
		if (empty($prog)) 
		{ 
			die('invalid program'); 
		}
		elseif (empty($prog['enable_pay_per_action']))
		{
			die('pay per action not enabled'); 
		}
		
		$this->init->_set_sale_program($prog);

		//set data array
		$data = $this->security_model->_load_config('public', __CLASS__,  __FUNCTION__);

		//initialize site with required db config
		$fdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//check for session id if there is no member ID set
		if (empty($sdata['member_id']))
		{
			$sdata['member_id'] = !empty($fdata['aff_cfg']['jrox_referring_userid']) ? $fdata['aff_cfg']['jrox_referring_userid'] : '';	
		}
		
		//check for member ID via IP if there is still none
		if (empty($sdata['member_id']))
		{
			//check tracking log
			$sdata['member_id'] = $this->aff->_get_ip_sponsor($this->input->ip_address());				
		}
		
		//check for default member ID if set
		if (empty($sdata['member_id']) && $this->config->item('default_referral_id'))
		{
			//check tracking log
			$sdata['member_id'] = $this->config->item('default_member_id');		
		}
		
		//check if there is a valid coupon code and use that affiliate instead
		if (!empty($sdata['coupon_code']))
		{
			$this->load->model('coupons_model');
			$coupon_data = $this->coupons_model->_get_coupon_details($sdata['coupon_code'], 'coupon_code', true);
			
			if (!empty($coupon_data))
			{
				$sdata['member_id'] = $coupon_data['member_id'];
				
			}
		}
		
		//if there is still no member ID exit
		if (empty($sdata['member_id'])) { die('no member ID'); }
		
		//check for a valid member ID
		$aff_data = $this->aff->_validate_user($sdata['member_id'], true);
		
		if (empty($aff_data)) { die('invalid member ID'); }
		
		//check for self restriction
		if (!empty($sdata['self_restrict']) && $this->config->item('sts_affiliate_restrict_self_commission') == 1)
		{
			if (strtolower(trim($sdata['self_restrict'])) == $aff_data['primary_email']) 
			{ 
				die('restrict self comm'); 
			}
		}
		
		//check for transaction ID requirement
		if ($this->config->item('prg_require_trans_id') == 1)
		{
			if (empty($sdata['trans_id'])) { die('transaction ID required'); }
		}
		
		//check currency ISO code (for currency conversion)
		if (!empty($sdata['currency']))
		{
			$sdata['amount'] = $this->curr->_convert_amount($sdata['amount'], $sdata['currency']);	
		}		
		
		//check for affiliate group
		$sdata['group_id'] = $this->config->item('prg_group_id');
		
		//check if user has a custom affiliate group
		if (!empty($aff_data['group_id'])) { $sdata['group_id'] = $aff_data['group_id']; }
		
		$comm_notes .= "\n" . ' group_id - ' . $sdata['group_id'];
		
		//get all admins for sending out admin alerts
		$this->load->model('admins_model');
		$admin_users = $this->admins_model->_get_all_admins();
		
		$num_options = get_default_currency($this->config->item('sts_site_default_currency'));
		
		//set the commission levels
		$sdata['levels'] = $this->config->item('prg_commission_levels');
		
		//set the commission status		
		switch ($this->config->item('prg_new_commission_option'))
		{
			case 'no_pending':
			case 'alert_pending':
				
				$comm_status = 'pending';
				
			break;
			
			default:
			
				$comm_status = 'unpaid';
				
			break;
		}
		
		//check for action commission
		if (!empty($sdata['action_comm']))
		{
			$action_comm = $this->comms->_get_action_commission_details($sdata['action_comm'], 'action_commission_name', true);
			$comm_notes .= "\n" . ' action_commission - ' . $action_comm['action_commission_name'];
		}
        elseif (!empty($sdata['product_identifier']))
        {
           $per_product = true;
            $action_comm = $this->comms->_get_product_commission_details($sdata['product_identifier'], 'product_id', true);
			$comm_notes .= "\n" . ' per_product - ' . $action_comm['product_id'];
        }
		
		//use coupon code
		if (!empty($coupon_data))
		{
			if ($coupon_data['use_program_comms'] == '0')
			{
				$action_comm = array(
										'type' => $coupon_data['type'],
										'amount' => $coupon_data['amount'],
									);
			}
			
			$comm_notes .= "\n" . ' coupon_code - ' . $coupon_data['coupon_code'];
		}
		
		if (!empty($action_comm))
		{
			//check amount
			if (empty($sdata['amount'])) { $sdata['amount'] = '0'; }
		
			$sdata['levels'] = '1';
			$sdata['set_action_comm'] = true;
			
			if ($action_comm['type'] == 'percent')
			{
				$sdata['comm_amount'] = $action_comm['amount'] * $sdata['amount'];	
			}
			else
			{
				$sdata['comm_amount'] = $action_comm['amount'];		
			}
			
			$sdata['comm_amount'] = format_amounts($sdata['comm_amount'], $num_options, true);
			
			$rewards_sale_amount = $sdata['amount'];
			$rewards_comm_amount = $sdata['comm_amount'];			
			
			if (isset($sdata['comm_amount']))
			{
				//add the commission				
				$comm_array = array(
					'member_id' 						=> 			$sdata['member_id'],
					'program_id' 						=> 			$sdata['program_id'],
					'invoice_id' 						=> 			empty($sdata['invoice_id']) ? '' : $sdata['invoice_id'],
					'comm_status' 						=>			$comm_status,
					'approved' 							=>			!empty($action_comm['auto_approve']) ? '1' : '0',
					'date' 								=>			_generate_timestamp(),
					'commission_amount'					=>			format_amounts($sdata['comm_amount'], $num_options, true),
					'sale_amount' 						=>			$sdata['amount'],
					'commission_level' 					=>			1,
					'referrer' 							=>			empty($sdata['referrer']) ? '' : $sdata['referrer'],
					'trans_id' 							=>			$sdata['trans_id'],
					'ip_address' 						=>			$this->input->ip_address(),
					'commission_notes' 					=>			!empty($comm_notes) ? $comm_notes : '',
					'performance_paid' 					=>			'0',
					'email_sent' 						=>			'0',
					'tool_type' 						=>			empty($sdata['tool_type']) ? '' : $sdata['tool_type'],
					'tool_id' 							=>			empty($sdata['tool_id']) ? '' : $sdata['tool_id'],
					'action_commission_id' 				=>			!empty($per_product) ? '' : $action_comm['id'],
                    'product_id' 				        =>			!empty($action_comm['product_id']) ? $action_comm['product_id'] : '0',
					'tracking_id' 						=>			empty($_COOKIE[$this->config->item('tracking_cookie_name')]) ? '' : $_COOKIE[$this->config->item('tracking_cookie_name')],
					'customer_name' 					=>			empty($sdata['customer_name']) ? '' : $sdata['customer_name'],
					'custom_commission_field_1' 		=>			empty($sdata['custom_field_1']) ? '' : $sdata['custom_field_1'],
					'custom_commission_field_2' 		=>			empty($sdata['custom_field_2']) ? '' : $sdata['custom_field_2'],
					'custom_commission_field_3' 		=>			empty($sdata['custom_field_3']) ? '' : $sdata['custom_field_3'],
					'custom_commission_field_4' 		=>			empty($sdata['custom_field_4']) ? '' : $sdata['custom_field_4'],
					'custom_commission_field_5' 		=>			empty($sdata['custom_field_5']) ? '' : $sdata['custom_field_5'],
					'custom_commission_field_6' 		=>			empty($sdata['custom_field_6']) ? '' : $sdata['custom_field_6'],
					'custom_commission_field_7' 		=>			empty($sdata['custom_field_7']) ? '' : $sdata['custom_field_7'],
					'custom_commission_field_8' 		=>			empty($sdata['custom_field_8']) ? '' : $sdata['custom_field_8'],
					'custom_commission_field_9' 		=>			empty($sdata['custom_field_9']) ? '' : $sdata['custom_field_9'],
					'custom_commission_field_10' 		=>			empty($sdata['custom_field_10']) ? '' : $sdata['custom_field_10'],
					'custom_commission_field_11' 		=>			empty($sdata['custom_field_11']) ? '' : $sdata['custom_field_11'],
					'custom_commission_field_12' 		=>			empty($sdata['custom_field_12']) ? '' : $sdata['custom_field_12'],
					'custom_commission_field_13' 		=>			empty($sdata['custom_field_13']) ? '' : $sdata['custom_field_13'],
					'custom_commission_field_14' 		=>			empty($sdata['custom_field_14']) ? '' : $sdata['custom_field_14'],
					'custom_commission_field_15' 		=>			empty($sdata['custom_field_15']) ? '' : $sdata['custom_field_15'],
					'custom_commission_field_16' 		=>			empty($sdata['custom_field_16']) ? '' : $sdata['custom_field_16'],
					'custom_commission_field_17' 		=>			empty($sdata['custom_field_17']) ? '' : $sdata['custom_field_17'],
					'custom_commission_field_18' 		=>			empty($sdata['custom_field_18']) ? '' : $sdata['custom_field_18'],
					'custom_commission_field_19' 		=>			empty($sdata['custom_field_19']) ? '' : $sdata['custom_field_19'],
					'custom_commission_field_20' 		=>			empty($sdata['custom_field_20']) ? '' : $sdata['custom_field_20'],
					'tracker'							=> 			empty($sdata['tracker']) ? '' : $sdata['tracker'],
					'order_id' 							=>			empty($sdata['order_id']) ? '' : $sdata['order_id'],
					);
				
				$this->comms->_add_commission($comm_array);
				
				//add tracking code for lifetime tracking
				$this->aff->_add_lifetime_sponsor($sdata);
	
				
				//send out alert email if set
				if ($this->config->item('sts_affiliate_new_commission') == 'alert_pending' || $this->config->item('sts_affiliate_new_commission') == 'alert_unpaid')
				{
					if ($this->config->item('sts_affiliate_alert_new_commission') == 1)
					{
						if ($aff_data['alert_new_commission'] != '0') //send out the email only if the user wants it
						{
							$aff_data['commission_amount'] = $sdata['comm_amount'];
							$aff_data['sale_amount'] = $sdata['amount'];
							$aff_data['customer_name'] = empty($sdata['customer_name']) ? '' : $sdata['customer_name'];
							$this->emailing_model->_send_template_email('member', $aff_data, 'member_affiliate_commission_generated_template', false, $this->config->item('prg_program_id'));
						}
					}
				}
				
				//send out admin alerts for new commission
				foreach ($admin_users as $admin_user)
				{
					if ($admin_user['alert_affiliate_commission'] == 1) //send out the admin alert
					{
						$admin_user['member_username'] = $aff_data['username'];
						$admin_user['commission_amount'] = $sdata['comm_amount'];
						$admin_user['sale_amount'] = $sdata['amount'];
						$admin_user['customer_name'] = empty($sdata['customer_name']) ? '' : $sdata['customer_name'];
						$this->emailing_model->_send_template_email('admin', $admin_user, 'admin_affiliate_commission_generated_template', false, $this->config->item('prg_program_id'));
					}
				}
				
				//send debug email if needed
				if ($this->config->item('sts_integration_enable_debug_email') == 1) 
				{	
					$sdata['current_level'] = $level;
					$sdata['username'] = $aff_data['username'];
					$this->security_model->_send_debug_email('Integration Debug Email', $sdata);
					echo '<pre>'; print_r($sdata);
				}
			}
		}
		else
		{
			//check amount
			if (!defined('ALLOW_ZERO_AMOUNT_COMMISSIONS'))
			{
				if (empty($sdata['amount'])) { die('amount required'); }
			}
			
			//generate the downline array of members
			$members = $this->downline->_get_upline($sdata['member_id'], $sdata['levels']);
            krsort($members);
			//load default program
			$group_data = $this->groups->_get_aff_group_details($this->config->item('prg_group_id'));	
			
			foreach ($members as $k => $v)
			{
				$level = $k;
				
				$group_data['commission_type_' . $level] = $group_data['commission_type'];
				
				//check scaled commissions
				$scaled_comm = $this->comms->_check_scaled_commission($sdata['amount'], $level);
				
				if (!empty($scaled_comm))
				{
					$group_data['commission_type_' . $level] = $scaled_comm['type'];
					$group_data['commission_level_' . $level] = $scaled_comm['comm_amount'];
				}
				else
				{
					//check for custom affiliate group
					if (!empty($v['group_id']))
					{
						$aff_group = $this->groups->_get_aff_group_details($v['group_id']);
						
						if (!empty($aff_group['commission_level_' . $level]))
						{
							$group_data['commission_type_' . $level] = $aff_group['commission_type'];
							$group_data['commission_level_' . $level] = $aff_group['commission_level_' . $level];
						}
					}
				}
				
				//calculate the commission
				if ($group_data['commission_type_' . $level] == 'percent')
				{
					$sdata['comm_amount'] = $group_data['commission_level_' . $level] * $sdata['amount'];
				}
				else
				{
					$sdata['comm_amount'] = $group_data['commission_level_' . $level];
				}
				
				//calculate the commission amount
				if ($level > 1)
				{
					$sale_amount = '0.00';
				}
				else
				{
					$sale_amount = $sdata['amount'];
					$rewards_sale_amount = $sale_amount;
					$rewards_comm_amount = $sdata['comm_amount'];
				}
				
				$sdata['comm_amount'] = format_amounts($sdata['comm_amount'], $num_options, true);
				//check for recurring commission
				if ($this->config->item('prg_commission_frequency'))
				{
					$recur = _generate_timestamp() + (3600 * 24 * $this->config->item('prg_commission_frequency'));	
					$recurring_comm = '1';
				}
				else
				{
					$recur = '0';	
				}
				
				if ($sdata['comm_amount'] > '0')
				{	
					//add the commission				
					$comm_array = array(
							'member_id' 						=> 			$v['member_id'],
							'program_id' 						=> 			$sdata['program_id'],
							'invoice_id' 						=> 			empty($sdata['invoice_id']) ? '' : $sdata['invoice_id'],
							'comm_status' 						=>			$comm_status,
							'approved' 							=>			'0',
							'date' 								=>			_generate_timestamp(),
							'commission_amount'					=>			format_amounts($sdata['comm_amount'], $num_options, true),
							'sale_amount' 						=>			$sale_amount,
							'commission_level' 					=>			$level,
							'referrer' 							=>			empty($sdata['referrer']) ? '' : $sdata['referrer'],
							'trans_id' 							=>			$sdata['trans_id'],
							'ip_address' 						=>			$this->input->ip_address(),
							'commission_notes' 					=>			!empty($comm_notes) ? $comm_notes : '',
							'performance_paid' 					=>			'0',
							'email_sent' 						=>			'0',
							'tool_type' 						=>			empty($sdata['tool_type']) ? '' : $sdata['tool_type'],
							'tool_id' 							=>			empty($sdata['tool_id']) ? '' : $sdata['tool_id'],
							'tracking_id' 						=>			empty($_COOKIE[$this->config->item('tracking_cookie_name')]) ? '' : $_COOKIE[$this->config->item('tracking_cookie_name')],
							'customer_name' 					=>			empty($sdata['customer_name']) ? '' : $sdata['customer_name'],
							'custom_commission_field_1' 		=>			empty($sdata['custom_field_1']) ? '' : $sdata['custom_field_1'],
							'custom_commission_field_2' 		=>			empty($sdata['custom_field_2']) ? '' : $sdata['custom_field_2'],
							'custom_commission_field_3' 		=>			empty($sdata['custom_field_3']) ? '' : $sdata['custom_field_3'],
							'custom_commission_field_4' 		=>			empty($sdata['custom_field_4']) ? '' : $sdata['custom_field_4'],
							'custom_commission_field_5' 		=>			empty($sdata['custom_field_5']) ? '' : $sdata['custom_field_5'],
							'custom_commission_field_6' 		=>			empty($sdata['custom_field_6']) ? '' : $sdata['custom_field_6'],
							'custom_commission_field_7' 		=>			empty($sdata['custom_field_7']) ? '' : $sdata['custom_field_7'],
							'custom_commission_field_8' 		=>			empty($sdata['custom_field_8']) ? '' : $sdata['custom_field_8'],
							'custom_commission_field_9' 		=>			empty($sdata['custom_field_9']) ? '' : $sdata['custom_field_9'],
							'custom_commission_field_10' 		=>			empty($sdata['custom_field_10']) ? '' : $sdata['custom_field_10'],
							'custom_commission_field_11' 		=>			empty($sdata['custom_field_11']) ? '' : $sdata['custom_field_11'],
							'custom_commission_field_12' 		=>			empty($sdata['custom_field_12']) ? '' : $sdata['custom_field_12'],
							'custom_commission_field_13' 		=>			empty($sdata['custom_field_13']) ? '' : $sdata['custom_field_13'],
							'custom_commission_field_14' 		=>			empty($sdata['custom_field_14']) ? '' : $sdata['custom_field_14'],
							'custom_commission_field_15' 		=>			empty($sdata['custom_field_15']) ? '' : $sdata['custom_field_15'],
							'custom_commission_field_16' 		=>			empty($sdata['custom_field_16']) ? '' : $sdata['custom_field_16'],
							'custom_commission_field_17' 		=>			empty($sdata['custom_field_17']) ? '' : $sdata['custom_field_17'],
							'custom_commission_field_18' 		=>			empty($sdata['custom_field_18']) ? '' : $sdata['custom_field_18'],
							'custom_commission_field_19' 		=>			empty($sdata['custom_field_19']) ? '' : $sdata['custom_field_19'],
							'custom_commission_field_20' 		=>			empty($sdata['custom_field_20']) ? '' : $sdata['custom_field_20'],
							'recur'								=> 			$recur,
							'recurring_comm'					=>			!empty($recur) ? '1' : '0',
							'tracker'							=> 			empty($sdata['tracker']) ? '' : $sdata['tracker'],
							'order_id' 							=>			empty($sdata['order_id']) ? '' : $sdata['order_id'],
					);
						
					$this->comms->_add_commission($comm_array);
				
					//add tracking code for lifetime tracking
					$this->aff->_add_lifetime_sponsor($sdata);
						
					//check for performance rewards for sale or commission amount	
						
					//send out alert email if set
					if ($this->config->item('sts_affiliate_new_commission') == 'alert_pending' || $this->config->item('sts_affiliate_new_commission') == 'alert_unpaid')
					{
						if ($aff_data['alert_new_commission'] != '0') //send out the email only if the user wants it
						{
							$v['commission_amount'] = $sdata['comm_amount'];
							$v['sale_amount'] = $sdata['amount'];
							$v['customer_name'] = empty($sdata['customer_name']) ? '' : $sdata['customer_name'];
							$this->emailing_model->_send_template_email('member', $v, 'member_affiliate_commission_generated_template', false, $this->config->item('prg_program_id'));
						}
					}
					
					//send out admin alerts for new commission
					foreach ($admin_users as $admin_user)
					{
						if ($admin_user['alert_affiliate_commission'] == 1) //send out the admin alert
						{
							$admin_user['member_username'] = $v['username'];
							$admin_user['commission_amount'] = $sdata['comm_amount'];
							$admin_user['sale_amount'] = $sdata['amount'];
							$admin_user['customer_name'] = empty($sdata['customer_name']) ? '' : $sdata['customer_name'];
							$this->emailing_model->_send_template_email('admin', $admin_user, 'admin_affiliate_commission_generated_template', false, $this->config->item('prg_program_id'));
						}
					}
					
					//send debug email if needed
					if ($this->config->item('sts_integration_enable_debug_email') == 1) 
					{
						$sdata['current_level'] = $level;
						$sdata['username'] = $v['username'];
						
						$this->security_model->_send_debug_email('Integration Debug Email', $sdata); 
						
						echo '<pre>'; print_r($sdata);
					}
				}
			}	
			
			//check performance rewards for program
			
				$this->load->model('rewards_model');
				
				$rewards = array('sale' => $rewards_sale_amount, 
								 'comm' => $rewards_comm_amount, 
								 'mid' => $sdata['member_id'], 
								 'pid' => $sdata['program_id'], 
								 'num_options' => $num_options, 
								 'comm_status' => $comm_status
								 );
				
				$this->rewards_model->_check_rewards($rewards);
		}

        //send postback
        if (!empty($prog['postback_url']))
        {
            $pdata = $sdata = array_merge($aff_data, $sdata);

            $type = defined('POSTBACK_DATA_TYPE') ? POSTBACK_DATA_TYPE : 'array';

            $fields = format_curl_data($pdata, $type);

            connect_curl($prog['postback_url'], true, $fields);
        }

		//run post commission modules
		$this->_run_comm_modules('post_commission_generation', $comm_array);
				
		//header("HTTP/1.0 200 OK");
		//exit();
	}
	
	// ------------------------------------------------------------------------	
	
	function _run_comm_modules($type = '', $data = '')
	{
		//load members model
		$this->load->model('modules_model');
		
		//run modules
		$sdata = $this->modules_model->_run_modules($type, 'public');
		
		if (!empty($sdata))
		{
			//load the api config
			$this->config->load('api');
			
			//load models
			if ($this->config->item('module_api_' . $type . '_models'))
			{
				$api_models = explode(',', $this->config->item('module_api_' . $type . '_models'));
				
				foreach ($api_models as $api)
				{
					$this->load->model(trim($api . '_model'), $api . '_api');
				}
			}
			
			//load helpers
			if ($this->config->item('module_api_' . $type . '_helpers'))
			{
				$helpers = explode(',', $this->config->item('module_api_' . $type . '_helpers'));
				
				foreach ($helpers as $helper)
				{
					$this->load->helper(trim($helper));
				}
			}
			
			for ($i=0; $i<count($sdata); $i++)
			{
				
				$module_model = 'modules/module_' . $sdata[$i]['module_type'] . '_' . $sdata[$i]['module_file_name'] . '_model';
				
				$this->load->model($module_model, $sdata[$i]['module_file_name']);
				
				$function = '_run_module_' . $sdata[$i]['module_file_name'];
				
				$this->$sdata[$i]['module_file_name']->$function('public', $data);	
			}
		}
	}
}
?>