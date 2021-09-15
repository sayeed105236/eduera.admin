<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Home extends CI_Controller {

	private $page_data = [];

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->page_data['system_name'] = get_settings('system_name');
		$this->pagination_config = array();
		if ($this->session->userdata('user_type') !== "ADMIN" && $this->session->userdata('user_type') !== "SUPER_ADMIN") {
			redirect(site_url('login'), 'refresh');
		}

		$this->page_data['total_users'] = $this->user_model->get_user_count();
		$this->page_data['total_enrollment'] = $this->enroll_model->get_enrollment_list("COUNT", array('id'));
		$this->page_data['total_courses'] = $this->course_model->get_all_courses_count();
		$this->page_data['total_day_amount'] = $this->enroll_model->getTotalAmountForToday();

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


		$this->page_data['total_unseen_message'] = $this->crud_model->get_user_chat_messages(
 			'COUNT',
 			array('chat_message' => array('sender_id', 'receiver_id', 'chat_messages_text', 'chat_messages_status', 'chat_messages_datetime')),
 			array('chat_messages_status' => 0, 'message_seen' => 0, 'receiver_id' => $this->session->userdata('user_id'))
 		);
 		
	}

	/*
		*   Default controller for base url.
	*/
	public function index() {

		// debug($this->page_data['notification']);
		// debug($this->page_data['course_notification']);
		$this->dashboard();
	}

	public function createexcel(){
		
		debug($this->session->userdata);
	}
	
	function get_instructor_courses_info()
	{
	//	debug($this->session->userdata);
		$course = $this->course_model->get_instructor_courses_info($this->session->userdata['user_id']);
		//debug($course);
		
		return $course;
	}
	

	/*
		    *   Default controller for /home page
	*/
	public function dashboard() {

		$this->page_data['page_name'] = "dashbaord";
		$this->page_data['page_title'] = "Dashboard";
		$this->page_data['page_view'] = "dashboard";
		


		$this->load->view('index', $this->page_data);
	}


	/*
		    *   Default controller for /courses page
		    *   This page shows all the courses
	*/
	public function courses($offset = 0) {

		$this->page_data['category_list'] = $this->category_model->get_category_list_with_nested_sub_category_list(array('id', 'name'));
		
		
		//debug($this->page_data['category_list']);
		
		$this->page_data['page_name'] = "courses";
		$this->page_data['page_title'] = "Courses";
		$this->page_data['page_view'] = "courses";

		$filter_array = array();
		$pagination_config['per_page'] = 10;

		if (isset($_GET['sub_category'])) {
			$filter_array['category_id'] = $_GET['sub_category'];
		} else if (isset($_GET['category'])) {
			$filter_array['category_id'] = $_GET['category'];
		}

		if (isset($_GET['status'])) {
			$filter_array['status'] = $_GET['status'];
		}

		if (isset($_GET['price_range'])) {

			if ($_GET['price_range'] == 0) {
				$price_range = array(
					"min" => 0,
					"max" => 0,
				);
			} else if ($_GET['price_range'] == 1) {
				$price_range = array(
					"min" => 0,
					"max" => 100,
				);
			} else if ($_GET['price_range'] == 2) {
				$price_range = array(
					"min" => 101,
					"max" => 500,
				);
			} else if ($_GET['price_range'] == 3) {
				$price_range = array(
					"min" => 501,
					"max" => 2000,
				);
			} else if ($_GET['price_range'] == 4) {
				$price_range = array(
					"min" => 2001,
					"max" => 5000,
				);
			} else if ($_GET['price_range'] == 5) {
				$price_range = array(
					"min" => 2001,
					"max" => 5000,
				);
			} else if ($_GET['price_range'] == 6) {
				$price_range = array(
					"min" => 5001,
					"max" => 30000,
				);
			}if ($_GET['price_range'] == 7) {
				$price_range = array(
					"min" => 30001,
					"max" => 5000000,
				);
			}

		}
		
		$courses_arra = $this->get_instructor_courses_info();
		

		if (isset($_GET['search_text'])) {
			$pagination_config['total_rows'] = $this->page_data['course_list'] = $this->course_model->get_course_list_with_enrollment_admin(
				array('id'),
				"COUNT",
				$filter_array,
				$_GET['search_text'],null,$courses_arra
			);
			$this->page_data['course_list'] = $this->course_model->get_course_list_with_enrollment_admin(
				array('id', 'title', 'price'),
				"OBJECT",
				$filter_array,
				$_GET['search_text'],
				array('limit' => $pagination_config['per_page'], 'offset' => $offset),$courses_arra
			);
		} else {
			$pagination_config['total_rows'] = $this->page_data['course_list'] = $this->course_model->get_course_list_with_enrollment_admin(
				array('id'),
				"COUNT",
				$filter_array,null,null,$courses_arra
			);
			$this->page_data['course_list'] = $this->course_model->get_course_list_with_enrollment_admin(
				array('id', 'title', 'price'),
				"OBJECT",
				$filter_array,
				null,
				array('limit' => $pagination_config['per_page'], 'offset' => $offset),$courses_arra
			);

		}

		// $this->page_data['page_name'] = "courses";
		// $this->page_data['page_title'] = 'Courses';
		// $this->page_data['page_view'] = "home/courses";
		// $this->page_data['page_limit'] = $pagination_config['per_page'];
		// $this->page_data['number_of_courses_for_this_page'] = $pagination_config['per_page'] == count($course_list) ? $pagination_config['per_page'] : count($course_list);

		$this->page_data['offset'] = $offset;
		$this->page_data['number_of_total_courses'] = $pagination_config['total_rows'];

		// debug($this->page_data);

		$pagination_config['base_url'] = site_url('home/courses/');
		$pagination_config['num_links'] = 10;
		$pagination_config['reuse_query_string'] = TRUE;
		$pagination_config['full_tag_open'] = '<div class="pagination-bar pull-right">';
		$pagination_config['full_tag_close'] = '</div>';
		$pagination_config['attributes'] = array('class' => 'pagination-bar-node');
		$pagination_config['first_link'] = 'First page';
		$pagination_config['last_link'] = 'Last page';
		$pagination_config['cur_tag_open'] = '<span class="pagination-bar-node pagination-bar-node-active">';
		$pagination_config['cur_tag_close'] = '</span>';

		$this->pagination->initialize($pagination_config);
		$this->load->view('index', $this->page_data);
	}

	/*
		    * Controller - Get all categories
	*/

	public function categories() {

		if (!has_role($this->session->userdata('user_id'), 'CATEGORY_READ')) {
			redirect(base_url('page_not_found'));
		}

		$this->page_data['page_name'] = "categories";
		$this->page_data['page_title'] = "Categories";
		$this->page_data['page_view'] = "category/categories";

		$this->page_data['category_list'] = $this->category_model->get_category_list_with_nested_sub_category_list(array('id', 'parent', 'name'));
		$this->load->view('index', $this->page_data);
	}

	/*
		    *   Service - add a course with very minimal information
	*/
	public function add_course() {

		if (!has_role($this->session->userdata('user_id'), 'COURSE_CREATE')) {
			redirect(base_url('page_not_found'));
		}

		$data['title'] = trim(html_escape($this->input->post('title')));
		$data['short_description'] = html_escape($this->input->post('short_description'));

		$base_slug = string_to_slug($data['title']);
		$slug = $base_slug;
		$slug_counter = 1;

		while ($this->course_model->get_course_list("COUNT", array('id'), array('slug' => $slug)) > 0) {
			$slug = $base_slug . '-' . $slug_counter;
			$slug_counter++;
		}
		/* Ordering */
		$getOrder = $this->course_model->get_max_course(); 
        $order= $getOrder[0]->rank+1;
        $data['rank'] = $order;


		$data['slug'] = $slug;
		$id = $this->course_model->insert_course($data);



		if ($id) {
			/*User Logger info*/

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('add_course') //Entry type like, Post, Page, Entry
			 ->id($id) //Entry ID
			 ->token('CREATE') //Token identify Action
			 ->comment($this->session->userdata('name'). ' create a new course.')
			 ->log(); //Add Database Entry


			$this->session->set_flashdata('course_add_success', 'true');
			$this->session->set_flashdata('course_add_course_id', $id);
			$this->session->set_flashdata('course_add_course_title', $data['title']);
		} else {
			$this->session->set_flashdata('course_add_success', 'false');
		}
		// redirect page were u want to show this massage.
		redirect('courses', 'refresh');
	}

	/*
		    *   Default controller for page not found page
	*/
	public function page_not_found() {
		$this->page_data['page_name'] = 'page_not_found';
		$this->page_data['page_view'] = 'page_not_found';
		$this->page_data['page_title'] = '404 page not found';
		$this->load->view('index', $this->page_data);
	}

	/*
		    *   Default controller for page not found page
	*/
	public function faq($form_name = null) {
		if (!has_role($this->session->userdata('user_id'), 'FAQ_READ')) {
			redirect(base_url('page_not_found'));
		}

		$this->page_data['page_name'] = 'faq';
		$this->page_data['page_view'] = 'faq/faq';
		$this->page_data['page_title'] = 'Frequently Asked Questions';
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
			            <span aria-hidden="true">&times;</span>
			            </button>', '</div>');
		$this->page_data['faq_category'] = $this->crud_model->get_faq_list_by_category(
			"OBJECT",
			array('faq_category' => array("id", "name"))
		);

		for ($i = 0; $i < count($this->page_data['faq_category']); $i++) {
			$cat_id = $this->page_data['faq_category'][$i]->id;
			$this->page_data['faq_category'][$i]->faq_list = $this->crud_model->get_faq_list(
				"OBJECT",
				array('faq' => array("id", "question", 'answer', 'video_id')),
				array('faq_category_id' => $cat_id)
			);

		}

		if ($form_name == "category_form") {
			if ($this->form_validation->run('faq_cat_form') == FALSE) {
				$this->load->view('index', $this->page_data);
			} else {
				$data['id'] = html_escape($this->input->post('faq_cat_id'));
				$data['name'] = html_escape($this->input->post('name'));

				if ($data['id'] == null) {

					if ($this->crud_model->insert_faq_category($data)['success']) {
						/*User Logger info*/

						$this->logger
						 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
						 ->user_details($this->user_model->getUserInfoByIpAddress())
						 ->type('faq_category') //Entry type like, Post, Page, Entry
						 ->id($result['faq_cat_id']) //Entry ID
						 ->token('CREATE') //Token identify Action
						 ->comment($this->session->userdata('name'). ' create new faq category.')
						 ->log(); //Add Database Entry


						$this->session->set_flashdata('faq_update_success', 'Saved Successfully');
					} else {
						$this->session->set_flashdata('faq_update_error', 'Failed to save!');
					}
				} else {
					if ($this->crud_model->update_faq_category($data, $data['id'])['success']) {

						$this->logger
						 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
						 ->user_details($this->user_model->getUserInfoByIpAddress())
						 ->type('faq_category') //Entry type like, Post, Page, Entry
						 ->id($data['id']) //Entry ID
						 ->token('UPDATE') //Token identify Action
						 ->comment($this->session->userdata('name'). ' updated faq category.')
						 ->log(); //Add Database Entry



						$this->session->set_flashdata('faq_update_success', 'Updated Successfully');
					} else {
						$this->session->set_flashdata('faq_update_error', 'Failed to update!');
					}
				}

				redirect(base_url('home/faq'));
			}
		} else if ($form_name == "faq_form") {
			if ($this->form_validation->run('faq_form') == FALSE) {
				$this->load->view('index', $this->page_data);
			} else {
				$this->load->model('service/service_vimeo', 'service_vimeo');
				$data['question'] = html_escape($this->input->post('question'));
				$data['answer'] = html_escape($this->input->post('answer'));
				$data['faq_category_id'] = html_escape($this->input->post('faq_cat_id'));
				$data['video_id'] = html_escape($this->input->post('video_id'));
				$duration_in_second = $this->service_vimeo->get_video_duration($data['video_id']);

				if ($data['duration_in_second']['error']) {
					$this->session->set_flashdata('faq_update_error', $data['duration_in_second']['error']);
				} else {
					if ($this->crud_model->insert_faq($data)['success']) {


							$this->logger
							 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
							 ->user_details($this->user_model->getUserInfoByIpAddress())
							 ->type('faq') //Entry type like, Post, Page, Entry
							 ->id($data['faq_id']) //Entry ID
							 ->token('CREATE') //Token identify Action
							 ->comment($this->session->userdata('name'). ' create new faq.')
							 ->log(); //Add Database Entry


						$this->session->set_flashdata('faq_update_success', 'Saved Successfully');
					} else {
						$this->session->set_flashdata('faq_update_error', 'Failed to save!');
					}
				}

				redirect(base_url('home/faq'));
			}
		} else {
			$this->load->view('index', $this->page_data);
		}

	}

	public function user_chating($sender_id = ''){
        if (!has_role($this->session->userdata('user_id'), 'CHAT_READ')) {
			redirect(base_url('page_not_found'));
		}
		
			$this->page_data['page_name'] = 'user_chating';
    		$this->page_data['page_view'] = 'home/user_chating';
    		$this->page_data['page_title'] = 'User Chating';

		$user_messages = $this->crud_model->get_sender_id($this->session->userdata('user_id'));
		
        /*update the unseen message*/
	 	  $this->crud_model->update_user_message_seen($sender_id, $this->session->userdata('user_id'));
		 
		 
		 
		if(isset($user_messages)){
		    
		    $this->page_data['user_list'] = array();
			foreach ($user_messages as $user) {
				$user_list = $this->crud_model->get_user_chat_info_list(
					"OBJECT", 
					array('user_chat_info' => array('id', 'user_id', 'ip_address', 'message_time'),
						  'user' => array('first_name', 'last_name', 'profile_photo_name')
					),
					array('id ' => $user->sender_id)
				);
				
				 array_push($this->page_data['user_list'], $user_list);
				
			
				 
			}
			

			
			for($i = 0; $i < count($this->page_data['user_list']); $i++){
			     
                $this->page_data['user_list'][$i][0]->message_count = $this->crud_model->get_user_chat_messages(
    	 			'COUNT',
    	 			array('chat_message' => array('sender_id', 'receiver_id', 'chat_messages_text', 'chat_messages_status', 'chat_messages_datetime')),
    	 			array('chat_messages_status' => 0, 'message_seen' => 0, 'sender_id' => $this->page_data['user_list'][$i][0]->id, 'receiver_id' => $this->session->userdata('user_id'))
	 		    );
            }
              
            
            	 /*get total unseen message*/
            	 
	 	     $this->page_data['total_unseen_message'] = $this->crud_model->get_user_chat_messages(
	 			'COUNT',
	 			array('chat_message' => array('sender_id', 'receiver_id', 'chat_messages_text', 'chat_messages_status', 'chat_messages_datetime')),
	 			array('chat_messages_status' => 0, 'message_seen' => 0, 'receiver_id' => $this->session->userdata('user_id'))
	 		);

		}


			 
			 
		if($sender_id != '' || $sender_id != NULL){
			$this->page_data['selected_user'] = $this->crud_model->get_user_chat_info_list(
				"OBJECT", 
				array('user_chat_info' => array('id', 'user_id', 'ip_address'),
					  'user' => array('first_name', 'last_name')
				),
				array('id ' => $sender_id)
			);
			
			

			if(isset($this->page_data['selected_user'][0])){
				$this->page_data['user_messages'] = $this->crud_model->get_user_chat_messages(
				 			'OBJECT',
				 			array('chat_message' => array('sender_id', 'receiver_id', 'chat_messages_text', 'chat_messages_status', 'chat_messages_datetime')),
				 			array('sender_id' => $this->page_data['selected_user'][0]->id, 'receiver_id' => $this->session->userdata('user_id'))
				 		);

			}
			

		}
		
	

		$this->load->view('index', $this->page_data);
	}
	
	public function message_and_email($course_id = ''){
	    if (!has_role($this->session->userdata('user_id'), 'MESSAGE_READ')) {
			redirect(base_url('page_not_found'));
	    }

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
			            <span aria-hidden="true">&times;</span>
			            </button>', '</div>');
           if ($this->form_validation->run('user_message_form') == FALSE) {
	            
    		$this->page_data['page_name'] = 'send_message';
    		$this->page_data['page_view'] = 'home/message_and_email';
    		$this->page_data['page_title'] = 'Send Message';
    		$this->page_data['sub_page_view'] = "send_message";
    		
        		
				
				$this->page_data['user_message_list'] = $this->user_model->get_user_messages(
				        'OBJECT',
				        array('id', 'user_id', 'admin_id', 'message', 'created_at')
				    );
				    
				    $message_user_list = array();
				    
				    for($i = 0; $i < count($this->page_data['user_message_list']); $i++){
				        $this->page_data['user_message_list'][$i]->user_id = json_decode($this->page_data['user_message_list'][$i]->user_id);
				        array_push($message_user_list, $this->page_data['user_message_list'][$i]->user_id);	
        				
				    }

				   
				   

			    $this->page_data['course_list'] = $this->course_model->get_course_list(
			    	"OBJECT",
			    	array('id', 'title'),
			    	array('status' => 1)
		    	);
				$this->page_data['user_list'] = '';
				    if($course_id != ''){
    			    	$this->page_data['course_id'] = $course_id;
		    		 	$this->page_data['user_list'] =  $this->enroll_model->get_enrollment_info_by_course_id(
		    		  		"OBJECT",
		    		  		$course_id,
		    		  		array('user' => array('id as user_id', 'first_name', 'last_name'),
		    	  			  "course" => array('id as course_id', 'title')
		    	  			),
		    		  		array('user_type' => "USER")
		    		   );

    				  

				    }else{
    	        		$this->page_data['user_list'] = $this->user_model->get_user_list(
    						array(
    							'user' => array('id as user_id', 'first_name', 'last_name'),
    						),
    						"OBJECT",
    						array('user_type' => 'USER', 'status' => 1)
    					
    					);
				    }
	        		
		

			    	
				    
					$this->load->view('index', $this->page_data);

				}else{
					    	$user_id = html_escape($this->input->post('user_id'));

					    	$data['message'] = html_escape($this->input->post('message'));
					    	$data['admin_id'] = $this->session->userdata('user_id');
					    	
					    	$data['user_id'] = '["' . implode('","', $user_id) . '"]';
						 	$data['created_at'] = date("Y-m-d H:i:s");   
					        $data['updated_at'] = date("Y-m-d H:i:s"); 

					      
					        
					    	$result = $this->user_model->insert_user_message($data);
					    	
							if ($result) {
								if ($result['success']) {

									$this->logger
									 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
									 ->user_details($this->user_model->getUserInfoByIpAddress())
									 ->type('message_and_email') //Entry type like, Post, Page, Entry
									 ->id($data['id']) //Entry ID
									 ->token('CREATE') //Token identify Action
									 ->comment($this->session->userdata('name'). ' send new meessage.')
									 ->log(); //Add Database Entry


									$this->session->set_flashdata('message_info_update_success', "Message send Successfully");
								} else {
									$this->session->set_flashdata('message_info_update_error', $result['message']);
								}
							} else {
								$this->session->set_flashdata('message_info_update_error', 'Failed to send message!');
							}
							
								redirect(base_url("home/message_and_email"));
				}
	

	}




	
	public function send_email($course_id = ''){
	    if (!has_role($this->session->userdata('user_id'), 'MESSAGE_READ')) {
			redirect(base_url('page_not_found'));
	    }
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
			            <span aria-hidden="true">&times;</span>
			            </button>', '</div>');
        if ($this->form_validation->run('user_email_form') == FALSE) {
            $this->page_data['page_name'] = 'send_email';   
        	$this->page_data['page_view'] = 'home/message_and_email';
    		$this->page_data['page_title'] = 'Send Email';
    		$this->page_data['sub_page_view'] = "send_email";
    		
    	    $this->page_data['course_list'] = $this->course_model->get_course_list(
    	    	"OBJECT",
    	    	array('id', 'title'),
    	    	array('status' => 1)
        	);

        	$this->page_data['user_sending_email_list'] = $this->user_model->get_user_sending_email(
        	        'OBJECT',
        	        array('id', 'user_email', 'admin_id', 'subject', 'body',  'created_at')
        	    );


			$this->page_data['user_list'] = '';
			    if($course_id != ''){
			    	$this->page_data['course_id'] = $course_id;
	    		 	$this->page_data['user_list'] =  $this->enroll_model->get_enrollment_info_by_course_id(
	    		  		"OBJECT",
	    		  		$course_id,
	    		  		array('user' => array('id as user_id', 'first_name', 'last_name', 'email'),
	    	  			  "course" => array('id as course_id', 'title')
	    	  			),
	    		  		array('user_type' => "USER")
	    		   );

				  

			    }else{
	        		$this->page_data['user_list'] = $this->user_model->get_user_list(
						array(
							'user' => array('id', 'first_name', 'last_name', 'email'),
						),
						"OBJECT",
						array('user_type' => 'USER', 'status' => 1)
					
					);
			    }
    		
    		$this->load->view('index', $this->page_data);
    	}else{

	    	$email = html_escape($this->input->post('user_email'));
	    	$data['subject'] = html_escape($this->input->post('subject'));
	    	$data['body'] = html_escape($this->input->post('body'));
	    	$data['admin_id'] = $this->session->userdata('user_id');
	    	
	    	$data['user_email'] = '["' . implode('","', $email) . '"]';
		 	$data['created_at'] = date("Y-m-d H:i:s");   
	        $data['updated_at'] = date("Y-m-d H:i:s"); 


	        
	    	$result = $this->user_model->insert_email_send_to_user($data);
	    	$this->page_data['mail_data'] = $data;
	    	
	    	$user_email = implode(",",$email);
	    	

	    	


			if ($result) {
				if ($result['success']) {

					$email_msg = $this->load->view('template/admin_send_mail', $this->page_data, true);
			    	$done = $this->email_model->send_email_to_system( $this->page_data['mail_data']['subject'],  $user_email, $email_msg);


			    	if ($done == '1') {

			    		$this->logger
			    		 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			    		 ->user_details($this->user_model->getUserInfoByIpAddress())
			    		 ->type('send_email') //Entry type like, Post, Page, Entry
			    		 ->id($data['id']) //Entry ID
			    		 ->token('CREATE') //Token identify Action
			    		 ->comment($this->session->userdata('name'). ' send new email.')
			    		 ->log(); //Add Database Entry



			    		$this->session->set_flashdata('email_info_update_success', "Email send Successfully!!");
			    	} else {
			    		$this->session->set_flashdata('email_info_update_failed', "Failed to send email! Please check it is an real email");
			    	}


				} else {
					$this->session->set_flashdata('email_info_update_failed', 'Failed to insert data! Contact with developer team.');
				}
			} else {
				$this->session->set_flashdata('email_info_update_failed', 'Failed to insert data!');
			}
			
				redirect(base_url("home/send_email"));
    	}
	}

	// public function admin_logger(){
	// 	debug('hello');
	// }

		public function admin_logger($offset = 0) {

			if (!has_role($this->session->userdata('user_id'), 'ADMIN_LOG_READ')) {
				redirect(base_url('page_not_found'));
			}




				$this->page_data['page_name'] = "admin_log";
				$this->page_data['page_title'] = "Admin Log";
				$this->page_data['page_view'] = "admin_log/index";
				$pagination_config['per_page'] = 10;
				$pagination_config['total_rows'] = 100;

				$filter_array = array();
				
			
				
				if (isset($_GET['user'])) {
					$filter_array['created_by'] = $_GET['user'];
				}


				$this->page_data['user_list'] = $this->user_model->get_user_list(
					array(
						'user' => array('id', 'first_name', 'last_name'),
					),
					"OBJECT",
					 array('user_type' => 'ADMIN', 'status' => '1')
				);



					$pagination_config['total_rows'] = $this->user_model->get_admin_log_data_list(
						array(
							'admin_logger' => array('id'),
						),
						"COUNT",
						$filter_array,
						NULL,
						NULL,
						$_GET['start_date'],
						$_GET['end_date']
						// $_GET['user_search_type']

					);
				
					$this->page_data['admin_log_list'] = $this->user_model->get_admin_log_data_list(
						array(
							'admin_logger' => array('id', 'created_by', 'user_details', 'type', 'token', 'type_id', 'comment', 'created_on'),
							'user' => array('first_name', 'last_name')
						),
						"OBJECT",
						$filter_array,
						null,
						array('limit' => $pagination_config['per_page'], 'offset' => $offset),
						$_GET['start_date'],
						$_GET['end_date']
						// $_GET['user_search_type']
					);
					
				
				$this->page_data['page_limit'] = $pagination_config['per_page'];
				$this->page_data['number_of_total_log_data'] = $pagination_config['total_rows'];
				$this->page_data['offset'] = $offset;

				$pagination_config['base_url'] = site_url('home/admin_logger');
				$pagination_config['num_links'] = 3;
				$pagination_config['reuse_query_string'] = TRUE;
				$pagination_config['full_tag_open'] = '<div class="pagination-bar">';
				$pagination_config['full_tag_close'] = '</div>';
				$pagination_config['attributes'] = array('class' => 'pagination-bar-node');
				$pagination_config['first_link'] = 'First page';
				$pagination_config['last_link'] = 'Last page';
				$pagination_config['cur_tag_open'] = '<span class="pagination-bar-node-active">';
				$pagination_config['cur_tag_close'] = '</span>';

				$this->pagination->initialize($pagination_config);
				$this->load->view('index', $this->page_data);
				
		
		}

		/* Service */

		/*Remove admin log*/

		public function remove_admin_log($log_id){

			$admin_log = $this->user_model->remove_admin_log($log_id);

			if ($admin_log) {

				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('remove_admin_log') //Entry type like, Post, Page, Entry
				 ->id($admin_log) //Entry ID
				 ->token('DELETE') //Token identify Action
				 ->comment($this->session->userdata('name'). '  removed  admin log.')
				 ->log(); //Add Database Entry

				$this->session->set_flashdata('success', 'Removed user log successfully.');
			} else {
				$this->session->set_flashdata('danger', 'Failed to remove user log!');
			}

			redirect(site_url('home/admin_logger'));
		}


		/*
		    * Controller - Get all currency
	*/

	public function currency() {
		
		if (!has_role($this->session->userdata('user_id'), 'CURRENCY_READ')) {
			redirect(base_url('page_not_found'));
		}
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
			            <span aria-hidden="true">&times;</span>
			            </button>', '</div>');
		if ($this->form_validation->run('currency_form') == FALSE) {

			$this->page_data['page_name'] = "currency";
			$this->page_data['page_title'] = "Currency";
			$this->page_data['page_view'] = "currency/index";
		
			$this->page_data['currency_list'] = $this->course_model->get_currency_list(
				"OBJECT",
				array(
					'currencies' => array('id', 'name', 'sign', 'value')
				)
			);
		
		
			$this->load->view('index', $this->page_data);
		} else {
			
			// $this->service_user->save_currency();

			$currency_id = html_escape($this->input->post('currency_id'));
			$data['name'] = html_escape($this->input->post('name'));
			$data['sign'] = html_escape($this->input->post('sign'));
			$data['value'] = html_escape($this->input->post('value'));


			if ($currency_id) {
				
				
				if ($this->course_model->update_currency($currency_id, $data)['success']) {
					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('save_currency') //Entry type like, Post, Page, Entry
					 ->id($currency_id) //Entry ID
					 ->token('UPDATE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' updated currency info.')
					 ->log(); //Add Database Entry


					$this->session->set_flashdata('currency_update_success', 'Saved Successfully currency info updated.');
				} else {
					$this->session->set_flashdata('currency_update_error', 'Failed to updated!');
				}
			} else {
				
			
					if ($this->course_model->insert_currency($data)['success']) {
						/*User Logger info*/

						$this->logger
						 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
						 ->user_details($this->user_model->getUserInfoByIpAddress())
						 ->type('save_currency') //Entry type like, Post, Page, Entry
						 ->id($result['id']) //Entry ID
						 ->token('CREATE') //Token identify Action
						 ->comment($this->session->userdata('name'). ' create new currency.')
						 ->log(); //Add Database Entry



						$this->session->set_flashdata('currency_update_success', 'Saved Successfully new currency');
					} else {
						$this->session->set_flashdata('currency_update_error', 'Failed to save!');
					}
				// }
			}

			redirect(base_url('home/currency/'));
		}

	

	}


	/* Service */

	/*Remove curreny*/

	public function remove_currency($currency_id){

		$currency = $this->user_model->remove_currency($currency_id);

		if ($currency) {

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('remove_currency') //Entry type like, Post, Page, Entry
			 ->id($currency_id) //Entry ID
			 ->token('DELETE') //Token identify Action
			 ->comment($this->session->userdata('name'). '  removed  currency.')
			 ->log(); //Add Database Entry

			$this->session->set_flashdata('currency_update_success', 'Removed currency successfully.');
		} else {
			$this->session->set_flashdata('currency_update_error', 'Failed to remove currency!');
		}

		redirect(site_url('home/currency'));
	}


	public function membership($offset = 0) {

	
		// if (!has_role($this->session->userdata('user_id'), 'MEMBERSHIP_READ')) {
		// 	redirect(base_url('page_not_found'));
		// }



		$this->page_data['page_name'] = "membership";
		$this->page_data['page_title'] = "Membership";
		$this->page_data['page_view'] = "membership/index";
		$pagination_config['per_page'] = 10;
		$pagination_config['total_rows'] = 100;
		

		$filter_array = array();
		
	

		if (isset($_GET['membership'])) {
			$filter_array['membership_type'] = $_GET['membership'];


			if($_GET['membership'] == 'all'){
				redirect('home/membership');
			}
		}

		if (isset($_GET['badge'])) {
			$filter_array['membership_badge_id'] = $_GET['badge'];


			if($_GET['bdage'] == 'all'){
				redirect('home/membership');
			}
		}

		


		$this->page_data['membership_types'] = $this->user_model->get_membership_data(
			array(
				'membership_payment' => array('id', 'membership_type', 'notes'),
			),
			"OBJECT"
		);

		
		
		$pagination_config['total_rows'] = $this->user_model->get_membership_data(
			array(
				'membership' => array('id'),
			),
			"COUNT",
			$filter_array,
			NULL,
			NULL,
			$_GET['start_date'],
			$_GET['end_date']
			// $_GET['user_search_type']

		);
	
		$this->page_data['membership_list'] = $this->user_model->get_membership_data(
			array(
				'membership' => array('name', 'email', 'phone'),
				'membership_payment' => array('id', 'membership_type', 'notes', 'membership_badge_id', 'membership_id'),
				'user' => array('first_name', 'email as user_email', 'phone as user_phone')
				
			),
			"OBJECT",
			$filter_array,
			null,
			array('limit' => $pagination_config['per_page'], 'offset' => $offset),
			$_GET['start_date'],
			$_GET['end_date']
			// $_GET['user_search_type']
		);
			// debug($this->page_data['membership_list']);
			// exit;
		// debug('test');
		// exit;
		$this->page_data['page_limit'] = $pagination_config['per_page'];
		$this->page_data['number_of_total_membership_data'] = $pagination_config['total_rows'];
		$this->page_data['offset'] = $offset;

		$pagination_config['base_url'] = site_url('home/membership');
		$pagination_config['num_links'] = 3;
		$pagination_config['reuse_query_string'] = TRUE;
		$pagination_config['full_tag_open'] = '<div class="pagination-bar">';
		$pagination_config['full_tag_close'] = '</div>';
		$pagination_config['attributes'] = array('class' => 'pagination-bar-node');
		$pagination_config['first_link'] = 'First page';
		$pagination_config['last_link'] = 'Last page';
		$pagination_config['cur_tag_open'] = '<span class="pagination-bar-node-active">';
		$pagination_config['cur_tag_close'] = '</span>';

		$this->pagination->initialize($pagination_config);
		$this->load->view('index', $this->page_data);
			
	
	}

	public function add_membership(){
		if (!has_role($this->session->userdata('user_id'), 'MEMBERSHIP_CREATE')) {
			redirect(base_url('page_not_found'));
		}

		/*For membership table*/
		$member['name'] = html_escape($this->input->post('name'));
		$member['email'] = html_escape($this->input->post('email'));
		$member['phone'] = html_escape($this->input->post('phone'));
		
		$member_id = html_escape($this->input->post('member_id'));

		if($member_id == null){
			$member_data = $this->user_model->insert_and_update_membership($member_id , $member);

			if($member_data['success']){


				/*For membership payment table*/
				$membership_payment['payment_method'] = 'Manually';
				$membership_payment['status'] = 'ACCEPTED';
				$membership_payment['amount'] = html_escape($this->input->post('amount'));
				$membership_payment['membership_type'] = html_escape($this->input->post('membership_type'));
				$membership_payment['membership_id'] = $member_data['id'];
				$membership_payment['notes'] = html_escape($this->input->post('notes'));

				$member_payment_data = $this->user_model->insert_and_update_membership_payment($member_id, $membership_payment);

				if($member_data['id']){


					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('add_membership') //Entry type like, Post, Page, Entry
					 ->id($member_data['id']) //Entry ID
					 ->token('INSERT') //Token identify Action
					 ->comment($this->session->userdata('name'). ' add new member.')
					 ->log(); //Add Database Entry

					$this->session->set_flashdata('membership_update_success', 'Added new member successfully.');
				}else{
					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('add_membership') //Entry type like, Post, Page, Entry
					 ->id($member_data['id']) //Entry ID
					 ->token('FAILED') //Token identify Action
					 ->comment($this->session->userdata('name'). ' failed to add new member.')
					 ->log(); //Add Database Entry

					$this->session->set_flashdata('membership_update_error', 'Failed to save.');
				}

			}else{
				$this->session->set_flashdata('membership_update_error', 'Failed to save member.');
			}
		}else{

			/*Update Member info */

			$update_member_data = $this->user_model->insert_and_update_membership($member_id, $member);

			if($update_member_data['success']){


				/*For membership payment table*/
				
				$membership_payment['amount'] = html_escape($this->input->post('amount'));
				$membership_payment['membership_type'] = html_escape($this->input->post('membership_type'));
				$membership_payment['notes'] = html_escape($this->input->post('notes'));
				// $membership_payment['membership_id'] = $member_id;


				$member_payment_data = $this->user_model->insert_and_update_membership_payment($member_id, $membership_payment);

				if($member_payment_data){


					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('update_membership') //Entry type like, Post, Page, Entry
					 ->id($member_id) //Entry ID
					 ->token('UPDATE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' updated member information.')
					 ->log(); //Add Database Entry

					$this->session->set_flashdata('membership_update_success', 'Successfully updated member information.');
				}else{
					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('update_membership') //Entry type like, Post, Page, Entry
					 ->id($member_id) //Entry ID
					 ->token('FAILED') //Token identify Action
					 ->comment($this->session->userdata('name'). ' failed to update member.')
					 ->log(); //Add Database Entry

					$this->session->set_flashdata('membership_update_error', 'Failed to update.');
				}

			}else{

			}
				
		}
		


		
		redirect('home/membership');

	}

	/* Service */

	/*Remove curreny*/

	public function remove_member(){

		if(isset($_GET['member_id'])){
			$member = $this->user_model->remove_member($_GET['member_id'], 'new_membership');
		}else{
			$member = $this->user_model->remove_member($_GET['id'], 'existing_user');
		}

	
		if ($member) {

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('remove_member') //Entry type like, Post, Page, Entry
			 ->id($member_id) //Entry ID
			 ->token('DELETE') //Token identify Action
			 ->comment($this->session->userdata('name'). '  removed a member.')
			 ->log(); //Add Database Entry

			$this->session->set_flashdata('membership_update_success', 'Removed member successfully.');
		} else {
			$this->session->set_flashdata('membership_update_error', 'Failed to remove member!');
		}

		redirect(site_url('home/membership'));
	}


	public function send_memberhsip_email($member_id){

		$existing_badge_id = $this->user_model->get_membership_data(
			array(
				'membership' => array( 'name', 'email', 'phone'),
				'membership_payment' => array('id', 'membership_type', 'notes', 'membership_badge_id'),
				'user' => array('first_name', 'email as user_email', 'phone as user_phone')
			),
			"OBJECT",
			array('membership_payment.id' => $member_id)
		)[0];
		
		if($existing_badge_id->membership_badge_id != NULL){
			$this->session->set_flashdata('membership_update_error', 'Email Already Sent');
		}else{
			$badge_id = $this->user_model->get_membership_payment_specific_data()->membership_badge_id;

			$unique_badge_id = (intval(substr($badge_id, -6))+1);
			$current_date = date('YmdHi');
			$new_badge_id =  intval($current_date . '000000') +  intval($unique_badge_id);
			 
			$data['badge_id'] = $new_badge_id;
			$badge_data['membership_badge_id'] = $new_badge_id;

			if($existing_badge_id->name == null){
				$data['name'] = $existing_badge_id->first_name;
			}else{
				$data['name'] = $existing_badge_id->name;
			}

			if($existing_badge_id->email == null){
				$data['email'] = $existing_badge_id->user_email;
			}else{
				$data['email'] = $existing_badge_id->email;
			}
			
			
			$member_payment_data = $this->user_model->insert_into_membership_payment($member_id, $badge_data);
			
			if($member_payment_data){
				$this->page_data['data'] = $data;
				$email_msg = $this->load->view('template/membership_email', $this->page_data, true);
				$membership_email = $this->email_model->send_membership_mail($data['email'], $email_msg);
				if($membership_email){
					


					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('update_membership_email_id') //Entry type like, Post, Page, Entry
					 ->id($member_id) //Entry ID
					 ->token('UPDATE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' updated member badge Id.')
					 ->log(); //Add Database Entry



					$this->session->set_flashdata('membership_update_success', 'Email Send successfully.');
				}else{
					$this->session->set_flashdata('membership_update_error', 'Something went wrong.Contact to developer');
				}
			}else{
				$this->session->set_flashdata('membership_update_error', 'Something wrong.Contact to developer');
			}
			
		}

		
		redirect(site_url('home/membership'));
		


	}





}
