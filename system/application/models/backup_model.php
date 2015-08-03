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
| FILENAME - backup_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for backing up data
|
*/

class Backup_Model extends CI_Model {	
	
	function _backup_db()
	{
		$max = '25';
		$total_backups = $this->_get_backups();
	
		rsort($total_backups);
		
		$home = $this->config->item('sts_backup_path');
		$val = ''; $res = '';
		
		if (is_writable($this->config->item('sts_backup_path')))
		{
			
			if (count($total_backups) >= $max)
			{
				@unlink($home . '/' . end($total_backups));
			}
		
			//get the db info
			require APPPATH . 'config/database.php';
			
			$date = _generate_timestamp() . '-' . date('M-d-Y_G:i:s', _generate_timestamp());
			
			$run = exec("mysqldump --opt --host=".$db['default']['hostname']." --user=".$db['default']['username'] . " --password=".$db['default']['password']." ".$db['default']['database'] . " > $home/db_" . $db['default']['database'] . "_$date.sql", $res, $val);

			if ($val != 0)
			{
				//log error
				$data['error'] = true;
				$data['msg'] = 'Could not run database backup';	
				log_message('error', 'Could not run database backup');
			}
			else
			{
				//log success
				log_message('info', 'database backed up successfully');	
				
				$data['error'] = false;
				$data['msg'] =  "db_" . $db['default']['database'] . "_$date.sql";
			}
		}
		else
		{
			$data['error'] = true;
			$data['msg'] = 'backup path is invalid or not writeable';
		}
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
	
	function _restore_db($file = '')
	{
		//get the db info
		require APPPATH . 'config/database.php';
			
		$home = $this->config->item('sts_backup_path');
		
		if (file_exists($home . '/' . $file))
		{
			$run = exec("mysql --host=".$db['default']['hostname']." --user=".$db['default']['username'] . " --password=".$db['default']['password']." ".$db['default']['database'] . " < $home/$file", $res, $val);
			
			if ($val != 0)
			{
				//log error
				log_message('error', 'Could not restore database backup');
			}
			else
			{
				//log success
				log_message('info', 'database restored successfully');	
				
				return  $file;
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	

	function _get_backups()
	{
		if ($handle = @opendir($this->config->item('sts_backup_path')))
		{
			$backups = array();
			while (false !== ($file = readdir($handle)))
			{
				if ($file != "." && $file != ".." && $file != "index.html")
				{
					array_push($backups, $file);
				}
			}
			closedir($handle);
			 
			if (!empty($backups))
			{
				rsort($backups);
				
				$output = array_slice($backups, 0, 24); 
				return $output;
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
}
?>