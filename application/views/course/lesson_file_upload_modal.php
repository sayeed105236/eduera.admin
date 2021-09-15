<div class="modal fade lesson_file_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="lesson_file_upload_form" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left" action="<?=base_url('course/lesson_file_upload/' . $course_info->id)?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="section_lesson_add_modal_title">Upload files</h4>
                </div>

                <div class="modal-body section-form">

                    <input type="hidden" name="lesson_id" value="">

                    <div class="form-group">
                         <h4 style="font-weight: bold">Supported files are : txt, pdf, xlsx, csv, xls, doc, docx</h4>
                         <br>
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vimeo_id">Upload file<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="file" class="form-control" multiple="" name = "lesson_files[]" id="lesson_files">
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