<div class="modal fade user_add_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Create new user</h4>
            </div>
            <div style="padding: 20px;">
                <?php $this->load->view('component/user_info_form'); ?>
            </div>
        </div>
    </div>
</div>