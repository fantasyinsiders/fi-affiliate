<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
| FILENAME - db_sort.php
| -------------------------------------------------------------------------     
| 
| This file determines default sorting of data and filtered input
|
*/

//number of years to show on reports
$config['dbr_total_report_years'] = '3';

//axis range fo reports
$config['dbr_y_axis_range'] = '50';

//data input to be filtered
$config['dbi_filter'] = array('reset', 'passconf', 'member_button', 'admin_button', 'send_welcome_email', 'registration_button',
							  'allowed_tags', 'product_button','sponsor_required', 'recaptcha_response_field',
							  'ship_option', 'jroxEnableShipping', 'affil_option', 'jroxEnableAffiliate', 'encrypted',
							  'jroxSubmitPForm', 'tos_check', 'program_button', 'recaptcha_challenge_field');

//to remove certain elements in the content 
$config['dbi_content_filter'] = array('&nbsp;' => '');


//for replacing certain words in the language file automatically
/*
$config['dbi_lang_filter'] = array('affiliate' => 'consultant',
									'member' => 'consultant',
								);
*/

//arrays not to run through xss filter
$config['dbi_arrays'] = array('redirect_custom_url');

//array for graph colors
$config['report_graph_colors'] = array('#DB887F', '#3F5C9A');

//affiliate ad categories
$config['dbs_aac_column'] = 'cat_name'; 
$config['dbs_aac_order'] = 'ASC';

//email archive
$config['dbs_arc_column'] = 'id'; 
$config['dbs_arc_order'] = 'DESC';

//admin_users
$config['dbs_au_column'] = 'admin_id'; 
$config['dbs_au_order'] = 'ASC';

//members
$config['dbs_mm_column'] = 'member_id';
$config['dbs_mm_order'] = 'DESC';

//affiliate payments
$config['dbs_afp_column'] = 'id'; 
$config['dbs_afp_order'] = 'ASC';

//custom form fields
$config['dbs_cff_column'] = 'sort_order';
$config['dbs_cff_order'] = 'ASC';

//affiliate groups
$config['dbs_ag_column'] = 'tier';
$config['dbs_ag_order'] = 'ASC';

//affiliate make payments
$config['dbs_amp_column'] = 'total';
$config['dbs_amp_order'] = 'DESC';

//countries
$config['dbs_con_column'] = 'country_name';
$config['dbs_con_order'] = 'ASC';

//regions
$config['dbs_reg_column'] = 'region_id';
$config['dbs_reg_order'] = 'ASC';

//integration
$config['dbs_int_column'] = 'sort';
$config['dbs_int_order'] = 'DESC';

//themes
$config['dbs_the_column'] = 'theme_default';
$config['dbs_the_order'] = 'DESC';

//modules
$config['dbs_mod_column'] = 'module_id';
$config['dbs_mod_order'] = 'DESC';

//currencies
$config['dbs_cur_column'] = 'currency_id';
$config['dbs_cur_order'] = 'ASC';

//languages
$config['dbs_lng_column'] = 'name';
$config['dbs_lng_order'] = 'ASC';

//performanc rewards
$config['dbs_pw_column'] = 'sort_order';
$config['dbs_pw_order'] = 'ASC';

//email templates
$config['dbs_emt_column'] = 'email_template_name';
$config['dbs_emt_order'] = 'ASC';

//content articles
$config['dbs_crt_column'] = 'sort_order';
$config['dbs_crt_order'] = 'ASC';

//faq articles
$config['dbs_faq_column'] = 'article_id';
$config['dbs_faq_order'] = 'ASC';

//banners
$config['dbs_bnr_column'] = 'sort_order';
$config['dbs_bnr_order'] = 'ASC';

//affiliate marketing
$config['dbs_afm_column'] = 'module_file_name';
$config['dbs_afm_order'] = 'ASC';

//mailing lists
$config['dbs_mll_column'] = 'mailing_list_id';
$config['dbs_mll_order'] = 'ASC';

//invisilinks
$config['dbs_inl_column'] = 'id';
$config['dbs_inl_order'] = 'ASC';

//mailing lists users
$config['dbs_mlu_column'] = 'send_date';
$config['dbs_mlu_order'] = 'ASC';

//follow ups
$config['dbs_flo_column'] = 'sequence';
$config['dbs_flo_order'] = 'ASC';

//queue
$config['dbs_emq_column'] = 'send_date';
$config['dbs_emq_order'] = 'DESC';

//commissions
$config['dbs_afc_column'] = 'comm_id';
$config['dbs_afc_order'] = 'DESC';

//downloads
$config['dbs_dwn_column'] = 'id';
$config['dbs_dwn_order'] = 'DESC';

//action commissions
$config['dbs_acc_column'] = 'id';
$config['dbs_acc_order'] = 'DESC';

//affiliate payments
$config['dbs_afp_column'] = 'id';
$config['dbs_afp_order'] = 'DESC';

//programs
$config['dbs_prg_column'] = 'sort_order';
$config['dbs_prg_order'] = 'ASC';

//data import
$config['dbs_imp_column'] = 'module_name';
$config['dbs_imp_order'] = 'ASC';

//data export
$config['dbs_exp_column'] = 'module_name';
$config['dbs_exp_order'] = 'ASC';

//reports
$config['dbs_rep_column'] = 'module_name';
$config['dbs_rep_order'] = 'ASC';

//reports archive
$config['dbs_arc_column'] = 'id';
$config['dbs_arc_order'] = 'DESC';

//traffic
$config['dbs_trf_column'] = 'traffic_id';
$config['dbs_trf_order'] = 'DESC';

//tracking
$config['dbs_trc_column'] = 'id';
$config['dbs_trc_order'] = 'DESC';

//tracking referrals
$config['dbs_trr_column'] = 'traffic_id';
$config['dbs_trr_order'] = 'DESC';

//tracking log
$config['dbs_trk_column'] = 'id';
$config['dbs_trk_order'] = 'DESC';

//coupons
$config['dbs_cou_column'] = 'coupon_code';
$config['dbs_cou_order'] = 'DESC';

/*
for public side sorting
*/

//faq articles
$config['pub_dbs_faq_column'] = 'content_title';
$config['pub_dbs_faq_order'] = 'ASC';

//content articles
$config['pub_dbs_crt_column'] = 'sort_order';
$config['pub_dbs_crt_order'] = 'ASC';

//support tickets
$config['dbs_stc_column'] = 'ticket_id';
$config['dbs_stc_order'] = 'DESC';

//support categories
$config['dbs_spc_column'] = 'support_category_id';
$config['dbs_spc_order'] = 'ASC';

//support categories
$config['dbs_spc_column'] = 'support_category_id';
$config['dbs_spc_order'] = 'ASC';

/*
for members side sorting
*/

//programs
$config['mem_dbs_prg_column'] = 'sort_order';
$config['mem_dbs_prg_order'] = 'ASC';

//members content
$config['mem_dbs_cnt_column'] = 'sort_order';
$config['mem_dbs_cnt_order'] = 'ASC';

//members downloads
$config['mem_dbs_dwn_column'] = 'download_name';
$config['mem_dbs_dwn_order'] = 'ASC';

//members tracking
$config['mem_dbs_trk_column'] = 'id';
$config['mem_dbs_trk_order'] = 'ASC';

//members traffic
$config['mem_dbs_tff_column'] = 'traffic_id';
$config['mem_dbs_tff_order'] = 'DESC';

//members tickets
$config['mem_dbs_sup_column'] = 'ticket_id';
$config['mem_dbs_sup_order'] = 'DESC';

//members reports
$config['mem_dbs_rep_column'] = 'module_name';
$config['mem_dbs_rep_order'] = 'ASC';

//members tools
$config['mem_dbs_afm_column'] = 'module_name';
$config['mem_dbs_afm_order'] = 'ASC';
?>