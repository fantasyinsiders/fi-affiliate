<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row capitalize">
    <div class="col-lg-3 col-md-6">
        <div class="box-info animated fadeInRight">
            <div class="icon-box">
                <span class="fa-stack">
                  <i class="fa fa-square fa-stack-2x info"></i>
                  <i class="fa fa-users fa-stack-1x fa-inverse"></i>
                </span>
            </div><div class="text-box">
                <h3><?=$total_members?></h3>
                <p><?=$this->lang->line('total_affiliate_signups')?></p>
            </div>
            <div class="clear"></div>
            <div class="progress progress-xs">
              <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="box-info animated fadeInRight">
        <div class="icon-box"> <span class="fa-stack"> <i class="fa fa-square fa-stack-2x info"></i> <i class="fa fa-edit fa-stack-1x fa-inverse"></i> </span> </div>
        <div class="text-box">
          <h3><?=$month_signups?></h3>
          <p><?=$this->lang->line('affiliates_this_month')?></p>
        </div>
        <div class="clear"></div>
        <div class="progress progress-xs">
          <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="box-info animated fadeInRight">
        <div class="icon-box"> <span class="fa-stack"> <i class="fa fa-square fa-stack-2x info"></i> <i class="fa fa-money fa-stack-1x fa-inverse"></i> </span> </div>
        <div class="text-box">
          <h3><?=format_amounts($total_commissions, $num_options)?></h3>
          <p><?=$this->lang->line('total_commissions')?></p>
        </div>
        <div class="clear"></div>
        <div class="progress progress-xs">
          <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="box-info animated fadeInRight">
        <div class="icon-box"> <span class="fa-stack"> <i class="fa fa-square fa-stack-2x info"></i> <i class="fa fa-calendar fa-stack-1x fa-inverse"></i> </span> </div>
        <div class="text-box">
          <h3><?=format_amounts($month_comm, $num_options)?></h3>
          <p><?=$this->lang->line('commissions_this_month')?></p>
        </div>
        <div class="clear"></div>
        <div class="progress progress-xs">
          <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
      </div>
    </div>
</div>
<div class="row capitalize">
	<div class="col-lg-8">
		<div class="box-info animated fadeInLeft">
			<h2><i class="fa fa-bar-chart-o"></i> <?=$this->lang->line('monthly_traffic_activity')?></h2>
            <div class="additional-btn">
                <a class="additional-icon" href="#fakelink" data-toggle="collapse" data-target="#activity"><i class="fa fa-chevron-down"></i></a>
            </div>
            <div id="activity" class="collapse in">
            <iframe id="iframe1" class="iframe" src="<?=admin_url()?>reports/quick_stats_traffic"  scrolling="no" frameborder="0" height="290"></iframe>
            </div>
        </div>      
    </div>
    <div class="col-lg-4">
   		 <div class="box-info  animated fadeInUp">
			<h2><i class="fa fa-edit"></i> <?=$this->lang->line('affiliate_signups_last_seven_days')?></h2>
            <div class="additional-btn">
                <a class="additional-icon" href="#fakelink" data-toggle="collapse" data-target="#registration"><i class="fa fa-chevron-down"></i></a>
            </div>
            <div id="registration" class="collapse in">
            <iframe id="iframe2" class="iframe" src ="<?=admin_url()?>reports/quick_stats_signups" scrolling="no" frameborder="0" height="290"></iframe>
            </div>
        </div>     
    </div>
</div>
<div class="row">
	<div class="col-lg-6">
    	<div class="box-info full animated fadeInLeft">
			<h2><i class="fa fa-users"></i> <?=$this->lang->line('latest_affiliates') ?></h2>
            <div class="additional-btn">
                <a class="additional-icon" href="#fakelink" data-toggle="collapse" data-target="#latest-members"><i class="fa fa-chevron-down"></i></a>
            </div>
			<div id="latest-members" class="collapse in">
            	<div class="table-responsive">
                	<?php if (empty($latest_members)):?>
                	<div class="jumbotron sm noborder">
              			<h3><?=$this->lang->line('no_new_members')?></h3>
              			<p><a href="members/add_member" class="btn btn-primary btn-lg"><?=$this->lang->line('add_member')?></a></p>
            		</div>
	 		       	<?php else:?>
					<table data-sortable class="table table-striped">
						<thead>
							<tr><th><?=$this->lang->line('signup_date')?></th><th><?=$this->lang->line('name')?></th><th><?=$this->lang->line('email_address')?></th><th><?=$this->lang->line('status')?></th><th data-sortable="false"></th></tr>
						</thead>
						<tbody>
							<?php foreach ($latest_members as $m): ?>
                        	<tr>
                                <td><?=_show_date($m['signup_date'])?></td>
                                <td><a href="<?=admin_url()?>members/update_member/<?=$m['mid']?>"><?=$m['fname']?> <?=$m['lname']?></a></td>
                                <td><?=$m['primary_email']?></td>
                                <td>
                                	<a href="<?=admin_url()?>members/update_status/<?=$m['mid']?>/3">
									<?php if ($m['status'] == '1'): ?>
                                	<span class="label label-success"> <?=$this->lang->line('active')?></span>
                                	<?php else : ?>
                                	<span class="label label-warning"><?=$this->lang->line('inactive')?></span>
                                	<?php endif; ?>
                                    </a>
                                </td>
                                <td>
                                	<a href="<?=admin_url()?>members/update_member/<?=$m['mid']?>" class="btn btn-default btn-sm"><i class="fa fa-search"></i> </a>
                                </td>
                        	</tr>
                        	<?php endforeach; ?>
        				</tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div> <!-- end .latest-members -->
        </div>
    </div>
    <div class="col-lg-6">
    	<div class="box-info full animated fadeInUp">
			<h2><i class="fa fa-money"></i>  <?=$this->lang->line('latest_commissions') ?></h2>
            <div class="additional-btn">
                <a class="additional-icon" href="#" data-toggle="collapse" data-target="#latest-commissions"><i class="fa fa-chevron-down"></i></a>
            </div>
			<div id="latest-commissions" class="collapse in">
            	<div class="table-responsive">
                	<?php if (empty($latest_commissions)):?>
                	<div class="jumbotron sm noborder">
              			<h3><?=$this->lang->line('no_new_commissions')?></h3>
              			<p><a href="commissions/add_commission" class="btn btn-primary btn-lg"><?=$this->lang->line('add_commission')?></a></p>
            		</div>
	 		       	<?php else:?>
					<table data-sortable class="table table-striped">
                        <thead>
							<tr><th><?=$this->lang->line('date')?></th><th><?=$this->lang->line('transaction_id')?></th><th><?=$this->lang->line('amount')?></th><th><?=$this->lang->line('username')?></th><th><?=$this->lang->line('status')?></th><th data-sortable="false"></th></tr>
						</thead>
						<tbody>
							<?php foreach ($latest_commissions as $c): ?>
                        	<tr>
                                <td><?=_show_date($c['date'])?></td>
                                <td><a href="<?=admin_url()?>commissions/update_commission/<?=$c['comm_id']?>"><?=limit_chars($c['trans_id'], 30)?></a></td>
                                <td><?=format_amounts($c['commission_amount'], $num_options)?></td>
                                <td><?=$c['username']?></td>
                                <td>
								<?php if ($c['comm_status'] == 'pending'): ?>
                                <span class="label label-danger">
                                <?php elseif ($c['comm_status'] == 'unpaid'): ?>
								<span class="label label-warning">
								<?php else: ?>
								<span class="label label-success">
                                <?php endif; ?>
                                <?=$c['comm_status']?></span>
                                </td>
                                <td>
                                	<a href="<?=admin_url()?>commissions/update_commission/<?=$c['comm_id']?>" class="btn btn-default btn-sm"><i class="fa fa-search"></i> </a>
                                </td>
                        	</tr>
                        	<?php endforeach; ?>
        				</tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div> <!-- end .latest-members -->
        </div>
    </div>
</div>


<?php if (!defined('JAM_ENABLE_RESELLER_LINKS')): ?>
<?php if ($sts_admin_show_dashboard_video == '1'): ?>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="H1">Watch an Overview of the Affiliate Admin Area</h4>
            </div>
            <div class="modal-body">

                <div class="row pull-top-small">
                    <div class="col-md-12">
                        <iframe width="640" height="360" src="//www.youtube.com/embed/FKT5Zo2_1zE?rel=0&autoplay=0&showinfo=0&vq=hd720&theme=light&color=white&hd=1" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            	 <a href="https://jam.jrox.com/redirect/videos.php" class="btn btn-default" target="_blank"><i class="fa fa-video-camera"></i> Watch More Videos</a>
                <a href="<?=admin_url()?>settings/disable_getting_started" class="btn btn-default"><i class="fa fa-times"></i> Do not show this again</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(window).load(function(){
        $('#myModal2').modal('show');
    });
</script>
<?php endif; ?>
<?php endif; ?>
