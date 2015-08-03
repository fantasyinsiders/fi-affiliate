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
| FILENAME: JROX_date_helper.php
| -------------------------------------------------------------------------     
| 
| This file extends the date_helper
|
*/

// ------------------------------------------------------------------------

function _generate_timestamp($time = '')
{
	$CI = & get_instance();	

	$zone = $CI->config->item('sts_site_default_timezone');
	$dst = $CI->config->item('sts_site_use_daylight_savings_time') == false ? true : false;
	
	if (empty($time))
	{
		$time = now();	
	}
	
	$t = gmt_to_local($time, $zone, $dst);

	return $t;
}

// ------------------------------------------------------------------------

function _generate_months()
{
	$m = array();
		
	$a = array(); $b = array();
	for ($i = 1; $i <=12; $i++)
	{
		$j = $i;
		if ($i < 10) { $j = '0' . $j;}
		array_push($a, $j);
		array_push($b, $j);
	}
	
	$m = combine_array($a, $b);
	
	return $m;
}

// ------------------------------------------------------------------------

function _generate_month_dropdown($select = '', $class = 'form-control', $onchange = 'ChangeReportStatus(this)')
{
	$CI =& get_instance();
	
	$options = array();
	
	$total_years = $CI->config->item('dbr_total_report_years');
	
	$current_year = date('Y');
	
	$last_year = $current_year - $total_years;
	
	for ($i = $current_year; $i > $last_year; $i = $i - '1')
	{	
		for ($j = 12; $j >= 1; $j = $j - '1')
		{
			$options[date('m/Y', mktime(0, 0, 0, $j  , '1', $i))] = date('F Y', mktime(0, 0, 0, $j  , '1', $i));
		}
		
		$j = 1;
	}
		  			   
	return form_dropdown('change_status', $options, $select, 'id="change_status" class="' . $class . '" onchange="' . $onchange . '"'); 
}

// ------------------------------------------------------------------------

function _generate_year_dropdown($select = '', $class = 'form-control', $onchange = 'ChangeReportStatus(this)')
{
	$CI =& get_instance();

	$options = array();
	
	$total_years = $CI->config->item('dbr_total_report_years');
	
	$month = '1';
	
	$current_year = date('Y');
	
	$last_year = $current_year - $total_years;
	
	for ($i = $current_year; $i > $last_year; $i = $i - '1')
	{	
		$options[$month .'/'.$i] = $CI->lang->line('year'). ' ' . date('Y', mktime(0, 0, 0, '1'  , '1', $i));
	}
		  			   
	return form_dropdown('change_status', $options, $select, 'class="' . $class . '" onchange="' . $onchange . '"'); 
}

// ------------------------------------------------------------------------

function _generate_years($y = 'Y')
{
	$z = array();
	
	$cur_year = date($y);
		
	$a = array(); $b = array();
	for ($i = 1; $i <=10; $i++)
	{
		array_push($a, $cur_year);
		array_push($b, $cur_year);
		
		$cur_year = date($y, mktime(0, 0, 0, 1, 1, date('Y') + $i));
	}
	
	$z = combine_array($a, $b);
	
	return $z;
}

// ------------------------------------------------------------------------

function _format_date($time = '', $format = '', $return = false)
{
	if (empty($time)) 
	{
		if ($return == true)
		{
			return '';	
		}
		
		$time = _generate_timestamp();
	}

	return date($format, $time);
}

// ------------------------------------------------------------------------

function _save_date($date = '', $now = false, $min = false)
{
	$CI = & get_instance();
	
	$fdate = explode(':', $CI->config->item('sts_admin_date_format'));
	$pub = explode('/', $date);

	if ($min == 'max')
	{
		$h = '23';
		$i = '59';
		$s = '59';
	}
	elseif ($min == 'min')
	{
		$h = '0';
		$i = '0';
		$s = '0';
	}
	else
	{
		$h = date('H', _generate_timestamp());
		$i = date('i', _generate_timestamp());
		$s = date('s', _generate_timestamp());
	}
	
	if (!empty($date))
	{
		switch ($fdate[0])
		{
			case 'mm/dd/yyyy':
				return mktime($h, $i, $s, $pub[0], $pub[1], $pub[2]);
			break;
			
			case 'dd/mm/yyyy':
				return mktime($h, $i, $s, $pub[1], $pub[0], $pub[2]);
			break;
		}
	}
	
	if ($now == true)
	{
		return _generate_timestamp();	
	}
}

// ------------------------------------------------------------------------

function _show_date($time = '', $full = false, $words = false)
{
	$CI = & get_instance();
	
	if ($full == true)
	{
		$a = date($CI->config->item('format_date3') . ' h:i:s A', $time);
	}
	else
	{
		$time = (empty($time)) ? _generate_timestamp() : $time;
		
		//format date
		$fdate = explode(':', $CI->config->item('sts_admin_date_format'));
				
		$a = date($fdate[2], $time);
		
		//$b = explode(' ', $a);
		//$c = '<span id="jroxContentMonth">' . $b[0] . '</span> ' . $b[1] . ' ' . $b[2];
	}
	
	return $a;
}

// ------------------------------------------------------------------------

function _show_timestamp($time = '', $type = false)
{
	$CI = & get_instance();
	
	if ($type == true)
	{
		$use = $CI->config->item('sts_admin_time_format');
	}
	else
	{
		$use = 'M d Y h:i:s A';
	}
	
	return date($use, $time);
}

// ------------------------------------------------------------------------

function _calculate_time($time = '')
{
	$CI = & get_instance();
	
	$now = _generate_timestamp();
	
	$a = $now - $time;
	
	//now check if time is in minutes, hours or days
	if ($a < 60)
	{
		$b = round($a);
		$c = $b < 2 ? $CI->lang->line('second') : $CI->lang->line('seconds');	
	}
	elseif ($a < 3600)
	{
		//minutes
		$b = $a / 60;
		$b = round($b);
		$c = $b < 2 ? $CI->lang->line('minute') : $CI->lang->line('minutes');
	}
	elseif ($a < 86400)
	{
		//hours
		$b = $a / 3600;
		$b = round($b);
		$c = $b < 2 ? $CI->lang->line('hour') : $CI->lang->line('hours');
	}
	else
	{
		//days
		$b = $a / 86400;
		$b = round($b);
		$c = $b < 2 ? $CI->lang->line('day') : $CI->lang->line('days');
	}
	
	return '<strong>' . $b . ' ' . $c . ' ' . $CI->lang->line('ago') . '</strong>';
}

// ------------------------------------------------------------------------


?>