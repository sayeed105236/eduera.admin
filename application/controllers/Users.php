<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Users extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->model('service/service_user', 'service_user');

		if ($this->session->userdata('user_type') !== "SUPER_ADMIN" && $this->session->userdata('user_type') !== "ADMIN") {
			redirect(site_url('login'), 'refresh');
		}
		$this->load->library('logger');
		$this->page_data['system_name'] = get_settings('system_name');
		$this->pagination_config = array();
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
		    *   Controller for users
	*/
	public function index($offset = 0) {

		if (!has_role($this->session->userdata('user_id'), 'USER_READ')) {
			redirect(base_url('page_not_found'));
		}

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>', '</div>');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		if ($this->form_validation->run('user_info_form') == FALSE) {

			$this->page_data['page_name'] = "users";
			$this->page_data['page_title'] = "Users";
			$this->page_data['page_view'] = "users";
			$pagination_config['per_page'] = 10;
			$pagination_config['total_rows'] = 100;

			$filter_array = array();

			if (isset($_GET['user_status'])) {
				$filter_array['status'] = $_GET['user_status'];
			}

			if (isset($_GET['course_id'])) {
				$filter_array['course_id'] = $_GET['course_id'];
			}

			if (isset($_GET['user_type'])) {
				$filter_array['user_type'] = $_GET['user_type'];
			}

			if (isset($_GET['search_text'])) {

				$pagination_config['total_rows'] = $this->user_model->get_user_list(
					array(
						'user' => array('id'),
					),
					"COUNT",
					$filter_array,
					$_GET['search_text'],
					$_GET['start_date'],
					$_GET['end_date'],
					$_GET['user_search_type']
				);

				$this->page_data['user_list'] = $this->user_model->get_user_list(
					array(
						'user' => array('id', 'first_name', 'last_name', 'phone', 'email', 'status', 'user_type', 'created_at'),
					),
					"OBJECT",
					$filter_array,
					$_GET['search_text'],
					array('limit' => $pagination_config['per_page'], 'offset' => $offset),
					$_GET['start_date'],
					$_GET['end_date'],
					$_GET['user_search_type']
				);


				$this->page_data['excel_user_list'] = $this->user_model->get_user_list(
					array(
						'user' => array('id', 'first_name', 'last_name', 'phone', 'email', 'status', 'user_type', 'created_at'),
					),
					"OBJECT",
					$filter_array,
					$_GET['search_text'],
					null,
					$_GET['start_date'],
					$_GET['end_date'],
					$_GET['user_search_type']
				);

			} else {
				
					
				if(isset($_GET['course_id'])){
					$payment_status = null;
					if(isset($_GET['payment_status'])){
						
						$payment_status = $_GET['payment_status'];
					}

					$pagination_config['total_rows'] = $this->user_model->get_user_list(
						array(
							'user' => array('id'),
						),
						"COUNT",
						$filter_array,
						NULL,
						NULL,
						$_GET['start_date'],
						$_GET['end_date'],
						"Enrollment",
						$_GET['course_id'],
						$payment_status
					);

					


					$this->page_data['user_list'] = $this->user_model->get_user_list(
						array(
							'user' => array('id', 'first_name', 'last_name', 'phone', 'email', 'status', 'user_type', 'created_at'),
						),
						"OBJECT",
						$filter_array,
						null,
						array('limit' => $pagination_config['per_page'], 'offset' => $offset),
						$_GET['start_date'],
						$_GET['end_date'],
						"Enrollment",
						$_GET['course_id'],
						$payment_status
					);

					$this->page_data['excel_user_list'] = $this->user_model->get_user_list(
						array(
							'user' => array('id', 'first_name', 'last_name', 'phone', 'email', 'status', 'user_type', 'created_at'),
						),
						"OBJECT",
						$filter_array,
						null,
						null,
						$_GET['start_date'],
						$_GET['end_date'],
						"Enrollment",
						$_GET['course_id'],
						$payment_status
					);


				}else{

					$pagination_config['total_rows'] = $this->user_model->get_user_list(
					array(
						'user' => array('id'),
					),
					"COUNT",
					$filter_array,
					NULL,
					NULL,
					$_GET['start_date'],
					$_GET['end_date'],
					$_GET['user_search_type']

				);
				$this->page_data['user_list'] = $this->user_model->get_user_list(
						array(
							'user' => array('id', 'first_name', 'last_name', 'phone', 'email', 'status', 'user_type', 'created_at'),
						),
						"OBJECT",
						$filter_array,
						null,
						array('limit' => $pagination_config['per_page'], 'offset' => $offset),
						$_GET['start_date'],
						$_GET['end_date'],
						$_GET['user_search_type']
						// $_GET['course_id']
					);

				$this->page_data['excel_user_list'] = $this->user_model->get_user_list(
						array(
							'user' => array('id', 'first_name', 'last_name', 'phone', 'email', 'status', 'user_type', 'created_at'),
						),
						"OBJECT",
						$filter_array,
						null,
						null,
						$_GET['start_date'],
						$_GET['end_date'],
						$_GET['user_search_type']
						// $_GET['course_id']
					);
				}
				

				$this->page_data['course_list'] = $this->course_model->get_course_list(
					"OBJECT",
					array('id', 'slug', 'title')
				);

				// debug($this->page_data['course_list']);

			}

			for ($i = 0; $i < count($this->page_data['user_list']); $i++) {
				$this->page_data['user_list'][$i]->enroll_history = $this->enroll_model->get_enrollment_info_by_user_id("OBJECT", $this->page_data['user_list'][$i]->id, array(
					"enrollment" => array('id as enroll_id', 'enrolled_price', 'access', 'created_at', 'user_id', 'course_id'),
					"course" => array('id', 'title'),
				), null, null, "SUM");
			}

			if(isset($_GET['download']) && $_GET['download'] == 'yes'){
						$fileName = 'users.xlsx';  
						$spreadsheet = new Spreadsheet();

						$sheet = $spreadsheet->getActiveSheet();
				       	$sheet->setCellValue('A1', '#');
				        $sheet->setCellValue('B1', 'Name');
				        $sheet->setCellValue('C1', 'Email');
				        $sheet->setCellValue('D1', 'Phone');
						$sheet->setCellValue('E1', 'User Type');
				        $sheet->setCellValue('F1', 'Registration Date');       
				        $sheet->setCellValue('G1', 'Status');       
				        $sheet->setCellValue('H1', 'Course');       
				        $rows = 2;
				       
				     



				        foreach ($this->page_data['excel_user_list'] as $index => $val){

				        	$this->page_data['excel_user_list'][$i]->enroll_history = $this->enroll_model->get_enrollment_info_by_user_id("OBJECT", $this->page_data['excel_user_list'][$index]->id, array(
				        		"enrollment" => array('id as enroll_id', 'enrolled_price', 'access', 'created_at', 'user_id', 'course_id'),
				        		"course" => array('id', 'title'),
				        	), null, null, "SUM");
				        	$title = array('name' => array());
				        	$final['name'] = array();
				        	foreach($this->page_data['excel_user_list'][$i]->enroll_history as $enroll){
				        		$title['name'] = array(
				        			'title' => $enroll->title, 'enrolled_price' => $enroll->enrolled_price, 'paid_amount' => $enroll->paid_amount
				        		);
				        		$final['name'] = array_merge($final['name'], $title['name']);
				        	}

				            $sheet->setCellValue('A' . $rows, $index+1);
				            $sheet->setCellValue('B' . $rows, $val->first_name . ' ' . $val->last_name);
				            $sheet->setCellValue('C' . $rows, $val->email);
				            $sheet->setCellValue('D' . $rows, $val->phone);
					        $sheet->setCellValue('E' . $rows, $val->user_type);
				            $sheet->setCellValue('F' . $rows, (date_format(date_create($val->created_at), 'd/m/Y')));
				            $sheet->setCellValue('G' . $rows, ($val->status == 1) ? 'Active' : 'Inactive');
				            $sheet->setCellValue('H' . $rows, '["' . implode('","', $final['name']) . '"]');
				            $rows++;
				        } 

				        $writer = new Xlsx($spreadsheet);
						$writer->save("uploads/".$fileName);
						header("Content-Type: application/vnd.ms-excel");
					    redirect(base_url()."uploads/".$fileName); 
			}

			$this->page_data['page_limit'] = $pagination_config['per_page'];
			$this->page_data['number_of_total_users'] = $pagination_config['total_rows'];
			$this->page_data['offset'] = $offset;

			$pagination_config['base_url'] = site_url('users');
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

		} else {
			if (!has_role($this->session->userdata('user_id'), 'USER_CREATE')) {
				redirect(base_url('page_not_found'));
			} else {
				$this->service_user->save_user("CREATE_USER");
			}
		}
	}

	
	/*
		    *   Controller to edit user data
	*/
	public function info($id = null) {

		
		if(isset($_GET['notify_id'])){
			$this->user_model->update_user_notification($_GET['notify_id']);
		}
		

		if (!has_role($this->session->userdata('user_id'), 'USER_READ') && !has_role($this->session->userdata('user_id'), 'USER_UPDATE')) {
			redirect(base_url('page_not_found'));
		}

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>', '</div>');

		if ($this->form_validation->run('user_info_form') == FALSE) {
			if ($id == '' || $id == null) {
				redirect(base_url("home/page_not_found"));
			}
			$this->page_data['page_name'] = "user_info";
			$this->page_data['page_title'] = "User Info";
			$this->page_data['page_view'] = "user/index";
			$this->page_data['user_data'] = $this->user_model->get_user_by_id($id, array('id', 'first_name', 'last_name', 'email', 'phone', 'status', 'biography', 'user_type', 'instructor'));
			$this->page_data['membership'] = $this->user_model->get_membership_payment_data(
				"OBJECT",
				array('id', 'membership_type'),
				array('user_id' => $id)
			)[0];

			

			$this->page_data['sub_page_view'] = "user_info";
			$this->load->view('index', $this->page_data);
		} else {
			$this->service_user->save_user("UPDATE_USER", $id);

			
		}

	}

	public function user_role($user_id = null) {

		if ($this->session->userdata('user_type') !== "SUPER_ADMIN") {
			redirect(base_url('page_not_found'));
		}

		if ($user_id == '' || $user_id == null) {
			redirect(base_url("home/page_not_found"));
		}

		$this->page_data['user_roles'] = array();
		$this->page_data['page_name'] = "user_role";
		$this->page_data['page_title'] = "User Role";
		$this->page_data['page_view'] = "user/index";
		$user_roles = $this->user_model->get_user_role();
		foreach ($user_roles as $user_role) {

			if (!array_key_exists($user_role->role_category, $this->page_data['user_roles'])) {
				$this->page_data['user_roles'][$user_role->role_category] = array();
			}
			array_push($this->page_data['user_roles'][$user_role->role_category], $user_role->name);
		}

		$this->page_data['user_data'] = $this->user_model->get_user_by_id($user_id, array('id', 'first_name', 'last_name', 'email', 'phone', 'instructor', 'role_list'));

		$this->page_data['user_data']->role_list = json_decode($this->page_data['user_data']->role_list);

		$this->page_data['sub_page_view'] = "user_role";
		$this->load->view('index', $this->page_data);

	}

	/*
		    * Controller - user enrollment
	*/
	public function user_enrollment($user_id = null) {
		if ($user_id == '' || $user_id == null) {
			redirect(base_url("home/page_not_found"));
		}

		if (!has_role($this->session->userdata('user_id'), 'USER_READ')) {
			redirect(base_url('page_not_found'));
		}

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>', '</div>');

		if ($this->form_validation->run('enroll_user_form') == FALSE) {

			$this->page_data['page_name'] = "user_enrollment";
			$this->page_data['page_title'] = "User enrollment";
			$this->page_data['page_view'] = "user/index";
			$this->page_data['user_data'] = $this->user_model->get_user_by_id($user_id, array('id', 'first_name', 'last_name', 'email', 'phone', 'instructor'));
			$this->page_data['course_list'] = $this->course_model->get_course_list("OBJECT", array('id', 'title', 'price', 'discount_flag', 'discounted_price'));
			// $this->page_data['enroll_history'] = $this->enroll_model->get_user_enroll_history($user_id);

			// Retrieve enrollment history along with few course data
			$this->page_data['enroll_history'] = $this->enroll_model->get_enrollment_info_by_user_id("OBJECT", $user_id, array(
				"enrollment" => array('id as enroll_id', 'enrolled_price', 'access', 'created_at', 'user_id', 'course_id', 'expiry_date'),
				"course" => array('id', 'title'),
			), null, null, "SUM");

			

			$this->page_data['sub_page_view'] = "user_enrollment";
			$this->load->view('index', $this->page_data);
		} else {
			if (!has_role($this->session->userdata('user_id'), 'USER_ENROLL')) {
				redirect(base_url('page_not_found'));
			} else {
				$this->service_user->enroll_in_a_course($user_id);
			}
		}
	}

	/*
		    * Controller - Save instructor enroll in a course
	*/
	public function instructor_enrollment($instructor_id = null) {

		if ($instructor_id == '' || $instructor_id == null) {
			redirect(base_url("home/page_not_found"));
		}

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>', '</div>');

		if ($this->form_validation->run('assign_instructor_form') == FALSE) {

			$this->page_data['user_data'] = $this->user_model->get_user_by_id($instructor_id, array('id', 'first_name', 'last_name', 'email', 'phone', 'instructor'));

			if ($this->page_data['user_data']->instructor != 1) {
				redirect(base_url("home/page_not_found"));
			}

			$this->page_data['page_name'] = "instructor_enrollment";
			$this->page_data['page_title'] = "Instructor enrollment";
			$this->page_data['page_view'] = "user/index";
			$this->page_data['course_list'] = $this->course_model->get_course_list("OBJECT", array('id', 'title', 'price', 'discounted_price'));
			$this->page_data['enroll_history'] = $this->enroll_model->get_instructor_enroll_history($instructor_id, array('id', 'title', 'instructor_share'));
			$this->page_data['sub_page_view'] = "instructor_enrollment";
			$this->load->view('index', $this->page_data);
		} else {
			$this->service_user->assign_an_instructor_in_a_course($instructor_id);
		}

	}

	/*
		    * Controller - User Enroll Course Payment Functionality
	*/
	public function user_enroll_make_payment($user_id = null) {

		if ($user_id == '' || $user_id == null) {
			redirect(base_url("home/page_not_found"));
		}

		if (!has_role($this->session->userdata('user_id'), 'USER_ENROLL')) {
			redirect(base_url('page_not_found'));
		}

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>', '</div>');

		if ($this->form_validation->run('user_course_payemnt_form') == FALSE) {

			$this->user_enrollment($user_id);
		} else {
			$this->service_user->user_enroll_make_payment($user_id);
		}
	}

	/*
		    * Controller - Save instructor enroll in a course
	*/
	public function status_monitoring($user_id = null) {
		$course_id = $this->input->post('course_id');
		$this->page_data['user_data'] = $this->user_model->get_user_by_id($user_id, array('id', 'first_name', 'last_name', 'email', 'phone', 'instructor'));

		$this->page_data['page_name'] = "status_monitoring";
		$this->page_data['page_title'] = "Status monitoring";
		$this->page_data['page_view'] = "user/index";

		$this->page_data['course_list'] = $this->enroll_model->get_enrollment_info_by_user_id(
			"OBJECT",
			$user_id,
			array(
				"course" => array('id', 'title'),
			)
		);

		if ($course_id) {
			$this->page_data['lesson_list'] = $this->lesson_model->get_lesson_list_by_users_status(
				$user_id,
				'OBJECT',
				array(
					'lesson' => array('id', 'title', 'duration_in_second'),
					'user_lesson_status' => array('finished_time', 'updated_at'),
				),
				array('course_id' => $course_id)
			);
			$this->page_data['total_course_duration'] = 0;
			$this->page_data['total_user_lesson_duration'] = 0;
			foreach ($this->page_data['lesson_list'] as $lesson) {
				$this->page_data['total_course_duration'] += $lesson->duration_in_second;
				$this->page_data['total_user_lesson_duration'] += $lesson->finished_time;
			}

		}

		$this->page_data['sub_page_view'] = "status_monitoring";
		$this->load->view('index', $this->page_data);

	}

		/*
			    * Controller - Save instructor payment
		*/
		public function instructor_payment($instructor_id = null) {
		

			if ($instructor_id == '' || $instructor_id == null) {
				redirect(base_url("home/page_not_found"));
			}

			$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	            </button>', '</div>');

			if ($this->form_validation->run('instructor_payment_form') == FALSE) {

				 $this->page_data['user_data'] = $this->user_model->get_user_by_id($instructor_id, array('id', 'first_name', 'last_name', 'email', 'phone', 'instructor'));

				if ($this->page_data['user_data']->instructor != 1) {
					redirect(base_url("home/page_not_found"));
				}

				$this->page_data['page_name'] = "instructor_payment";
				$this->page_data['page_title'] = "Instructor payment";
				$this->page_data['page_view'] = "user/index";

				$filter_array['instructor_id'] = $instructor_id;
				$total_profit = $this->user_model->get_instructor_payment(
					'all',
					array('id', 'title'),
					"OBJECT",
					$filter_array,
					"total_sell"
					
				);

				// debug($total_profit);

				$this->page_data['total_profit'] = 0;
				foreach ($total_profit as $key => $profit) {
					$this->page_data['total_profit'] += $profit->total_profit; 
				}
				
				/* get instructor  withdraw amount details */
				

				$this->page_data['withdraw_amount'] = $this->user_model->get_instructor_payment_withdraw_details(
					"OBJECT",
					array('id', 'instructor_id', 'withdraw_amount', 'payment_left', 'created_at'),
					array('instructor_id' => $instructor_id)
				);

				/* get instructor total withdraw amount*/

				$this->page_data['total_withdraw_amount'] = $this->user_model->get_instructor_total_withdraw_amount(
					"OBJECT",
					array('SUM(withdraw_amount) as "total_withdraw_amount"'),
					array('instructor_id' => $instructor_id)
				);

				// $this->page_data['course_list'] = $this->course_model->get_course_list("OBJECT", array('id', 'title', 'price', 'discounted_price'));

				// $this->page_data['enroll_history'] = $this->enroll_model->get_instructor_enroll_history($instructor_id, array('id', 'title', 'instructor_share'));
				
				$this->page_data['sub_page_view'] = "instructor_payment";

				$this->load->view('index', $this->page_data);
			} else {
				$this->service_user->instructor_payment($instructor_id);
			}

		}

		/*
			    * Controller - Get User Certificate
		*/
		public function user_certificate($user_id = null) {
			
			if ($user_id == '' || $user_id == null) {
				redirect(base_url("home/page_not_found"));
			}

			if (!has_role($this->session->userdata('user_id'), 'USER_CERTIFICATE')) {
				redirect(base_url('page_not_found'));
			}



			$this->page_data['user_data'] = $this->user_model->get_user_by_id($user_id, array('id', 'first_name', 'last_name', 'email', 'phone', 'instructor'));

		
			$this->page_data['enroll_history'] = $this->enroll_model->get_enrollment_info_by_user_id("OBJECT", $user_id, array(
				"enrollment" => array('id as enroll_id', 'enrolled_price', 'access', 'created_at', 'user_id', 'course_id', 'expiry_date'),
				"course" => array('id', 'title'),
			), null, null, "SUM");

				$this->page_data['page_name'] = "user_certificate";
				$this->page_data['page_title'] = "Certificate";
				$this->page_data['page_view'] = "user/index";


				if($_POST['course_id']){
					$this->page_data['certificate_info'] = $this->crud_model->getCertificateSerialNo($_POST['course_id'], $user_id)[0];
					// debug($this->page_data['certificate_info']);

					$this->page_data['course_info'] = $this->course_model->get_course_list("OBJECT", array('id', 'title', 'certificate'), array('id' => $_POST['course_id']))[0];

					// debug($this->page_data['course_info']);
					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('generate the certificate') //Entry type like, Post, Page, Entry
					 ->id($user_id) //Entry ID
					 ->token('GENERATE') //Token identify Action
					 ->comment($this->session->userdata('name'). '  generate the certificate.')
					 ->log(); //Add Database Entry
					
				}
			
				$this->page_data['sub_page_view'] = "user_certificate";
				$this->load->view('index', $this->page_data);
			

		}

		public function send_certificate_mail($certificate_key){

			$certificate_info = $this->crud_model->get_user_certificate_serial_no(
				"OBJECT",
				NULL,
				NULL,
				array(
					"certificate" => array(
						'certificate_no',
						'course_name',
						'user_seen_duration',
						'created_at',
						'certificate_key',
						'course_id',
					),
					"user" => array(
						"first_name",
						"last_name",
						"email",
						"id as user_id",

					),
				),
				array("certificate_key" => $certificate_key)
			)[0];


			if ($certificate_info->course_id == 1) {
				$certificate_info->course_name = 'IT Service Management Foundation';
			} elseif ($certificate_info->course_id == 4) {
				$certificate_info->course_name = 'Project Management Foundation';
			} elseif ($certificate_info->course_id == 8) {
				$certificate_info->course_name = 'Project Management Practitioner';
			}else if($certificate_info->course_id == 56){
				$certificate_info->course_name = 'ITIL4 Foundation Exam Preparation Training';
			}else if($certificate_info->course_id == 60){
				$certificate_info->course_name = 'ITIL4 Foundation Training';
			} else {
				$certificate_info->course_name;
			}



			$this->page_data['user_data'] = $certificate_info;
			// debug($this->page_data['user_data']);
			// exit;
			$email_msg = $this->load->view('template/certificate_email', $this->page_data, true);
			$subject = 'Your completion certificate is ready for "'.$certificate_info->course_name .'"';
			
			$mail_send_done = $this->email_model->send_certificate_mail($certificate_info->email, $email_msg, $subject);

			if($mail_send_done == 1){
					$data['certificate_mail_send'] = 1;
					$this->crud_model->update_user_certificate_serial_no($certificate_info->course_id, $certificate_info->user_id, $data);

					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('send mail with certificate') //Entry type like, Post, Page, Entry
					 ->id($certificate_info->user_id) //Entry ID
					 ->token('SEND_MAIL') //Token identify Action
					 ->comment($this->session->userdata('name'). '  send mail with user certificate.')
					 ->log(); //Add Database Entry

					 $this->session->set_flashdata('send_email_success', "Email Send Successfully!!");
			}else{
				$this->session->set_flashdata('send_email_failed', "Email not send ! Something Wrong.");
			}

			redirect(base_url('users/'.$certificate_info->user_id . '/user_certificate'));

		}

		/*Certificate Load*/

		public function load_certificate($course_id, $user_id) {

			$certificate_info = $this->crud_model->getCertificateSerialNo($course_id, $user_id)[0];
		
			redirect(('https://eduera.com.bd/home/get_certificate/' . $certificate_info->certificate_key));
		}
		/*Certificate download*/
			public function download_certificate($certificate_key) {

				if (!has_role($this->session->userdata('user_id'), 'USER_CERTIFICATE')) {
					redirect(base_url('page_not_found'));
				}



				$certificate_info = $this->crud_model->get_user_certificate_serial_no(
					"OBJECT",
					NULL,
					NULL,
					array(
						"certificate" => array(
							'certificate_no',
							'course_name',
							'user_seen_duration',
							'created_at',
							'certificate_key',
							'course_id',
						),
						"user" => array(
							"first_name",
							"last_name",
							'id as user_id'

						),
					),
					array("certificate_key" => $certificate_key)
				)[0];

				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('download the certificate') //Entry type like, Post, Page, Entry
				 ->id($certificate_info->user_id) //Entry ID
				 ->token('DOWNLOAD') //Token identify Action
				 ->comment($this->session->userdata('name'). '  download the certificate.')
				 ->log(); //Add Database Entry




				$course_info = $this->course_model->get_course_list("OBJECT", array('id', 'title', 'certificate'), array('id' => $certificate_info->course_id))[0];

				$get_date = date('d', strtotime($certificate_info->created_at));
				$get_month = date('m', strtotime($certificate_info->created_at));
				$get_year = date('Y', strtotime($certificate_info->created_at));

		// Create date object to store the DateTime format
				$dateObj = DateTime::createFromFormat('!m', $get_month);

		// Store the month name to variable
				$monthName = $dateObj->format('F');

				if ($certificate_info->course_id == 1) {
					$certificate_info->course_name = 'IT Service Management Foundation';
				} elseif ($certificate_info->course_id == 4) {
					$certificate_info->course_name = 'Project Management Foundation';
				} elseif ($certificate_info->course_id == 8) {
					$certificate_info->course_name = 'Project Management Practitioner';
				}else if($certificate_info->course_id == 56){
					$certificate_info->course_name = 'ITIL4 Foundation Exam Preparation Training';
				} else {
					$certificate_info->course_name;
				}
				$name = $certificate_info->first_name . ' ' . $certificate_info->last_name;
				$output = '<html>
			<head><meta http-equiv=Content-Type content="text/html; charset=UTF-8">
			<style type="text/css">
			<!--
			span.cls_003{font-family:"Monotype Corsiva",serif;font-size:46.1px;color:rgb(81,94,101);font-weight:normal;font-style:italic;text-decoration: none}
			div.cls_003{font-family:"Monotype Corsiva",serif;font-size:46.1px;color:rgb(81,94,101);font-weight:normal;font-style:italic;text-decoration: none}
			span.cls_002{font-family:"Monotype Corsiva",serif;font-size:24.7px;color:rgb(81,94,101);font-weight:normal;font-style:italic;text-decoration: none}
			div.cls_002{font-family:"Monotype Corsiva",serif;font-size:24.7px;color:rgb(81,94,101);font-weight:normal;font-style:italic;text-decoration: none}
			span.cls_004{font-family:"Monotype Corsiva",serif;font-size:10.0px;color:rgb(43,42,41);font-weight:normal;font-style:italic;text-decoration: none}
			div.cls_004{font-family:"Monotype Corsiva",serif;font-size:14.0px;color:rgb(43,42,41);font-weight:normal;font-style:italic;text-decoration: none}
			span.cls_005{font-family:"Monotype Corsiva",serif;font-size:6.9px;color:rgb(43,42,41);font-weight:normal;font-style:italic;text-decoration: none}
			div.cls_005{font-family:"Monotype Corsiva",serif;font-size:6.9px;color:rgb(43,42,41);font-weight:normal;font-style:italic;text-decoration: none}
			-->
			</style>
			<script type="text/javascript" src="5fddaf08-7982-11ea-8b25-0cc47a792c0a_id_5fddaf08-7982-11ea-8b25-0cc47a792c0a_files/wz_jsgraphics.js"></script>
			</head>
			<body id="certificate_view">
			<div style="position:absolute;left:50%;margin-left:-420px;top:0px;width:841px;height:595px;border-style:outset;overflow:hidden">
			<div style="position:absolute;left:0px;top:0px">
			<img src="https://eduera.com.bd/assets/frontend/certificate/'.$course_info->certificate.'" width=841 height=595>

			</div>
			<div style="position:absolute;left:191.59px;top:152.23px" class="cls_003"><span class="cls_003">Certification of completion</span></div>

			<div class="row" style=" text-align: center; position:absolute; left:130.62px;top:280.02px; padding-right: 30px;">
			    <div class="col-md-12">
			        <span class="cls_002 cls_003" style="font-weight:italic;">' . $certificate_info->first_name . ' ' . $certificate_info->last_name . '  has successfully completed <br><span style="color:#e33667;">' . $certificate_info->course_name . '</span> online training on ' . $get_date . ' ' . $monthName . ', ' . $get_year . ' </span>
			    </div>
			</div>

			<div style="position:absolute;left:16.06px;top:567.77px;" class="cls_004"><span class="cls_004">Certificate no: ' . $certificate_info->certificate_no . '</span></div>
			<div style="position:absolute;right:16.06px;top:567.77px;font-weight:bold" class="cls_004"><span class="cls_004">Verification URL:  www.eduera.com.bd/home/certificate</span></div>


			</body>
			</html>
			';

				include APPPATH . 'libraries/dompdf/autoload.inc.php';

				$dompdf = new Dompdf\Dompdf();
				$htmlcontent = $output;

				$dompdf->loadHtml($htmlcontent);

				$customPaper = array(2, -15, 665, 470);
				$dompdf->set_paper($customPaper);

				$dompdf->render();

				$dompdf->stream($certificate_info->course_name . ".pdf", array("Attachment" => 0));

			}




	/*
		    * Service - update user role
	*/
	public function update_user_role($user_id) {

		if ($this->session->userdata('user_type') !== "SUPER_ADMIN") {
			redirect(base_url('page_not_found'));
		}

		$data = $this->input->post('role_name');
		$role_list['role_list'] = '["' . implode('","', $data) . '"]';
		if ($this->user_model->update_user_info($user_id, $role_list)) {

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('update_user_role') //Entry type like, Post, Page, Entry
			 ->id($user_id) //Entry ID
			 ->token('UPDATE') //Token identify Action
			  ->comment($this->session->userdata('name'). '  has change user role.')
			 ->log(); //Add Database Entry

			$this->session->set_flashdata('user_role_save_success', "User Role Save Successfully");

		} else {
			$this->session->set_flashdata('user_role_save_failed', "User Role Save Failed");
		}
		redirect(base_url("users/" . $user_id . "/user_role"));
	}

	/*
		    * Service - remove particular user enrolled course
	*/
	public function remove_enroll_course($enroll_id) {

		$enroll_history = $this->enroll_model->remove_enrollment_by_id($enroll_id);

		if ($enroll_history) {

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('remove_enroll_course') //Entry type like, Post, Page, Entry
			 ->id($enroll_id) //Entry ID
			 ->token('DELETE') //Token identify Action
			 ->comment($this->session->userdata('name'). '  removed enrolled user.')
			 ->log(); //Add Database Entry

			$this->session->set_flashdata('success', 'User enrollment removed successfully.');
		} else {
			$this->session->set_flashdata('danger', 'Failed to remove enrollment!');
		}

		redirect(site_url('users/' . $_GET['user_id'] . '/user_enrollment'));
	}

	/*
		    * Service - remove particular instructor from course
	*/
	public function remove_instructor_from_course($course_id, $instructor_id) {

		if ($this->course_model->update_course_info($course_id, array('instructor_id' => 0, 'instructor_share' => 0))) {

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('remove_instructor_from_course') //Entry type like, Post, Page, Entry
			 ->id($course_id) //Entry ID
			 ->token('DELETE') //Token identify Action
			 ->comment($this->session->userdata('name'). '  removed instructor in a course.')
			 ->log(); //Add Database Entry


			$this->session->set_flashdata('success', 'Instructor in this course has been removed.');
		} else {
			$this->session->set_flashdata('danger', 'Failed to removed!');
		}

		redirect(site_url('users/' . $instructor_id . '/instructor_enrollment'));
	}

	/*
		    * Service - User Reset Password Functionality
	*/
	public function reset_password($id) {

		$length = 6;
		$password = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz$'), 1, $length);

		$this->page_data['user_data'] = $this->user_model->get_user_by_id($id, array('id', 'first_name', 'last_name', 'email', 'phone', 'password'));

		$data['password'] = sha1($password);
		
		$reset_password = $this->user_model->update_user_info($id, $data);
		if ($reset_password) {
			$this->page_data['password'] = $password;
			$email_msg = $this->load->view('template/password_reset_email', $this->page_data, true);
			$done = $this->email_model->send_email_and_password($this->page_data['user_data']->email, $email_msg);
			if ($done == '1') {

				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('reset_password') //Entry type like, Post, Page, Entry
				 ->id($id) //Entry ID
				 ->token('UPDATE') //Token identify Action
				 ->comment($this->session->userdata('name'). '  update user password.')
				 ->log(); //Add Database Entry



				$this->session->set_flashdata('reset_password', "Password Reset Successfully!!");
			} else {
				$this->session->set_flashdata('reset_password_failed', "Failed to send email! Please check it it is an real email");
			}

		} else {
			$this->session->set_flashdata('reset_password_failed', "Password Reset Failed. Please take a screenshot and contact developers team!!");
		}

		redirect(site_url('users/' . $id . '/info'));

	}


		

		

}