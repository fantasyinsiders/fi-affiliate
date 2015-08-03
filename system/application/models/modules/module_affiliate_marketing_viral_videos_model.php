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
| FILENAME - module_affiliate_marketing_viral_videos_model.php
| -------------------------------------------------------------------------     
| 
*/

class Module_Affiliate_Marketing_Viral_Videos_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$delete = 'DROP TABLE IF EXISTS ' . $this->db->dbprefix('affiliate_viral_videos') . ';';
		
		$query = $this->db->query($delete);
		
		$sql = 'CREATE TABLE IF NOT EXISTS ' . $this->db->dbprefix('affiliate_viral_videos') . ' (		  
				  id int(10) NOT NULL auto_increment,
				  program_id int(10) NOT NULL DEFAULT \'0\',
				  status enum(\'0\',\'1\') NOT NULL default \'0\',
				  name varchar(255) NOT NULL,
				  viral_video_link text NOT NULL,
				  enable_redirect enum(\'0\',\'1\') NOT NULL,
				  redirect_custom_url varchar(255) NOT NULL,
				  sort_order int(10) NOT NULL default \'0\',
				  notes text NOT NULL,
				  PRIMARY KEY  (id),
				  KEY viral_video_name (name)
				) AUTO_INCREMENT=1;';
				
		$query = $this->db->query($sql);
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_viral_video_code($html = '', $link = '')
	{
		$slink = empty($link) ? $this->config->item('sts_video_player_default_link') : $link;
		
		$html = '<div style="position:relative"><div onClick="window.location=\'' . $link . '\'" style="z-index:100; background:#' . $this->config->item('sts_video_player_back_color') . '; position: absolute; height: ' . $this->config->item('sts_video_player_height') . 'px; width: ' . $this->config->item('sts_video_player_width') .'px; top: 0px; left: 0px;">
		<div style="color: #' . $this->config->item('sts_video_player_front_color') . '; z-index:100"><a href="' . $slink . '">
		<img src="' . $this->config->item('sts_video_player_logo') . '" /></a></div></div>
		<div style="z-index:1;"><p>' . html_entity_decode($html['viral_video_link']) . '</p><p><button onclick="window.location=\'' . $slink . '\'" style=" display: inline-block; padding: 4px 12px;
margin-bottom: 0; font-size: 14px; line-height: 20px; text-align: center; vertical-align: middle; cursor: pointer;
  color: #333333;
  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
  background-color: #f5f5f5;
  background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6));
  background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6);
  background-image: -o-linear-gradient(top, #ffffff, #e6e6e6);
  background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#ffffffff\', endColorstr=\'#ffe6e6e6\', GradientType=0);
  border-color: #e6e6e6 #e6e6e6 #bfbfbf;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  *background-color: #e6e6e6;
  filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
  border: 1px solid #cccccc;
  *border: 0;
  border-bottom-color: #b3b3b3;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  *margin-left: .3em;
  -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05);
  -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05);
  box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05);">' . $html['name'] . '</button></p></div></div>';
		
		return $html;
	}
	
	// ------------------------------------------------------------------------	
	
	function _add_viral_video()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$data['sort_order'] = '1';
		
		//insert into db
		if (!$this->db->insert('affiliate_viral_videos', $data))
		{
			show_error($this->lang->line('could_not_add_viral_video'));
			
			//log error
			log_message('error', 'Could not insert viral_video into viral_videos table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			//log success
			log_message('info', 'viral_video '. $id . ' inserted into viral_videos table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------	
	
	function _edit_viral_video($id = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//insert into db
		$this->db->where('id', $id);
		if (!$this->db->update('affiliate_viral_videos', $data))
		{
			show_error($this->lang->line('could_not_update_viral_video'));
			
			//log error
			log_message('error', 'Could not update text link in affiliate_viral_videos table');
			
			return false;
		}
		else
		{	
			//log success
			log_message('info', 'text link '. $id . ' updated into affiliate_viral_videos table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------

	function _get_viral_video_details($id = '')
	{
		//get the data from viral_videos table
		$this->db->where('id', $id);
		$query = $this->db->get('affiliate_viral_videos');
		
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
	
	function _get_viral_videos($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $members = false, $mid = '', $pid = '')
	{
		//get all the viral_videos from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_bnr_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_bnr_column');
		
		if ($members == true && !empty($mid))
		{
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_viral_videos') . '.*,
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_videos') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'viral_videos\'
					AND member_id = \'' . $mid . '\') as clicks,';
					
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == 1)
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_videos') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_videos\'
					AND member_id = \'' . $mid . '\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_videos') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_videos\'
					AND member_id = \'' . $mid . '\') as sales';
			}
			else
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_videos') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_videos\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_videos') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_videos\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as sales';
			}		
			
					
			$sql .= ' FROM ' . $this->db->dbprefix('affiliate_viral_videos') . '
					WHERE status = \'1\'';
					
			if (!empty($pid))
			{
				$sql .= ' AND program_id = 	\'' . $pid . '\'';
			}
			
			$sql .= 'ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		}
		else
		{
		
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_viral_videos') . '.*,
					
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_videos') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'viral_videos\') as clicks,
					
					(SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_videos') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_videos\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_viral_videos') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'viral_videos\') as sales
					
					FROM ' . $this->db->dbprefix('affiliate_viral_videos') . '
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
		$data = $this->aff->_get_tool_details('viral_videos', $id);
		
		$data['s_title'] = '';
				
		//format the affiliate link
		$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/viral_videos/' . $data['id'];
				
		$data['tool_code'] = $this->_generate_viral_video_code($data, $link, 'jroxContainer');
		
		//set the title and message body
		$data['subject_title'] = $this->lang->line('subject');
		$data['body_title'] = $this->lang->line('viral_video');
		$data['template'] = 'tpl_members_marketing_preview_code2';
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_tools($pid = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $mid = '', $num_options = '')
	{
		//get the affiliate tools and put in the array
		$rows = $this-> _get_viral_videos($limit, $offset, 'sort_order', 'ASC', true, $mid, $pid);
		
		$a['tool_rows'] = array();
		
		if (!empty($rows))
		{
			$a['tool_rows'] = array();
		
			foreach ($rows as $v)
			{
				$v['s_commissions'] = format_amounts($v['commissions'], $num_options);
				$v['s_sales'] = format_amounts($v['sales'], $num_options);
				$v['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				
				$v['height'] = $this->config->item('sts_video_player_height') + 100;
				$v['width'] = $this->config->item('sts_video_player_width') + 100;
				
				//format the affiliate link
				$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/viral_videos/' . $v['id'];
				
				$v['s_title'] = $this->init->_parse_member_data($v['name']);
				
				$v['tool_code'] = $this->_generate_viral_video_code($v, $link, 'jroxContainer');
				
				array_push($a['tool_rows'], $v);
			}		
		}
		
		//set the title and message body
		$a['subject_title'] = $this->lang->line('subject');
		$a['body_title'] = $this->lang->line('viral_video');
		
		$sql = 'WHERE status = \'1\'';
		
		if (!empty($pid))
		{
			$sql .= ' AND program_id = 	\'' . $pid . '\'';
		}
		
		$a['row_count'] = $this->db_validation_model->_get_count('affiliate_viral_videos', $sql);
		$a['use_pagination'] = true;
		$a['template'] = 'tpl_members_general_marketing_tools6';
		
		return $a;
	}
	
	// ------------------------------------------------------------------------	
}
?>