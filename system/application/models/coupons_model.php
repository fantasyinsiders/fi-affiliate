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
| FILENAME - coupons_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing the coupon codes
|
*/

class Coupons_Model extends CI_Model {	
	
	// ------------------------------------------------------------------------
	
	function _get_coupons($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
    {
        //get all the admins from db for list view
        if (!$sort_order) $sort_order = $this->config->item('dbs_cou_order');
        if (!$sort_column) $sort_column = $this->config->item('dbs_cou_column');

         $sql = 'SELECT ' . $this->db->dbprefix('coupons') . '.*,
				(SELECT username from ' . $this->db->dbprefix('members') . ' 
						WHERE ' . $this->db->dbprefix('coupons') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as username
						FROM ' . $this->db->dbprefix('coupons') . '
						ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		 
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }

        return  false;
    }
	
	// ------------------------------------------------------------------------
	
	function _get_coupon_details($id = '', $field = 'coupon_id', $status = false)
    {
        //get the data from coupons table
        $sql = 'SELECT ' . $this->db->dbprefix('coupons') . '.*,
				(SELECT username from ' . $this->db->dbprefix('members') . ' 
						WHERE ' . $this->db->dbprefix('coupons') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as username
						FROM ' . $this->db->dbprefix('coupons') . '
						WHERE ' . $field . ' = \'' . $id . '\'';

        if ($status == true)
        {
            $sql .= ' AND status = \'1\'';
        }

        $query = $this->db->query($sql);

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
	
	function _add_coupon($data = array())
	{
		$data = $this->db_validation_model->_clean_data($data);
		
		unset($data['referring_affiliate']);
			
        //insert into db
        if (!$this->db->insert('coupons', $data))
        {
            show_error($this->lang->line('could_not_add_coupon'));

            log_message('error', 'Could not insert coupon into coupons table');

            return false;
        }
        else
        {
            log_message('info', 'coupon inserted into coupons table');
        }

        return $this->db->insert_id();
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_coupon($id = '')
	{
		 $this->db->where('coupon_id', $id);

        if ($this->db->delete('coupons'))
        {

            //log success
            log_message('info', 'coupon #' . $id . ' deleted successfully');
        }
        else
        {
            show_error($this->lang->line('could_not_delete_coupon'));

            //log error
            log_message('error', 'coupon #' . $id . ' could not be deleted');
        }

        return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_coupon($id = '', $data = array())
	{
		 //clean the data first
        $data = $this->db_validation_model->_clean_data($data);
		
		unset($data['referring_affiliate']);
		
        $this->db->where('coupon_id', $id);

        if (!$this->db->update('coupons', $data))
        {
            show_error($this->lang->line('could_not_update_coupon'));

            //log error
            log_message('error', 'Could not update coupon ID ' . $id . 'in coupon table');

            return false;
        }
        else
        {
            //log success
            log_message('info', 'coupon ID '. $id . ' updated in coupon table');
        }
	}
	
}
?>