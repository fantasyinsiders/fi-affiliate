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
| FILENAME - affiliate_marketing_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for affiliate_marketing
|
*/

class Affiliate_Marketing_Model extends CI_Model {
	
	// ------------------------------------------------------------------------
	
	function _get_total_clicks($type = '', $id = '', $mid = '')
	{
		$sql = 'SELECT COUNT(traffic_id) as total from ' . $this->db->dbprefix('traffic') . ' 
					WHERE tool_id = \'' . $id . '\'	
					AND tool_type = \'' . $type . '\'';
					
		if (!empty($mid))
		{
			$sql .= 'AND member_id = \'' . $mid . '\'';		
		}
		
		$query = $this->db->query($sql);
					
		$row = $query->row_array();
		
		return $row['total'];
	}
	
	// ------------------------------------------------------------------------
	
	function _get_total_sales($type = '', $id = '', $mid = '')
	{
		$sql = 'SELECT SUM(commission_amount) as total_comms, SUM(sale_amount) as total_sales 
				FROM ' . $this->db->dbprefix('commissions') . ' 
				WHERE tool_id = \'' . $id . '\'	
				AND tool_type = \'' . $type . '\'';
					
		if (!empty($mid))
		{
			$sql .= 'AND member_id = \'' . $mid . '\'';		
		}
		
		$query = $this->db->query($sql);
					
		$row = $query->row_array();
		
		return $row;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_lifetime_sponsor($id = '')
	{
		if ($this->config->item('sts_tracking_enable_lifetime_tracking') == 1)
		{
			$this->db->where('tracking_id', $id);
			
			$query = $this->db->get('tracking_log');
			
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array();
				
				return $row['member_id'];
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _add_lifetime_sponsor($data = array())
	{
		if ($this->config->item('sts_tracking_enable_lifetime_tracking') == 0) { return; }

		if (empty($data['lf_data'])) { return; }
		$row = $this->_get_lifetime_sponsor($data['lf_data']);
		
		if (empty($row))
		{
			$a = array('tracking_id' => trim($data['lf_data']),
					   'member_id' => $data['member_id'],
					   'date' => _generate_timestamp(),
					   'ip_address' => $this->input->ip_address(),
					   );
			
			$this->db->insert('tracking_log', $a);
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _generate_traffic_code()
	{
		$chk = false;
		
		while ($chk == false)
		{
			$str = _generate_random_string('15', 'random');
			$this->db->where('tracking_code', $str);
			$query = $this->db->get('traffic');
			
			if ($query->num_rows() < 1)
			{
				$chk = true;
				return $str;	
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _get_ip_sponsor($ip = '')
	{
		if ($this->config->item('sts_tracking_enable_ip_address') == 1)
		{
			$this->db->where('ip_address', $ip);
			$query = $this->db->get('traffic');
			
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array();
				
				return $row['member_id'];
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_ad_tracker()
	{
		$id = get_cookie($this->config->item('tracking_cookie_name'));	
		
		if (!empty($id))
		{
			$this->db->where('id', $id);
			$query = $this->db->get('tracking');
			
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array();
				
				$b = array( 'member_id' 	=> 	empty($row['member_id']) ? '' : $row['member_id'],
						'program_id' 	=> 	empty($row['program_id']) ? '1' : $row['program_id'],
						'username' 		=> 	'',
				);
				if (!empty($b))
				{
					$traffic = $this->_get_traffic_details('tracker', $row['id']);
					
					if (!empty($traffic))
					{
						$b['tool_type'] = $traffic['tool_type'];
						$b['tool_id'] = $traffic['tool_id'];
						$b['referrer'] = $traffic['referrer'];
						$v['tracking_code'] = $traffic['tracking_code'];
					}
					
					return $b;
				}
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_tracking_cookie($sdata = array())
	{
		if (!empty($sdata['tracking_code']))
		{
			$a = explode('-', $sdata['tracking_code']);	
		}
		else
		{
			$a = retrieve_cookie_data($this->config->item('aff_cookie_name'));	
		}

		if (is_array($a))
		{
			if (!empty($a[2]))
			{
				$this->db->where('username', $a[2]);
				
				$query = $this->db->get ('members');	
			
				if ($query->num_rows() > 0)
				{
					$row = $query->row_array();	
				
					$a[0] = $row['member_id'];
				}
			}
			
			$b = array( 'member_id' 	=> 	empty($a[0]) ? '' : $a[0],
						'program_id' 	=> 	empty($a[1]) ? '' : $a[1],
						'username' 		=> 	empty($a[2]) ? '' : $a[2],
						'tracking_code'		=> 	empty($a[3]) ? '' : $a[3],
				);
			
			if (!empty($b['tracking_code']))
			{
				$traffic = $this->_get_traffic_details('tracking_code', $b['tracking_code']);
				
				if (!empty($traffic))
				{
					$b['tool_type'] = $traffic['tool_type'];
					$b['tool_id'] = $traffic['tool_id'];
					$b['referrer'] = $traffic['referrer'];
					$b['tracker'] = $traffic['tracker'];
				}
			}
			
			return $b;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_traffic_details($col = 'tracking_code', $id = '')
	{
		$this->db->where($col, $id);
		
		$query = $this->db->get('traffic');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
			
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_config($id = '')
	{
		$this->db->where('module_id', $id);
		$this->db->where('module_type', 'affiliate_marketing');
		
		$query = $this->db->get('affiliate_marketing');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_user_traffic($id = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		if (!$sort_order) $sort_order = $this->config->item('mem_dbs_tff_order');
		if (!$sort_column) $sort_column = $this->config->item('mem_dbs_tff_column');
		
		$this->db->where('member_id', $id);
		$this->db->order_by($sort_column, $sort_order);
		$this->db->limit($limit, $offset);			
		
		$query = $this->db->get('traffic');

		if ($query->num_rows() > 0)
		{
		
			$a['traffic'] = array();
			
			foreach ($query->result_array() as $row)
			{
				$row['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				
				if (!empty($row['country_code']) && file_exists(PUBPATH . '/images/misc/flags/' . strtolower($row['country_code']) . '.gif'))
				{
					$row['country_flag'] = strtolower($row['country_code']) . '.gif';
				}
				
				array_push($a['traffic'], $row);
			}
			
			$this->db->where('member_id', $id);
			$this->db->from('traffic');
			
			$a['total_rows'] = $this->db->count_all_results();
			
			return $a;
		}
		
		return false;
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _insert_affiliate_traffic($data = '')
	{
		if ($this->db->insert('traffic', $data))
		{
			return $this->db->insert_id();
		}
		
		return false;		
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_subdomain($sub = '')
	{
		$subs = explode(',', $this->config->item('sts_affiliate_restrict_subdomains'));

		foreach ($subs as $value)
		{
			if ($sub == $value)
			{
				return false;
			}
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _validate_user($user = '', $id = false)
	{
		
		$this->db->select('members.*, members.member_id as mid, members_photos.*, members_groups.group_id');
		$this->db->from('members');
		
		if ($id == true)
		{		
			$this->db->where('members.member_id', $user);
		}
		else
		{
			$this->db->where('members.username', $user);
		}
		 
		$this->db->where('status', '1');
		$this->db->join('members_photos', 'members.member_id = members_photos.member_id', 'left');
		$this->db->join('members_groups', 'members.member_id = members_groups.member_id', 'left');
		$this->db->group_by('members.member_id');
		
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			$a = $query->row_array();
			
			unset ($a['password']);
			
			return $a;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _delete_tool($type = '', $id = '', $table = '')
	{		
		$data = array('tool_id' => '0',
					  'tool_type' => '');
		
		$this->db->where('tool_id', $id);
		$this->db->where('tool_type', $type);
		
		$this->db->update('traffic', $data);
		
		$this->db->where('tool_id', $id);
		$this->db->where('tool_type', $type);
		
		$this->db->update('commissions', $data);
		
		$this->db->where('id', $id);
		
		if ($this->db->delete($table))
		{
			log_message('info', $type . ' ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_' . $type));
			
			log_message('error', $type . ' ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _block_clicks()
	{
		if ($this->config->item('sts_click_security_block_ip') && !empty($_SERVER['REMOTE_ADDR']))
		{
			$deny = explode("\n", trim($this->config->item('sts_click_security_block_ip')));
			
			foreach($deny as $ip) 
			{
				$ip = trim($ip);
				
				if(preg_match("/$ip/",$_SERVER['REMOTE_ADDR'])) 
				{
					return true;
				}
			}
		}
		
		if ($this->config->item('sts_click_security_block_clicks_same_ip') == 1)
		{
			$this->db->where('ip_address', $this->input->ip_address());
			$this->db->limit(1);
			
			$query = $this->db->get('traffic');
			
			if ($query->num_rows() > 0)
			{
				return true;
			}
		}
		return false;	
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_ppc($prog = '', $ip = '')
	{
		$now = _generate_timestamp();
		
		$this->db->where('program_id', $prog['program_id']);
		$this->db->where('ip_address', $ip);
		$this->db->order_by('date', 'DESC');
		$this->db->limit(1);
		
		$query = $this->db->get('traffic');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			$expires = $row['date'] + ($prog['ppc_interval'] * 60);

			if ($expires > $now) { return true; }
		}
		
		return false;	
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_tool_details($type = '', $id = '')
	{
		$this->db->where('id', $id);
		$this->db->where('status', '1');

		$query = $this->db->get('affiliate_' . $type);
	
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
			
		}
		
		return false;
	}

	// ------------------------------------------------------------------------	
	
	function _get_affiliate_marketing_details($id = '')
	{
		$this->db->where('module_id', $id);
		$this->db->where('module_type', 'affiliate_marketing');
		
		$query = $this->db->get('modules');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();	
			
			$row['sts_config'] = array();
			
			$this->db->where('settings_module', 'affiliate_marketing');
			$this->db->where('settings_group', $id);
			$this->db->order_by('settings_sort_order', 'ASC');
			
			$query = $this->db->get('settings');
		
			foreach ($query->result_array() as $options)
			{
				array_push($row['sts_config'], $options);
			}
			
			return $row;
		}
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_affiliate_marketing($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		if (!$sort_order) $sort_order = $this->config->item('dbs_afm_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_afm_column');
		
		$this->db->order_by($sort_column, $sort_order); 	
		$this->db->where('module_type', 'affiliate_marketing');
		
		$query = $this->db->get('modules', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_program_tools()
	{
		$this->db->order_by($this->config->item('mem_dbs_afm_column'), $this->config->item('mem_dbs_afm_order')); 	
		$this->db->where('module_type', 'affiliate_marketing');
		$this->db->where('module_status', '1');
		
		$query = $this->db->get('modules');

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_user_tools()
	{		
		$this->db->order_by($this->config->item('mem_dbs_afm_column'), $this->config->item('mem_dbs_afm_order')); 	
		$this->db->where('module_type', 'affiliate_marketing');
		$this->db->where('module_status', '1');
		
		$query = $this->db->get('modules');

		if ($query->num_rows() > 0)
		{
			$a = array();
			foreach ($query->result_array() as $v)
			{
				if (file_exists($this->config->item('base_physical_path') . '/themes/main/' . $this->config->item('default_theme') . '/img/module_' . $v['module_type'] . '_' . $v['module_file_name'] . '.' . $this->config->item('member_marketing_tool_ext')))
				{				
					$v['tool_image'] = base_url() . 'themes/main/' . $this->config->item('default_theme') . '/img/module_' . $v['module_type'] . '_' . $v['module_file_name'] . '.' .  $this->config->item('member_marketing_tool_ext');
				}
				elseif (file_exists($this->config->item('base_physical_path') . '/images/modules/module_' . $v['module_type'] . '_' . $v['module_file_name'] . '.' . $this->config->item('member_marketing_tool_ext')))
				{				
					$v['tool_image'] = base_url() . 'images/modules/module_' . $v['module_type'] . '_' . $v['module_file_name'] . '.' .  $this->config->item('member_marketing_tool_ext');
				}
				else
				{
					$v['tool_image'] = base_url() . 'images/modules/tools.png';
				}
			
				array_push($a, $v);
			}
			
			return $a;
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _change_sort_order($table = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		foreach ($data as $k => $v)
		{
			if (strstr($k, "tool") == true) 
			{
				$id = explode("-", $k);
			
				$this->db->where('id', $id[1]);
				
				$sdata = array('sort_order' => $v);
				
				if (!$this->db->update($table, $sdata))
				{
					show_error($this->lang->line('could_not_update_affiliate_marketing'));
					
					log_message('error', 'Could not update affiliate_marketing #' . $k . ' in affiliate_marketing table');
					
					return false;
				}
			}
		}
		
		$this->db_validation_model->_db_sort_order($table, 'id', 'sort_order'); 
		
		return true;		
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_options($post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		if ($this->db_validation_model->_update_db_settings($data))
		{
			log_message('info', 'settings table updated successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_update_settings'));
			
			log_message('error', 'Could not update settings table');
			
			return false;		
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------	
	
}
?>