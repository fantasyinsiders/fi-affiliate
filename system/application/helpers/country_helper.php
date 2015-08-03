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
| FILENAME: country_helper.php
| -------------------------------------------------------------------------     
| 
| This file loads country info
|
*/

// ------------------------------------------------------------------------

function _load_countries_array($ship_to = false, $text = '')
{
	$CI =& get_instance();
	
	if ($ship_to == true)
	{
		$CI->db->where('ship_to', '1');
	}
	
	$query = $CI->db->get('countries');
	if ($query->num_rows() > 0)
	{
		if (!empty($text))
		{
				
		}
		
		return $query->result_array();
	}
	
	return false;
}

// ------------------------------------------------------------------------

function _load_regions_array($id = '')
{
	$CI =& get_instance();
	
	$CI->db->where('region_country_id', $id);
	$query = $CI->db->get('regions');
	if ($query->num_rows() > 0)
	{
		return $query->result_array();
	}
	
	return false;
}

// ------------------------------------------------------------------------

function _load_countries_dropdown($countries = '', $all = false, $text = 'all_countries')
{
	$CI =& get_instance();
	
	$a = array();
	$b = array();
	
	if ($all == true)
	{
		array_push($a, '');
		array_push($b, $CI->lang->line($text));
	}
	
	foreach ($countries as $value)
	{
		array_push($a, $value['country_id']);
		array_push($b, $value['country_name']);
	}

	$c = combine_array($a, $b);

	return $c;
}

// ------------------------------------------------------------------------

function _get_country_name($num = '', $col = '', $id = 'country_id')
{
	$CI =& get_instance();
	
	$CI->db->where($id, $num);
	$query = $CI->db->get('countries');
	
	if ($query->num_rows() > 0)
	{
		$cn = $query->row_array();
		
		$data = !empty($col) ? $cn[$col] : $cn;
		
		return $data;
	}
	
	return false;
}

// ------------------------------------------------------------------------

function _get_region_name($num = '', $col = '', $id = 'region_id')
{
	$CI =& get_instance();
	
	$CI->db->where($id, $num);
	$query = $CI->db->get('regions');
	
	if ($query->num_rows() > 0)
	{
		$cn = $query->row_array();
		
		$data = !empty($col) ? $cn[$col] : $cn;
		
		return $data;
	}
	
	return $num;
}

// ------------------------------------------------------------------------

function _check_ship_to($id = '')
{
	$countries = _load_countries_array(true);
	
	foreach ($countries as $v)
	{
		if ($id == $v['country_id'])
		{
			return true;
		}
	}
	
	return false;
}

// ------------------------------------------------------------------------
?>