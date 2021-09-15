<div class="modal fade instructor_payment_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="<?= base_url('users/'. $user_data->id .'/instructor_payment') ?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Instructor payment</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="payment_left" value="<?= $total_profit - $total_withdraw_amount[0]->total_withdraw_amount?>">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profit_share">Withdraw Amount <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input  type="text" class="form-control col-md-7 col-xs-12"  name="withdraw_amount" />
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
