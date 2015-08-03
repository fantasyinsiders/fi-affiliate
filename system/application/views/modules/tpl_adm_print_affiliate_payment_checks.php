<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="checkBg">
    <div class="check-box">
        <div class="check-date"><?=_show_date($current_date)?></div>
        
        <div class="check-name"><?=$payment_name?></div>
        <div class="payment-amount"><?=format_amounts($payment_amount, $num_options, true)?></div
        ><div class="payment-words"><?=num2words($payment_amount);?></div>
        <div class="payment-address">
			<?=$payment_name?><br />
            <?=$payment_address_1?> 
            <?=$payment_address_2?><br />
            <?=$payment_city?> <?=$payment_state?> <?=$payment_postal_code?><br />
            <?=_get_country_name($payment_country, 'country_name')?>
        </div>
    </div>
    <div class="second-box">
    	<div class="box-note-left"><?=$payment_name?> - <?=$affiliate_note?></div> 
        <div class="box-note-right"><?=format_amounts($payment_amount, $num_options, true)?></div>
    </div>
    
    <div class="third-box">
    	<div class="box-note-left"><?=$payment_name?> - <?=$affiliate_note?></div> 
        <div class="box-note-right"><?=format_amounts($payment_amount, $num_options, true)?></div>
    </div>
</div>


