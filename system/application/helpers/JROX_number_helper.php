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
| FILENAME: JROX_number_helper.php
| -------------------------------------------------------------------------     
| 
| This file extends the number_helper
|
*/

// ------------------------------------------------------------------------

function format_amounts($amount = 0, $options = '', $numeric = false, $symbols = false)
{
	if (!$options)
	{
		return number_format($amount);	
	}
		
	if ($symbols == false)
	{
		$amount = $amount * $options[0]['value'];
	}
		
	if ($numeric == false)
	{
		$amount = number_format($amount, $options[0]['decimal_places'], $options[0]['decimal_point'], $options[0]['thousands_point']);
		$amount = $options[0]['symbol_left'].$amount.$options[0]['symbol_right'];
	}
	else
	{
		$amount = number_format($amount, $options[0]['decimal_places'], $options[0]['decimal_point'], '');
	}
	
	
	return $amount;
}

// ------------------------------------------------------------------------

function get_default_currency($a = 'USD')
{
	$CI = & get_instance();
	
	$CI->db->where('code', $a);
	$query = $CI->db->get('currencies');
	
	return $query->result_array();
}

// ------------------------------------------------------------------------

function get_site_currency()
{
	$CI = & get_instance();
	
	$CI->db->select('settings_value');
	$CI->db->where('settings_key', 'sts_site_default_currency');
	$query = $CI->db->get('settings');
	
	return $query->row_array();
}

// ------------------------------------------------------------------------

function change_empty($value = '', $empty = '')
{
	if (empty($value))
	{
		$value = $empty;
	}
	
	return $value;
}

function format_phone($phone_number = '') 
{
	$phone_number= preg_replace('/[^0-9]+/', '', $phone_number);
	if (strlen($phone_number) == 11)
	{
		return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "+$1.$2.$3.$4", $phone_number);
	}
	elseif (strlen($phone_number) == 10)
	{
		return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "+1.$1.$2.$3", $phone_number);
	}
	return $phone_number;
}

// ------------------------------------------------------------------------

function change_boolean($value = '', $inactive = 0, $active = 1, $global = 2)
{
	switch ($value)
	{
		case '0':
			
			return $inactive;
			
		break;
		
		case '1':
		
			return $active;
		
		break;
		
		case '2':
		
			return $global;
		
		break;
	}
}

// ------------------------------------------------------------------------

function check_integer($str)
{
	return (bool)preg_match( '/^[\-+]?[0-9]+$/', $str);
}

// ------------------------------------------------------------------------

function num2words($num, $c = 1) 
{
    $ZERO = 'zero';
    $MINUS = 'minus';
    $lowName = array(
         /* zero is shown as "" since it is never used in combined forms */
         /* 0 .. 19 */
         "", "one", "two", "three", "four", "five",
         "six", "seven", "eight", "nine", "ten",
         "eleven", "twelve", "thirteen", "fourteen", "fifteen",
         "sixteen", "seventeen", "eighteen", "nineteen");
   
    $tys = array(
         /* 0, 10, 20, 30 ... 90 */
         "", "", "twenty", "thirty", "forty", "fifty",
         "sixty", "seventy", "eighty", "ninety");
   
    $groupName = array(
         /* We only need up to a quintillion, since a long is about 9 * 10 ^ 18 */
         /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
         "", "hundred", "thousand", "million", "billion",
         "trillion", "quadrillion", "quintillion");
   
    $divisor = array(
         /* How many of this group is needed to form one of the succeeding group. */
         /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
         100, 10, 1000, 1000, 1000, 1000, 1000, 1000) ;
   
    $num = str_replace(",","",$num);
    $num = number_format($num,2,'.','');
    $cents = substr($num,strlen($num)-2,strlen($num)-1);
    $num = (int)$num;
   
    $s = "";
   
    if ( $num == 0 ) $s = $ZERO;
    $negative = ($num < 0 );
    if ( $negative ) $num = -$num;
    // Work least significant digit to most, right to left.
    // until high order part is all 0s.
    for ( $i=0; $num>0; $i++ ) {
       $remdr = (int)($num % $divisor[$i]);
       $num = $num / $divisor[$i];
       // check for 1100 .. 1999, 2100..2999, ... 5200..5999
       // but not 1000..1099,  2000..2099, ...
       // Special case written as fifty-nine hundred.
       // e.g. thousands digit is 1..5 and hundreds digit is 1..9
       // Only when no further higher order.
       /*
	   if ( $i == 1  && 1 <= $num && $num <= 2 ){
           if ( $remdr > 0 ){
               $remdr = ($num * 10);
               $num = 0;
           } // end if
       } // end if
       */
	   if ( $remdr == 0 ){
           continue;
       }
       $t = "";
       if ( $remdr < 20 ){
           $t = $lowName[$remdr];
       }
       else if ( $remdr < 100 ){
           $units = (int)$remdr % 10;
           $tens = (int)$remdr / 10;
           $t = $tys [$tens];
           if ( $units != 0 ){
               $t .= "-" . $lowName[$units];
           }
       }else {
           $t = num2words($remdr, 0);
       }
       $s = $t." ".$groupName[$i]." ".$s;
       $num = (int)$num;
    } // end for
    $s = trim($s);
    if ( $negative ){
       $s = $MINUS . " " . $s;
    }
   
    if ($c == 1) $s .= " and $cents/100";
   
    return $s;
} // end num2words

// ------------------------------------------------------------------------

?>