<form id="course_info_form" data-parsley-validate class="form-horizontal form-label-left" style="padding: 30px;" action="<?= base_url('eduera_system/save_sytem_seo_info') ?>" method="post">

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
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_title">Seo title <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="seo_title" class="form-control col-md-7 col-xs-12" value="<?= $seo_info['seo_title'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta_description">Meta description <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea rows="3" type="text" name="meta_description" class="form-control col-md-7 col-xs-12"><?= $seo_info['meta_description'] ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta_tags">Meta tags <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea rows="3" type="text" name="meta_tags" class="form-control col-md-7 col-xs-12"><?= $seo_info['meta_tags'] ?></textarea>
        </div>
    </div>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="submit"  class="btn btn-success">Save</button>
        </div>
    </div>

</form>