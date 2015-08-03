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
| FILENAME: uploads_model.php
| -------------------------------------------------------------------------     
| 
| This file runs the image and upload functions
|
*/


class Uploads_Model extends CI_Model {
	
	function _insert_image_db($data, $table = '')
	{
		//insert image into the photo / images table
		$this->db->insert($table, $data); 
		
		return true;
	}
		
	// ------------------------------------------------------------------------
	
	function _update_image_db($data)
	{
		$this->db->where($data['config']['key'], $data['config']['value']);
		
		if ($this->db->update($data['config']['table'], $data['fields']))
		{
			//log success 
			log_message('info', 'photo for id #' . $data['config']['value'] . ' updated');
			return true;
		}
		
		//log error
		log_message('error', 'Could not update photo for id #' . $data['config']['value'] . ' in ' . $type . ' table');
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_photo($type = '', $data = '')
	{
		//get the photo details first
		$photo_data = $this->db_validation_model->_get_details($data['config']['table'], '', $data['config']['key'], $data['config']['value']);
		
		//delete the image file
		$image_path = $this->config->item('images_' . $type . '_dir');
		
		if (@unlink('./images/' . $image_path . '/' . $photo_data[0]['photo_file_name']))
		{
		
			//check if there is a resized image
			if ($photo_data[0]['image_resized'] == 1)
			{
				if (@unlink('./images/' . $image_path . '/' . $photo_data[0]['raw_name'] . '_jrox' . $photo_data[0]['file_ext']))
				{
					log_message('info', 'resized photo for ' . $type . ' id #' .$data['config']['id'] . ' deleted');
				}
			}
			
			//log success
			log_message('info', 'photo for ' . $type . ' id #' .$data['config']['id'] . ' deleted');
	        
			//set the SQL WHERE clause
			$this->db->where($data['config']['key'], $data['config']['value']);
			
			//use if you will be setting the data row for the photo to be blank
			if ($data['config']['type'] == 'update')
			{	
				//update the database info for image			
				if ($this->db->update($data['config']['table'], $data['fields']))
				{	
					//log success
					log_message('info', 'photo for admin id #' .$data['config']['id'] . ' updated');
					return true;
				}
			}
			else
			{
				//use if you will be deleting the photo from the table
				if ($this->db->delete($data['config']['table']))
				{
					//log success
					log_message('info', 'photo for ' . $type . ' id #' .$data['config']['id'] . ' deleted');
					return true;
				}
			}
		}

		//log error
		log_message('error', 'Could not delete photo for ' . $type . ' id #' . $data['config']['id'] . ' in ' . $type . ' table');
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _upload_file($userfile = 'userfile', $sconfig = '')
	{
		if (!empty($sconfig))
		{
			$config = $sconfig;
		}
		else
		{
			$config['upload_path'] = $this->config->item('sts_support_upload_folder_path');
			$config['allowed_types'] = $this->config->item('sts_support_upload_download_types');
			$config['max_size']	= $this->config->item('sts_support_max_upload_size');
			$config['encrypt_name'] = false;
			$config['remove_spaces'] = true;
		}
		
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($userfile))
		{
			show_error($error = array('error' => $this->upload->display_errors()));

			//log error
			log_message('error', 'Could not upload file');
			
			$data['msg'] = $this->upload->display_errors();
			$data['success'] = false;
		
		}
		else
		{
			$data['info'] = $this->upload->data();
			//return the array
			$data['success'] = true;
		}
		
		return $data;
	}
	
	// ------------------------------------------------------------------------
	
	function _upload_photo($type = '', $resize = '0', $userfile = 'userfile', $file_types = '')
	{
		switch ($type)
		{
			case 'admin':
				
				$config['upload_path'] = './images/' . $this->config->item('images_admins_dir') . '/';
				
			break;
			
			case 'banners':
			
				$config['upload_path'] = './images/' . $this->config->item('images_banners_dir') . '/';
				
			break;
			
			case 'members':
			
				$config['upload_path'] = './images/' . $this->config->item('images_members_dir') . '/';
				
			break;
			
			case 'programs':
			
				$config['upload_path'] = './images/' . $this->config->item('images_programs_dir') . '/';
				
			break;
			
			default:
				
				$config['upload_path'] = './images/' . $this->config->item('images_products_dir') . '/';
				
			break;
			
		}
		
		$config['allowed_types'] = empty($file_types) ? $this->config->item('sts_site_upload_photo_types') : $file_types;
		$config['max_size']	= $this->config->item('sts_image_max_photo_size');
		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($userfile))
		{
			//show_error($error = array('error' => $this->upload->display_errors()));

			//log error
			log_message('error', 'Could not upload ' . $type . ' photo');
			
			$data['msg'] = $this->upload->display_errors();
			$data['success'] = false;
			
			return $data;
		}
		else
		{
			$data['info'] = $this->upload->data();
			
			if ($resize == 1)
			{
				$config['image_library'] = $this->config->item('sts_image_library');
				$config['source_image'] = $data['info']['full_path'];		
	
				$config['quality'] = $this->config->item('images_quality');
				$config['maintain_ratio'] = $this->config->item('images_maintain_ratio');
				$config['thumb_marker'] = '_jrox';
				
				if ($type == 'admin') 
				{
					$config['width'] = $this->config->item('sts_admin_image_height');
					$config['height'] = $this->config->item('sts_admin_image_width');
				}
				elseif($type == 'members')
				{
					$config['width'] = $this->config->item('sts_affiliate_image_height');
					$config['height'] = $this->config->item('sts_affiliate_image_width');
					
					//create thumbs
					$config['create_thumb'] = ($this->config->item('sts_affiliate_image_auto_resize') == 1 ? true : false);
				}
				else //generate store images
				{
					$config['width'] = $this->config->item('sts_site_image_height');
					$config['height'] = $this->config->item('sts_site_image_width');
					
					//create thumbs
					$config['create_thumb'] = ($this->config->item('sts_site_image_auto_resize') == 1 ? true : false);
				}
				
				
								
				$this->load->library('image_lib', $config);
				$this->image_lib->initialize($config); 
				$this->image_lib->resize();
				$this->image_lib->clear();

			}
			
			//return the array
			$data['success'] = true;
			return $data;

		}
	}
	
}
?>