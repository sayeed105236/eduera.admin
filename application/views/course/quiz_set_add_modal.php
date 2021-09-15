<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<div class="modal fade quiz_set_add_modal" tabindex="-1" id="myModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="course_quiz_set_form" data-parsley-validate class="form-horizontal form-label-left" action="<?=base_url('course/save_quiz_set_form/' . $course_info->id)?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span>
                    </button>
                    <h4 class="modal-title" id="course_quiz_set_add_modal_title"></h4>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="course_id" value="<?= $course_info->id ?>">
                    <input type="hidden" name="quiz_set_id" id="quiz_set_id">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Quiz set title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="quiz_set_name" name="name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Type <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control"  name="type" id="quiz_set_type">
                                <option value="START">START</option>
                                <option value="END">END</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Choose quiz <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <p>
                                <span>
                                    Course: <input type="radio" name="option" value="course" id="course_radio" />
                                </span>
                                <span>
                                    Lesson: <input type="radio" name="option" value="lesson" id="lesson_radio" />
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="form-group" style="display: none;" id="select_lesson_div">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Lesson <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control"  name="lesson_id" id="selected_lesson_id" style="width: 414px;">
                                <option value="-1">Select Lesson</option>
                                <?php foreach ($course_info->lesson_list as $lesson) {?>
                                    <option value="<?=$lesson->id?>"><?=$lesson->title?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Duration(minutes) 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" id="duration" name="duration"  class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group" >
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Show quiz reuslt 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="checkbox" class="" name="quiz_result" id="quiz_result" />
                            <!-- <select class="form-control"  name="quiz_result" id="quiz_result">
                                <option value="0">Yes</option>
                                <option value="1">No</option>
                                
                            </select> -->
                        </div>
                    </div>

                     <div class="form-group" >
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Free access 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="checkbox" class="" name="free_access" id="free_access" />
                            
                        </div>
                    </div>
<!-- 
                    <div id="option_div">
                        <div class="form-group" id="add_quiz_set_option_row_0">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="question_list" for="option_0">Question List<span class="required" >*</span>
                            </label>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select  class="form-control" id="question_id" style="width: 414px;">
                                    <?php foreach ($question_list as $index => $question) {?>
                                    <option value="<?=$question->id?>"><?= ($index + 1). '. '. $question->question ?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="button"  onclick="addQuestion()" class="btn btn-success btn-sm" name="button">
                                <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="questions" for="option_0">
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12 " id="question_table">
                            <div class="alert alert-danger alert-dismissible message" style="display: none;" role="alert">

                            </div>
                        </div>
                    </div> -->

                   <!--  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="questions" for="option_0">Added Question List<span class="required" >*</span>
                        </label>
                        <input type="hidden" name="question_id_list" id="question_id_list" />
                        <div class="col-md-6 col-sm-6 col-xs-12 " id="question_table" style="overflow: auto;height: 450px;">


                           <table class="table table-bordered">
                               <thead>
                                    <th>#</th>
                                    <th>Question <span style="float:right;"><span id="quiz_set_form_number_of_questions">0</span> Questions</span></th>
                                    <th>Delete</th>
                               </thead>
                               <tbody id="questions_list">

                               </tbody>
                           </table>

                        </div>
                    </div> -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        
        $('#question_id').select2({
        dropdownParent: $('#myModal')
    });

        $('#selected_lesson_id').select2({
        dropdownParent: $('#myModal')
    });
    });


    quiz_set_to_load = null;

    $("#lesson_radio").click(function(){
        $("#select_lesson_div").show();
    });
    $("#course_radio").click(function(){
        $("#select_lesson_div").hide();
    });



    var question_list = <?=json_encode($question_list);?>;
    selectedQuestionList = [];


    // function load_add_quiz_set_form(){
    //     quiz_set_to_load = null;
    //     generate_form();
    // }



    function generate_form(){

        if (quiz_set_to_load == null){
            $("#course_quiz_set_add_modal_title").text('Add quiz set');
            $("#quiz_set_name").val('');
            $("#quiz_set_type").val('END');
            $("#course_radio").prop("checked", true);
            $("#select_lesson_div").hide();
            $("#quiz_set_id").val('');
        } else {
            $("#quiz_set_id").val(quiz_set_to_load.id);
            $("#course_quiz_set_add_modal_title").text('Edit quiz set');
            $("#quiz_set_name").val(quiz_set_to_load.name);
            $("#duration").val(quiz_set_to_load.duration);
            if (quiz_set_to_load.quiz_result == 1){
                $("#quiz_result").prop("checked", true);
            } else {
                $("#quiz_result").prop("checked", false);
               
            }

            if (quiz_set_to_load.free_access == 1){
                $("#free_access").prop("checked", true);
            } else {
                $("#free_access").prop("checked", false);
               
            }

            // $("#quiz_result").val(quiz_set_to_load.quiz_result);
            $("#quiz_set_type").val(quiz_set_to_load.type);
            if (quiz_set_to_load.lesson_id == null){
                $("#course_radio").prop("checked", true);
            } else {
                $("#lesson_radio").prop("checked", true);
                $("#selected_lesson_id").val(quiz_set_to_load.lesson_id);
                $("#select_lesson_div").show();
            }
        }

        generateQuestionTable();
    }


    // function removeQuestion(question_id){
    //     event.preventDefault();

    //     for(var i = 0; i < selectedQuestionList.length; i++){
    //         if(question_id == selectedQuestionList[i].id){
    //             selectedQuestionList.splice(i,1);
    //         }
    //     }
    //     generateQuestionTable();
    // }



    // function generateQuestionTable(){
    //     $("#questions_list").html('');
    //     value = [];
    //     $("#quiz_set_form_number_of_questions").html(selectedQuestionList.length);
    //     for(var i=0; i<selectedQuestionList.length; i++){

    //         $("#questions_list").append(
    //                '<tr><td>' + (i+1) + '</td><td>'+ selectedQuestionList[i].question +'</td><td onclick="removeQuestion('+selectedQuestionList[i].id+')"><a><i class="fa fa-trash"></i></a></td></tr>'
    //             );
    //        value.push(selectedQuestionList[i].id);
    //     }

    //     $("#question_id_list").val(value);
    // }



    // function addQuestion(){
    //     event.preventDefault();

    //     var question_id = $("#question_id").val();
    //     loop1:
    //     for(var i =0; i<question_list.length; i++){
    //         if(question_list[i].id == question_id){
    //             loop2:
    //            for(var j=0; j<selectedQuestionList.length; j++){
    //                 if(selectedQuestionList[j].id == question_id){
    //                     $(".message").show();
    //                     $(".message").text('Already Exist.');
    //                     $(".message").fadeOut(3500);
    //                     break loop1;
    //                 }

    //            }
    //             selectedQuestionList.push(question_list[i]);

    //         }

    //     }
    //     generateQuestionTable();
    // }


    function editQuizSet(quiz_set_id){
        $.ajax({
            url: '<?php echo base_url('/rest/api/get_quize_set_info_by_id/') ?>'+ quiz_set_id,
            type: 'GET',
            dataType: 'json',
            success: function(response){
                quiz_set_to_load = (response)[0];
                selectedQuestionList = quiz_set_to_load.question_list
                generate_form();
            }
        });
    }

</script>