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
| JROX PARSER FILE
| -------------------------------------------------------------------------     
| 
| This file is for parsing the view files for public and members areas
|
*/

class JROX_Parser extends CI_Parser {
	
	function __construct()
    {
        $this->CI =& get_instance();
    }
	
	// --------------------------------------------------------------------
	
	function _JROX_parse($template = '', $path = '', $data = '', $output = true, $show_errors = false)
	{
		$template = $this->CI->load->JROX_view($template, $path, $data, $output);
		
		if ($template == '')
		{
			return FALSE;
		}

		if ($output == FALSE)
		{
			$CI->output->final_output = $template;
		}
		
		return $template;
	}

	// ------------------------------------------------------------------------
	
	function _JROX_load_view($file = '', $section = '', $data = '', $show_header = true, $show_footer = true, $parse_tags = false)
	{				
		//set delimiters
		$this->set_delimiters('{', '}');	
		
		//set the view folder
		$path = APPPATH . 'views/main';
		
		$template = '';
		if ($show_header == true)
		{
			$header = 'tpl_header';
			
			//if (file_exists(APPPATH . '/views/main/' . '/custom_templates/tpl_header.php' ))
			if (file_exists($data['base_physical_path'] . '/themes/main/' . $data['default_theme'] . '/custom_templates/' . $header . '.php'))
			{
				$path = $data['base_physical_path'] . '/themes/main/' . $data['default_theme'] . '/custom_templates/';
			}
			
			//start the top menu
			$data['top_menu'] = '';

			if ($section == 'members')
			{
				//generate top menu
				$links_array = $this->CI->config->item('prg_program_layout_member_links_array');
				
				$data['top_menu'] = _generate_nav_menu($links_array, 'nav', 'nav-one', 'horizontal', $this->CI->session->userdata('m_aff_marketing'));
			}
	
			/*
			|-----------------------------------------------------
			|	generate the ad sense for the free version here
			|-----------------------------------------------------
			*/
			
			//first check if we will be using an affiliate logo
			
			$data['affiliate_custom_jrox_custom_field_1'] = '';
			$data['affiliate_custom_jrox_custom_field_2'] = '';
			$data['affiliate_custom_jrox_custom_field_3'] = '';
			$data['affiliate_custom_jrox_custom_field_4'] = '';
			$data['affiliate_custom_jrox_custom_field_5'] = '';
			$data['affiliate_custom_jrox_custom_field_6'] = '';
			$data['affiliate_custom_jrox_custom_field_7'] = '';
			$data['affiliate_custom_jrox_custom_field_8'] = '';
			$data['affiliate_custom_jrox_custom_field_9'] = '';
			$data['affiliate_custom_jrox_custom_field_10'] = '';
			$data['affiliate_custom_fname'] = '';
			$data['affiliate_custom_lname'] = '';
			$data['affiliate_custom_billing_address_1'] = '';
			$data['affiliate_custom_billing_address_2'] = '';
			$data['affiliate_custom_billing_city'] = '';
			$data['affiliate_custom_billing_state'] = '';
			$data['affiliate_custom_billing_country'] = '';
			$data['affiliate_custom_billing_postal_code'] = '';
			$data['affiliate_custom_company'] = '';
			$data['affiliate_custom_primary_email'] = '';
			$data['affiliate_custom_home_phone'] = '';
			$data['affiliate_custom_work_phone'] = '';
			$data['affiliate_custom_mobile_phone'] = '';
			$data['affiliate_custom_website'] = '';
			$data['affiliate_custom_photo_file_name'] = '';			
			$data['affiliate_custom_photo_file_name_thumb'] = '';			

			
			//generate header			
			$template .= $this->CI->parser->_JROX_parse($header ,$path, $data, true, true);
		}
		
		/*
		|--------------------
		| generate content
		|--------------------
		*/
		
		//set the view folder
		$path = $section == 'members' ? APPPATH . 'views/members' : APPPATH . 'views/main';
		
		if (file_exists($data['base_physical_path'] . '/themes/main/' . $data['default_theme'] . '/custom_templates/' . $file . '.php'))
		{
			$path = $data['base_physical_path'] . '/themes/main/' . $data['default_theme'] . '/custom_templates/';
		}

		if ($section == 'members' || $parse_tags == true)
		{
			$template .= $this->CI->parser->_JROX_parse($file, $path, $data, true, true);
		}
		else
		{
			$template .= $this->CI->parser->_JROX_parse($file, $path, $data, true);
		}
		
		if ($show_footer == true)
		{	
			/*
			|--------------------
			| generate footer
			|--------------------
			*/
			
			$footer = 'tpl_footer';
			
			//set the view folder
			$path = APPPATH . 'views/main';
			
			if (file_exists($data['base_physical_path'] . '/themes/main/' . $data['default_theme'] . '/custom_templates/' . $footer . '.php'))
			{
				$path = $data['base_physical_path'] . '/themes/main/' . $data['default_theme'] . '/custom_templates/';
			}
			
			$template .= $this->CI->parser->_JROX_parse($footer, $path, $data, true);
			
			//add the ending body tags for licensing
			
			$template .= $this->_JROX_generate_footer($section);
		}
		
		//check if header cache will be used
		if ($this->CI->config->item('pragma_header_cache_control') == true)
		{
			_generate_header_cache();
		}
			
		$data['template'] = $template;
		
		$this->CI->load->view('main/tpl_main', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function _JROX_generate_footer($section = '')
	{
		$code = '';
		
		//add copyright if this is a free version
		if (JAM_ENABLE_SYSTEM_LSETTINGS == 'jrox')
		{
			$year = date('Y');
			
			$code = '<div style="margin: 1em; auto; padding-bottom: 1em;" align="center">';
			
			$code .= '<link rel="stylesheet" href="themes/main/copyright.css" type="text/css" media="screen, projection" />';
			
			//check for reseller settings
			//6f6ae56d989d5f090050b27007536109
			if (defined('JAM_ENABLE_RESELLER_LINKS'))
			{
				if ($this->CI->config->item('customizer_reseller_company_name'))
				{
					$code .= '<a href="' . $this->CI->config->item('customizer_reseller_direct_url') . '" style="' . $this->CI->config->item('customizer_reseller_footer_css') . '" target="_blank">&copy; ' . $year . ' ' . $this->CI->lang->line('licensed_to') . ' ' . $this->CI->config->item('customizer_reseller_company_name') . '</a>';
				}
			}
			else
			{
				$code .= "\n\n\n\n" . '<br /><div id="jrox_copyright_footer" style="font-size: 9px; font-family: verdana, tahoma, arial; font-color: #777; text-decoration: none;"><a href="http://jem.jrox.com">Shopping Cart</a> | <a href="http://www.jrox.com">eCommerce Web Hosting</a> | <a href="http://jam.jrox.com">Affiliate Marketing Software</a> | <a href="http://www.jroxdesign.com">Web Design</a> | <a href="http://www.jroxdesign.com">Website Templates</a></div><div style="margin-top:5px; font-size: 9px; font-family: verdana, tahoma, arial; color: #777; text-decoration: none; visibility:visible;">&copy; ' .$year . ' <a href="http://jam.jrox.com" style="font-size: 9px; font-family: verdana, tahoma, arial; color: #777; text-decoration: none; visibility:visible;" target="_blank">Affiliate Software</a> By JROX.COM</div>' . "\n\n\n\n";
			}
			
			$code .= '</div>';
		}
			
		$code .= '</body></html>';
	
		return $code;
	}
	
}
?>