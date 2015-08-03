<?php
/*
| -------------------------------------------------------------------------
| COPYRIGHT NOTICE
| Copyright 2013 JROX Technologies, Inc.  All Rights Reserved.
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
| FILENAME - updates.php
| -------------------------------------------------------------------------
|
| This file is used for running db schema updates in JAM
|
*/
 
$update_version = 'new';

$update_array[0] = array(
	'update_type' => 'config',
	'config_key' => 'sts_affiliate_enable_ad_trackers',

	'update_sql' => "INSERT INTO `jam_settings` (`settings_key` ,`settings_value` ,`settings_module` ,`settings_type` , `settings_group` ,`settings_sort_order` ,`settings_function`) VALUES ('sts_affiliate_enable_ad_trackers', '0', 'settings', 'dropdown', '16', '47', 'boolean')",
);

$update_array[1] = array(

	'update_type' => 'column',
	'column_name' => 'tracker',
	'check_sql' => 'SHOW columns FROM jam_commissions',

	'update_sql' => "ALTER TABLE  `jam_commissions` ADD  `tracker` VARCHAR( 255 ) NOT NULL DEFAULT '' ;",
);


$update_array[2] = array(

	'update_type' => 'column',
	'column_name' => 'status',
	'check_sql' => 'SHOW columns FROM jam_languages',

	'update_sql' => "ALTER TABLE  `jam_languages` ADD  `status` ENUM(  '0',  '1' ) NOT NULL DEFAULT '1';",
);

$update_array[3] = array(

	'update_type' => 'column',
	'column_name' => 'description',
	'check_sql' => 'SHOW columns FROM jam_downloads',

	'update_sql' => "ALTER TABLE  `jam_downloads` ADD  `description` TEXT NOT NULL AFTER  `download_name` ;",
);

$update_array[4] = array(

	'update_type' => 'column',
	'column_name' => 'parent_id',
	'check_sql' => 'SHOW columns FROM jam_commissions',

	'update_sql' => "ALTER TABLE  `jam_commissions` ADD  `parent_id` INT( 10 ) NOT NULL DEFAULT  '0';",
);

$update_array[5] = array(

	'update_type' => 'column',
	'column_name' => 'recurring_comm',
	'check_sql' => 'SHOW columns FROM jam_commissions',

	'update_sql' => "ALTER TABLE  `jam_commissions` ADD  `recurring_comm` ENUM(  '0',  '1' ) NOT NULL DEFAULT  '0';",
);

$update_array[6] = array(

	'update_type' => 'column',
	'column_name' => 'email_template_description',
	'check_sql' => 'SHOW columns FROM jam_email_templates',

	'update_sql' => "ALTER TABLE  `jam_email_templates` ADD  `email_template_description` VARCHAR( 255 ) NOT NULL DEFAULT  '0';",
);

$update_array[7] = array(

	'update_type' => 'column',
	'column_name' => 'coinbase_id',
	'check_sql' => 'SHOW columns FROM jam_members',

	'update_sql' => "ALTER TABLE  `jam_members` ADD  `coinbase_id` VARCHAR( 255 ) NOT NULL AFTER  `payza_id` ;",
);

$update_array[8] = array(

	'update_type' => 'column',
	'column_name' => 'enable_coinbase_id',
	'check_sql' => 'SHOW columns FROM jam_programs_form_fields',

	'update_sql' => "ALTER TABLE  `jam_programs_form_fields` ADD  `enable_coinbase_id` ENUM(  '0',  '1',  '2' ) NOT NULL DEFAULT  '0' AFTER  `enable_payza_id` ;",
);

$update_array[9] = array(
	'update_type' => 'config',
	'config_key' => 'sts_content_translate_menus',

	'update_sql' => "INSERT INTO `jam_settings` (`settings_key` ,`settings_value` ,`settings_module` ,`settings_type` , `settings_group` ,`settings_sort_order` ,`settings_function`) VALUES ('sts_content_translate_menus', '0', 'settings', 'dropdown', '4', '72', 'boolean')",
);

$update_array[10] = array(
	'update_type' => 'config', 
	'config_key' => 'layout_theme_members_default_dashboard_template',

	'update_sql' => "INSERT INTO `jam_settings` (`settings_key`, `settings_value`, `settings_module`, `settings_type`, `settings_group`, `settings_sort_order`, `settings_function`) VALUES ('layout_theme_members_default_dashboard_template', 'tpl_members_dashboard', 'layout', 'dropdown', 21, 44, 'members_dashboard_template')",
);

$update_array[11] = array(

	'update_type' => 'column',
	'column_name' => 'dwolla_id',
	'check_sql' => 'SHOW columns FROM jam_members',

	'update_sql' => "ALTER TABLE  `jam_members` ADD  `dwolla_id` VARCHAR( 255 ) NOT NULL;",
);

$update_array[12] = array(

	'update_type' => 'column',
	'column_name' => 'enable_dwolla_id',
	'check_sql' => 'SHOW columns FROM jam_programs_form_fields',

	'update_sql' => "ALTER TABLE  `jam_programs_form_fields` ADD  `enable_dwolla_id` ENUM(  '0',  '1',  '2' ) NOT NULL DEFAULT  '0' AFTER  `enable_coinbase_id` ;",
);


$update_array[13] = array(

	'update_type' => 'table',
	'table_name' => 'integration_profiles',

	'update_sql' => "CREATE TABLE IF NOT EXISTS `jam_integration_profiles` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
					  `program_id` int(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
					  `product_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
					  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `amount` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
					  `trans_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
					  `tracking_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `invoice_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
					  `customer_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
					  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_3` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_4` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_5` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_6` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_7` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_8` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_9` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_10` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_11` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_12` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_13` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_14` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_15` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_16` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_17` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_18` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_19` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `custom_field_20` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `lf_data` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;",
);

$update_array[14] = array(

	'update_type' => 'column',
	'column_name' => 'sort_order',
	'check_sql' => 'SHOW columns FROM jam_performance_rewards',

	'update_sql' => "ALTER TABLE  `jam_performance_rewards` ADD  `sort_order` int(10) NOT NULL DEFAULT '0';",
);

$update_array[15] = array(
    'update_type' => 'config',
    'config_key' => 'sts_local_key',

    'update_sql' => "INSERT INTO `jam_settings` (`settings_key`, `settings_value`, `settings_module`, `settings_type`, `settings_group`, `settings_sort_order`, `settings_function`) VALUES ('sts_local_key', '', 'system', 'hidden', 15, 99, 'none')",
);

$update_array[16] = array(
    'update_type' => 'config',
    'config_key' => 'sts_local_mx_key',

    'update_sql' => "INSERT INTO `jam_settings` (`settings_key`, `settings_value`, `settings_module`, `settings_type`, `settings_group`, `settings_sort_order`, `settings_function`) VALUES ('sts_local_mx_key', '', 'system', 'hidden', 15, 99, 'none')",
);


$update_array[17] = array(

    'update_type' => 'table',
    'table_name' => 'product_commissions',

    'update_sql' => "CREATE TABLE IF NOT EXISTS `jam_product_commissions` (
                        `id` int(10) NOT NULL AUTO_INCREMENT,
                          `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
                          `type` enum('flat','percent') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'flat',
                          `product_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                          `product_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                          `amount` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;",
);

$update_array[18] = array(

    'update_type' => 'column',
    'column_name' => 'product_id',
    'check_sql' => 'SHOW columns FROM jam_commissions',

    'update_sql' => "ALTER TABLE  `jam_commissions` ADD  `product_id` VARCHAR( 255 ) NOT NULL AFTER  `action_commission_id` ;",
);

$update_array[19] = array(
    'update_type' => 'config',
    'config_key' => 'sts_content_members_dashboard_social_sharing',

    'update_sql' => "INSERT INTO `jam_settings` (`settings_key`, `settings_value`, `settings_module`, `settings_type`, `settings_group`, `settings_sort_order`, `settings_function`) VALUES ('sts_content_members_dashboard_social_sharing', '1', 'settings', 'dropdown', 19, 50, 'boolean')",
);

$update_array[20] = array( 
    'update_type' => 'config',
    'config_key' => 'sts_admin_show_dashboard_video',

    'update_sql' => "INSERT INTO `jam_settings` (`settings_key`, `settings_value`, `settings_module`, `settings_type`, `settings_group`, `settings_sort_order`, `settings_function`) VALUES ('sts_admin_show_dashboard_video', '1', 'settings', 'dropdown', 8, 30, 'yes_no')",
);

$update_array[21] = array(
    'update_type' => 'config',
    'config_key' => 'sts_auto_login_redirect_login',

    'update_sql' => "INSERT INTO `jam_settings` (`settings_key`, `settings_value`, `settings_module`, `settings_type`, `settings_group`, `settings_sort_order`, `settings_function`) VALUES ('sts_auto_login_redirect_login', '', 'settings', 'text', 24, 4, 'none')",
);

$update_array[22] = array(
    'update_type' => 'config',
    'config_key' => 'sts_auto_login_redirect_registration',

    'update_sql' => "INSERT INTO `jam_settings` (`settings_key`, `settings_value`, `settings_module`, `settings_type`, `settings_group`, `settings_sort_order`, `settings_function`) VALUES ('sts_auto_login_redirect_registration', '', 'settings', 'text', 24, 5, 'none')",
);


$update_array[23] = array(

    'update_type' => 'column',
    'column_name' => 'sort',
    'check_sql' => 'SHOW columns FROM jam_program_integration',

    'update_sql' => "ALTER TABLE  `jam_program_integration` ADD  `sort` INT( 10 ) NOT NULL DEFAULT '0';",
);

$update_array[24] = array(

    'update_type' => 'column',
    'column_name' => 'postback_url',
    'check_sql' => 'SHOW columns FROM jam_programs',

    'update_sql' => "ALTER TABLE  `jam_programs` ADD  `postback_url` VARCHAR( 255 ) NOT NULL DEFAULT '';",
);

$update_array[25] = array(

    'update_type' => 'config_update',
    'column_name' => 'sort',
    'check_sql' => '',

    'update_sql' => "UPDATE `jam_settings` SET `settings_value` = '0' WHERE `settings_key` = 'enable_facebook_connect' LIMIT 1;",
);

$update_array[26] = array(

	'update_type' => 'table',
	'table_name' => 'coupons',

	'update_sql' => "CREATE TABLE IF NOT EXISTS `jam_coupons` (
					  `coupon_id` int(10) NOT NULL AUTO_INCREMENT,
					  `coupon_code` varchar(50) NOT NULL,
					  `coupon_description` varchar(255) NOT NULL,
					  `member_id` int(10) NOT NULL,
					  `amount` decimal(14,2) NOT NULL DEFAULT '0.00',
					  `type` enum('flat','percent') NOT NULL DEFAULT 'flat',
					  `status` enum('0','1') NOT NULL DEFAULT '0',
					  `use_program_comms` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
					  PRIMARY KEY (`coupon_id`),
					  KEY `coupon_code` (`coupon_code`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;",
);

$update_array[27] = array(

    'update_type' => 'column',
    'column_name' => 'order_id',
    'check_sql' => 'SHOW columns FROM jam_commissions',

    'update_sql' => "ALTER TABLE  `jam_commissions` ADD  `order_id` varchar( 50 ) NOT NULL DEFAULT '';",
);

$update_array[28] = array(

    'update_type' => 'column',
    'column_name' => 'restrict_recur_commissions',
    'check_sql' => 'SHOW columns FROM jam_programs',

    'update_sql' => "ALTER TABLE  `jam_programs` ADD  `restrict_recur_commissions` INT( 10 ) NOT NULL DEFAULT '0';",
);

?>