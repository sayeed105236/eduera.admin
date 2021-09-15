<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap.min.css">

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">
            <?php echo validation_errors(); ?>
            <div class="row x_title">
                <div class="col-md-12">
                    <h3>Coupon</h3>
                </div>


                    <a type="button" class="btn btn-info pull-right" data-toggle="modal" data-target=".coupon_add_modal">Add new coupon</a>
                    <?php include 'coupon_add_modal.php';?>

                </div>
                <div class="row" style="padding: 7px;">
                    <?php if ($this->session->flashdata('coupon_update_success')) {?>

                        <div class="alert alert-success alert-dismissible show" role="alert">
                            <strong></strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?=$this->session->flashdata('coupon_update_success')?>
                        </div>

                    <?php }?>
                </div>
                <div class="row" style="padding: 7px;">
                    <?php if ($this->session->flashdata('coupon_update_error')) {?>

                        <div class="alert alert-danger alert-dismissible show" role="alert">
                            <strong></strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?=$this->session->flashdata('coupon_update_error')?>
                        </div>

                    <?php }?>
                </div>

            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <table id="datatable" class="table table-striped table-bordered">

                    <thead>
                        <tr>
                            <!-- <th>Id</th> -->
                            <th>Course Name</th>
                            <th>Coupon Code</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Discount type</th>
                            <th>Discount</th>
                            <th>Coupon Limit</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php foreach ($coupon_list as $index => $coupon) {?>
                        <tr>
                            <!-- <td><?=$index + 1?></td> -->
                            <td><?=$coupon->title?></td>
                            <td><?=$coupon->coupon_code?></td>
                            <td><?=$coupon->start_date?></td>
                            <td><?=$coupon->end_date?></td>
                            <td><?=strtoupper($coupon->discount_type)?></td>
                            <td><?=$coupon->discount?></td>
                            <td><?=$coupon->coupon_limit?></td>
                            <td><?=$coupon->status == 1 ? 'Active' : 'Inactive'?></td>

                            <td style="cursor: pointer;">
                               <i  onclick="edit_coupon(<?=$coupon->id?>)" class="fa fa-edit btn btn-sm btn-warning" data-toggle="modal" data-target=".coupon_add_modal"></i>

                               <a class="btn btn-sm btn-info" onclick="view_coupon(<?=$coupon->id?>)" data-toggle="modal" data-target=".coupon_view_modal"> View</a>
                            </td>

                        </tr>
                        <?php }?>
                    </tbody>

                </table>

            </div>

            <div class="clearfix"></div>
        </div>
    </div>

</div>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    function edit_coupon(coupon_id){
        $("#coupon_modal_title").html("Edit Coupon");
        $("#coupon_add_form input[name='coupon_id']").val(coupon_id);

        $.ajax({
            type: "GET",
            url: "<?php echo base_url('rest/api/get_coupon_info/'); ?>" + coupon_id,
            
            success: function(response){
                result = JSON.parse(response);
                console.log(result.status);

                $("#coupon_add_form select[name='course_id']").val(result.course_id);
                $("#coupon_add_form input[name='coupon_code']").val(result.coupon_code);
                $("#coupon_add_form input[name='start_date']").val(result.start_date);
                $("#coupon_add_form input[name='end_date']").val(result.end_date);
                $("#coupon_add_form input[name='discount']").val(result.discount);
                $("#coupon_add_form select[name='discount_type']").val(result.discount_type);
                $("#coupon_add_form input[name='coupon_limit']").val(result.coupon_limit);

                $("#coupon_add_form select[name='status']").val(result.status);
            },
                error: function (request, status, error) {
                }
        });
    }

    $(document).ready(function() {
        $('#datatable').DataTable();
    } );



    function view_coupon(coupon_id){
        $(".modal-title").html("View Coupon");
        $("#coupon_view_form input[name='coupon_id']").val(coupon_id);
        var base_url = 'https://eduera.com.bd/course/';
        // var base_url = 'http://localhost/eduera/course/';
        $.ajax({
            type: "GET",
            url: "<?php echo base_url('rest/api/get_coupon_info/'); ?>" + coupon_id,
            
            success: function(response){
                result = JSON.parse(response);
                console.log(result.status);

                $("#coupon_view_form select[name='course_id']").val(result.course_id);
                $("#coupon_view_form input[name='coupon_code']").val(result.coupon_code);
                $("#coupon_view_form input[name='start_date']").val(result.start_date);
                $("#coupon_view_form input[name='end_date']").val(result.end_date);
                $("#coupon_view_form input[name='discount']").val(result.discount);
                $("#coupon_view_form select[name='discount_type']").val(result.discount_type);
                $("#coupon_view_form input[name='coupon_limit']").val(result.coupon_limit);
                $("#coupon_view_form input[name='already_applied']").val(result.already_applied);
                $("#coupon_view_form input[name='coupon_limit_left']").val( (result.coupon_limit - result.already_applied));

                $("#coupon_view_form select[name='status']").val(result.status);
                $("#coupon_view_form p[id='course_link']").text(base_url+result.slug+'/?CouponCode='+result.coupon_code);
                $("#coupon_view_form input[name='view_course_link']").val(base_url+result.slug+'/?CouponCode='+result.coupon_code);
            },
                error: function (request, status, error) {
                }
        });
    }

    
    function copyLink() {
      /* Get the text field */
      var copyText = document.getElementById("view_course_link");

      /* Select the text field */
      copyText.select();
      // copyText.setSelectionRange(0, 99999); 
      /*For mobile devices*/

      /* Copy the text inside the text field */
      document.execCommand("copy");



    }

</script>