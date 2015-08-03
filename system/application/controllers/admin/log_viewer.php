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
| FILENAME - log_viewer.php
| -------------------------------------------------------------------------     
| 
*/

class Log_Viewer extends Admin_Controller {
	
    function __construct()
    {
       parent::__construct();

		$this->load->model('settings_model');
    }
	
	// ------------------------------------------------------------------------
	
	function index()
	{  
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->helper('file');
		
		$this->log_path = ($data['log_path'] != '') ? $data['log_path'] : BASEPATH.'logs/';
		
		if (!empty($_POST['logs']))
		{
			$date = $_POST['logs'];
		}
		else
		{
			$date = 'log-'.  date('Y-m-d') .EXT;
		}
		
		$data['page_title'] = $this->lang->line('view_logs') . ' - ' . $date;
		
		$filepath = $this->log_path . $date;
		
		$filenames = get_dir_file_info($this->log_path);
		
		$data['log_files'] = array();
		
		$keys = array();
		
		$values = array();
		
		foreach ($filenames as $k => $v)
		{
			if ($k != 'index.html')
			{
				array_push($keys, $k);
			}
		}
		
		$data['log_files'] = combine_array($keys, $keys);
		
		$string = read_file($filepath);
		
		$s = explode("\n", $string);
		
		$data['logs'] = array();
		
		foreach ($s as $value)
		{
			$a = explode ('#', $value);

			if (count($a) > 2)
			{
				array_push($data['logs'], $a);
			}
			
		}

		load_admin_tpl('admin', 'tpl_adm_manage_log_viewer', $data);	
	}
}
?>