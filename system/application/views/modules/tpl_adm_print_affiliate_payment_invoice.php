<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" class="table_2">
  <tr>
    <td>
    
        
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="50%" align="left" valign="top">
        <h3 style="margin-bottom:5px;"><?=$this->config->item('sts_site_name')?></h3>
		<?=$this->config->item('sts_site_payment_address')?><br />
		<?=$this->config->item('sts_site_payment_city')?>, <?=$this->config->item('sts_site_payment_state')?> <?=$this->config->item('sts_site_payment_zip')?><br /> 
		<?=_get_country_name($this->config->item('sts_site_payment_country'), 'country_name')?>        </td>
        
        <td width="50%" align="right" valign="top">
        <h3 style="margin-bottom:5px; text-transform:capitalize"><?=$this->lang->line('affiliate_payment_invoice')?></h3> 
        <span style="text-transform:capitalize"><?=$this->lang->line('payment_date')?> -  <?=_show_date($current_date)?><br />
        <br />
		<br />
		</span>
          </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="right">
    <hr width="100%" size="1" noshade="noshade"/>
    </td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="49%" valign="top" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="invoice_box_1" style="margin-right: 9px;">
          <tr>
            <td valign="top">
            <div class="account_info"><?=$this->lang->line('account_information')?></div>
            <?=$fname?> <?=$lname?><br />
			<?=$company?><br />
			<?=$billing_address_1?><br />
			<?=$billing_address_2?><br />
			<?=$billing_city?> <?=$billing_state?> <?=$billing_postal_code?><br />
			<?=_get_country_name($billing_country, 'country_name')?><br />
			</td>
          </tr>
        </table></td>
        <td width="49%" valign="top" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="invoice_box_3">
          <tr>
            <td valign="top">
            <div class="account_info"><?=$this->lang->line('payment_information')?></div>
			<?=$payment_name?><br />
			<?=$payment_address_1?><br />
			<?=$payment_address_2?><br />
			<?=$payment_city?> <?=$payment_state?> <?=$payment_postal_code?><br />
			<?=_get_country_name($payment_country, 'country_name')?><br />
			&nbsp;<br />
           </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="invoice_box_2">
      <tr class="title">
        <td width="81%"><?=$this->lang->line('description')?></td>
        <td width="19%"><?=$this->lang->line('amount')?></td>
        </tr>
      <tr>
        <td height="200" valign="top">
		<?=$this->lang->line('affiliate_payment')?>
        <br />
        <?=$affiliate_note?>        </td>
        <td valign="top">
        <?=format_amounts($payment_amount, $num_options)?>        </td>
        </tr>
      <tr>
        <td align="right" valign="top"><strong><?=$this->lang->line('total_amount')?></strong></td>
        <td valign="top"><strong> <?=format_amounts($payment_amount, $num_options)?></strong></td>
      </tr>
      
      
    </table></td>
  </tr>
</table>
<div class="separator"></div>