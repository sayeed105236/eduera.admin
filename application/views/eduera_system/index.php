<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel" style="padding: 0;">
			<div class="x_title" style="border-bottom: 0px; padding: 22px 15px 0 23px;">
				<h2><?= $page_title ?></h2>
					<a class="btn btn-info pull-right" href="https://www.eduera.com.bd/" target="_blank">View in website</a>
				<div class="clearfix"></div>
			</div>
			<div class="x_content" style="padding: 0px;">
				<div class="" role="tabpanel" data-example-id="togglable-tabs">
					<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
						<?php if (has_role($this->session->userdata('user_id'), 'SYSTEM_BASIC')) { ?>
						<li role="presentation" <?php if ($page_name === 'eduera_system_basic') { echo 'class="active"'; } ?>><a href="<?= base_url('eduera_system/basic') ?>"  aria-expanded="true">Basic</a>
						</li>
						<?php } ?>
						<?php if (has_role($this->session->userdata('user_id'), 'SYSTEM_SEO')) { ?>
						<li role="presentation" <?php if ($page_name === 'eduera_system_seo') { echo 'class="active"'; } ?>><a href="<?= base_url('eduera_system/seo') ?>"  aria-expanded="false">SEO</a>
						</li>
						<?php } ?>

						<?php if (has_role($this->session->userdata('user_id'), 'SYSTEM_EMAIL')) { ?>
						<li role="presentation" <?php if ($page_name === 'eduera_system_email') { echo 'class="active"'; } ?>><a href="<?= base_url('eduera_system/email') ?>"  aria-expanded="false">Email</a>
						</li>
						<?php } ?>

						<?php if (has_role($this->session->userdata('user_id'), 'HOME_PAGE_SETTING')) { ?>
						<li role="presentation" <?php if ($page_name === 'home_page_setting') { echo 'class="active"'; } ?>><a href="<?= base_url('eduera_system/home_page_setting') ?>"  aria-expanded="false">Home Page</a>
						</li>
						<?php } ?>

						<?php if (has_role($this->session->userdata('user_id'), 'FRONTEND_SETTING')) { ?>
						<li role="presentation" <?php if ($page_name === 'frontend_setting') { echo 'class="active"'; } ?>><a href="<?= base_url('eduera_system/frontend_setting') ?>"  aria-expanded="false">Frontend Setting</a>
						</li>
						<?php } ?>
					</ul>
				</div>
				<?php include $sub_page_view . '.php'; ?>
			</div>
		</div>
	</div>
</div>
