<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="dashboard_graph">
			<div class="row x_title">
				<div class="col-md-6">
					<h3>Membership</h3>
				</div>
			 <div class="col-md-6">
			 	<?php if (has_role($this->session->userdata('user_id'), 'MEMBERSHIP_CREATE')) {?>
			 		<button type="button" data-toggle="modal" data-target=".membership_add_modal" class="btn btn-primary pull-right" >Add New Member</button>
			 		<?php include 'membership_add_modal.php';?>
			 	<?php }?>
			 </div>
				<div style="padding: 40px 0px 0px 12px;">
					
					<div class="form-group" style="display: inline-block; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc" id="reportrange">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
						<span>January 01, 2020 - January 31, 2020</span> <b class="caret"></b>
					</div>
				</div> 
				<div class="" style="padding: 0px 0px 0px 12px;">
					<div class="form-group" style="display: inline-block;">
						<select class="form-control" id="membership_field">
							<option value="-1">Choose member </option>
							<option value="all">All</option>
							<option value="silver" <?php if ($_GET['membership'] == 'silver') {echo 'selected';}?>>Silver </option>
							<option value="gold" <?php if ($_GET['membership'] == 'gold') {echo 'selected';}?>>Gold </option>
							<option value="platinum" <?php if ($_GET['membership'] == 'platinum') {echo 'selected';}?>>Platinum </option>
						
						</select>
					</div>

					<div class="form-group" style="display: inline-block;">
						<input type="text" id="badge_id" name="badge_id" class="form-control" placeholder="Enter the badge id">
					</div>
				
					<button type="button" class="btn btn-info" onclick="filter_users()">Filter</button>
					<button type="button" class="btn btn-info" onclick="clear_filter()">Clear filter</button>
					
				</div>


			</div>

			<div class="col-md-12 col-sm-12 col-xs-12">
				<?php echo validation_errors(); ?>
				<?php if ($this->session->flashdata('membership_update_success')) {?>

					<div class="alert alert-success alert-dismissible  show" role="alert">
						<strong></strong>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<?=$this->session->flashdata('membership_update_success')?>
					</div>

				<?php }?>

				<?php if ($this->session->flashdata('membership_update_error')) {?>

					<div class="alert alert-danger alert-dismissible  show" role="alert">
						<strong></strong>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<?=$this->session->flashdata('membership_update_error')?>
					</div>

				<?php }?>
				<table id="datatable" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Membership</th>
							<th>Notes</th>
							<th>Badge Id</th>
							<th>Date</th>
							<th>Action</th>
						</tr>
					</thead>


					<tbody>
						<?php foreach ($membership_list as $index => $member) {

	?>	
							<tr>
								<td><?=$index + 1?></td>
								<td>
									<?php

										if($member->name == null){
											echo $member->first_name;
										}else{
											echo $member->name;
										}
									?>
									
										
									</td>

								<td>
									<?php

										if($member->email == null){
											echo $member->user_email;
										}else{
											echo $member->email;
										}
									?>
										
									</td>
								<td>
									<?php

										if($member->phone == null){
											echo $member->user_phone;
										}else{
											echo $member->phone;
										}
									?>
									
										
									</td>
								<td><?=ucfirst($member->membership_type)?></td>
								<td><?= $member->notes ?></td>
								<td><?= $member->membership_badge_id ?></td>
								<td><?= date_format(date_create($member->created_on), 'd/m/Y') ?></td>
								
						 	<td>
								
								
								<?php   if (has_role($this->session->userdata('user_id'), 'MEMBERSHIP_UPDATE')) {?>
									<a  onclick="return confirm('Are you agree send the email?');"  href="<?php echo base_url('home/send_memberhsip_email/' .  $member->id) ?>">
										<i class="fa fa-inbox"></i>
									</a>
								<?php }?>
								||
								<?php   
								if (has_role($this->session->userdata('user_id'), 'MEMBERSHIP_UPDATE')) {
									?>
									<a  onclick="editMember(<?= $member->id?>)" class="fa fa-pencil-square-o" aria-hidden="true" data-toggle="modal" data-target=".member-modal-edit">
										<!-- <i class="fa fa-pencil"></i> -->
									</a>
								<?php
								 }
								?>
								||
								<?php   if (has_role($this->session->userdata('user_id'), 'MEMBERSHIP_DELETE')) {?>
									<a  onclick="return confirm('Are you agree remove this member?');" 	
									<?php if( $member->membership_id != null){?>
									 href="<?php echo base_url('home/remove_member?member_id='.$member->membership_id ) ?>"
									 <?php 
									}else{
										?>
										 href="<?php echo base_url('home/remove_member?id=' .  $member->id) ?>"
										<?php
									}
									 ?>
									 >
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
					<p>Showing <?php echo $offset + 1; ?> to <?php echo $offset + count($membership_list); ?> of <?php echo $number_of_total_membership_data ?> entries</p>
				</div>
			</div>
		</div>

		<div class="clearfix"></div>
	</div>
</div>
</div>

    
    
    




<!-- /modals -->
<script type="text/javascript">
function editMember(id){

        console.log(id);

        $.ajax({
            type: "GET",
            url: "<?php echo site_url('rest/api/get_single_member_info'); ?>",
            data: {id: id},
            success: function(response){

                var json = JSON.parse(response);
                console.log(json[0]);

                $("#membership-form input[name='id']").val(json[0]['id']);
                $("#membership-form input[name='member_id']").val(json[0]['member_id']);
                if(json[0]['name'] != null){
                	$("#membership-form input[name='name']").val(json[0]['name']);
                	$("#membership-form input[name='name']").prop('disabled', false);
                }else{
                	$("#membership-form input[name='name']").val(json[0]['first_name'] + ' ' + json[0]['last_name']);
                	$("#membership-form input[name='name']").prop('disabled', true);
                }

                if(json[0]['email'] != null){
                	$("#membership-form input[name='email']").val(json[0]['email']);
                	$("#membership-form input[name='email']").prop('disabled', false);
                }else{
                	$("#membership-form input[name='email']").val(json[0]['user_email']);
                	$("#membership-form input[name='email']").prop('disabled', true);
                }

                if(json[0]['phone'] != null){
                	$("#membership-form input[name='phone']").val(json[0]['phone']);
                	$("#membership-form input[name='phone']").prop('disabled', false);
                }else{
                	$("#membership-form input[name='phone']").val(json[0]['user_phone']);
                	$("#membership-form input[name='phone']").prop('disabled', true);
                }
                
                // $("#membership-form input[name='email']").val(json[0]['email']);
                // $("#membership-form input[name='phone']").val(json[0]['phone']);
                $("#membership-form select[name='membership_type']").val(json[0]['membership_type']);
                $("#membership-form input[name='amount']").val(json[0]['amount']);
                $("#membership-form textarea[name='notes']").val(json[0]['notes']);
             
            },
            error: function (request, status, error) {
                console.log(request);
            }
        });
    }



eduera_picker = null;
	function filter_users(){
		var membership = $("#membership_field").val();
		var badge_id = $("#badge_id").val();
	
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

	

		}

		if (membership !== "-1"){
			if (url.length > 0){
					url += "&";
				}
			url += 'membership=' + membership;
		}

		if (badge_id !== "-1"){
			if (url.length > 0){
					url += "&";
				}
			url += 'badge=' + badge_id;
		}

	

		if (url.length > 0){
			url = "<?=base_url('home/membership?')?>" + url;
		} else {
			url = "<?=base_url('home/membership')?>";
		}


	
		window.location.replace(url);
	}


	function clear_filter(){
		window.location.replace("<?=base_url('home/membership')?>");
	}

	///////////////////////////////////////////////
	// Daterange picker
	///////////////////////////////////////////////

	$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		eduera_picker = picker;
	})

	
</script>