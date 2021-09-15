<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quiz_controller extends CI_Controller {
  
  public function __construct(){
    parent::__construct();

    $this->load->database();
    $this->load->library('session');
    /*cache control*/
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    $this->output->set_header('Pragma: no-cache');
    $this->load->library('logger');

    /*admin panel user registration notification */
    date_default_timezone_set('Asia/Dhaka');
    $this->page_data['notification'] = $this->user_model->getNotificateData(
          "OBJECT",
          array(
            'id as notify_id',
            'notification_type',
            'notification_id',
            'status',
            'created_at',
          ),

          array('status' => 1, 'notification_type' =>'registration')
        );

        // debug($this->page_data['notification']);

        $this->page_data['course_notification'] = $this->user_model->getNotificateData(
          "OBJECT",
          array(
            'id as notify_id',
            'notification_type',
            'notification_id',
            'status',
            'created_at',
          ),

          array('status' => 1, 'notification_type' =>'course_review')
        );
        

        for($i = 0; $i < count($this->page_data['notification']); $i++){

          $this->page_data['notification'][$i]->user_data = $this->user_model->getUserById(
            "OBJECT",
            array('id','first_name', 'last_name','created_at'),
            array('id' => $this->page_data['notification'][$i]->notification_id));
          
        }

        for($i = 0; $i < count($this->page_data['course_notification']); $i++){

          $this->page_data['course_notification'][$i]->course_data = $this->course_model->get_course_list(
            "OBJECT",
            array('id', 'title', 'created_at'),
            array('id' => $this->page_data['course_notification'][$i]->notification_id));
          
        }
  }

  public function insert_question(){
    $question =$_POST['question'];
    //debug($question);
    //return true;
    $question_insert_response = $this->quiz_model->insert_question(array(
      "question"            => $question["question"],
      "right_option_value"  => $question["right_option_value"],
      "course_id"           => $question["course_id"]
    ));
    if ($question_insert_response){
      $question_id = $this->db->insert_id();

      foreach ($question["option_list"] as $option) {
        $option_insert_response = $this->quiz_model->insert_question_option(
          array(
            "option"      => $option["option"],
            "value"      => $option["value"],
            "question_id"      => $question_id,
          )
        );

        if (!$option_insert_response) {
          $this->sendFailurMessage("Failed to insert question's option in db");
        }
      }

      $this->sendSuccessMessage("Successful");

    } else {
      $this->sendFailurMessage("Failed to insert question in db");
    }
  }


  public function insert_set(){
    $set =$_POST['quiz_set'];
    //debug($question);
    //return true;

    $quiz_set_table_data = array(
      "type"            => $set["type"],
      "name"  => $set["name"]
    );


    if ($set['set_for'] == "COURSE"){
      $quiz_set_table_data["course_id"] = $set['course_id'];
    } else if ($set['set_for'] == "LESSON"){
      $quiz_set_table_data["lesson_id"] = $set['lesson_id'];
    }

    $set_insert_response = $this->quiz_model->insert_set($quiz_set_table_data);

    
    if ($set_insert_response){
      $set_id = $this->db->insert_id();

      foreach ($set["question_list"] as $question_id) {
        $set_id_question_id_insert_response = $this->quiz_model->insert_in_set_question_table(
          array(
            "set_id"      => $set_id,
            "question_id"      => $question_id,
          )
        );

        if (!$set_id_question_id_insert_response) {
          $this->sendFailurMessage("Failed to insert set id and quesiton id in db");
        }
      }

      $this->sendSuccessMessage("Successful");

    } else {
      $this->sendFailurMessage("Failed to insert set in db");
    }
  }



  /*
  * Get a quiz set list, by course id. With the question list.
  */
  public function get_quiz_set_list_with_question_list_by_course_id($course_id, $type){

    if ($course_id == null || $course_id == ""){
      return false;
    }

    $quiz_set_list = $this->quiz_model->get_quiz_set_list_by_course_id($course_id, $type);
    

    for($i = 0; $i < count($quiz_set_list); $i++){
      $quiz_set_list[$i]["question_list"] = [];
      $question_id_list = $this->quiz_model->get_question_id_list_by_set_id($quiz_set_list[$i]["id"]);
      foreach ($question_id_list as $question_id) {
        array_push($quiz_set_list[$i]["question_list"], $this->quiz_model->get_question_with_option_list_by_id($question_id));
      }
    }

    echo json_encode($quiz_set_list);
    return true;
  }




  /*
  * Get a quiz set list, by lesson id. With the question list.
  */
  public function get_quiz_set_list_with_question_list_by_lesson_id($lesson_id, $type){
    if ($lesson_id == null || $lesson_id == ""){
      return false;
    }

    $quiz_set_list = $this->quiz_model->get_quiz_set_list_by_lesson_id($lesson_id, $type);
    

    for($i = 0; $i < count($quiz_set_list); $i++){
      $quiz_set_list[$i]["question_list"] = [];
      $question_id_list = $this->quiz_model->get_question_id_list_by_set_id($quiz_set_list[$i]["id"]);
      foreach ($question_id_list as $question_id) {
        array_push($quiz_set_list[$i]["question_list"], $this->quiz_model->get_question_with_option_list_by_id($question_id));
      }
    }

    echo(json_encode($quiz_set_list));
    return true;;
  }


  private function sendFailurMessage($message){
    echo json_encode(array("success" => false, "message" => $message));
    return false;
  }

  private function sendSuccessMessage($message){
    echo json_encode(array("success" => true, "message" => $message));
    return true;
  }


  public function insert_assessment_answer(){
    $this->quiz_model->insertAssessmentAnswerRecord($_POST["assessment_id"], $_POST["question_id"], $_POST["given_answer"]);
    return $this->sendSuccessMessage("Assessment answer inserted successfully.");
  }


  public function get_assessment_result($assessment_id){
    $question_list = $this->quiz_model->getAssessmentQuestionWithRightAnswerAndGivenAnswer($assessment_id);
    // debug($question_list);
    $right_answer = 0;
    $attempted = 0;

    foreach($question_list as $quesiton){
      if ($quesiton["given_answer"] != null){
        $attempted++;
        if ($quesiton["right_option_value"] == $quesiton["given_answer"]){
          $right_answer++;
        }
      }      
    }

    $success_rate = round(($right_answer / count($question_list)) * 100, 2);
    $success_rate_of_attempted = round(($right_answer / $attempted) * 100, 2);

    echo json_encode(array(
      "no_of_total_question" => count($question_list),
      "attempted" => $attempted,
      "no_of_right_answer" => $right_answer,
      "success_rate" => $success_rate,
      "success_rate_of_attempted" => $success_rate_of_attempted
    ));
  }

  

}