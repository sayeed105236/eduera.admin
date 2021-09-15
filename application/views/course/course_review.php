<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap.min.css">
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      	<div class="dashboard_graph">

            <div class="row x_title">
              	<div class="col-md-12">
                	<h3>Course Review</h3>
              	</div>
              	<br>
              	<?php if ($this->session->flashdata('review_success')) {?>
              	   <div class="row" style="padding: 7px;">
              	       <div class="alert alert-success alert-dismissible show" role="alert">
              	           <strong></strong>
              	           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              	               <span aria-hidden="true">&times;</span>
              	           </button>
              	           <?=$this->session->flashdata('review_success')?>
              	       </div>
              	   </div>
              	   <?php }?>


              	   <?php if ($this->session->flashdata('review_failed')) {?>
              	   <div class="row" style="padding: 7px;">     
              	       <div class="alert alert-danger alert-dismissible show" role="alert">
              	           <strong></strong>
              	           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              	               <span aria-hidden="true">&times;</span>
              	           </button>
              	           <?=$this->session->flashdata('review_failed')?>
              	       </div>        
              	   </div>
              	   <?php }?>
              	
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <table id="datatable" class="table table-striped table-bordered">
                	
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>rating</th>
							<th>Review</th>
							<th>Status</th>
							<?php if (has_role($this->session->userdata('user_id'), 'REVIEW_UPDATE')) { ?>
							<!-- <th>Action</th> -->
							<?php } ?>
						</tr>
					</thead>


					<tbody>

						<?php foreach ($course_review_list as $index => $review) { ?>
							
						<tr>
							<td><?= $review->first_name ?> <?= $review->last_name?></td>
							<td><?= $review->email ?></td>
							<td>
									<?php for($i = 1; $i < 6; $i++):?>
                                  <?php if ($i <= $review->rating): ?>
                                    <i class="fa fa-star filled" style="color: #f5c85b;"></i>
                                  <?php else: ?>
                                    <i class="fa fa-star" style="color: #abb0bb;"></i>
                                  <?php endif; ?>
                               <?php endfor; ?>
									

								</td>
							<td><?= $review->review ?></td>
							<td>
								<?php
								if($review->status == 0){
								?>
									 <span onclick="active_review(<?=$review->id?>, 0, '<?= $review->course_id?>')" class="badge badge-danger">Inactive</span>
							    <?php

								}else{
							    ?>
									 <span onclick="active_review(<?=$review->id?>, 1, '<?= $review->course_id?>')" class="badge badge-success">Active</span>
								<?php }?>
								
								</td>
							<?php if (has_role($this->session->userdata('user_id'), 'REVIEW_UPDATE')) { ?>
							<!-- <td>
								<a class="btn" href="<?= base_url('course/course_review/' . $review->id) ?>"><i class="fa fa-edit"></i></a>
							</td> -->
							<?php } ?>
						</tr>	
						<?php } ?>					
					</tbody>
					
				</table>
             
            </div>

            <div class="clearfix"></div>
      	</div>
    </div>

</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">

	$(document).ready(function() {
	    $('#datatable').DataTable();
	} );

	function  active_review(review_id, status, course_id){
		if(status == 0){
			if (confirm('Are you sure to active this review?')){
			           window.location = "<?= base_url('course/update_course_review/') ?>" + review_id + "/" + status + "/" + course_id;
			       }
		}else{
			if (confirm('Are you sure to inactive this review?')){
			           window.location = "<?= base_url('course/update_course_review/') ?>" + review_id + "/" + status + "/" + course_id;
			       }
		}
		
	}
</script>
