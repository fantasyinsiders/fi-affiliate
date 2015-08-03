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
| FILENAME - downline.php
| -------------------------------------------------------------------------     
| 
*/

class Downline extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('downline_model');
	}
	
	// ------------------------------------------------------------------------	
	
	function index()
	{
		redirect(admin_url() . 'downline/view_downline');
	}

	
	// ------------------------------------------------------------------------	
	
	function view_downline()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($data['sts_site_enable_downline_cache'] == 1)
		{
			$this->output->cache($data['sts_site_enable_downline_cache_minutes']);
		}
		
		$this->load->helper('country');
		
		if ((int)$this->uri->segment(4) > 0)
		{
			$member = $this->downline_model->_get_sponsor_details((int)$this->uri->segment(4));
			
			$data['member_name'] = $member[0]['fname'] . ' ' . $member[0]['lname'];
			
			$data['sponsor_id'] = !empty($member[0]['sponsor_id']) ? $member[0]['sponsor_id'] : '0';
			
			$data['member_photo'] = !empty($member[0]['photo_file_name']) ? base_url() . 'images/' . $this->config->item('images_members_dir') . '/' . $members[0]['photo_file_name'] : base_url() . 'images/misc/downline_main_1.jpg';
		}
		else
		{
			$data['member_name'] = $this->config->item('sts_site_name');
			
			$data['member_photo'] = base_url() . 'images/misc/world.png';
		}
		
		if ($this->config->item('sts_admin_use_google_visualization_api_admin'))
		{
			$mdata = $this->downline_model->_generate_downline((int)$this->uri->segment(4), true, 'email');
		
			$data['total_users' ] = $mdata['total_users'];
			
			$data['downline_table'] = $mdata['results'];
			
			$data['levels'] = $mdata['levels'];
		
			if ((int)$this->uri->segment(4) > '0')
			{
				$data['rows'] = '[{v:\'' . $member[0]['member_id'] . '\', f:\'' . '<div><img src="' . $data['member_photo'] . '" /><br />' . $data['member_name'] . '<div>\'}, \'\', \'' . $member[0]['username']  . '\'],'
			;
			}
			else
			{
				$data['rows'] = '[{v:\'0\', f:\'' . '<div style="font-size: 15px; font-weight: bold"><img src="' . $data['member_photo'] . '" /><br />' . $this->lang->line('organization_top') . '<div>\'}, \'\', \'\'],';	
			}
			
			foreach ($mdata['results'] as $v)
			{
				$icon = $v['status'] == '1' ? 'downline_1' : 'downline_inactive_1';
				
				$data['rows'] .= '[{v:\'' . $v['member_id'] . '\', f:\'' . '<div><a href="' . admin_url() .'downline/view_downline/' . $v['member_id'] . '"><img src="' . base_url() . 'images/misc/' . $icon . '.gif' . '" /><br />' . $v['fname'] . ' ' . $v['lname'] . '</a><div>\'}, \'' . $v['sponsor_id'] . '\', \'' . $v['username'] . '\'],';
			}
			
			$template = 'tpl_adm_view_downline2';
		}
		else
		{
			$mdata = $this->downline_model->_generate_downline((int)$this->uri->segment(4), true);
		
			$data['total_users' ] = $mdata['total_users'];
		
			$data['downline_table'] = $mdata['results'];
		
			$data['levels'] = $mdata['levels'];
			
			$template = 'tpl_adm_view_downline';
		}
		
		load_form('admin', $template, $data);	
	}
}
?>
