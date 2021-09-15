<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="dashboard_graph">
			<div class="row x_title">
				<div class="col-md-6">
					<h3>Users</h3>
				</div>
				<div class="col-md-6">
					<?php if (has_role($this->session->userdata('user_id'), 'USER_CREATE')) {?>
						<button type="button" data-toggle="modal" data-target=".user_add_modal" class="btn btn-primary pull-right" >Add New</button>
						<?php include 'component/user_add_modal.php';?>
					<?php }?>
				</div>
				<div style="padding: 40px 0px 0px 12px;">
					<div class="form-group" style="display: inline-block;">
						<select class="form-control" id="user_search_type">
							<option value="-1">Choose user type</option>
							<option value="Registration" <?php if ($_GET['user_search_type'] == 'Registration') {echo 'selected';}?>>Registration</option>
							<option value="Enrollment" <?php if ($_GET['user_search_type'] == 'Enrollment') {echo 'selected';}?>>Enrollment</option>
						</select>
					</div>
					<div class="form-group" style="display: inline-block; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc" id="reportrange">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
						<span>January 01, 2020 - January 31, 2020</span> <b class="caret"></b>
					</div>
				</div>
				<div class="" style="padding: 0px 0px 0px 12px;">
					<div class="form-group" style="display: inline-block;">
						<select class="form-control" id="user_type_field">
							<option value="-1">Choose user type</option>
							<option value="USER" <?php if ($_GET['user_type'] == 'USER') {echo 'selected';}?>>User</option>
							<option value="ADMIN" <?php if ($_GET['user_type'] == 'ADMIN') {echo 'selected';}?>>Admin</option>
						</select>
					</div>
					<div class="form-group" style="display: inline-block;">
						<select class="form-control" id="user_status_field">
							<option value="-1">Choose status</option>
							<option value="1" <?php if ($_GET['user_status'] == '1') {echo 'selected';}?>>Active</option>
							<option value="0" <?php if ($_GET['user_status'] == '0') {echo 'selected';}?>>Inactive</option>
						</select>
					</div>

					
					<div class="form-group" style="display: inline-block;">
						<input type="text" id="search_field" required="required" placeholder="Search name or email" class="form-control" value="<?php if (isset($_GET['search_text'])) {echo $_GET['search_text'];}?>">
					</div>
					<div class="form-group" style="display: inline-block;">
						<select class="form-control" id="course_id">
							<option value="">Choose Course</option>
							<?php
							foreach($course_list as $course){

							?>
							<option value="<?=$course->id?>" <?php if ($_GET['course_id'] == $course->id) {echo 'selected';}?>><?=$course->title?></option>
						<?php } ?>
						</select>
					</div>
					<div class="form-group" style="display: inline-block;">
						<select class="form-control" id="payment_status_field">
							<option value="">Choose payment status</option>
							<option value="Paid" <?php if ($_GET['payment_status'] == 'Paid') {echo 'selected';}?>>Paid</option>
							<option value="Unpaid" <?php if ($_GET['payment_status'] == 'Unpaid') {echo 'selected';}?>>Unpaid</option>
						</select>
					</div>					
					<button type="button" class="btn btn-info" onclick="filter_users()">Filter</button>
					<button type="button" class="btn btn-info" onclick="clear_filter()">Clear filter</button>
					<button type="button" class="btn btn-warning" onclick="filter_users('yes')">Download</button>

				</div>


			</div>

			<div class="col-md-12 col-sm-12 col-xs-12">
				<?php echo validation_errors(); ?>
				<?php if ($this->session->flashdata('user_save')) {?>

					<div class="alert alert-success alert-dismissible  show" role="alert">
						<strong></strong>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<?=$this->session->flashdata('user_save')?>
					</div>

				<?php }?>

				<?php if ($this->session->flashdata('duplicate_email')) {?>

					<div class="alert alert-danger alert-dismissible  show" role="alert">
						<strong></strong>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<?=$this->session->flashdata('duplicate_email')?>
					</div>

				<?php }?>
				<table id="datatable" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Email</th>
							<th>Courses</th>
							<th>Phone</th>
							<th>User type</th>
							<th>Registration Date</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>


					<tbody>
						<?php foreach ($user_list as $index => $user) {
	?>
							<tr>
								<td><?=$index + 1?></td>
								<td><?=$user->first_name . ' ' . $user->last_name?></td>
								<td><?=$user->email?></td>
								<td width="15%">

									<?php foreach ($user->enroll_history as $enroll) {
		?>
										<span title="<?=$enroll->title?> &#013;Enrolled Date: <?=$enroll->created_at?> &#013;Enrolled Price: <?=$enroll->enrolled_price?> &#013;Paid Amount:  <?=($enroll->paid_amount > 0) ? $enroll->paid_amount : 0?>" class="<?php
if ($enroll->paid_amount == 0 || $enroll->paid_amount == null) {
			echo 'dot_not_paid';
		} else if ($enroll->paid_amount >= $enroll->enrolled_price) {
			echo 'dot_full_paid';
		} else {
			echo 'dot_partial_paid';
		}
		?>"></span>
									<?php
}
	?>

								</td>
								<td><?=$user->phone?></td>
								<td><?=$user->user_type?></td>
								<td><?= date_format(date_create($user->created_at), 'd/m/Y') ?></td>
								<td><?php
if ($user->status == 1) {?>
									<button
									type="button" class="btn btn-round btn-success">Active</button>
									<?php
} else {?>

									<button
									type="button" class="btn btn-round btn-warning">Inactive</button>
								<?php }?>

							</td>
							<td>
								<?php if (has_role($this->session->userdata('user_id'), 'USER_UPDATE')) {?>
									<a class="btn " href="<?php echo base_url('users/' . $user->id . '/info') ?>">
										<i class="fa fa-edit"></i>
									</a>
								<?php } else if (has_role($this->session->userdata('user_id'), 'USER_READ')) {?>
									<a class="btn " href="<?php echo base_url('users/' . $user->id . '/info') ?>">
										<i class="fa fa-eye"></i>
									</a>
								<?php }?>
							</td>
						</tr>
					<?php }?>

				</tbody>
			</table>
			<div class="footer">
				<nav style="float:right">
					<?php
echo $this->pagination->create_links();
?>
				</nav>
				<!-- Showing 1 to 10 of 57 entries -->
				<div class="right" style="float:left">
					<p>Showing <?php echo $offset + 1; ?> to <?php echo $offset + count($user_list); ?> of <?php echo $number_of_total_users ?> entries</p>
				</div>
			</div>
		</div>

		<div class="clearfix"></div>
	</div>
</div>
</div>



<!-- /modals -->
<script type="text/javascript">



eduera_picker = null;
	function filter_users(download = ''){
		var user_type = $("#user_type_field").val();
		var user_status = $("#user_status_field").val();
		var search_text = $("#search_field").val();
		var user_search_type = $("#user_search_type").val();
		var payment_status_field = $("#payment_status_field").val();
		var course_id = $("#course_id").val();
		var url = "";
		if(eduera_picker != null){
			start_date = eduera_picker.startDate.format('YYYY-M-D');
			end_date = eduera_picker.endDate.format('YYYY-M-D');
			if(start_date != null){
				url +='start_date=' + start_date;
			}

			if(end_date != null){
				if (url.length > 0){
					url += "&";
				}
				url +='end_date=' + end_date;
			}

			if(user_search_type != null){
				if (url.length > 0){
					url += "&";
				}
				url +='user_search_type=' + user_search_type;
			}

		}

		if (user_type !== "-1"){
			if (url.length > 0){
					url += "&";
				}
			url += 'user_type=' + user_type;
		}

		if (user_status !== "-1"){
			if (url.length > 0){
				url += "&";
			}
			url += 'user_status=' + user_status;
		}


		if (search_text.length > 0){
			if (url.length > 0){
				url += "&";
			}
			url += 'search_text=' + search_text;
		}

		if (course_id.length > 0){
			if (url.length > 0){
				url += "&";
			}
			url += 'course_id=' + course_id;
		}

		if (payment_status_field.length > 0){
			if (url.length > 0){
				url += "&";
			}
			url += 'payment_status=' + payment_status_field;
		}

		if (url.length > 0){
			url = "<?=base_url('users?')?>" + url;
		} else {
			url = "<?=base_url('users')?>";
		}

		if(download == 'yes' && download != ''){
			
			if (url.length > 35){
				url += "&download=yes";
			}else{
				url += '?download=yes';
			}

			
		}

		// console.log(start_date);
		// console.log('Our start date -> ' + eduera_picker.startDate.format('M-D-YYYY'));
		// console.log('Our end date -> ' + eduera_picker.endDate.format('M-D-YYYY'));
		window.location.replace(url);
	}


	function clear_filter(){
		window.location.replace("<?=base_url('users')?>");
	}

	///////////////////////////////////////////////
	// Daterange picker
	///////////////////////////////////////////////

	$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		eduera_picker = picker;
	})




	function fnExcelReport()
	{
	    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
	    var textRange; var j=0;
	    tab = document.getElementById('datatable'); // id of table

	    for(j = 0 ; j < tab.rows.length ; j++) 
	    {     
	        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
	        //tab_text=tab_text+"</tr>";
	    }

	    tab_text=tab_text+"</table>";
	    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
	    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
	    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

	    var ua = window.navigator.userAgent;
	    var msie = ua.indexOf("MSIE "); 

	    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
	    {
	        txtArea1.document.open("txt/html","replace");
	        txtArea1.document.write(tab_text);
	        txtArea1.document.close();
	        txtArea1.focus(); 
	        sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
	    }  
	    else                 //other browser not tested on IE 11
	        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

	    return (sa);
	}

</script>