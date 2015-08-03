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
| FILENAME - update_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for updates
|
*/

class Update_Model extends CI_Model {	
	
	function _run_updates($dir = 'updates')
	{		
		$data = array();
		$data['success'] = true;
		$update_version = '';
		
		$this->load->helper('directory');
		
		$files = directory_map('./import/' . $dir . '/');

		if (!empty($files))
		{
			$data['files'] = '';
			
			foreach ($files as $update)
			{
				if ($update == 'index.html') { continue; }
				if (is_array($update)) { continue; }
				
				//check extension
				include $this->config->slash_item('base_physical_path') . 'import/' . $dir . '/' . $update;
				
				if (!empty($update_array))
				{
					foreach ($update_array as $v)
					{
						if ($v['update_type'] == 'insert')
						{
							//run the check first
							$sql = $v['check_sql'];
					
							$check = $this->db->query($sql);
					
							if ($check->num_rows() < 1)
							{
								//run the update
								$sql_insert = $v['update_sql'];
								
								$sql_insert = str_replace('{config_site_name}', $this->config->item('sts_site_name'), $sql_insert);
								$sql_insert = str_replace('{config_domain_name}', $this->config->item('base_domain_name'), $sql_insert);
								$sql_insert = str_replace('{config_url}', base_url(), $sql_insert);
								
								if (!$this->db->query($sql_insert))
								{
									$data['error'] = true;
								}
							}
						}
						elseif ($v['update_type'] == 'table')
						{
							$run = true;
							
							if ($this->db->table_exists($v['table_name']))
							{
							  $run = false;
							} 
							
							if ($run == true)
							{
								//run the update
								$sql_insert = $v['update_sql'];
			
								if (!$this->db->query($sql_insert))
								{
									$data['error'] = true;
								}
							}
						}
						elseif ($v['update_type'] == 'column')
						{
							//run the check first
							$sql = $v['check_sql'];
					
							$check = $this->db->query($sql);
							$run = true;
							//echo '<pre>';print_r($check->result_array());
							foreach ($check->result_array() as $row)
							{
								//echo '<pre>'; print_r($row);
								foreach ($row as $k => $value)
								{
									//echo $k . ' - ' . $value . '<br />';
									if ($k == 'Field')
									{
										if ($value == $v['column_name'])
										{
											$run = false;
										}	
									}
								}
							}
							
							if ($run == true)
							{
								//run the update
								$sql_insert = $v['update_sql'];
			
								if (!$this->db->query($sql_insert))
								{
									$data['error'] = true;
								}
							}
						}
						elseif ($v['update_type'] == 'config') //add to the configuration settings
						{
							$this->db->where('settings_key', $v['config_key']);
							$query = $this->db->get('settings');
							
							if ($query->num_rows() < 1)
							{
								$sql_update = $v['update_sql'];
							
								if (!$this->db->query($sql_update))
								{
									$data['error'] = true;
								}
							}
						}
						elseif ($v['update_type'] == 'module') //add to the configuration settings
						{
							$this->db->where('settings_key', $v['config_key']);
							$query = $this->db->get('settings');
							
							if ($query->num_rows() < 1)
							{
								//get the id
								$this->db->where('module_type', $v['module_type']);
								$this->db->where('module_file_name', $v['module_file_name']);
								$query = $this->db->get('modules');
								if ($query->num_rows() > 0)
								{			 
									$mod_info = $query->row_array();
									
									$update_sql = str_replace('{module_id}', $mod_info['module_id'], $v['update_sql']);
								
									if (!$this->db->query($update_sql))
									{
										$data['error'] = true;
									}
								}
							}
						}
						elseif ($v['update_type'] == 'integration') //add to the integration table
						{
							$this->db->where('code', $v['code']);
							$query = $this->db->get('program_integration');
							
							if ($query->num_rows() < 1)
							{
								$sql_insert = $v['insert_sql'];
							
								if (!$this->db->query($sql_insert))
								{
									$data['error'] = true;
								}
							}
							elseif (!empty($v['update_sql']))
							{
								$sql_update = $v['update_sql'];	
								
								if (!$this->db->query($sql_update))
								{
									$data['error'] = true;
								}
							}
							elseif (!empty($v['delete_sql']))
							{
								$sql_delete = $v['delete_sql'];	
								
								if (!$this->db->query($sql_delete))
								{
									$data['error'] = true;
								}
							}
						}
						else
						{
							//run the update
							$sql_update = $v['update_sql'];
							
							if (!$this->db->query($sql_update))
							{
								$data['error'] = true;
							}
						}
					}
				}
				//lets try and delete the file
				if (@unlink($this->config->slash_item('base_physical_path') . 'import/' . $dir . '/' . $update) == false)
				{
					$data['delete_warning'] = true;
				}
				
				
				$data['files'] = $update_version . ' update file ran successfully<br />';
			}
		}
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
}
?>