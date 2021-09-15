<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  

 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"/>

 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<form id="send_message_form" data-parsley-validate class="form-horizontal form-label-left" style="padding: 30px;" action="<?=base_url('home/message_and_email')?>" method="post">

    <?php echo validation_errors(); ?>

    <?php if ($this->session->flashdata('message_info_update_success')) {?>

       <div class="alert alert-success alert-dismissible  show" role="alert">
           <strong></strong>
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
           <?=$this->session->flashdata('message_info_update_success')?>
       </div>

    <?php }?>

    <?php if ($this->session->flashdata('message_info_update_error')) {?>

       <div class="alert alert-danger alert-dismissible  show" role="alert">
           <strong></strong>
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
           <?=$this->session->flashdata('message_info_update_error')?>
       </div>

    <?php }?>
 
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="level">Courses <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select   class="form-control" name="course_id" id="course_id">
            	<option value="">Select course</option>
               <?php foreach($course_list as $course){?>
                    <option value="<?= $course->id?>"   <?=isset($course_id) && $course_id == $course->id ? 'selected' : ''?>><?= $course->title?> </option>
               <?php }?>
            </select>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
        	<button type="button" id="search"  class="btn btn-info">Search</button>
        </div>
    </div>
<!-- </form> -->
<!-- <form id="send_message_form" data-parsley-validate class="form-horizontal form-label-left" action="<?=base_url('home/message')?>" method="post"> -->


    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="level">Users <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select id="framework" multiple  class="form-control" name="user_id[]"   >
               <?php 
               		foreach($user_list as $user){?>
               			<option value="<?= $user->user_id?>"><?= $user->first_name?> <?= $user->last_name?></option>

               <?php 
              
           }?>
            </select>
       

        </div>
    </div>
    
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Message <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea rows="5" type="text" name="message" class="form-control col-md-7 col-xs-12" ></textarea>
        </div>
    </div>
   
   
   

    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="submit"  class="btn btn-success">Save</button>
        </div>
    </div>

</form>


<!--send  message list-->
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      	<div class="dashboard_graph">

          

            <div class="col-md-12 col-sm-12 col-xs-12">
                <table id="datatable" class="table table-striped table-bordered">

					<thead>
						<tr>
						    <th>Id</th>
							<th>Admin </th>
							<th>User</th>
							<th>Message</th>
						    <th>Date</th>
						</tr>
					</thead>


					<tbody>
						<?php foreach ($user_message_list as $index => $message) { 
							
						?>
						<tr>
							<td><?= $index+1 ?></td>
							<td>
							    <?php
							    
							       $user_list = $this->user_model->get_user_list(
                    					array(
                    						'user' => array('id', 'first_name', 'last_name'),
                    					),
                    					"OBJECT",
                    					array('id'=> $message->admin_id)
                    				
                    				)[0];
                    				
							    ?>
							    
							    
							    <?= $user_list->first_name ?>  <?= $user_list->last_name ?> 
							    
							    </td>
							<td>
							    <?php
							    	foreach ($message->user_id as  $users) {
							    		$user_details = $this->user_model->get_user_list(
	    		         					array(
	    		         						'user' => array('id', 'first_name', 'last_name' ),
	    		         					),
	    		         					"OBJECT",
	    		         					array('user_type' => 'USER', 'id' => $users)
	    		        				
	    		         				);
	    		         				  $users ="";
							    		if (isset($user_details ) ) {
							    			
							    			foreach ($user_details as $key => $user) {
							    				$users .= $user->first_name . ' ' . $user->last_name .' , '  ;
							    				
							    			}
							    			
							    			$result =  substr($users, 0, -1);
							    			echo $result;
							    			
							    			
							    		}

							    	}
							    
							    ?>
						    </td>
							<td><?= $message->message ?></td>
                            <td><?= $message->created_at?></td>
						</tr>	
						<?php } ?>					
					</tbody>
					
				</table>
				
            </div>

            <div class="clearfix"></div>
      	</div>
    </div>

</div>
<script type="text/javascript">
	// $(document).ready(function(){
	 $('#framework').multiselect({
	  nonSelectedText: 'Select User',
	  enableFiltering: true,
	  enableCaseInsensitiveFiltering: true,
	  buttonWidth:'750px',
	  maxHeight: 400,
	  includeSelectAllOption: true
	 });

	 

	$("#search").click(function(){
		course_id = $("#course_id").val();
		window.location.replace("<?= base_url('home/message_and_email/') ?>"+course_id);
		
	})
	
  $(document).ready(function() {
        $('#course_id').select2();
    });
	

	
</script>


<!-- end-->
<script type="text/javascript">
  $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>

