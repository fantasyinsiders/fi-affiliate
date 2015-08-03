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
| FILENAME - forms_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing forms
|
*/

class Forms_Model extends CI_Model {	
	
	function _show_field_name($name = '', $desc = '')
	{
		if (!empty($desc))
		{
			return $desc;
		}
		
		return $this->lang->line($name);
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_registration_fields($id = '')
	{
	
		$this->db->where('program_id', $id);
		$query = $this->db->get('programs_form_fields');
		
		if ($query->num_rows() > 0)
		{
			$fields = array();
			
			foreach ($query->row_array() as $k => $v)
			{
				if ($v != '0')
				{
					if ($k != 'program_id')
					{
						$fields[$k] = $v;
					}
				}
			}
			
			return $fields;
		}
		
		return false;
		
	}
	
	// ------------------------------------------------------------------------
	
	function _update_program_form_fields($id = '')
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//set required fields
		$data['enable_fname'] = '1';
		$data['enable_primary_email'] = '1';

		$this->db->where('program_id', $id);
		
		if (!$this->db->update('programs_form_fields', $data))
		{
			show_error($this->lang->line('could_not_update_program'));
			
			//log error
			log_message('error', 'Could not update program form fields ' . $id . 'in programs table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'program ID '. $id . ' updated in programs table');
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _get_program_form_fields($id = '')
	{
		$this->db->where('program_id', $id);		
		$query = $this->db->get('programs_form_fields');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}	
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _generate_form_values($id = '', $name = '', $value = '')
	{
		switch ($name)
		{
			case 'billing_country':
			case 'shipping_country':
			case 'payment_country':

				$value = !empty($value) ?  $value : $this->config->item('sts_site_default_country');
			
				return form_dropdown('form_field_values-' . $id, $this->config->item('country_options'), $value);
			break;
			
			default:
				return '<input name="form_field_values-' . $id . '" type="text" id="form_field_values-' . $id . '" value="' . $value . '" class="popup-form-input" />';
			break;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _get_forms($type = '', $status = '')
	{

		if (!empty($type))
		{
			$this->db->where('form_type', $type);
		}
		
		if (!empty($status))
		{
			$this->db->where('status', $status);
		}
		
		$this->db->order_by('sort_order', 'ASC');
				
		$query = $this->db->get('form_fields');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------	
}
?>