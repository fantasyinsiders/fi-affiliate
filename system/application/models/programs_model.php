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
| FILENAME - programs_model.php
| -------------------------------------------------------------------------     
|  
| This model handles the functions for managing the programs
|
*/

class Programs_Model extends CI_Model {	
	
	var $table = 'programs';
	
	// ------------------------------------------------------------------------
	
	function _get_program_basic($field = 'program_id', $id = '', $fields = '')
	{
		if (!empty($fields))
		{
			$this->db->select($fields);
		}
		
		$this->db->where($field, $id);
		$query = $this->db->get($this->table);
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_program_details($id = '')
	{
		//get the data from members table first
		$this->db->where('program_id', $id);
		$query = $this->db->get($this->table);
		
		if ($query->num_rows() > 0)
		{
			$x['program_data'] = $query->row_array();
		}
		else
		{
			return false;
		}
		
		//get photos
		$x['photos'] = $this->_get_program_photos($id);

		return $x;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_program_photos($id = '')
	{
		$this->db->where('program_id', $id);
		$query = $this->db->get('programs_photos');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _change_sort_order($post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);

		foreach ($data as $k => $v)
		{
			if (strstr($k, "program") == true) 
			{
				$id = explode("-", $k);
			
				$this->db->where('program_id', $id[1]);
					
				$data = array('sort_order' => $v);
				
				if (!$this->db->update($this->table, $data))
				{
					show_error($this->lang->line('could_not_update_programs'));
					
					//log error
					log_message('error', 'Could not update programs #' . $k . ' in programs table');
					return false;
				}
			}
		}
		
		//make sure tiers are numbered sequentially
		$this->db_validation_model->_db_sort_order($this->table, 'program_id', 'sort_order'); 
		
		return true;		
	}
	
	// ------------------------------------------------------------------------
	
	function _add_program()
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$data['terms_of_service'] = _content_filter($data['terms_of_service']);
		$data['program_description'] = _content_filter($data['program_description']);
		$data['privacy_policy'] = _content_filter($data['privacy_policy']);
		
		//set update date and by
		$data['modified_by'] = $this->session->userdata('username'); 
		$data['last_modified'] = _generate_timestamp();
		
		//set the cookie name
		$data['program_cookie_name'] = _generate_cookie_name();
		
		if (empty($data['signup_link']))
		{
			$data['signup_link'] = url_title($data['program_name'],  'underscore', TRUE);
		}
		else
		{
			$data['signup_link'] = url_title($data['signup_link'],  'underscore', TRUE);	
		}
		
		if (!$this->db->insert($this->table, $data))
		{
			show_error($this->lang->line('could_not_add_program'));
			
			//log error
			log_message('error', 'Could not add program ' . $id . 'in programs table');
			
			return false;
		}
		else
		{
			$data['id'] = $this->db->insert_id();
			
			//log success
			log_message('info', 'program ID '. $data['id'] . ' added in programs table');
			
			//add program form fields
			$sdata = array('program_id' =>  $data['id'],
						  'enable_fname' => '1',
						  'enable_primary_email' => '1'
						  );
			$this->db->insert('programs_form_fields', $sdata);
			
			return $data; 
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _get_program_name($id = '')
	{
		$this->db->select('program_name');
		$this->db->where('program_id', $id);
		$query = $this->db->get($this->table);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			return $row['program_name'];
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_program($id = '')
	{
		
		if ($id == '1') return;
		
		$this->db->where('program_id', $id);
		
		if (!$this->db->delete($this->table)) 
		{
			show_error($this->lang->line('could_not_delete_program'));
			
			//log error
			log_message('error', 'Could not delete program ' . $id . 'in programs table');
			
			return false;
		}
		else
		{
			//log success
			log_message('info', 'program ID '. $id . ' deleted in programs table');
			
			$this->db->where('program_id', $id);
			if ($this->db->delete('performance_rewards'))
			{
				//log success
				log_message('info', 'program ID '. $id . ' deleted in performance rewards table');	
			}
			
			$this->db->where('program_id', $id);
			if ($this->db->delete('programs_photos'))
			{
				//log success
				log_message('info', 'program ID '. $id . ' deleted in programs_photos table');	
			}

			
			$this->db->where('program_id', $id);
			if ($this->db->delete('programs_form_fields'))
			{
				//log success
				log_message('info', 'program ID '. $id . ' deleted in programs_form_fields table');	
			}
			
			$this->db->where('program_id', $id);
			if ($this->db->delete('email_templates'))
			{
				//log success
				log_message('info', 'program ID '. $id . ' deleted in email_templates table');	
			}
			
			$this->db->where('program_id', $id);
			if ($this->db->delete('layout_menus'))
			{
				//log success
				log_message('info', 'program ID '. $id . ' deleted in layout menus table');	
			}
			
			//article adds
			if ($this->db->table_exists('affiliate_article_ads'))
			{
				$this->db->where('program_id', $id);
				if ($this->db->delete('affiliate_article_ads'))
				{
					//log success
					log_message('info', 'program ID '. $id . ' deleted in affiliate_article_ads table');	
				}
			}
			
			if ($this->db->table_exists('affiliate_banners'))
			{
				//banners
				$this->db->where('program_id', $id);
				if ($this->db->delete('affiliate_banners'))
				{
					//log success
					log_message('info', 'program ID '. $id . ' deleted in banners table');	
				}
			}
			
			if ($this->db->table_exists('affiliate_email_ads'))
			{
				//email ads
				$this->db->where('program_id', $id);
				if ($this->db->delete('affiliate_email_ads'))
				{
					//log success
					log_message('info', 'program ID '. $id . ' deleted in affiliate_email_ads table');	
				}
			}
			
			if ($this->db->table_exists('affiliate_html_ads'))
			{	
				//html ads
				$this->db->where('program_id', $id);
				if ($this->db->delete('affiliate_html_ads'))
				{
					//log success
					log_message('info', 'program ID '. $id . ' deleted in affiliate_html_ads table');	
				}
			}
			
			if ($this->db->table_exists('affiliate_lightbox_ads'))
			{	
				//lightbox ads
				$this->db->where('program_id', $id);
				if ($this->db->delete('affiliate_lightbox_ads'))
				{
					//log success
					log_message('info', 'program ID '. $id . ' deleted in affiliate_lightbox_ads table');	
				}
			}
			
			if ($this->db->table_exists('affiliate_page_peel_ads'))
			{	
				//page peels
				$this->db->where('program_id', $id);
				if ($this->db->delete('affiliate_page_peel_ads'))
				{
					//log success
					log_message('info', 'program ID '. $id . ' deleted in affiliate_page_peel_ads table');	
				}
			}
			
			if ($this->db->table_exists('affiliate_text_ads'))
			{	
				//text ads
				$this->db->where('program_id', $id);
				if ($this->db->delete('affiliate_text_ads'))
				{
					//log success
					log_message('info', 'program ID '. $id . ' deleted in affiliate_text_ads table');	
				}
			}
			
			if ($this->db->table_exists('affiliate_text_links'))
			{	
				//text links
				$this->db->where('program_id', $id);
				if ($this->db->delete('affiliate_text_links'))
				{
					//log success
					log_message('info', 'program ID '. $id . ' deleted in affiliate_text_links table');	
				}
			}
			
			if ($this->db->table_exists('affiliate_viral_pdfs'))
			{	
				//viral pdfs
				$this->db->where('program_id', $id);
				if ($this->db->delete('affiliate_viral_pdfs'))
				{
					//log success
					log_message('info', 'program ID '. $id . ' deleted in affiliate_viral_pdfs table');	
				}
			}
			
			if ($this->db->table_exists('affiliate_viral_videos'))
			{	
				//viral videos
				$this->db->where('program_id', $id);
				if ($this->db->delete('affiliate_viral_videos'))
				{
					//log success
					log_message('info', 'program ID '. $id . ' deleted in affiliate_viral_videos table');	
				}
			}
			
			return true;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _update_program($id = '')
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$data['terms_of_service'] = _content_filter($data['terms_of_service']);
		$data['program_description'] = _content_filter($data['program_description']);
		$data['privacy_policy'] = _content_filter($data['privacy_policy']);
		
		//set update date and by
		$data['modified_by'] = $this->session->userdata('username');
		$data['last_modified'] = _generate_timestamp();
		$data['signup_link'] = !empty($data['signup_link']) ? url_title($data['signup_link'],  'underscore', TRUE) : _generate_random_string(5) . $id;
		
		$this->db->where('program_id', $id);
		if (!$this->db->update($this->table, $data)) 
		{
			show_error($this->lang->line('could_not_update_program'));
			
			//log error
			log_message('error', 'Could not update program ' . $id . 'in programs table');
			
			return false;
		}
		else
		{
			//log success
			log_message('info', 'program ID '. $id . ' updated in programs table');
		}
		
		return true;
	
	}
	
	// ------------------------------------------------------------------------
	
	function _get_all_programs($sort_column = '', $sort_order = '')
	{
		if (!$sort_order) $sort_order = $this->config->item('dbs_prg_order');
		if (!$sort_column) $sort_column = 'program_name';
		
		$this->db->order_by($sort_column, $sort_order);
		$query = $this->db->get($this->table);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_form_fields($id = '')
	{
		$this->db->where('program_id', $id);
		$query = $this->db->get('programs_form_fields');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_registered_programs($mid = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_prg_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_prg_column');
		
		$sql = 'SELECT *, ' . $this->db->dbprefix('members_programs') . '.program_id as pid
				FROM ' . $this->db->dbprefix('members_programs') . '
				LEFT JOIN ' . $this->db->dbprefix('programs') . '  
				ON ' . $this->db->dbprefix($this->table) . '.program_id = ' . $this->db->dbprefix('members_programs') . '.program_id
				LEFT JOIN ' . $this->db->dbprefix('affiliate_groups') . '  
				ON ' . $this->db->dbprefix($this->table) . '.group_id = ' . $this->db->dbprefix('affiliate_groups') . '.group_id
				LEFT JOIN ' . $this->db->dbprefix('programs_photos') . '  
				ON ' . $this->db->dbprefix($this->table) . '.program_id = ' . $this->db->dbprefix('programs_photos') . '.program_id 
				WHERE member_id = \'' .$mid . '\' AND confirm_id = \'\'';
		
		$sql .= ' ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;

		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$s['programs'] = array();
			foreach ($query->result_array() as $row)
			{
				//get the levels
				$row['program_id'] = $row['pid'];
				$row['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				$row['payout'] = '';
				for ($i=1; $i<=$row['commission_levels']; $i++)
				{
					if ($i == 6) $row['payout'] .= '<br />';
					$row['payout'] .= '<div class="programLevels">' . $this->lang->line('level') . ' ' . $i . ': <span class="margin-20">' . $row['commission_level_' . $i] . '</span></div>' ;
				}
				
				if (empty($row['photo_file_name']))
				{
					$row['program_photo'] = 'images/misc/default.jpg';
				}
				else
				{
					//get the program photo
					if ($row['image_resized'] == '1')
					{
						$row['program_photo'] = 'images/programs/' . $row['raw_name'] . '_jrox' . $row['file_ext'] ;	
					}
					else
					{
						$row['program_photo'] = 'images/programs/' . $row['photo_file_name'];
					}
				}
				
				array_push($s['programs'], $row);
			}
			
			//get totals			
			$this->db->where('member_id', $mid);
			$this->db->from('members_programs');
			$s['total_rows'] = $this->db->count_all_results();

			return $s;
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_user_programs($mid = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_prg_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_prg_column');

		$sql = 'SELECT ' . $this->db->dbprefix($this->table) . '.*, ' . $this->db->dbprefix($this->table) . '.program_id as pid, 
			   ' . $this->db->dbprefix('affiliate_groups') . '.* , ' . $this->db->dbprefix('programs_photos') . '.* 
				FROM ' . $this->db->dbprefix($this->table) . '
				
				LEFT JOIN ' . $this->db->dbprefix('affiliate_groups') . '  
				ON ' . $this->db->dbprefix($this->table) . '.group_id = ' . $this->db->dbprefix('affiliate_groups') . '.group_id
				LEFT JOIN ' . $this->db->dbprefix('programs_photos') . '  
				ON ' . $this->db->dbprefix($this->table) . '.program_id = ' . $this->db->dbprefix('programs_photos') . '.program_id 
				WHERE program_status = \'1\'';
				
		if ($this->session->userdata('m_view_hidden_programs') == 0)
		{
			$sql .= ' AND hidden_program = \'0\'';	
		}
		
		$sql .= ' ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;

		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$s['programs'] = array();
			foreach ($query->result_array() as $row)
			{
				//get the levels
				$row['program_id'] = $row['pid'];
				$row['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				$row['payout'] = '<ul class="list-inline">';
				for ($i=1; $i<=$row['commission_levels']; $i++)
				{
					if ($i == 6) $row['payout'] .= '<br /><br />';
					if ($row['commission_type'] == 'percent')
					{
						$amount = sprintf("%.2f%%",  $row['commission_level_' . $i] * 100);
					}
					else
					{
						$amount = format_amounts($row['commission_level_' . $i], $this->config->item('num_options'));
					}
					$row['payout'] .= '<li><span class="label label-default">' . $this->lang->line('level') . ' ' . $i . ': ' . $amount . '</span></li>' ;
				}
				
				if (empty($row['photo_file_name']))
				{
					$row['program_photo'] = 'images/misc/group.png';
				}
				else
				{
					$row['program_photo'] = 'images/programs/' . $row['photo_file_name'];
				}
				
				array_push($s['programs'], $row);
			}
			
			//get totals
			$this->db->where('program_status', '1');
			if ($this->session->userdata('m_view_hidden_programs') == 0)
			{
				$this->db->where('hidden_program', '0');
			}
			$this->db->from($this->table);
			$s['total_rows'] = $this->db->count_all_results();
			
			return $s;
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_programs($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_prg_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_prg_column');

		$sql = 'SELECT ' . $this->db->dbprefix($this->table) . '.*, ' . $this->db->dbprefix($this->table) . '.program_id as pid, 
			   ' . $this->db->dbprefix('affiliate_groups') . '.* , ' . $this->db->dbprefix('programs_photos') . '.* 
				FROM ' . $this->db->dbprefix($this->table) . '
				LEFT JOIN ' . $this->db->dbprefix('affiliate_groups') . '  
				ON ' . $this->db->dbprefix($this->table) . '.group_id = ' . $this->db->dbprefix('affiliate_groups') . '.group_id
				LEFT JOIN ' . $this->db->dbprefix('programs_photos') . '  
				ON ' . $this->db->dbprefix($this->table) . '.program_id = ' . $this->db->dbprefix('programs_photos') . '.program_id 
				ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;

		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			//echo '<pre>'; print_r($query->result_array()); exit();
			$s = array();
			foreach ($query->result_array() as $row)
			{
				//get the levels
				$row['program_id'] = $row['pid'];
				
				$row['payout'] = '';
				for ($i=1; $i<=$row['commission_levels']; $i++)
				{
					if ($i == 6) $row['payout'] .= '<br />';
					if ($row['commission_type'] == 'percent')
					{
						$amount = sprintf("%.2f%%",  $row['commission_level_' . $i] * 100);
					}
					else
					{
						$amount = format_amounts($row['commission_level_' . $i], $this->config->item('num_options'));
					}
					
					$row['payout'] .= '<small class="text-muted programLevels">' . $this->lang->line('level') . ' ' . $i . ': ' . $amount . '</small>' ;
				}
				
				if (empty($row['photo_file_name']))
				{
					$row['program_photo'] = 'themes/admin/' . $this->config->item('sts_admin_layout_theme') . '/img/offers.png';
				}
				else
				{
					$row['program_photo'] = 'images/programs/' . $row['photo_file_name'];
				}
				
				array_push($s, $row);
			}

			return $s;
		}
		 
		return  false;
	}
}

?>