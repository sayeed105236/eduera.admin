<?php $aToZ = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']; ?>

<?php 

    $lesson = "";
    if($this->session->userdata('lesson_id')){
        $lesson = "&lesson_id=".$this->session->userdata('lesson_id');
    }
?>
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap.min.css"> -->
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
           
           <div class="form-group" style="float: left;">
              <h2><?= count($quiz_set_list->question_list)?> Questions</h2>
              <!-- <?= debug($quiz_set_list->question_list)?> -->
           </div>

       <div class="form-group" style="float: right;">
           <a href="<?=base_url('course/quiz_set/'.$course_info->id.'?option='.$this->session->userdata('option').$lesson)?>" class="btn btn-info">Back</a>
       </div>
    </div>

<br>
<div class="col-md-12 col-sm-12 col-xs-12">
 
      <div class="row">
            <form method="post" action="<?= base_url('course/remove_quiz_question_from_course/'.$course_info->id)?>"> 
            <input type="hidden" name="quiz_set_id" value="<?= $quiz_set_id?>">        
            <input type="hidden" name="lesson_id" value="<?= $this->session->userdata('lesson_id')?>">        
              <table class="table table-striped table-bordered" id="datatable">
                  <thead>
                      <tr>
                          <th><input type="checkbox" id="selectall"/></th>
                          <th>Question</th>
                          <th>Options</th>
                          <th>Right Answer</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php foreach($quiz_set_list->question_list as $question){?>
                      <tr>
                          <td width="10%"><input type="checkbox" class="singlechkbox" name="question_id[]" value="<?= $question->id?>"/></td>
                          <td width="30%"><?= $question->id?>. <?= $question->question?></td>
                          <td width="40%">
                               <?php foreach(json_decode($question->option_list) as $index => $options){?> 
                              <p>
                                   <?= $aToZ[$index] ?> . <?= $options?> 

                              </p>
                               <?php }?> 
                          </td>
                          <td><?= $aToZ[$question->right_option_value] ?></td>
                      </tr>
                   <?php } ?>
                  </tbody>
              </table>
              <button class="btn btn-info"  onclick="remove_question_from_course('<?=$course_info->id ?>')">Remove</button>
            </form>  
         
      </div>
  </div>
  <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap.min.js"></script> -->

</div>
</div>
<script type="text/javascript">
jQuery(function($) {
    $('body').on('click', '#selectall', function() {
        $('.singlechkbox').prop('checked', this.checked);
    });
 
    $('body').on('click', '.singlechkbox', function() {
        if($('.singlechkbox').length == $('.singlechkbox:checked').length) {
            $('#selectall').prop('checked', true);
        } else {
            $("#selectall").prop('checked', false);
        }
 
    });
});


function remove_question_from_course(course_id){
    if (confirm('Are you sure to remove  question this quiz set?')){
        window.location = "<?= base_url('course/remove_quiz_question_from_course/') ?>" + course_id ;
    }
}
</script>

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
    // $(document).ready(function() {
    //     $('#datatable').DataTable();
    // } );
</script>