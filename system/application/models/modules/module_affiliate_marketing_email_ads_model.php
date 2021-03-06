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
| FILENAME - module_affiliate_marketing_email_ads_model.php
| -------------------------------------------------------------------------     
|
*/

class Module_Affiliate_Marketing_email_Ads_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$delete = 'DROP TABLE IF EXISTS ' . $this->db->dbprefix('affiliate_email_ads') . ';';
		
		$query = $this->db->query($delete);
		
		$sql = 'CREATE TABLE IF NOT EXISTS ' . $this->db->dbprefix('affiliate_email_ads') . ' (		  
				  id int(10) NOT NULL auto_increment,
				  program_id int(10) NOT NULL DEFAULT \'0\',
				  status enum(\'0\',\'1\') NOT NULL default \'0\',
				  name varchar(255) NOT NULL,
				  email_ad_title text NOT NULL,
				  email_ad_body text NOT NULL,
				  enable_redirect enum(\'0\',\'1\') NOT NULL,
				  redirect_custom_url varchar(255) NOT NULL,
				  sort_order int(10) NOT NULL default \'0\',
				  notes text NOT NULL,
				  PRIMARY KEY  (id),
				  KEY email_ad_name (name)
				) AUTO_INCREMENT=1;';
				
		$query = $this->db->query($sql);
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _add_email_ad($post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		$data['sort_order'] = $this->db->count_all('affiliate_email_ads') + 1;
		
		$data['email_ad_body'] = _content_filter($data['email_ad_body']);
		
		//insert into db
		if (!$this->db->insert('affiliate_email_ads', $data))
		{
			show_error($this->lang->line('could_not_add_email_ad'));
			
			//log error
			log_message('error', 'Could not insert email_ad into email_ads table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			//log success
			log_message('info', 'email_ad '. $id . ' inserted into email_ads table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------	
	
	function _edit_email_ad($id = '', $post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);

		$data['email_ad_body'] = _content_filter($data['email_ad_body']);
		
		//insert into db
		$this->db->where('id', $id);
		if (!$this->db->update('affiliate_email_ads', $data))
		{
			show_error($this->lang->line('could_not_update_email_ad'));
			
			//log error
			log_message('error', 'Could not update text link in affiliate_email_ads table');
			
			return false;
		}
		else
		{	
			//log success
			log_message('info', 'text link '. $id . ' updated into affiliate_email_ads table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------

	function _get_email_ad_details($id = '')
	{
		//get the data from email_ads table
		$this->db->where('id', $id);
		$query = $this->db->get('affiliate_email_ads');
		
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
	
	function _generate_email_ad_code($code = '')
	{
		$html = '<div class="email-subject">' . $this->lang->line('subject') . ': ' . $code['email_ad_title'] . '</div>';
		
		$html .= '<br />';
	   
	  	$html .= $code['email_ad_body'];
	   
	  
		
		return $html;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_email_ads($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $members = false, $mid = '', $pid = '')
	{
		//get all the email_ads from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_bnr_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_bnr_column');
		
		if ($members == true && !empty($mid))
		{
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_email_ads') . '.*,
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_email_ads') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'email_ads\'
					AND member_id = \'' . $mid . '\') as clicks,';
					
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == 1)
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_email_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'email_ads\'
					AND member_id = \'' . $mid . '\') as commissions,
				
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_email_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'email_ads\'
					AND member_id = \'' . $mid . '\') as sales';
			}
			else
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_email_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'email_ads\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as commissions,
				
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_email_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'email_ads\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as sales';
			}	
			
			$sql .= ' FROM ' . $this->db->dbprefix('affiliate_email_ads') . '
					WHERE status = \'1\'';
					
			if (!empty($pid))
			{
				$sql .= ' AND program_id = 	\'' . $pid . '\'';
			}
			
			$sql .= 'ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		}
		else
		{
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_email_ads') . '.*,
					
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_email_ads') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'email_ads\') as clicks,
					
					(SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_email_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'email_ads\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_email_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'email_ads\') as sales
					
					FROM ' . $this->db->dbprefix('affiliate_email_ads') . '
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
	
	function _print_code($id = '')
	{
		$data = $this->aff->_get_tool_details('email_ads', $id);
		
				
		//format the affiliate link
		$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/email_ads/' . $id;
		
		$data['email_ad_body'] = str_replace('{affiliate_link}', $link, $data['email_ad_body']);
				
		$qr_code = '<img src="' . _public_url() . 'qr/email_ads/' . $id . '/' . $this->session->userdata('m_username') . '" border="0" />';
				
		$data['email_ad_body'] = str_replace('{qr_code}', $qr_code, $data['email_ad_body']);
						
		echo $this->init->_parse_member_data($data['email_ad_body']);
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_tools($pid = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $mid = '', $num_options = '')
	{
		//get the affiliate tools and put in the array
		$rows = $this-> _get_email_ads($limit, $offset, 'sort_order', 'ASC', true, $mid, $pid);
		
		$a['tool_rows'] = array();
		
		if (!empty($rows))
		{
			foreach ($rows as $v)
			{
				$v['s_commissions'] = format_amounts($v['commissions'], $num_options);
				$v['s_sales'] = format_amounts($v['sales'], $num_options);
				$v['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				
				$v['s_title'] = $this->init->_parse_member_data($v['email_ad_title']);
				
				//format the affiliate link
				$link = _public_url() .  _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/email_ads/' . $v['id'];
				
				$v['email_ad_body'] = str_replace('{affiliate_link}', $link, $v['email_ad_body']);
				
				$qr_code = '<img src="' . _public_url() . 'qr/email_ads/' . $v['id'] . '/' . $this->session->userdata('m_username') . '" border="0" />';
				
				$v['email_ad_body'] = str_replace('{qr_code}', $qr_code, $v['email_ad_body']);
				
				$v['tool_code'] = $this->init->_parse_member_data(htmlentities($v['email_ad_body'], ENT_QUOTES, $this->config->item('charset')));
				$v['p_tool_code'] = $this->init->_parse_member_data($v['email_ad_body']);
				
				array_push($a['tool_rows'], $v);
			}		
		}
		
		//set the title and message body
		$a['subject_title'] = $this->lang->line('subject');
		$a['body_title'] = $this->lang->line('email_ad');
		
		$sql = 'WHERE status = \'1\'';
		
		if (!empty($pid))
		{
			$sql .= ' AND program_id = 	\'' . $pid . '\'';
		}
			
		$a['row_count'] = $this->db_validation_model->_get_count('affiliate_email_ads', $sql);
		$a['use_pagination'] = true;
		$a['template'] = 'tpl_members_general_marketing_tools2';
		
		return $a;
	}
	
	// ------------------------------------------------------------------------	
}
?>