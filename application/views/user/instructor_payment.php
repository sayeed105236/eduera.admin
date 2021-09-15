<style type="text/css">
    .payment_info{
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">

            <?php echo validation_errors(); ?>

            <?php if ($this->session->flashdata('success')) { ?>

                <div class="alert alert-success alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('success') ?>
                </div>

            <?php } ?>

            <?php if ($this->session->flashdata('danger')) { ?>

                <div class="alert alert-danger alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('danger') ?>
                </div>

            <?php } ?>
            <div class="x_content">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row" style="padding: 5px;">
                        <button type="button" data-toggle="modal" data-target=".instructor_payment_modal" class="btn btn-primary pull-right" onclick="instructor_payment()">Instructor payment</button>
                    </div>
                    <?php $this->load->view('component/instructor_payment_modal'); ?>
                    <div class="payment_info">
                    <p>Total revenue :  <?= currency($total_profit)?></p>
                    <p>Withdraw amount:  <?= (count($total_withdraw_amount) > 0) ?  currency($total_withdraw_amount[0]->total_withdraw_amount) : 0?></p>
                    <p>Payment left: <?= currency($total_profit - $total_withdraw_amount[0]->total_withdraw_amount)?></p>
                </div>
                    <br>
                    <?php if(count($withdraw_amount) > 0){?>
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Instructor name</th>
                                    <th>Withdraw Amount</th>
                                    <th>Payment Left</th>
                                    <th>Date</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php foreach ($withdraw_amount as $index => $amount)  { ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $user_data->first_name?> <?=$user_data->last_name ?></td>
                                        <td><?= currency($amount->withdraw_amount) ?></td>
                                        <td><?= currency($amount->payment_left) ?></td>
                                        <td>
                                            <?= $amount->created_at?>
                                         
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    <?php }else {?>
                        <p>Not found any transaction details.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
   
</script>