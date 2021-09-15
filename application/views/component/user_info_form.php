<form class="form-horizontal form-label-left"  action="
    <?php
    if (has_role($this->session->userdata('user_id'), 'USER_UPDATE')) {
        if ($page_name == 'user_info') {
            echo base_url('users/'.$user_data->id.'/info');
        } else if ($page_name == 'users') {
            echo base_url('users');
        }
    }
    ?>"  method="post">
    <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first_name">First Name <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input id="first_name" class="form-control col-md-7 col-xs-12"  data-validate-words="2" name="first_name" type="text" value="<?php echo $user_data->first_name; ?>">
        </div>
    </div>
    <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_name">Last Name <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input id="last_name" class="form-control col-md-7 col-xs-12"  data-validate-words="2" name="last_name" value="<?php echo $user_data->last_name;?>"  type="text">
        </div>
    </div>
    <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Phone  <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input id="phone" class="form-control col-md-7 col-xs-12"  data-validate-words="2" name="phone"  value="<?php echo $user_data->phone;?>" type="text">
        </div>
    </div>
    <?php if ($page_name == 'users') { ?>
    <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" value="<?php echo $user_data->email?>" id="email" name="email" class="form-control col-md-7 col-xs-12">
        </div>
    </div>
    <?php } ?>

    <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="heard">Status *</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control" id="status" name="status" required>

                <option value="1" <?php if($user_data->status=="1") echo 'selected="selected"'; ?>>Active</option>
                <option value="0" <?php if($user_data->status=="0") echo 'selected="selected"'; ?>>Inactive</option>
            </select>
        </div>
    </div>

   <?php if ($this->session->userdata('user_type') === "SUPER_ADMIN") { 
    
   ?>
   <div class="item form-group">
       <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_type">User Type *</label>
       <div class="col-md-6 col-sm-6 col-xs-12">
           <select class="form-control" id="user_type" name="user_type" required>
               <option value="USER" <?php if($user_data->user_type == 'USER') echo  'selected="selected"'; ?>>User</option>
               <option value="ADMIN" <?php if($user_data->user_type == 'ADMIN') echo  'selected="selected"'; ?>>Admin</option>
               <option value="SUPER_ADMIN" <?php if($user_data->user_type == 'SUPER_ADMIN') echo  'selected="selected"'; ?>>Super Admin</option>
           </select>
       </div>
   </div>
   <?php } ?>



    <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Biography</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea class="resizable_textarea form-control" name="biography" placeholder="Write your biography.."><?php echo $user_data->biography;?></textarea>
        </div>
    </div>

    <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Instructor</label>
            <label class="col-md-6 col-sm-6 col-xs-12">
                <input type="checkbox"  name="instructor" <?php echo ($user_data->instructor==1 ? 'checked' : '');?> value="1" > 
            </label>
    </div>

     <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Membership:</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <label class="radio-inline">
                 <input type="radio" name="membership" value="silver" <?php echo ($membership->membership_type=='silver' ? 'checked' : '');?>>Silver
            </label>
            <label class="radio-inline">
                 <input type="radio" name="membership" value="gold" <?php echo ($membership->membership_type=='gold' ? 'checked' : '');?>>Gold
            </label>
            <label class="radio-inline">
                 <input type="radio" name="membership" value="platinum" <?php echo ($membership->membership_type=='platinum' ? 'checked' : '');?>>Platinum
            </label>
        </div>    
    </div>





    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <?php if ($page_name == 'users') { ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <?php } ?>
            <?php if ($page_name == 'user_info' && has_role($this->session->userdata('user_id'), 'USER_UPDATE')) { ?>
                <button id="send" type="submit" class="btn btn-success">Save</button>
            <?php } ?>
            <?php if ($page_name == 'users' && has_role($this->session->userdata('user_id'), 'USER_CREATE')) { ?>
                <button id="send" type="submit" class="btn btn-success">Create</button>
            <?php } ?>
        </div>
    </div>
</form>

<script type="text/javascript">
    $("#email").on('keyup', function() {
        var email = this.value;

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('rest/api/is_duplicate_email'); ?>",
            data: {email: email},
            success: function(response){
                console.log(response);
                if (response == 1) {
                    $(".sign-up-email-error-message").show();
                    $("#email").addClass("input-error");
                    document.getElementById('email').setCustomValidity('Email already taken!');
                } else {
                    $(".sign-up-email-error-message").hide();
                    $("#email").removeClass("input-error");
                    document.getElementById('email').setCustomValidity('');
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    });
</script>