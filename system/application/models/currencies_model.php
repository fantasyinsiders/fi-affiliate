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
| FILENAME - currencies_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing currencies
|
*/

class Currencies_Model extends CI_Model {	
	
	// ------------------------------------------------------------------------
	
	function _convert_amount($amount = '', $curr = '')
	{
		$this->db->where('code', $curr);
		$query = $this->db->get('currencies');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			$amount = $row['value'] * $amount;
		}

		return $amount;
	}

	// ------------------------------------------------------------------------
	
	function _convert_currencies($data = array())
	{		
		$this->db->where('currency_id', $data['currency_id']);
		$this->db->update('currencies', array('value' => '1.00')); 	
		
		$url = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
		
		$resp = connect_curl($url);
		
		$pattern = "{<Cube\s*currency='(\w*)'\s*rate='([\d\.]*)'/>}is";
        preg_match_all($pattern,$resp,$xml_rates);
        array_shift($xml_rates);
		
		$exchange_rate['EUR'] = 1; 		
        for($i=0;$i<count($xml_rates[0]);$i++) {
 
            $exchange_rate[$xml_rates[0][$i]] = $xml_rates[1][$i];
        }
		
		$query = $this->db->get('currencies');	
		
		foreach ($query->result_array() as $row)
		{
			$a = $row['code'];
			$b = $data['code'];
			
			if (!empty($exchange_rate[$b]) && !empty($exchange_rate[$a]))
			{
				$v = (1 / $exchange_rate[$b]) * $exchange_rate[$a];
				$new_value = array('value' => $v);
				
				$this->db->where('currency_id', $row['currency_id']);
				$this->db->update('currencies', $new_value); 	
			}
			
		}
	
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _add_currency()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$data['code'] = strtoupper($data['code']);
		
		//insert into db
		if (!$this->db->insert('currencies', $data))
		{
			show_error($this->lang->line('could_not_add_currency'));
			
			//log error
			log_message('error', 'Could not insert currency into currencies table');
			
			return false;
		}
		else
		{
			$currency_id = $this->db->insert_id();
			
			//log success
			log_message('info', 'currency '. $currency_id . ' inserted into currencies table');
		}
		
		return $currency_id;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_currency($id = '')
	{
		if ($id == 1)
		{
			return false;
		}	
		
		//get currency details first
		$this->db->where('currency_id', $id);
		$query = $this->db->get('currencies');
		
		if ($query->num_rows() > 0)
		{
			//update default currency
			$row = $query->result_array();
			
			if ($this->config->item('sts_site_default_currency') == $row[0]['code'])
			{
				$sdata = array('sts_site_default_currency' => 'USD');
				
				$this->db_validation_model->_update_db_settings($sdata);
			}
		
			//delete currency
			$this->db->where('currency_id', $id);
			if ($this->db->delete('currencies'))
			{
				
				//log success
				log_message('info', 'currency ID #' . $id . ' deleted successfully');
			}
			else
			{
				show_error($this->lang->line('could_not_delete_currency'));
				
				//log error
				log_message('error', 'currency ID #' . $id . ' could not be deleted');
			}
		}
		
		
		
		return true;
	}	
	
	// ------------------------------------------------------------------------

	function _get_currency_details($id = '', $table = 'currency_id')
	{
		//get the data from currencies table
		$this->db->where($table, $id);
		$query = $this->db->get('currencies');
		
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
	
	function _get_currencies($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_cur_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_cur_column');

		$this->db->order_by($sort_column, $sort_order); 	
		$query = $this->db->get('currencies', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	function _update_currency($id = '')
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$data['code'] = strtoupper($data['code']);
		
		//update currency data
		$this->db->where('currency_id', $id);
		
		if (!$this->db->update('currencies', $data))
		{
			show_error($this->lang->line('could_not_update_currency'));
			
			//log error
			log_message('error', 'Could not update currency ID ' . $id. 'in currencies table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'currency ID '. $id . ' updated in currencies table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_currencies()
	{
		//run currency update
		$msg = '';
		$currencies = $this->db_validation_model->_get_details('currencies');
				
		if (!empty($currencies))
		{
			$base = $this->config->item('sts_site_default_currency');
			
			foreach ($currencies as $k => $v)
			{			
				$rate = $this->_quote_oanda_currency($v['code'], $base);
				
				if (empty($rate))
				{
					$rate = $this->_quote_xe_currency($v['code'], $base);
					
					if (empty($rate))
					{
						$msg .= '<div class="error" id="error-messages">' . $v['code'] . ' could not be updated</div>';
					}
					else
					{
						$msg .= '<div class="success">' . $v['code'] . ' = ' . round($rate, 2) . ' updated via xe</div>';
						$update = true;
					}
				}
				else
				{	
					$msg .= '<div class="success">' . $v['code'] . ' = ' . round($rate, 2) . ' updated via oanda</div>';
					$update = true;
				}
				
				if (!empty($update))
				{
					//update currency table
					$sdata = array(
									'value'	=> $rate,
									);
									
					$this->db->where('code', $v['code']);
					$this->db->update('currencies', $sdata);
				}
				
				sleep(5);
			}		
		}
		
		return $msg;
	}
	
	// ------------------------------------------------------------------------	
	
	function _quote_oanda_currency($code = 'USD', $base = 'USD') 
	{
		$url = 'http://www.oanda.com/convert/fxdaily';
		
		$data = 'value=1&redirected=1&exch=' . $code .  '&format=CSV&dest=Get+Table&sel_list=' . $base;
		
		$page = connect_curl($url, true, $data, 1,  $this->config->item('sts_site_set_curl_timeout'));
		
		$page = explode("\n", $page);
		  
		if (is_object($page) || $page !='') 
		{
			$match = array();
			
			preg_match('/(.+),(\w{3}),([0-9.]+),([0-9.]+)/i', implode('', $page), $match);
			
			if (sizeof($match) > 0) 
			{
				return $match[3];
			} 
			else 
			{
				return false;
			}
		}
}
  
	// ------------------------------------------------------------------------	

	function _quote_xe_currency($to = 'USD', $from = 'USD') 
	{
		$url = 'http://www.xe.net/ucc/convert.cgi';
		
		$data = 'Amount=1&From=' . $from . '&To=' . $to;
		
		$page = connect_curl($url, true, $data, 1,  $this->config->item('sts_site_set_curl_timeout'));
		
		$page = explode("\n", $page);
		
		if (is_object($page) || $page !='') 
		{
			$match = array();
			
			preg_match('/[0-9.]+\s*' . $from . '\s*=\s*([0-9.]+)\s*' . $to . '/', implode('', $page), $match);
			
			if (sizeof($match) > 0) 
			{
				return $match[1];
			} 
			else 
			{
				return false;
			}
		}
	}
	
	// ------------------------------------------------------------------------	
}
?>