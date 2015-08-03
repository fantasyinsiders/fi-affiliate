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
| FILENAME - bounce_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the tasks managing bounced emails
|
*/

class Bounce_Model extends CI_Model {
	
	function _process_bounced_emails()
	{
		if (!function_exists('imap_open')) { return 'PHP IMAP functions not enabled'; }
		
		//imap server details
		$server = $this->config->item('sts_email_bounce_server');
		$login = $this->config->item('sts_email_bounce_username');
		$password = $this->config->item('sts_email_bounce_password');
		$port = $this->config->item('sts_email_bounce_port');
		$service_flags = $this->config->item('sts_email_bounce_service_flags');
		
		if ($server && $login && passsword)
		{
			$conn = @imap_open('{' . $server . ':' . $port . $service_flags . '}', $login, $password);
			
			if (!$conn) { return "bounce connection to $server failed"; }
			
			$headers = @imap_headers($conn);	
			
			$num_emails = sizeof($headers);
			
			$msgs = 0;
	
			if ($num_emails > 0) //if there are emails parse it
			{
				for($i = 1; $i <= $num_emails; $i++)
				{
					$mailHeader = @imap_headerinfo($conn, $i);				
					
					//get the email body
					$body = imap_body($conn, $i);
					$lines = explode("\n", $body);
					
					for ($j=0; $j < count($lines); $j++)
					{
						/*
						if (preg_match("/^Subject: (.*)/", $lines[$j], $matches)) {
						  //echo 'subject:' . $matches[1];
						}
						if (preg_match("/^From: (.*)/", $lines[$j], $matches)) {
						  //echo '<br />from: ' . $matches[1];
						}
						*/
						
						if (preg_match("/^To: (.*)/", $lines[$j], $matches)) 
						{	
							$to = $matches[1];	
						}
					}
					
					//reset the lines first
					reset($lines);
					
					if ($to)
					{
						$to = str_replace('<','',$to);
						$to = str_replace('>','',$to);	
						$to = str_replace("\r",'',$to);	
						$to = str_replace("\n",'',$to);	
						
						//insert the email	
						$this->_insert_bounce_email($to, $body);
						
						//check how many times the email has bounced 
						$total = $this->_count_bounced_emails($to);
						
						//if it has bounced more than the threshold, delete it
						if ($total >= '5') //threshold number
						{
							//remove the user from all lists
							$this->_delete_from_lists($to);
						}					
					}
					
					$msgs++;
					imap_delete($conn, $i);
				}
				
				imap_expunge($conn);
				imap_close($conn);
			}	
			
			return $msgs . ' bounced emails processed';
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_from_lists($email = '')
	{
		//first get ther user id
		$this->db->where('primary_email', $email);
		$query = $this->db->get('members');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			//delete if from lists
			$this->db->where('member_id', $row['member_id']);
			$this->db->delete('email_mailing_list_members');
		}
		
		//delete from bounces table
		$this->db->where('email', $email);
		$this->db->delete('email_bounces');
	}
	
	// ------------------------------------------------------------------------
	
	function _count_bounced_emails($email = '')
	{
		$this->db->where('email', $email);
		$query = $this->db->get('email_bounces');
		
		if ($query->num_rows() > 0)
		{
			return $query->num_rows();
		}
		
		return '0';	
	}
	
	// ------------------------------------------------------------------------
	
	function _insert_bounce_email($email = '', $body = '')
	{
		$insert = array('email' => $email,
						'date'  => _generate_timestamp(),
						'body'  => $body
						);	
		
		$this->db->insert('email_bounces', $insert);
		
		return true;
	}
}
?>