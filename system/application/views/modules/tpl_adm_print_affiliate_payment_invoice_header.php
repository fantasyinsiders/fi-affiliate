<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- load html charset -->
<meta http-equiv="Content-Type" content="text/html; charset=<?=$this->config->item('charset')?>" />

<!-- load page title -->
<title><?=$this->lang->line('affiliate_payment_invoice')?></title>

<!-- load admin stylesheets -->
<style media="screen, print">

@media print {
.noPrint { display: none; }
}
 
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size:12px;
	line-height:1.5em;

}

img {
	border:0;
}


a {
	text-decoration:none;
	color:#000;
}


a:hover {
	text-decoration:underline;
}

.amount-due {
	color:#CC0000;
}

.invoice-paid {
	color: #336600;
}

.noPrint {
	
	float:right;
	padding:5px;
}

.noPrint button {
	font-size: 10px;
	border:0;
	
	background:#FFFFFF; 
	padding:0 ; 
	margin:0;
}

table.invoice_box_2 tr.title td {
	background-color: #EFEFEF;
	color: #333;
	font-size: 13px;
	font-weight:bold;
	border-bottom:1px solid #CCCCCC;
	text-transform:capitalize;
}

table.invoice_box_2 tr.prod td {
	border-bottom:1px solid #CCCCCC;
}
.table_1 {
	padding:1em; 
	width:100%;
	margin: 1em auto;
}

.invoice_box_2 {
	border: 1px solid #999999;
	padding:1em;
	background-color:#F5F5F5;
	text-transform:capitalize;
}

.invoice_box_1 {
	width: 100%;
	text-transform:capitalize;
	border-top: 1px solid #999999;
	border-bottom: 1px solid #999999;
	border-left: 1px solid #999999;
	padding:1em;
	height: 170px;
	background-color:#F5F5F5;
}

.invoice_box_3 {
	width: 100%;
	text-transform:capitalize;
	border: 1px solid #999999;
	padding:1em;	
	height: 170px;
	background-color:#F5F5F5;
}

.invoice_box_1, .invoice_box_3 {
	width:100%;
	text-align:left;
}

.account_info {
	text-transform:capitalize; 
	font-weight:bold; 
	border-bottom: 1px solid #CCCCCC;
	padding:5px;
	margin-bottom:5px;
	background-color: #EFEFEF;
}

.table_1 {
	height:800px;
}

.table_2 {
	height:900px;
}

.separator { height: 18px; }

</style>
</head>
<body id="main_body"  <?php if ($this->config->item('sts_invoice_autload_print_window') == 1) echo 'onload="window.print()"'; ?>>