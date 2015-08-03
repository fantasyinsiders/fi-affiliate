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
| FILENAME - module_data_import_jam_model.php
| -------------------------------------------------------------------------     
| 
| This controller file is for importing JAM data
|
*/


class Module_Data_Import_Jam_Model extends CI_Model {

	function _install_jrox_module($id = '')
	{	
		
		$config = array(
							'settings_key'	=>	'module_data_import_jam_segment_affiliates',
							'settings_value'	=>	'0',
							'settings_module'	=>	'data_import',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'boolean',
							);
		
		//insert into settings table
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_data_import_jam_affiliate_limit',
							'settings_value'	=>	'10000',
							'settings_module'	=>	'data_import',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'2',
							'settings_function'	=>	'none',
							);
		
		//insert into settings table
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_data_import_jam_affiliate_offset',
							'settings_value'	=>	'0',
							'settings_module'	=>	'data_import',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'3',
							'settings_function'	=>	'none',
							);
		
		//insert into settings table
		$this->db->insert('settings', $config);
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _import_data($array = '')
	{
		//set for segmented migration
		$config['hostname'] = $array['module_data_import_jam_server'];
		$config['username'] =  $array['module_data_import_jam_username'];
		$config['password'] =  $array['module_data_import_jam_password'];
		$config['database'] =  $array['module_data_import_jam_database'];
		$config['dbdriver'] = "mysql";
		$config['dbprefix'] = "";
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = "";
		$config['char_set'] = "utf8";
		$config['dbcollat'] = "utf8_general_ci";
		
		$this->DB1 = $this->load->database('default', TRUE);
		$this->DB2 = $this->load->database($config, TRUE);
		if ($this->DB2)
		{

				
			//get members from JAM
			if ($this->config->item('module_data_import_jam_segment_affiliates') == true)
			{
				$this->DB2->limit($this->config->item('module_data_import_jam_affiliate_limit'), $this->config->item('module_data_import_jam_affiliate_offset'));
			}
			 
			$this->DB2->order_by('mid', 'ASC');
			$query = $this->DB2->get('jx_members');
			 
			if ($query->num_rows() > 0)
			{
				//truncate members table in JAM
				if ($this->config->item('module_data_import_jam_segment_affiliates') == false || $this->config->item('module_data_import_jam_affiliate_offset') == 0)
				{
					$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('members'));
					$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('members_groups'));
					$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('email_mailing_list_members'));
					$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('commissions'));
					$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('affiliate_payments'));
					$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('traffic'));
				}			
				
				$total = 0;
				
				foreach ($query->result_array() as $row)
				{
					//import the user first
					
					//get the country ID	
					$country_id = '223';
					
					$this->DB1->where('country_name', $row['country']);
					$cquery = $this->DB1->get('countries');
					
					if ($cquery->num_rows() > 0)
					{
						$cn = $cquery->row_array();
						
						$country_id = $cn['country_id'];
					}
				
					
					$pub = explode('-', $row['date']);
					$signup_date = mktime(date('H'),date('i'),date('s'), $pub[1], $pub[2], $pub[0]);
					
					$pub = explode('-', $row['last_login_date']);
					$last_login_date = mktime(date('H'),date('i'),date('s'), $pub[1], $pub[2], $pub[0]);
									
					$mem_data = array(
										'member_id'						=>			$row['mid'],
										'status' 						=> 			$row['status'] == 'active' ? '1' : '0',
										'fname'							=>			$row['first_name'],
										'lname'							=>			$row['last_name'],
										'username'						=>			$row['username'],
										'password'						=>			_generate_random_string('7', false),
										'primary_email'					=>			$row['primary_email'],
										'send_welcome_email' 			=>			'0',
										'login_status'					=>			'0',
										'company'						=>			$row['company_name'],
										'billing_address_1'				=>			$row['address_1'],
										'billing_address_2'				=>			$row['address_2'],
										'billing_city'					=>			$row['city'],
										'billing_state'					=>			$row['state'],
										'billing_country'				=>			$country_id,
										'billing_postal_code'			=>			$row['zip'],
										'home_phone'					=>			$row['phone'],
										'work_phone'					=>			'',
										'mobile_phone'					=>			'',
										'fax'							=>			'',
										'alert_downline_signup'			=>			'2',
										'alert_new_commission'			=>			'2',
										'alert_payment_sent'			=>			'2',
										'allow_downline_view'			=>			'2',
										'allow_downline_email'			=>			'2',
										'enable_custom_url'				=>			$row['enable_custom_url'],
										'custom_url_link'				=>			$row['custom_affiliate_url'],
										'sponsor_id'					=>			!empty($row['sponsor']) ? $row['sponsor'] : '',
										'original_sponsor_id'					=>			!empty($row['original_sponsor']) ? $row['original_sponsor'] : '',
										'website'						=>			$row['website'],
										'payment_name'					=>			$row['check_name'],
										'payment_preference_amount'		=>			$row['custom_payout_value'],
										'payment_address_1'				=>			$row['address_1'],
										'payment_address_2'				=>			$row['address_2'],
										'payment_city'					=>			$row['city'],
										'payment_state'					=>			$row['state'],
										'payment_country'				=>			$country_id,
										'payment_postal_code'			=>			$row['zip'],
										'paypal_id'						=>			$row['paypal_email'],
										
										'moneybookers_id'				=>			$row['moneybookers_email'],
										'payza_id'						=>			$row['alertpay_email'],
										'custom_id'						=>			$row['egold_id'],
										'bank_transfer'					=>			$row['bank_transfer'],
										'affiliate_groups'				=>			'1',
										
										'program_custom_field_1'			=>			$row['custom_field_value_1'],
										'program_custom_field_2'			=>			$row['custom_field_value_2'],
										'program_custom_field_3'			=>			$row['custom_field_value_3'],
										'program_custom_field_4'			=>			$row['custom_field_value_4'],
										'program_custom_field_5'			=>			$row['custom_field_value_5'],
										
										'signup_date'					=>			$signup_date,
										'last_login_date'				=>			$last_login_date,
										'last_login_ip'					=>			$row['last_login_ip'],
										'email_mailing_lists'			=>			array('1'),
									  );		
				
					$member_data = $this->_add_member($this->_clean_array($mem_data));
					
					if ($member_data)
					{
						$total++;
						
						//import commissions
						if (!empty($_POST['import_commissions']))
						{
							//now import his commissions
							$this->DB2->where('mid', $member_data['member_id']);
							
							$comm = $this->DB2->get('jx_commissions');
							
							$i = 0;
							$total_comms = $comm->num_rows();
							if ($total_comms > 0)
							{
								$comm_string = '';
								
								foreach ($comm->result_array() as $comm_row)
								{
									$pub = explode('-', $comm_row['date']);
									$comm_date = mktime(date('H'),date('i'),date('s'), $pub[1], $pub[2], $pub[0]);
									
									switch ($comm_row['tool'])
									{
										case '1':
											$tool_type = 'banners';
										break;
										
										case '2':
											$tool_type = 'text_links';
										break;
										
										case '3':
											$tool_type = 'text_ads';
										break;
										
										case '4':
											$tool_type = 'html_ads';
										break;
										
										case '5':
											$tool_type = 'email_ads';
										break;
										
										case '6':
											$tool_type = 'article_ads';
										break;
										
										default:
											$tool_type = '0';
										break;
									}
									
									$comm_data = array(
														"'" . $comm_row['cid'] . "'",
														"'" . $member_data['member_id'] . "'",
														"'" . $comm_row['pid'] . "'",
														'0',
														"'" . $comm_row['status'] . "'",
														"'" . $comm_row['approved'] . "'",
														"'" . $comm_date . "'",
														"'" . $comm_row['commission_amount'] . "'",
														"'" . $comm_row['sale_amount'] . "'",
														"'" . $comm_row['commission_level'] . "'",
														"'" . addslashes($comm_row['referrer']) . "'",
														"'" . $comm_row['trans_id'] . "'",
														"'" . $comm_row['ip_address'] . "'",
														"'" . strtotime($comm_row['date_paid']) . "'",
														"'" . addslashes($comm_row['commission_notes']) . "'",
														"'" . $comm_row['performance_paid'] . "'",
														'1',
														"'" . $tool_type . "'",
														"'" . $comm_row['tool_id'] . "'",
													  );
									
									$i++;
								
									$comm_string .= '(' . implode(',', $comm_data)  . ')';	
									
									if ($i < $total_comms) 
									{
										$comm_string .= ',';	
									}
								}
								
								$this->_add_commission($comm_string);
								
								//import his payments
								$this->DB2->where('mid', $member_data['member_id']);
								
								
								$payments = $this->DB2->get('jx_payments');
								$total_payments = $payments->num_rows();
								
								if ($total_payments > 0)
								{
									$payment_string = '';
									$i = 0;
									foreach ($payments->result_array() as $pay_row)
									{
										$payment_data = array(
																"'" . $pay_row['id'] . "'",
																"'" . $member_data['member_id'] . "'",
																"'" . strtotime($pay_row['date']) . "'",
																"'" . $pay_row['payment_amount'] . "'",		
																"'" . $pay_row['payment_type'] . "'"
															 );
										$i++;
								
										$payment_string .= '(' . implode(',', $payment_data)  . ')';	
										
										if ($i < $total_payments) 
										{
											$payment_string .= ',';	
										}
									}
									
									$this->_add_payment($payment_string);
									
								}
							}
						}
						
						//import traffic
						if (!empty($_POST['import_traffic']))
						{
							//import his traffic			
							$this->DB2->where('mid', $member_data['member_id']);
							$this->DB2->limit('1000');
							$this->DB2->order_by('id', 'DESC');
							$traffic = $this->DB2->get('jx_traffic');
							$total_traffic = $traffic->num_rows();
							
							if ($total_traffic > 0)
							{
								$traffic_string = '';
								$i = 0;
								
								foreach ($traffic->result_array() as $traffic_row)
								{
									switch ($traffic_row['tool'])
									{
										case '1':
											$tool_type = 'banners';
										break;
										
										case '2':
											$tool_type = 'text_links';
										break;
										
										case '3':
											$tool_type = 'text_ads';
										break;
										
										case '4':
											$tool_type = 'html_ads';
										break;
										
										case '5':
											$tool_type = 'email_ads';
										break;
										
										case '6':
											$tool_type = 'article_ads';
										break;
										
										default:
											$tool_type = '0';
										break;
									}
									
									$traffic_data = array(
															"'" .$traffic_row['id'] . "'",
															"'" .strtotime($traffic_row['date']) . "'",
															"'" .$traffic_row['mid'] . "'",
															"'" .$tool_type . "'",
															"'" .$traffic_row['tool_id'] . "'",
															"'" .addslashes($traffic_row['referrer']) . "'",
															"'" .$traffic_row['ip_address'] . "'",
															"'" .addslashes($traffic_row['user_agent']) . "'",
															"'" .$traffic_row['os'] . "'",
															"'" .$traffic_row['browser'] . "'",
															"'" .str_replace('\'', ' ',$traffic_row['isp']) . "'",
														
														 );
														 
									$i++;
								
									$traffic_string .= '(' . implode(',', $traffic_data)  . ')';	
									
									if ($i < $total_traffic) 
									{
										$traffic_string .= ',';	
									}
								}
								
								$this->_add_traffic($traffic_string);
							}	
						}
						
						//update database offset
						$up['module_data_import_jam_affiliate_offset'] = $this->DB1->count_all('members');
						
						$this->db_validation_model->_update_db_settings($up);
					}
				}	
			}
			
			$query->free_result();
			
			//import programs
			$programs = $this->DB2->get('jx_products');
			if ($programs->num_rows() > 0)
			{
				$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('programs'));
				$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('layout_menus'));
				$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('programs_form_fields'));
				$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('email_templates'));
				$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('affiliate_groups'));
				
				$i = 1;
				foreach ($programs->result_array() as $program)
				{
					
					$program_data = array(
										  'program_id' => $program['pid'],
										  'program_status' => '0',
										  'program_name' => $program['product_name'],
										  'program_description' => $program['product_description'],
										  'sort_order' => $program['program_sort_order'],
										  'enable_pay_per_click' => $program['product_name'],
										  'enable_cpm' => '0',
										  'enable_pay_per_action' => '1',
										  'ppc_interval' => $program['ppc_interval_value'],
										  'cpm_unique_ip' => '1',
										  'commission_levels' => $program['commission_levels'],
										  'commission_levels_restrict_view' => $program['commission_levels'],
										  'commission_frequency' => '0',
										  'new_commission_option' => 'no_pending',
										  'auto_approve_commissions' => '0', 
										  'url_redirect' => $program['product_url'], 
										  'enable_custom_login' => '0', 
										  'url_redirect_login' => $program['url_after_login'], 
										  'enable_custom_signup' => '0', 
										  'url_redirect_signup' => $program['url_after_signup'], 
										  'use_remote_domain_link' => '', 
										  'remote_domain_name' => '', 
										  'program_cookie_name' => '', 
										  'default_theme' => 'default', 
										  'terms_of_service' => '', 
										  'privacy_policy' => '', 
										  'hidden_program' => '0', 
										  'group_id' => '1', 
										  'signup_link' => 'program_' . $i, 
										  'require_trans_id' => '1',  
										  'last_modified' => '', 
										  'modified_by' => '', 
										  'program_logo' => '', 
										  'program_layout_member_links_array' => 'a:5:{i:0;a:9:{s:2:"id";s:1:"3";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:9:"dashboard";s:11:"menu_parent";s:1:"0";s:8:"menu_url";s:14:"{members_home}";s:15:"menu_sort_order";s:1:"1";s:12:"menu_options";s:0:"";s:4:"subs";a:0:{}}i:1;a:9:{s:2:"id";s:1:"4";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:9:"marketing";s:11:"menu_parent";s:1:"0";s:8:"menu_url";s:19:"{members_marketing}";s:15:"menu_sort_order";s:1:"2";s:12:"menu_options";s:0:"";s:4:"subs";a:2:{i:0;a:8:{s:2:"id";s:1:"8";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:14:"email_downline";s:11:"menu_parent";s:1:"4";s:8:"menu_url";s:24:"{members_email_downline}";s:15:"menu_sort_order";s:1:"7";s:12:"menu_options";s:0:"";}i:1;a:8:{s:2:"id";s:1:"5";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:13:"view_downline";s:11:"menu_parent";s:1:"4";s:8:"menu_url";s:18:"{members_downline}";s:15:"menu_sort_order";s:1:"8";s:12:"menu_options";s:0:"";}}}i:2;a:9:{s:2:"id";s:1:"6";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:11:"commissions";s:11:"menu_parent";s:1:"0";s:8:"menu_url";s:21:"{members_commissions}";s:15:"menu_sort_order";s:1:"4";s:12:"menu_options";s:0:"";s:4:"subs";a:1:{i:0;a:8:{s:2:"id";s:1:"7";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:18:"affiliate_payments";s:11:"menu_parent";s:1:"6";s:8:"menu_url";s:18:"{members_payments}";s:15:"menu_sort_order";s:1:"3";s:12:"menu_options";s:0:"";}}}i:3;a:9:{s:2:"id";s:1:"1";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:7:"profile";s:11:"menu_parent";s:1:"0";s:8:"menu_url";s:17:"{members_details}";s:15:"menu_sort_order";s:1:"5";s:12:"menu_options";s:0:"";s:4:"subs";a:0:{}}i:4;a:9:{s:2:"id";s:1:"2";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:7:"content";s:11:"menu_parent";s:1:"0";s:8:"menu_url";s:17:"{members_content}";s:15:"menu_sort_order";s:1:"6";s:12:"menu_options";s:0:"";s:4:"subs";a:0:{}}}', 
										  'enable_affiliate_signup_bonus' => '0', 
										  'affiliate_signup_bonus_amount' => '0', 
										  'enable_referral_bonus' => '0', 
										  'referral_bonus_amount' => '0', 
										  'remote_affiliate_url' => '', 
										  );
					
					$this->DB1->insert('programs', $this->_clean_array($program_data));
					$program_data['id'] = $program['pid'];
					
					//add affiliate groups
					$sdata = array(
									'tier' => 1,
									'aff_group_name' => $program['product_name'],
									'aff_group_code' => 'program_' . $program['pid'],
									'aff_group_description' => $program['product_description'],
									'commission_type' => $program['commission_type'] == 'flat' ? 'flat' : 'percent',
									'commission_level_1' => $program['commission_level_1'],
									'commission_level_2' => $program['commission_level_2'],
									'commission_level_3' => $program['commission_level_3'],
									'commission_level_4' => $program['commission_level_4'],
									'commission_level_5' => $program['commission_level_5'],
									'commission_level_6' => $program['commission_level_6'],
									'commission_level_7' => $program['commission_level_7'],
									'commission_level_8' => $program['commission_level_8'],
									'commission_level_9' => $program['commission_level_9'],
									'commission_level_10' => $program['commission_level_10'],
									'mailing_list_id' => 1,
									
					);
					
					$this->DB1->insert('affiliate_groups', $this->_clean_array($sdata));
					
					$group_id = $this->DB1->insert_id();
					
					$this->DB1->where('program_id', $program_data['id']);
					$this->DB1->update('programs', array('group_id' => $group_id));
					
					
					//add program form fields
					$sdata = array('program_id' =>  $program_data['id'],
								  'enable_fname' => '1',
								  'enable_primary_email' => '1'
								  );
					$this->DB1->insert('programs_form_fields', $this->_clean_array($sdata));
					
					//add program email templates
					$this->_add_program_email_templates($program_data);
					
					//add program menus
					$sql = "INSERT INTO `jam_layout_menus` (`program_id`, `menu_status`, `menu_name`, `menu_parent`, `menu_url`, `menu_sort_order`, `menu_options`) VALUES
(" . $program_data['id'] . ", '1', 'profile', 0, '{members_details}', 5, ''),
(" . $program_data['id'] . ", '1', 'content', 0, '{members_content}', 6, ''),
(" . $program_data['id'] . ", '1', 'dashboard', 0, '{members_home}', 1, ''),
(" . $program_data['id'] . ", '1', 'marketing', 0, '{members_marketing}', 4, '');";
		
					$this->DB1->query($sql);
		
					$i++;
				}
			}
			
			
			if (!empty($_POST['import_tools']))
			{
				$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('affiliate_article_ads'));
				$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('affiliate_banners'));
				$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('affiliate_email_ads'));
				$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('affiliate_html_ads'));
				$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('affiliate_text_ads'));
				$this->DB1->query('TRUNCATE TABLE ' . $this->DB1->dbprefix('affiliate_text_links'));
				
				//import  article_ads into JEM
				$article_ads = $this->DB2->get('jx_article_ads');
								
				if ($article_ads->num_rows() > 0)
				{
					foreach ($article_ads->result_array() as $article_ads_row)
					{
						$article_ads_row['article_ad_body'] = str_replace('%%%AFFILIATE_URL%%%', '{affiliate_link}', $article_ads_row['article_ad_body']);
						$article_ads_row['article_ad_body'] = str_replace('%%%FIRST_NAME%%%', '{fname}', $article_ads_row['article_ad_body']);
						$article_ads_row['article_ad_body'] = str_replace('%%%LAST_NAME%%%', '{lname}', $article_ads_row['article_ad_body']);
						$article_ads_row['article_ad_body'] = str_replace('%%%USERNAME%%%', '{username}', $article_ads_row['article_ad_body']);
						$article_ads_row['article_ad_body'] = str_replace('%%%LOGIN_URL%%%', '{login_url}', $article_ads_row['article_ad_body']);
						$article_ads_row['article_ad_body'] = str_replace('%%%PRIMARY_EMAIL%%%', '{primary_email}', $article_ads_row['article_ad_body']);
						
						$article_ads_data = array(
												'id'					=>		$article_ads_row['aaid'],
												'program_id'			=>		$article_ads_row['pid'],
												'status'				=>		$article_ads_row['article_ad_status'] == 'active' ? '1' : '0',
												'name'					=>		$article_ads_row['article_ad_name'],
												'article_ad_title'		=>		$article_ads_row['article_ad_subject'],
												'article_ad_body'		=>		$article_ads_row['article_ad_body'],
												'enable_redirect'		=>		$article_ads_row['enable_redirect'],
												'redirect_custom_url'	=>		$article_ads_row['redirect_custom_url'],
												'sort_order'			=>		'1',
												'notes'					=>		$article_ads_row['article_ad_notes'],
											 );
											 
						$this->DB1->insert('affiliate_article_ads', $this->_clean_array($article_ads_data));
					}
				}
				
				
				//import  banners into JEM
				$banner_ads = $this->DB2->get('jx_banners');
					 			
				if ($banner_ads->num_rows() > 0)
				{
					foreach ($banner_ads->result_array() as $banner_ads_row)
					{
						$banner_ads_data = array(
												'id'					=>		$banner_ads_row['bid'],
												'program_id'			=>		$banner_ads_row['pid'],
												'status'				=>		$banner_ads_row['banner_status'] == 'active' ? '1' : '0',
												'name'					=>		$banner_ads_row['banner_name'],
												
												'enable_redirect'		=>		$banner_ads_row['enable_redirect'],
												'redirect_custom_url'	=>		$banner_ads_row['redirect_custom_url'],
												'banner_height'			=>		$banner_ads_row['banner_height'],
												'banner_width'			=>		$banner_ads_row['banner_width'],
												'use_external_image'	=>		'0',
												'banner_file_name'		=>		$banner_ads_row['banner_file_name'],
												'sort_order'			=>		'1',
												'notes'					=>		$banner_ads_row['banner_notes'],
											 );
											 
						$this->DB1->insert('affiliate_banners', $this->_clean_array($banner_ads_data));
					}
					
					$banner_ads->free_result();
				}
				
				//import  email ads into JEM
				$email_ads = $this->DB2->get('jx_email_ads');
								
				if ($email_ads->num_rows() > 0)
				{
					foreach ($email_ads->result_array() as $email_ads_row)
					{
						$email_ads_row['email_ad_body'] = str_replace('%%%AFFILIATE_URL%%%', '{affiliate_link}', $email_ads_row['email_ad_body']);
						$email_ads_row['email_ad_body'] = str_replace('%%%FIRST_NAME%%%', '{fname}', $email_ads_row['email_ad_body']);
						$email_ads_row['email_ad_body'] = str_replace('%%%LAST_NAME%%%', '{lname}', $email_ads_row['email_ad_body']);
						$email_ads_row['email_ad_body'] = str_replace('%%%USERNAME%%%', '{username}', $email_ads_row['email_ad_body']);
						$email_ads_row['email_ad_body'] = str_replace('%%%LOGIN_URL%%%', '{login_url}', $email_ads_row['email_ad_body']);
						$email_ads_row['email_ad_body'] = str_replace('%%%PRIMARY_EMAIL%%%', '{primary_email}', $email_ads_row['email_ad_body']);
						
						$email_ads_data = array(
												'id'					=>		$email_ads_row['eaid'],
												'program_id'			=>		$email_ads_row['pid'],
												'status'				=>		$email_ads_row['email_ad_status'] == 'active' ? '1' : '0',
												'name'					=>		$email_ads_row['email_ad_name'],
												'email_ad_title'		=>		$email_ads_row['email_ad_subject'],
												'email_ad_body'			=>		$email_ads_row['email_ad_body'],
												'enable_redirect'		=>		$email_ads_row['enable_redirect'],
												'redirect_custom_url'	=>		$email_ads_row['redirect_custom_url'],
												'sort_order'			=>		'1',
												'notes'					=>		$email_ads_row['email_ad_notes'],
											 );
											 
						$this->DB1->insert('affiliate_email_ads', $this->_clean_array($email_ads_data));
					}
					
					$email_ads->free_result();
				}
				
				//import  hover ads into JEM	
				$jam_set_query = $this->DB2->get('jx_settings');
				$jam_settings = $jam_set_query->row_array();
				
				$hover_ads = $this->DB2->get('jx_hover_ads');
								
				if ($hover_ads->num_rows() > 0)
				{
					foreach ($hover_ads->result_array() as $hover_ads_row)
					{
						
						$hover_ads_row['hover_ad_body'] = str_replace('%%%AFFILIATE_URL%%%', '{affiliate_link}', $hover_ads_row['hover_ad_body']);
						$hover_ads_row['hover_ad_body'] = str_replace('%%%FIRST_NAME%%%', '{fname}', $hover_ads_row['hover_ad_body']);
						$hover_ads_row['hover_ad_body'] = str_replace('%%%LAST_NAME%%%', '{lname}', $hover_ads_row['hover_ad_body']);
						$hover_ads_row['hover_ad_body'] = str_replace('%%%USERNAME%%%', '{username}', $hover_ads_row['hover_ad_body']);
						$hover_ads_row['hover_ad_body'] = str_replace('%%%LOGIN_URL%%%', '{login_url}', $hover_ads_row['hover_ad_body']);
						
						$hover_ads_data = array(
												'id'					=>		$hover_ads_row['haid'],
												'program_id'			=>		$hover_ads_row['pid'],
												'status'				=>		$hover_ads_row['hover_ad_status'] == 'active' ? '1' : '0',
												'name'					=>		$hover_ads_row['hover_ad_name'],
												'html_ad_type'			=>		'floating_div',
												'html_ad_body'			=>		stripslashes($hover_ads_row['hover_ad_body']),
												'enable_redirect'		=>		$hover_ads_row['enable_redirect'],
												'redirect_custom_url'	=>		$hover_ads_row['redirect_custom_url'],
												'sort_order'			=>		'1',
												'notes'					=>		$hover_ads_row['hover_ad_notes'],
												
												'html_ad_width'			=>		$hover_ads_row['hover_ad_width'],
											 );
											 
						$this->DB1->insert('affiliate_html_ads', $this->_clean_array($hover_ads_data));
					}
					
					$hover_ads->free_result();
				}	
				
				//import  text ads into JEM
				$text_ads = $this->DB2->get('jx_text_ads');
								
				if ($text_ads->num_rows() > 0)
				{
					foreach ($text_ads->result_array() as $text_ads_row)
					{
						$text_ads_row['text_ad_body'] = str_replace('%%%AFFILIATE_URL%%%', '{affiliate_link}', $text_ads_row['text_ad_body']);
						$text_ads_row['text_ad_body'] = str_replace('%%%FIRST_NAME%%%', '{fname}', $text_ads_row['text_ad_body']);
						$text_ads_row['text_ad_body'] = str_replace('%%%LAST_NAME%%%', '{lname}', $text_ads_row['text_ad_body']);
						$text_ads_row['text_ad_body'] = str_replace('%%%USERNAME%%%', '{username}', $text_ads_row['text_ad_body']);
						$text_ads_row['text_ad_body'] = str_replace('%%%LOGIN_URL%%%', '{login_url}', $text_ads_row['text_ad_body']);
					
						$text_ads_data = array(
												'id'					=>		$text_ads_row['taid'],
												'program_id'			=>		$text_ads_row['pid'],
												'status'				=>		$text_ads_row['text_ad_status'] == 'active' ? '1' : '0',
												'name'					=>		$text_ads_row['text_ad_name'],
												'text_ad_title'			=>		$text_ads_row['text_ad_title'],
												'text_ad_body'			=>		$text_ads_row['text_ad_body'],
												'enable_redirect'		=>		$text_ads_row['enable_redirect'],
												'redirect_custom_url'	=>		$text_ads_row['redirect_custom_url'],
												'sort_order'			=>		'1',
												'notes'					=>		$text_ads_row['text_ad_notes'],
												
												'text_ad_width'			=>		$text_ads_row['text_ad_width'],
											 );
											 
						$this->DB1->insert('affiliate_text_ads', $this->_clean_array($text_ads_data));
					}
					
					$text_ads->free_result();
				}	
				
				//import  text links into JEM
				$text_links = $this->DB2->get('jx_text_links');
								
				if ($text_links->num_rows() > 0)
				{
					foreach ($text_links->result_array() as $text_links_row)
					{
						$text_links_data = array(
												'id'					=>		$text_links_row['tlid'],
												'program_id'			=>		$text_links_row['pid'],
												'status'				=>		$text_links_row['text_link_status'] == 'active' ? '1' : '0',
												'name'					=>		$text_links_row['text_link_name'],
												'text_link_title'		=>		$text_links_row['text_link_name'],
												'enable_redirect'		=>		$text_links_row['enable_redirect'],
												'redirect_custom_url'	=>		$text_links_row['redirect_custom_url'],
												'sort_order'			=>		'1',
												'notes'					=>		$text_links_row['text_link_notes'],
											 );
											 
						$this->DB1->insert('affiliate_text_links', $this->_clean_array($text_links_data));
					}
					
					$text_links->free_result();
				}	
			}
			
			//get total amount of users
			$total = $this->DB1->count_all('members');
			
			return $total;
		}
		else
		{
			echo '<div class="error" id="error-messages">' . $this->lang->line('could_not_connect_database') . '</div>';
			exit();
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _check_jam_id($id = '', $table = '')
	{
		$sql = 'SELECT * FROM ' . $this->db->dbprefix($table). ' WHERE username = \'' . $id . '\'';
		$query = $DB1->query($sql);	
		
		if ($query->num_rows() > 0)
		{
			//generate an id
			//get the last row ID from db
			$DB1->db->select('member_id');
			$DB1->db->from($table);
			$DB1->db->order_by('member_id', 'DESC');
			$DB1->db->limit('1');
			
			$squery = $CI->db->get();
			
			$num = $squery->row();
			$a = $num->member_id;
			
			$id =  _generate_random_string('5') . $a;
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	function _import_commissions($array = '')
	{
		//set for segmented migration
		$config['hostname'] = $array['module_data_import_jam_server'];
		$config['username'] =  $array['module_data_import_jam_username'];
		$config['password'] =  $array['module_data_import_jam_password'];
		$config['database'] =  $array['module_data_import_jam_database'];
		$config['dbdriver'] = "mysql";
		$config['dbprefix'] = "";
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = "";
		$config['char_set'] = "utf8";
		$config['dbcollat'] = "utf8_general_ci";
		
		$this->DB1 = $this->load->database('default', TRUE);
		$this->DB2 = $this->load->database($config, TRUE);
		if ($this->DB2)
		{

				
			//get members from JAM
			if ($this->config->item('module_data_import_jam_segment_affiliates') == true)
			{
				$this->DB2->limit($this->config->item('module_data_import_jam_affiliate_limit'), $this->config->item('module_data_import_jam_affiliate_offset'));
			}
			
			$this->DB2->order_by('cid', 'ASC');
			$query = $this->DB2->get('jx_commissions');
			if ($query->num_rows() > 0)
			{
						
				
				$total = 0;
				$comm_string = '';
				
				$i = 0;
				$total_comms = $query->num_rows();
				foreach ($query->result_array() as $comm_row)
				{
					
				  $pub = explode('-', $comm_row['date']);
				  $comm_date = mktime(date('H'),date('i'),date('s'), $pub[1], $pub[2], $pub[0]);
				  
				  switch ($comm_row['tool'])
				  {
					  case '1':
						  $tool_type = 'banners';
					  break;
					  
					  case '2':
						  $tool_type = 'text_links';
					  break;
					  
					  case '3':
						  $tool_type = 'text_ads';
					  break;
					  
					  case '4':
						  $tool_type = 'html_ads';
					  break;
					  
					  case '5':
						  $tool_type = 'email_ads';
					  break;
					  
					  case '6':
						  $tool_type = 'article_ads';
					  break;
					  
					  default:
						  $tool_type = '0';
					  break;
				  }
				  
				
				 $comm_data = array(
														"'" . $comm_row['cid'] . "'",
														"'" . $comm_row['member_id'] . "'",
														"'" . $comm_row['pid'] . "'",
														'0',
														"'" . $comm_row['status'] . "'",
														"'" . $comm_row['approved'] . "'",
														"'" . $comm_date . "'",
														"'" . $comm_row['commission_amount'] . "'",
														"'" . $comm_row['sale_amount'] . "'",
														"'" . $comm_row['commission_level'] . "'",
														"'" . addslashes($comm_row['referrer']) . "'",
														"'" . $comm_row['trans_id'] . "'",
														"'" . $comm_row['ip_address'] . "'",
														"'" . strtotime($comm_row['date_paid']) . "'",
														"'" . addslashes($comm_row['commission_notes']) . "'",
														"'" . $comm_row['performance_paid'] . "'",
														'1',
														"'" . $tool_type . "'",
														"'" . $comm_row['tool_id'] . "'",
													  );
									
									$i++;
								
									$comm_string = '(' . implode(',', $comm_data)  . ')';	
				 
				$this->_add_commission($comm_string);
									
				}

								
				//import his payments
				$this->DB2->where('mid', $comm_row['member_id']);
				
				
				$payments = $this->DB2->get('jx_payments');
				$total_payments = $payments->num_rows();
				
				if ($total_payments > 0)
				{
					$payment_string = '';
					$i = 0;
					foreach ($payments->result_array() as $pay_row)
					{
						$payment_data = array(
												"'" . $pay_row['id'] . "'",
												"'" . $comm_row['member_id'] . "'",
												"'" . strtotime($pay_row['date']) . "'",
												"'" . $pay_row['payment_amount'] . "'",		
												"'" . $pay_row['payment_type'] . "'"
											 );
						$i++;
				
						$payment_string .= '(' . implode(',', $payment_data)  . ')';	
						
						if ($i < $total_payments) 
						{
							$payment_string .= ',';	
						}
					}
					
					$this->_add_payment($payment_string);
					
				}
								
				//update database offset
				$up['module_data_import_jam_affiliate_offset'] = $this->DB1->count_all('commissions');
				
				$this->db_validation_model->_update_db_settings($up);
					
			
			}
			
			$query->free_result();
			
			
			
			//get total amount of users
			$total = $this->DB1->count_all('commissions');
			
			return $total;
		}
		else
		{
			echo '<div class="error" id="error-messages">' . $this->lang->line('could_not_connect_database') . '</div>';
			exit();
		}
	}
	
	
	// ------------------------------------------------------------------------
	
	function _import_traffic($array = '')
	{
		//set for segmented migration
		$config['hostname'] = $array['module_data_import_jam_server'];
		$config['username'] =  $array['module_data_import_jam_username'];
		$config['password'] =  $array['module_data_import_jam_password'];
		$config['database'] =  $array['module_data_import_jam_database'];
		$config['dbdriver'] = "mysql";
		$config['dbprefix'] = "";
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = "";
		$config['char_set'] = "utf8";
		$config['dbcollat'] = "utf8_general_ci";
		
		$this->DB1 = $this->load->database('default', TRUE);
		$this->DB2 = $this->load->database($config, TRUE);
		if ($this->DB2)
		{

				
			//get members from JAM
			if ($this->config->item('module_data_import_jam_segment_affiliates') == true)
			{
				$this->DB2->limit($this->config->item('module_data_import_jam_affiliate_limit'), $this->config->item('module_data_import_jam_affiliate_offset'));
			}
			
			$this->DB2->order_by('id', 'ASC');
			$query  = $this->DB2->get('jx_traffic');
			if ($query->num_rows() > 0)
			{			
				$total_traffic = $query->num_rows();
							
				if ($total_traffic > 0)
				{
					$traffic_string = '';
					$i = 0;
					
					foreach ($query->result_array() as $traffic_row)
					{
						switch ($traffic_row['tool'])
						{
							case '1':
								$tool_type = 'banners';
							break;
							
							case '2':
								$tool_type = 'text_links';
							break;
							
							case '3':
								$tool_type = 'text_ads';
							break;
							
							case '4':
								$tool_type = 'html_ads';
							break;
							
							case '5':
								$tool_type = 'email_ads';
							break;
							
							case '6':
								$tool_type = 'article_ads';
							break;
							
							default:
								$tool_type = '0';
							break;
						}
						
						$traffic_data = array(
												"'" .$traffic_row['id'] . "'",
												"'" .strtotime($traffic_row['date']) . "'",
												"'" .$traffic_row['mid'] . "'",
												"'" .$tool_type . "'",
												"'" .(int)$traffic_row['tool_id'] . "'",
												"'" .addslashes($traffic_row['referrer']) . "'",
												"'" .$traffic_row['ip_address'] . "'",
												"'" .addslashes($traffic_row['user_agent']) . "'",
												"'" .$traffic_row['os'] . "'",
												"'" .$traffic_row['browser'] . "'",
												"'" . '' . "'",
											
											 );
											 
						$i++;
					
						$traffic_string = '(' . implode(',', $traffic_data)  . ')';	
						
						$this->_add_traffic($traffic_string);
					}
					
					
				}	
								
				//update database offset
				$up['module_data_import_jam_affiliate_offset'] = $this->DB1->count_all('traffic');
				
				$this->db_validation_model->_update_db_settings($up);
					
			
			}
			
			$query->free_result();
			
			
			
			//get total amount of users
			$total = $this->DB1->count_all('traffic');
			
			return $total;
		}
		else
		{
			echo '<div class="error" id="error-messages">' . $this->lang->line('could_not_connect_database') . '</div>';
			exit();
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _import_affiliate_payments($array = '')
	{
		//set for segmented migration
		$config['hostname'] = $array['module_data_import_jam_server'];
		$config['username'] =  $array['module_data_import_jam_username'];
		$config['password'] =  $array['module_data_import_jam_password'];
		$config['database'] =  $array['module_data_import_jam_database'];
		$config['dbdriver'] = "mysql";
		$config['dbprefix'] = "";
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = "";
		$config['char_set'] = "utf8";
		$config['dbcollat'] = "utf8_general_ci";
		
		$this->DB1 = $this->load->database('default', TRUE);
		$this->DB2 = $this->load->database($config, TRUE);
		if ($this->DB2)
		{

				
			//get members from JAM
			if ($this->config->item('module_data_import_jam_segment_affiliates') == true)
			{
				$this->DB2->limit($this->config->item('module_data_import_jam_affiliate_limit'), $this->config->item('module_data_import_jam_affiliate_offset'));
			}
			
			$this->DB2->order_by('id', 'ASC');
			$query = $this->DB2->get('jx_payments');
			if ($query->num_rows() > 0)
			{
					$total = 0;
				
					$payment_string = '';
					$i = 0;
					foreach ($query->result_array() as $pay_row)
					{
						$payment_data = array(
												"'" . $pay_row['id'] . "'",
												"'" . $pay_row['mid'] . "'",
												"'" . strtotime($pay_row['date']) . "'",
												"'" . $pay_row['payment_amount'] . "'",		
												"'" . $pay_row['payment_type'] . "'"
											 );
						$i++;
				
						$payment_string = '(' . implode(',', $payment_data)  . ')';	
				
					
						$this->_add_payment($payment_string);
					}
					
					
					
				
								
				//update database offset
				$up['module_data_import_jam_affiliate_offset'] = $this->DB1->count_all('affiliate_payments');
				
				$this->db_validation_model->_update_db_settings($up);
					
			
			}
			
			$query->free_result();
			
			
			
			//get total amount of users
			$total = $this->DB1->count_all('affiliate_payments');
			
			return $total;
		}
		else
		{
			echo '<div class="error" id="error-messages">' . $this->lang->line('could_not_connect_database') . '</div>';
			exit();
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _add_traffic($string = '')
	{
		$sql = 'INSERT INTO ' . $this->DB1->dbprefix('traffic') . ' (`traffic_id`, `date`, `member_id`, `tool_type`, `tool_id`, `referrer`, `ip_address`, `user_agent`, `os`, `browser`, `isp` ) VALUES ' . $string;

		//insert into db
		$query = $this->DB1->query($sql);
		if (!$query)
		{
			$query->free_result();
					
			show_error($this->lang->line('could_not_add_traffic'));
			
			//log error
			log_message('error', 'Could not insert traffic into traffic table');
			
			return false;
		}
		else
		{
			//log success
			log_message('info', 'traffic inserted into traffic table');
		}
		
		return true;	
	}
	
	// ------------------------------------------------------------------------
	
	function _add_payment($string = '')
	{
		$sql = 'INSERT INTO ' . $this->DB1->dbprefix('affiliate_payments') . ' (`id`, `member_id`, `payment_date`, `payment_amount`, `payment_details`) VALUES ' . $string;

		//insert into db
		$query = $this->DB1->query($sql);
		if (!$query)
		{
			$query->free_result();
			
			show_error($this->lang->line('could_not_add_payment'));
			
			//log error
			log_message('error', 'Could not insert payment into payments table');
			
			return false;
		}
		else
		{
			//log success
			log_message('info', 'payments inserted into payments table');
		}
		
		return true;	
	}
	
	// ------------------------------------------------------------------------
	
	function _add_commission($string = '')
	{
		$sql = 'INSERT INTO ' . $this->DB1->dbprefix('commissions') . ' (`comm_id`, `member_id`, `program_id`, `invoice_id`, `comm_status`, `approved`, `date`, `commission_amount`, `sale_amount`, `commission_level`, `referrer`, `trans_id`, `ip_address`, `date_paid`, `commission_notes`, `performance_paid`, `email_sent`, `tool_type`, `tool_id`) VALUES ' . $string;
		
		//insert into db
		$query = $this->DB1->query($sql);
		if (!$query)
		{
			$query->free_result();
			
			show_error($this->lang->line('could_not_add_commission'));
			
			//log error
			log_message('error', 'Could not insert commission into commissions table');
			
			return false;
		}
		else
		{	
			//log success
			log_message('info', 'commissions inserted into commissions table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _add_member($post_array = '') //insert the new member into db
	{	
		//clean the data first
		$data = $this->db_validation_model->_clean_data($post_array);
		
		//set update date and by
		$data['updated_by'] = $this->session->userdata('username');
		$data['updated_on'] = _generate_timestamp();
		$data['confirm_id'] = _generate_random_string('5', true);
		
		//filter out the shipping data
		$a = array(); $b = array();
		
		foreach ($data as $k => $v)
		{
			if (preg_match('/shipping_*/', $k)) 
			{
				array_push($a, $k);
				array_push($b, $v);
				unset($data[$k]);
			}
		}
			
		$sdata['sh'] = combine_array($a, $b); //shipping array
		
		//filter affiliate groups
		if (!empty($data['affiliate_groups']))
		{
			$sdata['ag'] = array('group_id' => $data['affiliate_groups']); //affiliate groups array
			unset($data['affiliate_groups']);
		}
		
		//filter discount groups
		if (!empty($data['discount_groups']))
		{
			$sdata['dg'] = array('group_id' => $data['discount_groups']); //discount_groups array
			unset($data['discount_groups']);
		}
		
		
		//filter out mailing lists	
		if (!empty($data['email_mailing_lists']))
		{
			$sdata['ml'] = $data['email_mailing_lists'];
			unset($data['email_mailing_lists']);
		}

	
		//insert into db
		if (!$this->DB1->insert('members', $data))
		{
			show_error($this->lang->line('could_not_add_member'));
			
			//log error
			log_message('error', 'Could not insert member into members table');
			
			return false;
		}
		else
		{
			$member_id = $this->DB1->insert_id();
			
			//log success
			log_message('info', 'Member ID '. $member_id . ' inserted into members table');
		}
		
		//add to affiliate groups
		if (!empty($sdata['ag']))
		{
			$sdata['ag']['member_id'] = $member_id;
			
			if (!$this->DB1->insert('members_groups', $sdata['ag']))
			{
				show_error($this->lang->line('could_not_add_member'));
				
				//log error
				log_message('error', 'Could not insert affiliate group for member ID ' . $member_id . ' into affiliate_groups table');
			}
			else
			{
				//log success
				log_message('info', 'Member ID '. $member_id . ' inserted into affiliate_groups table');
			}
		}
		
		
		//add to mailing lists
		if (!empty($sdata['ml']) && is_array($sdata['ml']))
		{
			foreach ($sdata['ml'] as $value)
			{
				$d = array('mailing_list_id' => $value,
						   'member_id' => $member_id,
						   'sequence_id' => 1,
						   'send_date'	=>	_generate_timestamp());
				if (!$this->DB1->insert('email_mailing_list_members', $d))
				{
					show_error($this->lang->line('could_not_add_member'));
				
					//log error
					log_message('error', 'Could not insert mailing_lists for member ID ' . $member_id . ' into email_mailing_list_members table');
				}
				else
				{
					//log success
					log_message('info', 'Member ID '. $member_id . ' inserted into email_mailing_list_members table');
				}
				
			}
		}
		
		//prepare member info array to be sent back
		$data['member_id'] = $member_id;
		
		return $data;
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _add_program_email_templates($data = '')
	{
		$sql = "INSERT INTO `jam_email_templates` (`program_id`, `email_template_type`, `email_template_html`, `email_template_group`, `email_template_name`, `email_template_from_name`, `email_template_from_email`, `email_template_cc`, `email_template_bcc`, `email_template_subject`, `email_template_body_text`, `email_template_body_html`) VALUES
(" . $data['id'] . ",'admin', 'html', '', 'admin_reset_password_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Admin Reset Password', 'Hello {fname},\n\nyour login name is: {username}\n\nhere is your new password: {new_password}\n\nlogin URL: {admin_login_url}', '<p><span style=\"font-weight: bold; font-size: 10pt; font-family: Arial;\">Hello {fname},</span></p>\n<p><span style=\"font-size: 10pt; font-family: Arial;\">your login name is: {username}</span></p>\n<p><span style=\"font-size: 10pt; font-family: Arial;\">here is your new password: {new_password}</span></p>\n<p><span style=\"font-size: 10pt; font-family: Arial;\">login URL: </span><span style=\"font-size: 10pt; font-family: Arial;\"><a href=\"{admin_login_url}\">{admin_login_url}</a></span></p>'),
(" . $data['id'] . ",'admin', 'html', '', 'admin_affiliate_commission_generated_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'A New Commission Has Been Generated', 'Hello {fname},\n\nA new commission has been generated by one of your users:\n\nUsername: {member_username}\nCommission Amount: {commission_amount}\nCommission Date: {current_date}\n\nLogin to the admin area for more info:\n\n{admin_login_url}\n\n{site_name}\n', '<p>Hello {fname},<br />\n<br />\nA new commission has been generated by one of your users:<br />\n<br />\nUsername: {member_username}<br />\nCommission Amount: {commission_amount}<br />\nCommission Date: {current_date}<br />\n<br />\nLogin to the admin area for more info:<br />\n<br />\n{admin_login_url}<br />\n<br />\n{site_name}</p>'),
(" . $data['id'] . ",'admin', 'html', '', 'admin_alert_new_signup_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'A new user has signed up!', 'hello {fname},\n\na new user has been added to your site:\n\nFull Name: {member_name}\nUsername: {member_username}\nSignup IP: {signup_ip}\nSignup Date: {current_time}\n\nPlease view full details in the admin area:\n\n{admin_login_url}\n\n{site_name}', '<p>hello {fname},<br />\n<br />\na new user has been added to your site:<br />\n<br />\nFull Name: {member_name}<br />\nUsername: {member_username}<br />\nSignup IP: {signup_ip}<br />\nSignup Date: {current_time}<br />\n<br />\nPlease view full details in the admin area:<br />\n<br />\n{admin_login_url}<br />\n<br />\n{site_name}</p>'),
(" . $data['id'] . ",'admin', 'html', '', 'admin_failed_login_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'admin failed login', 'Someone, maybe you, tried to login to your eCommerce Manager admin area unsuccessfully.\n\nDetails are:\n\nlogin url: {admin_login_url}\nusername used: {username}\npassword used: {password}\ndate and time of login: {date}\nip address: {ip_address}', '<p>Someone, maybe you, tried to login to your eCommerce Manager admin area unsuccessfully.</p>\n<p>Details are:<br />\nlogin url: {admin_login_url} <br />\nusername used: {username}<br />\npassword used: {password}<br />\ndate and time of login: {date}<br />\nip address: {ip_address}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_login_details_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Account Login Details', 'Hello {fname},\n\nyour login details for {site_name}:\n\nusername: {primary_email}\npassword: {password}\n\nlogin URL: {login_url}', '<p>Hello {fname},</p>\n<p>your login details for {site_name}:</p>\n<p>&nbsp;</p>\n<p>username: {primary_email}<br />\npassword: {password}</p>\n<p>login URL: <a href=\"{%login_url%}\">{login_url}</a></p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_performance_group_upgrade_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Your group membership has been upgraded', 'Hello {fname},\n\nYour affiliate group membership has been upgraded.\n\nYou''ve achieved this through your performance.\n\nYour new group is {upgraded_affiliate_group}.\n\nThanks!\n{site_name}', '<p>Hello {fname},<br />\n<br />\nYour affiliate group membership has been upgraded.<br />\n<br />\nYou''ve achieved this through your performance.<br />\n<br />\nYour new group is {upgraded_affiliate_group}.<br />\n<br />\nThanks!<br />\n{site_name}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_performance_bonus_amount_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'performance bonus awarded', 'Hello {fname},\n\nCongratulations!  You''ve earned a performance bonus commission for all your affiliate referrals!\n\nBonus amount: {bonus_amount}\n\n\nThanks again!\n\n{site_name}', '<p>Hello {fname},<br />\n<br />\nCongratulations!&nbsp; You''ve earned a performance bonus commission for all your affiliate referrals!<br />\n<br />\nBonus amount: {bonus_amount}<br />\n<br />\n<br />\nThanks again!<br />\n<br />\n{site_name}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_commission_generated_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'New Commission Generated', 'Hello {fname},\n\nCongratulations!  You''ve earned a referral commission!\n\nCommission Amount: {commission_amount}\nCommission Date: {current_date}\n\nLogin to check your affiliate stats:\n{login_url}\n\n{site_name}\n', '<p>Hello {fname},<br />\n<br />\nCongratulations!&nbsp; You''ve earned a referral commission!<br />\n<br />\nCommission Amount: {commission_amount}<br />\nCommission Date: {current_date}<br />\n<br />\nLogin to check your affiliate stats:<br />\n{login_url}<br />\n<br />\n{site_name}<br />\n&nbsp;</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_payment_sent_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Your Affiliate Payment has been sent', 'Hello {fname},\n\nWe''ve sent your affiliate payment.\n\nthe amount you''ve made is {payment_amount}\n\n{affiliate_note}\n\nThanks again for being a great affiliate!\n\n{site_name}\n{login_url}', '<p>Hello {fname},<br />\n<br />\nWe''ve sent your affiliate payment.<br />\n<br />\nthe amount you''ve made is {payment_amount}<br />\n<br />\n{affiliate_note}<br />\n<br />\nThanks again for being a great affiliate!<br />\n<br />\n{site_name}<br />\n{login_url}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_reset_password_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'your password has been reset', 'Hello {fname},\n\nhere is your new password: {new_password}\n\nlogin URL: {login_url}\n\n\n{site_name}\n\n{login_url}\n', '<p>Hello {fname},<br />\n<br />\nhere is your new password: {new_password}<br />\n<br />\nlogin URL: {login_url}</p>\n<p>{site_name}</p>\n<p>{login_url}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_email_confirmation_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Account Confirmation Email', 'Hello {fname},\n\nplease click on the following link to confirm your account with us:\n\n{confirm_link}\n\n{site_name}\n{login_url}', '<p>Hello {fname},<br />\n<br />\nplease click on the following link to confirm your account with us:<br />\n<br />\n<a href=\"{confirm_link}\">click here to confirm</a><br />\n<br />\n{site_name}<br />\n{login_url}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_send_downline_email', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Message from your sponsor', 'Hello {downline_member_name},\n\nYour sponsor, {downline_sponsor_name} has sent you a message:\n\n{downline_message_text} \n\n{downline_sponsor_name}\n{downline_sponsor_email}\n{downline_sponsor_affiliate_link}\n\n\n{site_name}\n{login_url}\n\n', '<p>Hello {downline_member_name},<br />\n<br />\nYour sponsor, {downline_sponsor_name} has sent you a message:<br />\n<br />\n{downline_message_html} <br />\n<br />\n{downline_sponsor_name}<br />\n{downline_sponsor_email}<br />\n{downline_sponsor_affiliate_link}<br />\n<br />\n<br />\n{site_name}<br />\n{login_url}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_downline_signup', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'You just referred someone!', 'Hello {fname},\n\nYou have just referred someone in your downline!\n\n{downline_name}\n{downline_email}\n', '<p>Hello {fname},<br />\n<br />\nYou have just referred someone in your downline!<br />\n<br />\n{downline_name}<br />\n{downline_email}<br />\n&nbsp;</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_marketing_approval_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Affiliate Registration Approval', 'Hello {fname},\n\nYour affiliate registration has been approved!\n\n" . $data['program_name'] . "', '<p>Hello {fname},<br />\n<br />\nYour affiliate registration has been approved!<br />\n<br />" . $data['program_name'] . "</p>'),
(" . $data['id'] . ",'member', 'html', '', 'admin_affiliate_marketing_activation_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Affiliate Activation Request', 'Hello,\n\n{member_name} is requesting that you activate his affiliate account.\n\nPlease login to your admin area to confirm\n\n" . $data['program_name'] . "\n" . base_url() . "programs/" . $data['signup_link'] . "', '<p>Hello,<br />\n<br />\n{member_name} is requesting that you activate his affiliate account.<br />\n<br />Please login to your admin area to confirm<br /><br />" . $data['program_name'] . "<br />\n" . base_url() . "programs/" . $data['signup_link'] . "/</p>'),
(" . $data['id'] . ", 'member', 'html', '', 'member_affiliate_program_confirm_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Please confirm your affiliate program account', 'Hello {fname},\r\n\r\nPlease click on the link below to confirm your account access to our {program_name}\r\n\r\n{confirm_link}', '<p>Hello {fname},</p>\r\n<p>Please click on the link below to confirm your account access to our {program_name}</p>\r\n<p>{confirm_link}</p>'),
(" . $data['id'] . ", 'member', 'html', '', 'member_affiliate_commission_stats_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Your Affiliate Commission Stats Report', 'Hello {fname},\n\nHere is your current affiliate commissions stats:\n\n{current_month} {current_year}\nUnpaid Commissions: {current_month_unpaid_commissions}\n\nTotal Commissions Made: \nUnpaid Commissions: {total_unpaid_commissions}\nPaid Commissions: {total_paid_commissions}\n\nYou can login to our members area to view all of your stats in more detail:\n\n{login_url}', '<p>Hello {fname},<br />\n <br />\n Here is your current affiliate commissions stats:</p>\n<p>{current_month} {current_year}<br />\n Unpaid Commissions: {current_month_unpaid_commissions}<br />\n </p>\n<p>Total Commissions Made:<br />\n Unpaid Commissions: {total_unpaid_commissions}<br />\n Paid Commissions: {total_paid_commissions}</p>\n<p>You can login to our members area to view all of your stats in more detail:</p>\n<p>{login_url}</p>')
;";
		
		$this->DB1->query($sql);
		
	}
	
	// ------------------------------------------------------------------------
	
	function _clean_array($array = array())
	{
		foreach ($array as $k => $v)
		{
			if (is_string($v))
			{
				$array[$k] = addslashes($v);
			}
		}
		
		return $array;
	}
	
	// ------------------------------------------------------------------------
	
	
}
?>