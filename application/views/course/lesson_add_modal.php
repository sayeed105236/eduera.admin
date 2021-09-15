<div class="modal fade lesson_add_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="lesson_add_form" data-parsley-validate class="form-horizontal form-label-left" action="<?=base_url('course/curriculum/' . $course_info->id . '/lesson_add_form')?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="section_lesson_add_modal_title">Add lesson</h4>
                </div>

                <div class="modal-body section-form">
                    <input type="hidden" name="course_id" value="<?=$course_info->id?>">
                    <input type="hidden" name="section_id" value="">
                    <input type="hidden" name="lesson_id" value="">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Lesson title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="title" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="order">order <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" name="order" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="summary">Summary <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea type="number" name="summary" class="form-control col-md-7 col-xs-12"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="video_type">Video type <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="video_type" id="video_type" class="form-control">
                                <option value="vimeo">Vimeo</option>
                                <option value="youtube">Youtube</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="vimeo_id"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vimeo_id" >Video id in vimeo <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="vimeo_id" class="form-control col-md-7 col-xs-12" placeholder="Vimeo Id">
                        </div>
                    </div>


                    <div class="form-group" id="youtube_video_id" style="display: none;">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="youtube_video_id"  >Youtbe video url <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="youtube_video_url" class="form-control col-md-7 col-xs-12" placeholder="Youtube Video Url">
                        </div>
                    </div>

                    <div class="form-group" >
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Preview
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="checkbox" class="" name="preview" id="preview" />
                           
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="reset" id="section_lesson_add_reset_button" style="display: none;">Reset</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#video_type").on("change", function(){
        video_type= $(this).val();

        if(video_type == 'vimeo'){
            $("#vimeo_id").show();
            $("#youtube_video_id").hide();
        }else{
             $("#vimeo_id").hide();
            $("#youtube_video_id").show();
        }
    })

</script>