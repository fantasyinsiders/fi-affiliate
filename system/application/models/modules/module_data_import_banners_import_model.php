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
| FILENAME - module_data_import_banners_import_model.php
| -------------------------------------------------------------------------     
| 
| This controller file is for functions for importing banners
|
*/


class Module_Data_Import_Banners_Import_Model extends CI_Model {

	function _install_jrox_module($id = '')
	{	
		$config = array(
							'settings_key'	=>	'module_data_import_banners_import_activate_banners',
							'settings_value'	=>	'1',
							'settings_module'	=>	'data_import',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'3',
							'settings_function'	=>	'yes_no',
							);
		
		//insert into settings table
		if ($this->db->insert('settings', $config))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _import_data()
	{
		$this->load->helper('directory_helper');
		
		$banners = directory_map('images/banners');
		
		if (is_array($banners))
		{
			$total = 0;
			
			foreach ($banners as $b)
			{
				if ($b != 'index.html')
				{
					$size = @getimagesize(PUBPATH . '/images/banners/' . $b);
					
					$name = $b;
					$new = url_title($b);
					if (rename(PUBPATH . '/images/banners/' . $b, PUBPATH . '/images/banners/' . $new))
					{
						$name = $new;	
					}
					
					$this->db->where('banner_file_name', $name);
					$query = $this->db->get('affiliate_banners');
					
					if ($query->num_rows() < 1)
					{
						$insert = array(	'program_id' => '1',
											'status' => $this->config->item('module_data_import_banners_import_activate_banners') == 1 ? '1' : '0',
											'name' => $name,
											'banner_width' => !empty($size[0]) ? $size[0] : '',
											'banner_height' => !empty($size[1]) ? $size[1] : '',
											'banner_file_name' => $name,
											'sort_order' => '1',
											'notes' => 'imported',	
										);
					
						$this->db->insert('affiliate_banners', $insert);
					}
					
					$total++;
				}
			}
			
			return $total;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_email($id = '')
	{		
		$this->db->where('primary_email',$id);
		$cquery = $this->db->get('banners');
		
		if ($cquery->num_rows() > 0)
		{
			$cn = $cquery->row_array();
			
			return false;
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	function _string_check($id = '')
	{
		$id = str_replace('"', '', $id);
		
		return $id;
	}	
	
	// ------------------------------------------------------------------------
	
	function _check_country($id = '')
	{
		//get the country ID	
		$country_id = '223';
		
		$this->db->where('country_name',$id);
		$cquery = $this->db->get('countries');
		
		if ($cquery->num_rows() > 0)
		{
			$cn = $cquery->row_array();
			
			$country_id = $cn['country_id'];
		}
		
		return $country_id;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_sponsor_id($id = '')
	{
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_username($id = '')
	{
		
		$this->db->where('username', $id);
		$query = $this->db->get('banners');
		
		if ($query->num_rows() > 0)
		{
			return _generate_random_string(6);
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_password($id = '')
	{
		if (empty($id))
		{
			$id = _generate_random_string(6);
		}
		
		switch ($this->config->item('banners_password_function'))
		{
			case 'sha1':
			
				$id = sha1($id);
			
			break;
			
			case 'mcrypt':
				
				$id = $this->encrypt->encode($id);
				
			break;
		
			default:
			
				$id = md5($id);
			
			break;
		}

		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_numeric($id = '')
	{
		if (!is_numeric($id))
		{
			return '0';
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	
	function _format_date($id = '')
	{
		return strtotime($id);
	}
	
	
	// ------------------------------------------------------------------------
	
	
	function _check_global($id = '')
	{
		if ($id != '0' || $id != '1' || $id != '2')
		{
			return '2';
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	
	
}
?>