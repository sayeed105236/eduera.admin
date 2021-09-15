<form id="course_info_form" data-parsley-validate class="form-horizontal form-label-left" style="padding: 30px;" action="<?=base_url('course/info/' . $course_info->id)?>" method="post">

    <?php echo validation_errors(); ?>

    <?php if ($this->session->flashdata('course_info_update_success')) {?>

       <div class="alert alert-success alert-dismissible  show" role="alert">
           <strong></strong>
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
           <?=$this->session->flashdata('course_info_update_success')?>
       </div>

    <?php }?>

    <?php if ($this->session->flashdata('course_info_update_error')) {?>

       <div class="alert alert-danger alert-dismissible  show" role="alert">
           <strong></strong>
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
           <?=$this->session->flashdata('course_info_update_error')?>
       </div>

    <?php }?>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Course title <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="title" class="form-control col-md-7 col-xs-12" value="<?=$course_info->title?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="short_description">Short description <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea rows="3" type="text" name="short_description" class="form-control col-md-7 col-xs-12"><?=$course_info->short_description?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea rows="3" type="text" name="description" class="form-control col-md-7 col-xs-12"><?=$course_info->description?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="language">Language <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="language" class="form-control">
                <option value="bengali" <?php if ($course_info->language === 'bengali') {echo 'selected';}?>>Bengali</option>
                <option value="english" <?php if ($course_info->language === 'english') {echo 'selected';}?>>English</option>
            </select>
        </div>
    </div>
    

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category">Category <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control" name="category">
                <option value="-1">Category</option>
                <?php foreach ($category_list as $category) {?>
                    <option value="<?=$category->id?>" <?php if ($matched_category == $category->id) {echo 'selected';}?>><?=$category->name?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sub_category">Sub-category <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control" name="sub_category">
                <option value="-1">Subcategory</option>
                <?php foreach ($category_list as $category) {
	if ($category->id === $matched_category) {
		foreach ($category->sub_category_list as $sub_category) {?>
                                <option value="<?=$sub_category->id?>" <?php if ($matched_sub_category && $matched_sub_category === $sub_category->id) {echo 'selected';}?>><?=$sub_category->name?></option>
                                <?php
}
	}
}?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="level">Level <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="level" class="form-control">
                <option value="beginner" <?php if ($course_info->level === 'beginner') {echo 'selected';}?>>Beginner</option>
                <option value="intermediate" <?php if ($course_info->level === 'intermediate') {echo 'selected';}?>>Intermediate</option>
                <option value="advanced" <?php if ($course_info->level === 'advanced') {echo 'selected';}?>>Advanced</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="status" class="form-control">
                <option value="1" <?php if ($course_info->status === '1') {echo 'selected';}?>>Active</option>
                <option value="0" <?php if ($course_info->status === '0') {echo 'selected';}?>>Inactive</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="preview_video_id">Preview Video id
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input rows="3" type="number" value="<?=$course_info->preview_video_id?>" name="preview_video_id" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price">Price <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input rows="3" type="number" value="<?=$course_info->price?>" name="price" class="form-control col-md-7 col-xs-12" required>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discounted_price">Discounted Price <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input rows="3" type="number" value="<?=$course_info->discounted_price?>" name="discounted_price" class="form-control col-md-7 col-xs-12" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="language">Ordering <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="rank" class="form-control">
                <?php
                foreach($rank_list as $rank){
                ?>
                <option value="<?= $rank->rank?>" <?php if ($course_info->rank === $rank->rank) {echo 'selected';}?>><?= $rank->rank?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expiry_month">Expiray Month <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input placeholder="Enter the number of month" type="number" value="<?=$course_info->expiry_month?>" name="expiry_month" class="form-control col-md-7 col-xs-12" >
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="send_greeting_mail">Send Greeting Email 
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="send_greeting_mail" class="form-control">
                <option value="1" <?php if ($course_info->send_greeting_mail === '1') {echo 'selected';}?>>Yes</option>
                <option value="0" <?php if ($course_info->send_greeting_mail === '0') {echo 'selected';}?>>No</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="certification_course">Certification Course 
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="certification_course" class="form-control">
                <option value="1" <?php if ($course_info->certification_course === '1') {echo 'selected';}?>>Yes</option>
                <option value="0" <?php if ($course_info->certification_course === '0') {echo 'selected';}?>>No</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mock_test">Mock Test 
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="mock_test" class="form-control">
                <option value="1" <?php if ($course_info->mock_test === '1') {echo 'selected';}?>>Yes</option>
                <option value="0" <?php if ($course_info->mock_test === '0') {echo 'selected';}?>>No</option>
            </select>
        </div>
    </div>

    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="submit"  class="btn btn-success">Save</button>
        </div>
    </div>

</form>
<script type="text/javascript">
    var category_list = JSON.parse('<?=json_encode($category_list)?>');

    $("#course_info_form select[name='category']").change(function(){
        $("#course_info_form select[name='sub_category']").html('<option value="-1">Subcategory</option>');
        for (var i = 0; i < category_list.length; i++){
            if (category_list[i].id == $(this).val()){
                for (var j = 0; j < category_list[i].sub_category_list.length; j++ ){
                    $("#course_info_form select[name='sub_category']").append(
                        '<option value="' + category_list[i].sub_category_list[j].id + '">' + category_list[i].sub_category_list[j].name + '</option>'
                    );
                }
                break;
            }
        }

    });
</script>

