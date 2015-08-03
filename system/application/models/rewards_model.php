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
| FILENAME - rewards_model.php
| -------------------------------------------------------------------------     
| 
| This model handles performance rewards functions 
|
*/

class Rewards_Model extends CI_Model {
	
	var $table = 'performance_rewards';
	
	// ------------------------------------------------------------------------
	
	function _check_rewards($data = array())
	{		
		$sql = 'SELECT * FROM ' . $this->db->dbprefix('performance_rewards') . '
			    WHERE (sale_type = \'amount_of_commission\'
				OR sale_type = \'amount_of_sale\') 
				ORDER BY id ASC';

		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			//check sale
			foreach ($query->result_array() as $row)
			{
				$insert = false;
				
				switch ($row['sale_type'])
				{
					case 'amount_of_sale':
					
						switch ($row['greater_than'])
						{
							case 'greater_than':
								
								if ($data['sale'] > $row['sale_amount'])
								{
									$insert = true;
								}
								
							break;
							
							case 'less_than':
								
								if ($data['sale'] < $row['sale_amount'])
								{
									$insert = true;
								}
								
							break;
							
							case 'equal_to':
								
								if ($data['sale'] == $row['sale_amount'])
								{
									$insert = true;
								}
								
							break;
						}
						
					break;
					
					case 'amount_of_commission':
						
						switch ($row['greater_than'])
						{
							case 'greater_than':
								
								if ($data['comm'] > $row['sale_amount'])
								{
									$insert = true;
								}
								
							break;
							
							case 'less_than':
								
								if ($data['comm'] < $row['sale_amount'])
								{
									$insert = true;
								}
								
							break;
							
							case 'equal_to':
								
								if ($data['comm'] == $row['sale_amount'])
								{
									$insert = true;
								}
								
							break;
						}
						
					break;
				}
				
				//insert performance reward
				if (!empty($insert))
				{
					$action = array(
									'action' => $row['action'],
									'bonus' => $row['bonus_amount'],
									'gid' => $row['group_id'],
									'mid' => $data['mid'],
									'num_options' => $data['num_options'],
									'comm_status' => $data['comm_status'],
									);
					$this->_insert_reward($action);
				}
			}
		}

		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _generate_rewards()
	{
		$total = 0;
		
		//set the commission status
		switch ($this->config->item('sts_affiliate_new_commission'))
		{
			case 'no_pending':
			case 'alert_pending':
				
				$comm_status = 'pending';
				
			break;
			
			case 'no_unpaid':
			case 'alert_unpaid':
			
				$comm_status = 'unpaid';
				
			break;
		}
		
		$sql = 'SELECT * FROM ' . $this->db->dbprefix('performance_rewards') . '
			    WHERE sale_type = \'total_amount_of_commissions\'
				OR sale_type = \'total_amount_of_sales\'
				OR sale_type = \'total_amount_of_referrals\'
				ORDER BY sale_type ASC'; 
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$rewards = $query->result_array();
			
			//get all of the members
			$this->db->select('member_id');
			$query = $this->db->get('members');
			
			if ($query->num_rows() > 0)
			{
				$members = $query->result_array();
				
				foreach ($members as $mem)
				{
					foreach ($rewards as $row)
					{
						$insert = false;
						
						$this->db->where('performance_paid', '0');
				
						switch ($row['sale_type'])
						{
							case 'total_amount_of_commissions':
								$this->db->where('member_id', $mem['member_id']);
								$this->db->select_sum('commission_amount');
								$date = 'date';
								$table = 'commissions';
								$id = 'commission_amount';
							break;
							
							case 'total_amount_of_sales':
								$this->db->where('member_id', $mem['member_id']);
								$this->db->select_sum('sale_amount');
								$date = 'date';
								$table = 'commissions';
								$id = 'sale_amount';
							break;
							
							case 'total_amount_of_referrals':
								$this->db->where('sponsor_id', $mem['member_id']);
								$date = 'signup_date';
								$table = 'members';
								
							break;
						}
						
						//check the time limit
						switch ($row['time_limit'])
						{
							case 'current_month':
									
								$start = mktime('0', '0', '0', date('m'), '1', date('Y'));
								$end = mktime('23', '59', '59', date('m'), date('t'), date('Y'));
								$this->db->where($date . ' >', $start);
								$this->db->where($date . ' <', $end);
								
							break;
							
							case 'current_year':
								
								$start = mktime('0', '0', '0', '1', '1', date('Y'));
								$end = mktime('23', '59', '59', '12', '31', date('Y'));
								$this->db->where($date . ' >', $start);
								$this->db->where($date . ' <', $end);
								
							break;
							
							case 'last_month':
								
								$days = date('t', mktime('12', '00', '00', date('m')- 1, '1', date('Y')));
								$start = mktime('0', '0', '0', date('m') - 1, '1', date('Y'));
								$end = mktime('23', '59', '59', date('m'), $days, date('Y'));
								$this->db->where($date . ' >', $start);
								$this->db->where($date . ' <', $end);
								
							break;
							
							case 'last_year':
								
								$start = mktime('0', '0', '0', '1', '1', date('Y') - 1);
								$end = mktime('23', '59', '59', '12', '31', date('Y') - 1);
								$this->db->where($date . ' >', $start);
								$this->db->where($date . ' <', $end);
								
							break;
						}
						
						if ($row['sale_type'] == 'total_amount_of_referrals')
						{
							$total = $this->db->count_all_results('members');

						}
						else	
						{
							$query = $this->db->get($table);
								
							$t = $query->row_array();
							$total = $t[$id];
							
						}

						//check for the amount
						switch ($row['greater_than'])
						{
							case 'greater_than':
								
								if ($total > $row['sale_amount'])
								{
									$insert = true;
								}
								
							break;
							
							case 'less_than':
								
								if ($total < $row['sale_amount'])
								{
									$insert = true;
								}
								
							break;
							
							case 'equal_to':
								
								if ($total == $row['sale_amount'])
								{
									$insert = true;
								}
								
							break;
						}
					
						//insert performance reward
						if (!empty($insert))
						{
							$action = array(
											'action' => $row['action'],
											'bonus' => $row['bonus_amount'],
											'gid' => $row['group_id'],
											'mid' => $mem['member_id'],
											'num_options' => get_default_currency($this->config->item('sts_site_default_currency')),
											'comm_status' => $comm_status,
											);
							
							$this->_insert_reward($action);
							
							//update performance_paid
							switch ($row['time_limit'])
							{
								case 'current_month':
										
									$start = mktime('0', '0', '0', date('m'), '1', date('Y'));
									$end = mktime('23', '59', '59', date('m'), date('t'), date('Y'));
									$this->db->where($date . ' >', $start);
									$this->db->where($date . ' <', $end);
									
								break;
								
								case 'current_year':
									
									$start = mktime('0', '0', '0', '1', '1', date('Y'));
									$end = mktime('23', '59', '59', '12', '31', date('Y'));
									$this->db->where($date . ' >', $start);
									$this->db->where($date . ' <', $end);
									
								break;
								
								case 'last_month':
									
									$days = date('t', mktime('12', '00', '00', date('m')- 1, '1', date('Y')));
									$start = mktime('0', '0', '0', date('m') - 1, '1', date('Y'));
									$end = mktime('23', '59', '59', date('m'), $days, date('Y'));
									$this->db->where($date . ' >', $start);
									$this->db->where($date . ' <', $end);
									
								break;
								
								case 'last_year':
									
									$start = mktime('0', '0', '0', '1', '1', date('Y') - 1);
									$end = mktime('23', '59', '59', '12', '31', date('Y') - 1);
									$this->db->where($date . ' >', $start);
									$this->db->where($date . ' <', $end);
									
								break;
							}
							
							$this->db->where('performance_paid', '0');
							$this->db->update($table, array('performance_paid' => '1'));
						
							$total++;
						}
					}
				}
			}
		}
		
		return $total . ' rewards generated';
	}
	
	// ------------------------------------------------------------------------
	
	function _insert_reward($data = array())
	{
		
		switch ($data['action'])
		{
			case 'issue_bonus_commission':
			
				//add the commission				
				$comm_array = array(
					'member_id' 						=> 			$data['mid'],
					'program_id' 						=> 			'0',
					'invoice_id' 						=> 			'0',
					'comm_status' 						=>			$data['comm_status'],
					'approved' 							=>			'0',
					'date' 								=>			_generate_timestamp(),
					'commission_amount'					=>			format_amounts($data['bonus'], $data['num_options'], true),
					'sale_amount' 						=>			'0',
					'commission_level' 					=>			1,
					'referrer' 							=>			'',
					'trans_id' 							=>			$this->lang->line('performance_reward_generated'),
					'ip_address' 						=>			$this->input->ip_address(),
					'commission_notes' 					=>			'',
					'performance_paid' 					=>			'1',
					'email_sent' 						=>			'0',
					'tool_type' 						=>			'',
					'tool_id' 							=>			'',
					);
				
				$this->comms->_add_commission($comm_array);
			
			break;
			
			case 'assign_affiliate_group':
			
				$this->groups->_update_member_group($data['mid'], $data['gid']);
				
			break;
		}	
	}
	
	// ------------------------------------------------------------------------
	
	function _get_rewards($id = '', $sort_column = '', $sort_order = '')
	{
		//$this->db->where('program_id', $id);
		$this->db->order_by('sort_order', 'ASC');
		$query = $this->db->get($this->table);
		
		if ($query->num_rows() > 0)
		{
			$data = array();
			
			foreach ($query->result_array() as $v)
			{
				if ($v['sale_type'] == 'total_amount_of_commissions' || $v['sale_type'] == 'total_amount_of_sales' || $v['sale_type'] == 'total_amount_of_referrals')
				{
					$v['rule'] = $this->lang->line('if') . ' <span class="rewardLinks">' . $this->lang->line($v['sale_type']) . ' ' . $this->lang->line('is') . ' ' . $this->lang->line($v['greater_than']) . ' ' . $v['sale_amount']  . '</span> ' . $this->lang->line('for') . ' <span class="rewardLinks">' . $this->lang->line($v['time_limit']) . '</span> ' . $this->lang->line('then') . ' <span class="rewardLinks">' . $this->lang->line($v['action']) . ' ';
				}
				else
				{
					$v['rule'] = $this->lang->line('if') . ' <span class="rewardLinks">' . $this->lang->line($v['sale_type']) . ' ' . $this->lang->line('is') . ' ' . $this->lang->line($v['greater_than']) . ' ' . $v['sale_amount']  . '</span> '  . $this->lang->line('then') . ' <span class="rewardLinks">' . $this->lang->line($v['action']) . ' ';
				}
				
				if ($v['action'] == 'assign_affiliate_group')
				{
					$row =  $this->_get_reward_group($v['group_id']);
					$v['rule'] .= $row['aff_group_name'];
				}
				else
				{
					$v['rule'] .= $v['bonus_amount'];
				}
               
			   $v['rule'] .= '</span>';
			   
				array_push($data, $v);	
			}
			
			return $data;
		}
		
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_reward($id = '', $post = array())
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($post);
		
		$this->db->where('id', $id);

		if (!$this->db->update($this->table, $data)) 
		{
			show_error($this->lang->line('could_not_update_reward'));
			
			//log error
			log_message('error', 'Could not update reward ' . $id . 'in rewards table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'reward ID '. $id . ' updated in rewards table');
		}
		
		return true;
	}

	// ------------------------------------------------------------------------
	
	function _add_reward($id = '', $post = array())
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($post);
		
		$data['program_id'] = $id;

		if (!$this->db->insert($this->table, $data)) 
		{
			show_error($this->lang->line('could_not_add_reward'));
			
			//log error
			log_message('error', 'Could not add reward ' . $id . 'in rewards table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			//log success
			log_message('info', 'reward ID '. $id . ' added in rewards table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_reward_group($id = '')
	{
		$this->db->where('group_id', $id);
		$query = $this->db->get('affiliate_groups');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return false;	
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_reward($id = '')
	{
		$this->db->where('id', $id);
		if ($this->db->delete($this->table))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_reward_details($id = '')
	{
		$this->db->where('id', $id);
		
		$query = $this->db->get($this->table);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;	
	}
	
	// ------------------------------------------------------------------------	
	
	function _change_sort_order($table = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		foreach ($data as $k => $v)
		{
			if (strstr($k, "reward") == true) 
			{
				$id = explode("-", $k);
			
				$this->db->where('id', $id[1]);
				
				$sdata = array('sort_order' => $v);
				
				if (!$this->db->update($table, $sdata))
				{
					show_error($this->lang->line('could_not_update_reward'));
					
					log_message('error', 'Could not update reward #' . $k . ' in performance rewards table');
					
					return false;
				}
			}
		}
		
		$this->db_validation_model->_db_sort_order($table, 'id', 'sort_order'); 
		
		return true;		
	}
	
	// ------------------------------------------------------------------------
}	
?>