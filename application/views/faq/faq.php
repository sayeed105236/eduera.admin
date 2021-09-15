<div style="padding: 25px;">
    <div class="row" style="padding: 7px;">
        <?php echo validation_errors(); ?>
    </div>
    <div class="row" style="padding: 7px;">
        <?php if ($this->session->flashdata('faq_update_success')) {?>

            <div class="alert alert-success alert-dismissible show" role="alert">
                <strong></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?=$this->session->flashdata('faq_update_success')?>
            </div>

        <?php }?>
    </div>
    <div class="row" style="padding: 7px;">
        <?php if ($this->session->flashdata('faq_update_error')) {?>

            <div class="alert alert-danger alert-dismissible show" role="alert">
                <strong></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?=$this->session->flashdata('faq_update_error')?>
            </div>

        <?php }?>
    </div>
    <div class="row" style="padding: 7px;">
        <?php if ($page_name == 'faq' && has_role($this->session->userdata('user_id'), 'FAQ_CREATE')) {?>
            <a type="button" class="btn btn-info pull-right" data-toggle="modal" data-target=".faq_category_add_modal" onclick="add_section()">Add FAQ Category</a>
        <?php }?>

        <?php include 'faq_category_modal.php';?>
        <?php include 'faq_modal.php';?>
    </div>
    <!-- start accordion -->
    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
        <?php foreach ($faq_category as $key => $faq_cat) {
	?>
            <div class="panel">
                <a class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne_<?=$faq_cat->id?>" aria-expanded="true" aria-controls="collapseOne">
                    <h4 class="panel-title">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xl-6">
                                <?=$key + 1?>. <?=$faq_cat->name?>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xl-6">

                                <span class="pull-right">
                                    <?php if ($page_name == 'faq' && has_role($this->session->userdata('user_id'), 'FAQ_UPDATE')) {?>
                                        <i class="fa fa-edit" onclick="edit_faq_cat(<?=$faq_cat->id?>)" data-toggle="modal" data-target=".faq_category_add_modal"></i>
                                        <i style="padding-left: 5px" onclick="add_faq(<?=$faq_cat->id?>)" class="fa fa-plus-square" data-toggle="modal" data-target=".faq_add_modal"></i>
                                    <?php }?>

                                </span>
                            </div>
                        </div>
                    </h4>
                </a>
                <div id="collapseOne_<?=$faq_cat->id?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <?php if ($faq_cat->faq_list !== null && count($faq_cat->faq_list) > 0) {
		?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th>Video Id</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($faq_cat->faq_list as $faq_index => $faq) {?>
                                    <tr>
                                        <th scope="row"><?=$faq_index + 1?></th>
                                        <td><?=$faq->question?></td>
                                        <td><?=$faq->answer?></td>
                                        <td><?=$faq->video_id?></td>


                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        <?php } else {?>
                            <div style="text-align: center;">
                                <p>No QUestion</p>
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
    function edit_faq_cat(faq_cat_id){
        $("#faq_category_add_modal_title").html("Edit FAQ Category");
        $("#faq_category_add_form input[name='faq_cat_id']").val(faq_cat_id);

        $.ajax({
            type: "GET",
            url: "<?php echo base_url('rest/api/get_faq_category/'); ?>" + faq_cat_id,
            success: function(response){
                result = JSON.parse(response);
                $("#faq_category_add_form input[name='name']").val(result.name);
            },
                error: function (request, status, error) {
                }
        });
    }




    function add_faq(faq_cat_id){
        $("#faq_add_form input[name='faq_cat_id']").val(faq_cat_id);
    }



</script>