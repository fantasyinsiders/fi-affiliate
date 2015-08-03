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
| FILENAME: JROX_array_helper.php
| -------------------------------------------------------------------------     
| 
| This file extends the array_helper
|
*/

// ------------------------------------------------------------------------

function combine_array($a = '', $b = '')
{
	$c = array();
	if (is_array($a) && is_array($b))
	   while (list(, $va) = each($a))
		   if (list(, $vb) = each($b))
			   $c[$va] = $vb;
		   else
			   break 1;
	return $c;
}

// ------------------------------------------------------------------------

function format_array($x = array(), $y = '', $z = '', $none = false, $text = 'none')
{
	$CI = & get_instance();
	
	if (empty($x))
	{
		$array = array($CI->lang->line('none'));
		return $array;
	}
	else
	{
		if ($none == true)
		{
			$a = array(0);
			
			$b = array($CI->lang->line($text));
		}
		else
		{
			$a = array(); $b = array();
		}
		
		foreach ($x as $v)
		{
			array_push($a, $v[$y]);
			
			if (!empty($z))
			{
				array_push($b, $v[$z]);
			}
		}
		
		if (!empty($b))
		{
			$options = combine_array($a, $b);		
		}
		else
		{
			$options = $a;
		}
		
		return $options;
	}
}


// ------------------------------------------------------------------------

function _display_children($key = '', $value = 0,$level = 1, $table = '')
{
	$CI = & get_instance();	

    $sql = 'SELECT * FROM ' . $CI->db->dbprefix($table) . ' WHERE ' . $key . ' = \'' . $value . '\'';
      
    $result = $this->db->query($sql);    
    
	$indent = ($parent != NULL) ? str_repeat(' ',$level*2) : '' ;    
    
	$children = ($result->num_rows()>0) ? TRUE : FALSE;

    
    $tree ='';
    $tree.= ($children) ? $indent."<ul>\n" : '';
        
    foreach ($result->result_array() as $row){
      
       $tree .= $indent.'<li><a href="'.base_url(). $CI->config->item('index_page') . "/page/show_page/".intval($row['category_id']).'" title="'.$row['category_name'].'">'.$row['category_name']."</a></li>\n";
      
       $tree .= $this->_display_children($key, $row[$id], $level+1, $table);
    }
    $tree.= ($children) ? $indent."</ul>\n" : '';
   
    return $tree;
  } 

// ------------------------------------------------------------------------
	
function array_match_values($arr = '', $key = '', $item = '')
{
	$result = array();

	for ($i = 0; $i < count($arr); $i++)
	{
		//echo $arr[$i][$key].'='.$item.'<br />';
		if (strcmp($arr[$i][$key], $item) == 0)
		{	
			array_push($result, $arr[$i]);
		}
	}

	return $result;
}

// ------------------------------------------------------------------------

function _pr($a, $exit = true)  //this is only for testing and showing arrays
{
	echo '<pre>'; 
	print_r($a);
	echo '</pre>';
	if ($exit == true) exit();
}

// ------------------------------------------------------------------------

function get_cats($id = 0)
{
	$CI = & get_instance();	
	
	$sql = 'SELECT * FROM ' . $CI->db->dbprefix('products_categories') . ' WHERE parent_id = \''.$id.'\' ORDER BY category_id ASC';
  
	$result = $CI->db->query($sql);  
	
	$original_array = $result->result_array();

	$root_ids_array = array();
	
	$sql2 = 'SELECT * FROM ' . $CI->db->dbprefix('products_categories') . ' ORDER BY category_id ASC';
	$result2 = $CI->db->query($sql2); 
	$total = $result2->result_array();
	$commlevel = 1;
	$tree = $CI->_get_children(0, $total, 0, $commlevel, 10);
	echo $tree;
}

// ------------------------------------------------------------------------

function _get_children($id = 0, $all_cats = '', $level = 1, $commlevel, $total_levels = 10)
{
	$CI = & get_instance();	

	//select all children for this level
	$array = $CI->array_match_values($all_cats, $id);
	
	$indent = ($id != NULL) ? str_repeat(' ',$level*2) : '' ;   
	
	if (count($array) > 0)
	{
		$children = true;
	}
	else
	{
		$children = false;
	}
		
		
	// display each child
	$tree ='';
	$tree.= ($children) ? $indent."<ul>\n" : '';
	
	if ($children == true) {
		foreach	($array as $key => $value)
		{
			
			// indent and display the title of this child
			$tree .= $indent.' <li>' . $commlevel . '<a href="'.base_url(). $CI->config->item('index_page') . "/page/show_page/".intval($value['category_id']).'" title="'.$value['category_name'].'">'.$value['category_name']."</a></li>\n";
			// call this function again to display this
			// child's children
			
			$tree .= $CI->_get_children($value['category_id'], $all_cats, $level+1, $commlevel);
			//unset ($all_cats[$key]);
		}	
	}
	$tree.= ($children) ? $indent."</ul>\n" : '';
	
	return $tree;
}
	
// ------------------------------------------------------------------------	

?>