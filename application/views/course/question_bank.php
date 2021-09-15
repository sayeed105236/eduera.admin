<?php $aToZ = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']; ?>
<div style="padding: 25px;">
    <div class="row" style="padding: 7px;">
        <?php echo validation_errors(); ?>
    </div>


    <?php if ($this->session->flashdata('course_question_save_success')) {?>
    <div class="row" style="padding: 7px;">
        <div class="alert alert-success alert-dismissible show" role="alert">
            <strong></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?=$this->session->flashdata('course_question_save_success')?>
        </div>
    </div>
    <?php }?>


    <?php if ($this->session->flashdata('course_question_save_failed')) {?>
    <div class="row" style="padding: 7px;">     
        <div class="alert alert-danger alert-dismissible show" role="alert">
            <strong></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?=$this->session->flashdata('course_question_save_failed')?>
        </div>        
    </div>
    <?php }?>


    <?php if ($this->session->flashdata('question_image_upload_message')) {?>
    <div class="row" style="padding: 7px;">     
        <div class="alert alert-info alert-dismissible show" role="alert">
            <strong></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?=$this->session->flashdata('question_image_upload_message')?>
        </div>        
    </div>
    <?php }?>


    <div class="row" style="padding: 7px;">
        <h3>Total <?= count($question_list) ?> questions</h3>
        <?php if (has_role($this->session->userdata('user_id'), 'QUESTION_CREATE')) { ?>
        <a type="button" id="add_question" class="btn btn-info pull-right" data-toggle="modal" data-target=".question_add_modal" onclick="add_question()">Add Question</a>        
        <?php } ?>
        <div class="col-md-1">
            <form method="GET" id="pagination_form">
                <div class="form-group">
                    <select class="form-control" name="pagination" id="pagination">
                        <option value="10" <?= isset($_GET['pagination']) && $_GET['pagination'] == 10 ?   'selected' : '' ?>>10</option>
                        <option value="25" <?= isset($_GET['pagination']) && $_GET['pagination'] == 25 ?   'selected' : '' ?>>25</option>
                        <option value="50" <?= isset($_GET['pagination']) && $_GET['pagination'] == 50 ?   'selected' : '' ?>>50</option>
                        <option value="100" <?= isset($_GET['pagination']) && $_GET['pagination'] == 100 ?  'selected' : '' ?>>100</option>
                         <option value="500" <?= isset($_GET['pagination']) && $_GET['pagination'] == 500 ?  'selected' : '' ?>>500</option>
                    </select> 
                </div>
            </form>    
        </div>
        <div class="form-group col-md-6">
            <form method="get" id="question-form">
            <input type="text" id="search_question" value="<?= $_GET['search_question']?>" name="search_question" class="form-control" placeholder="Search your question..">
            </form>
        </div>
        
        <?php $this->load->view('course/question_add_modal');?>
    </div>


    <?php
        foreach ($question_list as $index => $question) {
    ?>
        <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">



            <div class="panel">

                <span class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne_<?=$question->id?>" aria-expanded="true" aria-controls="collapseOne">
                    <h4 class="panel-title">
                        <div class="row">
                            <div class="col-md-9 col-sm-9 col-xl-9">
                                <?= $index + 1 ?>. <?=$question->question?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xl-3">
                                <span class="pull-right">
                                    <?php if (has_role($this->session->userdata('user_id'), 'QUESTION_UPDATE')) { ?>
                                    <span onclick="editQuestion(<?=$question->id?>)" data-toggle="modal" data-target=".question_add_modal">
                                        <i class="fa fa-pencil"></i>
                                    </span>
                                    <?php } ?>

                                    <?php if (has_role($this->session->userdata('user_id'), 'QUESTION_DELETE')) { ?>
                                    <span onclick="deleteQuestion('<?= $question->course_id ?>', '<?= $question->id ?>')">
                                        <i class="fa fa-trash" style="padding-left: 15px;"></i>
                                    </span>
                                    <?php } ?>
                                </span>
                            </div>

                        </div>
                    </h4>
                </span>

                <div id="collapseOne_<?=$question->id?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <?php
                       
                        if(isset($question->question_img) && $question->question_img != ''){ ?>
                            <img src="<?=$image_path?><?=$question->question_img?>" width="100" height="100"><br> 

                            <p style="cursor: pointer; margin-top: 10px;" onclick="removeImage('<?=$question->question_img?>', '<?= $question->id?>', '<?= $question->course_id ?>')" class="label label-danger"><i  class="fa fa-trash" ></i> Delete Image</p><br>
                   <?php }
                     ?>
                       
                        <?php if ($question->option_list !== null && count($question->option_list) > 0) { ?>
                            <table class="table table-bordered" style="margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($question->option_list as $option_index => $option) { ?>
                                        <tr <?= $question->right_option_value == $option_index ? 'style="background-color: #cbeccb"' : ''?>>
                                            <th scope="row"><?= $aToZ[$option_index] ?></th>
                                            <td><?= $option ?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        <?php } else {?>
                            <div style="text-align: center;">
                                <p>No Option</p>
                            </div>
                        <?php }?>

                        <p>Explanation: 
                        <?php if($question->explanation != ''){?>
                            <?= $question->explanation?></p>
                        <?php }else{?>    
                            No Explanation
                         <?php } ?>   
                    </div>
                </div>
            </div>
        </div>
                
    <?php }?>
                
</div>
            <div class="footer">
                <nav style="float:right">
                    <?php
echo $this->pagination->create_links();
?>
                </nav>
                <!-- Showing 1 to 10 of 57 entries -->
                <div class="right" style="float:left">
                    <p>Showing <?php echo $offset + 1; ?> to <?php echo $offset + count($question_list); ?> of <?php echo $number_of_total_question ?> entries</p>
                </div>
            </div>

            


<script type="text/javascript">

    function removeImage(question_image, question_id, course_id){
        if(confirm("are you sure to remove this question image?")){
            window.location = "<?= base_url('course/remove_question_image_from_img/'); ?>" + question_image + "/" + question_id + '/' + course_id;
        }
    }
    function deleteQuestion(course_id, question_id){
        if(confirm("are you sure to remove this question?")){
            window.location = "<?= base_url('course/remove_question_from_course/'); ?>" + course_id + "/" + question_id;
        }
    }

    $("#search_question").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            // Do something
             document.getElementById("add_question").submit(); 
            console.log($("#search_question").val());
        }
    });


    $("#pagination").on('change',function(event) {
        event.preventDefault();

        $("#pagination_form").submit();
        /* Act on the event */
    });
</script>
