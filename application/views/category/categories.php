<style type="text/css">
    .fa.pull-right {
    margin-left: 1.3em;
    /* margin-bottom: 0em; */
}
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">
            <?php if ($this->session->flashdata('category_save_success')) { ?>

                <div class="alert alert-success alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('category_save_success') ?>
                </div>

            <?php } ?>
            <?php if ($this->session->flashdata('category_save_failed')) { ?>

                <div class="alert alert-danger alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('category_save_failed') ?>
                </div>

            <?php } ?>
          

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-align-left"></i> Category List </h2> <?php if (has_role($this->session->userdata('user_id'), 'CATEGORY_CREATE')) { ?><button type="button" data-toggle="modal" data-target=".add-category" class="btn btn-primary pull-right" id="add-category" >Add New</button><?php } ?>
                       <!--  <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>

                        </ul> -->
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <!-- start accordion -->
                        <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php foreach($category_list as $key=>$category){
                                $key = $key+1;
                                ?>
                                <div class="panel">
                                    <a class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne_<?php echo $key;?>" aria-expanded="true" aria-controls="collapseOne">
                                        <h4 class="panel-title"><?= $key; ?>. <?= $category->name; ?></h4>
                                       
                                        <?php if (has_role($this->session->userdata('user_id'), 'CATEGORY_UPDATE')) { ?>
                                        <i onclick="EditCategory(<?= $category->id?>)" class="fa fa-pencil-square-o pull-right" aria-hidden="true" data-toggle="modal" data-target=".bs-example-modal-edit"></i>
                                        <?php } ?>

                                        <?php if (has_role($this->session->userdata('user_id'), 'CATEGORY_CREATE')) { ?>
                                        <i style="margin-left: -10px;" onclick="AddSubCategory('<?= $category->id ?>', '<?= $category->name ?>' )" id="category_name" parent="<?= $category->name?>" class="fa fa-plus-circle pull-right" aria-hidden="true" data-toggle="modal" data-target=".add-category"></i>
                                        <?php } ?>
                                    </a>
                                    <div id="collapseOne_<?php echo $key;?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <?php if(count($category->sub_category_list) > 0){?>  
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Sub Category Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach($category->sub_category_list  as $keys=>$subcategory){
                                                            $keys = $keys+1;
                                                            ?>  
                                                            <tr>
                                                                <th scope="row"><?= $keys; ?></th>
                                                                <td><?= $subcategory->name ?></td>

                                                                <td>
                                                                <?php if (has_role($this->session->userdata('user_id'), 'CATEGORY_UPDATE')) { ?>
                                                                    <i onclick="EditCategory(<?= $subcategory->id?>)" class="fa fa-pencil-square-o" aria-hidden="true" data-toggle="modal" data-target=".bs-example-modal-edit"></i>
                                                                <?php } ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            <?php }else{ ?>
                                                <h5>No found sub category</h5>
                                            <?php } ?>    
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                        <!-- end of accordion -->


                    </div>
                </div>
            </div>


            <div class="clearfix"></div>
        </div>
    </div>

</div>


<!-- Start Add New Category Modal -->
<?php if (has_role($this->session->userdata('user_id'), 'CATEGORY_CREATE')) { ?>
<div class="modal fade add-category" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="category-title"></h4>
            </div>
            <form class="form-horizontal form-label-left"  action="<?= base_url('category/category_save/') ?>"  method="post">

                <input type="hidden" name="parent" id="add-cat-id">

                <div class="modal-body">  
                   <div class="item form-group">
                       <label class="control-label col-md-3 col-sm-3 col-xs-12 e" for="first_name"><span class="required"></span>
                       </label>
                       <div class="col-md-6 col-sm-6 col-xs-12" >
                           <h3 id="parent-name" style="display: none"></h3>
                       </div>
                   </div>
                    
                   
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12 category-first-name" for="first_name"><span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="first_name" class="form-control col-md-7 col-xs-12"  data-validate-words="2" name="name"  required="required" type="text" value="">
                        </div>
                    </div>

                   

                    <div class="ln_solid"></div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>
<?php } ?>
<!-- End Add New Category Modal -->

<!-- Start Edit Category Modal -->
<?php if (has_role($this->session->userdata('user_id'), 'CATEGORY_UPDATE')) { ?>
<div class="modal fade bs-example-modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Edit category</h4>
            </div>
            <form class="form-horizontal form-label-left"  action="<?= base_url('category/category_update/') ?>"  method="post">
                <input type="hidden" name="cat_id" id="update-cat-id">
                <div class="modal-body">  
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Category Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="name" class="form-control col-md-7 col-xs-12"  data-validate-words="2" name="name"  required="required" type="text" value="">
                        </div>
                    </div>


                    <div class="item form-group " >
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="heard">Parent </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control" id="edit_parent" name="parent" >
                                
                                <?php foreach($category_list as $category){?>
                                    <option value="<?= $category->id?>" ><?= $category->name ;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="ln_solid"></div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>

        </div>
    </div>
</div>
<?php } ?>
<!-- End Edit Category Modal -->


<script type="text/javascript">

    $("#add-category").on('click', function(){
        $("#category-title").text("Add new category");
        $(".parent-for-subcategory").hide();
        $("#parent-name").hide();
        $(".category-first-name").text("Category Name:*");
    });

    function AddSubCategory(id, name){
        $("#add-cat-id").val(id);
        $("#category-title").text("Add new sub category");
        $(".category-first-name").text("Sub Category Name:*");
        $("#parent-name").show();
        $("#parent-name").text(name);
        $("#parent").val(id);
    }
    function EditCategory(id){

        console.log(id);

        $.ajax({
            type: "GET",
            url: "<?php echo site_url('rest/api/get_single_category_info'); ?>",
            data: {id: id},
            success: function(response){

                var json = JSON.parse(response);
                console.log(json);
                $("#update-cat-id").val(json[0].id);
                $("#name").val(json[0].name);
                $("#edit_parent").val(json[0].parent);

            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }
</script>
