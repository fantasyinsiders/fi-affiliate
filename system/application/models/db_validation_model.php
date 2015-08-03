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
| FILENAME - db_validation_model.php
| -------------------------------------------------------------------------     
| 
| This model handles validation functions agains the database
|
*/

class Db_Validation_Model extends CI_Model {

	function _clean_data($data)
	{
		/*
		|------------------------------------------------------
		| this cleans each value in the array
		| then removes any empty values
		|------------------------------------------------------
		*/
		
		$filter = $this->config->item('dbi_filter');
				
		foreach ($data as $key => $value)
		{
			//check if password needs to be updated or not
			if ($key == 'password') //for users
			{
				if (!empty($value))
				{
					if (empty($data['encrypted']))
					{
						switch ($this->config->item('members_password_function'))
						{
							case 'sha1':
							
								$value = sha1($value);
							
							break;
							
							case 'mcrypt':
								
								$value = $this->encrypt->encode($value);
								
							break;
						
							default:
							
								$value = md5($value);
							
							break;
						}
					}
					
					$data[$key] = xss_clean($value);
				}
				else
				{	
					array_push($filter, $key);
				}
			}
			elseif ($key == 'apassword') //for admins
			{
				if (!empty($value))
				{
					$value = md5($value);
					$data[$key] = xss_clean($value);
				}
				else
				{
					array_push($filter, $key);
				}
			}
		}

		foreach ($data as $key => $value)
		{	
			//remove keys that are not in the database
			if (in_array($key, $filter))
			{
				 unset($data[$key]); 
			}
			else
			{
				in_array($key, $this->config->item('dbi_arrays')) ? '' : $data[$key] = $this->_input_clean($value);				
			}
		}
		
		return $data;
	}
	
	// ------------------------------------------------------------------------
	
	function _input_clean($html = '')
	{
		return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);	
	}
	
	// ------------------------------------------------------------------------
	
	function _get_count($table = '', $where = '')
	{
		$sql = 'SELECT COUNT(*) as count FROM ' . $this->db->dbprefix($table) . ' ';
		
		if (!empty($where))
		{
			$sql .= $where;
		}
		
		$query = $this->db->query($sql);
	
		$a = $query->result_array();
		
		if ($a < 1)
		{
			return '0';
		}
		
		return $a[0]['count'];
	}
	
	// ------------------------------------------------------------------------
	
	function _get_details($table = '', $fields = '', $key = '', $id = '', $sort_by = '', $sort_order = 'ASC')
	{
		/*
		|------------------------------------------------------
		| this is generic database select query
		| that retrieves data based on table
		|------------------------------------------------------
		|
		| $table = the table to query
		| $fields = the fields in the table to retrieve
		| $key = where key
		| $id = the id to query
		|
		|------------------------------------------------------
		*/
		
		if (!empty($key) AND !empty($id))
		{
			$this->db->where($key, $id);
		}
		
		if (!empty($fields))
		{
			$this->db->select($fields);
		}
		
		if (!empty($sort_by))
		{
			$this->db->order_by($sort_by, $sort_order);
		}
		
		$query = $this->db->get($table);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _set_pagination($uri = '', $table = '', $per_page = '25', $segment = '5', 
							 $sort_order = 'ASC', $sort_column = '', $query_count = '', 
							 $where_key = '0', $where_value = '0', $type = 'admin', $where_key2 = '', $where_value2 = '')
	{
		/*
		|------------------------------------------------------
		| this creates the pagination links at the bottom of 
		| each page
		| -----------------------------------------------------
		| 
		| $uri = the url where pagination will be used
		| $table = the table to query for total rows
		| $per_page = the number of results per page
		| $segment = which segment has the offset
		|
		|------------------------------------------------------
		*/
		
		//check if we will be counting all rows in a table, or rows in a query
		if ($query_count == '')
		{
			$total_rows = $this->db->count_all($table);
		}
		else
		{
			$total_rows = $query_count;
		}
		
		$this->load->library('pagination');
		
		$config = array(
			'base_url' 			=> 		'/'.$uri,
			'sort_order' 		=> 		$sort_order,
			'sort_column' 		=> 		$sort_column,
			'total_rows'		=>		$total_rows,
			'per_page'			=>		$per_page,
			'num_links'			=>		$this->config->item('jrox_module_type') == 'admin' ? $this->config->item('admin_pagination_links') : $this->config->item('member_pagination_links'),
			'uri_segment'		=>		$segment,
			'full_tag_open'		=>		'<div id="' . $table . '_pagination" class="pagination">',
			'full_tag_close'	=>		'</div>',
			'cur_tag_open'		=>		'<span id ="current-page" class="current">',
			'cur_tag_close'		=>		'</span>',
			'first_link'		=>		$this->lang->line('first'),
			'last_link'			=>		$this->lang->line('last'),
			'next_link'			=>		$this->lang->line('pagination_next'),
			'prev_link'			=>		$this->lang->line('pagination_prev'),
			'where_key'			=>		$where_key,
			'where_value'		=>		$where_value,
			'where_key2'		=>		$where_key2,
			'where_value2'		=>		$where_value2,
			'type'				=> 		$type
		);
		
		$this->pagination->initialize($config);
		
		$array = $this->pagination->create_links();
		
		$array['num_pages'] = ceil($config['total_rows'] / $config['per_page']);
		
		$array['select_rows'] = '<div class="btn-group">
									<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="icon-list"></i> 
									' . $this->lang->line('select_rows_per_page') . ' <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="' . admin_url(). 'update_session/rows/12/' . str_replace('/', ':', $this->uri->uri_string()) . '">12 ' . $this->lang->line('rows_per_page') . '</a></li>
										<li><a href="' . admin_url(). 'update_session/rows/24/' . str_replace('/', ':', $this->uri->uri_string()) . '">24 ' . $this->lang->line('rows_per_page') . '</a></li>
										<li><a href="' . admin_url(). 'update_session/rows/48/' . str_replace('/', ':', $this->uri->uri_string()) . '">48 ' . $this->lang->line('rows_per_page') . '</a></li>
										<li><a href="' . admin_url(). 'update_session/rows/96/' . str_replace('/', ':', $this->uri->uri_string()) . '">96 ' . $this->lang->line('rows_per_page') . '</a></li>
										<li><a href="' . admin_url(). 'update_session/rows/192/' . str_replace('/', ':', $this->uri->uri_string()) . '">192 ' . $this->lang->line('rows_per_page') . '</a></li>
										
									</ul>
		
								</div>';		
		
		return $array;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_next_prev($table = '', $col = '', $id = '')
	{
		//get previous
		$arg['next'] = '';
		$arg['prev'] = '';
		
		$this->db->select($col);
		$this->db->where($col . ' <', $id);
		$this->db->order_by($col, 'DESC');
		$this->db->limit('1');
		$query = $this->db->get($table);
		
		if ($query->num_rows > 0)
		{
			$row = $query->row_array();
			$arg['prev'] = $row[$col];
		}
		
		$this->db->select($col);
		$this->db->where($col . ' >', $id);
		$this->db->order_by($col, 'ASC');
		$this->db->limit('1');
		$query = $this->db->get($table);
		
		if ($query->num_rows > 0)
		{
			$row = $query->row_array();
			$arg['next'] = $row[$col];
		}
		
		return $arg;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_db_settings($data = '')
	{
		/*
		|------------------------------------------------------
		| update the values in the settings table
		| -----------------------------------------------------
		| 
		| $data = array
		|
		|------------------------------------------------------
		*/
		
		foreach ($data as $k => $v)
		{
			$sql = array('settings_value' => $v);

			$this->db->where('settings_key', $k);
			$this->db->update('settings', $sql);
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _validate_field($table = '', $field = '', $value = '', $id_key = '', $id = '')
	{
		/*
		|------------------------------------------------------
		| this validates the field to see if there is already
		| a corresponding value that is the same in the db
		| -----------------------------------------------------
		| 
		| $table = the table to validate the field on
		| $update = whether it is an add or update function
		| $field = the field to find in the table
		| $value = the value to compare in the field
		| $id_key = field for the id in an update
		| $id = the value of $id_key
		|
		|------------------------------------------------------
		*/
		
		//check for unique field
		$this->db->where($field, $value);
		
		//if updating only
		if (!empty($id))
		{
			//check other ids
			$this->db->where($id_key . ' !=', $id);
		}
			
		$query = $this->db->get($table);
		
		if ($query->num_rows() > 0)
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _db_sort_order($table = '', $id = '', $order_by = '', $where = '')
	{
		/*
		|------------------------------------------------------
		| this changes the sort order in the table designated
		| so that all required sorting is in sequential order
		| -----------------------------------------------------
		| 
		| $table = table to sort
		| $id = unique id
		| $order_by = sort order field to use
		| $where = if there is a where clause, add it
		|
		|------------------------------------------------------
		*/
		
		if ($this->config->item('disable_db_autosorting') == true) return;
		
		$this->db->order_by($order_by, 'ASC');
		
		if (!empty($where))
		{
			$this->db->where($where);
		}
		
		$query = $this->db->get($table);
		
		$total = $query->num_rows();
		$i = 1;
		if ($total > 1)
		{
			foreach ($query->result_array() as $row)
			{
				$update = array($order_by => $i);
				$this->db->where($id, $row[$id]);
				
				if ($this->db->update($table, $update))
				{
					//log success
					log_message('info', 'sort order changed for ' . $table );
				}
				
				$i++;
			}
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _change_status_field($table = '', $key = '', $value = '', $status_field = '', $active = '1', $inactive = '0')
	{	
		/*
		|------------------------------------------------------
		| this changes the status of the row  to active 
		| or inactive
		| -----------------------------------------------------
		| 
		| $table = table name
		| $key = the table row
		| $value = value to change 
		| $status_field = the column to change
		| $active = active
		| $inactive = inactive
		|
		|------------------------------------------------------
		*/
		
		$this->db->where($key, $value);
		$query = $this->db->get($table);
		
		$row = $query->result_array();
		
		$status = ($row[0][$status_field] == $active) ? $inactive : $active;
		
		$data = array($status_field => $status);
		
		//update status
		$this->db->where($key, $value);
		
		if ($this->db->update($table, $data))
		{
			
			//log success
			log_message('info', 'Status Changed for '. $table . ' ID# ' . $value);
			
			return true;
		}
		
		show_error($this->lang->line('could_not_update_product'));
			
		//log error
		log_message('error', 'Could not update ' . $table . ' ID #' . $value . ' in ' . $table . ' table');
		
		return false;
	}
	
	// ------------------------------------------------------------------------

}
?>