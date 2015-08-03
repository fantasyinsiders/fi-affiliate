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
| FILENAME - module_affiliate_marketing_invisilinks_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for invisilinks
|
*/

class Module_Affiliate_Marketing_Invisilinks_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$delete = 'DROP TABLE IF EXISTS ' . $this->db->dbprefix('affiliate_invisilinks') . ';';
		
		$query = $this->db->query($delete);
		
		$sql = 'CREATE TABLE IF NOT EXISTS ' . $this->db->dbprefix('affiliate_invisilinks') . ' (		  
				  id int(10) NOT NULL auto_increment,
				  program_id int(10) NOT NULL DEFAULT \'0\',
				  status enum(\'0\',\'1\') NOT NULL default \'0\',
				  member_id int(10) NOT NULL,
				  invisilink_url varchar(255) NOT NULL,
				  notes text NOT NULL,
				  PRIMARY KEY  (id)
				) AUTO_INCREMENT=1;';
				
		$query = $this->db->query($sql);
		
		$config = array(
							'settings_key'	=>	'module_affiliate_marketing_banners_file_types',
							'settings_value'	=>	'jpg|gif|png',
							'settings_module'	=>	'affiliate_marketing',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _initialize($tool_id = '', $type = 'add', $id = '')
	{
		$a = array(
				   'edit_function' => '_edit_invisilink',
				   'add_function' => '_add_invisilink',
				   'check_edit_function' => '_check_tool_update',
				   'check_add_function' => '_check_tool_add',
				   'template' => 'tpl_members_manage_invisilink',
				   'post_add_function' => '_send_admin_alert',
				   );
		
		if ($type == 'add')
		{
			$a['page_header'] = 'add_invisilink_url';
			$a['submit_url'] = site_url('members') . '/marketing/add/invisilinks/' . $tool_id;
		}
		else
		{
			$a['page_header'] = 'edit_invisilink_url';
			$a['submit_url'] = site_url('members') . '/marketing/edit/invisilinks/' . $id . '/' . $tool_id;
		}
		
		return $a;

	}
	
	// ------------------------------------------------------------------------	
	
	function _send_admin_alert($post = '', $id = '')
	{
		if ($this->config->item('module_affiliate_marketing_invisilinks_alert_email'))
		{
			$subject = $this->lang->line('new_invisilink_needs_approval');
			$message = $this->lang->line('new_invisilink_needs_approval');
		
			$headers = 'From: ' . $this->config->item('sts_site_email');
			
			//SEND EMAIL
			@mail($this->config->item('module_affiliate_marketing_invisilinks_alert_email'),$subject,$message,$headers);	
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _deactivate($id = '', $mid = '')
	{
		$this->db->where('id', $id);
		$this->db->where('member_id', $mid);
		if ($this->db->update('affiliate_invisilinks', array('status' => '0')))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_tool_add()
	{

		//clean it
		if (!empty($_POST['invisilink_url']))
		{	
			preg_match('@^(?:http://)?([^/]+)@i', $_POST['invisilink_url'], $matches);

			$this->validation->invisilink_url = $matches[1];
			
			if ($this->_check_invisilink_add() == false)
			{
				$this->validation->error_string = $this->lang->line('domain_name_taken');
				
				return false;	
			}
		}
		
		$rules['invisilink_url'] = 'trim|required';
		$this->validation->set_rules($rules);

		//repopulate form
		$fields['invisilink_url'] = $this->lang->line('url');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_tool_update($id = '', $post)
	{

		//clean it
		if (!empty($post['invisilink_url']))
		{	
			preg_match('@^(?:http://)?([^/]+)@i', $post['invisilink_url'], $matches);

			$this->validation->invisilink_url = $matches[1];
				
			if ($this->_check_invisilink_update() == false)
			{
				$this->validation->error_string = $this->lang->line('domain_name_taken');
				
				return false;	
			}
		}
		
		$rules['invisilink_url'] = 'trim|required';
	
		$this->validation->set_rules($rules);
		
		//repopulate form
		$fields['invisilink_url'] = $this->lang->line('url');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_invisilink_add()
	{
		if ($this->db_validation_model->_validate_field('affiliate_invisilinks', 'invisilink_url', $this->validation->invisilink_url))
		{
			$this->validation->set_message('_check_invisilink_add', $this->lang->line('domain_name_taken'));
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_invisilink_update()
	{
		if ($this->db_validation_model->_validate_field('affiliate_invisilinks', 'invisilink_url', $this->validation->invisilink_url, 'id', (int)$this->uri->segment(5)))
		{
			$this->validation->set_message('_check_invisilink_update', $this->lang->line('domain_name_taken'));
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _add_invisilink($post = array(), $mid = '')
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		//filter sponsor
		if (!empty($data['member_id'])) 
		{
			$sponsor_data = $this->db_validation_model->_get_details('members', 'member_id', 'username', $data['member_id']);
			
			$data['member_id'] = !empty($sponsor_data) ? $sponsor_data[0]['member_id'] : 0;
		}
		
		if (!empty($mid))
		{
			$data['member_id'] = $mid;
		}	
		
		preg_match('@^(?:http://)?([^/]+)@i', $_POST['invisilink_url'], $matches);

		$data['invisilink_url'] = $matches[1];
			
		//insert into db
		if (!$this->db->insert('affiliate_invisilinks', $data))
		{
			show_error($this->lang->line('could_not_add_invisilink'));
			
			//log error
			log_message('error', 'Could not insert invisilink into invisilinks table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			//log success
			log_message('info', 'invisilink '. $id . ' inserted into invisilinks table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------	
	
	function _edit_invisilink($id = '', $post = array(), $mid = '')
	{
		$data = $this->db_validation_model->_clean_data($post);

		//filter sponsor
		if (!empty($data['member_id'])) 
		{
			$sponsor_data = $this->db_validation_model->_get_details('members', 'member_id', 'username', $data['member_id']);
			
			$data['member_id'] = !empty($sponsor_data) ? $sponsor_data[0]['member_id'] : 0;
		}
		
		preg_match('@^(?:http://)?([^/]+)@i', $_POST['invisilink_url'], $matches);

		$data['invisilink_url'] = $matches[1];
		
		//insert into db
		$this->db->where('id', $id);
		if (!empty($mid))
		{
			$data['member_id'] = $mid;
			$this->db->where('member_id', $mid);
		}	
		if (!$this->db->update('affiliate_invisilinks', $data))
		{
			show_error($this->lang->line('could_not_update_invisilink'));
			
			//log error
			log_message('error', 'Could not update invisilink in affiliate_invisilinks table');
			
			return false;
		}
		else
		{	
			//log success
			log_message('info', 'invisilink '. $id . ' updated into affiliate_invisilinks table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------

	function _get_tool_details($id = '', $mid = '')
	{
		//get the data from invisilinks table
		$this->db->where('id', $id);
		$this->db->where('member_id', $mid);
		$query = $this->db->get('affiliate_invisilinks');
		
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

	function _get_invisilink_details($id = '')
	{
		//get the data from invisilinks table
		$this->db->where('id', $id);
		$query = $this->db->get('affiliate_invisilinks');
		
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
	
	function _get_invisilinks($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $members = false, $mid = '')
	{
		//get all the invisilinks from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_inl_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_inl_column');
		
		if ($members == true && !empty($mid))
		{
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_invisilinks') . '.*,
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_invisilinks') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'invisilinks\'
					AND member_id = \'' . $mid . '\') as clicks,';
					
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == 1)
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_invisilinks') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'invisilinks\'
					AND member_id = \'' . $mid . '\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_invisilinks') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'invisilinks\'
					AND member_id = \'' . $mid . '\') as sales';
			}
			else
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_invisilinks') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'invisilinks\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_invisilinks') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'invisilinks\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as sales'; 
			}		
			
					
			$sql .= ' FROM ' . $this->db->dbprefix('affiliate_invisilinks') . '
					WHERE  member_id = \'' . $mid . '\'
					ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		}
		else
		{
		
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_invisilinks') . '.*,
					
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_invisilinks') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'invisilinks\') as clicks,
					
					(SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_invisilinks') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'invisilinks\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_invisilinks') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'invisilinks\') as sales
					
					FROM ' . $this->db->dbprefix('affiliate_invisilinks') . '
					ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		}
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _preview_code($id = '')
	{
		$data = $this->aff->_get_tool_details('invisilinks', $id);
		
		$data['s_title'] = '';
				
		//format the affiliate link
		$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/invisilinks/' . $data['id'];
				
		$data['tool_code'] = '<a href="' . $link . '">' . $data['invisilink_title'] . '</a>';
		
		//set the title and message body
		$data['subject_title'] = $this->lang->line('subject');
		$data['body_title'] = $this->lang->line('invisilink');
		$data['template'] = 'tpl_members_marketing_preview_code2';
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_tools($pid = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $mid = '', $num_options = '')
	{
		//get the affiliate tools and put in the array
		$rows = $this-> _get_invisilinks($limit, $offset, 'invisilink_url', 'ASC', true, $mid, $pid);
		
		$a['tool_rows'] = array();
		
		if (!empty($rows))
		{
			$a['tool_rows'] = array();
		
			foreach ($rows as $v)
			{
				$v['s_commissions'] = format_amounts($v['commissions'], $num_options);
				$v['s_sales'] = format_amounts($v['sales'], $num_options);
				$v['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				
				//format the affiliate link
				$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/invisilinks/' . $v['id'];

				
				array_push($a['tool_rows'], $v);
			}		
		}
		
		$a['row_count'] = $this->db_validation_model->_get_count('affiliate_invisilinks', 'WHERE member_id = \'' . $mid . '\'');
		$a['use_pagination'] = true;
		$a['template'] = 'tpl_members_invisilinks';
		
		return $a;
	}
	
	// ------------------------------------------------------------------------	
}
?>