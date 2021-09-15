<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		if ($this->session->userdata('user_type') !== "ADMIN" && $this->session->userdata('user_type') !== "SUPER_ADMIN") {
			redirect(site_url('login'), 'refresh');
		}
	}

	public function get_header_search_result($query_text = "") {
		$response_output = array(
			'success' => true,
		);

		if ($query_text == "") {
			$response_output['object_list'] = [];
		}
		// debug($this->course_model->get_course_list_by_searching_query_text($query_text));
		$course_list = $this->course_model->get_course_list_by_searching_query_text($query_text, array('id', 'title'));
		$response_output['object_list'] = $course_list;
		echo json_encode($response_output);
	}

	public function is_duplicate_email() {
		if ($this->crud_model->is_duplicate_email($_POST['email'])) {
			echo 1;
		} else {
			echo 0;
		}
	}

	public function get_section_by_id($section_id) {
		echo json_encode($this->section_model->get_section_list("OBJECT", array('title', 'rank'), array('id' => $section_id))[0]);
	}

	public function get_question_by_id($question_id) {
		echo json_encode($this->quiz_model->get_question_by_id($question_id));
	}

	/*
		    * Category - Get category details by category id
	*/
	public function get_single_category_info() {
		$category_id = $this->category_model->get_category_by_id($_GET['id'], array('id', 'name', 'parent'));
		echo json_encode($category_id);
	}

	public function get_lesson_list_by_course_id($course_id) {
		echo json_encode($this->lesson_model->get_lesson_list_by_course_id($course_id, array('title', 'id')));
	}

	public function get_quize_set_info_by_id($quiz_set_id) {
		echo json_encode(
			$this->quiz_model->get_quiz_set_list(
				'OBJECT',
				true,
				array('quiz_set' => array('id', 'name', 'lesson_id', 'type', 'question_id_list', 'duration', 'quiz_result', 'free_access')),
				array('quiz_set.id' => $quiz_set_id)
			));
	}

	public function get_question_details_by_id($question_id) {
		echo json_encode($this->quiz_model->get_question_by_id(
			"OBJECT",
			array("id", "question", "right_option_value", "option_list", "explanation", 'question_img'),
			array("id" => $question_id)
		));
	}

	public function get_faq_category($faq_cat_id) {
		echo json_encode($this->crud_model->get_faq_cat_by_id($faq_cat_id));
	}

	public function get_coupon_info($coupon_id) {
		echo json_encode($this->course_model->get_coupon_list(
			"OBJECT",
			array(
				'coupon' => array('coupon_code', 'discount_type', 'discount', 'start_date', 'end_date', 'status', 'id', 'course_id', 'coupon_limit', 'already_applied'),
				'course' => array('slug')
			),	
			$coupon_id
		)[0]);
	}

	public function get_notification_data() {
				
		$data['notification'] =  $this->user_model->getNotificateData("OBJECT",
			array(
				'id as notify_id',
				'notification_type',
				'notification_id',
				'status',
				'created_at',
			),
			array('status' => 1, 'notification_type' => 'registration')
			);

		$data['course_notification'] =  $this->user_model->getNotificateData("OBJECT",
			array(
				'id as notify_id',
				'notification_type',
				'notification_id',
				'status',
				'created_at',
			),
			array('status' => 1, 'notification_type' => 'course_review')
		);

		for($i = 0; $i < count($data['notification']); $i++){

				$data['notification'][$i]->user_data = $this->user_model->getUserById(
					"OBJECT",
					array('id','first_name', 'last_name','created_at'),
					array('id' => $data['notification'][$i]->notification_id));
		}

		for($j = 0; $j< count($data['course_notification']); $j++){

			$data['course_notification'][$j]->course_data = $this->course_model->get_course_list(
				"OBJECT",
				array('id','title', 'created_at'),
				array('id' => $data['course_notification'][$j]->notification_id)
			);
		}
			

			
		// $data['user_data'] = array();
  //       $data['registration'] = '';
		// foreach($data['notification'] as $notify){
		    
		//    if($notify->notification_type == 'registration'){
  //   		     array_push($data['user_data'], $this->user_model->get_user_by_id($notify->notification_id, array('id','first_name', 'last_name','created_at'))) ;
  //   		     $data['registration'] = 'registration';
		//     }
		// }
		echo json_encode($data);
		
		 
	}


	public function insertUserMessage(){
		date_default_timezone_set('Asia/Dhaka');
		$data['message'] = $this->input->post('message');
		
		$data['user_id'] = $this->input->post('user_id');

		$done = $this->crud_model->insertUserMessage($data);

		echo json_encode($done);
		return true;

	}

	public function get_user_chat_messages($sender_id, $last_chat_message_id){
	    $data = array("user_message" => NULL, "user_info" => NULL);
		$data['user_message'] = $this->crud_model->get_user_chat_messages(
 			'OBJECT',
 			array('chat_message' => array('sender_id', 'receiver_id', 'chat_messages_text', 'chat_messages_status', 'chat_messages_datetime')),
 			array('id'=> $last_chat_message_id, 'sender_id' => $sender_id, 'receiver_id' => $this->session->userdata('user_id'))
 		);
 		
		// 	$data['user_info'] = $this->crud_model->get_user_chat_info_list(
		// 	"OBJECT", 
		// 	array('user_chat_info' => array('id', 'user_id', 'ip_address', 'message_time')
				 
		// 	),
		// 	array('id ' => $sender_id)
		// );
		

 		echo json_encode($data);
	}
			
	public function get_admin_all_unseen_message($sender_id, $receiver_id){
	    $data=array('total_unseen_messages' => NULL, 'user_unseen_message' => NULL);


    	$user_messages = $this->crud_model->get_sender_id($this->session->userdata('user_id'));


		 
		if(isset($user_messages)){
		    
		    $user_list = array();
			foreach ($user_messages as $user) {
				$users = $this->crud_model->get_user_chat_info_list(
					"OBJECT", 
					array('user_chat_info' => array('id', 'user_id', 'ip_address', 'message_time'),
						  'user' => array('first_name', 'last_name', 'profile_photo_name')
					),
					array('id ' => $user->sender_id)
				);
				
				 array_push($user_list, $users);
			
			
				 
			}
			

			
			for($i = 0; $i < count($user_list); $i++){
			     
                $user_list[$i][0]->message_count = $this->crud_model->get_user_chat_messages(
    	 			'COUNT',
    	 			array('chat_message' => array('sender_id', 'receiver_id', 'chat_messages_text', 'chat_messages_status', 'chat_messages_datetime')),
    	 			array('chat_messages_status' => 0, 'message_seen' => 0, 'sender_id' => $user_list[$i][0]->id, 'receiver_id' => $this->session->userdata('user_id'))
	 		    );
            }
                
             $total_unseen_message =  $this->crud_model->get_user_chat_messages(
		 			'COUNT',
		 			array('chat_message' => array('sender_id', 'receiver_id', 'chat_messages_text', 'chat_messages_status', 'chat_messages_datetime')),
		 			array('chat_messages_status' => 0, 'message_seen' => 0, 'receiver_id' => $this->session->userdata('user_id'))
		 		);
			
			    
			   
                $data['total_unseen_messages']= $total_unseen_message;
                $data['user_unseen_message']= $user_list;
                
			    echo json_encode($data);
			}
			
	}

	public function getCourseWiseEnrolledUser($course_id = ''){
	    if($course_id != ''){
	   
	  		 	$user_list =  $this->enroll_model->get_enrollment_info_by_course_id(
	  		  		"OBJECT",
	  		  		$course_id,
	  		  		array('user' => array('id as user_id', 'first_name', 'last_name'),
	  	  			  "course" => array('id as course_id', 'title')
	  	  			),
	  		  		array('user_type' => "USER")
	  		   );

		  	echo json_encode($user_list);
		  
	    }
	}

	// public function user_activity_update($value){

	// 	$user_activity = $this->user_model->user_activity_update($value);
	// 	echo json_encode($user_activity);
	// }

	public function get_currency_info($currency_id) {
		echo json_encode($this->course_model->get_currency_list(
			"OBJECT",
			array(
				'currencies' => array('id', 'name', 'sign', 'value')

			),
			array('id' => $currency_id)
		)[0]);
	}


	/*
		    * Membership - Get member details by member id
	*/
	public function get_single_member_info() {
		$filter_array = array();
		$filter_array['membership_payment.id'] = $_GET['id'];
		
		$membership = $this->user_model->get_membership_data(
			 array(
			 	'membership' => array('id as member_id', 'name', 'email', 'phone'),
				'membership_payment' => array('id', 'membership_type', 'amount', 'notes'),
				'user' => array('first_name', 'last_name', 'email as user_email', 'phone as user_phone')
			),
			"OBJECT",
			$filter_array
			);
		echo json_encode($membership);
	}

	public function get_home_page_setting_data(){
		$id = $this->input->get('id');

		$result = $this->crud_model->home_page_setting($id);
		echo json_encode($result);
	}

}