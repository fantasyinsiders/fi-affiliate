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
| FILENAME - downline.php
| -------------------------------------------------------------------------     
| 
| This controller file shows the downline of the user
|
*/

class Downline extends Member_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('downline_model');
		
		$this->load->helper('country');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(site_url('members/downline/view'));
	}
	
	// ------------------------------------------------------------------------
	
	function view()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		//set data array
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		if ($this->session->userdata('m_allow_downline_view') == 0)
		{
			show_error($this->lang->line('invalid_permissions'));
		}
		elseif ($this->config->item('sts_affiliate_allow_downline_view') == 0)
		{
			show_error($this->lang->line('invalid_permissions'));
		}
		
		//check if caching is enabled
		if ($data['sts_site_enable_downline_cache'] == '1')
		{
			$this->output->cache($data['sts_site_enable_downline_cache_minutes']);
		}
		
		$data['page_title'] = $this->lang->line('view_downline');

		
		$this->load->helper('country');
		
		$id = $this->session->userdata('userid');
		
		if ($this->config->item('sts_affiliate_allow_expandable_downlines') == 1)
		{
			if ($this->uri->segment(4))
			{
				$id = (int)$this->uri->segment(4);
			}	
		}
			
		if ($id > 0) //for the member only
		{
			$member = $this->downline_model->_get_sponsor_details($id);
			$data['show_upper_sponsor'] = !empty($member[0]['sponsor_id']) && $this->config->item('sts_affiliate_allow_expandable_downlines') == 1 ? '1' : '0';
			$data['member_name'] = $member[0]['fname'] . ' ' . $member[0]['lname'];
			$data['sponsor_id'] = !empty($member[0]['sponsor_id']) ? $member[0]['sponsor_id'] : '0';
			
			$data['member_photo'] = !empty($member[0]['photo_file_name']) ? base_url() . 'images/' . $this->config->item('images_members_dir') . '/' . $members[0]['photo_file_name'] : base_url() . 'images/misc/downline_main_1.jpg';
		}
		else
		{
			$data['member_name'] = $this->config->item('prg_program_name');
			$data['member_photo'] = base_url() . 'images/misc/downline_main_1.jpg';
		}
		
		if ($this->config->item('sts_affiliate_use_google_visualization_api_members'))
		{
			$mdata = $this->downline_model->_generate_downline($id, false, 'email');
			
			$data['rows'] = '[{v:\'' . $member[0]['member_id'] . '\', f:\'' . '<div><img src="' . $data['member_photo'] . '" /><br />' . $data['member_name'] . '<div>\'}, \'\', \'' . $member[0]['username']  . '\'],';
			
			foreach ($mdata['results'] as $v)
			{
				$link = $this->downline_model->_check_downline_details($v, false, 'google');
				
				$data['rows'] .= '[{v:\'' . $v['member_id'] . '\', f:\'' . '<div>' . $link . '<div>\'}, \'' . $v['sponsor_id'] . '\', \'' . $v['username'] . '\'],';
			}
			
			$data['google_api_code'] = "
			<script language='JavaScript' type='text/javascript' src='js/jquery.popupwindow.js'></script>
<script>
var profiles =
	{
		windowCenter:
		{
			height:750,
			width:700,
			center:1,
			scrollbars:1
		}
	};

	$(\".popupwindow\").popupwindow(profiles);
</script>
			<script type='text/javascript' src='" . _check_ssl('www.google.com/jsapi') . "'></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['orgchart']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
		data.addColumn('string', 'Affiliate');
        data.addColumn('string', 'ToolTip');
        data.addRows([
          " . $data['rows'] . "
        ]);
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        chart.draw(data, {allowHtml:true});
      }
    </script>";
			
			$template = 'tpl_members_downline2';
		}
		else
		{
			//$this->downline_model->_show_downline_list($id);
		
			$mdata = $this->downline_model->_generate_downline($id);
			
			$template = 'tpl_members_downline';
		}
		
		$data['total_users' ] = $mdata['total_users'];
		$data['downline_table'] = $mdata['results'];
		$data['levels'] = $mdata['levels'];
		
		//load the template
		echo $this->parser->_JROX_parse($template, APPPATH . 'views/members', $data, true, true);
		
	}
	
	// ------------------------------------------------------------------------
	
	function member()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		//set data array
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//check if caching is enabled
		if ($data['sts_site_enable_downline_cache'] == 1)
		{
			$this->output->cache($data['sts_site_enable_downline_cache_minutes']);
		}
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
				
		//get the member details
		$sdata = $this->members_model->_get_member_basic((int)$this->uri->segment(4));
		
		unset ($sdata['password']);
		
		foreach ($sdata as $k => $v)
		{
			$data[$k] = $v;
		}
		
		$data['billing_country_name'] = _get_country_name($data['billing_country'],'country_name');
		
		//check if we are allowing photo uploads
		if ($this->config->item('sts_affiliate_allow_upload_photos') == 1)
		{
			$data['show_upload'] = 1;
			$mem_photos = $this->members_model->_get_member_photos((int)$this->uri->segment(4));
			
			if (!empty($mem_photos))
			{
				if ($this->config->item('sts_affiliate_image_auto_resize') == 1)
				{
					$data['member_photo'] = 'images/members/' . $mem_photos[0]['raw_name'].'_jrox'.$mem_photos[0]['file_ext'];
				}
				else
				{
					$data['member_photo'] = 'images/members/' . $mem_photos[0]['photo_file_name'];
				}
				
				$data['show_delete_photo'] = '<button name="member_button" id="member_button" type="button" onclick="location.href=\'' . $data['members_url'] . '/account/delete_photo/' . $mem_photos[0]['photo_file_name'] . '/' . $this->session->userdata('userid') . '\';">' . $this->lang->line('delete_photo') . '</button>';
			}
			elseif (!empty($data['facebook_id']))
			{
             $data['member_photo'] = _check_ssl() . 'graph.facebook.com/' . $data['facebook_id'] . '/picture/?type=large'; 
			}
			else
			{
				$data['member_photo'] = 'images/misc/sponsor.png';
			}
		}
		else
		{
			$data['show_upload'] = 0;
		}
		
		//load the template
		echo $this->parser->_JROX_parse('tpl_members_downline_member', APPPATH . 'views/members', $data, true);
		
	}
	
	// ------------------------------------------------------------------------
	
	function email()
	{
		
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		//set data array
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		if ($this->session->userdata('m_allow_downline_email') == 0)
		{
			show_error($this->lang->line('invalid_permissions'));
		}
		elseif ($this->config->item('sts_affiliate_allow_downline_email') == 0)
		{
			show_error($this->lang->line('invalid_permissions'));
		}
		
		$data['page_title'] = $this->lang->line('email_downline');
	
		$id = $this->session->userdata('userid');
		
		if ($this->_check_send_downline_email() == false)
		{
			
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string;				
			}
		}
		else
		{
			//queue the emails
			$mdata = $this->downline_model->_generate_downline($id, false, 'email');
		
			if (!empty($mdata['results']))
			{
				$this->load->model('emailing_model');
					
				foreach ($mdata['results'] as $k => $v)
				{
					$sdata = array('primary_email' => $v['primary_email'],
								   'fname' => $v['fname'],
								   'lname' => $v['lname'],
								   	
								   'downline_sponsor_name' 					=> $this->session->userdata('m_fname') . ' ' . $this->session->userdata('m_lname'),
								   'downline_sponsor_email' 				=> $this->session->userdata('m_email'),
								   'downline_sponsor_affiliate_link' 		=> _get_aff_link($this->session->userdata('m_username')),			
						
								   'downline_member_name' 					=> $v['fname'] . ' ' . $v['lname'],
								   'downline_member_username' 				=> $v['username'],
								   'downline_member_email' 					=> $v['primary_email'],
								   'downline_member_id' 					=> $v['member_id'],
								   'downline_member_affiliate_link' 		=> _get_aff_link($v['username']),	
								  );		
					
					if ($this->config->item('sts_affiliate_enable_wysiwyg_content') == 1)
					{
						$sdata['downline_message_html'] = $this->input->post('body');
						$sdata['downline_message_text'] = strip_tags(str_replace('<br />', "\n", $this->input->post('body')));
					}
					else
					{
						$sdata['downline_message_html'] = nl2br($this->input->post('body'));
						$sdata['downline_message_text'] = $this->input->post('body');
					}
								
					$this->emailing_model->_send_template_email('member', $sdata, 'member_affiliate_send_downline_email', true);
				}
			}
			
			$this->session->set_flashdata('success', $this->lang->line('email_queued_successfully'));
			
			$this->security_model->_check_flood_control('add', $this->session->userdata('userid'));
			
			redirect(site_url('members/downline/email'));
			exit();
		}
		
		if ($this->config->item('sts_affiliate_enable_wysiwyg_email') == 1)
		{
			//initialize HTML editor
			$options = array(	'instance'	=> 'oEdit1',
								'type' => 'content',
								'content' => $this->validation->body,
								'height'	=> '400',
								'width'	=> '100%',
								'editor_type'	=> 'basic',
								'textarea'	=>	'body',
								'tags'	=> true,
							);
			$check_editor = _initialize_html_editor($options);
		
			$data['editor'] = $check_editor['editor'];
			$data['editor_path'] = $check_editor['editor_path'];
		}
		else
		{
			$fdata = array(
						  'name'        => 'body',
						  'id'          => 'jroxMembersDownlineEmailBody',
						  'value'       => $this->validation->body,
						  'rows'   		=> '20',
						  'cols'        => '140',
						  'class'       => 'jroxMembersDownlineEmailBody required',
						);

			$data['editor'] = form_textarea($fdata);
			$data['editor_path'] = '';
		
		}
				
		//load the template
		$this->parser->_JROX_load_view('tpl_members_send_downline_email', 'members', $data);
	}
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_send_downline_email()
	{
		
		$rules['body'] = 'trim|required';
		
		//check captcha
		if ($this->config->item('sts_sec_enable_captcha_order_form') == 1)
		{
			$rules['jroxVerifyField'] = 'trim|required|callback__check_captcha';
		}
		$fields['body'] = $this->lang->line('email_body');
		
		$this->validation->set_rules($rules);
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		//check flood control
		$this->security_model->_check_flood_control('check', $this->session->userdata('userid'));
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_captcha()
	{
		if ($this->session->userdata('user_captcha_set') != $this->validation->jroxVerifyField)
		{
			$this->validation->set_message('_check_captcha', $this->lang->line('invalid_verification_code'));
			return false;
		}
	}
}

?>