<form id="course_info_form" data-parsley-validate class="form-horizontal form-label-left" style="padding: 30px;" action="<?= base_url('eduera_system/save_sytem_basic_info') ?>" method="post">

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
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_title">Contact Email <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-10">
            <input type="text" name="contact_email" class="form-control col-md-7 col-xs-12" value="<?=$basic_info['contact_email']?>">
        </div>
       
    </div>


    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_title">Phone <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-10">
            <input type="text"  name="phone" class="form-control col-md-7 col-xs-12" value="<?=$basic_info['phone']?>">
        </div>
       
    </div>


    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_title">Address <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-10">
            <textarea type="text"  name="address" class="form-control col-md-7 col-xs-12" ><?=$basic_info['address']?></textarea>
        </div>
       
    </div>



    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_title">Vat <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-10">
            <input type="number" min="1" name="vat" class="form-control col-md-7 col-xs-12" value="<?=$basic_info['vat']?>">
        </div>
        <label class="control-label col-md-1 col-sm-1 col-xs-1" for="seo_title">% 
        </label>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="protocol">Tax <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-10">
            <input  type="number" min="1" name="tax" class="form-control col-md-7 col-xs-12" value="<?=$basic_info['tax']?>">
        </div>
         <label class="control-label col-md-1 col-sm-1 col-xs-6" for="seo_title">% 
        </label>
    </div>
     <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="protocol">Processing Fee <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-10">
            <input  type="number" min="1" name="processing_fee" class="form-control col-md-7 col-xs-12" value="<?=$basic_info['processing_fee']?>">
        </div>
         <label class="control-label col-md-1 col-sm-1 col-xs-6" for="seo_title">% 
        </label>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="protocol">Advertisement <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-10">
            <input  type="number" min="0" name="advertisement" class="form-control col-md-7 col-xs-12" value="<?=$basic_info['advertisement']?>">
        </div>
         <label class="control-label col-md-1 col-sm-1 col-xs-6" for="seo_title">% 
        </label>
    </div>
   

    

    


    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="submit"  class="btn btn-success">Update</button>
        </div>
    </div>

</form>