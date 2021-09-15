<div style="padding: 25px;">
	<div class="row" style="padding: 7px;">
		<?php echo validation_errors(); ?>
	</div>
	<div class="row" style="padding: 7px;">
		<?php if ($this->session->flashdata('section_update_success')) {?>

			<div class="alert alert-success alert-dismissible show" role="alert">
				<strong></strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<?=$this->session->flashdata('section_update_success')?>
			</div>

		<?php }?>
	</div>
	<div class="row" style="padding: 7px;">
		<?php if ($this->session->flashdata('section_update_error')) {?>

			<div class="alert alert-danger alert-dismissible show" role="alert">
				<strong></strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<?=$this->session->flashdata('section_update_error')?>
			</div>

		<?php }?>
	</div>
	<div class="row" style="padding: 7px;">
		<span>Total duration: <?=second_to_time_conversion($course_info->duration_in_second)?></span>
		<a type="button" class="btn btn-info pull-right" data-toggle="modal" data-target=".section_add_modal" onclick="add_section()">Add Section</a>
		<?php include 'section_add_modal.php';?>
		<?php include 'lesson_add_modal.php';?>
		<?php include 'lesson_file_upload_modal.php';?>
	</div>
	<!-- start accordion -->
	<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
		<?php foreach ($course_info->section_list as $section) {
	?>
			<div class="panel">
				<a class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne_<?=$section->id?>" aria-expanded="true" aria-controls="collapseOne">
					<h4 class="panel-title">
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xl-6">
								<?=$section->rank?>. <?=$section->title?>
							</div>
							<div class="col-md-6 col-sm-6 col-xl-6">
								<span style="font-size: 15px;">Duration: <?=second_to_time_conversion($section->duration_in_second)?></span>
								<span class="pull-right">
									<i class="fa fa-edit" onclick="edit_section(<?=$section->id?>)" data-toggle="modal" data-target=".section_add_modal"></i>
									<i style="padding-left: 5px" onclick="add_lesson(<?=$section->id?>)" class="fa fa-plus-square" data-toggle="modal" data-target=".lesson_add_modal"></i>
								</span>
							</div>
						</div>
					</h4>
				</a>
				<div id="collapseOne_<?=$section->id?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
					<div class="panel-body">
						<?php if ($section->lesson_list !== null && count($section->lesson_list) > 0) {
		?>
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Lesson ID</th>
										<th>Video Type</th>
										<th>Vimeo ID</th>
										<th>Lesson Title</th>
										<th>Duration (second)</th>
										<th>Preview</th>
										<th>Files</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($section->lesson_list as $lesson_index => $lesson) {?>
									<tr>
										<th scope="row"><?=$lesson->rank?></th>
										<td><?=$lesson->id?></td>
										<td>
											<?php if($lesson->video_type == 'vimeo'){
												echo 'Vimeo';
											}else{
												echo 'Youtube';
											}?>

										</td>
										<td>
											<?php if($lesson->video_type == 'vimeo'){
												echo $lesson->vimeo_id;
											}else{
												echo $lesson->video_id;
											}?>
										</td>
										<td><?=$lesson->title?></td>
										<td><?=second_to_time_conversion($lesson->duration_in_second)?></td>
										<td>
											<?php
												if($lesson->preview == 1){
													echo 'Preview';
												}else{
													echo 'No Preview';
												}
											?>
										</td>
										<td>
											<?php if ($lesson->lesson_file_list != null && count($lesson->lesson_file_list) > 0) {?>
												<?php foreach ($lesson->lesson_file_list as $key => $file) {?>
													<a href="<?=base_url('course/download_lesson_file/' . $file . '?course_id=' . $course_info->id)?>" target="_blank"><span><?=($key + 1) . '. ' . $file?></span> </a> &nbsp &nbsp
													<a style="color: red; " onclick="return confirm('Are you agree remove this file?');"  href="<?php echo base_url('course/remove_lesson_file/' . $key . '?lesson_id=' . $lesson->id) ?>">
														<i class="fa fa-trash"></i>
													</a><br />
												<?php }?>
											<?php }else{echo 'No files';}?>
											
											
										</td>
										<td>
											<i class="fa fa-edit" onclick='edit_lesson(<?=$section->id?>, <?=json_encode($lesson)?>)' data-toggle="modal" data-target=".lesson_add_modal"></i> &nbsp&nbsp&nbsp
											<i class="fa fa-file" onclick='upload_lesson_file(<?=$lesson->id?>)' data-toggle="modal" data-target=".lesson_file_modal" title="Upload file"></i>
										</td>
									</tr>
									<?php }?>
								</tbody>
							</table>
						<?php } else {?>
							<div style="text-align: center;">
								<p>No lesson</p>
							</div>
						<?php }?>
					</div>
				</div>
			</div>
		<?php }?>
	</div>
</div>
<!-- end of accordion -->
<script type="text/javascript">
	function edit_section(section_id){
		$("#course_section_add_reset_button").hide();
		$("#course_section_add_modal_title").html("Edit section");
		$("#section_add_form input[name='id']").val(section_id);

		$.ajax({
			type: "GET",
			url: "<?php echo base_url('rest/api/get_section_by_id/'); ?>" + section_id,
			success: function(response){
				// console.log(response);
				result = JSON.parse(response);
				$("#section_add_form input[name='title']").val(result.title);
				$("#section_add_form input[name='order']").val(result.rank);
			},
				error: function (request, status, error) {
					console.log(request.responseText);
				}
		});
	}

	function add_section(){
		$("#course_section_add_reset_button").show();
		$("#course_section_add_modal_title").html("Add section");
		$("#section_add_form input[name='title']").val("");
		$("#section_add_form input[name='order']").val("");
	}



	function add_lesson(section_id){
		console.log(section_id);
		$("#lesson_add_form input[name='section_id']").val(section_id);
	}


	function edit_lesson(section_id, lesson){
		
		if(lesson.video_type == 'vimeo'){
		    $("#vimeo_id").show();
		    $("#youtube_video_id").hide();
		}else{
		     $("#vimeo_id").hide();
		    $("#youtube_video_id").show();
		}


		youtube_video_url = 'https://www.youtube.com/watch?v=';
		$("#lesson_add_form input[name='section_id']").val(section_id);
		$("#lesson_add_form input[name='lesson_id']").val(lesson.id);
		$("#lesson_add_form input[name='title']").val(lesson.title);
		$("#lesson_add_form input[name='order']").val(lesson.rank);
		$("#lesson_add_form textarea[name='summary']").html(lesson.summary);
		$("#lesson_add_form input[name='vimeo_id']").val(lesson.vimeo_id);
		$("#lesson_add_form select[name='video_type']").val(lesson.video_type);
		$("#lesson_add_form input[name='youtube_video_url']").val(youtube_video_url + lesson.video_id);

		if (lesson.preview == 1){
		    $("#preview").prop("checked", true);
		} else {
		    $("#preview").prop("checked", false);
		   
		}
	}


	function upload_lesson_file(lesson_id){
		$("#lesson_file_upload_form input[name='lesson_id']").val(lesson_id);
	}
</script>