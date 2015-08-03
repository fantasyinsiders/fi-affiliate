DROP TABLE IF EXISTS `jam_action_commissions`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_action_commissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `type` enum('flat','percent') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'flat',
  `action_commission_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action_commission_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `auto_approve` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_admin_users`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_admin_users` (
  `admin_id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inactive',
  `fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `lname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `apassword` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `primary_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_login_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_login_ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `admin_photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `alert_affiliate_signup` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `alert_affiliate_commission` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `permissions` text COLLATE utf8_unicode_ci NOT NULL,
  `rows_per_page` int(10) NOT NULL DEFAULT '25',
  `confirm_id` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
INSERT INTO `jam_admin_users` (`admin_id`, `status`, `fname`, `lname`, `username`, `apassword`, `primary_email`, `last_login_date`, `last_login_ip`, `admin_photo`, `alert_affiliate_signup`, `alert_affiliate_commission`, `permissions`, `rows_per_page`) VALUES
(1, 'active', '{{admin_fname}}', '{{admin_lname}}', '{{admin_username}}', '{{admin_password}}', '{{admin_email}}', '', '', '', '1', '1', '', 25);
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_article_ads`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_article_ads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `article_ad_title` text COLLATE utf8_unicode_ci NOT NULL,
  `article_ad_body` text COLLATE utf8_unicode_ci NOT NULL,
  `enable_redirect` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `redirect_custom_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_ad_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_banners`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_banners` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `enable_redirect` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `redirect_custom_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `banner_height` int(10) NOT NULL DEFAULT '0',
  `banner_width` int(10) NOT NULL DEFAULT '0',
  `use_external_image` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `banner_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `rotator_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `banner_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_email_ads`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_email_ads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_ad_title` text COLLATE utf8_unicode_ci NOT NULL,
  `email_ad_body` text COLLATE utf8_unicode_ci NOT NULL,
  `enable_redirect` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `redirect_custom_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email_ad_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_groups`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_groups` (
  `group_id` int(10) NOT NULL AUTO_INCREMENT,
  `tier` int(10) NOT NULL DEFAULT '1',
  `aff_group_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `aff_group_code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `aff_group_description` text COLLATE utf8_unicode_ci NOT NULL,
  `commission_type` enum('percent','flat') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'percent',
  `commission_level_1` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `commission_level_2` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `commission_level_3` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `commission_level_4` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `commission_level_5` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `commission_level_6` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `commission_level_7` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `commission_level_8` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `commission_level_9` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `commission_level_10` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `mailing_list_id` int(10) NOT NULL,
  `ppc_amount` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `cpm_amount` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
INSERT INTO `jam_affiliate_groups` (`group_id`, `tier`, `aff_group_name`, `aff_group_code`, `aff_group_description`, `commission_type`, `commission_level_1`, `commission_level_2`, `commission_level_3`, `commission_level_4`, `commission_level_5`, `commission_level_6`, `commission_level_7`, `commission_level_8`, `commission_level_9`, `commission_level_10`, `mailing_list_id`, `ppc_amount`, `cpm_amount`) VALUES
(1, 1, 'Default Affiliate Group', 'default', 'default affiliate group', 'percent', '0.10', '0', '0', '0', '0', '0', '0', '0', '0', '0', 0, '0', '0');
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_html_ads`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_html_ads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `html_ad_type` text COLLATE utf8_unicode_ci NOT NULL,
  `html_ad_body` text COLLATE utf8_unicode_ci NOT NULL,
  `enable_redirect` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `redirect_custom_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `html_ad_width` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `html_ad_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_invisilinks`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_invisilinks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `member_id` int(10) NOT NULL,
  `invisilink_url` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_lightbox_ads`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_lightbox_ads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `lightbox_ad_name` varchar(255) NOT NULL,
  `lightbox_ad_body` text NOT NULL,
  `enable_redirect` enum('0','1') NOT NULL,
  `redirect_custom_url` varchar(255) NOT NULL,
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  `program_id` int(10) NOT NULL,
  `lightbox_ad_width` varchar(10) NOT NULL,
  `lightbox_ad_height` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lightbox_ad_name` (`lightbox_ad_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_member_events`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_member_events` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `member_id` int(10) NOT NULL,
  `date` varchar(25) NOT NULL DEFAULT '',
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time NOT NULL DEFAULT '00:00:00',
  `member_event_title` varchar(255) NOT NULL DEFAULT '',
  `member_event_location` text NOT NULL,
  `member_event_description` text NOT NULL,
  `member_event_photo` varchar(255) NOT NULL,
  `restrict_group` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_page_peel_ads`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_page_peel_ads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `page_peel_ad_name` varchar(255) NOT NULL DEFAULT '',
  `page_peel_ad_small_image` varchar(255) NOT NULL DEFAULT '',
  `page_peel_ad_large_image` varchar(255) NOT NULL DEFAULT '',
  `enable_redirect` enum('0','1') NOT NULL DEFAULT '0',
  `redirect_custom_url` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  `program_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `page_peel_ad_name` (`page_peel_ad_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_payments`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_payments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `payment_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payment_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `payment_details` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_text_ads`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_text_ads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `text_ad_title` text COLLATE utf8_unicode_ci NOT NULL,
  `text_ad_body` text COLLATE utf8_unicode_ci NOT NULL,
  `enable_redirect` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `redirect_custom_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `text_ad_width` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `text_ad_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_text_links`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_text_links` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `text_link_title` text COLLATE utf8_unicode_ci NOT NULL,
  `enable_redirect` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `redirect_custom_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_viral_pdfs`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_viral_pdfs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `viral_pdf_title` text COLLATE utf8_unicode_ci NOT NULL,
  `viral_pdf_body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `enable_redirect` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `redirect_custom_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `viral_pdf_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_affiliate_viral_videos`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_affiliate_viral_videos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `viral_video_link` text COLLATE utf8_unicode_ci NOT NULL,
  `enable_redirect` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `redirect_custom_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `viral_video_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_commissions`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_commissions` (
  `comm_id` int(20) NOT NULL AUTO_INCREMENT,
  `member_id` int(20) unsigned NOT NULL DEFAULT '0',
  `program_id` int(10) NOT NULL,
  `invoice_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `comm_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `approved` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `date` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `commission_amount` decimal(14,2) DEFAULT NULL,
  `sale_amount` decimal(14,2) DEFAULT NULL,
  `commission_level` mediumint(3) unsigned NOT NULL DEFAULT '1',
  `referrer` text COLLATE utf8_unicode_ci NOT NULL,
  `trans_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ip_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date_paid` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `commission_notes` text COLLATE utf8_unicode_ci,
  `performance_paid` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `email_sent` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `tool_type` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `tool_id` int(25) NOT NULL DEFAULT '0',
  `payment_id` int(10) NOT NULL DEFAULT '0',
  `action_commission_id` int(10) NOT NULL DEFAULT '0',
  `product_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `tracking_id` int(10) NOT NULL,
  `customer_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_1` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_2` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_3` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_4` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_5` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_6` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_7` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_8` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_9` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_10` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_11` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_12` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_13` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_14` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_15` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_16` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_17` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_18` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_19` text COLLATE utf8_unicode_ci NOT NULL,
  `custom_commission_field_20` text COLLATE utf8_unicode_ci NOT NULL,
  `recur` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tracker` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `recurring_comm` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`comm_id`),
  KEY `member_id` (`member_id`),
  KEY `IDX_TRANS_ID` (`trans_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_content_articles`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_content_articles` (
  `article_id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `content_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `content_body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date_published` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `drip_date` int(10) NOT NULL DEFAULT '0',
  `modified_by` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `enable_affiliate_group_permissions` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `sort_order` int(10) NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_content_permissions`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_content_permissions` (
  `article_id` int(10) NOT NULL DEFAULT '0',
  `group_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_countries`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_countries` (
  `country_id` int(10) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country_iso_code_2` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country_iso_code_3` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ship_to` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=242 ;
{{{~~~}}}
INSERT INTO `jam_countries` (`country_id`, `country_name`, `country_iso_code_2`, `country_iso_code_3`, `ship_to`) VALUES
(1, 'Afghanistan', 'AF', 'AFG', '1'),
(2, 'Albania', 'AL', 'ALB', '1'),
(3, 'Algeria', 'DZ', 'DZA', '1'),
(4, 'American Samoa', 'AS', 'ASM', '1'),
(5, 'Andorra', 'AD', 'AND', '1'),
(6, 'Angola', 'AO', 'AGO', '1'),
(7, 'Anguilla', 'AI', 'AIA', '1'),
(8, 'Antarctica', 'AQ', 'ATA', '1'),
(9, 'Antigua and Barbuda', 'AG', 'ATG', '1'),
(10, 'Argentina', 'AR', 'ARG', '1'),
(11, 'Armenia', 'AM', 'ARM', '1'),
(12, 'Aruba', 'AW', 'ABW', '1'),
(13, 'Australia', 'AU', 'AUS', '1'),
(14, 'Austria', 'AT', 'AUT', '1'),
(15, 'Azerbaijan', 'AZ', 'AZE', '1'),
(16, 'Bahamas', 'BS', 'BHS', '1'),
(17, 'Bahrain', 'BH', 'BHR', '1'),
(18, 'Bangladesh', 'BD', 'BGD', '1'),
(19, 'Barbados', 'BB', 'BRB', '1'),
(20, 'Belarus', 'BY', 'BLR', '1'),
(21, 'Belgium', 'BE', 'BEL', '1'),
(22, 'Belize', 'BZ', 'BLZ', '1'),
(23, 'Benin', 'BJ', 'BEN', '1'),
(24, 'Bermuda', 'BM', 'BMU', '1'),
(25, 'Bhutan', 'BT', 'BTN', '1'),
(26, 'Bolivia', 'BO', 'BOL', '1'),
(27, 'Bosnia and Herzegowina', 'BA', 'BIH', '1'),
(28, 'Botswana', 'BW', 'BWA', '1'),
(29, 'Bouvet Island', 'BV', 'BVT', '1'),
(30, 'Brazil', 'BR', 'BRA', '1'),
(31, 'British Indian Ocean Territory', 'IO', 'IOT', '1'),
(32, 'Brunei Darussalam', 'BN', 'BRN', '1'),
(33, 'Bulgaria', 'BG', 'BGR', '1'),
(34, 'Burkina Faso', 'BF', 'BFA', '1'),
(35, 'Burundi', 'BI', 'BDI', '1'),
(36, 'Cambodia', 'KH', 'KHM', '1'),
(37, 'Cameroon', 'CM', 'CMR', '1'),
(38, 'Canada', 'CA', 'CAN', '1'),
(39, 'Cape Verde', 'CV', 'CPV', '1'),
(40, 'Cayman Islands', 'KY', 'CYM', '1'),
(41, 'Central African Republic', 'CF', 'CAF', '1'),
(42, 'Chad', 'TD', 'TCD', '1'),
(43, 'Chile', 'CL', 'CHL', '1'),
(44, 'China', 'CN', 'CHN', '1'),
(45, 'Christmas Island', 'CX', 'CXR', '1'),
(46, 'Cocos (Keeling) Islands', 'CC', 'CCK', '1'),
(47, 'Colombia', 'CO', 'COL', '1'),
(48, 'Comoros', 'KM', 'COM', '1'),
(49, 'Congo', 'CG', 'COG', '1'),
(50, 'Cook Islands', 'CK', 'COK', '1'),
(51, 'Costa Rica', 'CR', 'CRI', '1'),
(52, 'Cote D''Ivoire', 'CI', 'CIV', '1'),
(53, 'Croatia', 'HR', 'HRV', '1'),
(54, 'Cuba', 'CU', 'CUB', '1'),
(55, 'Cyprus', 'CY', 'CYP', '1'),
(56, 'Czech Republic', 'CZ', 'CZE', '1'),
(57, 'Denmark', 'DK', 'DNK', '1'),
(58, 'Djibouti', 'DJ', 'DJI', '1'),
(59, 'Dominica', 'DM', 'DMA', '1'),
(60, 'Dominican Republic', 'DO', 'DOM', '1'),
(61, 'East Timor', 'TP', 'TMP', '1'),
(62, 'Ecuador', 'EC', 'ECU', '1'),
(63, 'Egypt', 'EG', 'EGY', '1'),
(64, 'El Salvador', 'SV', 'SLV', '1'),
(65, 'Equatorial Guinea', 'GQ', 'GNQ', '1'),
(66, 'Eritrea', 'ER', 'ERI', '1'),
(67, 'Estonia', 'EE', 'EST', '1'),
(68, 'Ethiopia', 'ET', 'ETH', '1'),
(69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', '1'),
(70, 'Faroe Islands', 'FO', 'FRO', '1'),
(71, 'Fiji', 'FJ', 'FJI', '1'),
(72, 'Finland', 'FI', 'FIN', '1'),
(73, 'France', 'FR', 'FRA', '1'),
(74, 'France, Metropolitan', 'FX', 'FXX', '1'),
(75, 'French Guiana', 'GF', 'GUF', '1'),
(76, 'French Polynesia', 'PF', 'PYF', '1'),
(77, 'French Southern Territories', 'TF', 'ATF', '1'),
(78, 'Gabon', 'GA', 'GAB', '1'),
(79, 'Gambia', 'GM', 'GMB', '1'),
(80, 'Georgia', 'GE', 'GEO', '1'),
(81, 'Germany', 'DE', 'DEU', '1'),
(82, 'Ghana', 'GH', 'GHA', '1'),
(83, 'Gibraltar', 'GI', 'GIB', '1'),
(84, 'Greece', 'GR', 'GRC', '1'),
(85, 'Greenland', 'GL', 'GRL', '1'),
(86, 'Grenada', 'GD', 'GRD', '1'),
(87, 'Guadeloupe', 'GP', 'GLP', '1'),
(88, 'Guam', 'GU', 'GUM', '1'),
(89, 'Guatemala', 'GT', 'GTM', '1'),
(90, 'Guinea', 'GN', 'GIN', '1'),
(91, 'Guinea-bissau', 'GW', 'GNB', '1'),
(92, 'Guyana', 'GY', 'GUY', '1'),
(93, 'Haiti', 'HT', 'HTI', '1'),
(94, 'Heard and Mc Donald Islands', 'HM', 'HMD', '1'),
(95, 'Honduras', 'HN', 'HND', '1'),
(96, 'Hong Kong', 'HK', 'HKG', '1'),
(97, 'Hungary', 'HU', 'HUN', '1'),
(98, 'Iceland', 'IS', 'ISL', '1'),
(99, 'India', 'IN', 'IND', '1'),
(100, 'Indonesia', 'ID', 'IDN', '1'),
(101, 'Iran (Islamic Republic of)', 'IR', 'IRN', '1'),
(102, 'Iraq', 'IQ', 'IRQ', '1'),
(103, 'Ireland', 'IE', 'IRL', '1'),
(104, 'Israel', 'IL', 'ISR', '1'),
(105, 'Italy', 'IT', 'ITA', '1'),
(106, 'Jamaica', 'JM', 'JAM', '1'),
(107, 'Japan', 'JP', 'JPN', '1'),
(108, 'Jordan', 'JO', 'JOR', '1'),
(109, 'Kazakhstan', 'KZ', 'KAZ', '1'),
(110, 'Kenya', 'KE', 'KEN', '1'),
(111, 'Kiribati', 'KI', 'KIR', '1'),
(112, 'Korea, Democratic People''s Republic of', 'KP', 'PRK', '1'),
(113, 'Korea, Republic of', 'KR', 'KOR', '1'),
(114, 'Kuwait', 'KW', 'KWT', '1'),
(115, 'Kyrgyzstan', 'KG', 'KGZ', '1'),
(116, 'Lao People''s Democratic Republic', 'LA', 'LAO', '1'),
(117, 'Latvia', 'LV', 'LVA', '1'),
(118, 'Lebanon', 'LB', 'LBN', '1'),
(119, 'Lesotho', 'LS', 'LSO', '1'),
(120, 'Liberia', 'LR', 'LBR', '1'),
(121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', '1'),
(122, 'Liechtenstein', 'LI', 'LIE', '1'),
(123, 'Lithuania', 'LT', 'LTU', '1'),
(124, 'Luxembourg', 'LU', 'LUX', '1'),
(125, 'Macau', 'MO', 'MAC', '1'),
(126, 'Macedonia', 'MK', 'MKD', '1'),
(127, 'Madagascar', 'MG', 'MDG', '1'),
(128, 'Malawi', 'MW', 'MWI', '1'),
(129, 'Malaysia', 'MY', 'MYS', '1'),
(130, 'Maldives', 'MV', 'MDV', '1'),
(131, 'Mali', 'ML', 'MLI', '1'),
(132, 'Malta', 'MT', 'MLT', '1'),
(133, 'Marshall Islands', 'MH', 'MHL', '1'),
(134, 'Martinique', 'MQ', 'MTQ', '1'),
(135, 'Mauritania', 'MR', 'MRT', '1'),
(136, 'Mauritius', 'MU', 'MUS', '1'),
(137, 'Mayotte', 'YT', 'MYT', '1'),
(138, 'Mexico', 'MX', 'MEX', '1'),
(139, 'Micronesia, Federated States of', 'FM', 'FSM', '1'),
(140, 'Moldova, Republic of', 'MD', 'MDA', '1'),
(141, 'Monaco', 'MC', 'MCO', '1'),
(142, 'Mongolia', 'MN', 'MNG', '1'),
(143, 'Montserrat', 'MS', 'MSR', '1'),
(144, 'Morocco', 'MA', 'MAR', '1'),
(145, 'Mozambique', 'MZ', 'MOZ', '1'),
(146, 'Myanmar', 'MM', 'MMR', '1'),
(147, 'Namibia', 'NA', 'NAM', '1'),
(148, 'Nauru', 'NR', 'NRU', '1'),
(149, 'Nepal', 'NP', 'NPL', '1'),
(150, 'Netherlands', 'NL', 'NLD', '1'),
(151, 'Netherlands Antilles', 'AN', 'ANT', '1'),
(152, 'New Caledonia', 'NC', 'NCL', '1'),
(153, 'New Zealand', 'NZ', 'NZL', '1'),
(154, 'Nicaragua', 'NI', 'NIC', '1'),
(155, 'Niger', 'NE', 'NER', '1'),
(156, 'Nigeria', 'NG', 'NGA', '1'),
(157, 'Niue', 'NU', 'NIU', '1'),
(158, 'Norfolk Island', 'NF', 'NFK', '1'),
(159, 'Northern Mariana Islands', 'MP', 'MNP', '1'),
(160, 'Norway', 'NO', 'NOR', '1'),
(161, 'Oman', 'OM', 'OMN', '1'),
(162, 'Pakistan', 'PK', 'PAK', '1'),
(163, 'Palau', 'PW', 'PLW', '1'),
(164, 'Panama', 'PA', 'PAN', '1'),
(165, 'Papua New Guinea', 'PG', 'PNG', '1'),
(166, 'Paraguay', 'PY', 'PRY', '1'),
(167, 'Peru', 'PE', 'PER', '1'),
(168, 'Philippines', 'PH', 'PHL', '1'),
(169, 'Pitcairn', 'PN', 'PCN', '1'),
(170, 'Poland', 'PL', 'POL', '1'),
(171, 'Portugal', 'PT', 'PRT', '1'),
(172, 'Puerto Rico', 'PR', 'PRI', '1'),
(173, 'Qatar', 'QA', 'QAT', '1'),
(174, 'Reunion', 'RE', 'REU', '1'),
(175, 'Romania', 'RO', 'ROM', '1'),
(176, 'Russian Federation', 'RU', 'RUS', '1'),
(177, 'Rwanda', 'RW', 'RWA', '1'),
(178, 'Saint Kitts and Nevis', 'KN', 'KNA', '1'),
(179, 'Saint Lucia', 'LC', 'LCA', '1'),
(180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', '1'),
(181, 'Samoa', 'WS', 'WSM', '1'),
(182, 'San Marino', 'SM', 'SMR', '1'),
(183, 'Sao Tome and Principe', 'ST', 'STP', '1'),
(184, 'Saudi Arabia', 'SA', 'SAU', '1'),
(185, 'Senegal', 'SN', 'SEN', '1'),
(186, 'Seychelles', 'SC', 'SYC', '1'),
(187, 'Sierra Leone', 'SL', 'SLE', '1'),
(188, 'Singapore', 'SG', 'SGP', '1'),
(189, 'Slovakia (Slovak Republic)', 'SK', 'SVK', '1'),
(190, 'Slovenia', 'SI', 'SVN', '1'),
(191, 'Solomon Islands', 'SB', 'SLB', '1'),
(192, 'Somalia', 'SO', 'SOM', '1'),
(193, 'South Africa', 'ZA', 'ZAF', '1'),
(194, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', '1'),
(195, 'Spain', 'ES', 'ESP', '1'),
(196, 'Sri Lanka', 'LK', 'LKA', '1'),
(197, 'St. Helena', 'SH', 'SHN', '1'),
(198, 'St. Pierre and Miquelon', 'PM', 'SPM', '1'),
(199, 'Sudan', 'SD', 'SDN', '1'),
(200, 'Suriname', 'SR', 'SUR', '1'),
(201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', '1'),
(202, 'Swaziland', 'SZ', 'SWZ', '1'),
(203, 'Sweden', 'SE', 'SWE', '1'),
(204, 'Switzerland', 'CH', 'CHE', '1'),
(205, 'Syrian Arab Republic', 'SY', 'SYR', '1'),
(206, 'Taiwan', 'TW', 'TWN', '1'),
(207, 'Tajikistan', 'TJ', 'TJK', '1'),
(208, 'Tanzania, United Republic of', 'TZ', 'TZA', '1'),
(209, 'Thailand', 'TH', 'THA', '1'),
(210, 'Togo', 'TG', 'TGO', '1'),
(211, 'Tokelau', 'TK', 'TKL', '1'),
(212, 'Tonga', 'TO', 'TON', '1'),
(213, 'Trinidad and Tobago', 'TT', 'TTO', '1'),
(214, 'Tunisia', 'TN', 'TUN', '1'),
(215, 'Turkey', 'TR', 'TUR', '1'),
(216, 'Turkmenistan', 'TM', 'TKM', '1'),
(217, 'Turks and Caicos Islands', 'TC', 'TCA', '1'),
(218, 'Tuvalu', 'TV', 'TUV', '1'),
(219, 'Uganda', 'UG', 'UGA', '1'),
(220, 'Ukraine', 'UA', 'UKR', '1'),
(221, 'United Arab Emirates', 'AE', 'ARE', '1'),
(222, 'United Kingdom', 'GB', 'GBR', '1'),
(223, 'United States', 'US', 'USA', '1'),
(224, 'United States Minor Outlying Islands', 'UM', 'UMI', '1'),
(225, 'Uruguay', 'UY', 'URY', '1'),
(226, 'Uzbekistan', 'UZ', 'UZB', '1'),
(227, 'Vanuatu', 'VU', 'VUT', '1'),
(228, 'Vatican City State (Holy See)', 'VA', 'VAT', '1'),
(229, 'Venezuela', 'VE', 'VEN', '1'),
(230, 'Viet Nam', 'VN', 'VNM', '1'),
(231, 'Virgin Islands (British)', 'VG', 'VGB', '1'),
(232, 'Virgin Islands (U.S.)', 'VI', 'VIR', '1'),
(233, 'Wallis and Futuna Islands', 'WF', 'WLF', '1'),
(234, 'Western Sahara', 'EH', 'ESH', '1'),
(235, 'Yemen', 'YE', 'YEM', '1'),
(236, 'Yugoslavia', 'YU', 'YUG', '1'),
(237, 'Zaire', 'ZR', 'ZAR', '1'),
(238, 'Zambia', 'ZM', 'ZMB', '1'),
(239, 'Zimbabwe', 'ZW', 'ZWE', '1');
{{{~~~}}}
DROP TABLE IF EXISTS `jam_coupons`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_coupons` (
  `coupon_id` int(10) NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `coupon_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `member_id` int(10) NOT NULL,
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `type` enum('flat','percent') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'flat',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `use_program_comms` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`coupon_id`),
  KEY `coupon_code` (`coupon_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_currencies`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_currencies` (
  `currency_id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `code` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `symbol_left` varchar(24) COLLATE utf8_unicode_ci DEFAULT NULL,
  `symbol_right` varchar(24) COLLATE utf8_unicode_ci DEFAULT NULL,
  `decimal_point` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thousands_point` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `decimal_places` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` float(13,2) DEFAULT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;
{{{~~~}}}
INSERT INTO `jam_currencies` (`currency_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_point`, `thousands_point`, `decimal_places`, `value`) VALUES
(1, 'US Dollar', 'USD', '$', '', '.', ',', '2', 1.00),
(2, 'Euro', 'EUR', '&#8364;', '', '.', ',', '2', 0.76),
(3, 'GB Pound', 'GBP', '&pound;', '', '.', ',', '2', 0.62),
(4, 'Canadian Dollar', 'CAD', '$', '', '.', ',', '2', 0.98),
(5, 'Australian Dollar', 'AUD', '$', '', '.', ',', '2', 0.95),
(6, 'Philippine Peso', 'PHP', 'Php', '', '.', ',', '2', 40.82),
(7, 'Singapore Dollar', 'SGD', '$', '', '.', ',', '2', 1.22),
(8, 'Brazil Real', 'BRL', 'R$', '', '.', ',', '2', 2.03),
(9, 'Mexican Peso', 'MXN', '$', '', '.', ',', '2', 12.73),
(10, 'Indian Rupee', 'INR', 'INR', '', '.', ',', '2', 54.67),
(11, 'Japanese Yen', 'JPY', '&yen;', '', '.', ',', '0', 88.14),
(12, 'Chinese Yuan', 'CNY', '&yen;', '', '.', ',', '2', 6.29),
(13, 'Indonesia Rupiah', 'IDR', 'Rp', '', '.', ',', '2', 9587.73),
(14, 'Hong Kong Dollar', 'HKD', '$', '', '.', ',', '2', 7.75);
{{{~~~}}}
DROP TABLE IF EXISTS `jam_downloads`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_downloads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `download_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `download_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `download_location_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `download_location_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `download_location_3` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `download_location_4` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `download_location_5` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_email_archive`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_email_archive` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `type` enum('admin','member') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'admin',
  `send_date` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `from_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `from_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `recipient_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `recipient_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `recipient_group` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `bcc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `html_body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `text_body` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_email_bounces`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_email_bounces` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_email_follow_ups`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_email_follow_ups` (
  `follow_up_id` int(10) NOT NULL AUTO_INCREMENT,
  `mailing_list_id` int(10) NOT NULL DEFAULT '0',
  `sequence` int(10) NOT NULL DEFAULT '0',
  `days_apart` int(10) NOT NULL DEFAULT '0',
  `follow_up_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `from_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `from_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_type` enum('text','html') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'html',
  `email_subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `html_message` text COLLATE utf8_unicode_ci NOT NULL,
  `text_message` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`follow_up_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_email_mailing_lists`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_email_mailing_lists` (
  `mailing_list_id` int(10) NOT NULL AUTO_INCREMENT,
  `mailing_list_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`mailing_list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
INSERT INTO `jam_email_mailing_lists` (`mailing_list_id`, `mailing_list_name`) VALUES
(1, 'Default');
{{{~~~}}}
DROP TABLE IF EXISTS `jam_email_mailing_list_members`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_email_mailing_list_members` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mailing_list_id` int(10) NOT NULL DEFAULT '0',
  `member_id` int(10) NOT NULL DEFAULT '0',
  `sequence_id` int(10) NOT NULL DEFAULT '0',
  `send_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`,`mailing_list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_email_queue`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_email_queue` (
  `eqid` bigint(20) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `type` enum('admin','member') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'member',
  `email_type` enum('text','html') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `send_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sender_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sender_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `recipient_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `recipient_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `recipient_cc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `recipient_bcc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `html_body` text COLLATE utf8_unicode_ci NOT NULL,
  `text_body` text COLLATE utf8_unicode_ci NOT NULL,
  `group` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `processing` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`eqid`),
  KEY `sender_email` (`sender_email`),
  KEY `recipient_email` (`recipient_email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_email_templates`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_email_templates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL,
  `email_template_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_template_html` enum('text','html') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `email_template_group` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_template_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_template_from_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_template_from_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_template_cc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_template_bcc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_template_subject` text COLLATE utf8_unicode_ci NOT NULL,
  `email_template_body_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `email_template_body_html` longtext COLLATE utf8_unicode_ci NOT NULL,
  `email_template_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
INSERT INTO `jam_email_templates` (`id`, `program_id`, `email_template_type`, `email_template_html`, `email_template_group`, `email_template_name`, `email_template_from_name`, `email_template_from_email`, `email_template_cc`, `email_template_bcc`, `email_template_subject`, `email_template_body_text`, `email_template_body_html`, `email_template_description`) VALUES
(1, 1, 'admin', 'html', '', 'admin_reset_password_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'Admin Reset Password', 'Hello {fname},\n\nSomeone (maybe you) asked to get the password reset for your admin account. If it wasn''t you, please disregard this email.  If so, please click the link below to reset your admin password:\n\n{admin_reset_password_link}\n\nlogin URL: {admin_login_url}', '\n<p><span style="font-weight: bold; font-size: 10pt; font-family: Arial;">Hello {fname},</span></p>\n<p><span style="font-family: Arial;">Someone (maybe you) asked to get the password reset for your admin account. If it wasn''t you, please disregard this email. &nbsp;If so, please click the link below to reset your admin password:</span></p>\n<p>{admin_reset_password_link}</p>\n<p><span style="font-size: 10pt; font-family: Arial;">login URL: </span><span style="font-size: 10pt; font-family: Arial;"><a href="{admin_login_url}">{admin_login_url}</a></span></p> ', 'Sends the requesting admin a new reset password email'),
(2, 1, 'admin', 'html', '', 'admin_affiliate_commission_generated_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'A New Commission Has Been Generated', 'Hello {fname},\n\nA new commission has been generated by one of your users:\n\nUsername: {member_username}\nCommission Amount: {commission_amount}\nCommission Date: {current_date}\n\nLogin to the admin area for more info:\n\n{admin_login_url}\n\n{site_name}\n', '<p>Hello {fname},<br />\n<br />\nA new commission has been generated by one of your users:<br />\n<br />\nUsername: {member_username}<br />\nCommission Amount: {commission_amount}<br />\nCommission Date: {current_date}<br />\n<br />\nLogin to the admin area for more info:<br />\n<br />\n{admin_login_url}<br />\n<br />\n{site_name}</p>', 'Sends an email notification to the admin when a new commission is generated'),
(3, 1, 'admin', 'html', '', 'admin_alert_new_signup_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'A new user has signed up!', 'hello {fname},\n\na new user has been added to your site:\n\nFull Name: {member_name}\nUsername: {member_username}\nSignup IP: {signup_ip}\nSignup Date: {current_time}\n\nPlease view full details in the admin area:\n\n{admin_login_url}\n\n{site_name}', '<p>hello {fname},<br />\n<br />\na new user has been added to your site:<br />\n<br />\nFull Name: {member_name}<br />\nUsername: {member_username}<br />\nSignup IP: {signup_ip}<br />\nSignup Date: {current_time}<br />\n<br />\nPlease view full details in the admin area:<br />\n<br />\n{admin_login_url}<br />\n<br />\n{site_name}</p>', 'Sends an email notification to the admin when a new user has signed up and has been added to the database'),
(4, 1, 'admin', 'html', '', 'admin_failed_login_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'admin failed login', 'Someone, maybe you, tried to login to your Affiliate Manager admin area unsuccessfully.\n\nDetails are:\n\nlogin url: {admin_login_url}\nusername used: {username}\npassword used: {password}\ndate and time of login: {date}\nip address: {ip_address}', '<p>Someone, maybe you, tried to login to your Affiliate Manager admin area unsuccessfully.</p>\n<p>Details are:<br />\n login url: {admin_login_url} <br />\n username used: {username}<br />\n password used: {password}<br />\n date and time of login: {date}<br />\n ip address: {ip_address}</p>', 'Send an email alert when someone logs into the admin area unsuccessfully'),
(5, 1, 'member', 'html', '', 'member_login_details_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'Account Login Details', 'Hello {fname},\n\nyour login details for {site_name}:\n\nusername: {primary_email}\npassword: {password}\n\nlogin URL: {login_url}', '<p>Hello {fname},</p>\n<p>Here are your login details for {site_name}:</p>\n<p>&nbsp;</p>\n<p>username: {primary_email}<br>\npassword: {password}</p>\n<p>login URL: <a href="{login_url}">{login_url}</a></p>', 'General member welcome email and login details'),
(6, 1, 'member', 'html', '', 'member_affiliate_performance_group_upgrade_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'Your group membership has been upgraded', 'Hello {fname},\n\nYour affiliate group membership has been upgraded.\n\nYou''ve achieved this through your performance.\n\nYour new group is {upgraded_affiliate_group}.\n\nThanks!\n{site_name}', '<p>Hello {fname},<br />\n<br />\nYour affiliate group membership has been upgraded.<br />\n<br />\nYou''ve achieved this through your performance.<br />\n<br />\nYour new group is {upgraded_affiliate_group}.<br />\n<br />\nThanks!<br />\n{site_name}</p>', 'Sends an email notificaton to the user telling him/her that her affiliate group has been upgraded due to her performance.'),
(7, 1, 'member', 'html', '', 'member_affiliate_performance_bonus_amount_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'performance bonus awarded', 'Hello {fname},\n\nCongratulations!  You''ve earned a performance bonus commission for all your affiliate referrals!\n\nBonus amount: {bonus_amount}\n\n\nThanks again!\n\n{site_name}', '<p>Hello {fname},<br />\n<br />\nCongratulations!&nbsp; You''ve earned a performance bonus commission for all your affiliate referrals!<br />\n<br />\nBonus amount: {bonus_amount}<br />\n<br />\n<br />\nThanks again!<br />\n<br />\n{site_name}</p>', 'Sends an email notificaton to the user telling him/her that she has received a performance bonus commission'),
(8, 1, 'member', 'html', '', 'member_affiliate_commission_generated_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'New Commission Generated', 'Hello {fname},\n\nCongratulations!  You''ve earned a referral commission!\n\nCommission Amount: {commission_amount}\nCommission Date: {current_date}\n\nLogin to check your affiliate stats:\n{login_url}\n\n{site_name}\n', '<p>Hello {fname},<br />\n<br />\nCongratulations!&nbsp; You''ve earned a referral commission!<br />\n<br />\nCommission Amount: {commission_amount}<br />\nCommission Date: {current_date}<br />\n<br />\nLogin to check your affiliate stats:<br />\n{login_url}<br />\n<br />\n{site_name}<br />\n&nbsp;</p>', 'Sends an email notification to the user to let him know that he has generated a new commission'),
(9, 1, 'member', 'html', '', 'member_affiliate_payment_sent_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'Your Affiliate Payment has been sent', 'Hello {fname},\n\nWe''ve sent your affiliate payment.\n\nthe amount you''ve made is {payment_amount}\n\n{affiliate_note}\n\nThanks again for being a great affiliate!\n\n{site_name}\n{login_url}', '<p>Hello {fname},<br />\n<br />\nWe''ve sent your affiliate payment.<br />\n<br />\nthe amount you''ve made is {payment_amount}<br />\n<br />\n{affiliate_note}<br />\n<br />\nThanks again for being a great affiliate!<br />\n<br />\n{site_name}<br />\n{login_url}</p>', 'Sends an email notification to the user letting them know that their affiliate payment has been sent'),
(10, 1, 'member', 'html', '', 'member_reset_password_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'your password has been reset', 'Hello {fname},\n\nhere is your new password: {new_password}\n\nlogin URL: {login_url}\n\n\n{site_name}\n\n{login_url}\n', '<p>Hello {fname},<br />\n<br />\nhere is your new password: {new_password}<br />\n<br />\nlogin URL: {login_url}</p>\n<p>{site_name}</p>\n<p>{login_url}</p>', 'Sends an email notification to the user that his/her password has been reset.'),
(11, 1, 'member', 'html', '', 'member_email_confirmation_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'Account Confirmation Email', 'Hello {fname},\n\nplease click on the following link to confirm your account with us:\n\n{confirm_link}\n\n{site_name}\n{login_url}', '<p>Hello {fname},<br />\n<br />\nplease click on the following link to confirm your account with us:<br />\n<br />\n<a href="{confirm_link}">click here to confirm</a><br />\n<br />\n{site_name}<br />\n{login_url}</p>', 'Sends an email notification to the user to confirm his / her email account'),
(12, 1, 'member', 'html', '', 'member_affiliate_send_downline_email', '{{base_domain_name}}', '{{system_email}}', '', '', 'Message from your sponsor', 'Hello {downline_member_name},\n\nYour sponsor, {downline_sponsor_name} has sent you a message:\n\n{downline_message_text} \n\n{downline_sponsor_name}\n{downline_sponsor_email}\n{downline_sponsor_affiliate_link}\n\n\n{site_name}\n{login_url}\n\n', '<p>Hello {downline_member_name},<br />\n<br />\nYour sponsor, {downline_sponsor_name} has sent you a message:<br />\n<br />\n{downline_message_html} <br />\n<br />\n{downline_sponsor_name}<br />\n{downline_sponsor_email}<br />\n{downline_sponsor_affiliate_link}<br />\n<br />\n<br />\n{site_name}<br />\n{login_url}</p>', 'Sends an email to the downline of the user'),
(13, 1, 'member', 'html', '', 'member_affiliate_downline_signup', '{{base_domain_name}}', '{{system_email}}', '', '', 'You just referred someone!', 'Hello {fname},\n\nYou have just referred someone in your downline!\n\n{downline_name}\n{downline_email}\n', '<p>Hello {fname},<br />\n<br />\nYou have just referred someone in your downline!<br />\n<br />\n{downline_name}<br />\n{downline_email}<br />\n&nbsp;</p>', 'Notifies the user that someone signed up in their downline'),
(14, 1, 'member', 'html', '', 'member_affiliate_marketing_approval_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'Affiliate Registration Approval', 'Hello {fname},\n\nYour affiliate registration has been approved!\n\n{{base_domain_name}}\n{{base_domain_name}}', '<p>Hello {fname},<br />\n<br />\nYour affiliate registration has been approved!<br />\n<br />{{base_domain_name}}<br />\n{{base_domain_name}}</p>', 'Notifies the user that their affiliate account has been approved'),
(15, 1, 'member', 'html', '', 'admin_affiliate_marketing_activation_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'Affiliate Activation Request', 'Hello,\n\n{member_name} is requesting that you activate his affiliate account.\n\nPlease login to your admin area to confirm\n\n{{base_domain_name}}\n{{base_domain_name}}', '<p>Hello,<br />\n<br />\n{member_name} is requesting that you activate his affiliate account.<br />\n<br />Please login to your admin area to confirm<br /><br />{{base_domain_name}}<br />\n{{base_domain_name}}</p>', 'Notifies the admin to approve an affiliate activation request'),
(16, 1, 'member', 'html', '', 'member_affiliate_program_confirm_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'Please confirm your affiliate program account', 'Hello {fname},\n\nPlease click on the link below to confirm your account access to our {program_name}\n\n{confirm_link}', '<p>Hello {fname},</p>\n<p>Please click on the link below to confirm your account access to our {program_name}</p>\n<p>{confirm_link}</p>', 'Sends a confirmation to the user for affiliate program access. only applies if you have showcase multiple programs disabled'),
(17, 1, 'member', 'html', '', 'member_affiliate_commission_stats_template', '{{base_domain_name}}', '{{system_email}}', '', '', 'Your Affiliate Commission Stats Report', 'Hello {fname},\n\nHere is your current affiliate commissions stats:\n\n{current_month} {current_year}\nUnpaid Commissions: {current_month_unpaid_commissions}\n\nTotal Commissions Made: \nUnpaid Commissions: {total_unpaid_commissions}\nPaid Commissions: {total_paid_commissions}\n\nYou can login to our members area to view all of your stats in more detail:\n\n{login_url}', '<p>Hello {fname},<br />\n <br />\n Here is your current affiliate commissions stats:</p>\n<p>{current_month} {current_year}<br />\n Unpaid Commissions: {current_month_unpaid_commissions}<br />\n </p>\n<p>Total Commissions Made:<br />\n Unpaid Commissions: {total_unpaid_commissions}<br />\n Paid Commissions: {total_paid_commissions}</p>\n<p>You can login to our members area to view all of your stats in more detail:</p>\n<p>{login_url}</p>', 'Send an email notification to your affiliate showing her current affiliate commissions stats');
{{{~~~}}}
DROP TABLE IF EXISTS `jam_faq_articles`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_faq_articles` (
  `article_id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `content_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `content_body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date_published` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `modified_by` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` int(10) NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
INSERT INTO `jam_faq_articles` (`article_id`, `program_id`, `status`, `content_title`, `content_body`, `date_published`, `modified_by`, `sort_order`) VALUES
(1, 1, '1', 'How can I Register for your Affiliate Program?', 'All you have to do is click on the ''Create Account'' Link on our main affiliate home page and fill in your details. &nbsp;Once you confirm your account, you''ll get immediate access to our affiliate members area and get your unique affiliate ID.', '{{date}}', '{{admin_username}}', 0),
(2, 1, '1', 'Do I Get My Own Unique Affiliate URL?', '<p>Yes, you get your very own unique affiliate link that you can immediately use on all of your promotions. Add it to your website, outgoing emails, or even your Facebook and LinkedIn profiles. &nbsp;</p>\n<p>Each and every click that is generated from your unique affiliate link will be tracked by our affiliate marketing system. &nbsp;You''ll be able to view all of your traffic in the members area at any time.</p>', '{{date}}', '{{admin_username}}', 0),
(3, 1, '1', 'Do You Provide any Marketing Materials or Tools?', 'Yes. Once yo sign up, you''ll get access to a variety of copy and paste marketing tools that you can use immediately to start generate commissions. &nbsp;Each of those marketing tools will have your own unique affiliate link embedded in it so that your referral traffic will be tracked by our affiliate system.', '{{date}}', '{{admin_username}}', 0),
(4, 1, '1', 'Can I View My Commissions and Referrals?', 'Yes you can. &nbsp;You will get complete access to all of your commissions and referral traffic in our members area. &nbsp; You will also be able to view your performance on a daily and monthly basis with full reporting tools available. &nbsp;Signup today!', '{{date}}', '{{admin_username}}', 0),
(5, 1, '1', 'How Do I Get Paid My Commissions?', 'You''ll get paid whenever you reached the set threshold amount for commissions set by our system. &nbsp;Once that happens we''ll send you payment immediately via the payment method set by our affiliate marketing program.', '{{date}}', '{{admin_username}}', 0),
(6, 1, '1', 'Can I Use My Unique Affiliate Link for Offline Promotion?', 'Yes you can. &nbsp;You can use your affiliate link to promote our products and services both online and offline, provided it is within our accepted affiliate marketing terms. &nbsp;Things such as offline flyers, cards, or mailers can be some ideas for offline marketing campaigns that you can use your unique affiliate code for.', '{{date}}', '{{admin_username}}', 0);
{{{~~~}}}
DROP TABLE IF EXISTS `jam_impressions`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_impressions` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `date` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `member_id` int(25) NOT NULL DEFAULT '0',
  `program_id` int(10) NOT NULL,
  `tool_type` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `tool_id` int(25) NOT NULL DEFAULT '0',
  `referrer` text COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_agent` text COLLATE utf8_unicode_ci NOT NULL,
  `os` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `browser` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `isp` text COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `country_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `country_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `coordinates` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `performance_paid` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_integration_profiles`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_integration_profiles` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
INSERT INTO `jam_integration_profiles` (`id`, `name`, `program_id`, `product_code`, `description`, `amount`, `trans_id`, `tracking_code`, `invoice_id`, `customer_name`, `first_name`, `last_name`, `custom_field_1`, `custom_field_2`, `custom_field_3`, `custom_field_4`, `custom_field_5`, `custom_field_6`, `custom_field_7`, `custom_field_8`, `custom_field_9`, `custom_field_10`, `custom_field_11`, `custom_field_12`, `custom_field_13`, `custom_field_14`, `custom_field_15`, `custom_field_16`, `custom_field_17`, `custom_field_18`, `custom_field_19`, `custom_field_20`, `lf_data`) VALUES
(1, 'paypal', '1', '', 'Paypal variables for use when posting to JAM via IPN', 'mc_gross', 'txn_id', 'custom', '', '', 'first_name', 'last_name', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'payer_email');
{{{~~~}}}
DROP TABLE IF EXISTS `jam_languages`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_languages` (
  `language_id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `code` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
INSERT INTO `jam_languages` (`language_id`, `status`, `name`, `code`, `image`) VALUES
(1, '1', 'english', 'US', 'us'),
(2, '0', 'german', 'DE', 'de'),
(3, '0', 'portuguese', 'PT', 'pt'),
(4, '0', 'french', 'FR', 'fr'),
(5, '0', 'spanish', 'ES', 'es');

{{{~~~}}}
DROP TABLE IF EXISTS `jam_layout_menus`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_layout_menus` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL,
  `menu_status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `menu_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `menu_parent` int(10) NOT NULL DEFAULT '0',
  `menu_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `menu_sort_order` int(10) NOT NULL DEFAULT '0',
  `menu_options` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `menu_parent` (`menu_parent`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
INSERT INTO `jam_layout_menus` (`id`, `program_id`, `menu_status`, `menu_name`, `menu_parent`, `menu_url`, `menu_sort_order`, `menu_options`) VALUES
(1, 1, '1', 'profile', 0, '{members_details}', 5, ''),
(2, 1, '1', 'content', 0, '{members_content}', 6, ''),
(3, 1, '1', 'dashboard', 0, '{members_home}', 1, ''),
(4, 1, '1', 'marketing', 0, '{members_marketing}', 2, ''),
(5, 1, '1', 'view_downline', 4, '{members_downline}', 8, ''),
(6, 1, '1', 'commissions', 0, '{members_commissions}', 4, ''),
(7, 1, '1', 'affiliate_payments', 6, '{members_payments}', 3, ''),
(8, 1, '1', 'email_downline', 4, '{members_email_downline}', 7, '');
{{{~~~}}}
DROP TABLE IF EXISTS `jam_members`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_members` (
  `member_id` int(10) NOT NULL AUTO_INCREMENT,
  `sponsor_id` int(10) NOT NULL DEFAULT '0',
  `original_sponsor_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `fname` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `lname` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `billing_address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `billing_address_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `billing_city` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `billing_state` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `billing_country` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `billing_postal_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payment_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payment_address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payment_address_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payment_city` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payment_state` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payment_country` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payment_postal_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `home_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `work_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mobile_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fax` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payment_preference_amount` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `primary_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `facebook_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `facebook_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `twitter_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `linkedin_id` text COLLATE utf8_unicode_ci NOT NULL,
  `myspace_id` text COLLATE utf8_unicode_ci NOT NULL,
  `paypal_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `moneybookers_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payza_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `coinbase_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dwolla_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `custom_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `bank_transfer` text COLLATE utf8_unicode_ci NOT NULL,
  `enable_custom_url` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `custom_url_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `program_custom_field_1` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_2` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_3` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_4` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_5` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_6` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_7` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_8` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_9` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_10` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_11` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_12` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_13` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_14` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_15` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_16` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_17` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_18` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_19` text COLLATE utf8_unicode_ci NOT NULL,
  `program_custom_field_20` text COLLATE utf8_unicode_ci NOT NULL,
  `alert_downline_signup` enum('0','1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '2',
  `alert_new_commission` enum('0','1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '2',
  `alert_payment_sent` enum('0','1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '2',
  `allow_downline_view` enum('0','1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '2',
  `allow_downline_email` enum('0','1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '2',
  `view_hidden_programs` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `last_login_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_login_ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `signup_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `updated_on` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `updated_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `login_status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `confirm_id` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `performance_paid` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`),
  KEY `JROX_MEMBERS_NAME` (`fname`,`username`,`sponsor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_members_groups`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_members_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_members_photos`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_members_photos` (
  `photo_id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `photo_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `raw_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file_ext` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `original_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_resized` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`photo_id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_members_programs`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_members_programs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL,
  `program_id` int(10) NOT NULL,
  `confirm_id` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_member_downloads`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_member_downloads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `filepath` text NOT NULL,
  `date_available` varchar(25) NOT NULL DEFAULT '0',
  `date_expires` varchar(25) NOT NULL DEFAULT '0',
  `group_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_modules`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_modules` (
  `module_id` int(10) NOT NULL AUTO_INCREMENT,
  `module_status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `module_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `module_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `module_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `module_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `window_height_size` int(10) NOT NULL DEFAULT '400',
  `window_width_size` int(10) NOT NULL DEFAULT '400',
  `module_sort_order` int(10) NOT NULL DEFAULT '1',
  `can_deactivate` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
INSERT INTO `jam_modules` (`module_id`, `module_status`, `module_type`, `module_name`, `module_description`, `module_file_name`, `window_height_size`, `window_width_size`, `module_sort_order`, `can_deactivate`) VALUES
(1, '1', 'affiliate_marketing', 'text links', 'affiliate text links', 'text_links', 250, 400, 1, '1'),
(2, '1', 'affiliate_marketing', 'Banners', 'affiliate banners', 'banners', 300, 500, 1, '1'),
(3, '1', 'affiliate_marketing', 'Text Ads', 'affiliate text ads', 'text_ads', 500, 700, 1, '1'),
(4, '0', 'affiliate_marketing', 'html ads', 'affiliate html ads', 'html_ads', 500, 700, 1, '1'),
(5, '0', 'affiliate_marketing', 'viral PDFs', 'affiliate viral PDFs', 'viral_pdfs', 340, 500, 1, '1'),
(6, '1', 'affiliate_marketing', 'article ads', 'affiliate article ads', 'article_ads', 250, 500, 1, '1'),
(7, '1', 'affiliate_marketing', 'email ads', 'affiliate email ads', 'email_ads', 250, 500, 1, '1'),
(8, '0', 'affiliate_marketing', 'viral videos', 'affiliate viral videos', 'viral_videos', 250, 500, 1, '1'),
(9, '1', 'affiliate_payment', 'Paypal Mass Payment', 'Generate Your Paypal Mass Payment File in a Few Clicks. You can also pay affiliates individually via Paypal Directly', 'paypal_mass_payment', 400, 400, 1, '1'),
(10, '1', 'affiliate_payment', 'Print Affiliate Invoice', 'print invoice for affiliate payment', 'print_invoice', 400, 400, 1, '0'),
(11, '1', 'affiliate_payment', 'Payza Mass Payment', 'Payza Mass Payment File', 'payza_mass_payment', 400, 400, 1, '1'),
(12, '1', 'affiliate_payment', 'Moneybookers Mass Payment', 'Moneybookers Mass Payment File', 'moneybookers_mass_payment', 400, 400, 1, '1'),
(13, '1', 'affiliate_payment', 'Print Affiliate Checks', 'print checks for affiliate payment', 'print_checks', 400, 400, 1, '1'),
(14, '1', 'data_export', 'Export Members', 'Download an Export File of Members', 'members', 350, 400, 1, '1'),
(15, '1', 'data_export', 'Export Commissions', 'Download an Export File of Commissions', 'commissions', 350, 400, 1, '1'),
(16, '1', 'data_export', 'Export Affiliate Payments', 'Download an Export File of Affiliate Payments', 'affiliate_payments', 350, 400, 1, '1'),
(17, '0', 'data_import', 'JAM version 1 Database Import', 'Import of JAM version 1 Database', 'jam', 600, 600, 1, '1'),
(18, '1', 'data_import', 'Member Import', 'Import Members via Tab Separated File', 'members', 400, 400, 1, '1'),
(19, '1', 'member_reporting', 'Your Monthly Referral Commission Stats', 'Monthly Commission Stats for the Member', 'month_commission_stats', 500, 500, 1, '1'),
(20, '1', 'member_reporting', 'Your Monthly Referral Sales Stats', 'Monthly Sales Stats for the Member', 'month_sales_stats', 500, 500, 1, '1'),
(21, '1', 'member_reporting', 'Your Monthly Referral Click Stats', 'Monthly Click Stats for the Member', 'month_click_stats', 500, 500, 1, '1'),
(22, '1', 'member_reporting', 'Your Yearly Referral Commission Stats', 'Yearly Commission Stats for the Member', 'year_commission_stats', 500, 500, 1, '1'),
(23, '1', 'member_reporting', 'Your Yearly  Referral Sales Stats', 'Yearly Sales Stats for the Member', 'year_sales_stats', 500, 500, 1, '1'),
(24, '1', 'member_reporting', 'Your Yearly Referral Click Stats', 'Yearly Click Stats for the Member', 'year_click_stats', 500, 500, 1, '1'),
(25, '1', 'stats_reporting', 'Monthly Sales Stats', 'Sales Stats for  Month', 'month_sales_stats', 400, 400, 1, '1'),
(26, '1', 'stats_reporting', 'Monthly Commission Stats', 'Commission Stats for  Month', 'month_commission_stats', 400, 400, 1, '1'),
(27, '1', 'stats_reporting', 'Yearly Sales Stats', 'Sales Stats for Year', 'year_sales_stats', 400, 400, 1, '1'),
(28, '1', 'stats_reporting', 'Yearly Commission Stats', 'Commission Stats for Year', 'year_commissions_stats', 400, 400, 1, '1'),
(29, '1', 'stats_reporting', 'Yearly Affiliate Clicks Stats', 'Affiliate Click Stats for the Year', 'year_affiliate_clicks_stats', 400, 400, 1, '1'),
(30, '1', 'stats_reporting', 'Monthly Affiliate Click Stats', 'Affiliate Click Stats for the Month', 'month_affiliate_clicks_stats', 400, 400, 1, '1'),
(31, '1', 'stats_reporting', 'Top Affiliates By Commissions', 'Top Affiliate Earners Per Month', 'month_top_affiliate_commissions', 400, 400, 1, '1'),
(32, '1', 'stats_reporting', 'Top Affiliates By Sales', 'Top Affiliate Earners By Sales Per Month', 'month_top_affiliate_sales', 400, 400, 1, '1'),
(33, '1', 'stats_reporting', 'Top Affiliates By Clicks', 'Top Affiliate Earners By Clicks Per Month', 'month_top_affiliate_clicks', 400, 400, 1, '1'),
(34, '1', 'stats_reporting', 'Affiliate Clickthrough Traffic', 'View Affiliate Clicks', 'affiliate_click_traffic', 400, 400, 1, '0'),
(35, '1', 'stats_reporting', 'User Registrations', 'shows the number of users who registered on a daily basis', 'month_user_registrations', 400, 400, 1, '1'),
(36, '0', 'affiliate_marketing', 'Light Box Ads', 'Light Box Ads', 'lightbox_ads', 500, 500, 1, '1'),
(37, '0', 'affiliate_marketing', 'Page Peel Ads', 'Page Peel Ads', 'page_peel_ads', 500, 500, 1, '1'),
(38, '0', 'affiliate_marketing', 'Member Events', 'Member Events', 'member_events', 500, 500, 1, '1'),
(39, '0', 'affiliate_marketing', 'invisilinks', 'invisible links', 'invisilinks', 500, 500, 1, '1'),
(40, '1', 'affiliate_payment', 'dwolla', 'Pay affiliates directly through Dwolla', 'dwolla_payment', 400, 400, 1, '1'),
(41, '1', 'affiliate_payment', 'coinbase bitcoin payment', 'Pay your users directly via Bitcoin using Coinbase', 'coinbase_payment', 400, 400, 1, '1'),
(42, '0', 'mailing_list', 'Mailchimp', 'MailChimp Email Marketing Service', 'mailchimp', 400, 400, 1, '1'),
(43, '0', 'mailing_list', 'GetResponse', 'GetResponse Email Marketing Service', 'getresponse', 400, 400, 1, '1'),
(44, '0', 'mailing_list', 'Constant Contact', 'Constant Contant Email Marketing Service', 'constant_contact', 400, 400, 1, '1');
{{{~~~}}}
DROP TABLE IF EXISTS `jam_performance_rewards`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_performance_rewards` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '1',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `sale_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `time_limit` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `greater_than` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `sale_amount` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bonus_amount` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `group_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_product_commissions`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_product_commissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `type` enum('flat','percent') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'flat',
  `product_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_programs`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_programs` (
  `program_id` int(10) NOT NULL AUTO_INCREMENT,
  `program_status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `program_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `program_description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(10) NOT NULL,
  `enable_pay_per_click` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `enable_cpm` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `enable_pay_per_action` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ppc_interval` int(10) NOT NULL,
  `cpm_unique_ip` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `commission_levels` int(10) NOT NULL DEFAULT '1',
  `commission_levels_restrict_view` int(10) NOT NULL DEFAULT '1',
  `commission_frequency` int(10) NOT NULL,
  `new_commission_option` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `auto_approve_commissions` int(25) NOT NULL,
  `url_redirect` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enable_custom_login` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `url_redirect_login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enable_custom_signup` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `url_redirect_signup` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `use_remote_domain_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remote_domain_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `program_cookie_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `default_theme` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `terms_of_service` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `privacy_policy` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `hidden_program` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `group_id` int(10) NOT NULL,
  `signup_link` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `require_trans_id` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `integration_code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `last_modified` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `modified_by` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `program_logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `program_layout_member_links_array` text COLLATE utf8_unicode_ci NOT NULL,
  `enable_affiliate_signup_bonus` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `affiliate_signup_bonus_amount` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `enable_referral_bonus` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `referral_bonus_amount` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `remote_affiliate_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postback_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`program_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
INSERT INTO `jam_programs` (`program_id`, `program_status`, `program_name`, `program_description`, `sort_order`, `enable_pay_per_click`, `enable_cpm`, `enable_pay_per_action`, `ppc_interval`, `cpm_unique_ip`, `commission_levels`, `commission_levels_restrict_view`, `commission_frequency`, `new_commission_option`, `auto_approve_commissions`, `url_redirect`, `enable_custom_login`, `url_redirect_login`, `enable_custom_signup`, `url_redirect_signup`, `use_remote_domain_link`, `remote_domain_name`, `program_cookie_name`, `default_theme`, `terms_of_service`, `privacy_policy`, `hidden_program`, `group_id`, `signup_link`, `require_trans_id`, `integration_code`, `last_modified`, `modified_by`, `program_logo`, `program_layout_member_links_array`, `enable_affiliate_signup_bonus`, `affiliate_signup_bonus_amount`, `enable_referral_bonus`, `referral_bonus_amount`, `remote_affiliate_url`) VALUES
(1, '1', 'affiliate program', '<h2 class="first">Start Making Money with Our Affiliate Program<br />\n         </h2>\n<p>You can make some great referral commissions just by joining our affiliate program. It''s easy. Just fill in the registration form and get started!<br />\n         </p>\n<h2>Free to Join<br />\n         </h2>\n<p>You don''t need any money to join our program. You can refer as many people as you want, and the more people you refer, the more commissions you can potentially make.</p>\n<h2>Get Started Right Now</h2>All you have to do to join our program is to fill in the registration form. Once you register, you''ll get access to all of our free marketing tools that will help you promote our products and get you started on making money!', 1, '0', '0', '1', 10, '0', 1, 1, 0, 'no_pending', 0, '{{home_base_url}}', '0', '', '0', '', '', '', 'jamcom', 'default', '<h3>1. Terms</h3>\n<p>By accessing this web site, you are agreeing to be bound by these web site Terms and Conditions of Use, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials contained in this web site are protected by applicable copyright and trade mark law.</p>\n<h3>2. Use License</h3>\n<ol type="a"> \n \n \n \n <li>Permission is granted to temporarily download one copy of the materials (information or software) on The Company''s web site for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:\n  \n  \n  \n  \n  <ol type="i">   \n   \n   \n   \n   <li>modify or copy the materials;</li>   \n   \n   \n   \n   <li>use the materials for any commercial purpose, or for any public display (commercial or non-commercial);</li>   \n   \n   \n   \n   <li>attempt to decompile or reverse engineer any software contained on The Company''s web site;</li>   \n   \n   \n   \n   <li>remove any copyright or other proprietary notations from the materials; or</li>   \n   \n   \n   \n   <li>transfer the materials to another person or "mirror" the materials on any other server.</li>  \n  \n  \n  \n  </ol></li> \n \n \n \n <li>This license shall automatically terminate if you violate any of these restrictions and may be terminated by The Company at any time. Upon terminating your viewing of these materials or upon the termination of this license, you must destroy any downloaded materials in your possession whether in electronic or printed format.</li>\n</ol>\n<h3>3. Disclaimer</h3>\n<ol type="a"> \n \n \n \n <li>The materials on The Company''s web site are provided "as is". The Company makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties, including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights. Further, The Company does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its Internet web site or otherwise relating to such materials or on any sites linked to this site.</li>\n</ol>\n<h3>4. Limitations</h3>\n<p>In no event shall The Company or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption,) arising out of the use or inability to use the materials on The Company''s Internet site, even if The Company or a The Company authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.</p>\n<h3>5. Revisions and Errata</h3>\n<p>The materials appearing on The Company''s web site could include technical, typographical, or photographic errors. The Company does not warrant that any of the materials on its web site are accurate, complete, or current. The Company may make changes to the materials contained on its web site at any time without notice. The Company does not, however, make any commitment to update the materials.</p>\n<h3>6. Links</h3>\n<p>The Company has not reviewed all of the sites linked to its Internet web site and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by The Company of the site. Use of any such linked web site is at the user''s own risk.</p>\n<h3>7. Site Terms of Use Modifications</h3>\n<p>The Company may revise these terms of use for its web site at any time without notice. By using this web site you are agreeing to be bound by the then current version of these Terms and Conditions of Use.</p>\n<h3>8. Governing Law</h3>\n<p>Any claim relating to The Company''s web site shall be governed by the laws of the State of State without regard to its conflict of law provisions.</p>\n<p>General Terms and Conditions applicable to Use of a Web Site.</p>\n<h2>Privacy Policy</h2>\n<p>Your privacy is very important to us. Accordingly, we have developed this Policy in order for you to understand how we collect, use, communicate and disclose and make use of personal information. The following outlines our privacy policy.</p>\n<ul> \n \n \n \n <li>Before or at the time of collecting personal information, we will identify the purposes for which information is being collected.</li> \n \n \n \n <li>We will collect and use of personal information solely with the objective of fulfilling those purposes specified by us and for other compatible purposes, unless we obtain the consent of the individual concerned or as required by law.</li> \n \n \n \n <li>We will only retain personal information as long as necessary for the fulfillment of those purposes.</li> \n \n \n \n <li>We will collect personal information by lawful and fair means and, where appropriate, with the knowledge or consent of the individual concerned.</li> \n \n \n \n <li>Personal data should be relevant to the purposes for which it is to be used, and, to the extent necessary for those purposes, should be accurate, complete, and up-to-date.</li> \n \n \n \n <li>We will protect personal information by reasonable security safeguards against loss or theft, as well as unauthorized access, disclosure, copying, use or modification.</li> \n \n \n \n <li>We will make readily available to customers information about our policies and practices relating to the management of personal information.</li>\n</ul>\n<p>We are committed to conducting our business in accordance with these principles in order to ensure that the confidentiality of personal information is protected and maintained.</p><br />', '<span style="font-weight: bold;">What information do we collect?</span><br />\n<br />\nWe collect information from you when you register on our site, place an order, subscribe to our newsletter or fill out a form. <br />\n<br />\nAny data we request that is not required will be specified as voluntary or optional. <br />\n<br />\nWhen\nordering or registering on our site, as appropriate, you may be asked\nto enter your: name, e-mail address, mailing address, phone number or\ncredit card information. You may, however, visit our site anonymously.<br />\n<br />\nGoogle,\nas a third party vendor, uses cookies to serve ads on your site.\nGoogle''s use of the DART cookie enables it to serve ads to your users\nbased on their visit to your sites and other sites on the Internet.\nUsers may opt out of the use of the DART cookie by visiting the Google\nad and content network privacy policy. {more}<br />\n<br />\n<span style="font-weight: bold;">What do we use your information for?</span><br />\n<br />\nAny of the information we collect from you may be used in one of the following ways: <br />\n<br />\n- To personalize your experience<br />\n(your information helps us to better respond to your individual needs)<br />\n<br />\n- To improve our website<br />\n(we continually strive to improve our website offerings based on the information and feedback we receive from you)<br />\n<br />\n- To process transactions<br />\nYour\ninformation, whether public or private, will not be sold, exchanged,\ntransferred, or given to any other company for any reason whatsoever,\nwithout your consent, other than for the express purpose of delivering\nthe purchased product or service requested by the customer. <br />\n<br />\n- To send periodic emails<br />\n<br />\nThe\nemail address you provide for order processing, may be used to send you\ninformation and updates pertaining to your order or request, in\naddition to receiving occasional company news, updates, promotions,\nrelated product or service information, etc.<br />\n<br />\nNote: If at any\ntime you would like to unsubscribe from receiving future emails, we\ninclude detailed unsubscribe instructions at the bottom of each email.<br />\n<br />\n- To administer a contest, promotion, survey or other site feature<br />\n<br />\n<span style="font-weight: bold;">How do we protect your information?</span><br />\n<br />\nWe\nimplement a variety of security measures to maintain the safety of your\npersonal information when you submit a request, place an order or\naccess your personal information. <br />\n<br />\nThese security measures\ninclude: password protected directories and databases to safeguard your\ninformation, SSL (Secure Sockets Layered) technology to ensure that\nyour information is fully encrypted and sent across the Internet\nsecurely or PCI Scanning to actively protect our servers from hackers\nand other vulnerabilities. <br />\n<br />\nWe offer the use of a secure\nserver. All supplied sensitive/credit information is transmitted via\nSecure Socket Layer (SSL) technology and then encrypted into our\nPayment gateway providers database only to be accessible by those\nauthorized with special access rights to such systems, and are required\nto keep the information confidential.<br />\n<br />\nAfter a transaction, your private information (credit cards, social security numbers, financials, etc.) will .<br />\n<br />\n<span style="font-weight: bold;">Do we use cookies?</span><br />\n<br />\nYes\n(Cookies are small files that a site or its service provider transfers\nto your computers hard drive through your Web browser (if you allow)\nthat enables the sites or service providers systems to recognize your\nbrowser and capture and remember certain information.<br />\n<br />\n<span style="font-weight: bold;">Do we disclose any information to outside parties?</span><br />\n<br />\nWe\ndo not sell, trade, or otherwise transfer to outside parties your\npersonally identifiable information. This does not include trusted\nthird parties who assist us in operating our website, conducting our\nbusiness, or servicing you, so long as those parties agree to keep this\ninformation confidential. We may also release your information when we\nbelieve release is appropriate to comply with the law, enforce our site\npolicies, or protect ours or others'' rights, property, or safety.\nHowever, non-personally identifiable visitor information may be\nprovided to other parties for marketing, advertising, or other uses.<br />\n<br />\n<span style="font-weight: bold;">Third party links</span><br />\n<br />\nOccasionally,\nat our discretion, we may include or offer third party products or\nservices on our website. These third party sites have separate and\nindependent privacy policies. We therefore have no responsibility or\nliability for the content and activities of these linked sites.\nNonetheless, we seek to protect the integrity of our site and welcome\nany feedback about these sites.<br />\n<br />\n<span style="font-weight: bold;">California Online Privacy Protection Act Compliance</span><br />\n<br />\nBecause\nwe value your privacy we have taken the necessary precautions to be in\ncompliance with the California Online Privacy Protection Act. We\ntherefore will not distribute your personal information to outside\nparties without your consent.<br />\n<br />\nAll users of our site may make any\nchanges to their information at anytime by logging into their control\npanel and going to the ''Edit Profile'' page.<br />\n<br />\n<span style="font-weight: bold;">Childrens Online Privacy Protection Act Compliance</span><br />\n<br />\nWe\nare in compliance with the requirements of COPPA (Childrens Online\nPrivacy Protection Act), we do not collect any information from anyone\nunder 13 years of age. Our website, products and services are all\ndirected to people who are at least 13 years old or older.<br />\n<br />\n<span style="font-weight: bold;">CAN-SPAM Compliance</span><br />\n<br />\nWe\nhave taken the necessary steps to ensure that we are compliant with the\nCAN-SPAM Act of 2003 by never sending out misleading information.<br />\n<br />\n<span style="font-weight: bold;">Online Privacy Policy Only</span><br />\n<br />\nThis online privacy policy applies only to information collected through our website and not to information collected offline.', '0', 1, 'signup', '0', '', '1356566528', '', '', 'a:5:{i:0;a:9:{s:2:"id";s:1:"3";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:9:"dashboard";s:11:"menu_parent";s:1:"0";s:8:"menu_url";s:14:"{members_home}";s:15:"menu_sort_order";s:1:"1";s:12:"menu_options";s:0:"";s:4:"subs";a:0:{}}i:1;a:9:{s:2:"id";s:1:"4";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:9:"marketing";s:11:"menu_parent";s:1:"0";s:8:"menu_url";s:19:"{members_marketing}";s:15:"menu_sort_order";s:1:"2";s:12:"menu_options";s:0:"";s:4:"subs";a:2:{i:0;a:8:{s:2:"id";s:1:"8";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:14:"email_downline";s:11:"menu_parent";s:1:"4";s:8:"menu_url";s:24:"{members_email_downline}";s:15:"menu_sort_order";s:1:"7";s:12:"menu_options";s:0:"";}i:1;a:8:{s:2:"id";s:1:"5";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:13:"view_downline";s:11:"menu_parent";s:1:"4";s:8:"menu_url";s:18:"{members_downline}";s:15:"menu_sort_order";s:1:"8";s:12:"menu_options";s:0:"";}}}i:2;a:9:{s:2:"id";s:1:"6";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:11:"commissions";s:11:"menu_parent";s:1:"0";s:8:"menu_url";s:21:"{members_commissions}";s:15:"menu_sort_order";s:1:"4";s:12:"menu_options";s:0:"";s:4:"subs";a:1:{i:0;a:8:{s:2:"id";s:1:"7";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:18:"affiliate_payments";s:11:"menu_parent";s:1:"6";s:8:"menu_url";s:18:"{members_payments}";s:15:"menu_sort_order";s:1:"3";s:12:"menu_options";s:0:"";}}}i:3;a:9:{s:2:"id";s:1:"1";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:7:"profile";s:11:"menu_parent";s:1:"0";s:8:"menu_url";s:17:"{members_details}";s:15:"menu_sort_order";s:1:"5";s:12:"menu_options";s:0:"";s:4:"subs";a:0:{}}i:4;a:9:{s:2:"id";s:1:"2";s:10:"program_id";s:1:"1";s:11:"menu_status";s:1:"1";s:9:"menu_name";s:7:"content";s:11:"menu_parent";s:1:"0";s:8:"menu_url";s:17:"{members_content}";s:15:"menu_sort_order";s:1:"6";s:12:"menu_options";s:0:"";s:4:"subs";a:0:{}}}', '0', '', '0', '', '');
{{{~~~}}}
DROP TABLE IF EXISTS `jam_programs_form_fields`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_programs_form_fields` (
  `program_id` int(10) NOT NULL DEFAULT '0',
  `enable_fname` enum('0','1','2') NOT NULL DEFAULT '1',
  `enable_lname` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_primary_email` enum('0','1','2') NOT NULL DEFAULT '1',
  `enable_username` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_password` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_company` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_billing_address_1` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_billing_address_2` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_billing_city` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_billing_state` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_billing_country` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_billing_postal_code` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_home_phone` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_work_phone` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_mobile_phone` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_fax` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_website` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_facebook_id` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_twitter_id` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_myspace_id` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_linkedin_id` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_payment_name` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_payment_address_1` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_payment_address_2` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_payment_city` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_payment_state` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_payment_country` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_payment_postal_code` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_payment_preference_amount` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_paypal_id` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_moneybookers_id` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_payza_id` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_coinbase_id` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_dwolla_id` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_custom_id` enum('0','1','2') NOT NULL DEFAULT '0',
  `enable_bank_transfer` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_1` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_2` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_3` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_4` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_5` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_6` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_7` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_8` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_9` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_10` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_11` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_12` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_13` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_14` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_15` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_16` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_17` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_18` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_19` enum('0','1','2') NOT NULL DEFAULT '0',
  `program_custom_field_20` enum('0','1','2') NOT NULL DEFAULT '0',
  `show_tos` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
{{{~~~}}}
INSERT INTO `jam_programs_form_fields` (`program_id`, `enable_fname`, `enable_lname`, `enable_primary_email`, `enable_username`, `enable_password`, `enable_company`, `enable_billing_address_1`, `enable_billing_address_2`, `enable_billing_city`, `enable_billing_state`, `enable_billing_country`, `enable_billing_postal_code`, `enable_home_phone`, `enable_work_phone`, `enable_mobile_phone`, `enable_fax`, `enable_website`, `enable_facebook_id`, `enable_twitter_id`, `enable_myspace_id`, `enable_linkedin_id`, `enable_payment_name`, `enable_payment_address_1`, `enable_payment_address_2`, `enable_payment_city`, `enable_payment_state`, `enable_payment_country`, `enable_payment_postal_code`, `enable_payment_preference_amount`, `enable_paypal_id`, `enable_moneybookers_id`, `enable_payza_id`, `enable_custom_id`, `enable_bank_transfer`, `program_custom_field_1`, `program_custom_field_2`, `program_custom_field_3`, `program_custom_field_4`, `program_custom_field_5`, `program_custom_field_6`, `program_custom_field_7`, `program_custom_field_8`, `program_custom_field_9`, `program_custom_field_10`, `program_custom_field_11`, `program_custom_field_12`, `program_custom_field_13`, `program_custom_field_14`, `program_custom_field_15`, `program_custom_field_16`, `program_custom_field_17`, `program_custom_field_18`, `program_custom_field_19`, `program_custom_field_20`, `show_tos`) VALUES
(1, '1', '1', '1', '1', '0', '2', '1', '0', '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
{{{~~~}}}
DROP TABLE IF EXISTS `jam_programs_photos`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_programs_photos` (
  `photo_id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `default_banner` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `photo_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `raw_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file_ext` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `original_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_resized` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`photo_id`),
  KEY `program_id` (`program_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_program_integration`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_program_integration` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(255) NOT NULL DEFAULT '',
  `img` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `api_integration` text NOT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
INSERT INTO `jam_program_integration` (`id`, `name`, `code`, `img`, `description`, `api_integration`, `sort`) VALUES
(1, 'generic pixel tracking', 'pixel', '', '<ol>\r\n  <li>Replace the <strong>$AMOUNT</strong> with your actual sale variable</li>\r\n  <li>Replace the <strong>$ORDER_ID</strong> with your actual order ID or transaction ID variable</li>\r\n<li> On a thank you page, make sure to add it in as a standard HTML hidden image tag on its own line , for example:\r\n<pre>&lt;img src="{tracking_url}sale/amount/$AMOUNT/trans_id/$ORDER_ID" width="1" height="1" border="0" /></pre>\r\n</li>\r\n<li>If you do not know what your actual sale and order ID variables are, make sure to ask your hosting, shopping cart or payment gateway provider</li>\r\n</ol>', '', 9999),
(2, 'iframe tracking code', 'iframe', '', '<ol>\r\n  <li>Replace the <strong>$AMOUNT</strong> with your actual sale variable</li>\r\n  <li>Replace the <strong>$ORDER_ID</strong> with your actual order ID or transaction ID variable</li>\r\n<li> On a PHP thank you page, make sure to add it in as a standard HTML iframe tag on its own line , for example:\r\n   \r\n    </li>\r\n</ol>\r\n\r\n<pre>&lt;iframe src="{tracking_url}sale/amount/$AMOUNT/trans_id/$ORDER_ID" border="0" scrolling="no" frameborder="0" width="1" height="1">&lt;/iframe></pre>\r\n', '', 9999),
(3, 'php file_get_contents', 'php_file', 'php.png', '<ol>\r\n  <li>Replace the <strong>$AMOUNT</strong> with your actual sale variable</li>\r\n  <li>Replace the <strong>$ORDER_ID</strong> with your actual order ID or transaction ID variable</li>\r\n<li> On a PHP thank you page, make sure to add it in as PHP code on its own line , for example:\r\n   \r\n    </li>\r\n</ol>\r\n\r\n<pre>file_get_contents(''{tracking_url}sale/amount/'' . $AMOUNT . ''/trans_id/'' . $ORDER_ID . ''/tracking_code/'' . $_COOKIE[''{aff_cookie_name}'']);</pre>\r\n', '', 9999),
(4, 'php curl', 'php_curl', 'php.png', '<ol>\r\n  <li>Replace the <strong>$AMOUNT</strong> with your actual sale variable</li>\r\n  <li>Replace the <strong>$ORDER_ID</strong> with your actual order ID or transaction ID variable</li>\r\n<li> On a PHP thank you page, make sure to add it in as PHP code, for example:\r\n   \r\n    </li>\r\n</ol>\r\n\r\n<pre>$curl = curl_init();\r\n\r\ncurl_setopt_array($curl, array(      <br>     CURLOPT_RETURNTRANSFER =&gt; 1,      \r\n     CURLOPT_URL =&gt; ''{tracking_url}sale/amount/'' . $AMOUNT . ''/trans_id/'' . $ORDER_ID . ''/tracking_code/'' . $_COOKIE[''{aff_cookie_name}''],\r\n     CURLOPT_USERAGENT =&gt; ''Affiliate Software Tracking Request''  ,\r\n)); \r\n     \r\n$resp = curl_exec($curl);  \r\ncurl_close($curl);</pre>\r\n', '', 9999),
(5, 'paypal standard', 'paypal_standard', 'paypal_standard.png', '<h3>Paypal Created Buttons (buy now or shopping cart) </h3><p>To integrate the affiliate software with Paypal Standard Buttons, follow these steps:</p>\r\n<h3>Buy Now and Subscription Buttons Made on Paypal Site</h3>\r\n<ol>\r\n  <li>You will have to enable IPN or Instant Payment Notification on your Paypal account and set the IPN URL to your domain name.</li>\r\n  <li>If you are creating buttons on paypal''s website, you can integrate the affiliate software into your paypal buttons like so:<br>\r\n    <br>\r\n    <pre>\r\n    &lt;form action=&quot;https://www.paypal.com/cgi-bin/webscr&quot; method=&quot;post&quot; target=&quot;_top&quot;&gt;\r\n    &lt;input type=&quot;hidden&quot; name=&quot;cmd&quot; value=&quot;_s-xclick&quot;&gt;\r\n    &lt;input type=&quot;hidden&quot; name=&quot;hosted_button_id&quot; value=&quot;XXXXXXXXXX&quot;&gt;\r\n    &lt;input type=&quot;image&quot; src=&quot;https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif&quot; border=&quot;0&quot; name=&quot;submit&quot; alt=&quot;PayPal - The safer, easier way to pay online!&quot;&gt;\r\n    &lt;img alt=&quot;&quot; border=&quot;0&quot; src=&quot;https://www.paypalobjects.com/en_US/i/scr/pixel.gif&quot; width=&quot;1&quot; height=&quot;1&quot;&gt;\r\n    &lt;!-- INTEGRATION CODE -->\r\n    &lt;input type="hidden" name="notify_url" value="{tracking_url}sale/paypal/" /&gt;    \r\n    &lt;script language="JavaScript" type="text/javascript" src="{tracking_url}js/track/custom/"&gt;&lt;/script&gt;    \r\n    &lt;!-- END INTEGRATION CODE --> \r\n   \r\n    &lt;/form&gt;\r\n    </pre>\r\n   \r\n  </li>\r\n  <li>You can now copy  and paste the paypal code to your web page.</li>\r\n</ol>\r\n<h3>Thank You Page Integration</h3>\r\n<p>If you want to use a thank you php page and integrate the Paypal code on to that page, you can do the following.  This can work if you have lots of paypal buttons already on your site and don''t want to edit each button individually.</p>\r\n<ol>\r\n  <li>If you are using paypal buttons created on paypal''s site, you will need to add the following when creating the button:<br>    rm=2<br>  \r\n  </li>  \r\n<li>Add that option under the Customize Advance Features area.</li>  <li>Make sure to also set the Take Customers to this URL when they finish checkout and have it point to your thank you page. The thank you page is where you would add your integration code for the affiliate software. Make sure it is a .php page, for example: thankyou.php</li>  <li>Create your button.</li>  <li>Once that is done, open up your thankyou.php page and add the following code:<br>    <pre>&lt;img src="{tracking_url}sale/amount/&lt;?=$_POST[''''mc_gross'''']?&gt;/trans_id/&lt;?=$_POST[''''transid'''']?&gt;" border="0" height="1" width="1" /&gt;</pre>  </li>  <li>Save your thankyou.php page.</li></ol>\r\n<h3>Paypal Subscription Payments (Subscriptions Not Created At Paypal''s Site)</h3>\r\n<p> In order to integrate the affiliate software with Paypal subscription payments, You will  have to enable IPN or Instant Payment Notification on your Paypal account.</p><ol>  <li>First, make sure you don''''t have recurring commissions enabled. You can check the setting in Programs &gt; Manage Programs, under the Program Info &gt; Commission Settings area. Look for the Recur Commissions field and set it to 0 (zero)</li>  <li>Below is an example Paypal subscription button that uses the custom and notify_url fields.  If you have customized implementation of your Paypal subscription buttons, let us know and we can integrate the software with yours for a nominal fee.</li></ol><p>Here is some sample code: </p><pre>	<p>&lt;form action="https://www.paypal.com/cgi-bin/webscr" method="post"&gt;    <br>&lt;input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but20.gif" border="0" name="submit" alt="Make payments with PayPal - it''''s fast, free and secure!" /&gt;<br>&lt;img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" /&gt;    <br>&lt;input type="hidden" name="cmd" value="_xclick-subscriptions" /&gt;    <br>&lt;input type="hidden" name="business" value="paypal@domain.com" /&gt;    <br>&lt;input type="hidden" name="item_name" value="test subscription" /&gt;    <br>&lt;input type="hidden" name="item_number" value="1001" /&gt;    <br>&lt;input type="hidden" name="no_shipping" value="1" /&gt;    <br>&lt;input type="hidden" name="no_note" value="1" /&gt;    <br>&lt;input type="hidden" name="currency_code" value="USD" /&gt;    <br>&lt;input type="hidden" name="bn" value="PP-SubscriptionsBF" /&gt;    <br>&lt;input type="hidden" name="a3" value="1.00" /&gt;    <br>&lt;input type="hidden" name="p3" value="1" /&gt;    <br>&lt;input type="hidden" name="t3" value="D" /&gt;    <br>&lt;input type="hidden" name="src" value="1" /&gt;    <br>&lt;input type="hidden" name="sra" value="1" /&gt;    <br>&lt;input type="hidden" name="srt" value="2" /&gt;\r\n &lt;!-- INTEGRATION CODE -->\r\n&lt;input type="hidden" name="notify_url" value="{tracking_url}sale/paypal/" /&gt;    <br>&lt;script language="JavaScript" type="text/javascript" src="{tracking_url}js/track/custom/"&gt;&lt;/script&gt;\r\n &lt;!-- END INTEGRATION CODE -->\r\n&lt;/form&gt;</p></pre>', '', 8888),
(6, '2checkout', '2checkout', '2checkout.png', '<h3>2Checkout</h3><p>To integrate the affiliate software with 2Checkout, follow these steps:</p>\r\n<ol style="">\r\n	<li>Login to 2Checkout.com.</li>\r\n	<li>Under the <span style="font-weight: bold;">Account > Site Management</span>&nbsp;tab, scroll down to the <span style="font-weight: bold;">Affiliate URL</span>&nbsp;and enter the full URL path to your affiliate software install<br />\r\n		      \r\n		<pre>{tracking_url}sale/amount/$a_total/trans_id/$a_order</pre></li>\r\n	\r\n</ol>\r\n', '', 30),
(7, 'woocommerce', 'woocommerce', 'woocommerce.png', '<h3>WooCommerce</h3>\r\n<p>Affiliate Manager has a free WordPress Plugin for integrating with WooCommerce. The plugin also supports automatic registration and login via the Affiliate Manager Automation API.<br /><br /><a href="http://my.jrox.com/downloads/woo_jrox_affiliate.zip"><strong>Click Here to Download the Plugin</strong><br /><br /></a>Install the plugin using the standard method of installing plugins in WordPress. Please make sure you have WooCommerce installed as it will not work if WooCommerce is not available.<br /><br />If you do not want to use the plugin, you can follow the steps below for the traditional integration method:</p>\r\n<ol style="">\r\n	<li>Edit the file <strong>wp-content/plugins/woocommerce/templates/checkout/thankyou.php </strong></li>\r\n	<li>Look for the following line of code:<br>\r\n	  <pre>&lt;?php do_action( ''woocommerce_thankyou'', $order-&gt;id );</pre></li>\r\n	<li>	  Place the following code below that line:<br />\r\n	  \r\n	  <pre>&lt;img src="{tracking_url}sale/amount/&lt;?php echo $order->order_total; ?/trans_id/&lt;?php echo $order->id; ?>" width="1" height="1" border="0" /></pre>\r\n  </li>\r\n	\r\n</ol>\r\n', '', 9000),
(8, 'whmcs', 'whmcs', 'whmcs.png', '<h3>WHMCS 5.x</h3>\r\n<p>Affiliate Manager has a free Action Hook Plugin for integrating with WHMCS5.x. The plugin also supports automatic registration and login via the Affiliate Manager Automation API.<br />\r\n  <br />\r\n<a href="http://my.jrox.com/downloads/whmcs_jrox_affiliate.zip"><strong>Click Here to Download the Plugin</strong></a></p>\r\n<p>Unzip the plugin and open it up for editing.</p>\r\n<p>Set the following constants in the file:</p>\r\n\r\n<pre>\r\ndefine(''JROX_AFFILIATE_URL'', ''http://yourdomain.com/affiliates''); //no trailing slash\r\ndefine(''JROX_AUTOMATION_ACCESS_KEY'', ''XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'');\r\ndefine(''JROX_AUTOMATION_ACCESS_ID'', ''XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'');\r\n</pre>\r\n<p>\r\nchange the <strong>http://yourdomain.com/affiliates</strong> to your affiliate installation URL.  Do not add a trailing slash.\r\n</p>\r\n<h3>Automatic Affiliate Registration and Login</h3>\r\n<p>\r\nIf you want to use the automation API for automatically registering new customers as affiliates, set the automation access key and ID.  Those can be found in your affiliate manager admin area, under Settings > Global Configuration > Automation.\r\n</p>\r\n\r\n<p>\r\nalso, uncomment the following sections to enable them:\r\n</p>\r\n<pre>\r\n//define(''JROX_ENABLE_AUTOREGISTER'', true);  //uncomment to enable\r\n//define(''JROX_ENABLE_AUTOLOGIN'', true);  //uncomment to enable\r\n</pre>', '', 1),
(9, 'prestashop', 'prestashop', 'prestashop.png', '<h3>Prestashop 1.5x</h3>\r\n<p>To integrate the affiliate software with Prestashop, follow these steps:</p>\r\n<ol>\r\n	<li>Open up the <strong>/controllers/front/OrderConfirmationController.php</strong>file. Locate the code block as shown below and add the integration code as show below. Look for the //START AFFILIATE CODE and //END AFFILIATE CODE block to edit.</li>\r\n	<li>Make sure to use your affiliate URL for the integration block.<br />\r\n		\r\n		<pre>public function displayOrderConfirmation()\r\n	{\r\n		if (Validate::isUnsignedId($this->id_order))\r\n		{\r\n			$params = array();\r\n			$order = new Order($this->id_order);\r\n			$currency = new Currency($order->id_currency);\r\n\r\n			if (Validate::isLoadedObject($order))\r\n			{\r\n				$params[''total_to_pay''] = $order->getOrdersTotalPaid();\r\n				$params[''currency''] = $currency->sign;\r\n				$params[''objOrder''] = $order;\r\n				$params[''currencyObj''] = $currency;\r\n				\r\n				//START AFFILIATE CODE\r\n				$JAMIntegrate = file_get_contents("{tracking_url}sale/amount/".$params[''total_to_pay'']."/trans_id/".$this->id_order."/tracking_code/".$_COOKIE[''{aff_cookie_name}'']);				\r\n				//END AFFILIATE CODE\r\n				\r\n				return Hook::exec(''displayOrderConfirmation'', $params);\r\n			}\r\n		}\r\n		return false;\r\n	}</pre></li>\r\n	<li>Save and upload to your /controllers folder.</li>\r\n</ol>\r\n<p></p>\r\n<p><strong>Integrating with Prestashop 1.5 for Paypal</strong></p>\r\n<p>To integrate with Paypal on Prestashop 1.5, follow these steps:</p>\r\n<ol>\r\n	<li>Open up <strong>modules/paypal/controllers/front/submit.php.</strong><span style="line-height: 1.8em;">Locate the code block as shown below under <strong>private function displayHook() </strong>and add the integration code as show below. Look for the //START AFFILIATE CODE and //END AFFILIATE CODE block to edit.</strong>      </li>  \r\n	<li>Make sure to use your affiliate URL for the integration block.<br />\r\n		<pre>if (Validate::isLoadedObject($order))\r\n			{\r\n				$params[''objOrder''] = $order;\r\n				$params[''currencyObj''] = $currency;\r\n				$params[''currency''] = $currency->sign;\r\n				$params[''total_to_pay''] = $order->getOrdersTotalPaid();\r\n				\r\n					\r\n				//START AFFILIATE CODE\r\n$JAMIntegrate = file_get_contents("{tracking_url}sale/amount/".$params[''total_to_pay'']."/trans_id/".$this->id_order."/tracking_code/".$_COOKIE[''{aff_cookie_name}'']);				\r\n				//END AFFILIATE CODE\r\n						\r\n				return $params;\r\n			}</pre>\r\n            </li>\r\n</ol>', '', 3),
(10, 'shopperpress', 'shopperpress', 'shopperpress.png', '<h3>ShopperPress</h3>\r\n<p>To integrate with WordPress ShopperPress, follow these steps:</p>\r\n<ol style="">\r\n	<li>Open up the /wp-content/themes/shopperpress/tpl_callback.php file</li>\r\n	<li>Add the following lines of code<br />\r\n		\r\n		<pre>/*   \r\nFOR THOSE LOOKING TO ADD THEIR OWN AFFILIATE CODE\r\n}\r\nDE THE VALUE $ARRAY BELOW HOLDS ALL OF THE CONTENT FOR THE ORDER;\r\n*/\r\n$result = mysql_query("SELECT * FROM ".$wpdb->prefix."orderdata WHERE ".$wpdb->prefix."orderdata.order_id=''".strip_tags($GLOBALS[''PPTorderID''])."''", $wpdb->dbh) \r\nor die(mysql_error().'' on line: ''.__LINE__);							\r\n$array = mysql_fetch_assoc($result);\r\n\r\n//START AFFILIATE CODE\r\n$Aff = file_get_contents("{tracking_url}sale/amount/".$array[''order_subtotal'']."/trans_id/".$array[''order_id'']."/tracking_code/".$_COOKIE[''{aff_cookie_name}'']);				\r\n//END AFFILIATE CODE</pre></li>\r\n	<li>Save it and upload</li>\r\n	<li>You have now integrated with ShopperPress for WordPress.</li>\r\n</ol>', '', 0),
(11, 'zencart', 'zencart', 'zencart.png', '<h3>Zencart</h3>\r\n<p>To integrate the affiliate software with your Zencart shopping cart, follow these steps:</p>\r\n<ol>\r\n	<li>Create a tpl_footer.php override file if you do not yet have one.</li>\r\n	<li>For a new file, you can copy the following code into your tpl_footer.php<br>\r\n    <pre>\r\n&lt;?php\r\n/**\r\n * Common Template - tpl_footer.php\r\n *\r\n * this file can be copied to /templates/your_template_dir/pagename&lt;br />\r\n * example: to override the privacy page&lt;br />\r\n * make a directory /templates/my_template/privacy&lt;br />\r\n * copy /templates/templates_defaults/common/tpl_footer.php to /templates/my_template/privacy/tpl_footer.php&lt;br />\r\n * to override the global settings and turn off the footer un-comment the following line:&lt;br />\r\n * &lt;br />\r\n * $flag_disable_footer = true;&lt;br />\r\n *\r\n * @package templateSystem\r\n * @copyright Copyright 2003-2005 Zen Cart Development Team\r\n * @copyright Portions Copyright 2003 osCommerce\r\n * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0\r\n * @version $Id: tpl_footer.php 3183 2006-03-14 07:58:59Z birdbrain $\r\n */\r\nrequire(DIR_WS_MODULES . zen_get_module_directory(''footer.php''));\r\n\r\nif (!$flag_disable_footer) {\r\n\r\n?>\r\n\r\n&lt;!--bof-navigation display -->\r\n&lt;div id="navSuppWrapper">\r\n&lt;div id="navSupp">\r\n&lt;ul>\r\n&lt;li>&lt;?php echo ''&lt;a href="'' . HTTP_SERVER . DIR_WS_CATALOG . ''">''; ?>&lt;?php echo HEADER_TITLE_CATALOG; ?>&lt;/a>&lt;/li>\r\n&lt;?php if (EZPAGES_STATUS_FOOTER == ''1'' or (EZPAGES_STATUS_FOOTER == ''2'' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER[''REMOTE_ADDR''])))) { ?>\r\n&lt;li>&lt;?php require($template->get_template_dir(''tpl_ezpages_bar_footer.php'',DIR_WS_TEMPLATE, $current_page_base,''templates''). ''/tpl_ezpages_bar_footer.php''); ?>&lt;/li>\r\n&lt;?php } ?>\r\n&lt;/ul>\r\n&lt;/div>\r\n&lt;/div>\r\n&lt;!--eof-navigation display -->\r\n\r\n&lt;!--bof-ip address display -->\r\n&lt;?php\r\nif (SHOW_FOOTER_IP == ''1'') {\r\n?>\r\n&lt;div id="siteinfoIP">&lt;?php echo TEXT_YOUR_IP_ADDRESS . ''  '' . $_SERVER[''REMOTE_ADDR'']; ?>&lt;/div>\r\n&lt;?php\r\n}\r\n?>\r\n&lt;!--eof-ip address display -->\r\n\r\n&lt;!--bof-banner #5 display -->\r\n&lt;?php\r\n  if (SHOW_BANNERS_GROUP_SET5 != '''' && $banner = zen_banner_exists(''dynamic'', SHOW_BANNERS_GROUP_SET5)) {\r\n    if ($banner->RecordCount() > 0) {\r\n?>\r\n&lt;div id="bannerFive" class="banners">&lt;?php echo zen_display_banner(''static'', $banner); ?>&lt;/div>\r\n&lt;?php\r\n    }\r\n  }\r\n?>\r\n&lt;!--eof-banner #5 display -->\r\n\r\n&lt;!--bof- site copyright display -->\r\n&lt;div id="siteinfoLegal" class="legalCopyright">&lt;?php echo FOOTER_TEXT_BODY; ?>&lt;/div>\r\n&lt;!--eof- site copyright display -->\r\n\r\n&lt;?php\r\n} // flag_disable_footer\r\n##########################################\r\n## START INTEGRATION WITH ZEN CART  ##\r\n##########################################\r\nif ((int)$orders_id > 0) {\r\n  $AFF = $db->Execute("select class, value from " . TABLE_ORDERS_TOTAL . " where orders_id = ''".(int)$orders_id."'' AND class in (''ot_coupon'', ''ot_subtotal'', ''ot_group_pricing'')");\r\n  while (!$AFF->EOF) {\r\n    switch ($AFF->fields[''class'']) {\r\n      case ''ot_subtotal'':\r\n       $order_subtotal = $AFF->fields[''value''];\r\n        break;\r\n      case ''ot_coupon'':\r\n       $coupon_amount = $AFF->fields[''value''];\r\n        break;\r\n      case ''ot_group_pricing'':\r\n       $group_pricing_amount = $AFF->fields[''value''];\r\n        break;\r\n    }\r\n    $AFF->MoveNext();\r\n  }\r\n  $commissionable_order = ($order_subtotal - $coupon_amount - $group_pricing_amount);\r\n  $commissionable_order = number_format($commissionable_order,2,''.'','''');\r\n  \r\n  if ($commissionable_order &lt; ''0.01'')\r\n  {\r\n	  //try paypal\r\n	  $aff = $db->Execute("select * FROM " . TABLE_PAYPAL . "  where order_id = ''".(int)$orders_id."''");\r\n	  \r\n	  if (!$aff)\r\n	  {\r\n		  $commissionable_order = $AFF->fields[''mc_gross''];\r\n	  }\r\n  }\r\n  \r\n  echo "&lt;script language=\\"JavaScript\\" type=\\"text/javascript\\" src=\\"{tracking_url}sale/amount/$commissionable_order/trans_id/$orders_id\\">&lt;/script>";\r\n}\r\n#######################################\r\n## END INTEGRATION WITH ZEN CART ##\r\n#######################################\r\n\r\n?>\r\n\r\n    </pre>\r\n	</li>\r\n	<li>If you already have a custom tpl_footer.php override file, you can copy the lines in the code to add to your file:</li>\r\n	<li>Scroll to the lines that have the affiliate integration and change the URL path to reflect your correct your affiliate installation URL:<br />\r\n		      \r\n		<pre>  echo "&lt;script language="\\"JavaScript\\"" type="\\"text/javascript\\"" src="\\"{tracking_url}sale/amount/$commissionable_order/trans_id/$orders_id\\"">&lt;/script>";</pre></li>\r\n	<li>Save it, and upload it to <strong>/includes/templates/THEME_NAME/checkout_success</strong>. If there is no <strong>checkout_success</strong>&nbsp;folder there, just create one.</li>\r\n	<li>If you are using SSL on your zencart checkout site, make sure to change the src URL of the affiliate system to use https:// instead of http://</li>\r\n</ol>\r\n', '', 0),
(12, 'ecwid shopping cart', 'ecwid', 'ecwid.png', '<h3>Ecwid Shopping Cart</h3>\r\n<p>To integrate the affiliate software with Ecwid Shopping Cart, please follow these steps:</p>\r\n<ol>\r\n	<li>Login to your Ecwid admin area and go to <strong>System Settings > Cart</strong></li>\r\n	<li>Scroll down to the section for <strong>Checkout Settings</strong>&nbsp;and enable the option for <strong>Affiliate Code on Thank You For Your Order </strong>page</li>\r\n	<li>Add the following code:<br />\r\n		<br />\r\n		      \r\n		<pre>&lt;script type="text/javascript">\r\n    var image;\r\n    image = window.document.createElement(''img'');\r\nimage.src = "{tracking_url}sale/amount/%order_total%/trans_id/%order_id%";\r\n    window.document.body.appendChild(image);\r\n&lt;/script></pre></li>\r\n</ol>', '', 0),
(13, 'opencart', 'opencart', 'opencart.png', '<h3>OpenCart Shopping Cart</h3>\r\n<p>To integrate the affiliate software with Opencart Shopping Cart, please follow these steps:</p>\r\n<ol>\r\n	<li>Open up the following file for editing:<br>\r\n	  <br>\r\n	  <strong>/catalog/controller/checkout/success.php</strong><br>\r\n	  <br>\r\n	  <strong></strong></li>\r\n	<li>Add the following lines of code as given below in the appropriate lines of code:<br />\r\n		<br />\r\n      <pre>&lt;?php\r\nclass ControllerCheckoutSuccess extends Controller { \r\n	public function index() { 	\r\n		if (isset($this->session->data[''order_id''])) {\r\n			\r\n			//START AFFILIATE INTEGRATION			\r\n			$jrox = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);\r\n			$sql = ''SELECT * FROM  `'' . DB_PREFIX . ''order` WHERE order_id = '' . (int)$this->session->data[''order_id''];\r\n			$jrox2 = mysqli_query($jrox, $sql);\r\n			$aff_data = mysqli_fetch_assoc($jrox2);	\r\n			$aff_integrate = file_get_contents(''{tracking_url}sale/amount/'' . $aff_data[''total''] . ''/trans_id/'' . $this->session->data[''order_id''] . ''/tracking_code/'' . $_COOKIE[''{aff_cookie_name}'']);\r\n			//END AFFILIATE INTEGRATION\r\n			\r\n			$this->cart->clear();\r\n\r\n			unset($this->session->data[''shipping_method'']);\r\n			unset($this->session->data[''shipping_methods'']);\r\n			unset($this->session->data[''payment_method'']);\r\n			unset($this->session->data[''payment_methods'']);\r\n			unset($this->session->data[''guest'']);\r\n			unset($this->session->data[''comment'']);\r\n			unset($this->session->data[''order_id'']);	\r\n			unset($this->session->data[''coupon'']);\r\n			unset($this->session->data[''reward'']);\r\n			unset($this->session->data[''voucher'']);\r\n			unset($this->session->data[''vouchers'']);\r\n			unset($this->session->data[''totals'']);\r\n		}	\r\n</pre>\r\n	</li>\r\n</ol>', '', 0),
(14, 'oscommerce', 'oscommerce', 'oscommerce.png', '<h3>OSCommerce</h3>\r\n<p>To integrate with OSCommerce, use the following directions:</p>\r\n<ol>    \r\n	<li>    \r\n		<p>Open up the file <strong>/oscommerce/checkout_success.php</strong>.</p></li>    \r\n	<li>    \r\n		<p>Scroll down in the file where it says the following lines of code, and paste your integration code in the area specified:<br />\r\n	  </p>\r\n		<pre>if ($global[''global_product_notifications''] != ''1'') {\r\n    echo TEXT_NOTIFY_PRODUCTS . '''';\r\n			&lt;p class="productsNotifications">    $products_displayed = array();\r\n    for ($i=0, $n=sizeof($products_array); $i&lt;$n; $i++) {\r\n      if (!in_array($products_array[$i][''id''], $products_displayed)) {\r\n        echo tep_draw_checkbox_field(''notify[]'', $products_array[$i][''id'']) . '' '' . $products_array[$i][''text''] . ''<br />\r\n				'';\r\n        $products_displayed[] = $products_array[$i][''id''];\r\n      }\r\n   }\r\n\r\n    \r\n\r\n###########################################\r\n## START AFF INTEGRATION WITH OSCOMMERCE ##\r\n###########################################\r\n\r\n$AFFTotal = tep_db_query("SELECT value FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = ''" . (int)$orders[''orders_id''] . "'' AND class = ''ot_subtotal''");\r\n$AFFTotal = tep_db_fetch_array($AFFTotal);\r\n$TotalAmount = number_format($AFFTotal[''value''],2,''.'','','');\r\n$TransID = $orders[''orders_id''];\r\necho ''&lt;img border="0" src="{tracking_url}sale/amount/''.$TotalAmount.''/trans_id/''.$TransID.''" width="1" height="1" border="0">'';\r\n\r\n#########################################\r\n## END AFF INTEGRATION WITH OSCOMMERCE ##\r\n#########################################\r\necho ''</p>'';</pre>      \r\n  </li>    \r\n  <li>\r\n		<p>Go ahead and Save the File.</p></li>\r\n</ol>', '', 0),
(15, 'bigcommerce', 'bigcommerce', 'bigcommerce.png', '<h3>BigCommerce</h3>\r\n<p>To integrate the affiliate software with BigCommerce, follow these steps:</p>\r\n<ol style="">\r\n	<li>Login to your BigCommerce Admin area.</li>\r\n	<li>Click on <strong>Settings > Affiliate Settings</strong></li>\r\n	<li>Go to the box marked <strong>Conversion Tracking Code</strong>&nbsp;and add your affiliate integration code:<br />\r\n		      \r\n		<pre>&lt;img src="{tracking_url}sale/amount/%%ORDER_SUBTOTAL_DISCOUNTED%%/trans_id/%%ORDER_ID%%" border="1" height="1" width="1" /></pre></li>\r\n	\r\n</ol>', '', 0),
(16, 'xt-commerce', 'xtcommerce', 'xtcommerce.png', '<h3>XT-Commerce</h3>\r\n<p>To integrate the affiliate manager with XT-Commerce, follow these steps:</p>\r\n<ol style="">\r\n	<li>Open up the <span style="font-weight: bold;">checkout_success.php</span>&nbsp;file.</li>\r\n	<li>Add the integration code as specified in the following lines of code.<br />\r\n		\r\n		<pre>$orders_query = xtc_db_query("select orders_id,\r\n                                     orders_status\r\n                              from ".TABLE_ORDERS."\r\n                              where customers_id = ''".$_SESSION[''customer_id'']."''\r\n                              order by orders_id desc limit 1");\r\n$orders = xtc_db_fetch_array($orders_query);\r\n$last_order = $orders[''orders_id''];\r\n$order_status = $orders[''orders_status''];\r\n\r\n//START INTEGRATION CODE\r\nif (!empty($_COOKIE[''{aff_cookie_name}'']))\r\n{\r\n	// get total amount of order\r\n	$jam_sql = "select value from ".TABLE_ORDERS_TOTAL.\r\n		  " where orders_id=''".(int)$last_order.\r\n		  "'' and class=''ot_subtotal''";\r\n	$jam_orders_total_query = xtc_db_query($jam_sql);\r\n	$jam_orders_total = xtc_db_fetch_array($jam_orders_total_query);\r\n	$jam_total_value = $jam_orders_total[''value''];\r\n	\r\n$jam_integrate = file_get_contents(''{tracking_url}sale/amount/'' . $jam_total_value . ''/trans_id/'' . $last_order . ''/tracking_code/'' . $_COOKIE[''{aff_cookie_name}'']);\r\n}\r\n\r\n//END INTEGRATION CODE\r\n\r\n//BOF  - web28 - 2010-03-27 PayPal Bezahl-Link\r\nif (isset($_SESSION[''paypal_link'']) &amp;&amp; MODULE_PAYMENT_PAYPAL_IPN_USE_CHECKOUT == ''True'') {\r\n	$smarty->assign(''PAYPAL_LINK'',$_SESSION[''paypal_link'']);\r\n    unset ($_SESSION[''paypal_link'']);    	\r\n}    </pre>  </li>\r\n	<li><span style="line-height: 1.8em;">Save the file.</span></li>\r\n</ol>', '', 0),
(17, 'volusion', 'volusion', 'volusion.png', '<h3>Volusion</h3>\r\n<p>To integrate the affiliate software with Volusion, follow these steps:</p>\r\n<ol style="">\r\n	<li>From your <strong>Admin Area</strong>, go to <strong>Design &gt; Site Content.   </strong></li>\r\n	<li>Scroll down until you find <strong>Article ID 130</strong>. If the article is not ID 130<em> </em>in your store, use <em>Search</em> to find the article where <strong>Spot Key is ROI_Javascripts.</strong></li>\r\n	<li>Click the Article ID. </li>\r\n	<li>In the <strong>Article Body</strong> field, click the <strong>HTML</strong> icon in the Easy Editor toolbar. </li>\r\n	<li>Enter or paste the following code into the <strong>Article Body </strong>field and click <strong>Save</strong>.<br />\r\n		      \r\n		<pre>\r\n&lt;script>\r\nvar order = Order[0];\r\nvar amount = Order[2];\r\ndocument.write(''&lt;img src="{tracking_url}sale/amount/'' + amount + ''/trans_id/'' + order + ''" border="0" height="1" width="1" />'');\r\n&lt;/script></pre>\r\n	</li>\r\n	\r\n</ol>\r\n', '', 8888),
(18, 'shopify', 'shopify', 'shopify.png', '<h3>Shopify</h3>\r\n<p>To integrate the affiliate manager with Shopify, follow these steps:</p>\r\n<ol style="">\r\n	<li>Login to your Shopify Admin Area.</li>\r\n	<li>Click on <strong>Settings</strong>.</li>\r\n	<li>Click on <strong>Checkout</strong>.</li>\r\n	<li>Scroll down to the <strong>Order Processing</strong> section.</li>\r\n	<li>In the box under <strong>Additional content &amp;scripts</strong>, add the following code:<br />\r\n		\r\n		<pre>&lt;iframe src="{tracking_url}sale/amount/{{subtotal_price|money_without_currency}}/trans_id/{{order_number}}" border="0" scrolling="no" frameborder="0" width="1" height="1">&lt;/iframe></pre>\r\n    </li>\r\n	<li>Save changes</li>\r\n</ol>', '', 8888),
(19, 'magento', 'magento', 'magento.png', '<h3>Magento</h3>\r\n<p>To integrate  with Magento Commerce:\r\n	</p>\r\n<ol type="1" class="whs2">    \r\n	<li class="kadov-p">    \r\n		<p>Open up the file <strong>app/design/frontent/default/default/template/checkout/success.phtml</strong><span style="font-weight: bold;"></span></p>    </li>    \r\n  <li class="kadov-p">    \r\n		<p>Scroll down in the file where it says the following lines of code, and paste your integration code in the area specified:\r\n			\r\n	<pre>&lt;div class="page-head">\r\n&lt;h3>&lt;?php echo $this->__(''Your order has been received'') ?>&lt;/h3>\r\n&lt;/div>\r\n&lt;?php echo $this->getMessagesBlock()->getGroupedHtml() ?>\r\n&lt;p>&lt;strong>&lt;?php echo $this->__(''Thank you for your purchase!'') ?>&lt;/strong>&lt;/p>\r\n&lt;p>\r\n&lt;?php if ($this->canPrint()) :?>\r\n&lt;?php echo $this->__(''Your order # is: &lt;a href="%s">%s&lt;/a>'', $this->getViewOrderUrl(), $this->getOrderId()) ?>.&lt;br/>\r\n&lt;?php else :?>\r\n&lt;?php echo $this->__(''Your order # is: %s'', $this->getOrderId()) ?>.&lt;br/>\r\n&lt;?php endif;?>\r\n\r\n\r\n<strong>&lt;!-- START INTEGRATION CODE -->\r\n&lt;?php \r\n$order = Mage::getModel(''sales/order'')->loadByIncrementId(Mage::getSingleton(''checkout/session'')->getLastRealOrderId());\r\n$amount = number_format($order->subtotal,2); \r\n?>\r\n&lt;img src="{tracking_url}sale/amount/&lt;?php echo $amount; ?>/trans_id/&lt;?php echo $this->getOrderId()?>" />\r\n&lt;!-- END INTEGRATION CODE -->\r\n</strong>\r\n\r\n&lt;?php echo $this->__(''You will receive an order confirmation email with details of your order and a link to track its progress.'') ?>&lt;br/>\r\n&lt;?php if ($this->canPrint()) :?>\r\n&lt;?php echo $this->__(''Click &lt;a href="%s" onclick="this.target=\\''_blank\\''">here to print&lt;/a> a copy of your order confirmation.'', $this->getPrintUrl()) ?>\r\n&lt;?php endif;?>\r\n&lt;/p>\r\n&lt;div class="button-set">\r\n&lt;button class="form-button" onclick="window.location=''&lt;?php echo $this->getUrl() ?>''">&lt;span>&lt;?php echo $this->__(''Continue Shopping'') ?>&lt;/span>&lt;/button>\r\n&lt;/div></pre></p>    </li>    \r\n	<li class="kadov-p">Go ahead and Save the File. </li>    \r\n	<li class="kadov-p">You now have the affiliate software integrated into your Magento Commerce Shopping Cart.    </li>\r\n</ol>', '', 0),
(20, 'pagseguro', 'pagseguro', 'pagseguro.png', '<h3>Pagseguro</h3>\r\n<p>To integrate Pagseguro for commission generation, follow these steps:</p>\r\n<ol style="">\r\n	<li>Open up the Return URL &nbsp;that customers are sent to after payment in Pagseguro.</li>\r\n	<li>Add the following integration code:\r\n		<pre>&lt;img src="{tracking_url}sale/amount/&lt;?=$_POST[''ProdValor_1'']?>/trans_id/&lt;?=$_POST[''TransacaoID'']?>" border="1" height="1" width="1" /></pre></li>\r\n	\r\n	<li>Save the file.</li>\r\n	<li>Run a test by clicking on an affiliate link and purchasing via your Pagseguro button.</li>\r\n</ol>', '', 0),
(21, 'dlguard', 'dlguard', 'dlguard.png', '<h3>DLGuard</h3>\r\n<p>To integrate the affiliate software with the DLGuard script, please follow these steps:</p>\r\n<ol style="">\r\n	<li>Login to your DLGuard Admin Area.</li>\r\n	<li>Click on a product in DLGuard that you want to pay commissions on.</li>\r\n	<li>On the Edit Product page, scroll down to the bottom where the<strong> Extra text/HTML code to be added to download page</strong>: box is.</li>\r\n	<li>Add the following code:\r\n		<pre>&lt;img src="{tracking_url}sale/amount/%%productprice%%/trans_id/%%customerreceipt%%" border="0" width="1" height="1" /></pre></li>\r\n	<li>Save the product. If you want to pay commissions on all products, repeat these steps for each of your products in DLGuard.</li>\r\n  <li>Make sure you enable the Require Unique Transaction IDs Only in <strong>Settings > Global Configuration > Security > Commission Security</strong> in your affiliate admin area to prevent duplicate commissions.</li>\r\n</ol>\r\n<p></p>', '', 0),
(22, 'cartmanager', 'cartmanager', 'cartmanager.png', '<h3>CartManager</h3>\r\n<p>To integrate the affiliate software with CartManager, follow these steps:</p>\r\n<ol style="">\r\n	<li>Login to the admin area in CartManager.</li>\r\n	<li>Once you are logged in, click on the <strong>Advanced Settings </strong>link</li>\r\n	<li>Scroll down to the <strong>HTML For Bottom of Receipt</strong>&nbsp;box.</li>  \r\n	<li>Add the following code in the text area<br />\r\n		    \r\n    \r\n		<pre>&lt;iframe src="{tracking_url}sale/amount/PRINTSUBTOTAL/trans_id/PRINTORDERNUMBER" border="0" scrolling="no" frameborder="0" width="1" height="1">&lt;/iframe></pre>    </li>\r\n	\r\n	<li>You have now integrated the affiliate software with CartManager</li>\r\n</ol>', '', 0),
(23, 'pdgcommerce', 'pdgcommerce', 'pdgcommerce.png', '<h3>PDGCommerce</h3>\r\n<p>To integrate the affiliate software with PDGCommerce, follow these steps:</p>\r\n<ol style="">\r\n	<li>Open up your<strong> /Templates/ThankYou.html and your /Templates/CreditAccept.html </strong>file for editing.</li>\r\n	<li>Scroll down to the bottom right before the ending <span style="font-weight: bold;">&lt;/body></span>&nbsp;tag&nbsp;and add the following code:<br />\r\n		\r\n		<pre>&lt;img src="{tracking_url}sale/amount/!---NO_FORMAT_SUBTOTAL_WITH_DISCOUNT---/trans_id/!---INVOICE---" width="1" height="1" border="0" /> \r\n&lt;/BODY>\r\n  &lt;/HTML></pre></li>\r\n  <li>Reupload the file back to your /Templates folder.</li>\r\n</ol>\r\n', '', 0),
(24, 'cubecart', 'cubecart', 'cubecart.png', '<h3>Cubecart</h3>\r\n<p>To integrate the affiliate software with Cubecart, follow these steps:</p>\r\n<ol style="">\r\n	<li>Login to your Cubecart admin area.</li>\r\n  <li>Scroll down to the <strong>modules</strong> section, and click on<strong> affiliate trackers</strong></li>\r\n  <li>Click on the <strong>Affiliate Manager</strong> option.</li>\r\n	<li>Enter your affiliate URL in the box<br />\r\n	  \r\n	  <pre>{tracking_url}</pre>\r\n  </li>\r\n  <li>Click <strong>Save</strong></li>\r\n</ol>\r\n', '', 0),
(25, 'weebly', 'weebly', 'weebly.png', '<h3>Weebly</h3>\r\n<p>To integrate Affiliate Manager with Weebly, follow these steps:</p>\r\n<ol>\r\n  <li>Login to Weebly and edit your Site.</li>\r\n  <li>Click on the <strong>Store</strong> menu, and go to <strong>Settings &gt; Advanced</strong>.</li>\r\n  <li>On the Advanced Settings page, click on <strong>Add Custom Tracking Code</strong>.</li>\r\n  <li>Enter the following code:<br>\r\n    <br>\r\n    <pre>&lt;iframe src="{tracking_url}sale/amount/{total}/trans_id/{txid}" border="0" scrolling="no" frameborder="0" width="1" height="1">&lt;/iframe></pre>\r\n  </li>\r\n  <li>Click <strong>Update Changes</strong></li>\r\n</ol>\r\n', '', 9998);
{{{~~~}}}
DROP TABLE IF EXISTS `jam_report_archive`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_report_archive` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `report_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `report_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `report_html` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_scaled_commissions`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_scaled_commissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` enum('flat','percent') NOT NULL DEFAULT 'flat',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `min_amount` varchar(25) NOT NULL DEFAULT '0',
  `max_amount` varchar(25) NOT NULL DEFAULT '0',
  `comm_amount` varchar(25) NOT NULL,
  `level` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_security`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_security` (
  `ip` varchar(25) NOT NULL DEFAULT '',
  `date` varchar(25) NOT NULL DEFAULT '',
  `type` varchar(25) NOT NULL DEFAULT '',
  `member_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_sessions`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `session_data` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`session_id`),
  KEY `JROX_ACTIVITY_IDX` (`ip_address`,`user_agent`,`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_settings`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_settings` (
  `settings_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `settings_value` text COLLATE utf8_unicode_ci NOT NULL,
  `settings_module` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `settings_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `settings_group` int(10) DEFAULT NULL,
  `settings_sort_order` int(10) NOT NULL DEFAULT '0',
  `settings_function` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`settings_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
{{{~~~}}}
INSERT INTO `jam_settings` (`settings_key`, `settings_value`, `settings_module`, `settings_type`, `settings_group`, `settings_sort_order`, `settings_function`) VALUES
('sts_site_payment_country', '223', 'settings', 'dropdown', 1, 6, 'countries'),
('sts_site_payment_state', 'CA', 'settings', 'text', 1, 4, 'none'),
('sts_site_payment_zip', '123456', 'settings', 'text', 1, 5, 'none'),
('sts_site_payment_city', 'los angeles', 'settings', 'text', 1, 3, 'none'),
('sts_site_payment_address', '123 test drive', 'settings', 'text', 1, 2, 'none'),
('sts_admin_date_format', 'mm/dd/yyyy:m/d/Y:M d Y', 'settings', 'dropdown', 8, 2, 'date_format'),
('sts_admin_default_language', 'english', 'settings', 'dropdown', 8, 1, 'language'),
('sts_admin_enable_member_graphs', '1', 'settings', 'dropdown', 8, 99, 'boolean'),
('sts_admin_enable_wysiwyg_content', '1', 'settings', 'dropdown', 8, 4, 'boolean'),
('sts_admin_image_height', '200', 'settings', 'text', 10, 7, 'none'),
('sts_admin_image_resize', '1', 'settings', 'dropdown', 10, 6, 'boolean'),
('sts_admin_image_width', '200', 'settings', 'text', 10, 8, 'none'),
('sts_admin_layout_theme', 'default', 'system', 'hidden', 8, 8, 'none'),
('sts_admin_time_format', 'm-d-Y h:i:s A', 'settings', 'text', 8, 3, 'none'),
('sts_admin_show_dashboard_video', '1', 'settings', 'dropdown', 8, 30, 'yes_no'),
('sts_affiliate_enable_mlm_forced_matrix', '0', 'settings', 'dropdown', 26, 1, 'boolean'),
('sts_tracking_enable_lifetime_tracking', '1', 'settings', 'dropdown', 20, 4, 'boolean'),
('sts_affiliate_enable_referral_signup_bonus', '0', 'settings', 'dropdown', 14, 9, 'boolean'),
('sts_affiliate_enable_referral_signup_bonus_amount', '', 'settings', 'text', 14, 10, 'none'),
('sts_affiliate_enable_replication', '0', 'settings', 'dropdown', 13, 16, 'boolean'),
('sts_affiliate_enable_signup_bonus', '0', 'settings', 'dropdown', 14, 7, 'boolean'),
('sts_affiliate_enable_signup_bonus_amount', '10', 'settings', 'text', 14, 8, 'none'),
('sts_affiliate_enable_wysiwyg_email', '1', 'settings', 'dropdown', 16, 99, 'boolean'),
('sts_affiliate_image_auto_resize', '1', 'settings', 'dropdown', 10, 9, 'boolean'),
('sts_affiliate_image_height', '200', 'settings', 'text', 10, 10, 'none'),
('sts_affiliate_image_width', '200', 'settings', 'text', 10, 11, 'none'),
('sts_affiliate_commission_levels_restrict_view', '10', 'settings', 'dropdown', 2, 32, 'numbers_10'),
('sts_affiliate_link_type', 'regular', 'settings', 'dropdown', 2, 5, 'affiliate_link_type'),
('sts_affiliate_min_payment', '100', 'settings', 'text', 2, 99, 'none'),
('sts_affiliate_mlm_matrix_width', '5', 'settings', 'dropdown', 26, 3, 'numbers_5'),
('sts_affiliate_refund_commission_type', 'delete', 'settings', 'dropdown', 2, 99, 'commission_refund_option'),
('sts_affiliate_replication_enable_header_footer', '1', 'settings', 'dropdown', 13, 16, 'boolean'),
('sts_affiliate_restrict_subdomains', 'www,ftp,mail,webmail', 'settings', 'text', 2, 5, 'none'),
('sts_affiliate_show_downline_details', '1', 'settings', 'dropdown', 16, 99, 'boolean'),
('sts_affiliate_show_downline_emails', '1', 'settings', 'dropdown', 16, 99, 'boolean'),
('sts_affiliate_show_downline_photos', '0', 'system', 'hidden', 16, 99, 'none'),
('sts_affiliate_show_pending_comms_members', '1', 'settings', 'dropdown', 16, 35, 'boolean'),
('sts_affiliate_tools_per_page', '5', 'settings', 'text', 13, 99, 'none'),
('sts_backup_path', '{{pubpath}}/backup', 'settings', 'text', 25, 2, 'none'),
('sts_content_document_charset', 'UTF-8', 'settings', 'text', 4, 90, 'none'),
('sts_content_html_editor', 'innovaeditor', 'settings', 'dropdown', 4, 15, 'editor'),
('sts_content_members_dashboard_enable', '1', 'settings', 'dropdown', 19, 20, 'boolean'),
('sts_content_members_dashboard_enable_account_details', '1', 'settings', 'dropdown', 19, 21, 'boolean'),
('sts_content_members_dashboard_enable_commissions', '1', 'settings', 'dropdown', 19, 22, 'boolean'),
('sts_content_members_dashboard_enable_content', '1', 'settings', 'dropdown', 19, 23, 'boolean'),
('sts_content_members_dashboard_enable_downline', '0', 'settings', 'dropdown', 19, 24, 'boolean'),
('sts_content_members_dashboard_enable_downloads', '1', 'settings', 'dropdown', 19, 25, 'boolean'),
('sts_content_members_dashboard_enable_reports', '1', 'settings', 'dropdown', 19, 26, 'boolean'),
('sts_content_members_dashboard_enable_tools', '1', 'settings', 'dropdown', 19, 27, 'boolean'),
('sts_content_members_dashboard_enable_traffic', '1', 'settings', 'dropdown', 19, 28, 'boolean'),
('sts_content_members_dashboard_num_articles', '5', 'settings', 'text', 19, 50, 'none'),
('sts_cron_password_key', '{{cron_key}}', 'system', 'hidden', 1, 1, 'none'),
('sts_email_auto_prune_archive', '30', 'settings', 'text', 5, 78, 'none'),
('sts_email_bounce_password', '', 'settings', 'text', 5, 79, 'none'),
('sts_email_bounce_port', '110', 'settings', 'text', 5, 80, 'none'),
('sts_email_bounce_server', '', 'settings', 'text', 5, 77, 'none'),
('sts_email_bounce_service_flags', '/pop3/notls', 'settings', 'text', 5, 81, 'none'),
('sts_email_bounce_username', '', 'settings', 'text', 5, 78, 'none'),
('sts_email_charset', 'UTF-8', 'settings', 'text', 5, 8, 'none'),
('sts_email_enable_archive', '0', 'settings', 'dropdown', 5, 77, 'boolean'),
('sts_email_enable_debugging', '0', 'settings', 'dropdown', 5, 55, 'boolean'),
('sts_email_enable_ssl', 'none', 'settings', 'dropdown', 5, 65, 'ssl_tls'),
('sts_email_limit_mass_mailing', '1000', 'settings', 'text', 5, 9, 'none'),
('sts_email_mailer_type', 'php', 'settings', 'dropdown', 5, 1, 'mailer_type'),
('sts_email_send_queue', '0', 'settings', 'dropdown', 5, 45, 'boolean'),
('sts_email_show_email_content_queue', '1', 'settings', 'dropdown', 5, 85, 'boolean'),
('sts_email_smtp_host', 'mail.{{base_domain_name}}', 'settings', 'text', 5, 3, 'none'),
('sts_email_smtp_password', '', 'settings', 'text', 5, 5, 'none'),
('sts_email_smtp_port', '26', 'settings', 'text', 5, 6, 'none'),
('sts_email_smtp_timeout', '60', 'settings', 'text', 5, 7, 'none'),
('sts_email_smtp_username', '', 'settings', 'text', 5, 4, 'none'),
('sts_email_use_smtp_authentication', '0', 'settings', 'dropdown', 5, 2, 'boolean'),
('sts_admin_use_google_visualization_api_admin', '1', 'settings', 'dropdown', 8, 10, 'boolean'),
('sts_affiliate_show_active_downline_users', '1', 'settings', 'dropdown', 2, 5, 'boolean'),
('sts_tracking_enable_ip_address', '0', 'settings', 'dropdown', 20, 2, 'boolean'),
('sts_click_security_block_ip', '', 'settings', 'textarea', 21, 1, 'none'),
('sts_image_library', 'GD2', 'settings', 'dropdown', 10, 1, 'image_library'),
('sts_image_maintain_ratio', '1', 'settings', 'dropdown', 10, 2, 'boolean'),
('sts_image_max_photo_size', '2048', 'settings', 'text', 10, 3, 'none'),
('sts_sec_admin_failed_login_email', '{{admin_email}}', 'settings', 'text', 6, 1, 'none'),
('sts_sec_admin_restrict_ip', '', 'settings', 'text', 6, 3, 'none'),
('sts_sec_auto_ip_block_interval', '10', 'settings', 'text', 6, 89, 'none'),
('sts_sec_enable_admin_restrict_ip', '0', 'settings', 'dropdown', 6, 2, 'boolean'),
('sts_sec_enable_auto_ip_block', '0', 'settings', 'dropdown', 6, 88, 'boolean'),
('sts_sec_enable_captcha', '0', 'settings', 'dropdown', 6, 90, 'boolean'),
('sts_tracking_enable_sessions', '0', 'settings', 'dropdown', 20, 3, 'boolean'),
('sts_sec_site_block_free_email_accounts', 'hotmail.com\ngmail.com', 'settings', 'textarea', 6, 78, 'none'),
('sts_sec_site_enable_block_free_email_accounts', '0', 'settings', 'dropdown', 6, 77, 'boolean'),
('sts_sec_site_enable_form_flood_control', '1', 'settings', 'dropdown', 6, 65, 'boolean'),
('sts_sec_site_form_flood_control_interval', '2', 'settings', 'text', 6, 66, 'none'),
('sts_sec_site_restrict_ips', '', 'settings', 'textarea', 6, 64, 'none'),
('sts_sale_security_block_ip', '', 'settings', 'textarea', 22, 1, 'none'),
('sts_site_default_country', '223', 'settings', 'dropdown', 1, 9, 'countries'),
('sts_site_default_currency', 'USD', 'settings', 'dropdown', 1, 7, 'currencies'),
('sts_site_default_language', 'english', 'settings', 'dropdown', 1, 6, 'language'),
('sts_site_default_timezone', 'UM8', 'settings', 'dropdown', 11, 8, 'timezone_menu'),
('sts_site_disable_registration', '0', 'settings', 'dropdown', 3, 1, 'boolean'),
('sts_site_email', '{{system_email}}', 'settings', 'text', 1, 4, 'none'),
('sts_site_enable_downline_cache', '0', 'settings', 'dropdown', 11, 82, 'boolean'),
('sts_site_enable_downline_cache_minutes', '5', 'settings', 'dropdown', 11, 83, 'numbers_10'),
('sts_site_enable_language_selector', '1', 'settings', 'dropdown', 11, 1, 'boolean'),
('sts_site_jam_installation_id', '{{jamcode}}', 'system', 'hidden', 1, 99, 'none'),
('sts_site_key', '', 'settings', 'text', 15, 99, 'none'),
('sts_local_key', '', 'system', 'hidden', 15, 99, 'none'),
('sts_site_name', '{{base_domain_name}}', 'settings', 'text', 1, 1, 'none'),
('sts_site_set_curl_timeout', '5', 'settings', 'text', 11, 50, 'none'),
('sts_site_time_format', 'm-d-Y h:i:s A', 'settings', 'text', 1, 9, 'none'),
('sts_tracking_auto_prune_days', '365', 'settings', 'text', 13, 67, 'none'),
('system_enable_gs_config', '', 'system', 'hidden', 1, 1, 'none'),
('system_enable_rs_config', '', 'system', 'hidden', 1, 99, 'none'),
('default_theme', 'default', 'settings', 'dropdown', 1, 10, 'themes'),
('sts_site_default_home_page', 'login', 'settings', 'dropdown', 1, 11, 'default_home_page'),
('sts_site_enable_custom_login', '0', 'settings', 'dropdown', 1, 12, 'boolean'),
('sts_site_url_redirect_login', 'login', 'settings', 'text', 1, 13, 'none'),
('sts_site_enable_custom_signup', '0', 'settings', 'dropdown', 1, 14, 'boolean'),
('sts_site_url_redirect_signup', '', 'settings', 'text', 1, 15, 'none'),
('sts_sale_security_monitor_duplicate_trans_id', '', 'settings', 'text', 22, 2, 'none'),
('sts_sale_security_restrict_ip', '', 'settings', 'textarea', 22, 3, 'none'),
('sts_affiliate_forced_matrix_spillover', 'none', 'settings', 'dropdown', 26, 5, 'forced_matrix_spillover'),
('sts_affiliate_forced_matrix_affiliate_id', '45', 'settings', 'text', 26, 7, 'none'),
('sts_affiliate_require_referral_code', '0', 'settings', 'dropdown', 2, 5, 'boolean'),
('sts_affiliate_cookie_timer', '365', 'settings', 'text', 20, 7, 'none'),
('sts_site_facebook_post_on_wall', 'just registered on {affiliate_link} - {reseller_link}', 'settings', 'textarea', 23, 7, 'none'),
('sts_affiliate_alert_payment_sent', '1', 'settings', 'dropdown', 16, 5, 'boolean'),
('sts_affiliate_alert_downline_signup', '1', 'settings', 'dropdown', 16, 4, 'boolean'),
('sts_site_disable_login', '0', 'settings', 'dropdown', 1, 10, 'yes_no'),
('sts_sec_require_email_confirmation', '1', 'settings', 'dropdown', 6, 45, 'boolean'),
('sts_sec_require_admin_approval', '0', 'settings', 'dropdown', 6, 46, 'boolean'),
('sts_auto_login_key', '{{secret}}', 'settings', 'text', 24, 2, 'none'),
('sts_auto_login_enable_signup_login', '0', 'settings', 'dropdown', 24, 1, 'boolean'),
('sts_auto_login_secret', '{{secret2}}', 'settings', 'text', 24, 3, 'none'),
('sts_auto_login_redirect_login', '', 'settings', 'text', 24, 4, 'none'),
('sts_auto_login_redirect_registration', '', 'settings', 'text', 24, 5, 'none'),
('sts_video_player_height', '80', 'settings', 'text', 18, 20, 'none'),
('sts_video_player_width', '50', 'settings', 'text', 18, 21, 'none'),
('sts_video_player_logo', '{{base_url}}/images/misc/vlogo.png', 'settings', 'text', 18, 24, 'none'),
('sts_video_player_front_color', '05080f', 'settings', 'text', 18, 25, 'none'),
('sts_video_player_screen_color', 'ffffff', 'settings', 'text', 18, 26, 'colorpicker'),
('sts_video_player_back_color', 'ffffff', 'settings', 'text', 18, 27, 'none'),
('system_rs_config_data', '', 'system', 'hidden', 1, 99, 'none'),
('sts_site_facebook_app_id', '', 'settings', 'text', 23, 2, 'none'),
('sts_site_facebook_app_secret', '', 'settings', 'text', 23, 3, 'none'),
('sts_site_upload_photo_types', 'gif|jpg|png|swf|jpeg', 'settings', 'text', 10, 5, 'none'),
('enable_facebook_connect', '0', 'settings', 'dropdown', 23, 1, 'boolean'),
('sts_site_showcase_multiple_programs', '1', 'system', 'dropdown', 2, 1, 'boolean'),
('sts_click_security_block_clicks_same_ip', '0', 'settings', 'dropdown', 21, 2, 'boolean'),
('sts_site_image_auto_resize', '1', 'settings', 'dropdown', 10, 11, 'boolean'),
('sts_site_image_height', '150', 'settings', 'text', 10, 12, 'none'),
('sts_site_image_width', '150', 'settings', 'text', 10, 13, 'none'),
('sts_backup_enable_schedule', '0', 'settings', 'dropdown', 25, 1, 'boolean'),
('sts_sec_recaptcha_public_key', '', 'settings', 'text', 6, 91, 'none'),
('sts_sec_recaptcha_private_key', '', 'settings', 'text', 6, 92, 'none'),
('sts_sec_recaptcha_theme', 'white', 'settings', 'text', 6, 93, 'none'),
('sts_site_facebook_page_code', 'PGlmcmFtZSBzcmM9Imh0dHA6Ly93d3cuZmFjZWJvb2suY29tL3BsdWdpbnMvbGlrZWJveC5waHA/aWQ9OTI3NzI4MDQ4MDQmYW1wO3dpZHRoPTM0MCZhbXA7Y29ubmVjdGlvbnM9MzYmYW1wO3N0cmVhbT1mYWxzZSZhbXA7c2hvd19ib3JkZXI9ZmFsc2UmYW1wO2hlYWRlcj1mYWxzZSZhbXA7aGVpZ2h0PTUwMCIgc2Nyb2xsaW5nPSJubyIgZnJhbWVib3JkZXI9IjAiIHN0eWxlPSJib3JkZXI6bm9uZTsgb3ZlcmZsb3c6aGlkZGVuOyB3aWR0aDozMzhweDsgaGVpZ2h0OjUwMHB4OyIgYWxsb3dUcmFuc3BhcmVuY3k9InRydWUiPjwvaWZyYW1lPg==', 'settings', 'textarea', 23, 4, 'base64_decode'),
('sts_content_members_dashboard_quick_stats', '1', 'settings', 'dropdown', 19, 52, 'boolean'),
('sts_content_members_dashboard_latest_referrals', '1', 'settings', 'dropdown', 19, 53, 'boolean'),
('sts_content_members_dashboard_social_sharing', '1', 'settings', 'dropdown', 19, 50, 'boolean'),
('sts_content_members_dashboard_latest_commissions', '1', 'settings', 'dropdown', 19, 54, 'boolean'),
('sts_content_members_dashboard_enable_programs', '0', 'settings', 'dropdown', 19, 41, 'boolean'),
('sts_content_members_dashboard_enable_referral_payments', '1', 'settings', 'dropdown', 19, 42, 'boolean'),
('sts_content_members_video_tutorials', '', 'settings', 'text', 19, 60, 'none'),
('sts_content_members_help_link', '', 'settings', 'text', 19, 61, 'none'),
('sts_content_members_docs_link', '{{home_base_url}}/docs/', 'settings', 'text', 19, 62, 'none'),
('sts_affiliate_allow_downline_view', '1', 'settings', 'dropdown', 16, 3, 'boolean'),
('sts_affiliate_allow_upload_photos', '0', 'settings', 'dropdown', 16, 43, 'boolean'),
('sts_sale_require_unique_trans_id', '1', 'settings', 'dropdown', 22, 4, 'boolean'),
('sts_affiliate_allow_downline_email', '0', 'settings', 'dropdown', 16, 45, 'boolean'),
('sts_affiliate_show_downloads', '1', 'settings', 'dropdown', 16, 46, 'boolean'),
('sts_affiliate_allow_expandable_downlines', '0', 'settings', 'dropdown', 16, 56, 'boolean'),
('sts_affiliate_restrict_self_commission', '1', 'settings', 'dropdown', 2, 25, 'boolean'),
('sts_content_members_dashboard_total_stats', '1', 'settings', 'dropdown', 19, 52, 'boolean'),
('sts_content_members_dashboard_calendar', '1', 'settings', 'dropdown', 19, 55, 'boolean'),
('sts_affiliate_new_commission', 'alert_pending', 'settings', 'dropdown', 2, 4, 'pending_commission'),
('sts_site_use_daylight_savings_time', '1', 'settings', 'dropdown', 11, 9, 'boolean'),
('sts_affiliate_ovewrite_existing_cookie', '1', 'settings', 'dropdown', 20, 11, 'boolean'),
('sts_integration_enable_debug_email', '0', 'settings', 'dropdown', 20, 50, 'boolean'),
('sts_affiliate_allow_javascript_info', '1', 'settings', 'dropdown', 13, 45, 'boolean'),
('sts_impressions_auto_prune_days', '365', 'settings', 'text', 13, 68, 'none'),
('sts_site_upload_max_filesize', '50M', 'settings', 'text', 27, 2, 'none'),
('sts_site_max_execution_time', '3600', 'settings', 'text', 27, 3, 'none'),
('sts_site_download_file_path', '{{pubpath}}/import/downloads/', 'settings', 'text', 27, 1, 'none'),
('sts_site_download_allowed_file_types', 'zip|pdf|doc|txt', 'settings', 'text', 27, 4, 'none'),
('sts_mx_key', '', 'settings', 'text', 15, 100, 'none'),
('sts_local_mx_key', '', 'system', 'hidden', 15, 100, 'none'),
('sts_affiliate_alert_new_commission', '1', 'settings', 'dropdown', 16, 6, 'boolean'),
('sts_site_script_memory_limit', '256M', 'settings', 'text', 11, 99, 'none'),
('sts_site_ssl_public_area', '0', 'settings', 'dropdown', 11, 101, 'boolean'),
('sts_site_ssl_members_area', '0', 'settings', 'dropdown', 11, 102, 'boolean'),
('sts_site_ssl_admin_area', '0', 'settings', 'dropdown', 11, 102, 'boolean'),
('sts_affiliate_enable_replication_cache', '', 'settings', 'text', 13, 17, 'none'),
('sts_affiliate_enable_ad_trackers', '', 'settings', 'dropdown', 16, 47, 'boolean'),
('sts_content_translate_menus', '0', 'settings', 'dropdown', 4, 72, 'boolean'),
('layout_theme_members_default_dashboard_template', 'tpl_members_dashboard', 'layout', 'dropdown', 21, 44, 'members_dashboard_template'),
('module_affiliate_marketing_invisilinks_alert_email', '{{system_email}}', 'affiliate_marketing', 'text', 39, 1, 'none'),
('module_affiliate_marketing_banners_file_types', 'jpg|gif|png|swf', 'affiliate_marketing', 'text', 2, 1, 'none'),
('module_affiliate_marketing_html_ads_default_html_ad_width', '250', 'affiliate_marketing', 'text', 4, 1, 'none'),
('module_affiliate_marketing_text_ads_default_text_ad_width', '150', 'affiliate_marketing', 'text', 3, 1, 'none'),
('module_affiliate_marketing_viral_pdfs_orientation', 'portrait', 'affiliate_marketing', 'dropdown', 5, 1, 'paper_orientation'),
('module_affiliate_marketing_viral_pdfs_paper_size', 'letter', 'affiliate_marketing', 'dropdown', 5, 1, 'paper_size'),
('module_data_export_affiliate_payments_delimiter', ',', 'data_export', 'text', 16, 1, 'none'),
('module_data_export_affiliate_payments_starting_rows', '0', 'data_export', 'text', 16, 3, 'none'),
('module_data_export_affiliate_payments_total_rows', '500', 'data_export', 'text', 16, 2, 'none'),
('module_data_export_commissions_delimiter', ',', 'data_export', 'text', 15, 1, 'none'),
('module_data_export_commissions_starting_rows', '0', 'data_export', 'text', 15, 3, 'none'),
('module_data_export_commissions_total_rows', '1000', 'data_export', 'text', 15, 2, 'none'),
('module_data_export_members_delimiter', ',', 'data_export', 'text', 14, 1, 'none'),
('module_data_export_members_starting_rows', '0', 'data_export', 'text', 14, 3, 'none'),
('module_data_export_members_total_rows', '1000', 'data_export', 'text', 14, 2, 'none'),
('module_data_import_jam_affiliate_limit', '1000', 'data_import', 'text', 17, 2, 'none'),
('module_data_import_jam_affiliate_offset', '0', 'data_import', 'text', 17, 3, 'none'),
('module_data_import_jam_segment_affiliates', '1', 'data_import', 'dropdown', 17, 1, 'boolean'),
('module_data_import_members_delimiter', 'comma', 'data_import', 'dropdown', 18, 1, 'delimiter'),
('module_data_import_members_generate_new_ids', '1', 'data_import', 'dropdown', 18, 2, 'boolean'),
('module_affiliate_payment_paypal_mass_payment_currency', 'USD', 'affiliate_payment', 'text', 9, 6, 'none'),
('module_affiliate_payment_paypal_mass_payment_total_rows', '10', 'affiliate_payment', 'text', 9, 5, 'none'),
('module_affiliate_payment_paypal_mass_payment_exclude_minimum', '0', 'affiliate_payment', 'dropdown', 9, 4, 'yes_no'),
('module_affiliate_payment_paypal_mass_payment_start_date', '', 'affiliate_payment', 'text', 9, 2, 'date'),
('module_affiliate_payment_paypal_mass_payment_end_date', '', 'affiliate_payment', 'text', 9, 3, 'date'),
('module_affiliate_payment_paypal_mass_payment_use_date_range', '0', 'affiliate_payment', 'dropdown', 9, 1, 'yes_no'),
('module_affiliate_payment_paypal_mass_payment_payment_details', 'affiliate payment', 'affiliate_payment', 'textarea', 9, 7, 'none'),
('module_affiliate_payment_moneybookers_mass_payment_use_date_range', '0', 'affiliate_payment', 'dropdown', 12, 1, 'yes_no'),
('module_affiliate_payment_moneybookers_mass_payment_start_date', '', 'affiliate_payment', 'text', 12, 2, 'date'),
('module_affiliate_payment_moneybookers_mass_payment_end_date', '', 'affiliate_payment', 'text', 12, 3, 'date'),
('module_affiliate_payment_moneybookers_mass_payment_exclude_minimum', '0', 'affiliate_payment', 'dropdown', 12, 4, 'yes_no'),
('module_affiliate_payment_moneybookers_mass_payment_total_rows', '10', 'affiliate_payment', 'text', 12, 5, 'none'),
('module_affiliate_payment_moneybookers_mass_payment_currency', 'USD', 'affiliate_payment', 'text', 12, 6, 'none'),
('module_affiliate_payment_moneybookers_mass_payment_payment_details', 'affiliate payment', 'affiliate_payment', 'textarea', 12, 7, 'none'),
('module_affiliate_payment_payza_mass_payment_use_date_range', '0', 'affiliate_payment', 'dropdown', 11, 1, 'yes_no'),
('module_affiliate_payment_payza_mass_payment_start_date', '', 'affiliate_payment', 'text', 11, 2, 'date'),
('module_affiliate_payment_payza_mass_payment_end_date', '', 'affiliate_payment', 'text', 11, 3, 'date'),
('module_affiliate_payment_payza_mass_payment_exclude_minimum', '0', 'affiliate_payment', 'dropdown', 11, 4, 'yes_no'),
('module_affiliate_payment_payza_mass_payment_total_rows', '10', 'affiliate_payment', 'text', 11, 5, 'none'),
('module_affiliate_payment_payza_mass_payment_payment_details', 'affiliate payment', 'affiliate_payment', 'textarea', 11, 6, 'none'),
('module_affiliate_payment_print_invoice_use_date_range', '0', 'affiliate_payment', 'dropdown', 10, 1, 'yes_no'),
('module_affiliate_payment_print_invoice_start_date', '', 'affiliate_payment', 'text', 10, 2, 'date'),
('module_affiliate_payment_print_invoice_end_date', '', 'affiliate_payment', 'text', 10, 3, 'date'),
('module_affiliate_payment_print_invoice_exclude_minimum', '0', 'affiliate_payment', 'dropdown', 10, 4, 'yes_no'),
('module_affiliate_payment_print_invoice_total_rows', '100', 'affiliate_payment', 'text', 10, 5, 'none'),
('module_affiliate_payment_print_invoice_payment_details', 'december 2010', 'affiliate_payment', 'textarea', 10, 6, 'none'),
('module_affiliate_payment_print_checks_use_date_range', '0', 'affiliate_payment', 'dropdown', 13, 1, 'yes_no'),
('module_affiliate_payment_print_checks_start_date', '', 'affiliate_payment', 'text', 13, 2, 'date'),
('module_affiliate_payment_print_checks_end_date', '', 'affiliate_payment', 'text', 13, 3, 'date'),
('module_affiliate_payment_print_checks_exclude_minimum', '0', 'affiliate_payment', 'dropdown', 13, 4, 'yes_no'),
('module_affiliate_payment_print_checks_total_rows', '10', 'affiliate_payment', 'text', 13, 5, 'none'),
('module_affiliate_payment_print_checks_payment_details', 'affiliate payment', 'affiliate_payment', 'textarea', 13, 6, 'none'),
('module_affiliate_payment_dwolla_payment_use_date_range', '0', 'affiliate_payment', 'dropdown', 40, 1, 'yes_no'),
('module_affiliate_payment_dwolla_payment_start_date', '', 'affiliate_payment', 'text', 40, 2, 'date'),
('module_affiliate_payment_dwolla_payment_end_date', '', 'affiliate_payment', 'text', 40, 3, 'date'),
('module_affiliate_payment_dwolla_payment_exclude_minimum', '0', 'affiliate_payment', 'dropdown', 40, 4, 'yes_no'),
('module_affiliate_payment_dwolla_payment_total_rows', '100', 'affiliate_payment', 'text', 40, 5, 'none'),
('module_affiliate_payment_dwolla_payment_payment_details', 'affiliate payment', 'affiliate_payment', 'textarea', 40, 6, 'none'),
('module_affiliate_payment_dwolla_payment_api_key', '', 'affiliate_payment', 'text', 40, 7, 'encrypt'),
('module_affiliate_payment_dwolla_payment_api_secret', '', 'affiliate_payment', 'text', 40, 8, 'encrypt'),
('module_affiliate_payment_dwolla_payment_api_token', '', 'affiliate_payment', 'text', 40, 9, 'encrypt'),
('module_affiliate_payment_dwolla_payment_api_pin', '', 'affiliate_payment', 'text', 40, 10, 'encrypt'),
('module_affiliate_payment_coinbase_payment_use_date_range', '0', 'affiliate_payment', 'dropdown', 41, 1, 'yes_no'),
('module_affiliate_payment_coinbase_payment_start_date', '', 'affiliate_payment', 'text', 41, 2, 'date'),
('module_affiliate_payment_coinbase_payment_end_date', '', 'affiliate_payment', 'text', 41, 3, 'date'),
('module_affiliate_payment_coinbase_payment_exclude_minimum', '0', 'affiliate_payment', 'dropdown', 41, 4, 'yes_no'),
('module_affiliate_payment_coinbase_payment_total_rows', '10', 'affiliate_payment', 'text', 41, 5, 'none'),
('module_affiliate_payment_coinbase_payment_api_key', '', 'affiliate_payment', 'text', 41, 6, 'encrypt'),
('module_affiliate_payment_coinbase_payment_api_secret', '', 'affiliate_payment', 'text', 41, 7, 'encrypt'),
('module_affiliate_payment_coinbase_payment_currency', 'USD', 'affiliate_payment', 'text', 41, 8, 'none'),
('module_affiliate_payment_coinbase_payment_user_fee', '0', 'affiliate_payment', 'text', 41, 9, 'none'),
('module_affiliate_payment_coinbase_payment_payment_details', 'affiliate payment', 'affiliate_payment', 'textarea', 41, 10, 'none'),
('module_mailing_list_mailchimp_api_key', '', 'mailing_list', 'text', 42, 1, 'encrypt'),
('module_mailing_list_mailchimp_list_id', '', 'mailing_list', 'text', 42, 2, 'none'),
('module_mailing_list_mailchimp_double_optin', '1', 'mailing_list', 'dropdown', 42, 3, 'boolean'),
('module_mailing_list_mailchimp_update_existing', '1', 'mailing_list', 'dropdown', 42, 4, 'boolean'),
('module_mailing_list_mailchimp_send_welcome', '1', 'mailing_list', 'dropdown', 42, 5, 'boolean'),
('module_mailing_list_getresponse_api_url', 'http://api2.getresponse.com', 'mailing_list', 'text', 43, 1, 'none'),
('module_mailing_list_getresponse_api_key', '', 'mailing_list', 'text', 43, 2, 'encrypt'),
('module_mailing_list_getresponse_campaign', '', 'mailing_list', 'text', 43, 3, 'none'),
('module_mailing_list_constant_contact_list_id', '', 'mailing_list', 'text', 44, 4, 'none'),
('module_mailing_list_constant_contact_api_url', 'http://api2.constant_contact.com', 'mailing_list', 'text', 44, 1, 'none'),
('module_mailing_list_constant_contact_api_key', '', 'mailing_list', 'text', 44, 2, 'encrypt'),
('module_mailing_list_constant_contact_access_token', '', 'mailing_list', 'text', 44, 3, 'encrypt');
{{{~~~}}}
DROP TABLE IF EXISTS `jam_tracking`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_tracking` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `program_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `member_id` int(10) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cost` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `cost_type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `recur` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_tracking_log`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_tracking_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` varchar(25) NOT NULL,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `tracking_id` text NOT NULL,
  `ip_address` varchar(25) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
{{{~~~}}}
DROP TABLE IF EXISTS `jam_traffic`;
{{{~~~}}}
CREATE TABLE IF NOT EXISTS `jam_traffic` (
  `traffic_id` int(25) NOT NULL AUTO_INCREMENT,
  `date` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `member_id` int(25) NOT NULL DEFAULT '0',
  `program_id` int(10) NOT NULL,
  `tracking_code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `tool_type` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `tool_id` int(25) NOT NULL DEFAULT '0',
  `referrer` text COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_agent` text COLLATE utf8_unicode_ci NOT NULL,
  `os` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `browser` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `isp` text COLLATE utf8_unicode_ci NOT NULL,
  `tracker` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `region` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `country_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `country_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `coordinates` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`traffic_id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
