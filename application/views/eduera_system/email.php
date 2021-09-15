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

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_title">System Email <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="system_email" class="form-control col-md-7 col-xs-12" value="<?= $email_info['system_email'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="protocol">Protocol <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input  type="text" name="protocol" class="form-control col-md-7 col-xs-12" value="<?= $email_info['protocol'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="smtp_host">Smtp Host <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="smtp_host" class="form-control col-md-7 col-xs-12" value="<?= $email_info['smtp_host'] ?>" />
        </div>
    </div>

     <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="smtp_port">Smtp Port <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="smtp_port" class="form-control col-md-7 col-xs-12" value="<?= $email_info['smtp_port'] ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="smtp_user">Smtp User <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="smtp_user" class="form-control col-md-7 col-xs-12" value="<?= $email_info['smtp_user'] ?>" />
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="smtp_pass">Smtp Pass <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="smtp_pass" class="form-control col-md-7 col-xs-12" value="<?= $email_info['smtp_pass'] ?>" />
        </div>
    </div>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="submit"  class="btn btn-success">Save</button>
        </div>
    </div>

</form>