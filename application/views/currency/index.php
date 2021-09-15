<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">
            <?php echo validation_errors(); ?>
            <div class="row x_title">
                <div class="col-md-12">
                    <h3>Currency</h3>
                </div>

                <?php if (has_role($this->session->userdata('user_id'), 'CURRENCY_CREATE')) { ?>
                    <a type="button" class="btn btn-info pull-right" data-toggle="modal" data-target=".currency_add_modal">Add new currency</a>
                    <?php include 'currency_add_modal.php';?>
                <?php } ?>
                </div>
                <div class="row" style="padding: 7px;">
                    <?php if ($this->session->flashdata('currency_update_success')) {?>

                        <div class="alert alert-success alert-dismissible show" role="alert">
                            <strong></strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?=$this->session->flashdata('currency_update_success')?>
                        </div>

                    <?php }?>
                </div>
                <div class="row" style="padding: 7px;">
                    <?php if ($this->session->flashdata('currency_update_error')) {?>

                        <div class="alert alert-danger alert-dismissible show" role="alert">
                            <strong></strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?=$this->session->flashdata('currency_update_error')?>
                        </div>

                    <?php }?>
                </div>

            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <table id="datatable" class="table table-striped table-bordered">

                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Sign</th>
                            <th>Value</th>
                            
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php foreach ($currency_list as $index => $currency) {?>
                        <tr>
                            <td><?=$index + 1?></td>
                            <td><?=$currency->name?></td>
                            <td><?=$currency->sign?></td>
                            <td><?=$currency->value?></td>
                         

                            <td>
                                <?php if (has_role($this->session->userdata('user_id'), 'CURRENCY_UPDATE')) { ?>
                               <i style="cursor: pointer;" onclick="edit_currency(<?=$currency->id?>)" class="fa fa-edit" data-toggle="modal" data-target=".currency_add_modal"></i> 
                               ||
                               <?php } ?>
                               <?php if (has_role($this->session->userdata('user_id'), 'CURRENCY_DELETE')) { ?>
                               <i style="cursor: pointer;" onclick="remove_currency(<?=$currency->id?>)" class="fa fa-trash" data-toggle="modal" ></i>
                           <?php } ?>
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


<script type="text/javascript">
    function edit_currency(currency_id){
        $("#currency_modal_title").html("Edit Currency");
        $("#currency_add_form input[name='currency_id']").val(currency_id);

        $.ajax({
            type: "GET",
            url: "<?php echo base_url('rest/api/get_currency_info/'); ?>" + currency_id,
            success: function(response){
                result = JSON.parse(response);
                console.log(result);
                $("#currency_add_form input[name='name']").val(result.name);
                $("#currency_add_form input[name='currency_id']").val(result.id);
                $("#currency_add_form input[name='sign']").val(result.sign);
                $("#currency_add_form input[name='value']").val(result.value);
               
            },
                error: function (request, status, error) {
                }
        });
    }


    function remove_currency(currency_id){
        if (confirm('Are you sure to remove this currency?')){
            window.location = "<?= base_url('home/remove_currency/') ?>" + currency_id ;
        }
    }
</script>