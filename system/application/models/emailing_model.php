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
| FILENAME - emailing_model.php
| -------------------------------------------------------------------------     
| 
| This model handles emailing functions
|
*/

class Emailing_Model extends CI_Model {
		
	//sends the email using a template
	function _send_template_email($type = '', $row = '', $template_name = '', $queue = false, $pid = 1)
	{
		//get email template first
		
		if ($this->config->item('sts_site_showcase_multiple_programs') == 1) { $pid = 1; }
		
		$this->db->where('email_template_name', $template_name);
		$this->db->where('program_id', $pid);
		
		//run query
		$query = $this->db->get('email_templates');
	
		if ($query->num_rows() == 1) //check if the template exists
		{
			$template = $query->row_array();
			
			//add supporting variables
			$row['admin_login_url'] = admin_url();
			$row['login_url'] = base_url();
			$row['site_url'] = base_url();
			$row['site_name'] = $this->config->item('sts_site_name');
			$row['charset'] = $this->config->item('sts_email_charset');
			$row['sts_site_description'] = $this->config->item('sts_site_description');
			
			//set date
			$fdate = explode(':', $this->config->item('sts_admin_date_format'));
			$row['current_date'] = date($fdate[1], time());
			$row['current_time'] = date($this->config->item('sts_admin_time_format'), time());
			
			//substitute all template variables for real data
			$template_ready = $this->_prepare_template($row, $template);
			
			//merge arrays
			$data = array_merge($row, $template_ready);
			
			if ($queue == true && $this->config->item('sts_email_send_queue') == 0) 
			{			
				$msg = $this->_queue_email($type, $data) == true ? true : false;
				
				return $msg;
			}
			else 
			{
				$msg = $this->_send_email($type, $data) == true ? true : false;
				
				return $msg;
			}
			
		}
		else 
		{
			return false;
		}
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _auto_prune_archive($day = '')
	{
		//get all commissions
		
		$time = _generate_timestamp() - ($day * 60 * 60 * 24);
		 
		$this->db->where('send_date <=', $time);
		$query = $this->db->delete('email_archive');
		
		if ($query)
		{
			return 'email archive pruned';
		}
		
		return false;
		
	}
	
	// ------------------------------------------------------------------------
	
	function _get_email_queue($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_emq_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_emq_column');

		$this->db->order_by($sort_column, $sort_order); 	
		$query = $this->db->get('email_queue', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_email_archive($data = '')
	{

		foreach ($data as $id)
		{
			
			$this->db->where('id', $id);
			
			if (!$this->_delete_archive((int)($id)))
			{
				show_error($this->lang->line('could_not_delete_email'));
				
				//log error
				log_message('error', 'Could not delete email in email archive table');
				return false;
			}
			
			//log success
			log_message('info', 'email deleted in archive for id ' . $id);
		}
		
		return true;		
	}
	
	
	// ------------------------------------------------------------------------
	
	function _delete_archive($id = '')
	{
		$this->db->where('id', $id);
		
		if ($this->db->delete('email_archive'))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_email_archive($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_arc_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_arc_column');

		$this->db->order_by($sort_column, $sort_order); 	
		$query = $this->db->get('email_archive', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _queue_mass_email($type = '', $data = '', $users = '')
	{
		
		foreach ($users as $v)
		{
			//echo '<pre>'; print_r($v); exit();
			$sdata = $this->_format_email($data, 'mass', $v);
			
			if (! $this->_queue_email('admin', $sdata))
			{
				return false;
			}
		}
		
		if ($this->config->item('sts_email_send_queue') == 1)
		{
			$this->_flush_queue($type);
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------
	
	function _get_list_users($list, $limit = 100, $offset = '0', $sort_column = 'id', $sort_order = 'DESC')
	{
		//explode the lists
		
		$list_array = explode(',', $list);
		
		$offset = empty($offset) ? '0' : $offset;
		
		$users['users'] = array();
		$users['total'] = 0;
		$emails = array();
		
		//get all the users
		foreach ($list_array as $v)
		{
			$sql = 'SELECT *
					FROM ' . $this->db->dbprefix('email_mailing_list_members') . '
					LEFT JOIN ' . $this->db->dbprefix('members') . ' 
					ON ' . $this->db->dbprefix('email_mailing_list_members') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id
					WHERE mailing_list_id = \'' . $v . '\'
					ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
					
			$query = $this->db->query($sql);
		
			if ($query->num_rows() > 0) 
			{
				foreach ($query->result_array() as $row)
				{
					
					if (!in_array($row['primary_email'], $emails))
					{
						array_push($users['users'], $row);
						array_push($emails, $row['primary_email']);
					}
				}
			
				$this->db->where('mailing_list_id', $v);
				$u = $this->db->count_all_results('email_mailing_list_members');
			
				$users['total'] += $u;
			}
		}
		
		$users['offset'] = $offset + $this->config->item('sts_email_limit_mass_mailing');
		
		return $users;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_email_queue($data = '')
	{

		foreach ($data as $id)
		{
			
			$this->db->where('eqid', $id);
			
			if (!$this->_delete_queue((int)($id)))
			{
				show_error($this->lang->line('could_not_delete_email'));
				
				//log error
				log_message('error', 'Could not delete email in email queue table');
				return false;
			}
			
			//log success
			log_message('info', 'email deleted in queue for id ' . $id);
		}
		
		return true;		
	}
	
	// ------------------------------------------------------------------------
	
	function _queue_email($type = 'admin', $data = '')
	{
		//check if there is no send date
		if (empty($data['send_date'])) { $data['send_date'] = _generate_timestamp(); }
		
		//check if there is no group
		if (empty($data['group'])) { $data['group'] = ''; }
		
		//check if there is no email type
		if (empty($data['email_type'])) { $data['email_type'] = 'html'; }
		
		if (valid_email($data['primary_email']))
		{
			
			$insert = array(  'type' => $type,
							  'email_type' => $data['email_type'],
							  'send_date' => $data['send_date'],
							  'sender_name' => $data['email_template_from_name'],
							  'sender_email' => $data['email_template_from_email'],
							  'recipient_name' => $data['fname'] . ' ' . $data['lname'],
							  'recipient_email' => $data['primary_email'],
							  'recipient_cc' => $data['email_template_cc'],
							  'recipient_bcc' => $data['email_template_bcc'],
							  'subject' => $data['email_template_subject'],
							  'html_body' => $data['email_template_body_html'],
							  'text_body' => $data['email_template_body_text'],
							  'group' => $data['group']
							 );
			
			$msg = $this->db->insert('email_queue', $insert) == true ? true : false;
			
			if ($this->config->item('sts_email_send_queue') == 1)
			{
				$this->_flush_queue('date');	
			}
			
			return $msg; 
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_queue($id = '')
	{
		$this->db->where('eqid', $id);
		
		if ($this->db->delete('email_queue'))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	//prepares the email template and replaces strings with data
	function _prepare_template($data = '', $template = '')
	{
		//replace template strings
		foreach ($data as $key => $value)
		{
			$template['email_template_subject'] = str_replace('{' . $key . '}', $value, $template['email_template_subject']);
			$template['email_template_body_text'] = str_replace('{' . $key . '}', $value, $template['email_template_body_text']);
			$template['email_template_body_html']= str_replace('{' . $key . '}', $value, $template['email_template_body_html']);
		}
		
		return $template;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_mailing_lists($data = '')
	{
		$array = array();
		
		foreach ($data as $v)
		{
			$q = $this->db_validation_model->_get_details('email_mailing_lists', 'mailing_list_name', 'mailing_list_id', $v);
			array_push($array, $q[0]['mailing_list_name']);
		}
		
		$opt = implode(',', $array);
		
		return $opt;
	}
	
	// ------------------------------------------------------------------------
	
	function _format_email($data = '', $type = '', $row = '') 
	{
		//replace all the variables for the email
		
		//add supporting variables
		$row['admin_login_url'] = admin_url();
		$row['login_url'] = base_url();
		$row['site_name'] = $this->config->item('sts_site_name');
		$row['charset'] = $this->config->item('sts_email_charset');
		$row['sts_site_description'] = $this->config->item('sts_site_description');
		$row['signup_link'] = base_url() . 'signup';
		$row['affiliate_link'] = _get_aff_link($row['username']);
		
		if ($type == 'mass')
		{
			$row['unsubscribe_link'] = $this->config->slash_item('base_url') . 'unsubscribe/id/' . $row['member_id'] . '/' . $row['mailing_list_id'];
		}
		
		foreach ($row as $key => $value)
		{
			$data['subject'] = str_replace('{' . $key . '}', $value, $data['subject']);
			
			if ($type == 'mass')
			{
				$data['text_body'] = str_replace('{' . $key . '}', $value, $data['text_body']);
			}
			
			$data['html_body'] = str_replace('{' . $key . '}', $value, $data['html_body']);
		}
		
		//format date
		$pub = explode('/', $data['send_date']);
		$sdata['send_date'] = _save_date($data['send_date']);
		
		//change the data array fields
		
		$sdata['email_template_from_name'] = empty($data['sender_name']) ? $this->config->item('sts_site_name') : $data['sender_name'];
		$sdata['email_template_from_email'] = empty($data['sender_email']) ? $this->config->item('sts_site_email') : $data['sender_email'];
		$sdata['email_template_cc'] = !empty($data['cc']) ? $data['cc'] : '';
		$sdata['email_template_bcc'] = !empty($data['bcc']) ? $data['bcc'] : '';
		$sdata['email_template_subject'] = $data['subject'];
		$sdata['email_template_html'] = $data['html_body'];
		$sdata['fname'] = $row['fname'];
		$sdata['lname'] = $row['lname'];
		
		$sdata['group'] = '';
		
		
		if ($type == 'mass')
		{
			$sdata['email_type'] = $data['email_type'];
			$sdata['email_template_html'] = $data['email_type'];
			
			$sdata['primary_email'] = $row['primary_email'];
			
			if ($this->config->item('member_list_append_unsubscribe') == true)
			{
				$data['html_body'] .= $this->lang->line('unsubscribe_html_1') . '<a href="' . $this->config->slash_item('base_url') . 'unsubscribe/id/' . $row['member_id'] . '/' . $row['mailing_list_id'] . '">' . $this->lang->line('unsubscribe_here_now') . '</a>' . $this->lang->line('unsubscribe_html_2');
				
				$data['text_body'] .= $this->lang->line('unsubscribe_text_1') . $this->config->slash_item('base_url') . 'unsubscribe/id/' . $row['member_id'] . '/' . $row['mailing_list_id'];
			}
			
			$sdata['email_template_body_text'] = $data['text_body'];
		}
		else
		{
			$sdata['primary_email'] = $data['recipient_email'];
			$sdata['email_template_html'] = 'html';
			$sdata['email_template_body_text'] = '';
		}	
		
		$sdata['email_template_body_html'] = $data['html_body'];
		
		return $sdata;
	}
	
	// ------------------------------------------------------------------------

	function _unsubscribe($mid = '', $list = '')
	{
		$this->db->where('member_id', $mid);
		$this->db->where('mailing_list_id', $list);
		
		$query = $this->db->delete('email_mailing_list_members');
		
		if ($query)
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _queue_follow_ups()
	{
		//put any follow ups in the email queue for sending
		$this->load->helper('country');
		
		//first lets get all follow ups for reference later
		$q = $this->db->get('email_follow_ups');
		
		$total = 0;
		
		if ($q->num_rows() > 0)
		{
			$follow_ups = $q->result_array();
			
			$time = _generate_timestamp() + 30;
			
			$sql = 'SELECT *
						FROM ' . $this->db->dbprefix('email_mailing_list_members') . '
						LEFT JOIN ' . $this->db->dbprefix('members') . ' 
						ON ' . $this->db->dbprefix('email_mailing_list_members') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id 
						LEFT JOIN ' . $this->db->dbprefix('email_follow_ups') . ' 
						ON ' . $this->db->dbprefix('email_mailing_list_members') . '.mailing_list_id = ' . $this->db->dbprefix('email_follow_ups') . '.mailing_list_id' . ' 
						AND ' . $this->db->dbprefix('email_mailing_list_members') . '.sequence_id = ' . $this->db->dbprefix('email_follow_ups') . '.sequence 
						WHERE send_date > \'0\' AND send_date <= \'' . $time . '\'
						';
						
			$query = $this->db->query($sql);

			if ($query->num_rows() > 0)
			{
				$rows = array();
				
				foreach ($query->result_array() as $row)
				{
					//remove the password
					unset($row['password']);
					
					if ($row['sequence_id'] == '0') { continue; }
					if (empty($row['email_subject'])) { continue; } 
					if (empty($row['send_date'])) {continue; }
					
					$row['admin_login_url'] = admin_url();
					$row['login_url'] = base_url();
					$row['site_name'] = $this->config->item('sts_site_name');
					$row['charset'] = $this->config->item('sts_email_charset');
					$row['sts_site_description'] = $this->config->item('sts_site_description');
					$row['signup_link'] = base_url() . 'signup';
					$row['affiliate_link'] = _get_aff_link($row['username']);
					$row['signup_ip'] = $row['last_login_ip'];
					
					$row['unsubscribe_link'] = $this->config->slash_item('base_url') . 'unsubscribe/id/' . $row['member_id'] . '/' . $row['mailing_list_id'];
					
					//set date
					$fdate = explode(':', $this->config->item('sts_admin_date_format'));
					$row['current_date'] = date($fdate[1], time());
					$row['current_time'] = date($this->config->item('sts_admin_time_format'), time());
					
					//parse the template
					foreach ($row  as $key => $value)
					{
						$row['email_subject'] = str_replace('{' . $key . '}', $value, $row['email_subject']);
						$row['html_message'] = str_replace('{' . $key . '}', $value, $row['html_message']);
						$row['text_message'] = str_replace('{' . $key . '}', $value, $row['text_message']);
					}
					
					
					if ($this->config->item('member_list_append_unsubscribe') == true)
					{
						$row['html_message'] .= $this->lang->line('unsubscribe_html_1') . '<a href="' . $this->config->slash_item('base_url') . 'unsubscribe/id/' . $row['member_id'] . '/' . $row['mailing_list_id'] . '">' . $this->lang->line('unsubscribe_here_now') . '</a>' . $this->lang->line('unsubscribe_html_2');
						
						$row['text_message'] .= $this->lang->line('unsubscribe_text_1') . $this->config->slash_item('base_url') . 'unsubscribe/id/' . $row['member_id'] . '/' . $row['mailing_list_id'];
					}
			
					$insert = array(  'type' => 'member',
									  'email_type' => $row['email_type'],
									  'email_template_from_name' => $row['from_name'],
									  'email_template_from_email' => $row['from_email'],
									  'fname' => $row['fname'],
									  'lname' => $row['lname'],
									  'primary_email' => $row['primary_email'],
									  'email_template_cc' => '',
									  'email_template_bcc' => '',
									  'email_template_subject' => $row['email_subject'],
									  'email_template_body_html' => $row['html_message'],
									  'email_template_body_text' => $row['text_message']
							 );
					
					
					if ($this->_queue_email('member', $insert))
					{
						//update the mailing list sequence id and future send date
						$next_sequence = $row['sequence'] + 1;
						
						$new_follow_up = array('sequence_id' => '0',
											   'send_date' => '0'
											   );
						
						//loop through each follow to see if there is another one to be scheduled
						
						foreach ($follow_ups as $follow_up)
						{
							if ($follow_up['mailing_list_id'] == $row['mailing_list_id'] && $follow_up['sequence'] == $next_sequence)
							{
								//lets update the list with the new mailing list schedule
								$new_follow_up = array('sequence_id' => $next_sequence,
													   'send_date' => _generate_timestamp() + ($follow_up['days_apart'] * 60 * 60 * 24)
													  );
							}
						}
						
						$this->db->where('id', $row['id']);
						$this->db->update('email_mailing_list_members', $new_follow_up);
						
						$total++;
					}
				}
			}
		}
		
		return $total++ . ' follow ups queued';
	}	
	
	// ------------------------------------------------------------------------
	
	function _flush_queue($type = '', $processing = false)
	{
		//send all emails out
		
		if ($type == 'date')
		{
			
			$this->db->where('send_date <= ', _generate_timestamp());
		}
		
		if ($processing == false)
		{
			$this->db->where('processing', '0');
		}
		
		$query = $this->db->get('email_queue', $this->config->item('sts_email_limit_mass_mailing'), 0);
		
		$total = 0;
		
		$srow = $query->result();
		
		if (!empty($srow))
		{
			foreach ($srow as $row)
			{
				//now update for processing
				$this->db->where('eqid', $row->eqid);
				$this->db->update('email_queue', array('processing' => '1'));
			}
			
			foreach ($srow as $row) //send to email server
			{
				//format emails
				$sdata['email_template_html'] = $row->email_type;
				$sdata['email_template_from_email'] = $row->sender_email;
				$sdata['email_template_from_name'] = $row->sender_name;
				$sdata['email_template_subject'] = $row->subject;
				$sdata['email_template_body_html'] = $row->html_body;
				$sdata['email_template_body_text'] = $row->text_body;
				$sdata['primary_email'] = $row->recipient_email;
				$sdata['fname'] = $row->recipient_name;
				$sdata['email_template_cc'] = $row->recipient_cc;
				$sdata['email_template_bcc'] = $row->recipient_bcc;
				
				if ($this->_send_email($type, $sdata))
				{
					//now delete from queue
					$this->db->where('eqid', $row->eqid);
					$this->db->delete('email_queue');
					
					$total++;
				}
				else
				{
					//now update for processing
					$this->db->where('eqid', $row->eqid);
					$this->db->update('email_queue', array('processing' => '0'));
														   
					@error_log("Email could not be sent from queue: Email ID:" . $row->eqid . "Email Address: " . $row->recipient_email, 1, $this->config->item('sts_sec_admin_failed_login_email'));									   
					//show_error($this->lang->line('could_not_send_email'));
				}
				
				if ($this->config->item('member_mass_email_throttle') != '0')
				{
					sleep($this->config->item('member_mass_email_throttle'));
				}
			}
		}
		return $total . ' ' . $this->lang->line('emails_sent_successfully');
	}
	
	// ------------------------------------------------------------------------
	
	//send the email immediately
	function _send_email($type = '', $data = '')
	{
		
		//initialize PHPMailer class
		require_once(APPPATH.'/libraries/phpmailer/class.phpmailer.php');
		
		if (empty($data['primary_email'])) { return false; }
		
		$this->phpmailer = new PHPMailer();
		
		//for debugging
		if ($this->config->item('sts_email_enable_debugging') == 1)
		{
			$this->phpmailer->SMTPDebug = 2;
		}
		
		//check if we will be sending via SMTP
		if ($this->config->item('sts_email_mailer_type') == 'php')
		{
			$this->phpmailer->IsMail();
		}
		elseif ($this->config->item('sts_email_mailer_type') == 'sendmail')
		{
			$this->phpmailer->IsSendmail();
		}
		elseif ($this->config->item('sts_email_mailer_type') == 'qmail')
		{
			$this->phpmailer->IsQmail();
		}
		elseif ($this->config->item('sts_email_mailer_type') == 'smtp')
		{			
			$this->phpmailer->IsSMTP();
			$this->phpmailer->Host = $this->config->item('sts_email_smtp_host');
			$this->phpmailer->Port = $this->config->item('sts_email_smtp_port');

			if ($this->config->item('sts_email_enable_ssl') != 'none')
			{
				$this->phpmailer->SMTPSecure = $this->config->item('sts_email_enable_ssl');
			}
		}
		
		//check if SMTP authorization is needed
		if ($this->config->item('sts_email_use_smtp_authentication') == 1)
		{
			$this->phpmailer->SMTPAuth = true;
			$this->phpmailer->Username = $this->config->item('sts_email_smtp_username');
			$this->phpmailer->Password = $this->config->item('sts_email_smtp_password');
		}
		
		//set the charset
		$this->phpmailer->CharSet = $this->config->item('sts_email_charset');
		
		$this->phpmailer->From = $data['email_template_from_email'];
		$this->phpmailer->FromName = $data['email_template_from_name'];
		$this->phpmailer->Subject = $data['email_template_subject'];
		
		//for html emails add the html header
		$html_body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=' . $this->config->item('sts_email_charset') . '" />
	<title>Email Template</title>
</head>
<body>';
		
		$html_body .= empty($data['email_template_body_html']) ? '' : $data['email_template_body_html'];
		
		
		$text_body = empty($data['email_template_body_text']) ? '' : $data['email_template_body_text'];
		
		//check license
		if (!defined('JAM_ENABLE_SYSTEM_LSETTINGS')) { die('invalid installation'); }
		
		//check for free version
		if (JAM_ENABLE_SYSTEM_LSETTINGS == 'jrox')
		{
			//check for reseller links
			
			if (defined('JAM_ENABLE_RESELLER_LINKS'))
			{
				$html_body .= '<p align="center">' . $this->config->item('customizer_html_email_footer') . '</p>';
				$text_body .= $this->config->item('customizer_text_email_footer');
			}
			else
			{
			
				if ($this->config->item('sts_site_affiliate_id'))
				{
					$affiliate_id = 'http://www.jrox.com/affiliates/' . $this->config->item('sts_site_affiliate_id');
				}
				else
				{
					$affiliate_id = 'http://www.jrox.com/';
				}
			
$text_body .= "\n\n==========================================
Powered By: JROX.COM Affiliate Manager
Start Your Own Affiliate Marketing Program Free!
$affiliate_id
==========================================\n\n\n";

$html_body .= '<br /><br /><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" style="border-top: 1px solid #ccc; margin-top: 1em;">
							<tr>
							<td width="50%" align="left" valign="top" style="font-size:12px; color: #333; font-family:Tahoma, Arial, Helvetica, sans-serif;"> <a href="' . $affiliate_id . '" style="color: #003366">Start Your Own Affiliate Marketing Program for <strong> Free</strong>!</a><br />
							                              </td>
							<td width="50%" align="right" valign="top" style="font-size:12px; color: #333; font-family:Tahoma, Arial, Helvetica, sans-serif;">
                            <strong><a href="' . $affiliate_id . '" style="color: #333; text-decoration: none">Powered By JROX.COM Affiliate Manager</a></strong>
                            </td>
							</tr>
							</table>
							';
							
			}
		}
		
		//add ending html body tags
		$html_body .= '</body></html>';
		
		//check if html email is to be sent
		if ($data['email_template_html'] == 'html')
		{
			$this->phpmailer->Body = stripslashes($html_body);
			$this->phpmailer->isHTML(true);
			$this->phpmailer->AltBody = stripslashes($text_body);
		}
		else 
		{
			$this->phpmailer->Body = stripslashes($text_body);
		}
		
		//add recipients
		$this->phpmailer->AddAddress($data['primary_email']);
		
		
		//add CC:
		if (!empty($data['email_template_cc']))
		{
			$cc_recipients = explode(',', $data['email_template_cc']);
			
			foreach ($cc_recipients as $value)
			{
				$this->phpmailer->AddCC($value);
			}
		}

		//add BCC:
		if (!empty($data['email_template_bcc']))
		{
			$bcc_recipients = explode(',', $data['email_template_bcc']);
			
			foreach ($bcc_recipients as $value)
			{
				$this->phpmailer->AddBCC($value);
			}
		}
		
		//add reply to
		if (!empty($data['reply_to_email']))
		{
			$this->phpmailer->AddReplyTo($data['reply_to_email'], $data['reply_to_email']);
		}
		else
		{
			//$this->phpmailer->AddReplyTo($data['email_template_from_email']);
			$this->phpmailer->AddReplyTo($this->config->item('sts_site_email'));
		}
		
		//send it!
		if(!$this->phpmailer->Send())
		{
		 	if ($this->config->item('sts_email_enable_debugging') == 1)
			{
		  		echo '<div class="alert alert-danger animated shake capitalize alert-msg hover-msg">' .  $this->phpmailer->ErrorInfo . '</div>';
			}
			
			
			return false;
		}
		else
		{
		    $this->phpmailer->ClearAddresses();
			$this->phpmailer->ClearAttachments();
			
			//check if email is to be archived
			if ($this->config->item('sts_email_enable_archive') == 1)
			{
				$this->_archive_email($type, $data);
			}
			
			return true;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _archive_email($type = 'admin', $data = '')
	{
		//check recipient group
		if (empty($data['group'])) { $data['group'] = ''; }
		
		$data = array( 	'type' => $type,
						'send_date' => _generate_timestamp(),
						'from_name' => $data['email_template_from_name'],
						'from_email' => $data['email_template_from_email'],
						'recipient_name' => $data['fname'],
						'recipient_email' => $data['primary_email'],
						'recipient_group' => $data['group'],
						'cc' => empty($data['email_template_cc']) ? '' : $data['email_template_cc'],
						'bcc' => empty($data['email_template_bcc']) ? '' : $data['email_template_bcc'],
						'subject' => $data['email_template_subject'],
						'html_body' => empty($data['email_template_body_html']) ? '' : $data['email_template_body_html'],
						'text_body' => empty($data['email_template_body_text']) ? '' : $data['email_template_body_text'],
						);
		
		$this->db->insert('email_archive', $data); 
	}
	
	// ------------------------------------------------------------------------
	
	function _get_email_templates($id = '', $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_emt_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_emt_column');
		
		//$this->db->join('admin_permissions', 'admin_users.admin_id = admin_permissions.admin_id');
		$this->db->where('program_id', $id);
		$this->db->order_by($sort_column, $sort_order); 	
		$query = $this->db->get('email_templates');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _add_program_email_templates($data = '')
	{
		
		
		
		$sql = "INSERT INTO `jam_email_templates` (`program_id`, `email_template_type`, `email_template_html`, `email_template_group`, `email_template_name`, `email_template_from_name`, `email_template_from_email`, `email_template_cc`, `email_template_bcc`, `email_template_subject`, `email_template_body_text`, `email_template_body_html`) VALUES
(" . $data['id'] . ",'admin', 'html', '', 'admin_reset_password_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Admin Reset Password', 'Hello {fname},\n\nyour login name is: {username}\n\nhere is your new password: {new_password}\n\nlogin URL: {admin_login_url}', '<p><span style=\"font-weight: bold; font-size: 10pt; font-family: Arial;\">Hello {fname},</span></p>\n<p><span style=\"font-size: 10pt; font-family: Arial;\">your login name is: {username}</span></p>\n<p><span style=\"font-size: 10pt; font-family: Arial;\">here is your new password: {new_password}</span></p>\n<p><span style=\"font-size: 10pt; font-family: Arial;\">login URL: </span><span style=\"font-size: 10pt; font-family: Arial;\"><a href=\"{admin_login_url}\">{admin_login_url}</a></span></p>'),
(" . $data['id'] . ",'admin', 'html', '', 'admin_affiliate_commission_generated_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'A New Commission Has Been Generated', 'Hello {fname},\n\nA new commission has been generated by one of your users:\n\nUsername: {member_username}\nCommission Amount: {commission_amount}\nCommission Date: {current_date}\n\nLogin to the admin area for more info:\n\n{admin_login_url}\n\n{site_name}\n', '<p>Hello {fname},<br />\n<br />\nA new commission has been generated by one of your users:<br />\n<br />\nUsername: {member_username}<br />\nCommission Amount: {commission_amount}<br />\nCommission Date: {current_date}<br />\n<br />\nLogin to the admin area for more info:<br />\n<br />\n{admin_login_url}<br />\n<br />\n{site_name}</p>'),
(" . $data['id'] . ",'admin', 'html', '', 'admin_alert_new_signup_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'A new user has signed up!', 'hello {fname},\n\na new user has been added to your site:\n\nFull Name: {member_name}\nUsername: {member_username}\nSignup IP: {signup_ip}\nSignup Date: {current_time}\n\nPlease view full details in the admin area:\n\n{admin_login_url}\n\n{site_name}', '<p>hello {fname},<br />\n<br />\na new user has been added to your site:<br />\n<br />\nFull Name: {member_name}<br />\nUsername: {member_username}<br />\nSignup IP: {signup_ip}<br />\nSignup Date: {current_time}<br />\n<br />\nPlease view full details in the admin area:<br />\n<br />\n{admin_login_url}<br />\n<br />\n{site_name}</p>'),
(" . $data['id'] . ",'admin', 'html', '', 'admin_failed_login_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'admin failed login', 'Someone, maybe you, tried to login to your eCommerce Manager admin area unsuccessfully.\n\nDetails are:\n\nlogin url: {admin_login_url}\nusername used: {username}\npassword used: {password}\ndate and time of login: {date}\nip address: {ip_address}', '<p>Someone, maybe you, tried to login to your eCommerce Manager admin area unsuccessfully.</p>\n<p>Details are:<br />\nlogin url: {admin_login_url} <br />\nusername used: {username}<br />\npassword used: {password}<br />\ndate and time of login: {date}<br />\nip address: {ip_address}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_login_details_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Account Login Details', 'Hello {fname},\n\nyour login details for {site_name}:\n\nusername: {primary_email}\npassword: {password}\n\nlogin URL: {login_url}', '<p>Hello {fname},</p>\n<p>your login details for {site_name}:</p>\n<p>&nbsp;</p>\n<p>username: {primary_email}<br />\npassword: {password}</p>\n<p>login URL: <a href=\"{%login_url%}\">{login_url}</a></p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_performance_group_upgrade_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Your group membership has been upgraded', 'Hello {fname},\n\nYour affiliate group membership has been upgraded.\n\nYou''ve achieved this through your performance.\n\nYour new group is {upgraded_affiliate_group}.\n\nThanks!\n{site_name}', '<p>Hello {fname},<br />\n<br />\nYour affiliate group membership has been upgraded.<br />\n<br />\nYou''ve achieved this through your performance.<br />\n<br />\nYour new group is {upgraded_affiliate_group}.<br />\n<br />\nThanks!<br />\n{site_name}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_performance_bonus_amount_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'performance bonus awarded', 'Hello {fname},\n\nCongratulations!  You''ve earned a performance bonus commission for all your affiliate referrals!\n\nBonus amount: {bonus_amount}\n\n\nThanks again!\n\n{site_name}', '<p>Hello {fname},<br />\n<br />\nCongratulations!&nbsp; You''ve earned a performance bonus commission for all your affiliate referrals!<br />\n<br />\nBonus amount: {bonus_amount}<br />\n<br />\n<br />\nThanks again!<br />\n<br />\n{site_name}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_commission_generated_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'New Commission Generated', 'Hello {fname},\n\nCongratulations!  You''ve earned a referral commission!\n\nCommission Amount: {commission_amount}\nCommission Date: {current_date}\n\nLogin to check your affiliate stats:\n{login_url}\n\n{site_name}\n', '<p>Hello {fname},<br />\n<br />\nCongratulations!&nbsp; You''ve earned a referral commission!<br />\n<br />\nCommission Amount: {commission_amount}<br />\nCommission Date: {current_date}<br />\n<br />\nLogin to check your affiliate stats:<br />\n{login_url}<br />\n<br />\n{site_name}<br />\n&nbsp;</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_payment_sent_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Your Affiliate Payment has been sent', 'Hello {fname},\n\nWe''ve sent your affiliate payment.\n\nthe amount you''ve made is {payment_amount}\n\n{affiliate_note}\n\nThanks again for being a great affiliate!\n\n{site_name}\n{login_url}', '<p>Hello {fname},<br />\n<br />\nWe''ve sent your affiliate payment.<br />\n<br />\nthe amount you''ve made is {payment_amount}<br />\n<br />\n{affiliate_note}<br />\n<br />\nThanks again for being a great affiliate!<br />\n<br />\n{site_name}<br />\n{login_url}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_reset_password_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'your password has been reset', 'Hello {fname},\n\nhere is your new password: {new_password}\n\nlogin URL: {login_url}\n\n\n{site_name}\n\n{login_url}\n', '<p>Hello {fname},<br />\n<br />\nhere is your new password: {new_password}<br />\n<br />\nlogin URL: {login_url}</p>\n<p>{site_name}</p>\n<p>{login_url}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_email_confirmation_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Account Confirmation Email', 'Hello {fname},\n\nplease click on the following link to confirm your account with us:\n\n{confirm_link}\n\n{site_name}\n{login_url}', '<p>Hello {fname},<br />\n<br />\nplease click on the following link to confirm your account with us:<br />\n<br />\n<a href=\"{confirm_link}\">click here to confirm</a><br />\n<br />\n{site_name}<br />\n{login_url}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_send_downline_email', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Message from your sponsor', 'Hello {downline_member_name},\n\nYour sponsor, {downline_sponsor_name} has sent you a message:\n\n{downline_message_text} \n\n{downline_sponsor_name}\n{downline_sponsor_email}\n{downline_sponsor_affiliate_link}\n\n\n{site_name}\n{login_url}\n\n', '<p>Hello {downline_member_name},<br />\n<br />\nYour sponsor, {downline_sponsor_name} has sent you a message:<br />\n<br />\n{downline_message_html} <br />\n<br />\n{downline_sponsor_name}<br />\n{downline_sponsor_email}<br />\n{downline_sponsor_affiliate_link}<br />\n<br />\n<br />\n{site_name}<br />\n{login_url}</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_downline_signup', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'You just referred someone!', 'Hello {fname},\n\nYou have just referred someone in your downline!\n\n{downline_name}\n{downline_email}\n', '<p>Hello {fname},<br />\n<br />\nYou have just referred someone in your downline!<br />\n<br />\n{downline_name}<br />\n{downline_email}<br />\n&nbsp;</p>'),
(" . $data['id'] . ",'member', 'html', '', 'member_affiliate_marketing_approval_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Affiliate Registration Approval', 'Hello {fname},\n\nYour affiliate registration has been approved!\n\n" . $data['program_name'] . "', '<p>Hello {fname},<br />\n<br />\nYour affiliate registration has been approved!<br />\n<br />" . $data['program_name'] . "</p>'),
(" . $data['id'] . ",'member', 'html', '', 'admin_affiliate_marketing_activation_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Affiliate Activation Request', 'Hello,\n\n{member_name} is requesting that you activate his affiliate account.\n\nPlease login to your admin area to confirm\n\n" . $data['program_name'] . "\n" . base_url() . "programs/" . $data['signup_link'] . "', '<p>Hello,<br />\n<br />\n{member_name} is requesting that you activate his affiliate account.<br />\n<br />Please login to your admin area to confirm<br /><br />" . $data['program_name'] . "<br />\n" . base_url() . "programs/" . $data['signup_link'] . "/</p>'),
(" . $data['id'] . ", 'member', 'html', '', 'member_affiliate_program_confirm_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Please confirm your affiliate program account', 'Hello {fname},\r\n\r\nPlease click on the link below to confirm your account access to our {program_name}\r\n\r\n{confirm_link}', '<p>Hello {fname},</p>\r\n<p>Please click on the link below to confirm your account access to our {program_name}</p>\r\n<p>{confirm_link}</p>'),
(" . $data['id'] . ", 'member', 'html', '', 'member_affiliate_commission_stats_template', '" . $data['program_name'] . "', '" . $this->config->item('sts_site_email') . "', '', '', 'Your Affiliate Commission Stats Report', 'Hello {fname},\r\n\r\nHere is your current affiliate commissions stats:\r\n\r\n{current_month} {current_year}\r\nUnpaid Commissions: {current_month_unpaid_commissions}\r\nTotal Commissions Made:\r\nUnpaid Commissions: {total_unpaid_commissions}\r\nPaid Commissions: {total_paid_commissions}\r\n\r\nYou can login to our members area to view all of your stats in more detail:\r\n\r\n{login_url}\r\n\r\n', '<p>Hello {fname},<br /><br />Here is your current affiliate commissions stats:</p><p>{current_month} {current_year}<br />Unpaid Commissions: {current_month_unpaid_commissions}<br /></p><p>Total Commissions Made:<br />Unpaid Commissions: {total_unpaid_commissions}<br />Paid Commissions: {total_paid_commissions}</p><p>You can login to our members area to view all of your stats in more detail:</p><p>{login_url}</p>')
;";
		
		if ($this->db->query($sql))
		{
			//log success
			log_message('info', 'program ID '. $data['id'] . ' templates added in email templates table');
			
			return true;
		}
		else
		{
			show_error($this->lang->line('could_not_add_program_email_templates'));
			
			//log error
			log_message('error', 'Could not add program email templates ' . $data['id'] . 'in email templates table');
			
			return false;
		}
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_program_email_templates($id = '', $fields = '', $sort_order = '', $sort_column = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_emt_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_emt_column');
		
		if (!empty($fields))
		{
			$this->db->select($fields);
		}
		
		$this->db->where('program_id', $id);
		$this->db->order_by($sort_column, $sort_order); 	
		
		$query = $this->db->get('email_templates');

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_email_template_details($id = '')
	{
		$this->db->where('id', $id);
		$query = $this->db->get('email_templates');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return false;
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_template_type($type = '')
	{
		if ($type == 'custom')
		{
			return true;
		}
		
		return false;
	}

	// ------------------------------------------------------------------------	
	
	function _update_email_template($id = '')
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//update template data
		$this->db->where('id', $id);
		
		if (!$this->db->update('email_templates', $data))
		{
			show_error($this->lang->line('could_not_update_template'));
			
			//log error
			log_message('error', 'Could not update template ID ' . $id . 'in email templates table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'template ID '. $id . ' updated in email templates table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_email_template($id = '')
	{
		$sdata = $this->_get_email_template_details($id);
		
		if ($sdata['email_template_type'] != 'custom')
		{
			return false;
		}
		
		$this->db->where('id', $id);
		if ($this->db->delete('email_templates'))
		{
			
			//log success
			log_message('info', 'template ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_email_template'));
			
			//log error
			log_message('error', 'template ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------	
	
	function _add_email_template()
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		if (!$this->db->insert('email_templates', $data))
		{
			show_error($this->lang->line('could_not_add_template'));
			
			//log error
			log_message('error', 'Could not add template in email templates table');
			
			return false;
		}
		else
		{
			
			$id = $this->db->insert_id();
			
			//log success
			log_message('info', 'template ID '. $id . ' add in email templates table');
		}
		
		return $id;
	}
}
?>