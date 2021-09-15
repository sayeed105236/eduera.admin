<style type="text/css">
    .fa.pull-right {
        margin-left: 1.3em;
        /* margin-bottom: 0em; */
    }
</style>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">
            <?php if ($this->session->flashdata('user_role_save_success')) { ?>

                <div class="alert alert-success alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('user_role_save_success') ?>
                </div>

            <?php } ?>
            <?php if ($this->session->flashdata('user_role_save_failed')) { ?>

                <div class="alert alert-danger alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('user_role_save_failed') ?>
                </div>

            <?php } ?>


            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-align-left"></i> User Role List </h2> 
                        <!--   <button type="button" data-toggle="modal" data-target=".add-category" class="btn btn-primary pull-right" id="add-category" >Add New</button> -->

                        <div class="clearfix"></div>
                    </div>
                    <form action="<?= base_url('users/update_user_role/'. $user_data->id) ?>" method="post">
                        <div class="x_content">

                            <!-- start accordion -->
                            <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">

                                <?php 
                                $index=0;
                                foreach($user_roles as $key=>$role_list){
                                    $index = $index+1;
                                    ?>
                                    <div class="panel">
                                        <a class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne_<?php echo $key;?>" aria-expanded="true" aria-controls="collapseOne">
                                            <h4 class="panel-title"> <?= $key ?> </h4>



                                        </a>
                                        <div id="collapseOne_<?php echo $key;?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
                                            <div class="panel-body">

                                                <div class="x_content">



                                                    <div class="table-responsive">
                                                        <table class="table table-striped jambo_table ">
                                                            <thead>
                                                                <tr class="headings">
                                                                    <th>
                                                                        <input type="checkbox" id="check-all_<?=$index?>" onclick="checkAll(<?= $index;?>)" class=" check">
                                                                    </th>
                                                                    <th class="column-title">Name </th>
                                                                    <th class="column-title">Description</th>


                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <?php foreach($role_list  as $key=>$role){

                                                                    ?>  
                                                                    <tr class="even pointer">
                                                                        <td class="a-center ">
                                                                            <input <?php 
                                                                            foreach($user_data->role_list as $selected_role){

                                                                                if($role == $selected_role){
                                                                                    echo "checked";
                                                                                    break 1;
                                                                                }

                                                                            }
                                                                            ?> type="checkbox" id="child_check_<?= $index?>" class="" name="role_name[]" value="<?= $role?>">
                                                                        </td>
                                                                        <td ><?= $role?></td>
                                                                        <td >Description Coming Soon </td>

                                                                    </tr>
                                                                <?php } ?> 
                                                            </tbody>
                                                        </table>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>


                            </div>
                            <!-- end of accordion -->


                        </div>
                        <button type="submit"  class="btn btn-primary">Save</button>
                    </form>

                </div>
            </div>


            <div class="clearfix"></div>
        </div>
    </div>

</div>

<script>

    function checkAll(index){
        if($("#check-all_"+index).prop("checked") == true){

            $('input[id=child_check_'+index+']').prop('checked', true);

        }

        else if($("#check-all_"+index).prop("checked") == false){

            $('input[id=child_check_'+index+']').prop('checked', false);
        }



    }


</script>
