
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel" style="padding: 0;">
			<div class="x_title" style="border-bottom: 0px; padding: 22px 15px 0 23px;">
				<h2><?=$page_title?></h2>
					<a class="btn btn-info pull-right" href="https://www.eduera.com.bd/" target="_blank">View in website</a>
				<div class="clearfix"></div>
			</div>
			<div class="x_content" style="padding: 0px;">
				<div class="" role="tabpanel" data-example-id="togglable-tabs">
					<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
					    <li role="presentation" <?php if ($page_name === 'send_message') {echo 'class="active"';}?>><a href="<?=base_url('home/message_and_email/')?>"  aria-expanded="true">Messaging</a>
						</li>
						<li role="presentation" <?php if ($page_name === 'send_email') {echo 'class="active"';}?>><a href="<?=base_url('home/send_email/')?>"  aria-expanded="true">Email</a>
						</li>
						<!--<?php if (has_role($this->session->userdata('user_id'), 'COURSE_UPDATE_INFO')) {?>-->
						<!--<li role="presentation" <?php if ($page_name === 'course_info') {echo 'class="active"';}?>><a href="<?=base_url('course/info/' . $course_info->id)?>"  aria-expanded="true">Course Info</a>-->
						<!--</li>-->
						<!--<?php }?>-->
						<!--<?php if (has_role($this->session->userdata('user_id'), 'COURSE_UPDATE_CURRICULUM')) {?>-->
						<!--<li role="presentation" <?php if ($page_name === 'course_curriculum') {echo 'class="active"';}?>><a href="<?=base_url('course/curriculum/' . $course_info->id)?>"  aria-expanded="false">Curriculum</a>-->
						<!--</li>-->
						<!--<?php }?>-->
					
					</ul>
				</div>
				<?php include $sub_page_view . '.php';?>
			</div>
		</div>
	</div>
</div>
