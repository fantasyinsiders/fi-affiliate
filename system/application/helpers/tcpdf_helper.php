<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| COPYRIGHT NOTICE                                                     
| Copyright 2007 - 2013 JROX Technologies, Inc.  All Rights Reserved.   
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
| FILENAME - tcpdf_pi.php
| -------------------------------------------------------------------------     
| 
| This plugin generates PDFs
|
*/

function pdf_create($html = '', $title = '', $filename = '')
{
	$CI =& get_instance();

	define('K_TCPDF_EXTERNAL_CONFIG', 1);
	
	define ('K_PATH_MAIN', PUBPATH . '/system/application/helpers/tcpdf/');
	define ('K_PATH_URL', base_url());
	define ('K_PATH_FONTS', K_PATH_MAIN.'fonts/');
	define ('K_PATH_CACHE', K_PATH_MAIN.'cache/');
	define ('K_PATH_URL_CACHE', K_PATH_URL.'cache/');


	define ('K_PATH_IMAGES', PUBPATH.'/images/');

	/**
	 * blank image
	 */
	define ('K_BLANK_IMAGE', K_PATH_IMAGES.'_blank.png');

	/**
	 * page format
	 */
	define ('PDF_PAGE_FORMAT', $CI->config->item('module_affiliate_marketing_viral_pdfs_paper_size'));

	/**
	 * page orientation (P=portrait, L=landscape)
	 */
	 
	$orientation = $CI->config->item('module_affiliate_marketing_viral_pdfs_orientation') == 'landscape' ? 'L': 'P';
	define ('PDF_PAGE_ORIENTATION', $orientation);

	/**
	 * document creator
	 */
	define ('PDF_CREATOR', 'TCPDF');

	/**
	 * document author
	 */
	define ('PDF_AUTHOR', 'TCPDF');

	/**
	 * header title
	 */
	define ('PDF_HEADER_TITLE', '');

	/**
	 * header description string
	 */
	define ('PDF_HEADER_STRING', $title);

	/**
	 * image logo
	 */
	define ('PDF_HEADER_LOGO', '');

	/**
	 * header logo image width [mm]
	 */
	define ('PDF_HEADER_LOGO_WIDTH', '');

	/**
	 *  document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch]
	 */
	define ('PDF_UNIT', 'mm');

	/**
	 * header margin
	 */
	define ('PDF_MARGIN_HEADER', 5);

	/**
	 * footer margin
	 */
	define ('PDF_MARGIN_FOOTER', 10);

	/**
	 * top margin
	 */
	define ('PDF_MARGIN_TOP', 27);

	/**
	 * bottom margin
	 */
	define ('PDF_MARGIN_BOTTOM', 25);

	/**
	 * left margin
	 */
	define ('PDF_MARGIN_LEFT', 15);

	/**
	 * right margin
	 */
	define ('PDF_MARGIN_RIGHT', 15);

	/**
	 * default main font name
	 */
	define ('PDF_FONT_NAME_MAIN', 'helvetica');

	/**
	 * default main font size
	 */
	define ('PDF_FONT_SIZE_MAIN', 10);

	/**
	 * default data font name
	 */
	define ('PDF_FONT_NAME_DATA', 'helvetica');

	/**
	 * default data font size
	 */
	define ('PDF_FONT_SIZE_DATA', 8);

	/**
	 * default monospaced font name
	 */
	define ('PDF_FONT_MONOSPACED', 'courier');

	/**
	 * ratio used to adjust the conversion of pixels to user units
	 */
	define ('PDF_IMAGE_SCALE_RATIO', 1.25);

	/**
	 * magnification factor for titles
	 */
	define('HEAD_MAGNIFICATION', 1.1);

	/**
	 * height of cell repect font height
	 */
	define('K_CELL_HEIGHT_RATIO', 1.25);

	/**
	 * title magnification respect main font size
	 */
	define('K_TITLE_MAGNIFICATION', 1.3);

	/**
	 * reduction factor for small font
	 */
	define('K_SMALL_RATIO', 2/3);

	/**
	 * set to true to enable the special procedure used to avoid the overlappind of symbols on Thai language
	 */
	define('K_THAI_TOPCHARS', true);

	/**
	 * if true allows to call TCPDF methods using HTML syntax
	 * IMPORTANT: For security reason, disable this feature if you are printing user HTML content.
	 */
	define('K_TCPDF_CALLS_IN_HTML', true);
	
	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');
	/*
	require_once("convertcharset/ConvertCharset.class.php");
	
	$convertcharset = new ConvertCharset();
	
	//convert to UTF8
	$html = $convertcharset->Convert($html, 'utf-8', 'iso-8859-1');
	*/
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor($CI->config->item('sts_site_name'));
	$pdf->SetTitle($title);
	//$pdf->SetSubject('TCPDF Tutorial');
	//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
	
	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l);
	
	// ---------------------------------------------------------
	
	// set font
	$pdf->SetFont('helvetica', '', 10);
	
	// add a page
	$pdf->AddPage();
	
	// output the HTML content
	$pdf->writeHTML($html, true, false, true, false, '');
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	
	// reset pointer to the last page
	$pdf->lastPage();
	
	// ---------------------------------------------------------
	
	//Close and output PDF document
	$pdf->Output($filename . '.pdf', 'D');


	
}

?>