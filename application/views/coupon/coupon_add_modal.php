<div class="modal fade coupon_add_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="coupon_add_form" data-parsley-validate class="form-horizontal form-label-left" action="<?=base_url('course/coupon')?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="coupon_modal_title">Add coupon</h4>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="coupon_id" >                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Select Course<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control" name="course_id">
                                <?php
foreach ($course_list as $key => $course) {?>
                                    <option value="<?=$course->id?>"> <?=$course->title?>

                                    </option>
<?php
}
?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Coupon Code <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="coupon_code" name="coupon_code" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_date">Start Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="start_date" name="start_date" required="required" class="form-control col-md-7 col-xs-12"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_date">End Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="end_date" name="end_date" required="required" class="form-control col-md-7 col-xs-12"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_data">Discount <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" id="discount" name="discount" required="required" class="form-control col-md-7 col-xs-12"/>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">

                            <select name="discount_type" class="form-control">
                                <option value="taka">BDT</option>
                                <option value="percentage">%</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_data">Coupon Limit <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" placeholder="How many people applied it?" id="coupon_limit" name="coupon_limit"  class="form-control col-md-7 col-xs-12"/>
                        </div>
                        
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_data">Status <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>





<!-- Coupon View Modal-->


<div class="modal fade coupon_view_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="coupon_view_form" data-parsley-validate class="form-horizontal form-label-left"  >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="coupon_modal_title">Add coupon</h4>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="coupon_id" >                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Select Course<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control" name="course_id" disabled="">
                                <?php
foreach ($course_list as $key => $course) {?>
                                    <option value="<?=$course->id?>"> <?=$course->title?>

                                    </option>
<?php
}
?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Coupon Code <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input disabled type="text"  name="coupon_code" required="required" class="form-control col-md-7 col-xs-12 coupon_code">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_date">Start Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input disabled type="date"  name="start_date" required="required" class="form-control col-md-7 col-xs-12 start_date"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_date">End Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input disabled type="date" name="end_date" required="required" class="form-control col-md-7 col-xs-12 end_date" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_data">Discount <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input disabled type="number"  name="discount" required="required" class="form-control col-md-7 col-xs-12 discount"/>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">

                            <select name="discount_type" class="form-control discount_type" disabled=""> 
                                <option value="taka">BDT</option>
                                <option value="percentage">%</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_data">Coupon Limit <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input disabled type="number"  name="coupon_limit" required="required" class="form-control col-md-7 col-xs-12 coupon_limit"/>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="already_applied">Already Applied <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input disabled type="number"  name="already_applied" required="required" class="form-control col-md-7 col-xs-12 already_applied"/>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="input-group">
                              <span class="input-group-addon" id="basic-addon1">Left: </span>
                              
                              <input type="number" class="form-control coupon_limit_left" name="coupon_limit_left" disabled="" aria-describedby="basic-addon1">
                            </div>


                            
                        </div>
                        
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_data">Status <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="status" class="form-control status" disabled="">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_data">Course Coupon Link: </label>

                       <div class="col-md-6 col-sm-6 col-xs-12">
                           <!-- <p class="course_link col-md-7 col-xs-12 " id="course_link"></p> -->
                           <input type="text" name="view_course_link" id="view_course_link" class="form-control col-md-7 col-xs-12"> 
                       </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <button type="button" class="btn btn-sm btn-info" onclick="copyLink()">Copy</button>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>







