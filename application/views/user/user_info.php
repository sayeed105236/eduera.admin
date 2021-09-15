
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">

            <?php echo validation_errors(); ?>

            <?php if ($this->session->flashdata('successfully')) { ?>

                <div class="alert alert-success alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('successfully') ?>
                </div>

            <?php } ?>

            <?php if ($this->session->flashdata('user_update_error')) { ?>

                <div class="alert alert-danger alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('user_update_error') ?>
                </div>

            <?php } ?>
            <div class="x_content">
                <?php $this->load->view('component/user_info_form') ?>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

</div>
