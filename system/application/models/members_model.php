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
| FILENAME - members_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing the members
|
*/

class Members_Model extends CI_Model {	
	
	// ------------------------------------------------------------------------
	
	function _add_member_program($mid = '', $pid = '')
	{
		$data = array('member_id' => $mid,
					  'program_id' => $pid,
					  'confirm_id' => _generate_random_string('5', true)
					  );
		
		$query = $this->db->insert('members_programs', $data);
		
		if ($query)
		{
			return $data;	
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_member_program($id = '', $col = 'member_id')
	{
		$this->db->where($col, $id);
		$query = $this->db->get('members_programs');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();	
		}
		
		return false;
	}
	
	function _update_password($id = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
	
		$this->db->where('member_id', $id);
		
		if ($this->db->update('members', array('password' => $data['password'])))
		{
			return true;	
		}
	
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_member_program($id = '', $program_id = '')
	{
		//check if the user is already subscribed
		$this->db->where('member_id', $id);
		$this->db->where('program_id', $program_id);
		$query = $this->db->get('members_programs');
		
		if ($query->num_rows() > 0)
		{
			$prg_group = array('program_id' => $program_id);
			
			$this->db->where('member_id', $id);
			
			if ($this->db->update('members_programs', $prg_group))
			{
				return true;
			}
		}
		else
		{
			//insert the data
			$prg_group = array('program_id' => $program_id,
							   'member_id' => $id
							   );
			
			if ($this->db->insert('members_programs', $prg_group))
			{
				return true;
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_next_member($mid, $next = 'next')
	{
		if ($next == 'next')
		{
			$this->db->where('member_id > ', $mid);
			$this->db->order_by('member_id', 'ASC');	
		}
		else
		{
			$this->db->where('member_id < ', $mid);
			$this->db->order_by('member_id', 'DESC');	
		}
		
		
		$this->db->limit(1);
		
		$query = $this->db->get('members');
			
		if ($query->num_rows() > 0)
		{
			$mid = $query->row_array();
		
			return $mid['member_id'];
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _add_member_list($post_array = array())
	{
		$data = $this->db_validation_model->_clean_data($post_array);
		
		//generate username
		$data['username'] = _generate_random_username($data['fname'], _generate_random_string('4'));
		$data['login_status'] = '1';
		
		$list_id = $data['list_id'];
		
		unset($data['list_id']);
		unset($data['login']);
		
		//insert into db
		if (!$this->db->insert('members', $data))
		{
			show_error($this->lang->line('could_not_add_member'));
			
			//log error
			log_message('error', 'Could not insert member into members table');
			
			return false;
		}
		else
		{
			$member_id = $this->db->insert_id();
			
			//log success
			log_message('info', 'Member ID '. $member_id . ' inserted into members table');
		}
		
		//insert shipping address
		$sdata['sh']['member_id'] = $member_id;
		
		if (!$this->db->insert('members_addresses', $sdata['sh']))
		{
			show_error($this->lang->line('could_not_add_member'));
			
			//log error
			log_message('error', 'Could not insert members address for member ID ' . $member_id . ' into members_address table');
		}
		else
		{
			//log success
			log_message('info', 'Member ID '. $member_id . ' inserted into members_addresses table');
		}
		
		//filter affiliate groups
		$aff_group = empty($data['affiliate_group']) ? '1' : $data['affiliate_group'];
		
		//add to affiliate groups		
		$sdata['ag'] = array('member_id' => $member_id,
							 'group_id' => $aff_group
							 );
		
		if (!$this->db->insert('members_groups', $sdata['ag']))
		{
			show_error($this->lang->line('could_not_add_member'));
			
			//log error
			log_message('error', 'Could not insert affiliate group for member ID ' . $member_id . ' into affiliate_groups table');
		}
		else
		{
			//log success
			log_message('info', 'Member ID '. $member_id . ' inserted into affiliate_groups table');
		}
		
		//add to mailing lists
		$d = array('mailing_list_id' => $list_id,
						   'member_id' => $member_id,
						   'sequence_id' => 1,
						   'send_date'	=>	_generate_timestamp());
		if (!$this->db->insert('email_mailing_list_members', $d))
		{
			show_error($this->lang->line('could_not_add_member'));
		
			//log error
			log_message('error', 'Could not insert mailing_lists for member ID ' . $member_id . ' into email_mailing_list_members table');
		}
		else
		{
			//log success
			log_message('info', 'Member ID '. $member_id . ' inserted into email_mailing_list_members table');
		}
		
		return true;
		
	}	
	
	// ------------------------------------------------------------------------
	
	function _set_user_status($email = '', $status = '0')
	{
		$this->db->where('primary_email', $email);
		if ($this->db->update('members', array('status' => $status)))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_username($username = '')
	{
		$this->db->where('username', $username);
		$query = $this->db->get('members');
		
		if ($query->num_rows() > 0)
		{
			$username = _generate_random_username($username, 3);
		}
		
		return $username;
	}
	
	// ------------------------------------------------------------------------
	
	function _add_member($post_array = '', $mod_type = '') //insert the new member into db
	{	
		//clean the data first
		$data = $this->db_validation_model->_clean_data($post_array);
		
		//set update date and by
		$data['updated_by'] = $this->session->userdata('username');
		$data['updated_on'] = _generate_timestamp();
		$data['signup_date'] = _generate_timestamp();
		$data['confirm_id'] = _generate_random_string('5', true);
		
		//filter affiliate groups
		//$sdata['ag'] = array('group_id' => '1');
		if (!empty($data['affiliate_groups']))
		{
			$sdata['ag'] = array('group_id' => $data['affiliate_groups']); //affiliate groups array
			
		}
		unset($data['affiliate_groups']);
		
		//filter sponsor
		if (!empty($data['sponsor'])) 
		{
			$sponsor_data = $this->db_validation_model->_get_details('members', 'member_id', 'username', $data['sponsor']);
			
			$data['sponsor_id'] = !empty($sponsor_data) ? $sponsor_data[0]['member_id'] : 0;
			$data['original_sponsor_id'] = $data['sponsor_id'];
			
			//check for forced matrix
			if($this->license_model->_check_mx() == true)
			{
				if ($this->config->item('sts_affiliate_enable_mlm_forced_matrix') == 1 && !empty($data['sponsor_id']))
				{
					$new_sponsor = $this->downline->_get_matrix_sponsor($data['sponsor_id']);	
					
					if (empty($new_sponsor)) 
					{ 
						switch ($this->config->item('sts_affiliate_forced_matrix_spillover'))
						{	
							case 'none':
								
								$data['sponsor_id'] = 0;
								
							break;
							
							case 'random':
							
								$data['sponsor_id'] = $this->_get_random_aff();
							break;
							
							case 'specific':
							
								$data['sponsor_id'] = $this->config->item('sts_affiliate_forced_matrix_affiliate_id');
								
							break;
							
							case 'original_sponsor':
								
								$data['sponsor_id'] = $data['original_sponsor_id'];
								
							break;

						}
					}
					else
					{
						$data['sponsor_id'] = $new_sponsor; 	
					}
				}
			}
		}

		unset($data['sponsor']);
		
		//filter out mailing lists	
		if (!empty($data['email_mailing_lists']))
		{
			$sdata['ml'] = $data['email_mailing_lists'];
		}
		unset($data['email_mailing_lists']);
		
		//filter out programs
		if (!empty($data['programs']))
		{
			$sdata['mp'] = $data['programs'];
			unset($data['programs']);
		}
		
		//set the filter for the first, last  and payment name
		$data['fname'] = str_replace('|', ' ', $data['fname']);
		$data['lname'] = !empty($data['lname']) ? str_replace('|', ' ', $data['lname']) : '';
		$data['payment_name'] = !empty($data['payment_name']) ? str_replace('|', ' ', $data['payment_name']) : '';
		
		//insert into db
		if (!$this->db->insert('members', $data))
		{
			show_error($this->lang->line('could_not_add_member'));
			
			//log error
			log_message('error', 'Could not insert member into members table');
			
			return false;
		}
		else
		{
			$member_id = $this->db->insert_id();
			
			//log success
			log_message('info', 'Member ID '. $member_id . ' inserted into members table');
		}

		//add to affiliate groups
		if (!empty($sdata['ag']))
		{
			$sdata['ag']['member_id'] = $member_id;
			
			if (!$this->db->insert('members_groups', $sdata['ag']))
			{
				show_error($this->lang->line('could_not_add_member'));
				
				//log error
				log_message('error', 'Could not insert affiliate group for member ID ' . $member_id . ' into affiliate_groups table');
			}
			else
			{
				//log success
				log_message('info', 'Member ID '. $member_id . ' inserted into affiliate_groups table');
			}
		}
		
		//add to mailing lists
		if (!empty($sdata['ml']))
		{
			foreach ($sdata['ml'] as $value)
			{
				$d = array('mailing_list_id' => $value,
						   'member_id' => $member_id,
						   'sequence_id' => 1,
						   'send_date'	=>	_generate_timestamp());
				if (!$this->db->insert('email_mailing_list_members', $d))
				{
					show_error($this->lang->line('could_not_add_member'));
				
					//log error
					log_message('error', 'Could not insert mailing_lists for member ID ' . $member_id . ' into email_mailing_list_members table');
				}
				else
				{
					//log success
					log_message('info', 'Member ID '. $member_id . ' inserted into email_mailing_list_members table');
				}
				
			}
		}
			
		//now add the programs
		if (!empty($sdata['mp']))
		{
			foreach ($sdata['mp'] as $value)
			{
				$d = array('program_id' => $value,
						   'member_id' => $member_id);
				
				if (!$this->db->insert('members_programs', $d))
				{
					show_error($this->lang->line('could_not_add_member'));
				
					//log error
					log_message('error', 'Could not update programs for member ID ' . $member_id . ' in members_programs table');
				}
				else
				{
					//log success
					log_message('info', 'Member ID '. $member_id . ' updated in members_programs table');
				}
			}
		}
		
		//prepare member info array to be sent back
		$data['member_id'] = $member_id;
		
		return $data;
		
	}

	// ------------------------------------------------------------------------
	
	function _get_random_aff()
	{
		$this->db->select('member_id');
		$this->db->order_by('member_id', 'RAND'); 
		$this->db->limit('1');
		$query = $this->db->get('members');	
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			return $row['member_id'];
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_login_status($id = '')
	{
		$login_stat = array('login_status' => '0');
		$this->db->where('member_id', $id);
		
		if ($this->db->update('members', $login_stat))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_member_list($type = '', $id = '', $list = '')
	{
		switch ($type)
		{
			case 'add':
			
				//first check if user is already on list
				$this->db->where('member_id', $id);
				$this->db->where('mailing_list_id', $list);
				$query = $this->db->get('email_mailing_list_members');
				
				if ($query->num_rows() > 0)
				{
					return true;
				}
				else
				{
					$sdata = array('member_id' => $id,
								   'mailing_list_id' => $list,
								   'sequence_id' => 1,
						   		   'send_date'	=>	_generate_timestamp());
					if ($this->db->insert('email_mailing_list_members', $sdata))
					{
						return true;
					}
				}
			
			break;
			
			case 'remove':
			
				$this->db->where('member_id', $id);
				$this->db->where('mailing_list_id', $list);
				
				if ($this->db->delete('email_mailing_list_members'))
				{
					return true;
				}
			
			break;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_member_group_data($mid = '')
	{
		$this->db->join('affiliate_groups', 'affiliate_groups.group_id = members_groups.group_id', 'left');
		$this->db->where('member_id', $mid);	
		$query = $this->db->get('members_groups');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _change_status($data = '', $type = '0')
	{

		foreach ($data as $id)
		{
			
			$this->db->where('member_id', $id);
			
			if ($type == 'delete')
			{
				$this->_delete_member((int)($id));
			}
			else
			{
				//update member in db
				
				$data = array(
								'status' => $type,
								'login_status' => '0',
							);
				
				if (!$this->db->update('members', $data))
				{
					show_error($this->lang->line('could_not_update_member'));
					
					//log error
					log_message('error', 'Could not update member ID #' . $id . ' in members table');
					return false;
				}
				
				//log success
				log_message('info', 'Status Changed for member ID# ' . $id);
			}
			
		}
		
		return true;		
	}
	
	// ------------------------------------------------------------------------	
	
	function _delete_member($id = '', $field = 'member_id') //delete a member
	{
		$this->db->where($field, $id);		
		
		$query = $this->db->get('members');
		if ($query->num_rows() > 0)
		{
			$mdata = $query->row_array();	
		}
		else
		{
			return false;	
		}
		//update the members table, set the sponsors to the sponsor above
		$sponsor_data = $this->db_validation_model->_get_details('members', 'sponsor_id', 'member_id', $mdata['member_id']);	
		
		if (empty($sponsor_data[0]['sponsor_id']))
		{
			$sponsor_data[0]['sponsor_id'] = 0;
		}
		
		$update_data = array('sponsor_id' => $sponsor_data[0]['sponsor_id']);
		
		$this->db->where('sponsor_id', $mdata['member_id']);
		
		if ($this->db->update('members', $update_data))
		{
			//log success
			log_message('info', 'Sponsor ID #' . $mdata['member_id'] . ' updated in members table');		
		}
		else
		{
			show_error($this->lang->line('could_not_delete_member'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $mdata['member_id'] . ' from members table');
			
			return false;
		}
		
		//delete from affiliate commissions
		$this->db->where('member_id', $mdata['member_id']);		
			
		if ($this->db->delete('commissions'))
		{
			//log success
			log_message('info', 'Member ID #' . $mdata['member_id'] . ' deleted from affiliate commissions table');				
		}
		else
		{
			show_error($this->lang->line('could_not_delete_member'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $mdata['member_id'] . ' from affiliate commissions table');
			
			return false;
		}
		
		
		//delete from members_programs
		$this->db->where('member_id', $mdata['member_id']);		
			
		if ($this->db->delete('members_programs'))
		{
			//log success
			log_message('info', 'Member ID #' . $mdata['member_id'] . ' deleted from members_programs table');				
		}
		else
		{
			show_error($this->lang->line('could_not_delete_member'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $mdata['member_id'] . ' from members_programs table');
			
			return false;
		}
		
		//delete from affiliate payments
		$this->db->where('member_id', $mdata['member_id']);		
			
		if ($this->db->delete('affiliate_payments'))
		{
			//log success
			log_message('info', 'Member ID #' . $mdata['member_id'] . ' deleted from affiliate payments table');				
		}
		else
		{
			show_error($this->lang->line('could_not_delete_member'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $mdata['member_id'] . ' from affiliate payments table');
			
			return false;
		}

		
		//delete from email mailing lists members
		$this->db->where('member_id', $mdata['member_id']);		
			
		if ($this->db->delete('email_mailing_list_members'))
		{
			//log success
			log_message('info', 'Member ID #' . $mdata['member_id'] . ' deleted from email mailing list members table');				
		}
		else
		{
			show_error($this->lang->line('could_not_delete_member'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $mdata['member_id'] . ' from email mailing list members table');
			
			return false;
		}
		
		//delete from email queue 
		$this->db->where('member_id', $mdata['member_id']);		
			
		if ($this->db->delete('email_mailing_list_members'))
		{
			//log success
			log_message('info', 'Member ID #' . $mdata['member_id'] . ' deleted from email mailing list members table');				
		}
		else
		{
			show_error($this->lang->line('could_not_delete_member'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $mdata['member_id'] . ' from email mailing list members table');
			
			return false;
		}
		
		//delete from members groups
		$this->db->where('member_id', $mdata['member_id']);		
			
		if ($this->db->delete('members_groups'))
		{
			//log success
			log_message('info', 'Member ID #' . $mdata['member_id'] . ' deleted from members groups table');				
		}
		else
		{
			show_error($this->lang->line('could_not_delete_member'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $mdata['member_id'] . ' from members groups table');
			
			return false;
		}
		
		//delete from members photos
		$this->db->where('member_id', $mdata['member_id']);
		$query = $this->db->get('members_photos');
		$member_data = $query->result_array();
		
		//delete photo files
		if (!empty($member_data[0]['photo_file_name']))
		{
			@unlink('./images/' . $this->config->item('images_members_dir') . '/' . $member_data[0]['photo_file_name']);
			
			//delete thumbs
			if ($this->config->item('sts_affiliate_image_auto_resize') == 1)
			{
				@unlink('./images/' . $this->config->item('images_members_dir') . '/' . $member_data[0]['raw_name'] . '_jrox' . $member_data[0]['file_ext']);
			}
		}
		
		$this->db->where('member_id', $mdata['member_id']);		
		
		if ($this->db->delete('members_photos'))
		{
			//log success
			log_message('info', 'Member ID #' . $mdata['member_id'] . ' deleted from members photos table');				
		}
		else
		{
			show_error($this->lang->line('could_not_delete_member'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $mdata['member_id'] . ' from members photos table');
			
			return false;
		}
		
		//delete from tracking_log
		$this->db->where('member_id', $mdata['member_id']);		
			
		if ($this->db->delete('tracking_log'))
		{
			//log success
			log_message('info', 'Member ID #' . $mdata['member_id'] . ' deleted from tracking_log table');				
		}
		else
		{
			show_error($this->lang->line('could_not_delete_member'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $mdata['member_id'] . ' from tracking_log table');
			
			return false;
		}
		
		//delete from traffic
		$this->db->where('member_id', $mdata['member_id']);		
			
		if ($this->db->delete('traffic'))
		{
			//log success
			log_message('info', 'Member ID #' . $mdata['member_id'] . ' deleted from traffic table');				
		}
		else
		{
			show_error($this->lang->line('could_not_delete_member'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $mdata['member_id'] . ' from traffic table');
			
			return false;
		}
		
		//delete from members table
		$this->db->where('member_id', $mdata['member_id']);		
			
		if ($this->db->delete('members'))
		{
			//log success
			log_message('info', 'Member ID #' . $mdata['member_id'] . ' deleted from members table');
	
			return true;	
		}
		else
		{
			show_error($this->lang->line('could_not_delete_member'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $mdata['member_id'] . ' from members table');
		}
		
		return false;
	}
	
	
	// ------------------------------------------------------------------------	
	
	function _get_members($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $where_column = '', $where_value = '')
	{
		//get all the members from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_mm_order');
		if (!$sort_column) $sort_column =  $this->config->item('dbs_mm_column');

		if ($sort_column != 'comms') $sort_column = $this->db->dbprefix('members') . '.' . $sort_column;	
		
		if (!empty($where_column) && $where_column == 'affiliate_group')
		{
			$sql = 'SELECT ' . $this->db->dbprefix('members') . '.*,
				   ' . $this->db->dbprefix('members_photos') . '.*, ' . $this->db->dbprefix('members') . '.member_id as mid,
				   (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as comms
					FROM  ' . $this->db->dbprefix('members') . '
					LEFT JOIN ' . $this->db->dbprefix('members_groups') . '  
					ON ' . $this->db->dbprefix('members') . '.member_id = ' . $this->db->dbprefix('members_groups') . '.member_id 
					LEFT JOIN ' . $this->db->dbprefix('members_photos') . '  
					ON ' . $this->db->dbprefix('members') . '.member_id = ' . $this->db->dbprefix('members_photos') . '.member_id
					WHERE group_id = \'' . $where_value .'\'
					GROUP BY ' . $this->db->dbprefix('members') . '.member_id 
					ORDER BY '  . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;

			$query = $this->db->query($sql);
			//echo $this->db->last_query(); exit();
		}
		elseif (!empty($where_column) && $where_column == 'search')
		{
			
			$sql = 'SELECT ' . $this->db->dbprefix('members') . '.*,
				   ' . $this->db->dbprefix('members_photos') . '.*, ' . $this->db->dbprefix('members') . '.member_id as mid,
				   (SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
					WHERE ' . $this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as comms
					FROM  ' . $this->db->dbprefix('members') . '
					LEFT JOIN ' . $this->db->dbprefix('members_groups') . '  
					ON ' . $this->db->dbprefix('members') . '.member_id = ' . $this->db->dbprefix('members_groups') . '.member_id 
					LEFT JOIN ' . $this->db->dbprefix('members_photos') . '  
					ON ' . $this->db->dbprefix('members') . '.member_id = ' . $this->db->dbprefix('members_photos') . '.member_id
					WHERE fname LIKE \'%' . $where_value . '%\'
					OR lname LIKE \'%' . $where_value . '%\'
					OR company LIKE \'%' . $where_value . '%\'
					OR username LIKE \'%' . $where_value . '%\'
					OR primary_email LIKE \'%' . $where_value . '%\'
					GROUP BY ' . $this->db->dbprefix('members') . '.member_id 
					ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;

			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0)
			{
				$a = array();
				foreach ($query->result_array() as $b)
				{
					array_push($a, $b);
				}
				
				
				return $a;
			}
		}
		else
		{ 
			$sql = 'SELECT ' . $this->db->dbprefix('members') . '.*, ' . $this->db->dbprefix('members_photos') . '.*, 
				' . $this->db->dbprefix('members') . '.member_id as mid,
				(SELECT SUM(commission_amount) from ' . $this->db->dbprefix('commissions') . ' 
				WHERE ' . $this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as comms
				FROM  ' . $this->db->dbprefix('members') . '
				LEFT JOIN ' . $this->db->dbprefix('members_photos') . '  
				ON ' . $this->db->dbprefix('members') . '.member_id = ' . $this->db->dbprefix('members_photos') . '.member_id';
				
			if (!empty($where_column) && !empty($where_value))
			{
				$sql .= ' WHERE ' . $where_column . ' = \'' . $where_value .'\'';
			}
			
			$sql .= ' ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;

			$query = $this->db->query($sql);

		}
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_member_mailing_lists($id = '', $format = false)
	{
		$this->db->where('member_id', $id);
		$query = $this->db->get('email_mailing_list_members');
		
		if ($query->num_rows() > 0)
		{
			if ($format == false)
			{
				return $query->result_array();
			}
			else
			{
				$srow = array();
				foreach ($query->result_array() as $row)
				{
					array_push($srow, $row['mailing_list_id']);
				}
				
				return $srow;
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_member_programs($id = '', $format = false)
	{
		$this->db->where('member_id', $id);
		$query = $this->db->get('members_programs');
		
		if ($query->num_rows() > 0)
		{
			if ($format == false)
			{
				return $query->result_array();
			}
			else
			{
				$srow = array();
				foreach ($query->result_array() as $row)
				{
					array_push($srow, $row['program_id']);
				}
				
				return $srow;
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_member_groups($id = '')
	{
		$this->db->where('member_id', $id);
		$query = $this->db->get('members_groups');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _load_member_photo($m = array())
	{
		$img = 	 base_url() . 'images/misc/mem_image.png';
		
		if (!empty($m['facebook_id']))
		{
			$img = _check_ssl() . 'graph.facebook.com/' . $m['facebook_id'] . '/picture';
		}
		 
		if (!empty($m['photo_file_name']))
		{
			if (file_exists(PUBPATH . '/images/members/' . $m['raw_name'] . '_jrox' . $m['file_ext']))
			{
				$img = base_url() . 'images/members/' . $m['raw_name'] . '_jrox' .$m['file_ext'];
			}
			elseif (file_exists(PUBPATH . '/images/members/' . $m['raw_name'] . $m['file_ext']))
			{
				$img = base_url() . 'images/members/' . $m['raw_name'] . $m['file_ext'];
			}
		}
		
		return '<img src="' . $img . '" style="max-height: 45px; max-width: 45px; margin: 0 3px;"/>';
	}

	// ------------------------------------------------------------------------	
	
	function _delete_member_photo($file_name = '', $mid = '')
	{
		$this->db->where('member_id', $mid);
		$this->db->where('raw_name', $file_name);
		
		if ($this->db->delete('members_photos'))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_member_photos($id = '')
	{
		$this->db->where('member_id', $id);
		$query = $this->db->get('members_photos');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_member_basic($id = '')
	{
		$this->db->where('member_id', $id);
		$this->db->limit('1');
		$query = $this->db->get('members');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_member_details($id = '')
	{
		//get the data from members table first
		$sql = 'SELECT *,
				(SELECT country_name FROM ' . $this->db->dbprefix('countries') . ' 
				WHERE ' . $this->db->dbprefix('countries') . '.country_id =  ' . $this->db->dbprefix('members') . '.billing_country)
				AS billing_country_name,
				(SELECT country_name FROM ' . $this->db->dbprefix('countries') . ' 
				WHERE ' . $this->db->dbprefix('countries') . '.country_id =  ' . $this->db->dbprefix('members') . '.payment_country)
				AS payment_country_name
		 		FROM ' . $this->db->dbprefix('members') . ' WHERE member_id = \'' . $id .'\' LIMIT 1';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$x['member_data'] = $query->row_array();
		}
		else
		{
			return false;
		}
		
		//get photos
		$x['photos'] = $this->_get_member_photos($id);
		
		//get mailing lists
		$x['mailing_lists'] = $this->_get_member_mailing_lists($id);
		
		//get programs
		$x['programs'] = $this->_get_member_programs($id);
		
		//get groups
		$groups = $this->_get_member_groups($id);
		
		//arrays for groups
		$a = array(); $b = array();
		
		if (!empty($groups))
		{
			foreach($groups as $k => $v)
			{
				//add to affiliate groups
				array_push($a, $k);
				array_push($b, $v);
			}
		}
		//put everything in one array
		if (!empty($a) AND !empty($b))
		{
			$x['affiliate_groups'] = combine_array($a, $b); //affiliate groups array
		}
		else
		{
			$x['affiliate_groups'] = array();
		}
				
		//check for sponsor
		if (!empty($x['member_data']['sponsor_id']))
		{	
			$sponsor_array = $this->db_validation_model->_get_details('members', 'username', 'member_id', $x['member_data']['sponsor_id']);
			$x['member_data']['sponsor'] = $sponsor_array[0]['username'];
		}
		
		return $x;
	}	
	
	// ------------------------------------------------------------------------	
		
	function _update_member($id = '', $admin = false)
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
	
		//set update date and by
		$data['updated_by'] = $this->session->userdata('username');
		$data['updated_on'] = _generate_timestamp();
		
		//filter affiliate groups
		if (!empty($data['affiliate_groups']))
		{
			$sdata['ag'] = array('group_id' => $data['affiliate_groups']); //affiliate groups array
			
		}
		unset($data['affiliate_groups']);
		//filter sponsor
		if (!empty($data['sponsor'])) 
		{
			$sponsor_data = $this->db_validation_model->_get_details('members', 'member_id', 'username', $data['sponsor']);
			
			$data['sponsor_id'] = !empty($sponsor_data) ? $sponsor_data[0]['member_id'] : 0;
		}
		else
		{
			if ($admin == true)
			{
				$data['sponsor_id'] = '0';	
			}
		}
		
		unset($data['sponsor']);
		
		//filter out mailing lists	
		if (!empty($data['email_mailing_lists']))
		{
			$sdata['ml'] = $data['email_mailing_lists'];
			unset($data['email_mailing_lists']);
		}
		
		//filter out programs
		if (!empty($data['programs']))
		{
			$sdata['mp'] = $data['programs'];
			unset($data['programs']);
		}
	
		//set the filter for the first, last  and payment name
		$data['fname'] = str_replace('|', ' ', $data['fname']);
		$data['lname'] = !empty($data['lname']) ? str_replace('|', ' ', $data['lname']) : '';
		$data['payment_name'] = !empty($data['payment_name']) ? str_replace('|', ' ', $data['payment_name']) : '';
		
		//update into db
		$this->db->where('member_id', $id);
		
		if (!$this->db->update('members', $data))
		{
			show_error($this->lang->line('could_not_update_member'));
			
			//log error
			log_message('error', 'Could not update member ' . $id . 'in members table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'Member ID '. $id . ' updated in members table');
		}
		
		if ($admin == true)
		{
			//update affiliate groups
			if (!empty($sdata['ag']))
			{
				//first check if the user is already in the members_groups table
				$this->db->where('member_id', $id);
				$check = $this->db->get('members_groups');
				
				$sdata['ag']['member_id'] = $id;
				
				if ($check->num_rows() > 0)
				{
					$this->db->where('member_id', $id);
				
					if (!$this->db->update('members_groups', $sdata['ag']))
					{
						show_error($this->lang->line('could_not_update_member'));
						
						//log error
						log_message('error', 'Could not update affiliate group for member ID ' . $id . ' in affiliate_groups table');
					}
					else
					{
						//log success
						log_message('info', 'Member ID '. $id . ' updated in affiliate_groups table');
					}
				}
				else
				{
					if (!$this->db->insert('members_groups', $sdata['ag']))
					{
						show_error($this->lang->line('could_not_add_member'));
						
						//log error
						log_message('error', 'Could not add affiliate group for member ID ' . $id . ' in affiliate_groups table');
					}
					else
					{
						//log success
						log_message('info', 'Member ID '. $id . ' added in affiliate_groups table');
					}
				}
			}
			else
			{
				$this->db->where('member_id', $id);
				$this->db->delete('members_groups');
			}
			
			//add to mailing lists
	
			//first get all the lists this user belongs to
			$this->db->where('member_id', $id);
			$query = $this->db->get('email_mailing_list_members');
			
			$lists = array();
			
			if ($query->num_rows() > 0)
			{
				$mlists = $query->result_array();
				
				$lists = format_array($mlists, 'mailing_list_id');		
				
			}	
			//first delete the lists required
			if (!empty($sdata['ml']))
			{
				if (!empty($lists))
				{
					foreach ($lists as $v)
					{
						if (in_array($v, $sdata['ml']))
						{	
							continue;
						}
						else
						{
							//delete the list
							$this->db->where('member_id', $id);
							$this->db->where('mailing_list_id', $v);
							$this->db->delete('email_mailing_list_members');
						}
					}
				}
				
				//now add rows into table
				foreach ($sdata['ml'] as $value)
				{			
					if (in_array($value, $lists)) //check if the list is already in the db
					{
						continue;
					}
					else //add the list to the db
					{
						$d = array('mailing_list_id' => $value,
								   'member_id' => $id,
								   'sequence_id' => 1,
								   'send_date'	=>	_generate_timestamp());
						
						if (!$this->db->insert('email_mailing_list_members', $d))
						{
							show_error($this->lang->line('could_not_add_member'));
						
							//log error
							log_message('error', 'Could not update mailing_lists for member ID ' . $id . ' in email_mailing_list_members table');
						}
						else
						{
							//log success
							log_message('info', 'Member ID '. $id . ' updated in email_mailing_list_members table');
						}
					}
				}
			}	
			else
			{
				$this->db->where('member_id', $id);
				$this->db->delete('email_mailing_list_members');
			}
			
			if ($this->config->item('sts_site_showcase_multiple_programs') == '0')
			{
				//first delete all programs user belongs to
				$this->db->where('member_id', $id);
				$this->db->delete('members_programs');
					
				//now add the programs
				if (!empty($sdata['mp']))
				{
					foreach ($sdata['mp'] as $value)
					{
						$d = array('program_id' => $value,
								   'member_id' => $id);
						
						if (!$this->db->insert('members_programs', $d))
						{
							show_error($this->lang->line('could_not_add_member'));
						
							//log error
							log_message('error', 'Could not update programs for member ID ' . $id . ' in members_programs table');
						}
						else
						{
							//log success
							log_message('info', 'Member ID '. $id . ' updated in members_programs table');
						}
					}
				}
			}
		}
		
		//prepare member info array to be sent back
		$data['member_id'] = $id;
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_member_password($pass = array())
	{
		$data = $this->db_validation_model->_clean_data($pass);
		
		$this->db->where('member_id', $pass['member_id']);
		if ($this->db->update('members', $data))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_status($id = '')
	{	
		$this->db->where('member_id', $id);
		$query = $this->db->get('members');
		
		$row = $query->result_array();
		
		$data = array('status' => $row[0]['status'] == '1' ? '0' : '1');
		
		if ($data['status'] == '1')
		{
			$data['login_status'] = '0';	
		}
	
		$this->db->where('member_id', $id);
		
		if ($this->db->update('members', $data))
		{
			
			//log success
			log_message('info', 'Status Changed for member ID# ' . $id);
			
			if ($data['status'] == 1)
			{
				return $row[0];
			}
			else
			{
				return 'inactive';
			}
		}
		
		show_error($this->lang->line('could_not_update_member'));
			
		//log error
		log_message('error', 'Could not update member ID #' . $id . ' in members table');
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
}
?>