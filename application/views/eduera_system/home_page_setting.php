<form id="course_info_form" data-parsley-validate class="form-horizontal form-label-left" style="padding: 30px;" action="<?= base_url('eduera_system/save_sytem_email_info') ?>" method="post">

    <?php echo validation_errors(); ?>

    <?php if ($this->session->flashdata('update_settings_success')) { ?>

       <div class="alert alert-success alert-dismissible  show" role="alert">
           <strong></strong> 
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
           <?= $this->session->flashdata('update_settings_success') ?>
       </div>

    <?php } ?>

    <?php if ($this->session->flashdata('update_settings_error')) { ?>

       <div class="alert alert-danger alert-dismissible  show" role="alert">
           <strong></strong> 
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
           <?= $this->session->flashdata('update_settings_error') ?>
       </div>

    <?php } ?>

   

</form>

<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="row x_title">
        <div class="col-md-6">
          <h3>Home Page Setting</h3>
        </div>

         <div class="col-md-6">
            <?php if (has_role($this->session->userdata('user_id'), 'HOME_PAGE_SETTING')) { ?><button type="button" data-toggle="modal" data-target=".bs-example-modal-edit" class="btn btn-primary pull-right" id="add-category" >Add New</button><?php } ?>
        </div>
  </div>      
  <table class="table table-striped table-bordered">
    <thead>
    <tr>
      <th>Name </th>
      <th>Rank </th>
      <th>Created At</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($home_page_setting as $home){?>
    <tr>
      <td><?= $home->name?></td>
      <td><?= $home->rank?></td>
      <td><?= $home->created_at?></td>
<?php if (has_role($this->session->userdata('user_id'), 'HOME_PAGE_SETTING')) { ?>

      <td> <i onclick="EditHomePage(<?= $home->id?>)" class="fa fa-pencil-square-o" aria-hidden="true" data-toggle="modal" data-target=".bs-example-modal-edit"></i></td>
  <?php }?>
    </tr>
  <?php } ?>
  </tbody>
  </table>
</div>


<!--  Modal -->

<?php if (has_role($this->session->userdata('user_id'), 'HOME_PAGE_SETTING')) { ?>
<div class="modal fade bs-example-modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Add Home Page Section</h4>
            </div>
            <form class="form-horizontal form-label-left"  action="<?= base_url('eduera_system/home_page_setting_update/') ?>"  method="post">
                <input type="hidden" name="home_page_setting_id" id="home_page_setting_id">
                <div class="modal-body">  
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="name" class="form-control col-md-7 col-xs-12"  data-validate-words="2" name="name"  required="required" type="text" value="">
                        </div>
                    </div>


                    <div class="item form-group " >
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="heard">Rank </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input id="rank" class="form-control col-md-7 col-xs-12"  name="rank"  required="required" type="number" value="">
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

<script type="text/javascript">
    
    function EditHomePage(id){

        $("#home_page_setting_id").val(id);

        $.ajax({
            url: '<?= base_url('rest/api/get_home_page_setting_data')?>',
            type: 'GET',
            dataType: 'json',
            data: {id: id},
            success: function(res){
               console.log(res);

                $("#name").val(res[0].name);
                $("#rank").val(res[0].rank);
                // $("#rank").val(res[0].rank);
            }
        })
        .done(function() {
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }
</script>