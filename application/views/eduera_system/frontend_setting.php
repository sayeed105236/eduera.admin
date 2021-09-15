<form id="course_info_form" data-parsley-validate class="form-horizontal form-label-left" style="padding: 30px;" action="<?= base_url('eduera_system/save_frontend_settings') ?>" method="post">

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
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="about_us">About Us <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea type="text" name="about_us" class="form-control col-md-7 col-xs-12" id="html"><?= $frontend_setting['about_us'] ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="terms_and_condition">Terms & Conditions  <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea rows="3" type="text" name="terms_and_condition" id="terms_and_condition" class="form-control col-md-7 col-xs-12"><?= $frontend_setting['terms_and_condition'] ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="privacy_policy">Privacy & Policy <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea rows="3" type="text" name="privacy_policy" id="privacy_policy" class="form-control col-md-7 col-xs-12"><?= $frontend_setting['privacy_policy'] ?></textarea>
        </div>
    </div>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="submit"  class="btn btn-success">Save</button>
        </div>
    </div>

</form>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.3.4/tinymce.min.js"></script>


<script type="text/javascript">
  
  $(document).ready(function () {
    tinyMCE.init({
      selector: "#html, #terms_and_condition, #privacy_policy",
      // content_css: 'https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css',
      plugins: ["code visualblocks"],
      valid_elements : '*[*]',
      toolbar: "undo redo | styleselect | bold italic | fontsizeselect | alignleft aligncenter alignright alignjustify | preview",
      schema: "html5",
      // verify_html : false,
      // valid_children : "+a[div], +div[*]"
      // extended_valid_elements : "div[*]",
    });
  });
</script>