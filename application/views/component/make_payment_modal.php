<div class="modal fade user_make_payment_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="user_payment_modal" data-parsley-validate class="form-horizontal form-label-left" action="<?= base_url('users/'. $user_data->id .'/user_enroll_make_payment') ?>" method="post">
                <input type="hidden" name="enrollment_id" >
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Make Payment</h4>
                </div>
                <div class="modal-body">
                    
                    
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="inserted_price">Price </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="price" name="amount" class="form-control col-md-6 col-sm-6 col-xs-12">
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

