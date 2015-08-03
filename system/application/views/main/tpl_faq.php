<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="faqs" class="col-lg-12">
	<h4 class="text-capitalize"><?=$this->lang->line('frequently_asked_questions_faqs')?> - <?=$prg_program_name?></h4>
    <hr />
   	<?php if (empty($faqs)): ?>
   	<div>
	    <h4><?=$this->lang->line('no_faqs_found')?></h4> 
    </div>
	<?php else: ?>
    <ul class="faqs">
		<?php foreach ($faqs as $v): ?>
        <li>
            <p><a data-toggle="collapse" data-parent="#faqs" href="#box-<?=$v['article_id']?>"><i class="fa fa-question-circle"></i> <?=$v['content_title']?></a></p>
            <div class="collapse animated fadeIn" id="box-<?=$v['article_id']?>">
                <?=$v['content_body']?>
                <hr />
            </div>
        </li>	
        <?php endforeach; ?>
    </ul>
	<?php endif; ?>
</div>