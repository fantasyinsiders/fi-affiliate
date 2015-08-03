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
| FILENAME - module_affiliate_payment_print_checks_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for printing affiliate payment checks
|
*/

class Module_Affiliate_Payment_Print_Checks_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$config = array(
							'settings_key'	=>	'module_affiliate_payment_print_checks_use_date_range',
							'settings_value'	=>	'0',
							'settings_module'	=>	'affiliate_payment',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'yes_no',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_affiliate_payment_print_checks_start_date',
							'settings_value'	=>	'',
							'settings_module'	=>	'affiliate_payment',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'2',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_affiliate_payment_print_checks_end_date',
							'settings_value'	=>	'',
							'settings_module'	=>	'affiliate_payment',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'3',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_affiliate_payment_print_checks_exclude_minimum',
							'settings_value'	=>	'0',
							'settings_module'	=>	'affiliate_payment',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'4',
							'settings_function'	=>	'yes_no',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_affiliate_payment_print_checks_total_rows',
							'settings_value'	=>	'100',
							'settings_module'	=>	'affiliate_payment',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'5',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_affiliate_payment_print_checks_payment_details',
							'settings_value'	=>	'affiliate payment',
							'settings_module'	=>	'affiliate_payment',
							'settings_type'	=>	'textarea',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'6',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_payments($data = '')
	{	
		$invoices = $this->load->view('modules/tpl_adm_print_affiliate_payment_checks_header', '', true);
		
		
		foreach($data as $member)
		{
			$member['current_date'] = _generate_timestamp();
			
			$invoices .= $this->load->view('modules/tpl_adm_print_affiliate_payment_checks', $member, true);
				
		}
		
		$invoices .= '</body></html>';
		
		echo $invoices;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_affiliate_payments($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $start_date = '', $end_date = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_amp_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_amp_column');
		
		//first get all users
		if (!empty($start_date) && !empty($end_date))
		{
			$sql = 'SELECT DISTINCT member_id as id,
					(SELECT CONCAT(fname,\' \',lname, \'|\',payment_name,\'|\',payment_preference_amount) as name FROM ' . $this->db->dbprefix('members') . ' 
					WHERE member_id = id) as payment_name,
					(SELECT SUM(commission_amount) FROM ' . $this->db->dbprefix('commissions') . ' 
					WHERE member_id = id AND approved = \'1\' AND comm_status = \'unpaid\'
					AND (date > \'' . $start_date . '\' AND date < \'' . $end_date . '\')) as total
					FROM ' . $this->db->dbprefix('commissions') . '
					WHERE approved = \'1\' AND comm_status = \'unpaid\'
					AND (date > \'' . $start_date . '\' AND date < \'' . $end_date . '\')
					ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0)
			{
				$data['payments'] = $query->result_array();
			
				//get the total rows
				$sql = 'SELECT DISTINCT member_id as id,
					(SELECT SUM(commission_amount) FROM ' . $this->db->dbprefix('commissions') . ' 
					WHERE member_id = id AND approved = \'1\' AND comm_status = \'unpaid\') as total
					FROM ' . $this->db->dbprefix('commissions') . '
					WHERE approved = \'1\' AND comm_status = \'unpaid\'';
					
				//$query2 = $this->db->query($sql);
				
				//$data['total_rows'] = $query2->num_rows();
				
				return $data;
			}
		}
		else
		{
			$sql = 'SELECT DISTINCT member_id as id, 
					(SELECT CONCAT(fname,\' \',lname, \'|\',payment_name,\'|\',payment_preference_amount) as name FROM ' . $this->db->dbprefix('members') . ' 
					WHERE member_id = id) as payment_name,
					(SELECT SUM(commission_amount) FROM ' . $this->db->dbprefix('commissions') . ' 
					WHERE member_id = id AND approved = \'1\' AND comm_status = \'unpaid\') as total
					FROM ' . $this->db->dbprefix('commissions') . '
					WHERE approved = \'1\' AND comm_status = \'unpaid\'
					ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0)
			{
				$data['payments'] = $query->result_array();
			
				//get the total rows
				$sql = 'SELECT DISTINCT member_id as id,
					(SELECT SUM(commission_amount) FROM ' . $this->db->dbprefix('commissions') . ' 
					WHERE member_id = id AND approved = \'1\' AND comm_status = \'unpaid\') as total
					FROM ' . $this->db->dbprefix('commissions') . '
					WHERE approved = \'1\' AND comm_status = \'unpaid\'';

					
				//$query2 = $this->db->query($sql);
				
				//$data['total_rows'] = $query2->num_rows();
				
				return $data;
			}
		}
		
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	
}
?>