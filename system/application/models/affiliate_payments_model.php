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
| and affiliate_payments from any liability that might arise from its use.                                                        
|                                                                           
| Selling the code for this program without prior written consent is       
| expressly forbidden and in violation of Domestic and International 
| copyright laws.  
|	
| -------------------------------------------------------------------------
| FILENAME - affiliate_payments_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing affiliate_payments
|
*/

class Affiliate_Payments_Model extends CI_Model {	
	
	function _add_payment($data = '')
	{
		if ($this->db->insert('affiliate_payments', $data))
		{
			return $this->db->insert_id();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _mark_commissions_paid($id = '', $amount = '', $note = '')
	{
		
		//add a new affiliate payment in db
		$sdata = array(	'member_id' => $id,
						'payment_date' => _generate_timestamp(),
						'payment_amount' => $amount,
						'payment_details' => $note,
					  );
		
		$sid = $this->_add_payment($sdata);
		
		if ($sid)
		{
			$this->db->where('member_id', $id);
			$this->db->where('comm_status', 'unpaid');
			$this->db->where('approved', '1');
			
			$sdata = array('comm_status' => 'paid',
						   'date_paid' =>  _generate_timestamp(),
						   'payment_id' => $sid
						   );
			
			$this->db->update('commissions', $sdata);	
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_payment_options()
	{
		$this->db->where('module_type', 'affiliate_payment');
		$this->db->where('module_status', '1');
		$query = $this->db->get('modules');
		
		if ($query->num_rows() > 0)
		{
			$gateways = $query->result_array();
			
			$options = format_array($gateways, 'module_file_name', 'module_name');
			
			//add mark as paid option
			$options['jrox_mark_as_paid'] = $this->lang->line('mark_commissions_as_paid');
			
			return $options;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	 
	function _update_payment($id = '')
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//set up dates
		$fdate = explode(':', $this->config->item('sts_admin_date_format'));
		switch ($fdate[0])
		{
			case 'mm/dd/yyyy':
					
				if (!empty($data['payment_date']))
				{
					//format date
					$pub = explode('/', $data['payment_date']);
					$data['payment_date'] = mktime(date('H'),date('i'),date('s'), $pub[0], $pub[1], $pub[2]);
				}
				else
				{
					$data['payment_date'] = _generate_timestamp();
				}

			break;
		}
		
		//get the member id
		$member = $this->db_validation_model->_get_details('members', 'member_id', 'username', $data['username']);
		$member_id = $member[0]['member_id'];
		unset($data['username']);
		
		$this->db->where('id', $id);
		
		if (!$this->db->update('affiliate_payments', $data))
		{
			show_error($this->lang->line('could_not_update_affiliate_payment'));
			
			//log error
			log_message('error', 'Could not update affiliate_payment payment ID ' . $id . 'in affiliate_payments table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'affiliate_payment payment ID '. $id . ' updated in affiliate_payments table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_payment($id = '')
	{
	
		//delete payment
		$this->db->where('id', $id);
		if ($this->db->delete('affiliate_payments'))
		{
			
			//log success
			log_message('info', 'affiliate_payment payment #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_affiliate_payment'));
			
			//log error
			log_message('error', 'affiliate_payment payment ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------
	
	function _check_product_id($id = '', $type = '')
	{
		$this->db->where('product_id', $id);
		
		if ($type == 'membership')
		{
			$this->db->where('product_type', $type);
		}
		
		$query = $this->db->get('products');
		
		if ($query->num_rows() > 0)
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------

	function _get_affiliate_payment_details($id = '')
	{
		$sql = 'SELECT ' . $this->db->dbprefix('affiliate_payments') . '.*,
				(SELECT username FROM ' . $this->db->dbprefix('members') . ' 
				WHERE ' . $this->db->dbprefix('members') . '.member_id = ' . $this->db->dbprefix('affiliate_payments') . '.member_id) AS username
				FROM ' . $this->db->dbprefix('affiliate_payments') . '
				WHERE id = \'' . $id .'\'';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_member_affiliate_payments($mid = '', $sort_column = 'date', $sort_order = 'DESC')
	{
		
		$sql = 'SELECT * 
				FROM ' . $this->db->dbprefix('commissions') . ' 
				LEFT JOIN ' . $this->db->dbprefix('members') . '  
				ON ' . $this->db->dbprefix('members') . '.member_id = ' . $this->db->dbprefix('commissions') . '.member_id 
				WHERE ' . $this->db->dbprefix('commissions') . '.member_id = \'' . $mid . '\' AND ' . $this->db->dbprefix('commissions') . '.approved = \'1\' 
				AND ' . $this->db->dbprefix('commissions') . '.comm_status = \'unpaid\'
				ORDER BY ' . $sort_column . ' ' . $sort_order;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{	
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_affiliate_payment_options($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		if (!$sort_order) $sort_order = $this->config->item('dbs_afm_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_afm_column');
		
		$this->db->order_by($sort_column, $sort_order); 	
		$this->db->where('module_type', 'affiliate_payment');
		$this->db->where('module_status', '1');
		
		$query = $this->db->get('modules', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_affiliate_payments($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $where_column = '', $where_value = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_amp_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_amp_column');
		
		//first get all users
		
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
				
			$query2 = $this->db->query($sql);
			
			$data['total_rows'] = $query2->num_rows();
			
			return $data;
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_payment_history($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $where_column = '', $where_value = '')
	{
		if (!$sort_order) $sort_order = $this->config->item('dbs_afp_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_afp_column');
		
		$sql = 'SELECT ' . $this->db->dbprefix('affiliate_payments') .'.*, member_id as mid,
				(SELECT CONCAT(fname,\' \',lname) FROM ' . $this->db->dbprefix('members') . ' WHERE member_id = mid) as name
				FROM ' . $this->db->dbprefix('affiliate_payments');
		
		if (!empty($where_column) AND !empty($where_value))
		{
			$sql .= ' WHERE ' . $where_column . ' = \'' . $where_value . '\'';
		}
		
		$sql .= ' ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_user_payments($id = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_afp_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_afp_column');
		
		$sql = 'SELECT * FROM ' . $this->db->dbprefix('affiliate_payments') . '
						WHERE member_id = \'' . $id . '\'
						ORDER BY ' . $sort_column . ' ' . $sort_order . ' 
						LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
		
			$a['payments'] = array();
			
			foreach ($query->result_array() as $row)
			{
				$row['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				array_push($a['payments'], $row);
			}
			
			//get total rows
			$this->db->where('member_id', $id);
			$this->db->from('affiliate_payments');
			
			$a['total_rows'] = $this->db->count_all_results();
			
			return $a;
		}
		
		return false;
		
	}
	
	// ------------------------------------------------------------------------	
}
?>