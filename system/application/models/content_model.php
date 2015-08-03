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
| FILENAME - content_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing content
|
*/

class Content_Model extends CI_Model {	
	
	function _get_homepage()
	{
		$this->db->where('article_id', '1');
		$query = $this->db->get('content_articles');
			
		$row = $query->row_array();
			
		if ($row['content_type'] == '3')
		{
			$this->load->helper('file');
				
			$row['content_body'] = read_file($row['content_path']);
		}
		else
		{
			$row['content_body'] = html_entity_decode($row['content_body']);
		}
		
		$row['content_title_url'] = url_title($row['content_title']);

		return $row;
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _change_sort_order()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		foreach ($data as $k => $v)
		{
			if (strstr($k, "article_id") == true) 
			{
				$id = explode("-", $k);
			
				$this->db->where('article_id', $id[1]);
			
				//update member in db
					
				$data = array('sort_order' => $v);
				
				if (!$this->db->update('content_articles', $data))
				{
					show_error($this->lang->line('could_not_update_content_articles'));
					
					//log error
					log_message('error', 'Could not update content article #' . $k . ' in content articles table');
					return false;
				}
			}
		}
		
		//make sure tiers are numbered sequentially
		$this->db_validation_model->_db_sort_order('content_articles', 'article_id', 'sort_order'); 
		
		return true;		
	}
	
	// ------------------------------------------------------------------------
	
	function _add_content_article($post = '')
	{
		$aff_groups = !empty($post['content_permissions']) ? $post['content_permissions'] : '';

		unset($post['content_permissions']);
		
		$data = $this->db_validation_model->_clean_data($post);
				
		if (empty($data['program_id'])) { $data['program_id'] = '1'; }
		
		$data['sort_order'] = $this->db->count_all('content_articles') + 1;
		
		$data['date_published'] = _save_date($data['date_published'], false, true);
		
		//add modified by
		$data['modified_by'] = $this->session->userdata('fname');
		
		if ($this->config->item('content_enable_javascript_code') == true)
		{
			$data['content_body'] = $_POST['content_body'];
		}
		
		//check for content filter
		$data['content_body'] = _content_filter($data['content_body']);
		
		//insert into db
		if (!$this->db->insert('content_articles', $data))
		{
			show_error($this->lang->line('could_not_add_content'));
			
			//log error
			log_message('error', 'Could not insert content into content table');
			
			return false;
		}
		else
		{
			$data['article_id'] = $this->db->insert_id();
			
			//log success
			log_message('info', 'content '. $data['article_id'] . ' inserted into content table');
		}
		
		//check if we are adding content permissions
		if ($data['enable_affiliate_group_permissions'] == '1')
		{
			//then add
			if (!empty($aff_groups))
			{
				foreach ($aff_groups as $k => $v)
				{
					$data = array(	'article_id'	=>	$data['article_id'],
									'group_id'	=>	$v,
									);
				
					if ($this->db->insert('content_permissions', $data))
					{
						//log success
						log_message('info', 'article ID '. $data['article_id'] . ' added to content permissions table');
					}
					else
					{
						//log error
						log_message('error', 'Could not add article ID ' . $data['article_id'] . ' in  content permissions table');
					}
				}
			}	
		}
		
		return $data;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_content_article($id = '')
	{

		
		//delete content
		$this->db->where('article_id', $id);
		if ($this->db->delete('content_articles'))
		{
			
			//log success
			log_message('info', 'content ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_content'));
			
			//log error
			log_message('error', 'content ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	

	// ------------------------------------------------------------------------

	function _get_content_article_details($id = '')
	{
		//get the data from content table
		$this->db->where('article_id', $id);
		//$this->db->join('content_categories', 'content_articles.category_id=content_categories.category_id', 'left');
		$query = $this->db->get('content_articles');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _get_article_details($id = '')
	{
		//get the data from content table
		$this->db->where('article_id', $id);
		
		if (!$this->session->userdata('adminid'))
		{
			$this->db->where('date_published <=', _generate_timestamp());
		}
		
		$this->db->where('status', '1');
		
		$query = $this->db->get('content_articles');
	
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
		
			$row['content_body'] = str_replace('{more}', '', $row['content_body']);
			
			return $row;
		}
		else
		{
			return false;
		}	
	}
	
	// ------------------------------------------------------------------------
	
	function _get_affiliate_groups($id = '')
	{
		$this->db->where('article_id', $id);
		$query = $this->db->get('content_permissions');
		
		if ($query->num_rows() > 0)
		{
			$aff = $query->result_array();
			$aff_groups = format_array($aff, 'group_id');
			return $aff_groups;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_dashboard_content($id = '')
	{
		if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
		{
			$this->db->where('program_id', $id);
		}
		
		$this->db->where('date_published <=', _generate_timestamp());
		$this->db->where('status', '1');
		$this->db->order_by($this->config->item('pub_dbs_crt_column'), $this->config->item('pub_dbs_crt_order'));
		$this->db->limit($this->config->item('sts_content_members_dashboard_num_articles'));			
					
		$query = $this->db->get('content_articles');
		
		if ($query->num_rows() > 0)
		{
			$a = array();
			
			foreach ($query->result_array() as $row)
			{
				
				if ($row['enable_affiliate_group_permissions'] == 1)
				{
					$show_content = false;
					$row['show_comments'] = 0;
					//check for membership cookie
					if ($this->session->userdata('m_affiliate_group'))
					{
						//get affiliate groups
						$groups = $this->_get_affiliate_groups($row['article_id']);
						
						if (!empty($groups))
						{
							foreach ($groups as $g)
							{
								if ($this->session->userdata('m_affiliate_group') == $g['group_id']) 
								{ 
									$show_content = true;
									$row['show_comments'] = 1;
								}
							}
						}
					}
					
					if ($show_content == false)
					{
						continue;
						//$row['content_body'] = '<div class="jroxLoginRequired MembershipAccessRequired">' . $this->lang->line('group_membership_required_to_view') . '</div>';
					}	
				}
				
				//get the preview text
				$body = explode('{more}', $row['content_body']); 
				
				$row['content_body'] = $this->init->_parse_member_data(html_entity_decode($body[0], ENT_QUOTES, $this->config->item('charset')));
				
				$row['content_title_url'] = url_title($row['content_title']);
				$row['content_date'] = _show_date($row['date_published']);
				
				array_push($a, $row);
			
			}
			
			return $a;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_members_content($id = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('mem_dbs_cnt_order');
		if (!$sort_column) $sort_column = $this->config->item('mem_dbs_cnt_column');
		
		if ($this->session->userdata('adminid'))
		{
			if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
			{
				$this->db->where('program_id', $id);
			}
			$this->db->order_by($this->config->item('mem_dbs_cnt_column'), $this->config->item('mem_dbs_cnt_order'));
			$this->db->limit($limit, $offset);			

		}
		else
		{	
			if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
			{
				$this->db->where('program_id', $id);
			}
			$this->db->where('date_published <=', _generate_timestamp());
			$this->db->where('status', '1');
			$this->db->order_by($this->config->item('mem_dbs_cnt_column'), $this->config->item('mem_dbs_cnt_order'));
			$this->db->limit($limit, $offset);			
		}
			
		$query = $this->db->get('content_articles');
		
		if ($query->num_rows() > 0)
		{
		
			$a['articles'] = array();
			
			foreach ($query->result_array() as $row)
			{
				
				$row['content_title_url'] = url_title($row['content_title']);
				
				//get the preview text
				$body = explode('{more}', $row['content_body']);
				
				$row['content_body'] = $this->init->_parse_member_data(html_entity_decode($body[0], ENT_QUOTES, $this->config->item('charset')));
				
				if ($row['enable_affiliate_group_permissions'] == 1)
				{
					$show_content = false;
					$row['show_comments'] = 0;
					//check for membership cookie
					if ($this->session->userdata('m_affiliate_group'))
					{
						//get affiliate groups
						$groups = $this->_get_affiliate_groups($row['article_id']);
						
						if (!empty($groups))
						{
							foreach ($groups as $g)
							{
								if ($this->session->userdata('m_affiliate_group') == $g['group_id']) 
								{ 
									$show_content = true;
									$row['show_comments'] = 1;
								}
							}
						}
					}
					
					if ($show_content == false)
					{
						continue;
					}	
				}
				
				$row['content_date'] = _show_date($row['date_published']);
				
				$row['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				
				array_push($a['articles'], $row);
			}
			
			//get total rows
			if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
			{
				$this->db->where('program_id', $id);
			}
			$this->db->where('date_published <=', _generate_timestamp());
			$this->db->where('status', '1');
			$this->db->from('content_articles');
			
			$a['total_rows'] = $this->db->count_all_results();
			
			return $a;
		}
		
		return false;
		
	}
	
	// ------------------------------------------------------------------------
	
	function _mass_update_articles($array = '', $cat = '')
	{
		$arg = explode('-', $cat);
		
		foreach ($array as $v)
		{
			switch ($arg[0])
			{
				case 'add_category':
				
					$update = array('category_id' => $arg[1]);
					
					$this->db->where('article_id', $v);
					$query = $this->db->update('content_articles', $update);
					
				break;
				
				case 'add_affiliate_group':
				
					//first delete
					$this->db->where('article_id', $v);
					$this->db->where('group_id', $arg[1]);
					$this->db->delete('content_permissions');
			
					$data = array(	'article_id'	=>	$v,
									'group_id'	=>	$arg[1],
									);
				
					$this->db->insert('content_permissions', $data);
				
					$this->db->where('article_id', $v);
					$query = $this->db->update('content_articles', array('enable_affiliate_group_permissions' => '1'));

				break;
				
				case 'add_membership':
					
					$update = array('require_membership' => '1',
									'membership_id' => $arg[1]
									);
					
					$this->db->where('article_id', $v);
					$query = $this->db->update('content_articles', $update);
					
				break;
				
				case 'remove_memberships':
					
					$update = array('require_membership' => '0',
									'membership_id' => '0',
									);
					
					$this->db->where('article_id', $v);
					$query = $this->db->update('content_articles', $update);
					
				break;
				
				case 'remove_affiliate_groups':
				
					//first delete
					$this->db->where('article_id', $v);
					$this->db->delete('content_permissions');
					
				break;
			}
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_content_articles($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_crt_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_crt_column');
		
		$sql = 'SELECT ' . $this->db->dbprefix('content_articles') . '.*
				FROM ' . $this->db->dbprefix('content_articles') . '
				ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_site_articles($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $type = '', $value = '', $cat_type = 'custom', $search = false)
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_crt_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_crt_column');
		
		if ($search == true)
		{
			$sql = 'SELECT ' . $this->db->dbprefix('content_articles') . '.*, ' . $this->db->dbprefix('content_categories') . '.*,
					(SELECT COUNT(*) FROM ' . $this->db->dbprefix('content_comments') . ' 
					WHERE ' . $this->db->dbprefix('content_articles') . '.article_id = ' . $this->db->dbprefix('content_comments') . '.article_id 
					AND ' . $this->db->dbprefix('content_comments') . '.status = \'1\') as comments
					FROM ' . $this->db->dbprefix('content_articles') . '
					LEFT JOIN ' . $this->db->dbprefix('content_categories') . ' 
					ON ' . $this->db->dbprefix('content_articles') . '.category_id = ' . $this->db->dbprefix('content_categories') . '.category_id
					WHERE '. $this->db->dbprefix('content_articles') . '.status = \'1\' 
					AND ' . $this->db->dbprefix('content_categories') . '.category_type = \'' . $cat_type . '\'
					AND date_published <=' . _generate_timestamp() . ' 
					AND ' . $this->db->dbprefix('content_articles') . '.content_type = \'1\' 
					AND (' . $this->db->dbprefix('content_articles') . '.content_body LIKE \'%' . xss_clean(addslashes($value)) . '%\'
					OR ' . $this->db->dbprefix('content_articles') . '.content_title LIKE \'%' . xss_clean(addslashes($value)) . '%\'
					OR ' . $this->db->dbprefix('content_articles') . '.content_tags LIKE \'%' . xss_clean(addslashes($value)) . '%\')
					GROUP BY ' . $this->db->dbprefix('content_articles') . '.article_id
					ORDER BY ' . $this->db->dbprefix('content_articles') . '.' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
			
		}
		elseif ($value == 1)
		{
			$sql = 'SELECT ' . $this->db->dbprefix('content_articles') . '.*, ' . $this->db->dbprefix('content_categories') . '.*,
					(SELECT COUNT(*) FROM ' . $this->db->dbprefix('content_comments') . ' 
					WHERE ' . $this->db->dbprefix('content_articles') . '.article_id = ' . $this->db->dbprefix('content_comments') . '.article_id
					AND ' . $this->db->dbprefix('content_comments') . '.status = \'1\') as comments
					FROM ' . $this->db->dbprefix('content_articles') . '
					LEFT JOIN ' . $this->db->dbprefix('content_categories') . ' 
					ON ' . $this->db->dbprefix('content_articles') . '.category_id = ' . $this->db->dbprefix('content_categories') . '.category_id

					WHERE ' . $this->db->dbprefix('content_categories') . '.category_type = \'' . $cat_type . '\'
					AND date_published <=' . _generate_timestamp() . '
					AND ' . $this->db->dbprefix('content_articles') . '.status = \'1\' 
					AND ' . $this->db->dbprefix('content_articles') . '.content_type = \'1\' 
					GROUP BY ' . $this->db->dbprefix('content_articles') . '.article_id
					ORDER BY ' . $this->db->dbprefix('content_articles') . '.' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		}
		else
		{
			$sql = 'SELECT ' . $this->db->dbprefix('content_articles') . '.*, ' . $this->db->dbprefix('content_categories') . '.*,
					(SELECT COUNT(*) FROM ' . $this->db->dbprefix('content_comments') . ' 
					WHERE ' . $this->db->dbprefix('content_articles') . '.article_id = ' . $this->db->dbprefix('content_comments') . '.article_id
					AND ' . $this->db->dbprefix('content_comments') . '.status = \'1\') as comments
					FROM ' . $this->db->dbprefix('content_articles') . '
					LEFT JOIN ' . $this->db->dbprefix('content_categories') . ' 
					ON ' . $this->db->dbprefix('content_articles') . '.category_id = ' . $this->db->dbprefix('content_categories') . '.category_id
					WHERE ' . $this->db->dbprefix('content_articles') . '.' . $type . ' = ' . $value . '
					AND ' . $this->db->dbprefix('content_categories') . '.category_type = \'' . $cat_type . '\'
					AND date_published <=' . _generate_timestamp() . '
					AND ' . $this->db->dbprefix('content_articles') . '.status = \'1\' 
					AND ' . $this->db->dbprefix('content_articles') . '.content_type = \'1\' 
					GROUP BY ' . $this->db->dbprefix('content_articles') . '.article_id
					ORDER BY ' . $this->db->dbprefix('content_articles') . '.' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		}
		
		
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{		
			if ($search == true)
			{
				$sql = 'SELECT ' . $this->db->dbprefix('content_articles') . '.*, ' . $this->db->dbprefix('content_categories') . '.*
						FROM ' . $this->db->dbprefix('content_articles') . '
						LEFT JOIN ' . $this->db->dbprefix('content_categories') . ' 
						ON ' . $this->db->dbprefix('content_articles') . '.category_id = ' . $this->db->dbprefix('content_categories') . '.category_id
						WHERE '. $this->db->dbprefix('content_articles') . '.status = \'1\' 
						AND ' . $this->db->dbprefix('content_categories') . '.category_type = \'' . $cat_type . '\'
						AND date_published <=' . _generate_timestamp() . ' 
						AND ' . $this->db->dbprefix('content_articles') . '.content_type = \'1\' 
						AND (' . $this->db->dbprefix('content_articles') . '.content_body LIKE \'%' . xss_clean(addslashes($value)) . '%\'
						OR ' . $this->db->dbprefix('content_articles') . '.content_title LIKE \'%' . xss_clean(addslashes($value)) . '%\'
						OR ' . $this->db->dbprefix('content_articles') . '.content_tags LIKE \'%' . xss_clean(addslashes($value)) . '%\')
						GROUP BY ' . $this->db->dbprefix('content_articles') . '.article_id';
			}
			elseif ($value == 1)
			{
			
				$sql = 'SELECT ' . $this->db->dbprefix('content_articles') . '.*, ' . $this->db->dbprefix('content_categories') . '.*
						FROM ' . $this->db->dbprefix('content_articles') . '
						LEFT JOIN ' . $this->db->dbprefix('content_categories') . ' 
						ON ' . $this->db->dbprefix('content_articles') . '.category_id = ' . $this->db->dbprefix('content_categories') . '.category_id

						WHERE ' . $this->db->dbprefix('content_categories') . '.category_type = \'' . $cat_type . '\'
						AND date_published <=' . _generate_timestamp() . '
						AND ' . $this->db->dbprefix('content_articles') . '.status = \'1\' 
						AND ' . $this->db->dbprefix('content_articles') . '.content_type = \'1\' 
						GROUP BY ' . $this->db->dbprefix('content_articles') . '.article_id';
			}
			else
			{
			
				$sql = 'SELECT ' . $this->db->dbprefix('content_articles') . '.*, ' . $this->db->dbprefix('content_categories') . '.*
						FROM ' . $this->db->dbprefix('content_articles') . '
						LEFT JOIN ' . $this->db->dbprefix('content_categories') . ' 
						ON ' . $this->db->dbprefix('content_articles') . '.category_id = ' . $this->db->dbprefix('content_categories') . '.category_id
						WHERE ' . $this->db->dbprefix('content_articles') . '.' . $type . ' = ' . $value . '
						AND ' . $this->db->dbprefix('content_categories') . '.category_type = \'' . $cat_type . '\'
						AND date_published <=' . _generate_timestamp() . '
						AND ' . $this->db->dbprefix('content_articles') . '.status = \'1\' 
						AND ' . $this->db->dbprefix('content_articles') . '.content_type = \'1\' 
						GROUP BY ' . $this->db->dbprefix('content_articles') . '.article_id';
			}
			
			$squery = $this->db->query($sql);
			
			$data['total_rows'] = $squery->num_rows();
			
			$data['rows'] = array();
			
			$this->load->helper('file');
			
			foreach ($query->result_array() as $row)
			{
				$row['content_title_url'] = url_title($row['content_title']);
				$row['content_date'] = _show_date($row['date_published']);
				$row['url_encoded'] = urlencode(_format_url(CONTENT_ROUTE .'/article', $row['article_id'], $row['content_title']));
				$row['publish_day'] = date('d', $row['date_published']);
				$row['publish_month'] = date('M', $row['date_published']);
				$row['publish_year'] = date('Y', $row['date_published']);
				
				if ($row['content_type'] == '3')
				{
					$row['content_body'] = read_file($row['content_path']);
				}
				
				//get the preview text
				$body = explode('{more}', $row['content_body']);
				
				$row['content_body'] = html_entity_decode($body[0]);
				$row['membership_required'] = 0;
				$row['show_comments'] = 1;
				
				//check for affiliate group access
				if ($row['enable_affiliate_group_permissions'] == 1)
				{
					$show_content = false;
					$row['show_comments'] = 0;
					//check for membership cookie
					if ($this->session->userdata('m_affiliate_group'))
					{
						//get affiliate groups
						$groups = $this->_get_affiliate_groups($row['article_id']);
						
						if (!empty($groups))
						{
							foreach ($groups as $g)
							{
								if ($this->session->userdata('m_affiliate_group') == $g['group_id']) 
								{ 
									$show_content = true;
									$row['show_comments'] = 1;
								}
							}
						}
					}
					
					if ($show_content == false)
					{
						continue;
					}	
				}
				
				//check if content requires membership
				if ($row['require_membership'] == 1)
				{
					$show_content = false;
					$row['show_comments'] = 0;
					//check for membership cookie
					if ($this->session->userdata('m_memberships'))
					{
						foreach ($this->session->userdata('m_memberships') as $mem)
						{
							if ($mem['product_id'] == $row['membership_id']) 
							{ 
								$show_content = true;
								$row['show_comments'] = 1;
							}
						}
					}
					
					if ($show_content == false)
					{
						$row['content_body'] = '<div class="jroxLoginRequired MembershipAccessRequired">' . $this->lang->line('membership_required_to_view') . '</div>';
					}
				}
				
				
				
				
				
				array_push($data['rows'], $row);
			}
			
			return $data;
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	function _update_content_article($id = '', $array = '')
	{
		$aff_groups = !empty($_POST['content_permissions']) ? $_POST['content_permissions'] : '';
		unset($array['content_permissions']);

		//clean the data first
		$data = $this->db_validation_model->_clean_data($array);
		
		if (empty($data['program_id'])) { $data['program_id'] = '1'; }
		
		$data['date_published'] = _save_date($data['date_published'], false, true);
		
		//add modified by
		$data['modified_by'] = $this->session->userdata('fname');
		
		if ($this->config->item('content_enable_javascript_code') == true)
		{
			$data['content_body'] = $_POST['content_body'];
		}
		
		//check for content filter
		$data['content_body'] = _content_filter($data['content_body']);
	
		//update content data
		$this->db->where('article_id', $id);
		
		if (!$this->db->update('content_articles', $data))
		{
			show_error($this->lang->line('could_not_update_content'));
			
			//log error
			log_message('error', 'Could not update content ID ' . $id . 'in content table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'content ID '. $id . ' updated in content table');
		}
		
		$data['article_id'] = $id;
		
		//check if we are adding content permissions
		if ($data['enable_affiliate_group_permissions'] == '1')
		{
			//first delete
			$this->db->where('article_id', $id);
			$this->db->delete('content_permissions');
			
			//then add
			if (!empty($aff_groups))
			{
				foreach ($aff_groups as $k => $v)
				{
					$sdata = array(	'article_id'	=>	$data['article_id'],
									'group_id'	=>	$v,
									);
				
					if ($this->db->insert('content_permissions', $sdata))
					{
						//log success
						log_message('info', 'article ID '. $sdata['article_id'] . ' added to content permissions table');
					}
					else
					{
						//log error
						log_message('error', 'Could not add article ID ' . $sdata['article_id'] . ' in  content permissions table');
					}
				}
			}	
		}
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
}
?>