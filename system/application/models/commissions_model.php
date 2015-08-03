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
| FILENAME - commissions_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for generating commissions
|
*/

class Commissions_Model extends CI_Model {
	
	// ------------------------------------------------------------------------
	
	function _mark_all_approved()
	{
		$array = array(
						'comm_status' => 'unpaid',
						'approved' => '1',
						);
		
		$this->db->where('comm_status !=', 'paid');
		$this->db->update('commissions', $array);	
	}
	
	// ------------------------------------------------------------------------
	
	function _get_sale_values($type = '', $post = array())
	{
		$this->db->where('name', $type);
		$query = $this->db->get('integration_profiles');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			foreach ($row as $k =>$v)
			{
				if (isset($post[$v]))
				{
					$post[$k] = $post[$v];
				}
			}
		}
		
		if (!empty($row['program_id']))
		{
			$post['program_id'] = $row['program_id'];
		}	
		
		if (!empty($row['product_code']))
		{
			$post['product_code'] = $row['product_code'];
		}	

		//check for customer name
		if (empty($post['customer_name']))
		{
			$post['customer_name'] = '';
			
			if (!empty($post['first_name']))
			{
				$post['customer_name'] .= $post['first_name'];	
			}
			
			if (!empty($post['last_name']))
			{
				$post['customer_name'] .= ' ' . $post['last_name'];	
			}
		}
		
		return $post;
	}
	
	// ------------------------------------------------------------------------
	
	function _run_comm_checks($data = array())
	{
		//check IP blocks
		$this->security_model->_check_sale_restriction('sts_sale_security_block_ip');
		
		//check IP address restriction
		if (!$this->security_model->_check_sale_restriction('sts_sale_security_restrict_ip'))
		{
			die('<h1>403 Restricted IP</h1>');
		}
		
		//check duplicate transaction monitor
		if (!empty($data['trans_id']))
		{
			$this->_check_trans_id($data['trans_id']);
		}
		
		//check for order ID
		if (!empty($data['order_id']))
		{
			$this->_check_recur_restrictions($data);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _check_recur_restrictions($data = array())
	{
		if (empty($data['order_id'])){ return; }
		
		$pid = empty($data['program_id']) ? 1 : $data['program_id'];
		
		$this->db->where('program_id', $pid);
		$query = $this->db->get('programs');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			if (!empty($row['restrict_recur_commissions']))
			{
				//check how many order IDs are in the database
				$this->db->where('order_id', xss_clean($data['order_id']));
				$this->db->where('commission_level', '1');
				
				$total = $this->db->count_all_results('commissions');
				
				if ($row['restrict_recur_commissions'] <= $total)
				{
					die('<h1>recurring order ID commission reached</h1>');	
				}
							
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _get_total_member_commissions($id = '', $pending = false)
	{
		$this->db->select_sum('commission_amount');	
		$this->db->where('member_id' , $id);
		if ($pending == true)
		{
			$this->db->where('comm_status' , 'pending');
		}
		$query = $this->db->get('commissions');
	
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();	
		
			return $row['commission_amount'];
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_trans_id($id = '')
	{
		//check for unique transaction ID requirement
		if ($this->config->item('sts_sale_require_unique_trans_id'))
		{
			$this->db->where('trans_id' , $id);
			$this->db->limit(1);
			
			$query = $this->db->get('commissions');
			
			if ($query->num_rows() > 0)
			{
				die('<h1>403 Unique Transaction ID Required</h1>');
			}	
		}
		
		if ($this->config->item('sts_sale_security_monitor_duplicate_trans_id'))
		{
			$this->db->where('trans_id' , $id);
			$this->db->order_by('date', 'DESC');
			$this->db->limit(1);
			
			$query = $this->db->get('commissions');
			
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array();
				
				//check timer
				$expires = _generate_timestamp() - $row['date'];
	
				if ($expires < $this->config->item('sts_sale_security_monitor_duplicate_trans_id'))
				{
					die('<h1>403 Duplicate Transaction ID</h1>');
				}
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _add_ppc($mid = '', $tool = array(), $amount = 0)
	{
		if ($amount > 0)
		{
			$comm_notes = $this->lang->line('pay_per_click') . "\n";
	
			//set all the required data
			$referral_bonus = array('member_id' 		=> 			$mid,			
									'invoice_id'		=>			'0',
									'program_id'		=>			$tool['program_id'],
									'comm_status'		=>			$this->_get_new_comm_status(),
									'approved'			=>			'0',
									'date'				=>			_generate_timestamp(),
									'commission_amount'	=>			$amount,
									'sale_amount'		=>			'0',
									'commission_level'	=>			'1',
									'referrer'			=>			$this->agent->referrer(),
									'trans_id'			=>			$this->lang->line('pay_per_click') . '-' . $this->input->ip_address(),
									'ip_address'		=>			$this->input->ip_address(),
									'date_paid'			=>			'',
									'commission_notes'	=>			$comm_notes,
									'performance_paid'	=>			'0',
									'email_sent'		=>			'1',
									'tool_type'			=>			empty($tool['type']) ? 0 : $tool['type'],
									'tool_id'			=>			empty($tool['id']) ? 0 : $tool['id'],
							);	
		  
			//add the commission
			$this->_add_commission($referral_bonus);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _add_referral_bonus($row = array())
	{	
		$comm_notes = $this->lang->line('referral_signup_bonus_note') . "\n";
		
		if (!empty($userdata['sponsor_id']))
		{
			//set all the required data
			$referral_bonus = array('member_id' 		=> 			$row['sponsor_id'],			
									'invoice_id'		=>			'0',
									'program_id'		=>			$row['program_id'],
									'comm_status'		=>			$this->_get_new_comm_status(),
									'approved'			=>			'0',
									'date'				=>			_generate_timestamp(),
									'commission_amount'	=>			$row['ref_bonus'],
									'sale_amount'		=>			'0',
									'commission_level'	=>			'1',
									'referrer'			=>			'',
									'trans_id'			=>			$this->lang->line('trans_id_referral_signup_bonus') . ' - ' . date('M d Y'),
									'ip_address'		=>			$this->input->ip_address(),
									'date_paid'			=>			'',
									'commission_notes'	=>			$comm_notes,
									'performance_paid'	=>			'1',
									'email_sent'		=>			'1',
							);	
		
			//add the commission
			$this->_add_commission($referral_bonus);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _get_new_comm_status()
	{
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
		
		return $comm_status;
	}
	
	// ------------------------------------------------------------------------
	
	function _add_affiliate_bonus($row = array())
	{
		
	  
		$comm_notes = $this->lang->line('affiliate_signup_bonus_note') . "\n";
		
		//set all the required data
		$referral_bonus = array('member_id' 		=> 			$row['member_id'],			
								'invoice_id'		=>			'0',
								'program_id'		=>			$row['program_id'],
								  'comm_status'		=>			$this->_get_new_comm_status(),
								'approved'			=>			'0',
								'date'				=>			_generate_timestamp(),
								'commission_amount'	=>			$row['aff_bonus'],
								'sale_amount'		=>			'0',
								'commission_level'	=>			'1',
								'referrer'			=>			'',
								'trans_id'			=>			$this->lang->line('trans_id_signup_bonus')  . ' - ' . date('M d Y'),
								'ip_address'		=>			$this->input->ip_address(),
								'date_paid'			=>			'',
								'commission_notes'	=>			$comm_notes,
								'performance_paid'	=>			'1',
								'email_sent'		=>			'1',
						);	
	  
		//add the commission
		$this->_add_commission($referral_bonus);
	}
	
	// ------------------------------------------------------------------------
	
	function _add_action_commission($data = '')
{
$data = $this->db_validation_model->_clean_data($data);

    //insert into db
if (!$this->db->insert('action_commissions', $data))
{
show_error($this->lang->line('could_not_add_action_commission'));

log_message('error', 'Could not insert action commission into commissions table');

return false;
}
else
{
    log_message('info', 'action commission  inserted into action commissions table');
}

return $this->db->insert_id();
}

// ------------------------------------------------------------------------

    function _get_action_commission_details($id = '', $field = 'id', $status = false)
{
    //get the data from commissions table
    $sql = 'SELECT ' . $this->db->dbprefix('action_commissions') . '.*
						FROM ' . $this->db->dbprefix('action_commissions') . '
						WHERE ' . $field . ' = \'' . $id . '\'';

    if ($status == true)
    {
        $sql .= ' AND status = \'1\'';
    }

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0)
    {
        return $query->row_array();
    }
    else
    {
        return false;
    }
}

// ------------------------------------------------------------------------

    function _update_action_commission($id = '', $data = '')
{
    //clean the data first
    $data = $this->db_validation_model->_clean_data($_POST);

    $this->db->where('id', $id);

    if (!$this->db->update('action_commissions', $data))
    {
        show_error($this->lang->line('could_not_action_commission'));

        //log error
        log_message('error', 'Could not update action_commission ID ' . $id . 'in action_commission table');

        return false;
    }
    else
    {
        //log success
        log_message('info', 'action_commission ID '. $id . ' updated in action_commission table');
    }
}

// ------------------------------------------------------------------------

    function _get_action_commissions($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
{
    //get all the admins from db for list view
    if (!$sort_order) $sort_order = $this->config->item('dbs_acc_order');
    if (!$sort_column) $sort_column = $this->config->item('dbs_acc_column');

    $this->db->order_by($sort_column, $sort_order);
    $query = $this->db->get('action_commissions', $limit, $offset);

    if ($query->num_rows() > 0)
    {
        return $query->result_array();
    }

    return  false;
}

// ------------------------------------------------------------------------

    function _delete_action_commission($id = '')
{
    $this->db->where('id', $id);

    if ($this->db->delete('action_commissions'))
    {

        //log success
        log_message('info', 'action_commission #' . $id . ' deleted successfully');
    }
    else
    {
        show_error($this->lang->line('could_not_delete_action_commission'));

        //log error
        log_message('error', 'action_commission #' . $id . ' could not be deleted');
    }

    return true;
}

// ------------------------------------------------------------------------

    function _add_product_commission($data = '')
    {
        $data = $this->db_validation_model->_clean_data($data);

        //insert into db
        if (!$this->db->insert('product_commissions', $data))
        {
            show_error($this->lang->line('could_not_add_action_commission'));

            log_message('error', 'Could not insert action commission into commissions table');

            return false;
        }
        else
        {
            log_message('info', 'action commission  inserted into action commissions table');
        }

        return $this->db->insert_id();
    }

    // ------------------------------------------------------------------------

    function _get_product_commission_details($id = '', $field = 'id', $status = false)
    {
        //get the data from commissions table
        $sql = 'SELECT ' . $this->db->dbprefix('product_commissions') . '.*
						FROM ' . $this->db->dbprefix('product_commissions') . '
						WHERE ' . $field . ' = \'' . $id . '\'';

        if ($status == true)
        {
            $sql .= ' AND status = \'1\'';
        }

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {
            return false;
        }
    }

    // ------------------------------------------------------------------------

    function _update_product_commission($id = '', $data = '')
    {
        //clean the data first
        $data = $this->db_validation_model->_clean_data($_POST);

        $this->db->where('id', $id);

        if (!$this->db->update('product_commissions', $data))
        {
            show_error($this->lang->line('could_not_action_commission'));

            //log error
            log_message('error', 'Could not update action_commission ID ' . $id . 'in action_commission table');

            return false;
        }
        else
        {
            //log success
            log_message('info', 'action_commission ID '. $id . ' updated in action_commission table');
        }
    }

    // ------------------------------------------------------------------------

    function _get_product_commissions($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
    {
        //get all the admins from db for list view
        if (!$sort_order) $sort_order = $this->config->item('dbs_acc_order');
        if (!$sort_column) $sort_column = $this->config->item('dbs_acc_column');

        $this->db->order_by($sort_column, $sort_order);
        $query = $this->db->get('product_commissions', $limit, $offset);

        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }

        return  false;
    }

    // ------------------------------------------------------------------------

    function _delete_product_commission($id = '')
    {
        $this->db->where('id', $id);

        if ($this->db->delete('action_commissions'))
        {

            //log success
            log_message('info', 'action_commission #' . $id . ' deleted successfully');
        }
        else
        {
            show_error($this->lang->line('could_not_delete_action_commission'));

            //log error
            log_message('error', 'action_commission #' . $id . ' could not be deleted');
        }

        return true;
    }

    // ------------------------------------------------------------------------
	
	function _generate_recurring_commissions2()
	{
		$sql = 'SELECT ' . $this->db->dbprefix('commissions') . '.*, ' . $this->db->dbprefix('programs') . '.commission_frequency
				FROM ' . $this->db->dbprefix('commissions') . '
				LEFT JOIN ' . $this->db->dbprefix('programs') . '  
				ON ' . $this->db->dbprefix('commissions') . '.program_id = ' . $this->db->dbprefix('programs') . '.program_id 
				WHERE recur < \'' . _generate_timestamp() . '\'
				AND recur > \'0\' ORDER BY comm_id ASC';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
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
		 
			$i = 0;
			
			foreach ($query->result_array() as $row)
			{
				
				//echo _show_date($row['date']) . '<BR />';
				//echo _show_date($row['recur']) . '<BR />';
				$comm_id = $row['comm_id'];
				//now add it
				$row['commission_notes'] = 'previous commission: ' . $row['comm_id'];
				$row['comm_id'] = '';
				$row['date'] = $row['recur'];
				$row['recur'] = $row['recur'] + (3600 * 24 * $row['commission_frequency']);	
				$row['date_paid'] = '';
				$row['approved'] = '0';
				$row['performance_paid'] = '0';
				$row['payment_id'] = '0';
				
				$row['comm_status'] = $comm_status;
				//echo _show_date($row['date']) . '<BR />';
				//echo _show_date($row['recur']); 
				unset($row['commission_frequency']);
				//echo '<pre>';print_r($row);
				$this->db->insert('commissions', $row);
				//die();
				
				//update commission
				
				$this->db->where('comm_id', $comm_id);
				$this->db->update('commissions', array('recur' => $this->db->insert_id()));
				
				$i++;
			}
			
			return $i .' recurring commissions processed';
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _generate_recurring_commissions()
	{
		/*
		$sql = 'SELECT ' . $this->db->dbprefix('commissions') . '.*, ' . $this->db->dbprefix('programs') . '.commission_frequency
				FROM ' . $this->db->dbprefix('commissions') . '
				LEFT JOIN ' . $this->db->dbprefix('programs') . '  
				ON ' . $this->db->dbprefix('commissions') . '.program_id = ' . $this->db->dbprefix('programs') . '.program_id 
				WHERE recur < \'' . _generate_timestamp() . '\'
				AND recur > \'0\'';
			*/	
				
		$sql = 'SELECT ' . $this->db->dbprefix('commissions') . '.*, ' . $this->db->dbprefix('programs') . '.commission_frequency
				FROM ' . $this->db->dbprefix('commissions') . '
				LEFT JOIN ' . $this->db->dbprefix('programs') . '  
				ON ' . $this->db->dbprefix('commissions') . '.program_id = ' . $this->db->dbprefix('programs') . '.program_id 
				WHERE recurring_comm  = \'1\' AND (recur < \'' . _generate_timestamp() . '\'
				AND recur > \'0\')';

		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
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
		 
			$i = 0;
			
			foreach ($query->result_array() as $row)
			{
				//update commission
				$this->db->where('comm_id', $row['comm_id']);
				$new_date = array(
									'recur' => _generate_timestamp() + (3600 * 24 * $row['commission_frequency'])
								);
				$this->db->update('commissions', $new_date);
				
				//now add it
				$row['parent_id'] = $row['comm_id'];	
				$row['comm_id'] = '';
				$row['date'] = _generate_timestamp();
				$row['recur'] = '0';
				$row['recurring_comm'] = '0';
				$row['date_paid'] = '';
				$row['approved'] = '0';
				$row['performance_paid'] = '0';
				$row['payment_id'] = '0';
				
				$row['comm_status'] = $comm_status;
				
				unset($row['commission_frequency']);
				
				$this->db->insert('commissions', $row);
			
				$i++;
			}
			
			return $i .' recurring commissions processed';
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_scaled_commissions()
	{
		//get the data from scaled_commissions table
		$this->db->order_by('max_amount', 'ASC');
		$query = $this->db->get('scaled_commissions');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_scaled_commission($id = '')
	{
		$this->db->where('id', $id);
		
		if ($this->db->delete('scaled_commissions'))
		{
			
			//log success
			log_message('info', 'scaled_commissions #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_scaled_commission'));
			
			//log error
			log_message('error', 'scaled_commissions #' . $id . ' could not be deleted');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_scaled_commissions($post_array)
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($post_array);
		
		foreach ($data as $k => $v)
		{
			if (preg_match('/min_amount-*/', $k)) 
			{	
				$array = explode('-', $k);
				
				$id = $array[1];
				
				if ($id == '0')
				{
					if (!empty($data['min_amount-0']) && !empty($data['max_amount-0']) && !empty($data['comm_amount-0']))
					{
						$data['status-0'] = (empty($data['status-0'])) ? '0' : $data['status-0'];
						
						$insert = array(
											'status' => $data['status-0'],
											'min_amount'	=>	xss_clean($data['min_amount-0']),
											'max_amount'	=> xss_clean($data['max_amount-0']),
											'comm_amount' => xss_clean($data['comm_amount-0']),
											'type' => xss_clean($data['type-0']),
											'level' => xss_clean($data['level-0']),
											'status' => xss_clean($data['status-0']),
										);
										
						$this->db->insert('scaled_commissions', $insert);
					}
				}
				else
				{
					if (!empty($data['min_amount-'. $id]) && !empty($data['max_amount-' . $id]) && !empty($data['comm_amount-'  . $id]))
					{
						$data['status-' . $id] = (empty($data['status-' . $id])) ? '0' : $data['status-' . $id];
						
						$update = array(
											'status' => $data['status-' . $id],
											'min_amount'	=>	xss_clean($data['min_amount-' . $id]),
											'max_amount'	=> xss_clean($data['max_amount-' . $id]),
											'comm_amount' => xss_clean($data['comm_amount-' . $id]),
											'type' => xss_clean($data['type-' . $id]),
											'level' => xss_clean($data['level-' . $id]),
											'status' => xss_clean($data['status-' . $id]),
										);
						
						$this->db->where('id', $id);
						$this->db->update('scaled_commissions', $update);
					}
				}
			}	
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _add_commission($data = '')
	{
		$data = $this->db_validation_model->_clean_data($data);
		
		unset($data['referring_affiliate']);
		unset($data['use_program_defaults']);
		unset($data['credit_upline']);
		unset($data['send_email_alert']);
		
		//insert into db
		if (!$this->db->insert('commissions', $data))
		{
			show_error($this->lang->line('could_not_add_commission'));
			
			//log error
			log_message('error', 'Could not insert commission into commissions table');
			
			return false;
		}
		else
		{
			$data['comm_id']= $this->db->insert_id();
			
			//log success
			log_message('info', 'commission '. $data['comm_id'] . ' inserted into commissions table');
		}
		
		return $data;
	}

	
	// ------------------------------------------------------------------------
	
	function _update_invoice_commissions($id = '', $type = '') //update commissions associated with invoice on refund
	{
		if ($type != 'none')
		{
			//set the arrays
			$paid_comms = array();
			$unpaid_comms = array();
		
			//get all commissions associated with invoice
			$this->db->where('invoice_id', $id);
			$query = $this->db->get('commissions');
			
			if ($query->num_rows() > 0)
			{
				//sort commissions
				foreach ($query->result_array() as $row)
				{
					if ($row['comm_status'] == 'paid')
					{
						array_push($paid_comms, $row);
					}
					else
					{
						array_push($unpaid_comms, $row);
					}
				}
				//echo '<pre>'; print_r($unpaid_comms); exit();
				//let's process the unpaid commissions first
				
				if (count($unpaid_comms) > 0)
				{
					$comm_ids = array();
					foreach ($unpaid_comms as $v)
					{
						array_push($comm_ids, $v['comm_id']);
					}
					
					$this->_change_status($comm_ids, $type);
				}
				
				//now lets do the paid commissions
				
				//we need to create a reverse entry for this since the commission is already paid
				
				if (count($paid_comms) > 0)
				{
					foreach ($paid_comms as $v)
					{
						$reverse = array(	'member_id' 			=> 			$v['member_id'],			
											'invoice_id'			=>			$id,
											'comm_status'			=>			'unpaid',
											'approved'				=>			'1',
											'date'					=>			_generate_timestamp(),
											'commission_amount'		=>			'-' . $v['commission_amount'],
											'sale_amount'			=>			'0',
											'commission_level'		=>			$v['commission_level'],
											'referrer'				=>			'',
											'trans_id'				=>			$this->lang->line('commission_reversal') . ' - ' . $v['comm_id'],
											'ip_address'			=>			$v['ip_address'],
											'date_paid'				=>			'',
											'commission_notes'		=>			$this->lang->line('commission_reversal'),
											'performance_paid'		=>			'1',
											'email_sent'			=>			'1',
								);	
			
						//add the commission
						$this->_add_commission($reverse);
					}
				}
			}
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _auto_approve_commissions($day = '')
	{
		//get all commissions
		
		$time = _generate_timestamp() - ($day * 60 * 60 * 24);
		 
		$this->db->where('approved', '0');
		$this->db->where('date <=', $time);
		$query = $this->db->get('commissions');
		
		if ($query->num_rows() > 0)
		{
			$i = 0;
			
			foreach ($query->result_array() as $row)
			{
				//now approve it
				$array = array('approved' => '1', 'comm_status' => 'unpaid');
				$this->db->where('comm_id', $row['comm_id']);
				$this->db->update('commissions', $array);
			
				$i++;
			}
			
			return $i .' affiliate commissions auto-approved';
		}
		
		return false;
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _change_trans_status($key = 'trans_id', $id = '', $type = 'pending')
	{
		$types = array('pending', 'unpaid', 'paid');
		
		if (in_array($type, $types))
		{
			$this->db->where($key, $id);
			$this->db->update('commissions', array('comm_status' => $type));	
			
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _change_status($data = '', $type = '0')
	{

		foreach ($data as $id)
		{
			
			$comm = $this->_get_commission_details($id);
			
			$this->db->where('comm_id', $id);
			
			if ($type == 'delete')
			{
				$this->_delete_commission((int)($id));
			}
			else
			{
				//update member in db
				if ($type == 'pending')
				{
					$data = array('comm_status' => $type);
				}
				elseif ($type == 'unpaid')
				{
					$data = array('comm_status' => $type);
				}
				elseif ($type == 'paid')
				{
					$data = array('comm_status' => $type, 'approved' => '1');
				}
				elseif ($type == 'disable_recur')
				{
					$data = array('recur' => '0');
				}
				else
				{
					$data = array('approved' => $type,
								 );
				
					if ($comm['comm_status'] != 'paid')
					{
						$data['comm_status'] = 'unpaid';	
					}
				}
				
					
				if (!$this->db->update('commissions', $data))
				{
					show_error($this->lang->line('could_not_update_commission'));
					
					//log error
					log_message('error', 'Could not update commission ID #' . $id . ' in commissions table');
					return false;
				}
				
				//log success
				log_message('info', 'Status Changed for commission ID# ' . $id);
			}
			
		}
		
		return true;		
	}
	
	// ------------------------------------------------------------------------
	
	function _get_default_comm_amounts()
	{
		//get the default commission amounts for non-group members
		$default_comms = $this->db_validation_model->_get_details('affiliate_groups' , '*', 'group_id', '1');
				
		$comms = $default_comms[0];
	
		return $comms;
	}
	
	// ------------------------------------------------------------------------
	
	function _calculate_commission($type = '', $level = '', $level_amount = '', $subtotal = '')
	{
		//check if you need to use the default commissions
		if (empty($level_amount))
		{
			$defaults = $this->_get_default_comm_amounts();
			$level_amount = $defaults['commission_level_' . $level];
			$type = $defaults['commission_type'];
		}
		
		if ($type == 'percent')
		{
			$amount = $level_amount * $subtotal;
		}
		else
		{
			$amount = $level_amount;
		}
		
		return $amount;
	}
	 
	// ------------------------------------------------------------------------
	
	function _get_commissions_for_bonus($type = '', $mid = '')
	{
		//get only commissions that have not been calculated for performance
		$this->db->where('performance_paid', '0'); 
		$this->db->where('member_id', $mid);
		
		switch ($type)
		{
			case 'commission_amount':
			
				$this->db->select_sum('commission_amount');
			
			break;
			
			case 'sales_amount':
			
			break;
		
		}
		
		$query = $this->db->get('commissions');
		
		$a = $query->result_array();
	
		return $a[0]['commission_amount'];
	}
	
	// ------------------------------------------------------------------------
	
	function _upgrade_bonus_group($mid = '')
	{
		$this->db->where('member_id', $mid);
		$this->db->where('group_type', 'affiliate');
		$this->db->join('affiliate_groups', 'affiliate_groups.group_id = members_groups.group_id', 'left');
		$query = $this->db->get('members_groups');
		
		$group = $query->result_array();
		
		//current tier is
		$tier = $group[0]['tier'];
		
		//next tier
		$next = $tier + 1;
		
		//see if the tier is still available
		$this->db->where('tier', $next);
		$query = $this->db->get('affiliate_groups');
		
		if ($query->num_rows() > 0)
		{
			$new = $query->result_array();
			
			//update the user's group
			$sdata = array('group_id' => $new[0]['group_id']);
			
			$this->db->where('member_id', $mid);
			$this->db->where('group_type', 'affiliate');
			$this->db->update('members_groups', $sdata);
		
			return $new[0];
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_commissions_for_bonus($mid = '')
	{
		$this->db->where('performance_paid', '0'); 
		$this->db->where('member_id', $mid);
		
		$sdata = array('performance_paid' => '1');
		
		if ($this->db->update('commissions', $sdata))
		{
			return true;
		}
	
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_commission($id = '', $comm_id = 'comm_id')
	{
		//delete commission
		$this->db->where($comm_id, $id);
		if ($this->db->delete('commissions'))
		{
			
			//log success
			log_message('info', 'commission ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_commission'));
			
			//log error
			log_message('error', 'commission ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------

	function _get_commission_details($id = '')
	{
		//get the data from commissions table
		$sql = 'SELECT ' . $this->db->dbprefix('commissions') . '.*,
						(SELECT username from ' . $this->db->dbprefix('members') . ' 
						WHERE ' . $this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as username
						FROM ' . $this->db->dbprefix('commissions') . '
						WHERE comm_id = \'' . $id . '\' LIMIT 1';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _send_affiliate_stats($num_options = '')
	{
		$cmonth = date('m', _generate_timestamp());
		$cyear = date('Y', _generate_timestamp());
		
		$days = date('t', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		$start = mktime(0, 0, 0, $cmonth  , 1, $cyear);
		$end = mktime(23, 59, 59, $cmonth  , $days, $cyear);
			
		//get email members
		$sql = 'SELECT ' . $this->db->dbprefix('members') . '.*,
				(SELECT SUM(commission_amount) FROM ' . $this->db->dbprefix('commissions') . ' 
				WHERE ' . $this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id
				AND ' . $this->db->dbprefix('commissions') . '.comm_status = \'unpaid\'
				 ) as total_unpaid_commissions,
				 
				 (SELECT SUM(commission_amount) FROM ' . $this->db->dbprefix('commissions') . ' 
				WHERE ' . $this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id
				AND ' . $this->db->dbprefix('commissions') . '.comm_status = \'paid\'
				 ) as total_paid_commissions,
				 
				  (SELECT SUM(commission_amount) FROM ' . $this->db->dbprefix('commissions') . ' 
				WHERE ' . $this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id
				AND ' . $this->db->dbprefix('commissions') . '.comm_status = \'unpaid\'
				AND (date >= ' . $start . ' 
					  AND date <= ' . $end . ') 
				 ) as current_month_unpaid_commissions,
				 
				  (SELECT SUM(commission_amount) FROM ' . $this->db->dbprefix('commissions') . ' 
				WHERE ' . $this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id
				AND ' . $this->db->dbprefix('commissions') . '.comm_status = \'paid\'
				AND (date >= ' . $start . ' 
					  AND date <= ' . $end . ') 
				 ) as current_month_paid_commissions

				 
				FROM ' . $this->db->dbprefix('members') . ' WHERE ' . $this->db->dbprefix('members') . '.status = \'1\''
		;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$total = 0;
			foreach ($query->result_array() as $row)
			{
				$row['current_month'] = date('F', _generate_timestamp());
				$row['current_year'] = $cyear;
				$row['current_month_unpaid_commissions'] = format_amounts($row['current_month_unpaid_commissions'], $num_options);
				$row['current_month_paid_commissions'] = format_amounts($row['current_month_paid_commissions'], $num_options);
				$row['total_unpaid_commissions'] = format_amounts($row['total_unpaid_commissions'], $num_options);
				$row['total_paid_commissions'] = format_amounts($row['total_paid_commissions'], $num_options);
				
				unset($row['password']);
				$this->emailing_model->_send_template_email('member', $row, 'member_affiliate_commission_stats_template', true);	
				$total++;
				
			}
		
			return $total . ' emails sent';
		}
		else
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _get_commissions($limit = 25, $offset = 0, $sort_column = '', $sort_order = '',  $where_column = '', $where_value = '', $where_column2 = '', $where_value2 = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_afc_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_afc_column');

		if ($where_column == 'search')
		{
			$sql = 'SELECT ' . $this->db->dbprefix('commissions') . '.*, ' . $this->db->dbprefix('members') . '.*
						FROM ' . $this->db->dbprefix('commissions') . '
						JOIN ' . $this->db->dbprefix('members') . ' ON ' .
						$this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id
						WHERE ' . $this->db->dbprefix('commissions') . '.trans_id LIKE \'%' . $where_value .'%\'
						OR ' . $this->db->dbprefix('commissions') . '.commission_amount LIKE \'%' . $where_value .'%\'
						OR ' . $this->db->dbprefix('commissions') . '.sale_amount LIKE \'%' . $where_value .'%\'
						OR ' . $this->db->dbprefix('members') . '.fname LIKE \'%' . $where_value .'%\'
						OR ' . $this->db->dbprefix('members') . '.lname LIKE \'%' . $where_value .'%\'
						OR ' . $this->db->dbprefix('members') . '.username LIKE \'%' . $where_value .'%\'
						ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;

		}
		else
		{
			if (!empty($where_value))
			{

				  $sql = 'SELECT ' . $this->db->dbprefix('commissions') . '.*,
						  (SELECT username from ' . $this->db->dbprefix('members') . ' 
						  WHERE ' . $this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as username
						  FROM ' . $this->db->dbprefix('commissions');
							
				if (!empty($where_column) && !empty($where_value) && empty($where_column2) && empty($where_value2))
				{
					
					$sql .= ' WHERE ' . $where_column . ' = \'' . $where_value . '\'';
				}
				elseif (!empty($where_column) && !empty($where_value) && !empty($where_column2) && !empty($where_value2))
				{
					$sql .= ' WHERE ' . $where_column . ' = \'' . $where_value . '\'
								AND ' . $where_column2 . ' = \'' . $where_value2 . '\'';
				}
				elseif (empty($where_column) && empty($where_value) && !empty($where_column2) && !empty($where_value2))
				{
					$sql .= ' WHERE ' . $where_column2 . ' = \'' . $where_value2 . '\'';
				}
			
				
				$sql .= ' ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
			}
			else
			{
				$sql = 'SELECT ' . $this->db->dbprefix('commissions') . '.*,
						(SELECT username from ' . $this->db->dbprefix('members') . ' 
						WHERE ' . $this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as username
						FROM ' . $this->db->dbprefix('commissions');
						
				if (!empty($where_column) && !empty($where_value) && empty($where_column2) && empty($where_value2))
				{
					
					$sql .= ' WHERE ' . $where_column . ' = \'' . $where_value . '\'';
				}
				elseif (!empty($where_column) && !empty($where_value) && !empty($where_column2) && !empty($where_value2))
				{
					$sql .= ' WHERE ' . $where_column . ' = \'' . $where_value . '\'
								AND ' . $where_column2 . ' = \'' . $where_value2 . '\'';
				}
				elseif (empty($where_column) && empty($where_value) && !empty($where_column2) && !empty($where_value2))
				{
					$sql .= ' WHERE ' . $where_column2 . ' = \'' . $where_value2 . '\'';
				}
				
				$sql .= ' ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
			}
		}
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	function _update_commission($id = '')
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		$referring_affiliate = $data['referring_affiliate'];
		
		unset($data['use_program_defaults']);
		unset($data['referring_affiliate']);
				
		if (!empty($data['date']))
		{
			$data['date'] = _save_date($data['date']);
		}
		
		if (!empty($data['date_paid']))
		{
			$data['date_paid'] = _save_date($data['date_paid']);
		}
		
		if (!empty($data['recur']))
		{
			$data['recur'] = _save_date($data['recur']);
		}
				

		//get referring affiliate details
		$member = $this->db_validation_model->_get_details('members', '*', 'username', $referring_affiliate);
		$data['member_id'] = $member[0]['member_id'];

		//update commission data
		$this->db->where('comm_id', $id);

		if (!$this->db->update('commissions', $data))
		{
			show_error($this->lang->line('could_not_update_commission'));
			
			//log error
			log_message('error', 'Could not update commission ID ' . $id . 'in commissions table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'commission ID '. $id . ' updated in commissions table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_user_commissions($id = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $num_options = '', $where_value = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_afc_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_afc_column');
		
		$sql = 'SELECT ' . $this->db->dbprefix('commissions') . '.*, ' . $this->db->dbprefix('programs') . '.program_name
				FROM ' . $this->db->dbprefix('commissions') . '
				LEFT JOIN ' . $this->db->dbprefix('programs') . '  
				ON ' . $this->db->dbprefix('commissions') . '.program_id = ' . $this->db->dbprefix('programs') . '.program_id 
				WHERE member_id = \'' . $id . '\'';
			
		if (!empty($where_value) && $where_value == 'unpaid')
		{	
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == 1)
			{
				$sql .= ' AND comm_status != \'paid\' ';	
			}
			else
			{
				$sql .= ' AND comm_status = \'unpaid\' ';
			}
		}
		elseif (!empty($where_value) && $where_value == 'paid')
		{
			$sql .= ' AND comm_status = \'paid\' ';	
		}
		else
		{
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == 0)
			{
				$sql .= ' AND comm_status != \'pending\' ';	
			}
		}		
		
			
		$sql .= ' 
				 ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
		
			$a['commissions'] = array();
			
			foreach ($query->result_array() as $row)
			{
				$row['s_commission_amount'] = format_amounts($row['commission_amount'], $num_options);
				$row['s_sale_amount'] = format_amounts($row['sale_amount'], $num_options);
				$row['s_date'] = _show_date($row['date']);
				$row['s_date_paid'] = _show_date($row['date_paid']);
				$row['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				$row['s_referrer'] = limit_chars($row['referrer'], 50);
				$row['s_commission_notes'] = nl2br($row['commission_notes']);
				$row['s_approved'] = $row['approved'] == '1' ? 'tick' : 'warning1';
				
				
				
				array_push($a['commissions'], $row);
			}
			
			//get total rows
			$this->db->where('member_id', $id);
			
			if (!empty($where_value) && $where_value == 'unpaid')
			{				
				if ($this->config->item('sts_affiliate_show_pending_comms_members') == 1)
				{
					$this->db->where('comm_status !=', 'paid');
				}
				else
				{
					$this->db->where('comm_status', 'unpaid');
				}
			}
			elseif (!empty($where_value) && $where_value == 'paid')
			{
				$this->db->where('comm_status', 'paid');
			}
			else
			{
				if ($this->config->item('sts_affiliate_show_pending_comms_members') == 0)
				{
					$this->db->where('comm_status !=', 'pending');
				}
			}
		
			$this->db->from('commissions');
			
			$a['total_rows'] = $this->db->count_all_results();
			
			return $a;
		}
		
		return false;
		
	} 
	
	// ------------------------------------------------------------------------	
	
	function _check_scaled_commission($amount = '', $level = '')
	{
		$this->db->where('min_amount <', $amount);
		$this->db->where('max_amount >', $amount);
		$this->db->where('status', '1');
		$this->db->where('level', $level);
		$query = $this->db->get('scaled_commissions');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();	
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_commissions($sdata = '', $aff_data = '', $admin_users = '', $num_options = '', $members = '')
	{
		if (!empty($members))
		{
			/*
			| ----------------------------------------------------
			| Give out commissions for all ACTIVE upline members
			| ----------------------------------------------------
			*/
		
			//start the member loop of upline
			foreach ($members as $k => $v) //loop through each member of the upline
			{ 
				if (!empty($sdata['self_restrict']) && $this->config->item('sts_affiliate_restrict_self_commission') == 1)
				{
					if (strtolower(trim($sdata['self_restrict'])) == $v['primary_email']) continue;
				}
				
				if ($v['status'] == 1) //only add a commission if the member has an active status
				{
					//set commission defaults
					$commission_level = $k; //commission level
					
					
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
					
					//set the commission notes
					$comm_notes = '';
					
					/*
					| -------------------------------------------------------------
					| Finally, let's add a commission for TOTAL SALE ONLY
					| -------------------------------------------------------------
					*/
					
					//calculate the commission amount
					if ($commission_level > 1)
					{
						$sale_amount = '0.00';
					}
					else
					{
						$sale_amount = $sdata['amount'];
					}
					
					
					//check for action commissions
					
					//check for scaled commissions
					
					
					$comm_amount = $this->_calculate_commission($v['commission_type'], $commission_level, $v['commission_level_' . $k], $sale_amount); 
					
					
				
					if ($comm_amount > 0 )
					{
						//set all the required data
						$insert = array(	'member_id' 		=> 			$v['member_id'],			
											'invoice_id'		=>			$sdata['invoice_id'],
											'comm_status'		=>			$comm_status,
											'approved'			=>			'0',
											'date'				=>			$sdata['payment_date'],
											'commission_amount'	=>			$comm_amount,
											'sale_amount'		=>			$sale_amount,
											'commission_level'	=>			$commission_level,
											'referrer'			=>			'',
											'trans_id'			=>			$sdata['transaction_id'],
											'ip_address'		=>			$this->input->ip_address(),
											'date_paid'			=>			'',
											'commission_notes'	=>			$comm_notes,
											'performance_paid'	=>			'0',
											'email_sent'		=>			'0',
										);	
					
						//add the commission
						$this->db->insert('commissions', $insert);
					
					
						/*
						| -------------------------------------------------------------
						| Send out an alert email to the user 
						| -------------------------------------------------------------
						*/
		
						//send out alert email if set
						if ($this->config->item('sts_affiliate_new_commission') == 'alert_pending' || $this->config->item('sts_affiliate_new_commission') == 'alert_unpaid')
						{
							if ($v['alert_new_commission'] != '0') //send out the email only if the user wants it
							{
								$v['commission_amount'] = format_amounts($comm_amount, $num_options);
								$this->emailing_model->_send_template_email('member', $v, 'member_affiliate_commission_generated_template', true);
							}
						}
						
						//send out admin alerts for new commission
						foreach ($admin_users as $admin_user)
						{
							if ($admin_user['alert_affiliate_commission'] == 1) //send out the admin alert
							{
								$admin_user['member_username'] = $v['username'];
								$admin_user['commission_amount'] = format_amounts($comm_amount, $num_options);
								$this->emailing_model->_send_template_email('admin', $admin_user, 'admin_affiliate_commission_generated_template', true);
							}
						}
					}

					/*
					| -------------------------------------------------------------
					| Let's see if the user gets any performance bonuses
					| -------------------------------------------------------------
					*/
					
					//check performance bonuses
					if ($this->config->item('sts_affiliate_enable_performance_bonus') == 1)
					{
						//generate bonuses if any
						$unpaid_bonus = $this->_get_commissions_for_bonus($this->config->item('sts_affiliate_performance_bonus_required'), $v['member_id']);
						
						//calculate whether the user has made enough commissions
						if ($unpaid_bonus >= $this->config->item('sts_affiliate_performance_bonus_required_amount'))
						{
							/*
							| -------------------------------------------------------------
							| Should we give them a bonus amount or upgrade the group?
							| -------------------------------------------------------------
							*/
						
							//check if performance is group upgrade or an amount
							if ($this->config->item('sts_affiliate_performance_bonus_type') == 'payment_amount')
							{
								//insert a commission amount
								
								$comm_notes = $this->lang->line('performance_bonus_earned') . "\n";
								$v['bonus_amount'] = format_amounts($this->config->item('sts_affiliate_performance_bonus_amount'), $num_options);
								
								//set all the required data
								$new_bonus = array(	'member_id' 		=> 			$v['member_id'],			
													'invoice_id'		=>			'0',
													'comm_status'		=>			$comm_status,
													'approved'			=>			'0',
													'date'				=>			$sdata['payment_date'],
													'commission_amount'	=>			$this->config->item('sts_affiliate_performance_bonus_amount'),
													'sale_amount'		=>			'0',
													'commission_level'	=>			'1',
													'referrer'			=>			'',
													'trans_id'			=>			$this->lang->line('trans_id_performance_bonus') . ' - ' . date('M d Y'),
													'ip_address'		=>			$this->input->ip_address(),
													'date_paid'			=>			'',
													'commission_notes'	=>			$comm_notes,
													'performance_paid'	=>			'1',
													'email_sent'		=>			'1',
												);	
							
								//add the commission
								$this->_add_commission($new_bonus);
								
								if ($this->config->item('sts_affiliate_new_commission') == 'alert_pending' || $this->config->item('sts_affiliate_new_commission') == 'alert_unpaid')
								{
									//send out alerts
									if ($v['alert_new_commission'] != '0') //only send out the email if the user allows it
									{
										$this->emailing_model->_send_template_email('member', $v, 'member_affiliate_performance_bonus_amount_template', true);
									}
								}
								
								//send out admin alerts for new commission
								foreach ($admin_users as $admin_user)
								{
									if ($admin_user['alert_affiliate_commission'] == 1) //send out the admin alert
									{
										$admin_user['member_username'] = $v['username'];
										$admin_user['commission_amount'] = $v['bonus_amount'];
										$this->emailing_model->_send_template_email('admin', $admin_user, 'admin_affiliate_commission_generated_template', true);
									}
								}
							}
							else //upgrade to group
							{
								$group_upgrade = $this->_upgrade_bonus_group($v['member_id']);
								
								if (!empty($group_upgrade))
								{
									$v['upgraded_affiliate_group'] = $group_upgrade['aff_group_name'];
									
									if ($this->config->item('sts_affiliate_new_commission') == 'alert_pending' || $this->config->item('sts_affiliate_new_commission') == 'alert_unpaid')
									{	
										//send out alerts
										if ($v['alert_new_commission'] != '0')
										{
											$this->emailing_model->_send_template_email('member', $v, 'member_affiliate_performance_group_upgrade_template', true);
										}
									}
								}
							}
							
							/*
							| -------------------------------------------------------------
							| Let's mark all the commissions paid for bonuses now
							| -------------------------------------------------------------
							*/
							
							//now mark all commissions to performance paid
							$this->_update_commissions_for_bonus($v['member_id']);	
						}
					}
				}
			}	
		}
	}
}
?>