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
| FILENAME - downloads.php 
| -------------------------------------------------------------------------     
| 
| This controller file is for affiliate downloads
|
*/

class Downloads extends Member_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('downloads_model', 'downloads');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(site_url('members/downloads/view'));
	}
	
	// ------------------------------------------------------------------------
	
	function view()
	{
	
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		$data['languages'] = $sdata['languages'];
		
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['page_title'] = $this->lang->line('downloads');

		$sdata = $this->downloads->_get_site_downloads(MEMBERS_DOWNLOADS_PER_PAGE, $data['offset'], $data['sort_column'], $data['sort_order']);
		
		if (!empty($sdata['downloads']))
		{
			$data['rows'] = $sdata['downloads'];
		}

		$pagination = $this->db_validation_model->_set_pagination($data['uri'], 'downloads', MEMBERS_DOWNLOADS_PER_PAGE, 4, $data['sort_order'], $data['sort_column'],  $sdata['total_rows'], '', '', 'public');
		
		$data['pagination_rows'] = $pagination['rows'];
		
		$data['num_pages'] = $pagination['num_pages'];

		$this->parser->_JROX_load_view('tpl_members_downloads', 'members', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function get()
	{
		//set data array
		$data = $this->security_model->_load_config('members');
		
		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		
		//download the file
		$id = $this->uri->segment(4);
		$num = $this->uri->segment(5, 1); 
		
		//get the download details first
		$download = $this->downloads->_get_download_details($id);
		
		$location = 'download_location_' . $num;
		
		//check if this is a URL redirect first
		if(preg_match("/ftp:\/\//",$download[$location]) || preg_match("/http:\/\//",$download[$location]) || preg_match("/https:\/\//",$download[$location]))
		{
			redirect_301($download[$location], true, false);
		}
		else
		{
			$this->load->helper('download');
			$this->load->helper('file');
			$data = read_file($download[$location]);
		
			$name = basename($download[$location]);
		
			force_download($name, $data); 
		}
	}
	
	// ------------------------------------------------------------------------
	
}
?>