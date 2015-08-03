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
| FILENAME - module_affiliate_marketing_viral_pdfs_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for viral pdfs
|
*/

class Module_Affiliate_Marketing_Viral_Pdfs_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$delete = 'DROP TABLE IF EXISTS ' . $this->db->dbprefix('affiliate_viral_pdfs') . ';';
		
		$query = $this->db->query($delete);
		
		$sql = 'CREATE TABLE IF NOT EXISTS ' . $this->db->dbprefix('affiliate_viral_pdfs') . ' (		  
				  id int(10) NOT NULL auto_increment,
				  program_id int(10) NOT NULL DEFAULT \'0\',
				  status enum(\'0\',\'1\') NOT NULL default \'0\',
				  name varchar(255) NOT NULL,
				  viral_pdf_title text NOT NULL,
				  viral_pdf_body text NOT NULL,
				  enable_redirect enum(\'0\',\'1\') NOT NULL,
				  redirect_custom_url varchar(255) NOT NULL,
				  sort_order int(10) NOT NULL default \'0\',
				  notes text NOT NULL,
				  PRIMARY KEY  (id),
				  KEY viral_pdf_name (name)
				) AUTO_INCREMENT=1;';
				
		$query = $this->db->query($sql);
		
		
		$config = array(
							'settings_key'	=>	'module_affiliate_marketing_viral_pdfs_paper_size',
							'settings_value'	=>	'letter',
							'settings_module'	=>	'affiliate_marketing',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'paper_size',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_affiliate_marketing_viral_pdfs_orientation',
							'settings_value'	=>	'portrait',
							'settings_module'	=>	'affiliate_marketing',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'2',
							'settings_function'	=>	'paper_orientation',
							);
		
		$this->db->insert('settings', $config);

		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _add_viral_pdf()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$data['sort_order'] = '1';
		
		$data['viral_pdf_body'] = _content_filter($data['viral_pdf_body']);
		
		//insert into db
		if (!$this->db->insert('affiliate_viral_pdfs', $data))
		{
			show_error($this->lang->line('could_not_add_viral_pdf'));
			
			//log error
			log_message('error', 'Could not insert viral_pdf into viral_pdfs table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			//log success
			log_message('info', 'viral_pdf '. $id . ' inserted into viral_pdfs table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------	
	
	function _edit_viral_pdf($id = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$data['viral_pdf_body'] = _content_filter($data['viral_pdf_body']);
		
		//insert into db
		$this->db->where('id', $id);
		if (!$this->db->update('affiliate_viral_pdfs', $data))
		{
			show_error($this->lang->line('could_not_update_viral_pdf'));
			
			//log error
			log_message('error', 'Could not update text link in affiliate_viral_pdfs table');
			
			return false;
		}
		else
		{	
			//log success
			log_message('info', 'text link '. $id . ' updated into affiliate_viral_pdfs table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------

	function _get_viral_pdf_details($id = '')
	{
		//get the data from viral_pdfs table
		$this->db->where('id', $id);
		$query = $this->db->get('affiliate_viral_pdfs');
		
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
	
	function _generate_viral_pdf_code($id = '', $public = false)
	{
		$pdf = $this->_get_viral_pdf_details($id);
		
		if (!empty($pdf['viral_pdf_body']))
		{
			$this->load->helper('tcpdf');
			
			if ($public == true)
			{
				$filename = url_title($this->init->_parse_member_data($pdf['name']));
				$title = $this->init->_parse_member_data($pdf['viral_pdf_title']);
				$body = $this->init->_parse_member_data($pdf['viral_pdf_body']);
				
				//format the affiliate link
				$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/viral_pdfs/' . $id;
				
				$body  = str_replace('{affiliate_link}', $link, $body);
			}
			else
			{
				$filename = url_title($pdf['name']);
				$title = $pdf['viral_pdf_title'];
				$body = $pdf['viral_pdf_body'];
			}
			
			pdf_create($body, $title, $filename);
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_viral_pdfs($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $members = false, $mid = '', $pid = '')
	{
		//get all the viral_pdfs from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_bnr_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_bnr_column');
		
		if ($members == true && !empty($mid))
		{
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_viral_pdfs') . '.*,
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_pdfs') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'viral_pdfs\'
					AND member_id = \'' . $mid . '\') as clicks,';
					
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == 1)
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_pdfs') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_pdfs\'
					AND member_id = \'' . $mid . '\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_pdfs') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_pdfs\'
					AND member_id = \'' . $mid . '\') as sales';
			}
			else
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_pdfs') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_pdfs\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_pdfs') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_pdfs\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as sales';
			}		
			
					
			$sql .= ' FROM ' . $this->db->dbprefix('affiliate_viral_pdfs') . '
					WHERE status = \'1\'';
					
			if (!empty($pid))
			{
				$sql .= ' AND program_id = 	\'' . $pid . '\'';
			}
			
			$sql .= 'ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		}
		else
		{
		
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_viral_pdfs') . '.*,
					
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_pdfs') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'viral_pdfs\') as clicks,
					
					(SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_pdfs') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_pdfs\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_pdfs') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_pdfs\') as sales
					
					FROM ' . $this->db->dbprefix('affiliate_viral_pdfs') . '
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
		$data = $this->aff->_get_tool_details('viral_pdfs', $id);
		
		$data['s_title'] = '';
				
		//format the affiliate link
		$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/viral_pdfs/' . $data['id'];
		
		$data['viral_pdf_body'] = str_replace('{affiliate_link}', $link, $data['viral_pdf_body']);
		
		$this->_generate_viral_pdf_code($id); 
		
		exit();
	}
	
	// ------------------------------------------------------------------------	
	
	function _download_tool($id = '')
	{
		$this->_generate_viral_pdf_code($id, true);
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_tools($pid = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $mid = '', $num_options = '')
	{
		//get the affiliate tools and put in the array
		$rows = $this-> _get_viral_pdfs($limit, $offset, 'sort_order', 'ASC', true, $mid, $pid);
		
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
				$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/viral_pdfs/' . $v['id'];

				$v['tool_code'] = htmlspecialchars('<a href="' . $link . '">' . $v['viral_pdf_title'] . '</a>');
				
				array_push($a['tool_rows'], $v);
			}		
		}
		
		//set the title and message body
		$a['subject_title'] = $this->lang->line('subject');
		$a['body_title'] = $this->lang->line('viral_pdf');
		
		$sql = 'WHERE status = \'1\'';
		
		if (!empty($pid))
		{
			$sql .= ' AND program_id = 	\'' . $pid . '\'';
		}
		
		$a['row_count'] = $this->db_validation_model->_get_count('affiliate_viral_pdfs', $sql);
		$a['use_pagination'] = true;
		$a['template'] = 'tpl_members_general_marketing_tools5';
		
		return $a;
	}
	
	// ------------------------------------------------------------------------	
}
?>