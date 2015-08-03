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
| FILENAME - dashboard_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing the dashboard
|
*/

class Dashboard_Model extends CI_Model {	
	
	// ------------------------------------------------------------------------
	
	function _latest_members($mid = '')
	{		
	
		$sql = 'SELECT ' . $this->db->dbprefix('members') . '.*, ' . $this->db->dbprefix('members_photos') . '.*, 
				' . $this->db->dbprefix('members') . '.member_id as mid
				FROM  ' . $this->db->dbprefix('members') . ' 
				LEFT JOIN ' . $this->db->dbprefix('members_photos') . '  
				ON ' . $this->db->dbprefix('members') . '.member_id = ' . $this->db->dbprefix('members_photos') . '.member_id'; 
				
		if (!empty($mid))
		{
			$sql .= ' WHERE sponsor_id = \'' . $mid . '\'
					  ORDER BY signup_date DESC LIMIT 5';
		}		
		else
		{
			$sql .= ' ORDER BY signup_date DESC LIMIT 7';
		}
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _latest_commissions($mid = '')
	{
		$this->db->order_by('comm_id', 'DESC');
		$this->db->limit('7');
		$this->db->join('members','members.member_id=commissions.member_id', 'left');
		
		if (!empty($mid))
		{
			$this->db->where('members.member_id', $mid);
			
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == '0')
			{
				$this->db->where('comm_status !=', 'pending');
			}	
		}
		
		$query = $this->db->get('commissions');

		if ($query->num_rows() > 0)
		{
			
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _total_signups($m = '', $y = '', $mid = '')
	{
		$sql = 'SELECT COUNT(member_id) as amount
				  FROM ' . $this->db->dbprefix('members'); 
				  
		if (!empty($mid))
		{
			$sql = 'SELECT COUNT(member_id) as amount
				  FROM ' . $this->db->dbprefix('members') . '
				  WHERE sponsor_id = \'' . $mid . '\''; 
		}
		
				  
		if (!empty($m) && !empty($y))
		{
			$start = mktime(0, 0, 0, $m  , '1', $y);
			$end = mktime(23, 59, 59, $m  , date("t"), $y);
			
			if (!empty($mid))
			{
				$sql .= ' AND (signup_date > \'' . $start . '\' 
				  AND signup_date < \'' . $end . '\')';
			}
			else
			{
				$sql .= ' WHERE (signup_date > \'' . $start . '\' 
				  AND signup_date < \'' . $end . '\')';	
			}
		}
	
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			return $row['amount'];
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _latest_programs()
	{
		$this->db->limit($this->config->item('sts_content_members_dashboard_enable_latest_programs'));
		$this->db->where('hidden_program', '0');
		$this->db->order_by('program_id', 'DESC');
		$query = $this->db->get('programs');	
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _total_programs()
	{
		return $this->db->count_all('programs');
	}
	
	// ------------------------------------------------------------------------
	
	function _get_last_payment($mid = '')
	{
		$this->db->where('member_id', $mid);
		$this->db->order_by('id', 'DESC');	
		$this->db->limit(1);
		
		$query = $this->db->get('affiliate_payments');
			
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _total_user_referrals($mid = '', $m = '', $y = '')
	{
		$sql = 'SELECT COUNT(member_id) as amount
				  FROM ' . $this->db->dbprefix('members') . '
				  WHERE (sponsor_id = \'' . $mid . '\')' ;
		
		if (!empty($m) && !empty($y))
		{
			$start = mktime(0, 0, 0, $m  , '1', $y);
			$end = mktime(23, 59, 59, $m  , date("t"), $y);
		
			$sql .= ' AND (signup_date > \'' . $start . '\' 
				  AND signup_date < \'' . $end . '\')';
		}
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			return $row['amount'];
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _total_user_clicks($mid = '')
	{
		$sql = 'SELECT COUNT(traffic_id) as amount
				  FROM ' . $this->db->dbprefix('traffic') . '
				  WHERE member_id = \'' . $mid . '\'';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			return $row['amount'];
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _total_user_comms($mid = '')
	{
		$sql = 'SELECT SUM(commission_amount) as amount
				  FROM ' . $this->db->dbprefix('commissions') . '
				  WHERE member_id = \'' . $mid . '\'';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			return $row['amount'];
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _total_user_payments($mid = '')
	{
		$sql = 'SELECT SUM(payment_amount) as amount
				  FROM ' . $this->db->dbprefix('affiliate_payments') . '
				  WHERE member_id = \'' . $mid . '\'';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			return $row['amount'];
		}
		
		return false;
	}

	// ------------------------------------------------------------------------
	
	function _total_clicks($m = '', $y = '', $mid = '')
	{
		$sql = 'SELECT COUNT(*) as amount
				  FROM ' . $this->db->dbprefix('traffic'); 
				  
		if (!empty($m) && !empty($y))
		{
			$start = mktime(0, 0, 0, $m  , '1', $y);
			$end = mktime(23, 59, 59, $m  , date("t"), $y);
			
			
			$sql .= ' WHERE (date > \'' . $start . '\' 
				  AND date < \'' . $end . '\')';
		}
		
		if (!empty($mid))
		{
			$sql .= ' AND member_id = \'' . $mid . '\'';	
		}
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			return $row['amount'];
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _total_commissions($m = '', $y = '', $mid = '')
	{
		$sql = 'SELECT SUM(commission_amount) as amount
				  FROM ' . $this->db->dbprefix('commissions'); 
				  
		if (!empty($m) && !empty($y))
		{
			$start = mktime(0, 0, 0, $m  , '1', $y);
			$end = mktime(23, 59, 59, $m  , date("t"), $y);
		
			$sql .= ' WHERE date > \'' . $start . '\' 
				  AND date < \'' . $end . '\'';
		}
		
		if (!empty($mid))
		{
			$sql .= ' AND member_id = \'' . $mid . '\'';
			
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == '0')
			{
				$sql .= ' AND comm_status != \'pending\'';
			}	
		}
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			return $row['amount'];
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_jrox_rss()
	{
				
		if (!defined('JEM_ENABLE_RESELLER_LINKS'))
		{
			require_once APPPATH .'/libraries/magpie/rss_fetch.inc';
			$rss = @fetch_rss("http://www.jrox.com/rss/feed/random_content");
			
			$data['jrox_rss'] = array();
			
			if(count($rss->items)>0)
			{
				foreach ($rss->items as $rss)
				{
					//if (count($data['jrox_rss']) >= 3) break;
					$rss['description'] = word_limiter(strip_tags($rss['description']), 20);
					
					array_push($data['jrox_rss'], $rss);
					$i++;
				}
				
				return $data['jrox_rss'];
				/*
				$sdata['jrox_rss'] = array();
				for ($i = 0; $i<=2; $i++)
				{
					$j = rand('0', count($data['jrox_rss']));
					array_push($sdata['jrox_rss'], $data['jrox_rss'][$j]);
					unset($data['jrox_rss'][$j]);
				}
				*/
				
			}
		}
	
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_jrox_tweets()
	{
		if (!defined('JEM_ENABLE_RESELLER_LINKS'))
		{
			require_once APPPATH .'/libraries/magpie/rss_fetch.inc';
			$rss = fetch_rss("http://twitter.com/statuses/user_timeline/22250965.rss");
			
			$data['jrox_tweets'] = array();
			$i = 0;
			if(count($rss->items)>0)
			{
				foreach ($rss->items as $rss)
				{
					if (count($data['jrox_tweets']) >= 3) break;
					$rss['title'] = str_replace('jrox_com:', '', $rss['title']);
					$rss['description'] = word_limiter(strip_tags($rss['description']), 25);
					$rss['date'] = date('M d Y', $rss['date_timestamp']);
					array_push($data['jrox_tweets'], $rss);
					$i++;
				}
				
				return $data['jrox_tweets'];
				/*
				$sdata['jrox_rss'] = array();
				for ($i = 0; $i<=2; $i++)
				{
					$j = rand('0', count($data['jrox_rss']));
					array_push($sdata['jrox_rss'], $data['jrox_rss'][$j]);
					unset($data['jrox_rss'][$j]);
				}
				*/
				
			}
		}
	
		return false;
	}
}
?>