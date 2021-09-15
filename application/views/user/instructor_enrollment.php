<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">

            <?php echo validation_errors(); ?>

            <?php if ($this->session->flashdata('success')) { ?>

                <div class="alert alert-success alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('success') ?>
                </div>

            <?php } ?>

            <?php if ($this->session->flashdata('danger')) { ?>

                <div class="alert alert-danger alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('danger') ?>
                </div>

            <?php } ?>
            <div class="x_content">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php if (has_role($this->session->userdata('user_id'), 'INSTRUCTOR_UPDATE')) {?>
                    <div class="row" style="padding: 5px;">
                        <button type="button" data-toggle="modal" data-target=".instructor_enroll_modal" class="btn btn-primary pull-right" onclick="assign_in_a_course()">Assign in a course</button>
                    </div>
                    <?php } ?>

                    <?php $this->load->view('component/instructor_enroll_modal'); ?>
                    <?php if(count($enroll_history) > 0){?>
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Course ID</th>
                                    <th>Course Title</th>
                                    <th>Share</th>
                                    <th>Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php foreach ($enroll_history as $index => $course)  { ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $course->id ?></td>
                                        <td><?= $course->title ?></td>
                                        <td><?= $course->instructor_share ?>%</td>
                                        <td>
                                            <?php if (has_role($this->session->userdata('user_id'), 'INSTRUCTOR_UPDATE')) {?>
                                            <a style="padding-right: 5px;" onclick="fill_the_form(<?= $course->id ?>, <?= $course->instructor_share ?>)" data-toggle="modal" data-target=".instructor_enroll_modal">
                                                <i class="fa fa-edit"></i> 
                                            </a>
                                        <?php } ?>
                                        <?php if (has_role($this->session->userdata('user_id'), 'INSTRUCTOR_DELETE')) {?>
                                            <a onclick="return confirm('Are you agree remove this history?');"  href="<?php echo base_url('users/remove_instructor_from_course/'.$course->id.'/'.$user_data->id) ?>">
                                                <i class="fa fa-trash"></i> 
                                            </a>
                                             <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    <?php }else {?>
                        <p>Not found any enrolled course.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function fill_the_form(course_id, instructor_share){

        $("#course_select_field").val(course_id);
        $("#profit_share_field").val(instructor_share);
        // $("#course_select_field").prop('disabled', 'disabled');        
    }


    function assign_in_a_course(){
        $("#course_select_field").prop('disabled', false);
    }
</script>