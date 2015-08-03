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
| FILENAME - module_affiliate_marketing_page_peel_ads_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for page_peel ads
|
*/

class Module_Affiliate_Marketing_Page_Peel_Ads_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$delete = 'DROP TABLE IF EXISTS ' . $this->db->dbprefix('affiliate_page_peel_ads') . ';';
		
		$query = $this->db->query($delete);
		
		$sql = 'CREATE TABLE IF NOT EXISTS ' . $this->db->dbprefix('affiliate_page_peel_ads') . ' (		  
				  id int(10) NOT NULL auto_increment,
				  status enum(\'0\',\'1\') NOT NULL default \'0\',
				  page_peel_ad_name varchar(255) NOT NULL default \'\',
				  page_peel_ad_small_image varchar(255) NOT NULL default \'\',
				  page_peel_ad_large_image varchar(255) NOT NULL default \'\',
				  enable_redirect enum(\'0\',\'1\') NOT NULL default \'0\',
				  redirect_custom_url varchar(255) NOT NULL default \'\',
				  sort_order int(10) NOT NULL default \'0\',
				  notes text NOT NULL,
				  program_id int(10) NOT NULL default \'0\',
				  PRIMARY KEY  (id),
				  KEY page_peel_ad_name (page_peel_ad_name)
				) AUTO_INCREMENT=1;';
				
		$query = $this->db->query($sql);

		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _add_page_peel_ad()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$data['sort_order'] = '1';
		
		//insert into db
		if (!$this->db->insert('affiliate_page_peel_ads', $data))
		{
			show_error($this->lang->line('could_not_add_page_peel_ad'));
			
			//log error
			log_message('error', 'Could not insert page_peel_ad into page_peel_ads table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			//log success
			log_message('info', 'page_peel_ad '. $id . ' inserted into page_peel_ads table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------	
	
	function _edit_page_peel_ad($id = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//insert into db
		$this->db->where('id', $id);
		if (!$this->db->update('affiliate_page_peel_ads', $data))
		{
			show_error($this->lang->line('could_not_update_page_peel_ad'));
			
			//log error
			log_message('error', 'Could not update text link in affiliate_page_peel_ads table');
			
			return false;
		}
		else
		{	
			//log success
			log_message('info', 'text link '. $id . ' updated into affiliate_page_peel_ads table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------

	function _get_page_peel_ad_details($id = '')
	{
		//get the data from page_peel_ads table
		$this->db->where('id', $id);
		$this->db->join('programs', 'affiliate_page_peel_ads.program_id = programs.program_id', 'left');
		$query = $this->db->get('affiliate_page_peel_ads');
		
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
	
	function _generate_page_peel_ad_code($code = '', $public = false, $url = '')
	{
		return '<script src="' . base_url() . 'js/type/page_peel_ads/' . $code['id'] . '/' . $this->session->userdata('m_username') . '" type="text/javascript"></script>';
	}
	
	// ------------------------------------------------------------------------
	
	function _generate_page_peel_js($code = '', $public = false, $url = '')
	{
		if ($public == false)
		{
			$url = 	$code['enable_redirect'] == 1 ? $code['redirect_custom_url'] :  $code['url_redirect'];
		}
		
		$page_peel = 'var jroxpeel = new Object();

jroxpeel.ad_url = escape(\'' . $url . '\');
jroxpeel.small_path = \'' . base_url() . 'images/misc/small.swf\';
jroxpeel.small_image = escape(\'' . base_url() . 'images/banners/' . $code['page_peel_ad_small_image'] . '\');
jroxpeel.small_width = \'100\';
jroxpeel.small_height = \'100\';
jroxpeel.small_params = \'ico=\' + jroxpeel.small_image;

jroxpeel.big_path = \'' . base_url() . 'images/misc/large.swf\';
jroxpeel.big_image = escape(\'' . base_url() . 'images/banners/' . $code['page_peel_ad_large_image'] . '\');
jroxpeel.big_width = \'650\';
jroxpeel.big_height = \'650\';
jroxpeel.big_params = \'big=\' + jroxpeel.big_image + \'&ad_url=\' + jroxpeel.ad_url;

function sizeup987(){
	document.getElementById(\'cornerBig\').style.top = \'0px\';
	document.getElementById(\'cornerSmall\').style.top = \'-1000px\';
}

function sizedown987(){
	document.getElementById("cornerSmall").style.top = "0px";
	document.getElementById("cornerBig").style.top = "-1000px";
}

jroxpeel.putObjects = function () {
// <cornerSmall>
document.write(\'<div id="cornerSmall" style="position:absolute;width:\'+ jroxpeel.small_width +\'px;height:\'+ jroxpeel.small_height +\'px;z-index:9999;right:0px;top:0px;">\');
// object
document.write(\'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"\');
document.write(\' codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0"\');
document.write(\' id="cornerSmallObject" width="\'+jroxpeel.small_width+\'" height="\'+jroxpeel.small_height+\'">\');
// object params
document.write(\' <param name="allowScriptAccess" value="always"/> \');
document.write(\' <param name="movie" value="\'+ jroxpeel.small_path +\'?\'+ jroxpeel.small_params +\'"/>\');
document.write(\' <param name="wmode" value="transparent" />\');
document.write(\' <param name="quality" value="high" /> \');
document.write(\' <param name="FlashVars" value="\'+jroxpeel.small_params+\'"/>\');
// embed
document.write(\'<embed src="\'+ jroxpeel.small_path + \'?\' + jroxpeel.small_params +\'" name="cornerSmallObject" wmode="transparent" quality="high" width="\'+ jroxpeel.small_width +\'" height="\'+ jroxpeel.small_height +\'" flashvars="\'+ jroxpeel.small_params +\'" allowscriptaccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>\');
document.write(\'</object></div>\');
document.write(\'<\/script>\');
// </cornerSmall>
// <cornerBig>
document.write(\'<div id="cornerBig" style="position:absolute;width:\'+ jroxpeel.big_width +\'px;height:\'+ jroxpeel.big_height +\'px;z-index:9999;right:0px;top:0px;">\');
// object
document.write(\'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"\');
document.write(\' codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0"\');
document.write(\' id="cornerBigObject" width="\'+ jroxpeel.big_width +\'" height="\'+ jroxpeel.big_height +\'">\');
// object params
document.write(\' <param name="allowScriptAccess" value="always"/> \');
document.write(\' <param name="movie" value="\'+ jroxpeel.big_path +\'?\'+ jroxpeel.big_params +\'"/>\');
document.write(\' <param name="wmode" value="transparent"/>\');
document.write(\' <param name="quality" value="high" /> \');
document.write(\' <param name="FlashVars" value="\'+ jroxpeel.big_params +\'"/>\');
// embed
document.write(\'<embed src="\'+ jroxpeel.big_path + \'?\' + jroxpeel.big_params +\'" id="cornerBigEmbed" name="cornerBigObject" wmode="transparent" quality="high" width="\'+ jroxpeel.big_width +\'" height="\'+ jroxpeel.big_height +\'" flashvars="\'+ jroxpeel.big_params +\'" swliveconnect="true" allowscriptaccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>\');
document.write(\'</object></div>\');
// </cornerBig>
setTimeout(\'document.getElementById("cornerBig").style.top = "-1000px";\',1000);
}
jroxpeel.putObjects();';
				
		return $page_peel;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_page_peel_ads($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $members = false, $mid = '', $pid = '')
	{
		//get all the page_peel_ads from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_bnr_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_bnr_column');
		
		if ($members == true && !empty($mid))
		{
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_page_peel_ads') . '.*,
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_page_peel_ads') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'page_peel_ads\'
					AND member_id = \'' . $mid . '\') as clicks,';
					
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == 1)
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_page_peel_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'page_peel_ads\'
					AND member_id = \'' . $mid . '\') as commissions,
					
					(SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_page_peel_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'page_peel_ads\'
					AND member_id = \'' . $mid . '\') as sales';
			}
			else
			{
				$sql .= ' (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_page_peel_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'page_peel_ads\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as commissions,
				
				   (SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_page_peel_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'page_peel_ads\'
					AND member_id = \'' . $mid . '\'
					AND comm_status != \'pending\') as sales';
			}		
			
					
			$sql .= ' FROM ' . $this->db->dbprefix('affiliate_page_peel_ads') . '
					WHERE status = \'1\'';
					
			if (!empty($pid))
			{
				$sql .= ' AND program_id = 	\'' . $pid . '\'';
			}
			
			$sql .= 'ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		}
		else
		{
		
			$sql = 'SELECT ' . $this->db->dbprefix('affiliate_page_peel_ads') . '.*,
					
					(SELECT COUNT(*) from ' . $this->db->dbprefix('traffic') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_page_peel_ads') . '.id = ' . $this->db->dbprefix('traffic') . '.tool_id 
					AND ' . $this->db->dbprefix('traffic') . '.tool_type = \'text_links\') as clicks,
					
					(SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_page_peel_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'page_peel_ads\') as commissions,
					
					 (SELECT SUM(sale_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('affiliate_page_peel_ads') . '.id = ' . $this->db->dbprefix('commissions') . '.tool_id 
					AND ' . $this->db->dbprefix('commissions') . '.tool_type = \'page_peel_ads\') as sales
					
					FROM ' . $this->db->dbprefix('affiliate_page_peel_ads') . '
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
		$data = $this->aff->_get_tool_details('page_peel_ads', $id);
		
		$data['s_title'] = $data['page_peel_ad_name'];
				
		//format the affiliate link
		$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/page_peel_ads/' . $data['id'];
				
		$data['tool_code'] = $this->init->_parse_member_data($this->_generate_page_peel_ad_code($data, true));
		$data['tool_code'] = htmlentities($data['tool_code'], ENT_QUOTES, $this->config->item('charset'));
		
		//set the title and message body
		$data['subject_title'] = $this->lang->line('subject');
		$data['body_title'] = $this->lang->line('page_peel_ad');
		$data['template'] = 'tpl_members_marketing_preview_code';
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_tools($pid = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $mid = '', $num_options = '')
	{
		//get the affiliate tools and put in the array
		$rows = $this-> _get_page_peel_ads($limit, $offset, 'sort_order', 'ASC', true, $mid, $pid);
		
		$a['tool_rows'] = array();
		
		if (!empty($rows))
		{
			$a['tool_rows'] = array();
		
			foreach ($rows as $v)
			{
				$v['s_commissions'] = format_amounts($v['commissions'], $num_options);
				$v['s_sales'] = format_amounts($v['sales'], $num_options);
				$v['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				
				$v['name'] = $v['page_peel_ad_name'];
				
				//format the affiliate link
				$link = _public_url() . _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->session->userdata('m_username') . '/tool/page_peel_ads/' . $v['id'];

				$v['tool_code'] = htmlspecialchars($this->init->_parse_member_data($this->_generate_page_peel_ad_code($v, true)));
				
				array_push($a['tool_rows'], $v);
			}		
		}
		
		//set the title and message body
		$a['subject_title'] = $this->lang->line('subject');
		$a['body_title'] = $this->lang->line('page_peel_ad');
		
		$sql = 'WHERE status = \'1\'';
		
		if (!empty($pid))
		{
			$sql .= ' AND program_id = 	\'' . $pid . '\'';
		}
		
		$a['row_count'] = $this->db_validation_model->_get_count('affiliate_page_peel_ads', $sql);
		$a['use_pagination'] = true;
		$a['template'] = 'tpl_members_general_marketing_tools4';
		
		return $a;
	}
	
	// ------------------------------------------------------------------------	
}
?>