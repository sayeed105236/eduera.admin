<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  

 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"/>


 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


<form id="send_message_form" data-parsley-validate class="form-horizontal form-label-left" style="padding: 30px;" action="<?=base_url('home/send_email')?>" method="POST">

    <?php echo validation_errors(); ?>

    <?php if ($this->session->flashdata('email_info_update_success')) {?>

       <div class="alert alert-success alert-dismissible  show" role="alert">
           <strong></strong>
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
           <?=$this->session->flashdata('email_info_update_success')?>
       </div>

    <?php }?>

    <?php if ($this->session->flashdata('email_info_update_error')) {?>

       <div class="alert alert-danger alert-dismissible  show" role="alert">
           <strong></strong>
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
           <?=$this->session->flashdata('email_info_update_error')?>
       </div>

    <?php }?>
 

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="level">Courses <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select   class="js-example-basic-multiple form-control" name="course_id" id="course_id">
            	<option value="">Select course</option>
               <?php foreach($course_list as $course){?>
                    <option value="<?= $course->id?>"  <?=isset($course_id) && $course_id == $course->id ? 'selected' : ''?>><?= $course->title?> </option>
               <?php }?>
            </select>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
        	<button type="button" id="mailSearch"  class="btn btn-info">Search</button>
        </div>
    </div>




    <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="level">Users <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select id="user" multiple  class="form-control" name="user_email[]"   >
                   <?php 
                   		foreach($user_list as $user){?>
                   			<option value="<?= $user->email?>"><?= $user->first_name?> <?= $user->last_name?></option>

                   <?php 
                  
               }?>
                </select>
           

            </div>
        </div>

      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Email Subject <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="subject" class="form-control col-md-7 col-xs-12" />
        </div>
    </div>
    
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Email Body <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea rows="7" type="text" name="body" class="form-control col-md-7 col-xs-12" ></textarea>
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
							<th>Subject</th>
							<th>Body</th>
						    <th>Date</th>
						</tr>
					</thead>


					<tbody>
						<?php foreach ($user_sending_email_list as $index => $user_sending_email) { 
							// debug($message);
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
                    					array('id'=> $user_sending_email->admin_id)
                    				
                    				)[0];
                    				
							    ?>
							    
							    
							    <?= $user_list->first_name ?>  <?= $user_list->last_name ?> 
							    
							    </td>
							<td>
							    <?php $user_email =  json_decode($user_sending_email->user_email);?>
						    	<?=  implode(", ",$user_email);?>
						    </td>
							<td><?= $user_sending_email->subject ?></td>
							<td><?= $user_sending_email->body ?></td>
                            <td><?= $user_sending_email->created_at?></td>
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

	 $('#user').multiselect({
	  nonSelectedText: 'Select User',
	  enableFiltering: true,
	  enableCaseInsensitiveFiltering: true,
	  buttonWidth:'750px',
	  maxHeight: 400,
	  includeSelectAllOption: true
	 });

	 

	$("#mailSearch").click(function(){
		course_id = $("#course_id").val();
		window.location.replace("<?= base_url('home/send_email/') ?>"+course_id);
	
	})

	var category_list = JSON.parse('<?= json_encode($category_list) ?>');
	console.log(category_list);

	$("#category_field").change(function(){
		$("#sub_category_field").html('<option value="-1">Subcategory</option>');
		for (var i = 0; i < category_list.length; i++){
			if (category_list[i].id == $(this).val()){
				for (var j = 0; j < category_list[i].sub_category_list.length; j++ ){
					$("#sub_category_field").append(
						'<option value="' + category_list[i].sub_category_list[j].id + '">' + category_list[i].sub_category_list[j].name + '</option>'
					);
				}				
				break;
			}
		}
		
	});


	function filter_courses(){
		var category = $("#category_field").val();
		var sub_category = $("#sub_category_field").val();
		var search_text = $("#search_field").val();

		var url = "";

		if (category !== "-1"){
			url += 'category=' + category;
		}

		if (sub_category !== "-1"){
			if (url.length > 0){
				url += "&";
			}
			url += 'sub_category=' + sub_category;
		}


		if (search_text.length > 0){
			if (url.length > 0){
				url += "&";
			}
			url += 'search_text=' + search_text;
		}

		if (url.length > 0){
			url = "<?= base_url('courses?') ?>" + url;
		} else {
			url = "<?= base_url('courses') ?>";
		}

		window.location.replace(url);
	}


	function clear_filter(){
		window.location.replace("<?= base_url('courses') ?>");
	}
</script>


<!-- end-->
<script type="text/javascript">
  $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});

  $(document).ready(function() {
        $('#course_id').select2();
    });
</script>

