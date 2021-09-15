<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      	<div class="dashboard_graph">

            <div class="row x_title">
              	<div class="col-md-12">
                	<h3>Courses</h3>
              	</div>
              	<div class="filter-button-bar">
              		<div class="form-group" style="display: inline-block;">
                      	<select class="form-control" id="category_field">
                            <option value="-1">Category</option>
                            <?php foreach ($category_list as $categoryx) { ?>
                            	<option value="<?= $categoryx->id ?>" <?php if (isset($_GET['category']) == $categoryx->id) { echo 'selected'; } ?>><?= $categoryx->name ?></option>
                            <?php } ?>
                      	</select>
                    </div>
                    <div class="form-group" style="display: inline-block;">
                      	<select class="form-control" id="sub_category_field">
                            <option value="-1">Subcategory</option>
                            <?php if (isset($_GET['category'])) {
                            	foreach ($category_list as $category) {
                            		if ($category->id === $_GET['category']){
                            			foreach ($category->sub_category_list as $sub_category) {
                            				?>
                            				<option value="<?= $sub_category->id ?>" <?php if ($_GET['sub_category'] == $sub_category->id) { echo 'selected'; } ?>><?= $sub_category->name ?></option>
                            				<?php
                            			}
                            		}
                            	}
                            } ?>
                      	</select>
                    </div>
                    <div class="form-group" style="display: inline-block;">
                        <input type="text" id="search_field" required="required" placeholder="Search title" class="form-control" value="<?php if (isset($_GET['search_text'])) { echo $_GET['search_text']; } ?>">
                    </div>
                    <button type="button" class="btn btn-info" onclick="filter_courses()">Filter</button>
                    <button type="button" class="btn btn-info" onclick="clear_filter()">Clear filter</button>
                    <?php if (has_role($this->session->userdata('user_id'), 'COURSE_CREATE')) { ?>
                    <a type="button" class="btn btn-info pull-right" data-toggle="modal" data-target=".course_add_modal">Add new course</a>
                    <?php include 'component/course_add_modal.php'; ?>
                	<?php } ?>
              	</div>
              	<?php if ($this->session->flashdata('course_add_success')) { ?>
              		<div class="filter-button-bar">
              			<?php if ($this->session->flashdata('course_add_success') === 'true') { ?>
              				<div class="alert alert-success alert-dismissible show" role="alert">
				                <strong></strong> 
				                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				                    <span aria-hidden="true">&times;</span>
				                </button>
				                A new course <a href=""><?= $this->session->flashdata('course_add_course_title') ?></a> has just been inserted. <a>Edit the course.</a> 
				            </div>
              			<?php } else if ($this->session->flashdata('course_add_success') === 'false') { ?>
              				<div class="alert alert-danger alert-dismissible show" role="alert">
				                <strong></strong> 
				                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				                    <span aria-hidden="true">&times;</span>
				                </button>
				                Failed to insert course! Please contact developer tema.
				            </div>
              			<?php } ?>
              		</div>
              	<?php } ?>
              	
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <table id="datatable" class="table table-striped table-bordered">

					<thead>
						<tr>
							<th>Course id</th>
							<th>Title</th>
							<th>Price</th>
							<th>Enrollment</th>
							<?php if (has_role($this->session->userdata('user_id'), 'COURSE_UPDATE_INFO')) { ?>
							<th>Action</th>
							<?php } ?>
						</tr>
					</thead>


					<tbody>

						<?php foreach ($course_list as $index => $course) { ?>
							
						<tr>
							<td><?= $course->id ?></td>
							<td><?= $course->title ?></td>
							<td><?= $course->price ?></td>
							<td><?= $course->enrollment_count ?></td>
							<?php if (has_role($this->session->userdata('user_id'), 'COURSE_UPDATE_INFO')) { ?>
							<td>
								<a class="btn" href="<?= base_url('course/info/' . $course->id) ?>"><i class="fa fa-edit"></i></a>
							</td>
							<?php } ?>
						</tr>	
						<?php } ?>					
					</tbody>
					
				</table>
				<nav>
					<span>
						Showing <?= $offset + 1 ?> to <?= $offset + count($course_list) ?> of <?= $number_of_total_courses ?> entries
					</span>
		            <?php
		                echo $this->pagination->create_links();
		            ?>
		        </nav>
            </div>

            <div class="clearfix"></div>
      	</div>
    </div>

</div>
<script type="text/javascript">

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
