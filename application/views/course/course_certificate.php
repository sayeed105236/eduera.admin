<div style="padding: 10px 142px; margin-top: 20px;">
	<?php echo validation_errors(); ?>

	<?php if ($this->session->flashdata('certificate_photo_upload_error')) {?>

		<div class="alert alert-danger alert-dismissible  show" role="alert">
			<strong></strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<?=$this->session->flashdata('certificate_photo_upload_error')?>
		</div>

	<?php }?>

	<?php if ($this->session->flashdata('certificate_photo_upload_successful')) {?>

		<div class="alert alert-success alert-dismissible  show" role="alert">
			<strong></strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<?=$this->session->flashdata('certificate_photo_upload_successful')?>
		</div>

	<?php }?>

</div>
<div class="col-md-4">
	<?php echo form_open_multipart('course/upload_certificate/' . $course_info->id); ?>
	<div class="form-group">

		<input type="file" required="" class="form-control" name="course_certificate" size="20" />

	</div>

	<input type="submit" class="btn btn-primary" value="upload" />

</form>
</div>

<div class="col-md-1"></div>
<div class="col-md-5">
	<?php
if (file_exists($certificate_path . '' . $course_info->certificate)) {?>
		<img src="<?=$certificate_path . '' . $course_info->certificate?>" alt="Not Found Image" width="500" height="500">
	<?php }?>
</div>
</div>