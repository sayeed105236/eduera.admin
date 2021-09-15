<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel" style="padding: 0;">
			<div class="x_title" style="border-bottom: 0px; padding: 22px 15px 0 23px;">
				<div class="pull-left">
					<h2 ><?=$user_data->first_name?> <?=$user_data->last_name;?></h2><br>
					<p><?=$user_data->email;?> <br> <?=$user_data->phone;?></p>
				</div>
				<div class="pull-right">
					<a href="<?=base_url('users/reset_password/' . $user_data->id)?>" onclick="return confirm('Are you sure reset this user password.?');" class="btn btn-danger">Reset Password</a>
					<a class="btn btn-info" href="https://www.eduera.com.bd/" target="_blank">View in website</a>
				</div>

				<div class="clearfix"></div>
			</div>
			<div class="x_content" style="padding: 0px;">
				<div class="" role="tabpanel" data-example-id="togglable-tabs">
					<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
						<?php if (has_role($this->session->userdata('user_id'), 'USER_UPDATE') || has_role($this->session->userdata('user_id'), 'USER_READ')) {?>
						<li role="presentation" <?php if ($page_name === 'user_info') {echo 'class="active"';}?>><a href="<?=base_url('users/' . $user_data->id . '/info')?>"  aria-expanded="true">User info</a>
						</li>
						<?php }?>
						<?php if ($this->session->userdata('user_type') === "SUPER_ADMIN") {?>
						<li role="presentation" <?php if ($page_name === 'user_role') {echo 'class="active"';}?>><a href="<?=base_url('users/' . $user_data->id . '/user_role')?>"  aria-expanded="true">User role</a>
						</li>
						<?php }?>
						<?php if (has_role($this->session->userdata('user_id'), 'USER_READ')) {?>
						<li role="presentation" <?php if ($page_name === 'user_enrollment') {echo 'class="active"';}?>><a href="<?=base_url('users/' . $user_data->id . '/user_enrollment')?>"  aria-expanded="false">User enrollment</a>
						</li>
						<?php }?>
						<?php if ($user_data->instructor) {?>
						<li role="presentation" <?php if ($page_name === 'instructor_enrollment') {echo 'class="active"';}?>><a href="<?=base_url('users/' . $user_data->id . '/instructor_enrollment')?>"  aria-expanded="false">Instructor enrollment</a>
						</li>
						<li role="presentation" <?php if ($page_name === 'instructor_payment') {echo 'class="active"';}?>><a href="<?=base_url('users/' . $user_data->id . '/instructor_payment')?>"  aria-expanded="false">Instructor payment</a>
						</li>
						<?php }?>
						<li role="presentation" <?php if ($page_name === 'status_monitoring') {echo 'class="active"';}?>><a href="<?=base_url('users/' . $user_data->id . '/status_monitoring')?>"  aria-expanded="false">Status monitoring</a>
						</li>
						<?php if (has_role($this->session->userdata('user_id'), 'USER_CERTIFICATE')) {?>
						<li role="presentation" <?php if ($page_name === 'user_certificate') {echo 'class="active"';}?>><a href="<?=base_url('users/' . $user_data->id . '/user_certificate')?>"  aria-expanded="false">Certificate</a>
						</li>
					<?php } ?>
					</ul>
				</div>

				<?php if ($this->session->flashdata('reset_password')) {?>

				    <div class="alert alert-success alert-dismissible  show" role="alert">
				        <strong></strong>
				        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				            <span aria-hidden="true">&times;</span>
				        </button>
				        <?=$this->session->flashdata('reset_password')?>
				    </div>

				<?php }?>

				<?php if ($this->session->flashdata('reset_password_failed')) {?>

				    <div class="alert alert-danger alert-dismissible  show" role="alert">
				        <strong></strong>
				        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				            <span aria-hidden="true">&times;</span>
				        </button>
				        <?=$this->session->flashdata('reset_password_failed')?>
				    </div>

				<?php }?>
				<?php include $sub_page_view . '.php';?>
			</div>
		</div>
	</div>
</div>
