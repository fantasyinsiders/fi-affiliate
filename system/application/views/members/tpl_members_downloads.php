<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-12">
	<?php if (empty($rows)): ?>
    <div class="alert alert-warning text-capitalize animated shake">
    	<h3><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('no_downloads_found')?></h3>
    	<p><a href="javascript:history.go(-1)" class="btn btn-warning"><i class="fa fa-chevron-left"></i> <?=$this->lang->line('go_back')?></a></p>
    </div>
	<?php else: ?>
	<div class="panel panel-default animated fadeInDown">
        <div class="panel-heading text-capitalize">
        	<div class="additional-btn">
            <a data-toggle="collapse" data-target=".download-details"><i class="fa fa-chevron-down"></i></a>
            </div>
			<?=$page_title?>
        </div>
        <div class="panel-body">
			<table class="table table-striped table-hover">
                <thead>
                    <tr class="text-capitalize">
                        <th style="width:80%"><a href="<?=$sort_header?>/download_name"><?=$this->lang->line('download_name')?></a></th>
                        <th style="width:20%"></th>
                    </tr>
                </thead>
                <tbody>
                	<?php foreach ($rows as $v): ?>
                    <tr>
                        <td>
							<h5><a class="collapsed" data-toggle="collapse" data-parent="p-<?=$v['id']?>" data-target="#<?=$v['id']?>"><?=$v['download_name']?></a></h5>
                            <div id="<?=$v['id']?>" class="collapse fade download-details">
                            	<hr />
                            	<ul class="list-inline">
                        		<?php if (!empty($v['s_download_link_1'])): ?><li><i class="fa fa-download"></i> <?=$v['s_download_link_1']?></li><?php endif; ?>
								<?php if (!empty($v['s_download_link_2'])): ?><li><i class="fa fa-download"></i> <?=$v['s_download_link_2']?></li><?php endif; ?>
                                <?php if (!empty($v['s_download_link_3'])): ?><li><i class="fa fa-download"></i> <?=$v['s_download_link_3']?></li><?php endif; ?>
                                <?php if (!empty($v['s_download_link_4'])): ?><li><i class="fa fa-download"></i> <?=$v['s_download_link_4']?></li><?php endif; ?>
                                <?php if (!empty($v['s_download_link_5'])): ?><li><i class="fa fa-download"></i> <?=$v['s_download_link_5']?></li><?php endif; ?>	
                            	</ul>
                            </div>
                        </td>
                        <td class="text-right"><a data-toggle="collapse" data-target="#<?=$v['id']?>" class="btn btn-sm btn-default"><i class="fa fa-download"></i> <?=$this->lang->line('get_files')?></a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-center"><?=$pagination_rows?></div>
        </div>
	</div>
</div>
<?php endif; ?>