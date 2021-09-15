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
                    <div class="row" style="padding: 5px;">
                        <?php if (has_role($this->session->userdata('user_id'), 'USER_ENROLL')){ ?>
                            <button type="button" data-toggle="modal" data-target=".user_enroll_modal" class="btn btn-primary pull-right" onclick="assign_in_a_course()">Enroll in a course</button>
                        <?Php } ?>
                    </div>
                    <?php $this->load->view('component/user_enroll_modal'); ?>
                    <?php $this->load->view('component/make_payment_modal'); ?>
                    <?php if(count($enroll_history) > 0){?>
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Enroll ID</th>
                                    <th>Course Title</th>
                                    <th>Enrolled Price</th>
                                    <th>Paid Amount</th>
                                    <th>Final access</th>
                                    <th>Admins' access</th>
                                    <th>Enrolled Date</th>
                                    <th>Expiry Date</th>
                                    <?php if (has_role($this->session->userdata('user_id'), 'USER_ENROLL')) { ?>
                                        <th>Pay</th>
                                        <th>Action</th>
                                    <?php } ?>
                                </tr>
                            </thead>


                            <tbody>
                                <?php foreach ($enroll_history as $index => $enroll)  {
                                    $created_date = $enroll->created_at;
                                    $datetime = new DateTime($created_date);
                                    $day = $datetime->format('D');
                                    $month = $datetime->format('M');
                                    $year = $datetime->format('Y');
                                    $date = $datetime->format('d');
                                    ?>

                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $enroll->enroll_id ?></td>
                                        <td><?= $enroll->title ?></td>
                                        <td><?= $enroll->enrolled_price ?></td>
                                        <td><?= $enroll->paid_amount ?></td>
                                        <td><?= access_in_a_course($enroll->user_id, $enroll->course_id)['access_percentage'] ?>%</td>
                                        <td><?= $enroll->access . '%' ?></td>
                                        <td><?= $day .', '. $date.'-'.$month.'-'.$year;  ?></td>
                                        <td>
                                            <?php
                                                if($enroll->expiry_date == 0){
                                                    echo 'Life Time';
                                                }else{
                                                    echo $enroll->expiry_date;
                                                }
                                            ?>
                                           
                                                
                                        </td>
                                        <?php if (has_role($this->session->userdata('user_id'), 'USER_ENROLL')) { ?>
                                        <td ><a onclick="make_payment(<?= $enroll->enroll_id ?>)" href="#" class="btn btn-warning" data-toggle="modal" data-target=".user_make_payment_modal">Make Payment</a></td>
                                        <td>
                                            <a style="padding-right: 5px;" onclick="fill_the_form(<?= $enroll->enroll_id ?>, <?= $enroll->course_id ?>, <?= $enroll->enrolled_price ?>, <?= $enroll->access ?>, '<?= $enroll->expiry_date?>')" data-toggle="modal" data-target=".user_enroll_modal">
                                                <i class="fa fa-edit"></i> 
                                            </a>
                                            <a onclick="return confirm('Are you agree remove this history?');"  href="<?php echo base_url('users/remove_enroll_course/'.$enroll->enroll_id.'?user_id='.$enroll->user_id) ?>">
                                                <i class="fa fa-trash"></i> 
                                            </a>
                                        </td>
                                        <?php } ?>
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

    course_list = JSON.parse('<?= json_encode($course_list) ?>');
    course = null;

    function fill_the_form(enroll_id, course_id, enrolled_price, access, expiry_date){
        $("#user_enroll_form input[name='purpose']").val('update_enrollment');
        $("#user_enroll_form input[name='enrollment_id']").val(enroll_id);
        if(expiry_date != '0000-00-00'){
            $("#user_enroll_form input[name='expiry_date']").val(expiry_date);

        }else{
            $("#user_enroll_form input[name='expiry_date']").val();
        }
        $("#price_tab").show();
        $("#course_select_field").val(course_id);
        $("#inserted_price").val(enrolled_price);

        $("input[name=expiry_date]").datepicker({
          dateFormat: 'yy-mm-dd',
          onSelect: function(dateText, inst) {
            $(inst).val(dateText); // Write the value in the input
          }
        });


        for (var i = 0; i < course_list.length; i++){
            if (course_list[i].id == course_id){
                course = course_list[i];
                break;
            }
        }

        $("#user_enroll_form input[name='access']").val(access);

        if (course.discount_flag == null || course.discount_flag == false){
            $("#discount_price_radio").hide();
        } else {
            $("#discount_price_radio").show();
        }

        $("#user_enroll_reset_button").hide();
    }


    function assign_in_a_course(){
        $("#user_enroll_form input[name='purpose']").val('enroll_user');
        $("#course_select_field").prop('disabled', false);
        $("#user_enroll_reset_button").show();
    }

    function make_payment(enroll_id){
        $("#user_payment_modal input[name='enrollment_id']").val(enroll_id);


    }
</script>

