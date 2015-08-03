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
| FILENAME - cron.php
| -------------------------------------------------------------------------     
| 
| This controller file runs all necessary cron jobs for the system
|
*/

class Cron extends Cron_Controller {

	function __construct()
	{
		parent::Cron_Controller();	
		
		set_time_limit(0);
	}
	
	// ------------------------------------------------------------------------
	
	function send_email()
	{
		//set data array
		$data = $this->security_model->_load_config('cron');
		$txt = '';
		
		$this->load->model('emailing_model');
		
		//queue follow ups
		$this->load->model('mailing_lists_model');
		
		$msg = $this->emailing_model->_queue_follow_ups();
		
		if (!empty($msg))
		{
			//log success
			log_message('info', $msg);	
			$txt .= $msg . "\n";
		}
		
		
		$msg = $this->emailing_model->_flush_queue('date');
		
		if (!empty($msg))
		{
			//log success
			log_message('info', $msg);
			$txt .= $msg . "emails flushed from queue \n";
		}
		
		echo nl2br($txt);
	}
	
	// ------------------------------------------------------------------------
	
	function bounce()
	{
		//set data array
		$data = $this->security_model->_load_config('cron');
		
		$this->load->model('bounce_model');
		
		$msg = $this->bounce_model->_process_bounced_emails();
	
		if (!empty($msg))
		{
			//log success
			log_message('info', $msg);	
			echo $msg;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function affiliate_stats()
	{
		//set data array
		$data = $this->security_model->_load_config('cron');
		
		$this->load->model('emailing_model');
		$this->load->model('mailing_lists_model');
		$this->load->model('commissions_model', 'comms');
		
		$num_options = get_default_currency($this->config->item('sts_site_default_currency'));
		 
		$msg = $this->comms->_send_affiliate_stats($num_options);
	
		if (!empty($msg))
		{
			//log success
			log_message('info', $msg);	
			echo $msg;
		}
	}
	// ------------------------------------------------------------------------
	
	function process2()
	{
		//set data array
		$data = $this->security_model->_load_config('cron');
		
		$this->load->model('emailing_model');
		$this->load->model('mailing_lists_model');
		$this->load->model('commissions_model', 'comms');
		$this->load->model('groups_model', 'groups');
		$this->load->model('tracking_model');
		$this->load->model('rewards_model');
		
		$txt = '';
		
		$sql = 'SELECT ' . $this->db->dbprefix('commissions') . '.*, ' . $this->db->dbprefix('programs') . '.commission_frequency
				FROM ' . $this->db->dbprefix('commissions') . '
				LEFT JOIN ' . $this->db->dbprefix('programs') . '  
				ON ' . $this->db->dbprefix('commissions') . '.program_id = ' . $this->db->dbprefix('programs') . '.program_id 
				WHERE recur < \'' . _generate_timestamp() . '\'
				AND recur > \'0\' ORDER BY comm_id ASC';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			//set the commission status
			switch ($this->config->item('sts_affiliate_new_commission'))
			{
				case 'no_pending':
				case 'alert_pending':
					
					$comm_status = 'pending';
					
				break;
				
				case 'no_unpaid':
				case 'alert_unpaid':
				
					$comm_status = 'unpaid';
					
				break;
			}
		 
			$i = 0;
			
			foreach ($query->result_array() as $row)
			{
				
				//echo _show_date($row['date']) . '<BR />';
				//echo _show_date($row['recur']) . '<BR />';
				$comm_id = $row['comm_id'];
				//now add it
				$row['commission_notes'] = 'previous commission: ' . $row['comm_id'];
				$row['comm_id'] = '';
				$row['date'] = $row['recur'];
				$row['customer_name'] = 'previous comm: ' . $comm_id;
				$row['recur'] = $row['recur'] + (3600 * 24 * $row['commission_frequency']);	
				$row['date_paid'] = '';
				$row['approved'] = '0';
				$row['performance_paid'] = '0';
				$row['payment_id'] = '0';
				
				$row['comm_status'] = $comm_status;
				//echo _show_date($row['date']) . '<BR />';
				//echo _show_date($row['recur']); 
				unset($row['commission_frequency']);
				//echo '<pre>';print_r($row);
				$this->db->insert('commissions', $row);
				//die();
				
				//update commission
				
				$this->db->where('comm_id', $comm_id);
				$this->db->update('commissions', array('recur' => $this->db->insert_id()));
				
				$i++;
			}
			
			echo $i .' recurring commissions processed';
		}
		 
		
	}
	
	// ------------------------------------------------------------------------
	
	function process()
	{
		//set data array
		$data = $this->security_model->_load_config('cron');
		
		$this->load->model('emailing_model');
		$this->load->model('mailing_lists_model');
		$this->load->model('commissions_model', 'comms');
		$this->load->model('groups_model', 'groups');
		$this->load->model('tracking_model');
		$this->load->model('rewards_model');
		
		$txt = '';
		
		/*
		|--------------------------
		| auto approve commissions
		|--------------------------
		*/
			
		$msg = $this->comms->_auto_approve_commissions($this->config->item('sts_affiliate_auto_approve_commissions'));

		if (!empty($msg))
		{
			//log success
			log_message('info', $msg);	
			$txt .= $msg . "\n";
		}

		
		/*
		|--------------------------------
		| run recurring commissions
		|--------------------------------
		*/

		$msg = $this->comms->_generate_recurring_commissions(); 
		
		if (!empty($msg))
		{
			//log success
			log_message('info', $msg);	
			$txt .= $msg . "\n";
		}
		
		/*
		|--------------------------------
		| run performance rewards
		|--------------------------------
		*/

		$msg = $this->rewards_model->_generate_rewards(); 
		 
		if (!empty($msg))
		{
			//log success
			log_message('info', $msg);	
			$txt .= $msg . "\n";
		}
		
		/*
		|--------------------------
		| auto prune email archive
		|--------------------------
		*/
		
		if ($this->config->item('sts_email_auto_prune_archive') != '0')
		{
			$msg = $this->emailing_model->_auto_prune_archive($this->config->item('sts_email_auto_prune_archive'));
	
			if (!empty($msg))
			{
				//log success
				log_message('info', $msg);	
				$txt .= $msg . "\n";
			}
		}	
		
		
		/*
		|------------------------------
		| prune ad tracking referrals
		|------------------------------
		*/
		
		$track_days = $this->config->item('sts_tracking_auto_prune_days');
		
		if (!empty($track_days))
		{
			$msg = $this->tracking_model->_prune_ad_trackers($track_days);
		
			if (!empty($msg))
			{
				log_message('info', $msg);
				$txt .= $msg . "\n";
			}
		}
		
		/*
		|------------------------------
		| prune impressions
		|------------------------------
		*/
		
		$track_days = $this->config->item('sts_impressions_auto_prune_days');
		
		if (!empty($track_days))
		{
			$msg = $this->tracking_model->_prune_impressions($track_days);
		
			if (!empty($msg))
			{
				log_message('info', $msg);
				$txt .= $msg . "\n";
			}
		}
		
		/*
		|------------------------------
		| optimize the database tables
		|------------------------------
		*/
		
		$this->load->dbutil();
		
		if ($this->dbutil->optimize_database())
		{
			log_message('info', 'database optimized successfully');
			$txt .= "database optimized successfully\n";
		}
		
		/*
		|----------------------
		| run database backup 
		|----------------------
		*/
		
		if ($this->config->item('sts_backup_enable_schedule') == 1)
		{
			$this->load->model('backup_model');
			
			$msg = $this->backup_model->_backup_db();
			
			if (!empty($msg))
			{
				//log success
				log_message('info', $msg . ' backup file created successfully');
				$txt .= "backup file created successfully\n";
			}
		}
		/*
		|--------------------------------------------
		| check email queue for non-processed emails
		|--------------------------------------------
		*/
		
		$msg = $this->emailing_model->_flush_queue('date', true);
		
		if (!empty($msg))
		{
			//log success
			log_message('info', $msg);
			$txt .= $msg . "emails flushed from queue \n";
		}
		
		/*
		|------------------------------
		| check rsl updates
		|------------------------------
		*/
		
		//$msg = $this->security_model->_check_rsl_updates();
		
		echo nl2br($txt);
	}
}
?>