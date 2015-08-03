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
| FILENAME - init_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for the website initialization
|
*/

class Init_Model extends CI_Model {

	function _initialize($module = '', $func = '')
	{		
		ini_set('upload_max_filesize', $this->config->item('sts_site_upload_max_filesize'));
		ini_set('post_max_size', $this->config->item('sts_site_upload_max_filesize'));
		ini_set('max_input_time', $this->config->item('sts_site_max_execution_time'));
		ini_set('max_execution_time', $this->config->item('sts_site_max_execution_time'));
		
		//get languages
		$data['languages'] = $this->_get_languages();
		
		//get language text
		$data['text'] = $this->_get_text($module, $func);
			
		//get referral config
		$data['aff_cfg'] = $this->_init_referrals($module, $func);
		
		$this->_check_maintenance_mode($module, $func);
		
		$this->_check_browser();
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
	
	function _set_sale_program($row = '')
	{ 
		foreach ($row as $k => $v)
		{
			$this->config->set_item('prg_' . $k, $v);
		}
		
		//set the config
		if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
		{
			$this->config->set_item('default_theme', $row['default_theme']);
			$this->config->set_item('sts_site_enable_custom_login', $row['enable_custom_login']);
			$this->config->set_item('sts_site_url_redirect_login', $row['url_redirect_login']);
			$this->config->set_item('sts_site_enable_custom_signup', $row['enable_custom_signup']);
			$this->config->set_item('sts_site_url_redirect_signup', $row['url_redirect_signup']);
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _set_default_program($name = '', $admin = false, $id = false)
	{
		//set the default program
		if ($this->config->item('sts_site_showcase_multiple_programs') == 1 || $admin == true)
		{
			$this->db->where('program_id', '1');
		}	
		else
		{
			if ($id == true)
			{
				$this->db->where('program_id', $name);
			}
			else
			{
				$this->db->where('signup_link', $name);
			}
		}

		$query = $this->db->get('programs');
		
		if ($query->num_rows() > 0)
		{			
			$row = $query->row_array();

			foreach ($row as $k => $v)
			{
				$this->config->set_item('prg_' . $k, $v);
			}
			
			//set the config
			if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
			{
				$this->config->set_item('default_theme', $row['default_theme']);
				$this->config->set_item('sts_site_enable_custom_login', $row['enable_custom_login']);
				$this->config->set_item('sts_site_url_redirect_login', $row['url_redirect_login']);
				$this->config->set_item('sts_site_enable_custom_signup', $row['enable_custom_signup']);
				$this->config->set_item('sts_site_url_redirect_signup', $row['url_redirect_signup']);
			}
		}
		else
		{
			redirect(PROGRAM_ROUTE);
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_browser()
	{
		if ($this->agent->browser() == 'Internet Explorer' && $this->agent->version() < 8)
		{
			$this->config->set_item('design_disable_dropdown_js', true);		
		}
	}
	
	// ------------------------------------------------------------------------	
	
	
	function _check_maintenance_mode($module = '', $func = '')
	{
		if ($module == 'Maintenance')
		{
			return;
		}

		if ($this->config->item('sts_site_disable_login') == 1)
		{
			if ($this->session->userdata('adminid') && $this->session->userdata('ll_type'))
			{
				return;
			}
			else
			{
				redirect('maintenance');
				exit();
			}
		}	
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_latest_articles()
	{
		//get latest posts
		$sql = 'SELECT ' . $this->db->dbprefix('content_articles') . '.*, ' . $this->db->dbprefix('content_categories') . '.*
					FROM ' . $this->db->dbprefix('content_articles') . '
					LEFT JOIN ' . $this->db->dbprefix('content_categories') . ' 
					ON ' . $this->db->dbprefix('content_articles') . '.category_id = ' . $this->db->dbprefix('content_categories') . '.category_id

					WHERE ' . $this->db->dbprefix('content_categories') . '.category_type = \'custom\'
					AND date_published <=' . _generate_timestamp() . '
					AND ' . $this->db->dbprefix('content_articles') . '.status = \'1\' 
					AND ' . $this->db->dbprefix('content_articles') . '.content_type = \'1\' 
					GROUP BY ' . $this->db->dbprefix('content_articles') . '.article_id
					ORDER BY ' . $this->db->dbprefix('content_articles') . '.article_id DESC LIMIT 0,10';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows > 0)
		{
			//lets format the data
			$total = array();
			foreach ($query->result_array() as $row)
			{
				$row['content_title_url'] = url_title($row['content_title']);
				$row['latest_article_title'] = $row['content_title'];
				$row['content_title_url'] = url_title($row['content_title']);
				$row['content_date'] = _show_date($row['date_published']);
				$row['content_link_url'] =_public_url() . $this->config->slash_item('index_page') . CONTENT_ROUTE . '/article/' . $row['article_id'] . '/' . $row['content_title_url'];
				array_push($total, $row);
			}
			
			return $total;
		}
		
		
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
		
	function _parse_member_data($data = '')
	{
		$data = str_replace('{site_name}', $this->config->item('sts_site_name'), $data);
		$data = str_replace('{login_url}', base_url(), $data);
		$data = str_replace('{site_link}', base_url(), $data);
		
		$data = str_replace('{affiliate_link}', _get_aff_link($this->session->userdata('m_username')), $data);
		
		$data = str_replace('{member_id}', $this->session->userdata('userid'), $data);
		$data = str_replace('{username}', $this->session->userdata('m_username'), $data);
		$data = str_replace('{fname}', $this->session->userdata('m_fname'), $data);
		$data = str_replace('{lname}', $this->session->userdata('m_lname'), $data);
		$data = str_replace('{primary_email}', $this->session->userdata('m_email'), $data);
		
		//replace custom fields
		for ($i = 1; $i<=20; $i++)
		{
			$data = str_replace('{custom_field_' . $i . '}', $this->session->userdata('m_program_custom_field_' . $i), $data);	
		}

		foreach ($this->session->userdata as $k => $v)
		{
			if (!is_array($v))
			{
				$data = str_replace('{' . $k . '}', $this->session->userdata($k), $data);	
			}
		}
				
		//replace other fields
		foreach ($this->session->userdata as $k => $v)
		{
			if (preg_match('/m_sponsor_*/', $k))
			{
				$str = explode('m_sponsor_', $k);
				
				$data = str_replace('{sponsor_' . $str[1] . '}', $this->session->userdata($k), $data);		
			}
		}
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
	
	function _redirect_home()
	{
		$url = $this->config->item('layout_design_custom_home_page_redirect');
		if (!empty($url))
		{
			redirect_301($this->config->item('layout_design_custom_home_page_redirect'), true);
			exit();
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _init_referrals($module = '', $func = '')
	{
	
		//first check if the session cookie is set for the affiliate
		if ($this->session->userdata('jrox_site_referral_regular'))
		{
			$get = $this->session->userdata('jrox_site_referral_regular');
		}
		else
		{
			//check if there is an affiliate referral via the cookie
			$get = get_cookie($this->config->item('aff_cookie_name'));
		}
		
		$data['show_affiliate_username'] = '0';	
		
		if (!empty($get))
		{
			$user = explode('-', $get);
			
			if (count($user) > 1)
			{
				$data['sponsor_referring_username'] = $user[2];
				$data['sponsor_referring_userid'] = $user[0];
			
				//get necessary config data
				$data['show_affiliate_username'] = $this->config->item('sts_affiliate_show_name');	
				$data['show_affiliate_intro_text'] = _check_language_translation($this->config->item('sts_affiliate_intro_text'));
			}
		}
		
		//check for IP address tracking
		if (empty($get) && $this->config->item('sts_tracking_enable_ip_address') == 1)
		{
			//check the database for the user's IP
			$this->db->where('ip_address', $this->input->ip_address());
			//$this->db->where('user_agent', $this->input->user_agent());
			$this->db->join('members', 'tracking_log.member_id = members.member_id', 'left');
			
			$query = $this->db->get('tracking_log');
		
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array();
				
				$data['sponsor_referring_username'] = $row['username'];
				$data['sponsor_referring_userid'] = $user['member_id'];
			
				//get necessary config data
				$data['show_affiliate_username'] = $this->config->item('sts_affiliate_show_name');	
				$data['show_affiliate_intro_text'] = _check_language_translation($this->config->item('sts_affiliate_intro_text'));
			}
		}
	
		return $data;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_text($module = '', $func = '')
	{	
		//set the global text
		$data = array(	'content_route'	=> $this->config->slash_item('index_page') . CONTENT_ROUTE,
						'faq_route'	=> $this->config->slash_item('index_page') . FAQ_ROUTE,
						'members_route'	=> $this->config->slash_item('index_page') . MEMBERS_ROUTE,
						'program_route'	=> $this->config->slash_item('index_page') . PROGRAM_ROUTE,
					 );
		
		//set the login text
		if ($this->session->userdata('userid'))
		{
			$data['mlogin'] = '1';
			$data['member_name'] = $this->session->userdata('fname');
			$data['lang_hello'] = $this->lang->line('hello');
		}
		else
		{
			$data['mlogin'] = '0';
		}
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_languages()
	{
		$this->db->where('status', '1');
		$query = $this->db->get('languages');
		
		if ($query->num_rows() > 1)
		{
			return $query->result_array();
		}
		
		return false;
	}

	// ------------------------------------------------------------------------	

}
?>