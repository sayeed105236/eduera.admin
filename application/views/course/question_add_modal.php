<div class="modal fade question_add_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="question_add_form" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left" action="<?=base_url('course/question_bank/' . $course_info->id)?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="course_question_add_modal_title">Add Question</h4>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="course_id" value="<?=$course_info->id?>">
                    <input type="hidden" name="id" id="hidden_question_id_field" value="">
                    <div class="form-group" id="question_form_group">

                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Image (If Any) 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="file" name="question_img"  class="form-control question_photo">
                        </div>
                       
                    </div>

                    <div id="option_div">
                        
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Right Answer <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control"  name="right_option" id="select_right_option">
                                <option value="0">A</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Explanation <span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <textarea id="explanation" name="explanation" class="form-control">
                               
                           </textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Question Photo 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <img src="" id="question_img" width="200" height="150" alt="Not Found">
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="reset" id="course_section_add_reset_button" style="display: none;">Reset</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script type="text/javascript">

    question_to_load = null;

    aToZ = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    basicForm = null;

   
    function addOptionField(){
        event.preventDefault();
        if (question_to_load == null){
            question_to_load = {
                option_list : []
            }
        } else {
            question_to_load.option_list = [];
        }

        var values = $("#option_div textarea[name='option[]']");
        for(var i = 0; i < values.length; i++){
            question_to_load.option_list.push($(values[i]).val());
        }
        question_to_load.option_list.push('');
        load_option_list();
    }

    function removeOptionField(field_id){
        for (var i = 0; i < question_to_load.option_list.length; i++){
            if (field_id == i){
                question_to_load.option_list.splice(i, 1);
                break;
            }
        }
        load_option_list();
    }

    $("#add_question").click(function() {
        $("#course_question_add_modal_title").text("Add Question");
    });



    function load_question_form(){


        var html_code = '' +
            '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="question">Question <span class="required">*</span></label>' +
            '<div class="col-md-6 col-sm-6 col-xs-12">' +
                '<textarea type="text" name="question" required="required" class="form-control col-md-7 col-xs-12">';

        if (question_to_load != null && question_to_load.question != null){
            html_code += question_to_load.question;
        }

        html_code += '</textarea>' +
            '</div>';
        $("#question_form_group").html(html_code);

        load_option_list();

    }


    function load_option_list(){
        var html_code = 
            '<div class="form-group " id="add_question_option_row_0">' +
                '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="question">Option A<span class="required" >*</span>' +
                '</label>' +

                '<div class="col-md-6 col-sm-6 col-xs-12">' + 
                    '<textarea id="start_option" class="form-control" type="text" name="option[]" option_value="A" required="" >';


        if (question_to_load != null && question_to_load.option_list.length > 0){
            html_code += question_to_load.option_list[0];
        }

        html_code += '</textarea>' +
            '</div>' +
            '<div class="col-md-1 col-sm-1 col-xs-12">' +
                '<button type="button"  onclick="addOptionField()" class="btn btn-success btn-sm" name="button">' +
                '<i class="fa fa-plus"></i>' +
                '</button>' +
            '</div>' +
        '</div>';


        if (question_to_load != null && question_to_load.option_list.length > 1) {
            for(var i = 1; i < question_to_load.option_list.length; i++){
                html_code += 
                '<div class="form-group " id="add_question_option_row_' + i + '">' +
                    '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="question">Option ' + aToZ[i] + '<span class="required" >*</span>' +
                    '</label>' +

                    '<div class="col-md-6 col-sm-6 col-xs-12">' + 
                        '<textarea id="start_option" class="form-control" type="text" name="option[]" option_value="' + aToZ[i] + '" required="" >';
                html_code += question_to_load.option_list[i];
                html_code += '</textarea>' +
                    '</div>' +
                    '<div class="col-md-1 col-sm-1 col-xs-12">' + 
                        '<button type="button"  onclick="removeOptionField(' + i + ')" class="btn btn-danger btn-sm" name="button">' + 
                            '<i class="fa fa-minus"></i> ' +
                        '</button>' +
                    '</div>' +
                '</div>';
            }
        }
        $("#option_div").html(html_code);

        html_code = '';

        if (question_to_load != null && question_to_load.option_list.length > 1) {
            for(var i = 0; i < question_to_load.option_list.length; i++){
                html_code += '<option id="option_' + i + '" value="' + i + '" ';
                if (i == question_to_load.right_option_value){
                    html_code += 'selected="selected"';
                }
                html_code += '>' + aToZ[i] + '</option>';
            }
        } else {
            html_code += '<option id="option_' + 0 + '" value="' + 0 + '">' + aToZ[0] + '</option>';
        }
        
        $("#select_right_option").html(html_code);
    }


    function add_question(){
        question_to_load = null;
        load_question_form();
    }

    function editQuestion(id){
        $("#hidden_question_id_field").val(id);
        $("#course_question_add_modal_title").text('Edit Question');

        $.ajax({
            url: '<?php echo base_url('/rest/api/get_question_details_by_id/') ?>'+id,
            type: 'GET',
            success:function(response){
                question_to_load = JSON.parse(response)[0];
               
                document.getElementById("question_img").src = '<?= ('http://localhost/eduera/uploads/question_images/')?>' + question_to_load.question_img;
                $("#explanation").val(question_to_load.explanation);
                question_to_load.option_list = JSON.parse(question_to_load.option_list);
                load_question_form();
            }
        }).done(function() {
            console.log("success");
        }).fail(function() {
            console.log("error");
        }).always(function() {
            console.log("complete");
        });
    }
</script>