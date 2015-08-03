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
| FILENAME - scaled_commissions.php
| -------------------------------------------------------------------------     
|
*/

class Scaled_Commissions extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('commissions_model');
		
		$this->config->set_item('menu', 'c');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'scaled_commissions/view_scaled_commissions');
	}
	
	// ------------------------------------------------------------------------
	
	function view_scaled_commissions()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['scaled_commissions'] = $this->commissions_model->_get_scaled_commissions();
		
		$data['levels'] = array(); 
		
		for ($i=1; $i<=10; $i++)
        {
      		$data['levels'][$i] = $i;	
        }
		
		
		load_admin_tpl('admin', 'tpl_adm_manage_scaled_commissions', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function delete_scaled_commission()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->_check_discounts() == true)
		{
			$this->commissions_model->_delete_scaled_commission((int)$this->uri->segment(4));
			
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));	
		}
				
		redirect(admin_url() . 'scaled_commissions/view_scaled_commissions');	
	}
	
	// ------------------------------------------------------------------------
	
	function update_scaled_commissions()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->_check_discounts() == true)
		{
			$this->commissions_model->_update_scaled_commissions($_POST);
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));	
		}
		
		echo '<script>window.location = "' . admin_url().'scaled_commissions/view_scaled_commissions"</script>';	
		exit();		
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_discounts()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		foreach ($data as $k => $v)
		{
			if (preg_match('/min_amount-*/', $k)) 
			{					
				if (!empty($v) && !is_numeric($v))
				{
					echo '<div class="alert alert-danger animated shake capitalize hover-msg">' . $this->lang->line('all_amounts_must_be_numeric') . '</div>';
					exit();
				}
			}  
			
			if (preg_match('/max_amount-*/', $k)) 
			{					
				if (!empty($v) && !is_numeric($v))
				{
					echo '<div class="alert alert-danger animated shake capitalize hover-msg">' . $this->lang->line('all_amounts_must_be_numeric') . '</div>';
					exit();
				}
			}  
			
			if (preg_match('/comm_amount-*/', $k)) 
			{					
				if (!empty($v) && !is_numeric($v))
				{
					echo '<div class="alert alert-danger animated shake capitalize hover-msg">' . $this->lang->line('all_amounts_must_be_numeric') . '</div>';
					exit();
				}
			}  
		}
		
		return true;	
	}
}
?>