<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script>
$(document).ready(function(){
	$(".jroxMembersTable tr").mouseover(function() {
		$(this).addClass("over");}).mouseout(function() {
		$(this).removeClass("over");
	});
});
 
function ShowDetails(id) {
	$("#view-"+id+"").toggle();
}
</script>
<div id="jroxMembersContent" class="jroxGeneralContent">
    
    <div id="dashboardMarketingToolsBox" class="jroxMembersBox">        
        <h2 class="dashboardHeader" id="members_traffic"><?=$this->lang->line('programs')?></h2>
        
        <?php if (empty($rows)): ?>
        <h2 class="marginOne jroxCapitalize"><?=$this->lang->line('no_programs_found')?></h2>
        <?php else: ?>  
        
        <?php foreach ($rows as $v): ?>
        <div class="programDescBox"><a name="pid-<?=$v['program_id']?>"></a>
        	<div class="floatRight">
                <button id="downloadButton_<?=$v['program_id']?>" class="downloadButton" onclick="javascript:ShowDetails('<?=$v['program_id']?>')" type="button">
        			<img src="themes/main/<?=$default_theme?>/images/add_16.png" />
        			<span><?=$this->lang->line('view_details')?></span>
        		</button>  
                <?php if ($v['program_id'] != $prg_program_id): ?>
            	<button id="downloadButton_<?=$v['program_id']?>" class="downloadButton" onclick="window.location='<?=site_url('members')?>/programs/login/<?=$v['program_id']?>'" type="button">
        			<img src="themes/main/<?=$default_theme?>/images/star_16.png" />
        			<span><?=$this->lang->line('login')?></span>
        		</button>  
                <?php endif; ?>
        	</div>
        <div class="programDesc">
                    
          <div class="floatLeft"><img src="<?=base_url() . $v['program_photo']?>" border="0" class="programImage"/>
          </div>
          
          <h4><?=$v['program_name']?> </h4> 
          <strong><?=$this->lang->line('program_link')?>:</strong> <a href="<?=_get_aff_link($this->session->userdata('m_username')) . '_' . $v['program_id']?>" target="_blank"><?=_get_aff_link($this->session->userdata('m_username'), 'regular') . '_' . $v['program_id']?></a>  																					
          <br />
          <strong><?=$this->lang->line('commission_levels')?>:</strong> <?=$v['commission_levels']?>
          
          <?php if ($v['enable_pay_per_action'] == 1):?>
          <strong style="padding-left: 10px;"><?=$this->lang->line('per_action')?>:</strong> <?=$v['commission_type']?>
          
          <?php if ($v['enable_pay_per_click'] == 1):?>
          <strong style="padding-left: 10px;"><?=$this->lang->line('per_click')?>:</strong> <?=$v['ppc_amount']?>
          <?php endif; ?>
          
          <?php if ($v['enable_cpm'] == 1):?>
          <strong style="padding-left: 10px;"><?=$this->lang->line('cpm')?>:</strong> <?=$v['cpm_amount']?>
          <?php endif; ?>
          
          <br />
          <?=$v['payout']?>
          <?php endif; ?>
          </div>
          
          <div class="programDescription hide" id="view-<?=$v['program_id']?>"><?=$v['program_description']?></div>
        </div>
        <?php endforeach; ?>
      
        
    	<div class="paginationDiv"><?=$pagination_rows?></div>
      	<div class="clear"></div>
                
         <!-- pagination links -->

		<?php endif; ?>
                         
        <div class="clear"></div>
    </div>
</div>	

	