<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="dashboard_graph">
			<div class="row x_title">
				<div class="col-md-6">
					<h3>Admin Log</h3>
				</div>
			 
				<div style="padding: 40px 0px 0px 12px;">
					
					<div class="form-group" style="display: inline-block; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc" id="reportrange">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
						<span>January 01, 2020 - January 31, 2020</span> <b class="caret"></b>
					</div>
				</div> 
				<div class="" style="padding: 0px 0px 0px 12px;">
					<div class="form-group" style="display: inline-block;">
						<select class="form-control" id="user_field">
							<option value="-1">Choose user </option>
							<?php foreach($user_list as $admin){?>
							<option value="<?= $admin->id?>"  <?php if ($_GET['user'] == $admin->id) {echo 'selected';}?>><?= $admin->first_name?> <?= $admin->last_name?></option>
						<?php }?>
						</select>
					</div>
				<!-- 	<div class="form-group" style="display: inline-block;">
						<select class="form-control" id="user_status_field">
							<option value="-1">Choose status</option>
							<option value="1" <?php if ($_GET['user_status'] == '1') {echo 'selected';}?>>Active</option>
							<option value="0" <?php if ($_GET['user_status'] == '0') {echo 'selected';}?>>Inactive</option>
						</select>
					</div>
					<div class="form-group" style="display: inline-block;">
						<input type="text" id="search_field" required="required" placeholder="Search name or email" class="form-control" value="<?php if (isset($_GET['search_text'])) {echo $_GET['search_text'];}?>">
					</div>		 -->			
					<button type="button" class="btn btn-info" onclick="filter_users()">Filter</button>
					<button type="button" class="btn btn-info" onclick="clear_filter()">Clear filter</button>
					
				</div>


			</div>

			<div class="col-md-12 col-sm-12 col-xs-12">
				<?php echo validation_errors(); ?>
				<?php if ($this->session->flashdata('success')) {?>

					<div class="alert alert-success alert-dismissible  show" role="alert">
						<strong></strong>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<?=$this->session->flashdata('success')?>
					</div>

				<?php }?>

				<?php if ($this->session->flashdata('danger')) {?>

					<div class="alert alert-danger alert-dismissible  show" role="alert">
						<strong></strong>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<?=$this->session->flashdata('danger')?>
					</div>

				<?php }?>
				<table id="datatable" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Type</th>
							<th>Details</th>
							
							<th>Comment</th>
							<th>Action Date</th>
							<th>Action</th>
						</tr>
					</thead>


					<tbody>
						<?php foreach ($admin_log_list as $index => $user) {

	?>	
							<tr>
								<td><?=$index + 1?></td>
								<td><?=$user->first_name?> <?=$user->last_name?></td>
								<td><?=$user->type?></td>
								<?php $details =  json_decode($user->user_details)?>
								<td>Country: <?=$details[0]?>, City:  <?=$details[1]?>, Address:  <?=$details[2]?>, IP:  <?=$details[3]?>, LAT,LON:  <?=$details[4]?>, POSTAL:  <?=$details[5]?></td>
								<td><?= $user->comment ?></td>
								<td><?= date_format(date_create($user->created_on), 'd/m/Y') ?></td>
								
								
						 	<td>
							

								<?php   if (has_role($this->session->userdata('user_id'), 'ADMIN_LOG_DELETE')) {?>
									<a style="color: red; " onclick="return confirm('Are you agree remove this file?');"  href="<?php echo base_url('home/remove_admin_log/' .  $user->id) ?>">
										<i class="fa fa-trash"></i>
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
					<p>Showing <?php echo $offset + 1; ?> to <?php echo $offset + count($admin_log_list); ?> of <?php echo $number_of_total_log_data ?> entries</p>
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
	function filter_users(){
		var user = $("#user_field").val();
		// var user_status = $("#user_status_field").val();
		// var search_text = $("#search_field").val();
		// var user_search_type = $("#user_search_type").val();
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

			// if(user_search_type != null){
			// 	if (url.length > 0){
			// 		url += "&";
			// 	}
			// 	url +='user_search_type=' + user_search_type;
			// }

		}

		if (user !== "-1"){
			if (url.length > 0){
					url += "&";
				}
			url += 'user=' + user;
		}

		// if (user_status !== "-1"){
		// 	if (url.length > 0){
		// 		url += "&";
		// 	}
		// 	url += 'user_status=' + user_status;
		// }


		// if (search_text.length > 0){
		// 	if (url.length > 0){
		// 		url += "&";
		// 	}
		// 	url += 'search_text=' + search_text;
		// }

		if (url.length > 0){
			url = "<?=base_url('home/admin_logger?')?>" + url;
		} else {
			url = "<?=base_url('home/admin_logger')?>";
		}


		// console.log(start_date);
		// console.log('Our start date -> ' + eduera_picker.startDate.format('M-D-YYYY'));
		// console.log('Our end date -> ' + eduera_picker.endDate.format('M-D-YYYY'));
		window.location.replace(url);
	}


	function clear_filter(){
		window.location.replace("<?=base_url('home/admin_logger')?>");
	}

	///////////////////////////////////////////////
	// Daterange picker
	///////////////////////////////////////////////

	$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		eduera_picker = picker;
	})

</script>