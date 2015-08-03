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
| FILENAME: JROX_string_helper.php
| -------------------------------------------------------------------------     
| 
| This file extends the string_helper
|
*/

// ------------------------------------------------------------------------

function jrox_string_replace($data = '', $replace = '', $type = '')
{
	$data = str_replace($replace, $type, $data);

	return $data;
}

// ------------------------------------------------------------------------

function limit_chars($word = '', $total = 20)
{
	if (strlen($word) > $total)
	{
		$word = substr($word, 0, $total).'...';
	}
	
	return $word;
}


// ------------------------------------------------------------------------

function check_string_value($a, $type = '', $id = '')
{
	$CI = & get_instance();
	
	if (empty($a))
	{
		$a = $CI->lang->line('none');
	}	
	
	if ($type == 'date' and $a != 'none')
	{
		$a = date($CI->config->item('format_date3'), $a);
	}
	if ($type == 'fulldate' and $a != 'none')
	{
		$a = _show_date($a, true);
	}
	elseif ($type == 'admin_user' and $a != 'none')
	{
		$data = $CI->db_validation_model->_get_details('admin_users', 'username', 'admin_id', $id);
		$a = $data[0]['username'];
	}
	
	return $a;
}

// ------------------------------------------------------------------------

function _csv_from_result($query, $delim = ",", $newline = "\n", $enclosure = '"')
{
	$out = '';
	 
	if ($delim == "tab") { $delim = "\t"; $enclosure = ''; }  
	
	// First generate the headings from the table column names
	foreach ($query['list_fields'] as $name)
	{
		$out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure, $name).$enclosure.$delim;
	}
	
	$out = rtrim($out);
	$out .= $newline;
	
	// Next blast through the result array and build out the rows
	foreach ($query['result_array'] as $row)
	{
		foreach ($row as $item)
		{
			$out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure, $item).$enclosure.$delim;			
		}
		$out = rtrim($out);
		$out .= $newline;
	}

	return $out;
}

// ------------------------------------------------------------------------

function _generate_random_string($num = 8, $upcase = true)
{

	$string = random_string('alnum', $num);	

	if ($upcase == true)
	{
		return strtoupper($string);
	}
	else
	{
		return strtolower($string);
	}
	
	return $string;
}

// ------------------------------------------------------------------------

function _make_alphanum($str = '')
{
	return preg_replace('/[^a-zA-Z0-9\s]/', '', $str);
}

// ------------------------------------------------------------------------

function _generate_random_username($prepend = '', $append = '')
{
	$CI =& get_instance();
	
	$prepend = url_title(strtolower($prepend), '_');
	
	$prepend =  str_replace('_', '', $prepend);
	
	//check for custom function
	if (file_exists(APPPATH . '/helpers/custom_helper.php'))
	{
		require_once(APPPATH . '/helpers/custom_helper.php');	
		
		if (function_exists('_generate_custom_random_username'))
		{
			return _generate_custom_random_username();
		}
	}
	
	//first check if the first name is less than 6 characters
	if (strlen($prepend) < $CI->config->item('member_min_username_length'))
	{
		$left = $CI->config->item('member_min_username_length') - strlen($prepend);
		$gen = _generate_random_string($left, false);
		$prepend = $prepend . $gen;
	}
	
	//now check if the username is being used
	$check = true;
	
	while ($check == true)
	{
		$CI->db->select('username');
		$CI->db->from('members');
		$CI->db->where('username', $prepend);
		
		$cquery = $CI->db->get();
		
		if ($cquery->num_rows > 0)
		{
			//get the last row ID from db
			$CI->db->select('member_id');
			$CI->db->from('members');
			$CI->db->order_by('member_id', 'DESC');
			$CI->db->limit('1');
			
			$query = $CI->db->get();
			
			if ($query->num_rows() > 0)
			{
				$num = $query->row();
				$a = $num->member_id;
			}
			else
			{
				$a = 1;
			}
			
			$prepend = substr($prepend, 0, 5);
			
			$prepend = $prepend . $a;
		}
		else
		{
			$check = false;
		}
	}
	
	return strtolower($prepend);
}

?>