<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Pagination Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Pagination
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/pagination.html
 */
class CI_Pagination {

	var $base_url			= ''; // The page we are linking to
	var $total_rows  		= ''; // Total number of items (database results)
	var $per_page	 		= 10; // Max number of items you want shown per page
	var $num_links			=  2; // Number of "digit" links to show before/after the currently viewed page
	var $cur_page	 		=  0; // The current page being viewed
	var $first_link   		= '&lsaquo; First';
	var $next_link			= '&gt;';
	var $prev_link			= '&lt;';
	var $last_link			= 'Last &rsaquo;';
	var $uri_segment		= 3;
	var $full_tag_open		= '';
	var $full_tag_close		= '';
	var $first_tag_open		= '';
	var $first_tag_close	= '&nbsp;';
	var $last_tag_open		= '&nbsp;';
	var $last_tag_close		= '';
	var $cur_tag_open		= '&nbsp;<b>';
	var $cur_tag_close		= '</b>';
	var $next_tag_open		= '&nbsp;';
	var $next_tag_close		= '&nbsp;';
	var $prev_tag_open		= '&nbsp;';
	var $prev_tag_close		= '';
	var $num_tag_open		= '&nbsp;';
	var $num_tag_close		= '';
	var $sort_order			= 'ASC';
	var $sort_column		= '';
	var $where_key			= '0';
	var $where_value		= '0';
	var $where_key2			= '0';
	var $where_value2		= '0';
	var $type				= 'admin';
   
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 */
	function CI_Pagination($params = array())
	{
		if (count($params) > 0)
		{
			$this->initialize($params);		
		}
		
		log_message('debug', "Pagination Class Initialized");
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}		
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */	
	function create_links()
	{
		$a['rows'] = '';
		$a['previous'] = '';
		$a['next'] = '';
		$a['small_rows'] = '';
		
		// If our item count or per-page total is zero there is no need to continue.
		if ($this->total_rows == 0 OR $this->per_page == 0)
		{
		   return $a;
		}

		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);

		// Is there only one page? Hm... nothing more to do here then.
		if ($num_pages == 1)
		{
			return $a;
		}

		// Determine the current page number.		
		$CI =& get_instance();	
		if ($CI->uri->segment($this->uri_segment) != 0)
		{
			$this->cur_page = $CI->uri->segment($this->uri_segment);
			
			// Prep the current page - no funny business!
			$this->cur_page = (int) $this->cur_page;
		}

		$this->num_links = (int)$this->num_links;
		
		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}
				
		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = 0;
		}
		
		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->cur_page > $this->total_rows)
		{
			$this->cur_page = ($num_pages - 1) * $this->per_page;
		}
		
		$uri_page_number = $this->cur_page;
		$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;
		
		$a['previous'] = (($this->cur_page - 2) * $this->per_page) >= 0 ? (($this->cur_page - 2) * $this->per_page) : '';
		$a['next'] = ($this->cur_page * $this->per_page) < ($num_pages * $this->per_page) ? ($this->cur_page * $this->per_page) : '';
		
		// Add a trailing slash to the base URL if needed
		$this->base_url = rtrim($this->base_url, '/') .'/';
	
		$a['rows'] = '<ul class="pagination pagination-lg">';	
		
		// Render the "First" link
		if  ($this->cur_page > $this->num_links)
		{
			$a['rows'] .= '<li><a href="'.$this->base_url.'/0/'.$this->sort_order.'/'.$this->sort_column.'/'.$this->where_key.'/'.$this->where_value.'/'.$this->where_key2.'/'.$this->where_value2.'">'.$this->first_link.'</a></li>';
		}

		// Render the "previous" link
		if  ($this->cur_page != 1)
		{
			$i = $uri_page_number - $this->per_page;
			//if ($i == 0) $i = '';
			$a['rows'] .= '<li><a href="'.$this->base_url.'/'.$i.'/'.$this->sort_order.'/'.$this->sort_column.'/'.$this->where_key.'/'.$this->where_value.'/'.$this->where_key2.'/'.$this->where_value2.'">'.$this->prev_link.'</a></li>';
		}

		// Write the digit links
		for ($loop = $start -1; $loop <= $end; $loop++)
		{
			$i = ($loop * $this->per_page) - $this->per_page;
					
			if ($i >= 0)
			{
				if ($this->cur_page == $loop)
				{
					$a['rows'] .= '<li class="active">'.$this->cur_tag_open.$loop.$this->cur_tag_close . '</li>'; // Current page
				}
				else
				{
					//$n = ($i == 0) ? '' : $i;
					$n = $i;
					$a['rows'] .= '<li><a href="'.$this->base_url.'/'.$n.'/'.$this->sort_order.'/'.$this->sort_column.'/'.$this->where_key.'/'.$this->where_value.'/'.$this->where_key2.'/'.$this->where_value2.'">'.$loop.'</a></li>';
				}
			}
		}

		// Render the "next" link
		if ($this->cur_page < $num_pages)
		{
				$a['rows'] .= '<li><a href="'.$this->base_url.'/'.($this->cur_page * $this->per_page).'/'.$this->sort_order.'/'.$this->sort_column.'/'.$this->where_key.'/'.$this->where_value.'/'.$this->where_key2.'/'.$this->where_value2.'">&gt;</a></li>';
			
		}

		// Render the "Last" link
		if (($this->cur_page + $this->num_links) < $num_pages)
		{
			$i = (($num_pages * $this->per_page) - $this->per_page);
			
				$a['rows'] .= '<li><a href="'.$this->base_url.'/'.$i.'/'.$this->sort_order.'/'.$this->sort_column.'/'.$this->where_key.'/'.$this->where_value.'/'.$this->where_key2.'/'.$this->where_value2.'">'.$this->last_link.'</a></li>';
			
		}
		
		$a['rows'] .= '</ul>';
		
		// Kill double slashes.  Note: Sometimes we can end up with a double slash
		// in the penultimate link so we'll kill all double slashes.
		$a['rows'] = preg_replace("#([^:])//+#", "\\1/", $a['rows']);

		// Add the wrapper HTML if exists
		$a['rows'] = $this->full_tag_open.$a['rows'].$this->full_tag_close;
	
		$a['small_rows'] = str_replace('pagination-lg', 'pagination', $a['rows']);
		return $a;		
	}
}
// END Pagination Class
?>