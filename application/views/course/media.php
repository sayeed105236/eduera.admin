<div style="padding: 50px;">
	<?php if ($this->session->flashdata('photo_upload_error')) { ?>

	    <div class="alert alert-danger alert-dismissible show" role="alert">
	        <strong></strong> 
	        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	        </button>
	        <?= $this->session->flashdata('photo_upload_error') ?>
	    </div>

	<?php } ?>

	<?php if ($this->session->flashdata('photo_upload_success')) { ?>

	    <div class="alert alert-success alert-dismissible show" role="alert">
	        <strong></strong> 
	        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	        </button>
	        <?= $this->session->flashdata('photo_upload_success') ?>
	    </div>

	<?php } ?>

	<div class="row">
		<div class="col-md-6">
			<?php echo form_open_multipart('course/upload_photo/' . $course_info->id);?>
			<div class="form-group">

				<input type="file" class="form-control-file" name="course_thumbnail" size="20" />

			</div>

			<input type="submit" class="btn btn-info" value="upload" />

			</form>
		</div>
<?php

	// $root = 'http://localhost/eduera/uploads/thumbnails/course_thumbnails/';
?>
		<div class="col-md-6">
			<img src="<?=$image_path?>course_thumbnail_default_<?=$course_info->id?>.jpg" alt="<?=$course_info->title?>" style="border: 2px solid
#e33667;padding: 10px;">
		</div>
	</div>
</div>