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
| FILENAME: template_helper.php
| -------------------------------------------------------------------------     
| 
*/

function _confirm_deletion($func = '')
{
	$CI = & get_instance();
	
	$msg = 'are_you_sure_you_want_to_do_this';	
	
	switch ($func)
	{
		case 'update_member':
		case 'view_members':
			
			$msg = 'are_you_sure_you_want_to_delete_this_member';
		
		break;	
		
		case 'update_program':
		case 'view_programs':
			
			$msg = 'are_you_sure_you_want_to_delete_this_program';
			
		break;
	}
	
	return $CI->lang->line($msg);
}


// ------------------------------------------------------------------------

function _previous_next($type = 'next', $table = '', $id = '', $tid = false, $sql = '')
{
	$CI = & get_instance();

	if ($tid == true)
	{
		if ($id >= '0')
		{
			if ($type == 'previous')
			{
				return '<a href="' . base_url('js') . $CI->uri->segment(1) . '/' . $CI->uri->segment(2) . '/' . $CI->uri->segment(3) . '/' . $id . '/' . $CI->uri->segment(5,0). '/' . $CI->uri->segment(6,0) . '/' . $CI->uri->segment(7,0). '/' . $CI->uri->segment(8,0). '/' . $CI->uri->segment(9,0) . '/' . $CI->uri->segment(10,0) . '" class="btn btn-primary" title="' . $CI->lang->line('previous') . '"><i class="fa fa-chevron-left"></i></a>';
			}
		
			return '<a href="' . base_url('js') . $CI->uri->segment(1) . '/' . $CI->uri->segment(2) . '/' . $CI->uri->segment(3) . '/' . $id . '/' . $CI->uri->segment(5,0). '/' . $CI->uri->segment(6,0) . '/' . $CI->uri->segment(7,0). '/' . $CI->uri->segment(8,0). '/' . $CI->uri->segment(9,0) . '/' . $CI->uri->segment(10,0) . '" class="btn btn-primary" title="' . $CI->lang->line('next') . '"><i class="fa fa-chevron-right"></i></a>';
		}
		
		return;
	}
	
	$sort = $type == 'next' ? 'ASC' : 'DESC';
	$less = $type == 'next' ? ' > ' : ' < ';
	
	switch ($table)
	{
		case 'discount_groups':
		case 'affiliate_groups':
			$key = 'group_id';
		break;
		
		case 'admin_users':
			$key = 'admin_id';
		break;
		
		case 'languages':
			$key = 'language_id';
		break;
		
		case 'currencies':
			$key = 'currency_id';
		break;
		
		
		case 'countries':
			$key = 'country_id';
		break;
				
		case 'vendors':
			$key = 'vendor_id';
		break;
		
		case 'modules':
			$key = 'module_id';
		break;
		
		case 'members':
			$key = 'member_id';
		break;
		
		case 'programs':
			$key = 'program_id';
		break;
		
		case 'email_mailing_lists':
			$key = 'mailing_list_id';
		break;

		case 'content_categories':
		case 'faq_categories':
			$key = 'category_id';
		break;
		
		case 'content_articles':
		case 'faq_articles':
			$key = 'article_id';
		break;
		
		case 'coupons':
			$key = 'coupon_id';
		break;
		
		case 'countries':

			$key = 'country_id';
		break;
		
		case 'commissions':
			$key = 'comm_id';
		break;
		
		default:
			$key = 'id';
		break;
			
	}

	$sort_column = empty($sort_id) ? $key : $sort_id;
	
	$sql = 'SELECT ' . $key . ' FROM ' . $CI->db->dbprefix($table) . ' WHERE ' . $key . $less . $id . ' ' . $sql . ' ORDER BY ' . $sort_column . ' ' . $sort . ' LIMIT 1';

	$query = $CI->db->query($sql);
	
	if ($query->num_rows() > 0)
	{
		$row = $query->row_array();
		
		if ($type == 'previous')
		{
			return '<a href="' . base_url('js') . $CI->uri->segment(1) . '/' . $CI->uri->segment(2) . '/' . $CI->uri->segment(3) . '/' . $row[$key] . '" class="btn btn-primary" data-toggle="tooltip" title="' . $CI->lang->line('previous') . '"><i class="fa fa-chevron-left"></i></a>';
		}
		
		return '<a href="' . base_url('js') . $CI->uri->segment(1) . '/' . $CI->uri->segment(2) . '/' . $CI->uri->segment(3) . '/' . $row[$key] . '" class="btn btn-primary" data-toggle="tooltip" title="' . $CI->lang->line('next') . '"><i class="fa fa-chevron-right"></i> </a>';
	}
	  
	return  false;
}

// ------------------------------------------------------------------------

function _show_msg($type = '', $msg = '' )
{
	$CI = & get_instance();
	
	if ($type == 'error')
	{
		return '<div class="alert alert-danger animated shake text-capitalize hover-msg"><button type="button" class="close" data-dismiss="alert">×</button><h4><i class="fa fa-exclamation-triangle"></i> '. $CI->lang->line('errors_in_submission') . '</h4>' . $msg . '</div>';	
	}
	
	return '<div class="alert alert-success animated bounce text-capitalize alert-msg hover-msg"> <h5><button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-check"></i> ' . $msg . '</h5></div>';
}

// ------------------------------------------------------------------------

function _generate_help_center()
{
	$CI = & get_instance();
	
	if ($CI->config->item('module') == 'dashboard') return; 
	
	if (!defined('JAM_ENABLE_RESELLER_LINKS'))
	{
		
		if (file_exists(APPPATH . 'language/' . $CI->session->userdata('sess_admin_lang') . '/adm_help_center_lang.php'))
		{
			$CI->lang->load('adm_help_center', $CI->session->userdata('sess_admin_lang'));	
		}
		else
		{
			$CI->lang->load('adm_help_center', 'english');	
		}
		
		echo '<div id="help-center"><div class="row hidden-xs"><div class="col-lg-12"><div class="box-info">';
		
		echo '<h4><i class="fa fa-question-circle"></i> ' . $CI->lang->line('help_center') . '</h4>
				<div class="additional-btn">
					<a class="additional-icon" href="#" data-toggle="collapse" data-target="#help-box"><i class="fa fa-chevron-down"></i></a>
				</div>';
		
		echo '<div id="help-box" class="collapse in">';
		
		
		//show description of page here
		echo '<div class="text-capitalize"><a name="help"></a><p>';
		
		switch ($CI->config->item('module'))
		{			
			case 'integration':
			case 'layout':
			case 'programs':
			
				echo $CI->lang->line('help_center_' . $CI->config->item('module') . '_' . $CI->config->item('function'), true);	
				
			break;
			
			case 'affiliate_payments':
			
				switch ($CI->config->item('function'))
				{
					case 'view_affiliate_payments':
					
						echo $CI->lang->line('help_center_' . $CI->config->item('module') . '_' . $CI->config->item('function'), true);	
						
					break;
					
					default:
					
						echo $CI->lang->line('help_center_' . $CI->config->item('module'), true);
						
					break;	
				}
			
			break;
			
			default:
				
				echo $CI->lang->line('help_center_' . $CI->config->item('module'), true);
				
			break;	
		}
		
		echo '</p></div>';
		
		echo '<p>
				<a href="https://jam.jrox.com/redirect/videos.php?q=' . $CI->config->item('module') . '-' . $CI->config->item('function') . '" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-play-circle"></i> ' . $CI->lang->line('watch_videos') . '</a>
				<a href="https://jam.jrox.com/redirect/kb.php?q=' . $CI->config->item('module') . '-' . $CI->config->item('function') . '" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-question-circle"></i> ' . $CI->lang->line('view_kb_articles') . '</a>
				<a href="https://jam.jrox.com/redirect/forums.php?q=' . $CI->config->item('module') . '-' . $CI->config->item('function') . '" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-users"></i> ' . $CI->lang->line('visit_community_forums') . '</a>
			</p>';
		
		echo '</div></div></div></div></div>';
	}
	
	
}

// ------------------------------------------------------------------------

function _generate_sub_headline($page = '', $info = '')
{
	$CI =& get_instance();
	
	switch ($page)
	{
		case 'commissions':
			
			if ($CI->uri->segment(7) && $CI->uri->segment(8))
			{
				$CI->db->where($CI->uri->segment(7), $CI->uri->segment(8));	
			}
			
			$t = $CI->db->count_all_results('commissions');
			
			$page = $t . ' ' . $CI->lang->line('total_' . $page);
			
		break;
		
		case 'follow_ups';
		
			$page = $CI->lang->line('view_follow_ups') . ' - ' . $info;
			
		break;
		
		case 'admin_users':
			$t = $CI->db->count_all($page);
			
			if ($t > 0)
			{
				$page = $t . ' ' . $CI->lang->line('total_' . $page);
			}
		break;	
		
		case 'total_members':

			$page = number_format($info) . ' ' . $CI->lang->line($page);
			
		break;
		
		case 'manage_member':
		 
		 	$page = $CI->lang->line($page) . ' ' .  $info;
		 	
		 break;
		
		default:
			
			$page = $CI->lang->line($page);
			
		break;
	}
	
	$a = '<h2 class="sub-header block-title"><i class="fa fa-desktop"></i> ' . $page . '</h2>';
	echo $a;
	//echo '<a class="btn btn-info iframe" href="http://www.youtube.com/v/F1xRW1CYpik?rel=0&wmode=transparent">Need Help with this page? Watch this tutorial.</a>';
	//echo $CI->uri->uri_string();
}

// ------------------------------------------------------------------------

function _check_ssl($url = '')
{
	if ($_SERVER['SERVER_PORT'] == '443')
	{
		return 'https://' . $url;	
	}
	
	return 'http://' . $url;
}

// ------------------------------------------------------------------------

function _content_filter($body = '')
{
	$CI =& get_instance();
	
	if (is_array($CI->config->item('dbi_content_filter')))
	{
		foreach ($CI->config->item('dbi_content_filter') as $k => $v)
		{
			$body = str_replace($k, $v, $body);	
		}
	}
	
	return html_entity_decode($body, ENT_QUOTES, $CI->config->item('charset'));
}

// ------------------------------------------------------------------------

function change_content_type($value = '')
{
	$CI =& get_instance();
	
	switch ($value)
	{
		case '1':
			
			return $CI->lang->line('blog');
			
		break;
		
		case '2':
		
			return $CI->lang->line('standard');
		
		break;
		
		case '3':
		
			return $CI->lang->line('advanced');
		
		break;
	}
}

// ------------------------------------------------------------------------

function load_form($type = '', $file ='', $data = '', $stream = false)
{
	$CI =& get_instance();
	
	if (file_exists(APPPATH . '/views/' . $type . '/' . '/custom_templates/' . $file . '.php' ))
	{
		$body = '/custom_templates/' . $file;
			
	}
	else 
	{
		$body = $file;
	}
	
	if ($stream == true)
	{
		return $CI->load->view($type . '/' . $body, $data, true);
	}
	else
	{
		echo header('Content-type: text/html; charset=' . $CI->config->item('charset'));
		$CI->load->view($type . '/' . $body, $data);
	}
}


// ------------------------------------------------------------------------

function load_admin_tpl($type = '', $file = '', $data = '', $top = true, $bottom = true)
{
	$CI =& get_instance();
	
	$template = '';
	
	if ($top == true)
	{
		//load header file
		$template .= $CI->load->view('admin/tpl_adm_header', $data, true);
	}
	
	//load content
	$template .= $CI->load->view($type . '/' . $file, $data, true);
	
	if ($bottom == true)
	{
		//load footer
		$template .= $CI->load->view('admin/tpl_adm_footer', $data, true);
	}
	
	if ($CI->config->item('enable_profiler') == true)
	{
		$CI->load->library('profiler');				

		$template = $CI->profiler->run();
	}
			
	echo header('Content-type: text/html; charset=' . $CI->config->item('charset'));
	echo $template;
}

// ------------------------------------------------------------------------

function _get_bread_crumbs($type = '')
{
	$CI = & get_instance();
	
	if ($type == 'admin')
	{
		$a = '<ol class="breadcrumb"><li><a href="' . admin_url() . '"><i class="fa fa-home"></i> ' .  $CI->lang->line('dashboard') . '</a></li> ';
		
		if ($CI->uri->segment(2))
		{
			$admin_url = admin_url();
			
			if ($CI->uri->segment(1) == 'modules')
			{
				$a .= '<li><a href="' . base_url('js') . 'modules/' . $CI->uri->segment(2) . '">';
			}
			
			else
			{
				$a .= '<li><a href="' . admin_url() . $CI->uri->segment(2) . '">';
			}
			$name = $CI->uri->segment(2);
			
			if ($name == 'follow_ups') $name = 'mailing_lists';
			if ($CI->lang->line(strtolower($name)))
			{							   
				$a .= $CI->lang->line(strtolower($name));
			}
			else
			{
				$a .= str_replace('_', ' ', str_replace('module_', '', $name));
			}
			
			$a .= '</a></li>';
		}
		
		
		
		if ($CI->uri->segment(3))
		{
			$a .= '<li>';
			
			
			if ($name == 'follow_ups') $name = 'mailing_lists';
			if ($CI->lang->line(strtolower($CI->uri->segment(3))))
			{							   
				$a .= $CI->lang->line(strtolower($CI->uri->segment(3)));
			}
			else
			{
				$a .= str_replace('_', ' ', $CI->uri->segment(3));
			}
			
			
			$a .= '</li></ol>';
		}
	}
	else
	{ 
		$a = '<ol class="breadcrumb"><li><a href="' . site_url('members') . '"><i class="fa fa-home"></i> ' . $CI->lang->line('home') . '</a></li>';
		
		if (!$CI->uri->segment(2))
		{
			if ($CI->session->userdata('m_username'))
			{
				$a .= '<li>' . $CI->lang->line('logged_in_as') .' ' . $CI->session->userdata('m_username') . ' | ' . $CI->lang->line('last_login') . ': ' . $CI->session->userdata('m_ll_date') . ' | ' . $CI->session->userdata('m_ll_ip') . '</li>' ;
			}
		}
		else
		{
			$a .= ' &gt; ' . '<a href="' . site_url('members') . '/' . $CI->uri->segment(2) . '">' . str_replace("_", " ", $CI->uri->segment(2)) . '</a>';
		}
		
		if ($CI->uri->segment(3))
		{
			$a .= ' &gt; ' . str_replace("_", " ", $CI->uri->segment(3));
		}
	
		$a = str_replace('module', '', $a);
		
		$a .= '</ol>';
	}
	
	
	
	return $a;
}

// ------------------------------------------------------------------------

function _load_custom_form_fields($type = '') //load the custom fields for use on forms
{
	$CI = & get_instance();
	
	$CI->db->order_by($CI->config->item('dbs_cff_column'), $CI->config->item('dbs_cff_order')); 	
	$CI->db->where('form_type', $type);
	$query = $CI->db->get('form_fields');
	
	if ($query->num_rows() > 0)
	{
		return $query->result_array();
	}
	 
	return  false;
}

// ------------------------------------------------------------------------

function _check_form_fields($name = '', $fields = array())
{
	$CI = & get_instance();

	if (!empty($fields[$name]))
	{
		return $fields[$name];	
	}
	
	return $CI->lang->line($name);
}


// ------------------------------------------------------------------------

function _check_custom_name($name = '', $fields = array())
{
	$CI = & get_instance();
	
	foreach ($fields as $k => $v)
	{
		if ($name == $v['form_field_name'])
		{
			return $v['form_field_description'];	
		}
	}
	
	return $CI->lang->line($name);
}

// ------------------------------------------------------------------------

function load_field_type($type = '', $options = '', $selected = '', $class = '', $mod = 'public')
{
	switch ($type)
	{
		case "text":
			
			$data = '<input name="' . $options['form_field_name'] . '" type="text" id="' . $options['form_field_name'] . '" value="' . $selected . '" class="' . $class .'"/>';
			
		break;
		
		case "hidden":
			
			if ($mod == 'admin')
			{
				$data = '<input name="' . $options['form_field_name'] . '" type="text" id="' . $options['form_field_name'] . '" value="' . $selected . '" class="' . $class .'"/>';
			}
			else
			{
			
				$data = '<div>' . $selected . '<input name="' . $options['form_field_name'] . '" type="hidden" id="' . $options['form_field_name'] . '" value="' . $selected . '" class="' . $class .'"/></div>';
			}
			
		break;
		
		case "textarea":
		
			$data = '<textarea name="' . $options['form_field_name'] . '" id="' . $options['form_field_name'] . '" cols="45" rows="5" class="' . $class .'">' . $selected . '</textarea>';
		
		break;
		
		case "dropdown":
		
			$data = '<select name="' . $options['form_field_name'] . '" class="' . $class .'">';
			
			//explode the field values
			$values = explode(",", $options['form_field_values']);
			
			foreach ($values as $v)
			{
				//explode the name value pairs
				$value = explode("|", $v);
				
				if (!empty($value[0]))
				{
					if ($value[0] == $selected)
					{
						$data .= '<option value="' . trim($value[0]) . '" selected="selected">' . trim($value[1]) . '</option>';
					}
					else
					{
						$data .= '<option value="' . $value[0] . '">' . $value[1] . '</option>';
					}
				}
			}

			$data .= '</select>';
		
		break;
	}
	
	return $data; 
}

// ------------------------------------------------------------------------

function _generate_form_dropdown($form_type = '', $form_name = '', $form_values = '', $form_selected = '', $form_options = '', $value = '', $name = '', $selected = '')
	{
		/*
		|------------------------------------------------------
		| this generates the form input such as text / textarea
		|
		| -----------------------------------------------------
		| 
		| $form_type = type of form such as text or dropdown
		| $form_name = the name / id for the form field
		| $form_values = options for the form
		| $form_selected = the selected values if Any.
		| $form_options = extra form field code
		| $value = form dropdown value
		| $name = form dropdown name
		| $selected = the selected id in the dropdown
		|
		|------------------------------------------------------
		*/
		
		$CI = & get_instance();
		
		//first format the form_values array...
		$options = format_array($form_values, $value, $name);
		
		//and the form_selected array
		$selected_options = format_array($form_selected, $selected);
		
		switch ($form_type)
		{
			case "dropdown":
				$m = form_dropdown($form_name, $options, $selected_options, $form_options);
			break;
			
			case "multiple-dropdown":
				$m = form_dropdown($form_name.'[]', $options, $selected_options, 'multiple="multiple" id="' . $form_name . '"' . $form_options . '"');
			break;

		}
		
		return $m;
		
	}
	

// ------------------------------------------------------------------------

function _get_program_themes($type = 'main')
{
	$CI = & get_instance();
	
	$CI->load->helper('directory');
	
	$map = directory_map('./themes/' . $type);
	
	$themes = array();
	
	foreach ($map as $k => $v)
	{
		if (file_exists(PUBPATH . '/themes/' . $type . '/' . $k . '/theme_info.php'))
		{
			$themes[$k] = $k;	
		}
	}

	return $themes;
}

// ------------------------------------------------------------------------

function _generate_settings_field($v = '', $value = '', $attributes = '')
{
	$CI = & get_instance();
	
	switch ($v['settings_type'])
	{
		case 'textarea':
			
		switch ($v['settings_function'])
		{
			case 'base64_decode';
			
				$value = base64_decode($value);
				
			break;
		}
		
		$data = array(
						  'name'	=> $v['settings_key'],
						  'id'	=> $v['settings_key'],
						  'value'	=> $value,
						  'class'	=> 'form-control'
						 );

			return form_textarea($data);
				
		
		break;
		
		case 'hidden':	

			return form_hidden($v['settings_key'], $value);
			
		break;
		
		case 'text':
		
			$data = array(
						  'name'	=> $v['settings_key'],
						  'id'	=> $v['settings_key'],
						  'value'	=> $value,
						  'class'	=> 'form-control'
						 );

			
			switch ($v['settings_function'])
			{
				case 'license_id':
				
					if (defined('JEM_ENABLE_RESELLER_LINKS'))
					{
						return '<a href="' . $CI->config->item('customizer_reseller_direct_url') .'">' . $CI->config->item('customizer_reseller_company_name') . '</a>';
					}
				
				break;
				
				case 'encrypt':
				
					$data['value'] = $CI->encrypt->decode($value);
					
				break;
				
				case 'date':
					
					$data['value'] = _format_date($value, $CI->config->item('format_date2'));
					$data['class'] = 'form-control datepicker-input';
				
				break;
			}
			
			return form_input($data);
			
		break;
		
		case 'readonly':
		
			return $value;
		
		break;
		
		case 'dropdown':
			
			$attributes = array('id' => $v['settings_key'], 'class' => 'select');
			
			switch ($v['settings_function'])
			{
				case 'cc_auth_type':
				
					$options = array('AUTH_CAPTURE' => 'Authorization and Capture',
									 'AUTH_ONLY' => 'Authorization Only');
				
				break;
				
				case 'timezone_menu':
				 
					$CI->load->helper('date');
					
					return timezone_menu($v['settings_value'], 'timezones form-control', 'sts_site_default_timezone');
					
				break;
				
				case 'forced_matrix_spillover':
					
					$options = array(	'none' => $CI->lang->line('no_sponsor'),
									 	'random' => $CI->lang->line('random_affiliate'),
										'specific'	=> $CI->lang->line('specific_affiliate'),
                                        'original_sponsor'	=> $CI->lang->line('original_sponsor'),
										);
					
				break;
				
				case 'paper_size':
				
					$options = array(	'letter' => 'letter',
										'legal'	=> 'legal',
										'11x17' => '11x17');
										
				break;
				
				case 'commission_status':
				
					$options = array(	'all' => 'all',
										'pending'	=> 'pending',
										'unpaid' => 'unpaid',
										'paid' => 'paid',
										);
										
				break;
				
				
				case 'default_home_page':
				
					$options = array(	'login' => 'login',
										'registration'	=> 'registration',
										'description' => 'description');
				break;
				
				case 'paper_orientation':
					
					$options = array(	'landscape' => 'landscape',
										'portrait'	=> 'portrait');
										
				break;
				
				case 'order_status':
				
					$options = array(	'order_pending' => $CI->lang->line('order_pending'),
									 	'order_processing' => $CI->lang->line('order_processing'),
										'order_completed' => $CI->lang->line('order_completed')
									 
									 );
					
				break;
				
				case 'editor':
					
					$options = array(	'innovaeditor' => 'innovaeditor');
										
				break;
				
				case 'affiliate_performance_bonus_required':
					
					$options = array(	'commission_amount' => $CI->lang->line('commission_amount'),
										'sales_amount' => $CI->lang->line('sales_amount'));
				
				break;
				
				case 'cart_commissions':
					$options = array(	'total_sale' => $CI->lang->line('total_sale'),
										'per_product'	=> $CI->lang->line('per_product'));
				break;
				
				case 'affiliate_group':
					
					$array = $CI->db_validation_model->_get_details('affiliate_groups');
					$options = format_array($array, 'group_id', 'aff_group_name');
					
				break;
				
				case 'commission_refund_option':
				
					$options = array(	'none'	=>	$CI->lang->line('do_nothing'),
										'delete'	=>	$CI->lang->line('delete'),
										'pending'	=>	$CI->lang->line('pending')
									);
				
				break;
				
				case 'affiliate_link_type':
				
					$options = array('regular' => $CI->lang->line('regular'), 'subdomain' => $CI->lang->line('subdomain'), 'replicated_site' => $CI->lang->line('replicated_site'));
				
				break;
				
				case 'affiliate_link_username_id':
					
					$options = array('username' => $CI->lang->line('username'), 'id' => $CI->lang->line('id'));
				
				break;
				
				case 'video_control_bar':
					
					$options = array('bottom' => 'bottom', 'over' => 'over', 'none' => 'none');
					
				break;
				
				case 'affiliate_performance_bonus':
				
					$options = array('group_upgrade' => $CI->lang->line('group_upgrade'), 'payment_amount' => $CI->lang->line('payment_amount'));
				
				break;
				
				case 'affiliate_program_type':
				
					$options = array(	'pay-per-sale' => $CI->lang->line('pay-per-sale'), 
										//'pay-per-lead' => $CI->lang->line('pay-per-lead'), 
										//'pay-per-click' => $CI->lang->line('pay-per-click')
										);
					
				break;
				
				case 'delimiter':
				
					$options = array('comma' => 'comma', 'tab' => 'tab', 'semicolon' => 'semicolon');
				
				break;
				
				case 'product_listing_style':
					
					$options = array('grid' => 'grid', 'list' => 'list');
				
				break;
				
				case 'backup_option':
				
					$options = array('daily' => $CI->lang->line('daily'), 'weekly' => $CI->lang->line('weekly'), 'monthly' => $CI->lang->line('monthly'));
				
				break;
				
				case 'boolean':
					
            		$options = array('1' => $CI->lang->line('enable'), '0' => $CI->lang->line('disable'));
            		
				break;
				
				case 'yes_no':
					
            		$options = array('1' => $CI->lang->line('yes'), '0' => $CI->lang->line('no'));
            		
				break;
				
				case 'commission_type':
					$options = array('flat' => $CI->lang->line('flat'), 'percent' => $CI->lang->line('percent'));
				break;
				
				case 'countries':
					
					$CI->load->helper('country');		
		
					$options = _load_countries_dropdown($countries = _load_countries_array());

				break;
				
				case 'currencies':	
				
					$currency_array = $CI->db_validation_model->_get_details('currencies');
					$options = format_array($currency_array, 'code', 'title');
				
				break;
				
				case 'date_format':
				
					$options = array(
										'mm/dd/yyyy:m/d/Y:M d Y' => 'mm/dd/yyyy',
										'dd/mm/yyyy:d/m/Y:d M Y' => 'dd/mm/yyyy',
										'yyyy/mm/dd:Y/m/d:Y M d' => 'yyyy/mm/dd',
									);
				
				break;
				
				
				case 'image_library':
				
					$options = array('GD' => 'GD', 'GD2' => ' GD2', 'ImageMagick' => 'ImageMagick', 'NetPBM' => 'NetPBM');
					
				break;
				
				case 'language':
					
					$lang_array = $CI->db_validation_model->_get_details('languages', '', 'status' , '1');

					$lang_admin_array = array();
					
					foreach ($lang_array as $lang_value)
					{
						$lang_value_path = APPPATH . 'language/' . $lang_value['name'] . '/adm_main_lang.php';
		
						if (file_exists($lang_value_path))
						{
							array_push($lang_admin_array, $lang_value);
						}
					}
					
					$options = format_array($lang_admin_array, 'name', 'name');
			
				break;
				
				case 'pending_commission':
					$options = array('no_pending' => $CI->lang->line('pending_no_email'), 
									 'alert_pending' => $CI->lang->line('pending_send_email'), 
									 'no_unpaid' => $CI->lang->line('unpaid_no_email'), 
									 'alert_unpaid' => $CI->lang->line('unpaid_send_email'));
				break;
				
				case 'mailer_type':
					
					$options = array('php' => 'php', 'smtp' => 'smtp', 'sendmail' => 'sendmail', 'qmail' => 'qmail');
				
				break;
				
				case 'player_link':
					
					$options = array('play'	=> 'play', 'link'	=> 'link');
				
				break;
				
				case 'ssl_tls':
					
					$options = array('none' => 'none', 'ssl' => 'ssl', 'tls' => 'tls');
					
				break;
				
				case 'mailing_list':
				
					$list_array = $CI->db_validation_model->_get_details('email_mailing_lists');
					$options = format_array($list_array, 'mailing_list_id', 'mailing_list_name', true, 'none');	
				
				break;
				
				case 'numbers_5':
					
					$options = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);
				
				break;
				
				case 'numbers_10':
					
					$options = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10);
				
				break;
				
				case 'matrix_width':
				
					for ($i=2; $i <=5; $i++)
					{
						$options[$i] = $i;
					}
				
				break;
				
				case 'numbers_30':
				
					$i = 1;
					while ($i <=30)
					{
						$options[$i] = $i;
						$i++;
					}
				break;
				
				case 'numbers_50':

					$options = array(50 => 50, 100 => 100, 250 => 250, 500 => 500, 1000 => 1000);
					
				break;
				
				case 'enable':
				
					$options = array(0 => $CI->lang->line('disable'), 1 => $CI->lang->line('enable'));
				
				break;
				
				case 'themes':
					
					$options = _get_program_themes();

				break;
				
				case 'weight':
					
					$options = array('lbs' => $CI->lang->line('lbs'), 'kg' => $CI->lang->line('kg'));
				
				break;
				
				case 'tax_zone':
				
					$array = $CI->db_validation_model->_get_details('tax_zones');
					$options = format_array($array, 'tax_zone_id', 'tax_zone_name');	
					
				break;
				
				case 'zone_calc':
				
					$options = array('item' => $CI->lang->line('item'), 'price' => $CI->lang->line('price'), 'weight' => $CI->lang->line('weight'));
					
				break;
				
				case 'address_type':
				
					$options = array('billing' => $CI->lang->line('billing'), 'shipping' => $CI->lang->line('shipping'));
				
				break;
			}
			
			return form_dropdown($v['settings_key'], $options, $value, 'class="form-control"');
	
		break;
	}
	
}

// ------------------------------------------------------------------------

function _check_close_buttons($type = '')
{
	$CI = & get_instance();
	
	if ($type == 'click')
	{
		return 'window.opener.location.reload();self.close();return false;';
	} 
	else
	{ 
		return 'href="javascript:void(0);" onClick="window.opener.location.reload();self.close();return false;"';
	}
}

// ------------------------------------------------------------------------

function _check_admin_popups($url = '', $name = '', $height = '300', $width = '500', $class = '', $style = '', $id = '')
{
	$CI = & get_instance();

	return  '<a href="' . $url . '" id="' . $id . '" onclick="window.open(this.href,\'popUpWindow\',\'height=' . $height . ',width=' . $width . ',left=100,top=100,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no, status=no\'); return false" class="' . $class . ' " style="' . $style . '">' . $name . '</a>';
}

// ------------------------------------------------------------------------

function _check_thickbox()
{
	$CI = & get_instance();
	
	if ($CI->config->item('sts_image_enable_thickbox') == 1)
	{
		if ($CI->config->item('images_thickbox_class'))
		{
			return 'class="' . $CI->config->item('images_thickbox_class') . '"';
		}
		else
		{
			return 'class="thickbox"';
		}
		
		
		//return 'class="MagicZoom MagicThumb"';
		//return 'class="MagicZoom MagicThumb" rel="zoom-width: 400px; zoom-position: bottom';
	}
	else
	{
		return 'class="popupwindow" rel="windowCenter" target="name"';
	}
}

// ------------------------------------------------------------------------

function _check_image_thumbnail($type = '', $v = '', $resize = 1, $height = '', $width = '', $thickbox = true, $rel = true, $class = 'thumb-images')
{
	$CI = & get_instance();
	
	if ($resize == 1)
	{
		$a = base_url() . 'images/' .  $type . '/' . $v['raw_name'].'_jrox'.$v['file_ext'];
	}
	else
	{
		$a = base_url() . 'images/' .  $type . '/' . $v['photo_file_name'];
	}
	
	if ($thickbox == true)
	{
		$rel_code = $rel == true ? 'rel="products"' : '';
		
		$b = base_url() . 'images/' .  $type . '/' . $v['photo_file_name'];
		$thickbox_code = _check_thickbox();
		$url = '<a href="' . $b . '" ' . $thickbox_code . ' ' . $rel_code . '>';
		$url .=  '<img src="' . $a . '" class="' . $class . '" alt="" />';
	    $url .= '</a>';
		
	}
	else
	{
		$url = '<img src="' . $a . '" class="' . $class . '" alt=""/>';
	}
	
	

	return $url;  
}

// ------------------------------------------------------------------------

function _generate_dynamic_tags($textarea = '', $url = false, $dynamic_tags = true)
{
	$CI =& get_instance();
	
	if ($dynamic_tags == true)
	{
		//setup dynamic tags
		$data = '<select class="form-control">';
		$data .= '<option value = "">' . $CI->lang->line('allowed_tags') . '</option>';	
			
		if ($url == true) //use for menu maker
		{
			$options = array(	'home', 'site', 'members_login'
							);
			
			sort($options);
			foreach ($options as $v)
			{
				$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
			}	
		}
		else
		{
		
				
			$data .= '<option value="{site_name}">' . $CI->lang->line('site_name') . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {site_name}&nbsp;&nbsp;&nbsp; </option>';
			$data .= '<option value="{login_url}">' . $CI->lang->line('login_url') . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {login_url}&nbsp;&nbsp;&nbsp; </option>';
			
			switch ($textarea)
			{
				
				case 'admin_affiliate_commission_generated_template':
				
					$options = array('member_username', 'commission_amount' ,'fname', 'current_date', 'admin_login_url');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
				
				break;
				
				case 'admin_alert_new_signup_template':
					
					$options = array('member_name', 'member_username' ,'signup_ip', 'current_time');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
								
				case 'admin_failed_login_template';
				
					$options = array('admin_login_url', 'username' ,'password', 'date', 'ip_address');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
				
				break;
				
				case 'admin_reset_password_template':
					
					$options = array('username', 'new_password');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
				case 'member_affiliate_downline_signup':
					
					$options = array('downline_name', 'downline_email', 'fname');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
				case 'member_affiliate_commission_generated_template':
				
					$options = array('commission_amount', 'current_date',
									 'affiliate_link', 
									 'fname' , 'lname', 'username', 'company',
									 'billing_address_1', 'billing_address_2', 'billing_city',
									 'billing_state', 'billing_country', 'billing_postal_code',
									 'payment_name', 'payment_address_1', 'payment_address_2',
									 'payment_city', 'payment_state', 'payment_country', 
									 'payment_postal_code', 'home_phone', 'work_phone',
									 'mobile_phone', 'fax', 'website',
									 'custom_field_1', 'custom_field_2',
									 'custom_field_3', 'custom_field_4',
									 'custom_field_5', 'custom_field_6',
									 'custom_field_7', 'custom_field_8',
									 'custom_field_9', 'custom_field_10',
									 'custom_field_11', 'custom_field_12',
									 'custom_field_13', 'custom_field_14',
									 'custom_field_15', 'custom_field_16',
									 'custom_field_17', 'custom_field_18',
									 'custom_field_19', 'custom_field_20');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
				case 'member_affiliate_payment_sent_template':
				
					$options = array('payment_amount', 'affiliate_note');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
				case 'member_affiliate_performance_bonus_amount_template':
				
					$options = array('bonus_amount','store_link', 'affiliate_link', 
									 'fname' , 'lname', 'username', 'company',
									 'billing_address_1', 'billing_address_2', 'billing_city',
									 'billing_state', 'billing_country', 'billing_postal_code',
									 'payment_name', 'payment_address_1', 'payment_address_2',
									 'payment_city', 'payment_state', 'payment_country', 
									 'payment_postal_code', 'home_phone', 'work_phone',
									 'mobile_phone', 'fax', 'website',
									 'custom_field_1', 'custom_field_2',
									 'custom_field_3', 'custom_field_4',
									 'custom_field_5', 'custom_field_6',
									 'custom_field_7', 'custom_field_8',
									 'custom_field_9', 'custom_field_10',
									 'custom_field_11', 'custom_field_12',
									 'custom_field_13', 'custom_field_14',
									 'custom_field_15', 'custom_field_16',
									 'custom_field_17', 'custom_field_18',
									 'custom_field_19', 'custom_field_20');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
				case 'member_affiliate_performance_group_upgrade_template':
				
					$options = array('upgraded_affiliate_group','store_link', 'affiliate_link', 
									 'fname' , 'lname', 'username', 'company',
									 'billing_address_1', 'billing_address_2', 'billing_city',
									 'billing_state', 'billing_country', 'billing_postal_code',
									 'payment_name', 'payment_address_1', 'payment_address_2',
									 'payment_city', 'payment_state', 'payment_country', 
									 'payment_postal_code', 'home_phone', 'work_phone',
									 'mobile_phone', 'fax', 'website',
									 'custom_field_1', 'custom_field_2',
									 'custom_field_3', 'custom_field_4',
									 'custom_field_5', 'custom_field_6',
									 'custom_field_7', 'custom_field_8',
									 'custom_field_9', 'custom_field_10',
									 'custom_field_11', 'custom_field_12',
									 'custom_field_13', 'custom_field_14',
									 'custom_field_15', 'custom_field_16',
									 'custom_field_17', 'custom_field_18',
									 'custom_field_19', 'custom_field_20');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
				case 'member_affiliate_send_downline_email':
					
					$options = array('downline_sponsor_name', 'downline_sponsor_email', 'downline_sponsor_affiliate_link',
									 'downline_member_name', 'downline_member_username', 'downline_member_email',
									 'downline_member_id', 'downline_member_affiliate_link', 'downline_message_html',
									 'downline_message_text');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
				case 'member_email_confirmation_template':
					
					$options = array('fname', 'confirm_link');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
				case 'member_affiliate_commission_stats_template':
					
					$options = array('affiliate_link', 
									 'current_month', 'current_year',
									 'current_month_unpaid_commissions',
									 'current_month_paid_commissions',
									 'total_unpaid_commissions',
									 'total_paid_commissions',
									 'fname' , 'lname', 'username', 'company',
									 'billing_address_1', 'billing_address_2', 'billing_city',
									 'billing_state', 'billing_country', 'billing_postal_code',
									 'payment_name', 'payment_address_1', 'payment_address_2',
									 'payment_city', 'payment_state', 'payment_country', 
									 'payment_postal_code', 'home_phone', 'work_phone',
									 'mobile_phone', 'fax', 'website',
									 'custom_field_1', 'custom_field_2',
									 'custom_field_3', 'custom_field_4',
									 'custom_field_5', 'custom_field_6',
									 'custom_field_7', 'custom_field_8',
									 'custom_field_9', 'custom_field_10',
									 'custom_field_11', 'custom_field_12',
									 'custom_field_13', 'custom_field_14',
									 'custom_field_15', 'custom_field_16',
									 'custom_field_17', 'custom_field_18',
									 'custom_field_19', 'custom_field_20');
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
				case 'member_login_details_template':
					
					$options = array('affiliate_link', 
									 'fname' , 'lname', 'username', 'company',
									 'billing_address_1', 'billing_address_2', 'billing_city',
									 'billing_state', 'billing_country', 'billing_postal_code',
									 'payment_name', 'payment_address_1', 'payment_address_2',
									 'payment_city', 'payment_state', 'payment_country', 
									 'payment_postal_code', 'home_phone', 'work_phone',
									 'mobile_phone', 'fax', 'website',
									 'custom_field_1', 'custom_field_2',
									 'custom_field_3', 'custom_field_4',
									 'custom_field_5', 'custom_field_6',
									 'custom_field_7', 'custom_field_8',
									 'custom_field_9', 'custom_field_10',
									 'custom_field_11', 'custom_field_12',
									 'custom_field_13', 'custom_field_14',
									 'custom_field_15', 'custom_field_16',
									 'custom_field_17', 'custom_field_18',
									 'custom_field_19', 'custom_field_20');
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
				case 'member_reset_password_template':
					
					$options = array('fname', 'new_password' );
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
					
				case 'email_send-member':
				case 'email_send-mailing_list':
				
					$options = array('affiliate_link', 
									 'fname' , 'lname', 'username', 'company',
									 'billing_address_1', 'billing_address_2', 'billing_city',
									 'billing_state', 'billing_country', 'billing_postal_code',
									 'payment_name', 'payment_address_1', 'payment_address_2',
									 'payment_city', 'payment_state', 'payment_country', 
									 'payment_postal_code', 'home_phone', 'work_phone',
									 'mobile_phone', 'fax', 'website',
									 'custom_field_1', 'custom_field_2',
									 'custom_field_3', 'custom_field_4',
									 'custom_field_5', 'custom_field_6',
									 'custom_field_7', 'custom_field_8',
									 'custom_field_9', 'custom_field_10',
									 'custom_field_11', 'custom_field_12',
									 'custom_field_13', 'custom_field_14',
									 'custom_field_15', 'custom_field_16',
									 'custom_field_17', 'custom_field_18',
									 'custom_field_19', 'custom_field_20');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}	
					
				break;
				
				case 'content_pages':
					$options = array('username','primary_email','fname','lname','member_id','fname','lname','username','company','billing_address_1','billing_address_2','billing_city','billing_state','billing_country','billing_postal_code','payment_name','payment_address_1','payment_address_2','payment_city','payment_state','payment_country','payment_postal_code','home_phone','work_phone','mobile_phone','fax','payment_preference_amount','primary_email','facebook_id','facebook_link','twitter_id','linkedin_id','myspace_id','paypal_id','moneybookers_id','payza_id','custom_id','bank_transfer','enable_custom_url','custom_url_link','website','program_custom_field_1','program_custom_field_2','program_custom_field_3','program_custom_field_4','program_custom_field_5','program_custom_field_6','program_custom_field_7','program_custom_field_8','program_custom_field_9','program_custom_field_10','program_custom_field_11','program_custom_field_12','program_custom_field_13','program_custom_field_14','program_custom_field_15','program_custom_field_16','program_custom_field_17','program_custom_field_18','program_custom_field_19','program_custom_field_20','programs','affiliate_group','program_id','program_name','signup_link','sponsor_member_id','sponsor_fname','sponsor_lname','sponsor_username','sponsor_company','sponsor_billing_address_1','sponsor_billing_address_2','sponsor_billing_city','sponsor_billing_state','sponsor_billing_country','sponsor_billing_postal_code','sponsor_payment_name','sponsor_payment_address_1','sponsor_payment_address_2','sponsor_payment_city','sponsor_payment_state','sponsor_payment_country','sponsor_payment_postal_code','sponsor_home_phone','sponsor_work_phone','sponsor_mobile_phone','sponsor_fax','sponsor_primary_email','sponsor_facebook_id','sponsor_facebook_link','sponsor_twitter_id','sponsor_linkedin_id','sponsor_myspace_id','sponsor_paypal_id','sponsor_moneybookers_id','sponsor_payza_id','sponsor_custom_id','sponsor_bank_transfer','sponsor_enable_custom_url','sponsor_custom_url_link','sponsor_website','sponsor_program_custom_field_1','sponsor_program_custom_field_2','sponsor_program_custom_field_3','sponsor_program_custom_field_4','sponsor_program_custom_field_5','sponsor_program_custom_field_6','sponsor_program_custom_field_7','sponsor_program_custom_field_8','sponsor_program_custom_field_9','sponsor_program_custom_field_10','sponsor_program_custom_field_11','sponsor_program_custom_field_12','sponsor_program_custom_field_13','sponsor_program_custom_field_14','sponsor_program_custom_field_15','sponsor_program_custom_field_16','sponsor_program_custom_field_17','sponsor_program_custom_field_18','sponsor_program_custom_field_19','sponsor_program_custom_field_20','sponsor_file_type','sponsor_original_file_name',);
					
					foreach ($options as $v)
					{
						$data .= '<option value="{m_' . $v . '}">' . '{m_' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}	
					
				break;
				
				case 'followups':
					
					$options = array('affiliate_link', 
									 'fname' , 'lname', 'username', 'company',
									 'billing_address_1', 'billing_address_2', 'billing_city',
									 'billing_state', 'billing_country', 'billing_postal_code',
									 'payment_name', 'payment_address_1', 'payment_address_2',
									 'payment_city', 'payment_state', 'payment_country', 
									 'payment_postal_code', 'home_phone', 'work_phone',
									 'mobile_phone', 'fax', 'website',
									 'store_address', 'store_city', 'store_state', 
									 'store_country', 'store_postal_code', 'store_phone',
									 'current_date', 'current_time', 'signup_ip',
									 'custom_field_1', 'custom_field_2',
									 'custom_field_3', 'custom_field_4',
									 'custom_field_5', 'custom_field_6',
									 'custom_field_7', 'custom_field_8',
									 'custom_field_9', 'custom_field_10',
									 'custom_field_11', 'custom_field_12',
									 'custom_field_13', 'custom_field_14',
									 'custom_field_15', 'custom_field_16',
									 'custom_field_17', 'custom_field_18',
									 'custom_field_19', 'custom_field_20',
									 'member_id', 'mailing_list_id', 'unsubscribe_link');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}	
					
				break;
				
				case 'textads':
				case 'articleads':
				case 'htmlads':
				case 'emailads':
					
					$options = array('site_link', 'affiliate_link', 'fname' , 'lname', 'username', 'primary_email',
									'custom_field_1', 'custom_field_2',
									 'custom_field_3', 'custom_field_4',
									 'custom_field_5', 'custom_field_6',
									 'custom_field_7', 'custom_field_8',
									 'custom_field_9', 'custom_field_10',
									 'custom_field_11', 'custom_field_12',
									 'custom_field_13', 'custom_field_14',
									 'custom_field_15', 'custom_field_16',
									 'custom_field_17', 'custom_field_18',
									 'custom_field_19', 'custom_field_20',
									 'qr_code' 
									);
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}		
					
				break;
				
				case 'email_template_body_html':
				case 'send_member_email':			
				
					$options = array('username', 'fname' , 'lname');
					
					foreach ($options as $v)
					{
						$data .= '<option value="{' . $v . '}">' . $CI->lang->line($v) . ' &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; {' . $v . '}&nbsp;&nbsp;&nbsp; </option>';
					}				
				break;
			}
		}
		
		$data .= '</select>';
		
		return $data;
	}
		
	return false;
}

// ------------------------------------------------------------------------

function _preview_module_code($data = '')
{
	$qr_code = '<img src="' . _public_url() . 'qr/article_ads/1/preview" border="0" />';
				
	$data = str_replace('{qr_code}', $qr_code, $data);	
	
	return $data;
}

// ------------------------------------------------------------------------

function _get_module_links($type = '', $html = false, $sort_column = 'module_name', $sort_order = 'ASC')
{
	$CI = & get_instance();
	
	$CI->db->where('module_type', $type);
	$CI->db->order_by($sort_column, $sort_order);
	$query = $CI->db->get('modules');
	
	if ($query->num_rows() > 0)
	{
		if ($html == true)
		{
			$links = '<ul>';
			
			foreach ($query->result_array() as $a)
			{
				$links .= '<li><a href="' . modules_url() . 'module_' . $type . '_' . $a['module_file_name'] . '/view">' . $CI->lang->line($a['module_file_name']) . '</a></li>';
			}
			
			$links .= '</ul>';
			
			return $links;
		}
		else
		{
			return $query->result_array();	
		}
	}
	
	return false;
}

// ------------------------------------------------------------------------

function _check_language_translation($name = '')
{
	$CI =& get_instance();
	
	if ($CI->config->item('sts_content_translate_menus') == 1)
	{
		if (!$CI->config->item('cfg_disable_capitalize_title_headings'))
		{
			//return ucfirst($CI->lang->line($name));
		}
		
		//set spaces to underscros for language translation
		
		$name = str_replace(' ', '_', $name);
		
		if ($CI->lang->line(strtolower($name)))
		{							   
			return $CI->lang->line(strtolower($name));
		}
	}
	
	//if no translation just replace underscores
	$name = str_replace('_', ' ', $name);
	
	if (!$CI->config->item('cfg_disable_capitalize_title_headings'))
	{
		return ucfirst($name);
	}
	return $name;
}

// ------------------------------------------------------------------------

function _htmlentities($text = '')
{
	$CI =& get_instance();
	
	if (!$CI->config->item('jem_set_disable_htmlentities'))
	{
		$text = htmlentities($text);
	}
	
	return $text;
}

// ------------------------------------------------------------------------

function _generate_nav_menu($data = '', $class = 'nav', $id = 'nav-one', $type = 'horizontal', $aff_mark = '')
{

	//generates the menus for the site
	
	if (empty($data)) { return false; }
	
	$a = unserialize($data);
	
	$html = '<ul class="' . $class . ' navbar-nav">';
	
	$i = 1;
	$j = 1;
	
	$link_class = 'Top';

	foreach ($a as $k => $v)
	{
		$uri_class ='';
		if ($link_class == 'Top') { $uri_class = _check_menu_class($v['menu_url']); }
		
		if (is_array($v['subs']) && count($v['subs']) > 0)
		{
			$html .= '<li class="dropdown">' . _check_nav_url($v, true);
			$html .= '<ul class="dropdown-menu" role="menu">';
				
			//generate the submenus 
			foreach ($v['subs'] as $c)
			{				
				$html .= '    <li><a href="' . _generate_site_urls($c['menu_url']) . '" ' . html_entity_decode($c['menu_options']) . '><span>' . _check_language_translation($c['menu_name']) . '</span></a></li>';
				$j++;
				
			}
			
			$html .= '  </ul>';
		}
		else
		{			
			$html .= ' <li>' . _check_nav_url($v);
		}
		
		$html .= '</li><li class="divider-vertical"></li>';
		
		$i++;
	}
	
	$html .= '</ul>';
	
	return $html;

}

// ------------------------------------------------------------------------

function _check_nav_url($v = '', $caret = false)
{
	$n = _check_language_translation($v['menu_name']);
	
	if (empty($v['menu_url']))
	{
		return $n;	
	}
	
	if ($caret == true)
	{
		//$a = '<a href="' . _generate_site_urls($v['menu_url']) . '" ' . html_entity_decode($v['menu_options']). ' class="dropdown-toggle" data-toggle="dropdown">' . $n;
		
		//remove the data-toggle="dropdown" to allow for clicks on top menu items
		$a = '<a href="' . _generate_site_urls($v['menu_url']) . '" ' . html_entity_decode($v['menu_options']). ' class="dropdown-toggle">' . $n;
		$a .= ' <b class="caret"></b>';	
	}
	else
	{
		$a = '<a href="' . _generate_site_urls($v['menu_url']) . '" ' . html_entity_decode($v['menu_options']). '>' . $n;	
	}
	
	$a .= '</a>';
	
	return $a;
}

// ------------------------------------------------------------------------

function _check_menu_class($url = '')
{
	$CI =& get_instance();
	
	if (!$CI->uri->segment(1) && $url == '{home}') { return 'jroxMenuLink_home'; }
	
	if ($url == '{' . $CI->uri->segment(1) . '}' || $url == '{' . $CI->uri->segment(2) . '}')
	{
		return 'jroxMenuLink_' . $CI->uri->segment(1);
	}
	
	if (function_exists('parse_url'))
	{
		//parse the url
		$seg = parse_url($url);
		
		$path = '/';
		$path .= !empty($seg['path']) ? $seg['path'] : '';
		
		$seg2 = $CI->uri->uri_string();
		
		if ($path == $seg2)
		{
			return 'jroxMenuLink_' . $CI->uri->segment(1);	
		}
	}
	
	//try to match to other segments
	$str = $CI->uri->segment(1) . '/' . $CI->uri->segment(2);
	
	$url = str_replace('{', '', $url);
	$url = str_replace('}', '', $url);
	switch ($url)
	{
		case 'store':
			
			if ($str == 'products/details') return 'jroxMenuLink_' . $url;
			if ($str == 'products/category') return 'jroxMenuLink_' . $url;
		
		break;
		
		case 'members_home':
			
			if ($str == 'members/') return 'jroxMenuLink_members_home';
		
		break;
		
		case 'members_support':
			if ($str == 'members/support') return 'jroxMenuLink_members_support';
		break;
		
		case 'members_marketing':
			if ($str == 'members/marketing') return 'jroxMenuLink_members_marketing';
		break;
		
		case 'members_details':
			if ($str == 'members/account') return 'jroxMenuLink_members_details';
		break;
		
		case 'members_content':
			if ($str == 'members/content') return 'jroxMenuLink_members_content';
		break;

	}
	
	return false;
}

// ------------------------------------------------------------------------

function _generate_site_urls($url = '')
{
	//change the dynamic tags for URLs {link}
	
	switch ($url)
	{
		
		case '{content}':
			
			$url = str_replace("{content}", site_url() . CONTENT_ROUTE , $url);
			return $url;
		
		break;
		
		case '{faq}':
			
			$url = str_replace("{faq}", site_url() . FAQ_ROUTE , $url);
			return $url;
		
		break;
		
		case '{home}':

			$url = str_replace("{home}", site_url(), $url);
			return $url;
		
		break;
		
		
		case '{site}':
			
			$url = str_replace("{site}", base_url(), $url);
			return $url;
		
		break;
		
		case '{members_login}':
			
			$url = str_replace("{members_login}", site_url() . 'login' , $url);
			return $url;
		
		break;

		case '{terms_of_service}':
			
			$url = str_replace("{terms_of_service}", site_url() . CONTENT_ROUTE . '/view/tos' , $url);
			return $url;
		
		break;
		
		case '{privacy_policy}':
			
			$url = str_replace("{privacy_policy}", site_url() . CONTENT_ROUTE . '/view/privacy_policy' , $url);
			return $url;
		
		break;
		
		case '{members_support}':
		
			$url = str_replace("{members_support}", site_url() . MEMBERS_ROUTE . '/support/view' , $url);
			return $url;
		
		break;
		
		case '{members_payments}':
		
			$url = str_replace("{members_payments}", site_url() . MEMBERS_ROUTE . '/payments/view' , $url);
			return $url;
		
		break;
		
		case '{members_commissions}':
		
			$url = str_replace("{members_commissions}", site_url() . MEMBERS_ROUTE . '/commissions/view' , $url);
			return $url;
		
		break;
		
		case '{members_home}':
		
			$url = str_replace("{members_home}", site_url() . MEMBERS_ROUTE , $url);
			return $url;
		
		break;
		
		case '{members_marketing}':
		
			$url = str_replace("{members_marketing}", site_url() . MEMBERS_ROUTE . '/marketing/view' , $url);
			return $url;
		
		break;
		
		case '{members_details}':
		
			$url = str_replace("{members_details}", site_url() . MEMBERS_ROUTE . '/account' , $url);
			return $url;
		
		break;
		
		case '{members_content}':
		
			$url = str_replace("{members_content}", site_url() . MEMBERS_ROUTE . '/content/view' , $url);
			return $url;
		
		break;
		
		case '{members_downline}':
		
			$url = str_replace("{members_downline}",  'javascript:void(window.open(\''. site_url() . MEMBERS_ROUTE . '/downline/view\', \'popup\', \'width=700,height=500, location=no, menubar=no, status=no,toolbar=no, scrollbars=yes, resizable=yes\'))', $url);
			return $url;
		
		break;
		
		case '{members_email_downline}':
			$url = str_replace("{members_email_downline}", site_url() . MEMBERS_ROUTE . '/downline/email' , $url);
			return $url;
		break;
		
		case '{members_coupons}':
		
			$url = str_replace("{members_coupons}", site_url() . MEMBERS_ROUTE . '/coupons/view' , $url);
			return $url;
		
		break;
		
		case '{members_downloads}':
		
			$url = str_replace("{members_downloads}", site_url() . MEMBERS_ROUTE . '/downloads/view' , $url);
			return $url;
		
		break;
		
		case '{members_memberships}':
		
			$url = str_replace("{members_memberships}", site_url() . MEMBERS_ROUTE . '/memberships/view' , $url);
			return $url;
		
		break;
		
		
		default:
			return $url;
		break;
	}

}

// ------------------------------------------------------------------------

function _check_wysiwyg_disable($redirect = '')
{
	$CI = & get_instance();
	
	if ($CI->config->item('sts_admin_enable_wysiwyg_content') == '1')
	{
		if ($CI->session->userdata('admin_disable_wysiwyg') == true)
		{
			return '<a class="button" href="' . admin_url() . 'content_articles/update_wysiwyg/enable/' . $redirect . '">
  <img src="' . base_url() . 'themes/admin/' . $CI->config->item('sts_admin_layout_theme') . '/images/key.png" alt=""/>' . $CI->lang->line('enable_wysiwyg') .'</a>';
		}
		else
		{
			return '<a class="button" href="' . admin_url() . 'content_articles/update_wysiwyg/disable/' . $redirect . '">
  <img src="' . base_url() . 'themes/admin/' . $CI->config->item('sts_admin_layout_theme') . '/images/key.png" alt=""/>' . $CI->lang->line('disable_wysiwyg') .'</a>';
		}
	}
	else
	{
		return false;
	}	
}
	
// ------------------------------------------------------------------------

function _initialize_html_editor($options = array(	'type' => 'content',
										'content' => '',
										'height'	=> '400',
										'width'	=> '100%',
										'editor_type'	=> 'description',
										'textarea'	=>	'txtContent',
										'instance'	=>	'oEdit1',
										'tags'	=> true,
									))
{
	$CI = & get_instance();
	
	$enable_html = $CI->config->item('sts_admin_enable_wysiwyg_content');
	
	if ($CI->session->userdata('admin_disable_wysiwyg') == true)
	{
		$enable_html = 0;	
	}
							   
	$CI->load->helper($CI->config->item('sts_content_html_editor'));

	$data['editor_path'] =  '<script language="Javascript" src="' . base_url('js') . 'js/scripts/language/en-US/editor_lang.js"></script>
						     <script language="Javascript" type="text/javascript" src="' . base_url('js') . 'js/scripts/innovaeditor.js"></script>';
	
	if ($CI->config->item('sts_content_html_editor') == 'innovaeditor')
	{
		$html_type = $enable_html;
	}
	else
	{
		$html_type = 0;
	}
	
	$data['editor'] = HTML_Editor(	$options['instance'], 
									$html_type,  
									$options['editor_type'], 
									$options['content'], 
									$options['textarea'], 
									$options['height'], 
									$options['width'], 
									$options['tags']);
	
	return $data;
}

?>