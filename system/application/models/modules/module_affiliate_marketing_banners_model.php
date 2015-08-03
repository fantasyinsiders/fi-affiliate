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
| FILENAME - module_affiliate_marketing_banners_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for banners
|
*/

class Module_Affiliate_Marketing_Banners_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$delete = 'DROP TABLE IF EXISTS ' . $this->db->dbprefix('affiliate_banners') . ';';
		
		$query = $this->db->query($delete);
		
		$sql = 'CREATE TABLE IF NOT EXISTS ' . $this->db->dbprefix('affiliate_banners') . ' (
				  id int(10) NOT NULL auto_increment,
				  program_id int(10) NOT NULL DEFAULT \'0\',
				  status enum(\'0\',\'1\') NOT NULL default \'0\',
				  name varchar(255) NOT NULL,
				  rotator_group enum(\'0\',\'1\') NOT NULL,
				  enable_redirect enum(\'0\',\'1\') NOT NULL default \'0\',
				  redirect_custom_url varchar(255) NOT NULL,
				  banner_height int(10) NOT NULL,
				  banner_width int(10) NOT NULL,
				  use_external_image enum(\'0\',\'1\') NOT NULL default \'0\',
				  banner_file_name varchar(255) NOT NULL,
				  sort_order int(10) NOT NULL,
				  notes text NOT NULL,
				  PRIMARY KEY  (id),
				  KEY banner_name (name)
				) AUTO_INCREMENT=1 ;';
				
		$query = $this->db->query($sql);
		
		$config = array(
							'settings_key'	=>	'module_affiliate_marketing_invisilinks_alert_email',
							'settings_value'	=>	'',
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
	
	function _add_banner($post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		$data['sort_order'] = $this->db->count_all('affiliate_banners') + 1;
		
		if (!$this->db->insert('affiliate_banners', $data))
		{
			show_error($this->lang->line('could_not_add_banner'));
			
			log_message('error', 'Could not insert banner into banners table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();

			log_message('info', 'banner '. $id . ' inserted into banners table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------	
	
	function _delete_banner($id = '')
	{
		
		//update banners first
		
		$data = array('tool_id' => '0',
					  'tool_type' => '');
		
		//update traffic
		$this->db->where('tool_id', $id);
		$this->db->where('tool_type', 'banner');
		$this->db->update('traffic', $data);
		

		//update commissions
		$this->db->where('tool_id', $id);
		$this->db->where('tool_type', 'banner');
		$this->db->update('commissions', $data);
	
		//delete photo if any
		
		//get banner data
		$banner_data = $this->_get_banner_details($id);
		
		//check first if photo needs to be deleted
		if (!empty($banner_data[0]['banner_file_name']))
		{
			@unlink('./images/' . $this->config->item('images_banners_dir') . '/' . $banner_data[0]['banner_file_name']);
		}
		
		//delete banner
		$this->db->where('id', $id);
		if ($this->db->delete('affiliate_banners'))
		{
			
			//log success
			log_message('info', 'banner ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_banner'));
			
			//log error
			log_message('error', 'banner ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------	
	
	function _edit_banner($id = '', $post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		//insert into db
		$this->db->where('id', $id);
		if (!$this->db->update('affiliate_banners', $data))
		{
			show_error($this->lang->line('could_not_update_banner'));
			
			//log error
			log_message('error', 'Could not update banner into affiliate_banners table');
			
			return false;
		}
		else
		{	
			//log success
			log_message('info', 'banner '. $id . ' updated into affiliate_banners table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_banner_code($code = '', $aff_link = '', $scode = '')
	{
		//get the filetype
		$x = explode('.', $code['banner_file_name']);
		$ext = end($x);
		
		$url = $code['banner_file_name'];
		
		switch ($this->config->item('jrox_module_type'))
		{
			case 'admin':
				$base = $this->config->item('sts_site_ssl_admin_area') == 1 ? 'https://' : 'http://';
			break;
			
			case 'member':
				$base = $this->config->item('sts_site_ssl_members_area') == 1 ? 'https://' : 'http://';
			break;
			
			case 'public':
				$base = $this->config->item('sts_site_ssl_public_area') == 1 ? 'https://' : 'http://';
			break;
		}
		
		$url_base = $this->config->item('jrox_module_type') == 'public' ? _public_url() : base_url();
		
		if ($scode == 'code')
		{
			$url_base = _public_url(false);
		}
		
		if ($code['use_external_image'] == 0)
		{
			$url = $url_base . 'images/' . $this->config->item('images_banners_dir') . '/' . $code['banner_file_name'];	
		}
		
		//check if the ext is flash
		if ($ext == 'swf')
		{
			$a = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="' . $base . 'download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0"  width="' . $code['banner_width'] . '" height="' . $code['banner_height'] . '">
          <param name="movie" value="' . $url . '?JROX_URL='.$aff_link.'" />
          <param name="quality" value="high" />
		  <param name="wmode" value="opaque"> 
          <embed src="' . $url . '?JROX_URL='.$aff_link.'" quality="high" pluginspage="' . $base . 'www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"  width="' . $code['banner_width'] . '" height="' . $code['banner_height'] . '"></embed>
        </object>
';
		}
		else
		{
			$a = '<img src="' . $url . '" width="' . $code['banner_width'] . '" height="' . $code['banner_height'] . '" border="0" ' . $this->_no_follow() .' />';
		}
		
		return $a;
	}
	
	// ------------------------------------------------------------------------
	
	function _no_follow()
	{
		if (defined('ENABLE_NO_FOLLOW'))
		{
			return 'rel="nofollow"';	
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _get_banner_imp_details($id = '')
	{
		
		$this->db->join('programs', 'programs.program_id = affiliate_banners.program_id', 'left');
		$this->db->join('affiliate_groups', 'affiliate_groups.group_id = programs.group_id', 'left');
		$this->db->where('id', $id);	
		
		$query = $this->db->get('affiliate_banners');	
		
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

	function _get_banner_details($id = '')
	{
		//get the data from banners table
		$this->db->where('id', $id);
		$query = $this->db->get('affiliate_banners');
		
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
	
	function _get_banners($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $members = false, $mid = '', $pid = '')
	{
		//get all the article_ads from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_bnr_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_bnr_column');
		
		if ($members == true && !empty($mid))
		{
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_banners') . '.*,
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_banners') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'banners\'
					AND member_id = \'' . $mid . '\') as clicks,';
					
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == 1)
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_banners') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'banners\'
					AND member_id = \'' . $mid . '\') as commissions,
				
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_banners') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'banners\'
					AND member_id = \'' . $mid . '\') as sales';
			}
			else
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_banners') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'banners\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as commissions,
				
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_banners') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'banners\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as sales';
			}		
			
					
			$sql .= ' FROM ' . $this->db->dbprefix('affiliate_banners') . '
					WHERE status = \'1\'';
			
			if (!empty($pid))
			{
				$sql .= ' AND program_id = 	\'' . $pid . '\'';
			}
			
			$sql .= ' ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		}
		else
		{
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_banners') . '.*,
					
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_banners') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'banners\') as clicks,
					
					(SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_banners') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'banners\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_banners') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'banners\') as sales
					
					FROM ' . $this->db->dbprefix('affiliate_banners') . '
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
	
	function _generate_tools($pid = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $mid = '', $num_options = '')
	{
		//get the affiliate tools and put in the array
		$rows = $this-> _get_banners($limit, $offset, 'sort_order', 'ASC', true, $mid, $pid);
		
		$a['tool_rows'] = array();
		
		if (!empty($rows))
		{
			
			foreach ($rows as $v)
			{
				$v['s_commissions'] = format_amounts($v['commissions'], $num_options);
				$v['s_sales'] = format_amounts($v['sales'], $num_options);
				$v['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				
				$v['s_title'] = $v['name'];
				
				//format the affiliate link
				$afflink = _public_url() . AFF_TOOLS_ROUTE . '/' . $this->session->userdata('m_username') . '/tool/banners/' . $v['id'];
				
				if ($v['use_external_image'] == 0)
				{
					$img_link = $v['banner_file_name'];
				}
				else
				{
					$img_link = _public_url() . '/images/banners/' . $v['banner_file_name'];
				}
				
				$image = explode('.', $v['banner_file_name']);	
				$ext = end($image);
				
				$img_code = $this->_generate_banner_code($v, $afflink, 'code');
				
				if ($ext == 'swf')
				{
					$v['p_tool_code'] = $img_code;
					$code = $img_code . '<img src="' . base_url() . $this->config->slash_item('index_page').'track/imp/banners/'. $mid . '_' . $v['id'] .'" border="0" width="1" height="1" />';
					$v['tool_code'] = htmlentities($code, ENT_QUOTES, $this->config->item('charset'));	
				}
				else
				{
					$v['p_tool_code'] = '<a href="' . $afflink . '" target="_blank" ' . $this->_no_follow() .'>' . $img_code . '</a>';
					$code = '<a href="' . $afflink . '" ' . $this->_no_follow() .'>' . $img_code . '</a><img src="' . base_url() . $this->config->slash_item('index_page') . 'track/imp/banners/'. $mid . '_' . $v['id'] .'" border="0" width="1" height="1" />';
					$v['tool_code'] = htmlentities($code,  ENT_QUOTES, $this->config->item('charset'));
				}
				
				
				array_push($a['tool_rows'], $v);
			}		
		}
		
		//set the title and message body
		$a['subject_title'] = $this->lang->line('banner_name');
		$a['body_title'] = $this->lang->line('banner');
		
		$sql = 'WHERE status = \'1\'';
		
		if (!empty($pid))
		{
			$sql .= ' AND program_id = 	\'' . $pid . '\'';
		}
		
		$a['row_count'] = $this->db_validation_model->_get_count('affiliate_banners', $sql);
		$a['use_pagination'] = true;
		$a['template'] = 'tpl_members_general_marketing_tools';
		
		return $a;
	}
	
	// ------------------------------------------------------------------------	
}
?>