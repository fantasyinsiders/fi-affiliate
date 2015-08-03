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
| FILENAME - tracking_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing tracking
|
*/

class Tracking_Model extends CI_Model {	
	
	function _add_tracking($mid = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		if (!empty($mid))
		{
			$data['member_id'] = $mid;	
		}
		
		//insert into db
		if (!$this->db->insert('tracking', $data))
		{
			show_error($this->lang->line('could_not_add_tracking'));
			
			//log error
			log_message('error', 'Could not insert tracking into tracking table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			//log success
			log_message('info', 'tracking '. $id . ' inserted into tracking table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_referrer($url = '')
	{
		$this->db->where('referrer', $url);
		$query = $this->db->get('traffic', '1');
		
		if ($query->num_rows() > 0)
		{
			return true;	
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _prune_ad_trackers($days = '')
	{
		$time = _generate_timestamp() - (3600 * 24 * $days);
		
		$this->db->where('date <', $time);
		$this->db->delete('traffic');
		
		return 'ad trackers pruned';
	}
	
	// ------------------------------------------------------------------------
	
	function _prune_impressions($days = '')
	{
		$time = _generate_timestamp() - (3600 * 24 * $days);
		
		$this->db->where('date <', $time);
		$this->db->where('performance_paid', '1');
		$this->db->delete('impressions');
		
		return 'impressions pruned';
	}
	
	// ------------------------------------------------------------------------

	function _reset_tracking($id = '')
	{
		//update invoices
		$this->db->where('tracking_id', $id);
		if ($this->db->update('commissions', array('tracking_id' => '')))
		{
			//delete tracking members
			$this->db->where('tracker', $id);
			if ($this->db->update('traffic', array('tracker' => '')))
			{
				//log success
				log_message('info', 'tracking urls deleted successfully');
			
			}
		}
		else
		{
			show_error($this->lang->line('could_not_delete_tracking'));
			
			//log error
			log_message('error', 'tracking ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}
		
	// ------------------------------------------------------------------------
	
	function _delete_tracking_referral($id = '')
	{
		//delete mailing list members
		$this->db->where('id', $id);
		if ($this->db->delete('traffic'))
		{
			
			//log success
			log_message('info', 'tracking urls deleted successfully');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_tracking($id = '', $mid = '')
	{
		
		//delete mailing list members
		$this->db->where('tracker', $id);
		
		if ($this->db->update('traffic', array('tracker' => '')))
		{
			
			//log success
			log_message('info', 'tracking urls deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_tracking'));
			
			//log error
			log_message('error', 'tracking ID #' . $id . ' could not be deleted');
		}
		

		
		
		//delete tracking
		$this->db->where('id', $id);
		
		if (!empty($mid))
		{
			$this->db->where('member_id', $mid);	
		}
		
		if ($this->db->delete('tracking'))
		{
			
			//log success
			log_message('info', 'tracking ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_tracking'));
			
			//log error
			log_message('error', 'tracking ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	

	
	// ------------------------------------------------------------------------
	
	function _get_tracking_stats($id= '', $type = '', $cmonth = '', $cyear = '')
	{
		
		
		if ($type == 'clicks')
		{
			$sql = 'SELECT COUNT(*) as total
					  FROM ' . $this->db->dbprefix('traffic') . ' 
					  WHERE tracker = \'' . $id . '\'';
		}
		else
		{
			$sql = 'SELECT SUM(commission_amount) as total
					  FROM ' . $this->db->dbprefix('commissions') . ' 
					  WHERE tracking_id = \'' . $id . '\'';
		
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == 0)
			{
				$sql .= ' AND comm_status != \'pending\'';	
			}
		}
		
		if (!empty($cmonth)  && !empty($cyear))
		{
			$start = mktime(0, 0, 0, $cmonth  , 1, $cyear);
			$end = mktime(23, 59, 59, $cmonth  , date('t'), $cyear);
			
			$sql .= 'AND date >= ' . $start . ' 
					  AND date <= ' . $end;
	
		}
		
		$totals = $this->db->query($sql);
		$a = $totals->row_array();
		
		return $a['total'];
	}
	
	// ------------------------------------------------------------------------

	function _get_tracking_details($id = '')
	{
		//get the data from tracking table
		$this->db->where('id', $id);
		$query = $this->db->get('tracking');
		
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

	function _get_user_tracking_details($id = '', $mid = '')
	{
		//get the data from tracking table
		$this->db->where('id', $id);
		$this->db->where('member_id', $mid);
		$query = $this->db->get('tracking');
		
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
	
	function _get_all_tracking()
	{
		$query =$this->db->get('tracking');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_tracking($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_trc_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_trc_column');

		$sql = 'SELECT ' . $this->db->dbprefix('tracking') . '.*,
				(SELECT COUNT(*) FROM ' . $this->db->dbprefix('traffic') . '
				WHERE ' . $this->db->dbprefix('tracking') . '.id = ' . $this->db->dbprefix('traffic') . '.tracker) as clicks,
				(SELECT SUM(commission_amount)
				FROM ' . $this->db->dbprefix('commissions') . '
				WHERE ' . $this->db->dbprefix('commissions') . '.tracking_id = ' .  $this->db->dbprefix('tracking') . '.id) as comms,
				(SELECT SUM(sale_amount)
				FROM ' . $this->db->dbprefix('commissions') . '
				WHERE ' . $this->db->dbprefix('commissions') . '.tracking_id = ' .  $this->db->dbprefix('tracking') . '.id) as sales,
				(SELECT COUNT(comm_id)
				FROM ' . $this->db->dbprefix('commissions') . '
				WHERE ' . $this->db->dbprefix('commissions') . '.tracking_id = ' .  $this->db->dbprefix('tracking') . '.id) as num_sales,
				(SELECT SUM(commission_amount)
				FROM ' . $this->db->dbprefix('commissions') . '
				WHERE ' . $this->db->dbprefix('commissions') . '.tracking_id = ' .  $this->db->dbprefix('tracking') . '.id AND action_commission_id != \'0\') as action_comms,
				(SELECT date
				 FROM ' . $this->db->dbprefix('traffic') . '
				WHERE ' . $this->db->dbprefix('traffic') . '.tracker = ' .  $this->db->dbprefix('tracking') . '.id ORDER BY date ASC LIMIT 1) as f_date,
				(SELECT date
				 FROM ' . $this->db->dbprefix('traffic') . '
				WHERE ' . $this->db->dbprefix('traffic') . '.tracker = ' .  $this->db->dbprefix('tracking') . '.id ORDER BY date DESC LIMIT 1) as l_date
				
				FROM ' . $this->db->dbprefix('tracking') . '
				ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		//echo '<pre>'; print_r($query->result_array()); exit();
		if ($query->num_rows() > 0)
		{
			$data = array();
			foreach ($query->result_array() as $row)
			{
				$row['total_days'] = ceil(($row['l_date'] - $row['f_date']) / (3600 *24));
				
				$row['cpc'] = 0;
				$row['cpa'] = 0;
				$row['cps'] = 0;
				$row['total_cost'] = 0;
				$row['net'] = 0;
				$row['roi'] = 0;
				
				//calculate the total cost
				if (!empty($row['cost']))
				{
					switch ($row['cost_type'])
					{
						case 'ppc':
						
							$row['cpc'] = $row['clicks'] * $row['cost'];
							$row['cpa'] = !empty($row['action_comms']) ? $row['clicks'] / $row['action_comms'] : '0';
							$row['total_cost'] = $row['clicks'] * $row['cost'];
							$row['cps'] = !empty($row['num_sales']) ?  $row['total_cost'] / $row['num_sales'] : '0';
							
							$row['net'] = $row['sales'] - $row['total_cost'];
							$row['roi'] = !empty($row['total_cost']) ? ($row['net'] / $row['total_cost']) * 100 : '0';
						
						break;
						
						case 'one_time':
							
							$row['cpc'] = !empty($row['clicks']) ? $row['clicks'] / $row['cost'] : '0'; 
							$row['cpa'] = !empty($row['action_comms']) ? $row['clicks'] / $row['action_comms'] : '0';
							$row['total_cost'] = $row['cost'];
							$row['cps'] = !empty($row['num_sales']) ?  $row['total_cost'] / $row['num_sales'] : '0';
							
							$row['net'] = $row['sales'] - $row['total_cost'];
							$row['roi'] = !empty($row['net']) ? ($row['net'] / $row['total_cost']) * 100 : '0';
							
						break;
						
						case 'recur':
							
							$row['recur_frequency'] =  ceil($row['total_days'] / $row['recur']);
							
							$row['cpc'] = $row['clicks'] / $row['cost'];
							$row['cpa'] = !empty($row['action_comms']) ? $row['clicks'] / $row['action_comms'] : '0';
							$row['total_cost'] = $row['cost'] * $row['recur_frequency'];
							$row['cps'] = !empty($row['num_sales']) ?  $row['total_cost'] / $row['num_sales'] : '0';
							
							$row['net'] = $row['sales'] - $row['total_cost'];
							$row['roi'] = !empty($row['total_cost']) ? ($row['net'] / $row['total_cost']) * 100 : '0';
							
						break;
					}
				}
				array_push($data, $row);
			}
			
			return $data;
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_tracking_referrals($id = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_trr_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_trr_column');

		$sql = 'SELECT ' . $this->db->dbprefix('traffic') . '.*
				FROM ' . $this->db->dbprefix('traffic') . '
				WHERE tracker = \'' . $id . '\'
				ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			$data['tracking'] = $query->result_array();
		
			//get totals
			$sql = 'SELECT COUNT(*) as total
				FROM ' . $this->db->dbprefix('traffic') . '
				WHERE tracker = ' . $id;
		
			$query = $this->db->query($sql);
			
			$t = $query->row_array();
			
			$data['totals'] = $t['total'];
			
			return $data;
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_impression($data = array(), $unique = '1')
	{
		if ($unique == '1')
		{
			$this->db->where('ip_address', $data['ip_address']);
			$query = $this->db->get('impressions');
			
			if ($query->num_rows() > 0)
			{
				return true;
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_impressions($data)
	{
		$this->db->where('member_id', $data['member_id']);
		$this->db->where('program_id', $data['program_id']);
		$this->db->where('tool_id', $data['tool_id']);
		$this->db->where('performance_paid', '0');
		$this->db->limit('1000');
		
		$this->db->update('impressions', array('performance_paid' => '1'));
	}
	
	// ------------------------------------------------------------------------
	
	function _insert_impression($data = array())
	{
		if ($this->db->insert('impressions', $data))
		{
			return $this->_count_impressions($data);
		}
		
		return false;			
	}
	
	// ------------------------------------------------------------------------
	
	function _count_impressions($data = array())
	{
		//get count for impressions
		$this->db->where('member_id', $data['member_id']);
		$this->db->where('tool_id', $data['tool_id']);
		$this->db->where('tool_type', $data['tool_type']);
		$this->db->where('performance_paid', '0');
		$this->db->from('impressions');	
		
		return $this->db->count_all_results();
	}
	
	// ------------------------------------------------------------------------
	
	function _get_user_trackers($mid = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $num_options = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('mem_dbs_trk_order');
		if (!$sort_column) $sort_column = $this->config->item('mem_dbs_trk_column');
		
		$sql = 'SELECT *, (SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
				WHERE ' . $this->db->dbprefix('tracking') . '.id = ' . $this->db->dbprefix('traffic') . '.tracker) 
				as clicks,
				(SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
				WHERE ' . $this->db->dbprefix('tracking') . '.id = ' . $this->db->dbprefix('commissions') . '.tracking_id AND member_id = \'' . $mid . '\'';
				
		if ($this->config->item('sts_affiliate_show_pending_comms_members') == 0)
		{
			$sql .= ' AND comm_status != \'pending\'';	
		}
		
		$sql .= ') as comms,
				
				(SELECT SUM(sale_amount)
				FROM ' . $this->db->dbprefix('commissions') . '
				WHERE ' . $this->db->dbprefix('commissions') . '.tracking_id = ' .  $this->db->dbprefix('tracking') . '.id AND member_id = \'' . $mid . '\') as sales,
				(SELECT COUNT(comm_id)
				FROM ' . $this->db->dbprefix('commissions') . '
				WHERE ' . $this->db->dbprefix('commissions') . '.tracking_id = ' .  $this->db->dbprefix('tracking') . '.id AND member_id = \'' . $mid . '\') as num_sales,
				(SELECT SUM(commission_amount)
				FROM ' . $this->db->dbprefix('commissions') . '
				WHERE ' . $this->db->dbprefix('commissions') . '.tracking_id = ' .  $this->db->dbprefix('tracking') . '.id AND member_id = \'' . $mid . '\' AND action_commission_id != \'0\'';
		
		if ($this->config->item('sts_affiliate_show_pending_comms_members') == 0)
		{
			$sql .= ' AND comm_status != \'pending\'';	
		}
				
		$sql .= ') as action_comms,
				(SELECT date
				 FROM ' . $this->db->dbprefix('traffic') . '
				WHERE ' . $this->db->dbprefix('traffic') . '.tracker = ' .  $this->db->dbprefix('tracking') . '.id ORDER BY date ASC LIMIT 1) as f_date,
				(SELECT date
				 FROM ' . $this->db->dbprefix('traffic') . '
				WHERE ' . $this->db->dbprefix('traffic') . '.tracker = ' .  $this->db->dbprefix('tracking') . '.id ORDER BY date DESC LIMIT 1) as l_date
				
				FROM ' . $this->db->dbprefix('tracking') . '
				WHERE member_id = \'' . $mid . '\'
				ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{			
			$sdata['tracking'] = array();
			
			foreach ($query->result_array() as $row)
			{				
				$row['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				$row['s_comms'] = format_amounts($row['comms'], $num_options);
				
				$row['total_days'] = ceil(($row['l_date'] - $row['f_date']) / (3600 *24));
				
				$row['cpc'] = 0;
				$row['cpa'] = 0;
				$row['cps'] = 0;
				$row['total_cost'] = 0;
				$row['net'] = 0;
				$row['roi'] = 0;
				
				//calculate the total cost
				if (!empty($row['cost']))
				{
					switch ($row['cost_type'])
					{
						case 'ppc':
						
							$row['cpc'] = $row['cost'];
							$row['cpa'] = !empty($row['action_comms']) ? $row['clicks'] / $row['action_comms'] : '0';
							$row['total_cost'] = $row['clicks'] * $row['cost'];
							$row['cps'] = !empty($row['num_sales']) ?  $row['total_cost'] / $row['num_sales'] : '0';
							
							$row['net'] = $row['comms'] - $row['total_cost'];
							$row['roi'] = !empty($row['total_cost']) ? ($row['net'] / $row['total_cost']) * 100 : '0';
						
						break;
						
						case 'one_time':
							
							$row['cpc'] = $row['clicks'] / $row['cost'];
							$row['cpa'] = !empty($row['action_comms']) ? $row['clicks'] / $row['action_comms'] : '0';
							$row['total_cost'] = $row['cost'];
							$row['cps'] = !empty($row['num_sales']) ?  $row['total_cost'] / $row['num_sales'] : '0';
							
							$row['net'] = $row['comms'] - $row['total_cost'];
							$row['roi'] = !empty($row['total_cost']) ? ($row['net'] / $row['total_cost']) * 100 : '0';
							
						break;
						
						case 'recur':
							
							$row['recur_frequency'] =  ceil($row['total_days'] / $row['recur']);
					
							$row['cpa'] = !empty($row['action_comms']) ? $row['clicks'] / $row['action_comms'] : '0';
							$row['total_cost'] = $row['cost'] * $row['recur_frequency'];
							$row['cps'] = !empty($row['num_sales']) ?  $row['total_cost'] / $row['num_sales'] : '0';
							$row['cpc'] = !empty($row['clicks']) ? $row['total_cost'] / $row['clicks'] : '0';
							
							$row['net'] = $row['comms'] - $row['total_cost'];
							$row['roi'] = !empty($row['total_cost']) ? ($row['net'] / $row['total_cost']) * 100 : '0';
							
						break;
					}
				}
				
				array_push($sdata['tracking'], $row);
			}
			
			$this->db->where('member_id', $mid);
			$this->db->from('tracking');
			$sdata['total_rows'] = $this->db->count_all_results();
		
			return $sdata;
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	function _update_tracking($id = '', $mid = '')
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//update tracking data
		$this->db->where('id', $id);
		
		if (!empty($mid))
		{
			$this->db->where('member_id', $mid);	
		}
		
		if (!$this->db->update('tracking', $data))
		{
			show_error($this->lang->line('could_not_update_tracking'));
			
			//log error
			log_message('error', 'Could not update tracking ID ' . $id . 'in tracking table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'tracking ID '.$id . ' updated in tracking table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
}
?>