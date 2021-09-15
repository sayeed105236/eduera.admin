
<div style="padding: 25px;">
    <div class="row" style="padding: 7px;">
        <?php echo validation_errors(); ?>
    </div>


    <?php if ($this->session->flashdata('announcement_success')) {?>
    <div class="row" style="padding: 7px;">
        <div class="alert alert-success alert-dismissible show" role="alert">
            <strong></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?=$this->session->flashdata('announcement_success')?>
        </div>
    </div>
    <?php }?>


    <?php if ($this->session->flashdata('announcement_failed')) {?>
    <div class="row" style="padding: 7px;">     
        <div class="alert alert-danger alert-dismissible show" role="alert">
            <strong></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?=$this->session->flashdata('announcement_failed')?>
        </div>        
    </div>
    <?php }?>


    <div class="row" style="padding: 7px;">
        
        <?php if (has_role($this->session->userdata('user_id'), 'ANNOUNCEMENT_CREATE')) { ?>
        <a type="button" id="add_announcement" class="btn btn-info pull-right" data-toggle="modal" data-target=".announcement_add_modal" onclick="add_question()">Add Announcement</a>        
        <?php } ?>
        <?php $this->load->view('course/question_add_modal');?>
    </div>


    <?php
    if($announcement_list != NULL){
        foreach ($announcement_list as $index => $announcement) {
   
    ?>
        <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">



            <div class="panel">

                <span class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne_<?=$announcement->id?>" aria-expanded="true" aria-controls="collapseOne">
                    <h4 class="panel-title">
                        <div class="row">
                            <div class="col-md-9 col-sm-9 col-xl-9">
                                <?= $index + 1 ?>. <?=$announcement->title?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xl-3">
                                <span class="pull-right">
                                    <?php if (has_role($this->session->userdata('user_id'), 'ANNOUNCEMENT_UPDATE')) { ?>
                                    <span onclick="editAnnouncement(<?=$announcement->id?>, '<?=$announcement->title?>', '<?=$announcement->description?>')" data-toggle="modal" data-target=".announcement_add_modal">
                                        <i class="fa fa-pencil"></i>
                                    </span>
                                    <?php } ?>

                                    <?php if (has_role($this->session->userdata('user_id'), 'ANNOUNCEMENT_DELETE')) { ?>
                                    <span onclick="deleteAnnouncement('<?= $course_info->id ?>', '<?= $announcement->id ?>')">
                                        <i class="fa fa-trash" style="padding-left: 15px;"></i>
                                    </span>
                                    <?php } ?>
                                </span>
                            </div>

                        </div>
                    </h4>
                </span>

                <div id="collapseOne_<?=$announcement->id?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                        <tr>
                                            <td><?= $announcement->description?></td>
                                            <td><?= $announcement->created_at ?></td>
                                        </tr>
                                   
                                </tbody>
                            </table>
                        
                          

                        
                    </div>
                </div>
            </div>
        </div>
                
    <?php }

}else{
        echo 'No Announcement';
}
        ?>
</div>



<div class="modal fade announcement_add_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="announcement_add_form" data-parsley-validate class="form-horizontal form-label-left" action="<?=base_url('course/announcement/' . $course_info->id )?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="announcement_add_modal_title">Add Announcement</h4>
                </div>

                <div class="modal-body section-form">
                    <input type="hidden" name="course_id" value="<?=$course_info->id?>">
                    <input type="hidden" name="announcement_id" value="">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"> Title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text"  name="title" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea  name="description"  class="form-control col-md-7 col-xs-12"></textarea>
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
    function deleteAnnouncement(course_id, announcement_id){
        if(confirm("are you sure to remove this announcement?")){
            window.location = "<?= base_url('course/remove_announcement_from_course/'); ?>" + course_id + "/" + announcement_id;
        }
    }

    function editAnnouncement(announcement_id, title, description){
        $("#announcement_add_modal_title").text('Edit Announcement');
        $("#announcement_add_form input[name='announcement_id']").val(announcement_id);
        $("#announcement_add_form input[name='title']").val(title);
        $("#announcement_add_form textarea[name='description']").val(description);
       
    }
</script>
