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
| FILENAME - settings_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for settings and configuration
|
*/

class Settings_Model extends CI_Model {	
	
	function _get_settings($type = 'settings') //get all settings from db
	{
		
		$this->db->where('settings_module' , $type);
		$this->db->order_by('settings_sort_order', 'ASC');
		$query = $this->db->get('settings');
		
		return $query->result_array();
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_settings()
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//check matrix 
		if (!defined('JAM_ENABLE_SYSTEM_FSETTINGS'))
		{
			if ($this->input->post('sts_affiliate_enable_mlm_forced_matrix') == 1)
			{
				$data['sts_affiliate_enable_mlm_forced_matrix'] = 0;
			}	
		}
		
		if ($this->_update_db($data))
		{
			//log success
			log_message('info', 'settings table updated successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_update_settings'));
			
			//log error
			log_message('error', 'Could not update settings table');
			
			return false;		
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_sub_menus($id = '')
	{
		$this->db->where('menu_parent', $id);
		$this->db->order_by('menu_sort_order', 'ASC');
		$query = $this->db->get('layout_menus');
		
		return $query->result_array();
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_db($data = '')
	{
		foreach ($data as $k => $v)
		{
			$sql = array('settings_value' => $v);

			$this->db->where('settings_key', $k);
			$this->db->update('settings', $sql);
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _insert_layout_box()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		unset ($data['type']);
		
		if (empty($data['layout_box_file_name']))
		{
			$data['layout_box_file_name'] = random_string('alnum', 16);
		}

		//update member in db	
		if (!$this->config->item('jem_set_disable_htmlentities'))
		{
			$data['layout_box_code'] = htmlentities($_POST['layout_box_code']);
		}
		
		if (!$this->db->insert('layout_boxes', $data))
		{
			//log error
			log_message('error', 'Could not insert layout box #' . $k . ' in layout_boxes table');
			return false;
		}
		
		return $this->db->insert_id();
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_box($id = '')
	{

		//delete box in db
		$this->db->where('layout_box_id', $id);
				
		if (!$this->db->delete('layout_boxes'))
		{
			//log error
			log_message('error', 'Could not insert layout box #' . $k . ' in layout_boxes table');
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_layout_box($id = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$this->db->where('layout_box_id', $id);
			
		//use layout box from pure post code			
		
		if (!$this->config->item('jem_set_disable_htmlentities'))
		{
			$data['layout_box_code'] = htmlentities($_POST['layout_box_code']);
		}
		
		if (!$this->db->update('layout_boxes', $data))
		{
			//log error
			log_message('error', 'Could not update layout box #' . $k . ' in layout_boxes table');
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_layout_box($id = '')
	{
		$this->db->where('layout_box_id' , $id);
		$query = $this->db->get('layout_boxes');
		
		return $query->result_array();
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_box_order()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		foreach ($data as $k => $v)
		{
			if (strstr($k, "left") == true) 
			{
				$id = explode("-", $k);
			
				$this->db->where('layout_box_id', $id[1]);
			
				//update member in db
					
				$data = array('layout_box_sort_order' => $v);
				
				if (!$this->db->update('layout_boxes', $data))
				{
					show_error($this->lang->line('could_not_update_layout_boxes'));
					
					//log error
					log_message('error', 'Could not update layout box #' . $k . ' in layout_boxes table');
					return false;
				}
			}
			elseif (strstr($k, "right") == true) 
			{
				$id = explode("-", $k);
			
				$this->db->where('layout_box_id', $id[1]);
			
				//update member in db
					
				$data = array('layout_box_sort_order' => $v);
				
				if (!$this->db->update('layout_boxes', $data))
				{
					show_error($this->lang->line('could_not_update_layout_boxes'));
					
					//log error
					log_message('error', 'Could not update layout box #' . $k . ' in layout_boxes table');
					return false;
				}
			}
		}
		
		 //make sure tiers are numbered sequentially
		$this->db_validation_model->_db_sort_order('layout_boxes', 'layout_box_id', 'layout_box_sort_order', 'layout_box_location = \'left\'');
		$this->db_validation_model->_db_sort_order('layout_boxes', 'layout_box_id', 'layout_box_sort_order', 'layout_box_location = \'right\'');
		
		return true;		
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_layout_boxes()
	{
		$this->db->order_by('layout_box_sort_order', 'ASC');
		$query = $this->db->get('layout_boxes');
		
		return $query->result_array();
	}
	
	// ------------------------------------------------------------------------	
	
	function _add_menu_maker($data = '')
	{
		$sql = "INSERT INTO `jam_layout_menus` (`program_id`, `menu_status`, `menu_name`, `menu_parent`, `menu_url`, `menu_sort_order`, `menu_options`) VALUES
(" . $data['id'] . ", '1', 'profile', 0, '{members_details}', 5, ''),
(" . $data['id'] . ", '1', 'content', 0, '{members_content}', 6, ''),
(" . $data['id'] . ", '1', 'dashboard', 0, '{members_home}', 1, ''),
(" . $data['id'] . ", '1', 'marketing', 0, '{members_marketing}', 4, '');";
		
		if ($this->db->query($sql))
		{
			//log success
			log_message('info', 'program ID '. $data['id'] . ' menu items added in layout menus table');
			
			return true;
		}
		else
		{
			show_error($this->lang->line('could_not_add_program_menu_items'));
			
			//log error
			log_message('error', 'Could not add program menu items ' . $data['id'] . 'in layout menus table');
			
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_layout_menu($id = '')
	{
		$this->db->where('menu_parent', '0');
		$this->db->where('program_id', $id);
		$this->db->order_by('menu_sort_order', 'ASC');
		$query = $this->db->get('layout_menus');
		
		return $query->result_array();
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_menus($program_id = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		foreach ($data as $k => $v)
		{
			if (substr($k,0,7) == "member_")
			{
				$array = explode('-', $k);	
				
				//first check to see if there is a new entry
				if ($array[1] == '0')
				{
					if ($k =='member_menu_name-0')
					{					
						if (!empty($data['member_menu_name-0']))
						{
							$data['member_menu_status-0'] = (empty($data['member_menu_status-0'])) ? '0' : $data['member_menu_status-0'];
							
							$insert = array(	'program_id' => $program_id,
												'menu_status' => $data['member_menu_status-0'],
												'menu_name'	=>	xss_clean($data['member_menu_name-0']),
												'menu_parent'	=> '0',
												'menu_url' => xss_clean($data['member_menu_url-0']),
												'menu_sort_order' => xss_clean($data['member_menu_sort_order-0']),
												'menu_options' => htmlentities($data['member_menu_options-0']),
											);
											
							$this->db->insert('layout_menus', $insert);
							
						}
					}
				}
				else
				{
					//update or delete any other menu items
					
					$id = $array[1];
					
					if ($k == 'member_menu_name-'.$id)
					{				
						$name = 'member_menu_name-'.$id;
						
						if (empty($data[$name]))
						{
							//delete the entry
							$this->db->where('id', $id);	
							$this->db->delete('layout_menus');
							
							//delete any children
							$this->db->where('menu_parent', $id);	
							$this->db->delete('layout_menus');
							
						}
						else
						{
							$data['member_menu_status-'.$id] = (empty($data['member_menu_status-'.$id])) ? '0' : $data['member_menu_status-'.$id];
							
							//update the entry
							$update = array(
												'menu_status' => $data['member_menu_status-'.$id],
												'menu_name'	=>	$data['member_menu_name-'.$id],
												'menu_parent'	=> '0',
												'menu_url' => $data['member_menu_url-'.$id],
												'menu_sort_order' => $data['member_menu_sort_order-'.$id],
												'menu_options' => htmlentities($data['member_menu_options-'.$id]),
											);
							
							$this->db->where('id', $id);				
							$this->db->update('layout_menus', $update);
						}
					}
				}
			}
		}
		
		//make sure tiers are numbered sequentially
		for ($i = 1; $i <=5; $i++)
		{
			$this->db_validation_model->_db_sort_order('layout_menus', 'id', 'menu_sort_order', 'program_id = \''.$program_id.'\''); 
		}
			
		//update menus
		$this->_update_html_menus($program_id);
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_html_menus($id = '')
	{
		//now generate the links array for all links

		//system_layout_top_links_array
		$this->db->where('program_id', $id);
		$this->db->where('menu_parent', '0');
		$this->db->where('menu_status', '1');
		$this->db->order_by('menu_sort_order', 'ASC');
		$query = $this->db->get('layout_menus');
		
		$top_links = array();
		
		foreach ($query->result_array() as $row)
		{
			$row['subs'] = array();
			
			//now get all the children
			$this->db->where('menu_parent', $row['id']);
			$this->db->where('menu_status', '1');
			$this->db->order_by('menu_sort_order', 'ASC');
			$child = $this->db->get('layout_menus');
		
			if ($child->num_rows() > 0)
			{
				
				foreach ($child->result_array() as $sub)
				{
					array_push($row['subs'], $sub);
				}
			}
			
			array_push($top_links, $row);
		}
		
		//serialize data
		$top = serialize($top_links);

		
		$data = array( 'program_layout_member_links_array' => $top
					  );
					  
		$this->db->where('program_id', $id);
		$this->db->update('programs', $data);
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_sub_menus($id = '', $program_id = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		foreach ($data as $k => $v)
		{
			if ($k =='menu_name-0')
			{		
				if (!empty($data['menu_name-0']))
				{
					$data['menu_status-0'] = (empty($data['menu_status-0'])) ? '0' : $data['menu_status-0'];
					
					$insert = array(
										'program_id' => $program_id,
										'menu_status' => $data['menu_status-0'],
										'menu_name'	=>	xss_clean($data['menu_name-0']),
										'menu_parent'	=> $id,
										'menu_url' => xss_clean($data['menu_url-0']),
										'menu_sort_order' => xss_clean($data['menu_sort_order-0']),
										'menu_options' => htmlentities($data['menu_options-0']),
									);
									
					$this->db->insert('layout_menus', $insert);
				}
			}
			else
			{
				$array = explode('-', $k);
				
				if (!empty($array))
				{
					if (!empty($array[1])) 
					{
						$sid = $array[1];
						
						if ($k == 'menu_name-'.$sid)
						{					
							$name = 'menu_name-'.$sid;
							
							if (empty($data[$name]))
							{
								
								//delete the entry
								$this->db->where('id', $sid);	
								$this->db->delete('layout_menus');
								
							}
							else
							{
								$data['menu_status-'.$sid] = (empty($data['menu_status-'.$sid])) ? '0' : $data['menu_status-'.$sid];
								
								//update the entry
								$update = array(
													'menu_status' => $data['menu_status-'.$sid],
													'menu_name'	=>	$data['menu_name-'.$sid],
													'menu_url' => $data['menu_url-'.$sid],
													'menu_sort_order' => $data['menu_sort_order-'.$sid],
													'menu_options' => htmlentities($data['menu_options-'.$sid]),
												);
								
								$this->db->where('id', $sid);				
								$this->db->update('layout_menus', $update);
							}
						}
					}
				}
			}
		}
		
		$this->db_validation_model->_db_sort_order('layout_menus', 'id', 'menu_sort_order', 'menu_parent = \''.$id.'\''); 
		
		$this->_update_html_menus($program_id);
		
		return true;
	}
}
?>