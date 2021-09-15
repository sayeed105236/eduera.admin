<?php $aToZ = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']; ?>
<div style="padding: 25px;">
    <div class="row" style="padding: 7px;">
        <?php echo validation_errors(); ?>
    </div>
    <div class="row" style="padding: 7px;">
        <?php if ($this->session->flashdata('quiz_set_save_success')) {?>

            <div class="alert alert-success alert-dismissible show" role="alert">
                <strong></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?=$this->session->flashdata('quiz_set_save_success')?>
            </div>

        <?php }?>
    </div>
    <div class="row" style="padding: 7px;">
        <?php if ($this->session->flashdata('quiz_set_save_failed')) {?>

            <div class="alert alert-danger alert-dismissible show" role="alert">
                <strong></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?=$this->session->flashdata('quiz_set_save_failed')?>
            </div>

        <?php }?>
    </div>
    <div class="row" style="padding: 7px;">
        <?php if (has_role($this->session->userdata('user_id'), 'QUIZ_CREATE')) { ?>
        <a type="button" class="btn btn-info pull-right" data-toggle="modal" data-target=".quiz_set_add_modal" onclick="load_add_quiz_set_form()">Add Quiz Set</a>
        <?php } ?>
        <?php include 'quiz_set_add_modal.php';?>

        <div class="" style="padding: 40px 0px 0px 12px;">
            <div class="form-group" style="display: inline-block;">
                <select class="form-control"  name="option_field" id="option_field">
                    <option value="-1">Choose your option</option>
                    <option value="course" <?= isset($_GET['option']) && $_GET['option'] == 'course' ? 'selected': ''?>>Course</option>
                    <option value="lesson" <?= isset($_GET['option']) && $_GET['option'] == 'lesson' ? 'selected': ''?>>Lesson</option>
                </select>
            </div>
            <div class="form-group" style="display: inline-block;" id="lesson_list_div">
                <select class="form-control" name="lesson_id" id="lesson_field">
                    <?php foreach ($course_info->lesson_list as $lesson) { ?>
                        <option value="<?= $lesson->id ?>" <?= isset($_GET['lesson_id']) && $_GET['lesson_id'] == $lesson->id ? 'selected': ''?>><?= $lesson->title ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="button" class="btn btn-info" onclick="filter_quiz_set('<?= $course_info->id ?>')">Filter</button>
        </div>
    </div>
   <!--  <?php
    if (count($quiz_set_list) > 0) {
	    foreach ($quiz_set_list as $key => $quiz_set) { ?>
            <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel">
                    <span class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne_<?=$quiz_set->id?>" aria-expanded="true" aria-controls="collapseOne">
                        <h4 class="panel-title">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xl-4">
                                    <?= $key + 1 ?>. <?= $quiz_set->name ?> (<?= count($quiz_set->question_list) ?> questions)
                                </div> 
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <span class="pull-right">
                                       Duration:  <?= $quiz_set->duration ?  $quiz_set->duration : 0?>
                                    </span>
                                </div>     

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <span class="pull-right">
                                      

                                      Show quiz result: <?php echo ($quiz_set->quiz_result == 1) ? 'No' : 'Yes'; ?>
                                    </span>
                                </div>  

                                  <div class="col-md-2 col-sm-2 col-xs-2">
                                    <span class="pull-right">
                                      
                                      Free access: <?php echo ($quiz_set->free_access == 1) ? 'Yes' : 'No'; ?>
                                    </span>
                                </div>                           
                                <div class="col-md-2 col-sm-2 col-xl-2">
                                    <span class="pull-right">
                                    <?php if (has_role($this->session->userdata('user_id'), 'QUIZ_UPDATE')) { ?>
                                        <span style="margin-left: 10px;" onclick="editQuizSet(<?= $quiz_set->id ?>)" data-toggle="modal" data-target=".quiz_set_add_modal"  >
                                           <i class="fa fa-pencil"></i>
                                        </span>
                                    <?php } ?>
                                    <?php if (has_role($this->session->userdata('user_id'), 'QUIZ_DELETE')) { ?>
                                        <span style="padding-left: 20px;" onclick="delete_quiz_set('<?= $course_info->id ?>', '<?= $quiz_set->id ?>')">
                                           <i class="fa fa-trash"></i>
                                        </span>
                                    <?php } ?>
                                    </span>
                                </div>
                            </div>
                        </h4>
                    </span>

                    <div id="collapseOne_<?=$quiz_set->id?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Question Name</th>
                                        <th>Option </th>
                                        <th>Right answer </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($quiz_set->question_list as $question_index => $question) { ?>
                                    <tr>
                                        <td scope="row"><?= $question_index + 1 ?></td>
                                        <td><?= $question->question ?></td>
                                        <td>
                                            <?php
                                                $question->option_list = json_decode($question->option_list);
                                    			foreach ($question->option_list as $index => $option) {
                                    				echo '<p>' . $aToZ[$index] . '.' . $option . '</p>';
                                    			}
                                            ?>
                                        </td>
                                        <td><?= $aToZ[$question->right_option_value] ?></td>
                                    </tr>
                                     <?php }?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

    <?php
}
}
?> -->

<!-- <?php if (has_role($this->session->userdata('user_id'), 'QUIZ_UPDATE')) { ?>
    <span style="margin-left: 10px;" onclick="editQuizSet(<?= $quiz_set->id ?>)" data-toggle="modal" data-target=".quiz_set_add_modal"  >
       <i class="fa fa-pencil"></i>
    </span>
<?php } ?> -->
<!-- <?php if (has_role($this->session->userdata('user_id'), 'QUIZ_DELETE')) { ?>
    <span style="padding-left: 20px;" onclick="delete_quiz_set('<?= $course_info->id ?>', '<?= $quiz_set->id ?>')">
       <i class="fa fa-trash"></i>
    </span>
<?php } ?> -->
     <?php
    if (count($quiz_set_list) > 0) { 
        

        ?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table id="datatable" class="table table-striped table-bordered">

        <thead>
            <tr>
                <th>Id</th>
                <th>Quiz Name</th>
                <th>Duration</th>
                <th>Show Quiz Result</th>
                <th>Free Access</th>
                <th>Total Question</th>
                
                <th>Action</th>
            </tr>
        </thead>


        <tbody>
            <?php foreach ($quiz_set_list as $index => $quiz_set) {?>
            <tr>
                <td><?=$index + 1?></td>
                <td><?=$quiz_set->name ?></td>
                <td><?= $quiz_set->duration ?  $quiz_set->duration : 0?></td>
                <td><?php echo ($quiz_set->quiz_result == 1) ? 'Yes' : 'No'; ?></td>
                <td> <?php echo ($quiz_set->free_access == 1) ? 'Yes' : 'No'; ?></td>
                <td><?= count($quiz_set->question_list) ?></td>
              <!--   <td><?=$coupon->discount?></td>
                <td><?=$coupon->status == 1 ? 'Active' : 'Inactive'?></td> -->

                <td>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <li><a href="<?= base_url('course/addQuestionInQuiz/'.$course_info->id.'/'.$quiz_set->id)?>">Add Question</a></li>

                          <li><a href="<?= base_url('course/show_questions/'.$course_info->id.'/'.$quiz_set->id)?>">Show Questions</a></li>



                          <li><a href="#"  onclick="editQuizSet(<?= $quiz_set->id ?>)" data-toggle="modal" data-target=".quiz_set_add_modal" >Edit</a></li>
                          <li><a  onclick="delete_quiz_set('<?= $course_info->id ?>', '<?= $quiz_set->id ?>')">Delete</a></li>
                        </ul>
                      </div>
                  <!--  <i style="cursor: pointer;" onclick="edit_coupon(<?=$coupon->id?>)" class="fa fa-edit" data-toggle="modal" data-target=".coupon_add_modal"></i> -->
                </td>

            </tr>
            <?php }?>
        </tbody>

    </table>

</div>
</div>
<?php }?>

<script type="text/javascript">
    <?php
        if (!isset($_GET['lesson_id'])){
            echo '$("#lesson_list_div").hide();';
        }
    ?>
    
    course_id =  '<?php echo $course_info->id; ?>';

    $("#option_field").on('change', function(){
        if($("#option_field").val() == 'lesson'){
            $("#lesson_list_div").show();
        }else{
            $("#lesson_list_div").hide();
        }
    });


    function filter_quiz_set(course_id){
        var option = $("#option_field").val();
        var lesson = $("#lesson_field").val();
        console.log(option);
        var url = "<?= base_url('course/quiz_set/') ?>" + course_id;

        if (option == 'course'){
            url += '?option=course';
        } else if (option == 'lesson'){
            url += '?option=lesson&lesson_id=' + lesson;
        }

        window.location = url;
    }


    function delete_quiz_set(course_id, quiz_set_id){
        if (confirm('Are you sure to remove this quiz set?')){
            window.location = "<?= base_url('course/remove_quiz_set_from_course/') ?>" + course_id + "/" + quiz_set_id;
        }
    }

</script>