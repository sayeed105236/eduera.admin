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
					    <li role="presentation" <?php if ($page_name === 'send_message') {echo 'class="active"';}?>><a href="<?=base_url('home/message_and_email/')?>"  aria-expanded="true">Send Message</a>
						</li>
						<li role="presentation" <?php if ($page_name === 'send_email') {echo 'class="active"';}?>><a href="<?=base_url('home/send_email/')?>"  aria-expanded="true">Send Email</a>
						</li>
						
					
					</ul>
				</div>
				<?php include $sub_page_view . '.php';?>
			</div>
		</div>
	</div>
</div>
