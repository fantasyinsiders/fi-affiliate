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
| FILENAME - module_affiliate_marketing_lightbox_ads_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for lightbox ads
|
*/

class Module_Affiliate_Marketing_Lightbox_Ads_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$delete = 'DROP TABLE IF EXISTS ' . $this->db->dbprefix('affiliate_lightbox_ads') . ';';
		
		$query = $this->db->query($delete);
		
		$sql = 'CREATE TABLE IF NOT EXISTS ' . $this->db->dbprefix('affiliate_lightbox_ads') . ' (		  
				  id int(10) NOT NULL auto_increment,
				  program_id int(10) NOT NULL DEFAULT \'0\',
				  status enum(\'0\',\'1\') NOT NULL default \'0\',
				  lightbox_ad_name varchar(255) NOT NULL,
				  lightbox_ad_body text NOT NULL,
				  enable_redirect enum(\'0\',\'1\') NOT NULL,
				  redirect_custom_url varchar(255) NOT NULL,
				  sort_order int(10) NOT NULL default \'0\',
				  notes text NOT NULL,
				  program_id int(10) NOT NULL default \'0\',
				  lightbox_ad_width varchar(10) NOT NULL,
	 	 		  lightbox_ad_height varchar(10) NOT NULL,
				  PRIMARY KEY  (id),
				  KEY lightbox_ad_name (lightbox_ad_name)
				) AUTO_INCREMENT=1;';
				
		$query = $this->db->query($sql);

		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _add_lightbox_ad()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$data['sort_order'] = '1';
		
		$data['lightbox_ad_body'] = _content_filter($data['lightbox_ad_body']);
		
		//insert into db
		if (!$this->db->insert('affiliate_lightbox_ads', $data))
		{
			show_error($this->lang->line('could_not_add_lightbox_ad'));
			
			//log error
			log_message('error', 'Could not insert lightbox_ad into lightbox_ads table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			//log success
			log_message('info', 'lightbox_ad '. $id . ' inserted into lightbox_ads table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------	
	
	function _edit_lightbox_ad($id = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$data['lightbox_ad_body'] = _content_filter($data['lightbox_ad_body']);
		
		//insert into db
		$this->db->where('id', $id);
		if (!$this->db->update('affiliate_lightbox_ads', $data))
		{
			show_error($this->lang->line('could_not_update_lightbox_ad'));
			
			//log error
			log_message('error', 'Could not update text link in affiliate_lightbox_ads table');
			
			return false;
		}
		else
		{	
			//log success
			log_message('info', 'text link '. $id . ' updated into affiliate_lightbox_ads table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------

	function _get_lightbox_ad_details($id = '')
	{
		//get the data from lightbox_ads table
		$this->db->where('id', $id);
		$query = $this->db->get('affiliate_lightbox_ads');
		
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
	
	function _generate_lightbox_ad_code($code = '', $public = false)
	{
		$html = '';
		
		if ($public == true)
		{
			if ($public == true)
			{
				$html .= '<script language="JavaScript" type="text/javascript" src="' . base_url() . 'js/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="' . base_url() . 'js/jquery-ui.js"></script>
<link rel="stylesheet" media="screen" type="text/css" href="' . base_url() . 'js/css/ui.css" />
<script type="text/javascript" src="' . base_url() . 'js/colorbox/jquery.colorbox-min.js"></script>
<link rel="stylesheet" media="screen" type="text/css" href="' . base_url() .  'js/colorbox/colorbox.css" />

<script>
$(document).ready(function() {
	
	$("#show-ad").colorbox({height:"' . $code['lightbox_ad_height'] . '", width:"' . $code['lightbox_ad_width'] . 'px", inline:true, open:true});

	
});
</script>
 <a href="#inline-' . $code['id'] . '" id="show-ad" >' . $this->lang->line('show') . '</a>
 <div style="display: none">
 <div id="inline-' . $code['id'] . '" class="light-box">
 
 ' . $code['lightbox_ad_body'] . '
 
 <div style="clear:both; height: 1px"></div>
 </div>
 </div>
';				
			}	
		}
		else 
		{
			$html .= ' <div id="inline-' . $code['id'] . '" class="light-box">
		   
		   ' . $code['lightbox_ad_body'] . '
		   
		   <div style="clear:both; height: 1px"></div>
		   </div>';		
		}
		
		
		return $html;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_lightbox_ads($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $members = false, $mid = '', $pid = '')
	{
		//get all the lightbox_ads from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_bnr_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_bnr_column');
		
		if ($members == true && !empty($mid))
		{
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_lightbox_ads') . '.*,
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_lightbox_ads') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'lightbox_ads\'
					AND member_id = \'' . $mid . '\') as clicks,';
					
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == 1)
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_lightbox_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'lightbox_ads\'
					AND member_id = \'' . $mid . '\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_lightbox_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'lightbox_ads\'
					AND member_id = \'' . $mid . '\') as sales';
			}
			else
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_lightbox_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'lightbox_ads\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as commissions,
				
				   (SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_lightbox_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'lightbox_ads\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as sales';
			}		
			
					
			$sql .= ' FROM ' . $this->db->dbprefix('affiliate_lightbox_ads') . '
					WHERE status = \'1\'';
			
			if (!empty($pid))
			{
				$sql .= ' AND program_id = 	\'' . $pid . '\'';
			}
			
			$sql .= ' ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		}
		else
		{
		
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_lightbox_ads') . '.*,
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_lightbox_ads') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'lightbox_ads\') as clicks,
					
					(SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_lightbox_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'lightbox_ads\') as commissions,
					
					 (SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_lightbox_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'lightbox_ads\') as sales
					
					FROM ' . $this->db->dbprefix('affiliate_lightbox_ads') . '
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
		$data = $this->aff->_get_tool_details('lightbox_ads', $id);
		
				
		$data['s_title'] = $data['lightbox_ad_name'];
		
		//format the affiliate link
		$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/lightbox_ads/' . $data['id'];
				
		$data['html_ad_body'] = str_replace('{affiliate_link}', $link, $data['lightbox_ad_body']);
				
		$data['tool_code'] = $this->init->_parse_member_data($this->_generate_lightbox_ad_code($data, true));
		$data['tool_code'] = auto_link($data['tool_code']);
		
		//set the title and message body
		$data['template'] = 'tpl_members_marketing_preview_code2';
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_tools($pid = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $mid = '', $num_options = '')
	{
		//get the affiliate tools and put in the array
		$rows = $this-> _get_lightbox_ads($limit, $offset, 'sort_order', 'ASC', true, $mid, $pid);
		
		$a['tool_rows'] = array();
		
		if (!empty($rows))
		{
			$a['tool_rows'] = array();
		
			foreach ($rows as $v)
			{
				$v['s_commissions'] = format_amounts($v['commissions'], $num_options);
				$v['s_sales'] = format_amounts($v['sales'], $num_options);
				$v['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				
				$v['name'] = $v['lightbox_ad_name'];
				
				//format the affiliate link
				$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/lightbox_ads/' . $v['id'];
				
				$v['lightbox_ad_body'] = str_replace('{affiliate_link}', $link, $v['lightbox_ad_body']); 
				$v['p_tool_code'] = $v['lightbox_ad_body'];
				
				$v['tool_code'] = htmlspecialchars($this->init->_parse_member_data($this->_generate_lightbox_ad_code($v, true)));
				
				$v['width'] = $v['lightbox_ad_width'];
				$v['height'] = $v['lightbox_ad_height'];
				
				array_push($a['tool_rows'], $v);
			}		
		}
		
		//set the title and message body
		$a['subject_title'] = $this->lang->line('subject');
		$a['body_title'] = $this->lang->line('lightbox_ad');
		
		$sql = 'WHERE status = \'1\'';
		
		if (!empty($pid))
		{
			$sql .= ' AND program_id = 	\'' . $pid . '\'';
		}
		
		$a['row_count'] = $this->db_validation_model->_get_count('affiliate_lightbox_ads', $sql);
		$a['use_pagination'] = true;
		$a['template'] = 'tpl_members_general_marketing_tools3';
		
		return $a;
	}
	
	// ------------------------------------------------------------------------	
}
?>