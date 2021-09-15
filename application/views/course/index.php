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
						<?php if (has_role($this->session->userdata('user_id'), 'COURSE_UPDATE_INFO')) {?>
						<li role="presentation" <?php if ($page_name === 'course_info') {echo 'class="active"';}?>><a href="<?=base_url('course/info/' . $course_info->id)?>"  aria-expanded="true">Course Info</a>
						</li>
						<?php }?>
						<?php if (has_role($this->session->userdata('user_id'), 'COURSE_UPDATE_CURRICULUM')) {?>
						<li role="presentation" <?php if ($page_name === 'course_curriculum') {echo 'class="active"';}?>><a href="<?=base_url('course/curriculum/' . $course_info->id)?>"  aria-expanded="false">Curriculum</a>
						</li>
						<?php }?>
						<?php if ($this->session->userdata('user_type') === "SUPER_ADMIN"|| has_role($this->session->userdata('user_id'), 'COURSE_THUMBNAILS')) {?>
						<li role="presentation" <?php if ($page_name === 'media') {echo 'class="active"';}?>><a href="<?=base_url('course/media/' . $course_info->id)?>"  aria-expanded="false">Media</a>
						</li>
						<?php }?>
						<?php if (has_role($this->session->userdata('user_id'), 'QUESTION_READ')) {?>
						<li role="presentation" <?php if ($page_name === 'question_bank') {echo 'class="active"';}?>><a href="<?=base_url('course/question_bank/' . $course_info->id)?>"  aria-expanded="false">Question Bank</a>
						</li>
						<?php }?>
						<?php if (has_role($this->session->userdata('user_id'), 'QUIZ_READ')) {?>
						<li role="presentation" <?php if ($page_name === 'quiz_set' || $page_name == 'add_question_for_quiz_set' || $page_name == 'show_questions') {echo 'class="active"';}?>><a href="<?=base_url('course/quiz_set/' . $course_info->id)?>"  aria-expanded="false">Quiz Set</a>
						</li>
						<?php }?>
						 <?php if ($this->session->userdata('user_type') === "SUPER_ADMIN") {?>
						<li role="presentation" <?php if ($page_name === 'certificate') {echo 'class="active"';}?>><a href="<?=base_url('course/certificate/' . $course_info->id)?>"  aria-expanded="false">Certificate</a>
						</li>
						 <?php }?>
						<?php if (has_role($this->session->userdata('user_id'), 'COURSE_UPDATE_SEO')) {?>
						<li role="presentation" <?php if ($page_name === 'seo') {echo 'class="active"';}?>><a href="<?=base_url('course/seo/' . $course_info->id)?>"  aria-expanded="false">SEO</a>
						</li>
						<?php }?>
						<?php if (has_role($this->session->userdata('user_id'), 'ANNOUNCEMENT_READ')) {?>
						<li role="presentation" <?php if ($page_name === 'announcement') {echo 'class="active"';}?>><a href="<?=base_url('course/announcement/' . $course_info->id)?>"  aria-expanded="false">Announcement</a>
						</li>
						<?php }?>
						<?php if (has_role($this->session->userdata('user_id'), 'REVIEW_READ')) {?>
						<li role="presentation" <?php if ($page_name === 'course_review') {echo 'class="active"';}?>><a href="<?=base_url('course/course_review/' . $course_info->id)?>"  aria-expanded="false">Review</a>
						</li>
						<?php }?>
					</ul>
				</div>
				<?php include $sub_page_view . '.php';?>
			</div>
		</div>
	</div>
</div>
