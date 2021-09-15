<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends CI_Controller {

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
		$this->load->library('logger');
		$this->load->model('service/service_course', 'service_course');
		$this->page_data['category_list'] = $this->category_model->get_category_list_with_nested_sub_category_list(array('id', 'parent', 'name'));
		$this->page_data['user_data'] = $this->user_model->get_user_by_id($this->session->userdata('user_id'), array('id', 'profile_photo_name'));
		$this->page_data['total_users'] = $this->user_model->get_user_count();
		$this->page_data['total_enrollment'] = $this->enroll_model->get_enrollment_list("COUNT", array('id'));
		$this->page_data['total_courses'] = $this->course_model->get_all_courses_count();
		$this->load->helper('file');


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
		    *   Controller - for showing question bank page
	*/
	public function question_bank($course_id = null, $offset = 0) {
		if (has_role($this->session->userdata('user_id'), 'QUESTION_READ')) {
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	            </button>', '</div>');

			if ($this->form_validation->run('course_question_form') == FALSE) {

				if ($course_id == null || $this->course_model->get_course_list(
					"COUNT",
					array('id'),
					array('id' => $course_id)
				) == 0) {
					redirect(base_url('home/page_not_found'));
				}

				$pagination_config['per_page'] = 0;

				if(isset($_GET['pagination'])){
					$pagination_config['per_page'] = $_GET['pagination'];
				}else{
					$pagination_config['per_page'] = 10;
				}

				if(constant('FILE_SERVER_ROOT_DIR') == '/var/www/html/'){
					$root = 'http://localhost/eduera/';
				}else{
					$root = 'https://eduera.com.bd/';
				}
				$this->page_data['image_path'] = $file = $root . 'uploads/question_images/';
				// $pagination_config['total_rows'] = 100;

				$this->page_data['question'] = array();
				$this->page_data['page_name'] = "question_bank";
				$this->page_data['page_view'] = "course/index";

				$this->page_data['course_info'] = $this->course_model->get_course_list(
					"OBJECT",
					array('id', 'title'),
					array('id' => $course_id)
				)[0];

				$search_question = null;
				if(isset($_GET['search_question'])){
					$search_question = $_GET['search_question'];
				}

				$pagination_config['total_rows'] = $this->quiz_model->get_all_question_by_course_id(
					"COUNT",
					$course_id,
					$search_question
					// $_GET['user_search_type']
				);

				$this->page_data['question_list'] = $this->quiz_model->get_all_question_by_course_id(
					"OBJECT",
					$course_id,
					$search_question,
					($search_question != null) ? null:array('limit' => $pagination_config['per_page'], 'offset' => $offset)
				);


				for ($i = 0; $i < count($this->page_data['question_list']); $i++) {
					$this->page_data['question_list'][$i]->option_list = json_decode($this->page_data['question_list'][$i]->option_list);
				}

				$this->page_data['page_title'] = $this->page_data['course_info']->title;
				$this->page_data['sub_page_view'] = "question_bank";


				$this->page_data['page_limit'] = 1;
				$this->page_data['number_of_total_question'] = $pagination_config['total_rows'];
				$this->page_data['offset'] = $offset;

				$pagination_config['base_url'] = site_url('course/question_bank/'.$course_id.'/');
				$pagination_config['num_links'] = 10;
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
				$this->load->model('service/service_quiz', 'service_quiz');
				$this->service_quiz->save_question_for_course($course_id);
			}

		} else {
			redirect(base_url('page_not_found'));
		}
	}

	/*
		    *   Controller - for showing specifice couse info page
	*/
	public function info($course_id = null) {

		if ($course_id == null || $this->course_model->get_course_list(
			"COUNT",
			array('id'),
			array('id' => $course_id)
		) == 0) {
			redirect(base_url('home/page_not_found'));
		}

		if (!has_role($this->session->userdata('user_id'), 'COURSE_UPDATE_INFO')) {
			redirect(base_url('page_not_found'));
		}

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>', '</div>');

		if ($this->form_validation->run('course_info_form') == FALSE) {
			$this->page_data['page_name'] = "course_info";
			$this->page_data['page_view'] = "course/index";

			$this->page_data['course_info'] = $this->course_model->get_course_list(
				"OBJECT",
				array('id', 'title', 'short_description', 'description', 'language', 'category_id', 'level', 'status', 'preview_video_id', 'price', 'discounted_price', 'rank', 'expiry_month', 'send_greeting_mail', 'certification_course', 'mock_test'),
				array('id' => $course_id)
			)[0];


			$this->page_data['page_title'] = $this->page_data['course_info']->title;

			foreach ($this->page_data['category_list'] as $category) {
				if ($category->id === $this->page_data['course_info']->category_id) {
					$this->page_data['matched_category'] = $category->id;
					break;
				}
				foreach ($category->sub_category_list as $sub_category) {
					if ($sub_category->id === $this->page_data['course_info']->category_id) {
						$this->page_data['matched_category'] = $category->id;
						$this->page_data['matched_sub_category'] = $sub_category->id;
						break 2;
					}
				}
			}

			$this->page_data['rank_list'] = $this->course_model->get_course_list(
				"OBJECT",
				array('id', 'rank')
			);

			$this->page_data['sub_page_view'] = "info";
			$this->load->view('index', $this->page_data);
		} else {
			$this->service_course->save_course_info($course_id);
		}

	}

	/*
		    *   Controller - for showing curriculum page
	*/
	public function curriculum($course_id = null, $form_name = null) {

		if ($course_id == null || $this->course_model->get_course_list(
			"COUNT",
			array('id', 'title'),
			array('id' => $course_id)
		) == 0) {
			redirect(base_url('home/page_not_found'));
		}

		if (!has_role($this->session->userdata('user_id'), 'COURSE_UPDATE_CURRICULUM')) {
			redirect(base_url('page_not_found'));
		}

		$this->page_data['page_name'] = "course_curriculum";
		$this->page_data['page_view'] = "course/index";
		$this->page_data['sub_page_view'] = "curriculum";

		$this->page_data['course_info'] = $this->course_model->get_course_list(
			"OBJECT",
			array('id', 'title'),
			array('id' => $course_id)
		)[0];

		$this->page_data['page_title'] = $this->page_data['course_info']->title;
		$this->page_data['course_info']->section_list = $this->section_model->get_section_list("OBJECT", array('id', 'title', 'rank'), array('course_id' => $course_id));

		for ($i = 0; $i < count($this->page_data['course_info']->section_list); $i++) {
			$this->page_data['course_info']->section_list[$i]->lesson_list = $this->lesson_model->get_lesson_list_by_section_id($this->page_data['course_info']->section_list[$i]->id, array('id', 'title', 'lesson_file as lesson_file_list', 'rank', 'duration_in_second', 'summary', 'vimeo_id', 'preview', 'video_id', 'video_type'));

			for ($j = 0; $j < count($this->page_data['course_info']->section_list[$i]->lesson_list); $j++) {
				$this->page_data['course_info']->section_list[$i]->lesson_list[$j]->lesson_file_list = json_decode($this->page_data['course_info']->section_list[$i]->lesson_list[$j]->lesson_file_list);
			}
			$this->page_data['course_info']->section_list[$i]->duration_in_second = 0;
			foreach ($this->page_data['course_info']->section_list[$i]->lesson_list as $lesson) {
				$this->page_data['course_info']->section_list[$i]->duration_in_second += $lesson->duration_in_second;
				$this->page_data['course_info']->duration_in_second += $lesson->duration_in_second;
				$this->page_data['course_info']->lesson_count++;
			}
		}

		foreach ($this->page_data['category_list'] as $category) {
			if ($category->id === $this->page_data['course_info']->category_id) {
				$this->page_data['matched_category'] = $category->id;
				break;
			}
			foreach ($category->sub_category_list as $sub_category) {
				if ($sub_category->id === $this->page_data['course_info']->category_id) {
					$this->page_data['matched_category'] = $category->id;
					$this->page_data['matched_sub_category'] = $sub_category->id;
					break 2;
				}
			}
		}

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>', '</div>');

		if ($form_name == "section_add_form") {
			if ($this->form_validation->run('course_section_form') == FALSE) {
				$this->load->view('index', $this->page_data);
			} else {
				$this->service_course->save_section();
			}
		} else if ($form_name == "lesson_add_form") {
			if ($this->form_validation->run('course_lesson_form') == FALSE) {
				$this->load->view('index', $this->page_data);
			} else {
				$this->service_course->save_lesson();
			}
		} else {
			$this->load->view('index', $this->page_data);
		}

	}

	/*
		*   Controller - for showing media page
	*/
	public function media($course_id = null) {
		if (!has_role($this->session->userdata('user_id'), 'COURSE_THUMBNAILS')) {
			redirect(base_url('page_not_found'));
		}
		if ($course_id == null || $this->course_model->get_course_list(
			"COUNT",
			array('id', 'title'),
			array('id' => $course_id)
		) == 0) {
			redirect(base_url('home/page_not_found'));
		}

		$this->page_data['page_name'] = "media";
		$this->page_data['page_view'] = "course/index";

		$this->page_data['course_info'] = $this->course_model->get_course_list(
			"OBJECT",
			array('id', 'title'),
			array('id' => $course_id)
		)[0];
			if(constant('EDUERA_ROOT_DIR') == '/var/www/html/'){
				$root = 'http://localhost/eduera/';
			}else{
				$root = 'https://eduera.com.bd/';
			}
			
			
		$this->page_data['image_path'] = $file = $root . 'uploads/thumbnails/course_thumbnails/';
		$this->page_data['page_title'] = $this->page_data['course_info']->title;
		$this->page_data['sub_page_view'] = "media";
		$this->load->view('index', $this->page_data);
	}

	public function upload_photo($course_id){
		if (!has_role($this->session->userdata('user_id'), 'COURSE_THUMBNAILS')) {
			redirect(base_url('page_not_found'));
		}
		
		if(constant('EDUERA_ROOT_DIR') == "/home2/eduera61/public_html/"){
			$config['upload_path'] = constant('EDUERA_ROOT_DIR').'uploads/thumbnails/course_thumbnails/';

		}else{
			$config['upload_path'] = constant('EDUERA_ROOT_DIR').'eduera/uploads/thumbnails/course_thumbnails/';

		}
		
		$config['allowed_types'] = 'jpg|JPG';
		$config['file_name'] = 'course_thumbnail_default_'.$course_id.'.jpg';
		$config['max_size'] = 512;
		// $config['max_width'] = 1075;
		// $config['max_height'] = 1024;
		$config['overwrite'] = TRUE;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('course_thumbnail')) {
			$this->session->set_flashdata('photo_upload_error', $this->upload->display_errors());
		} else {
			// if ($this->user_model->update_user_info($this->session->userdata('user_id'), array("profile_photo_name" => $this->upload->data()['file_name']))) {

				$this->session->set_flashdata('photo_upload_success', "Course thumbnail uploaded successfully!");
			// } else {
			// 	$this->session->set_flashdata('photo_upload_error', "Failed to save in database!");
			// }

		}

		redirect(base_url('course/media/'.$course_id));
	}

	/*
		*   Controller - for showing course certificate
	*/
	public function certificate($course_id = null) {

		if ($course_id == null || $this->course_model->get_course_list(
			"COUNT",
			array('id', 'title'),
			array('id' => $course_id)
		) == 0) {
			redirect(base_url('home/page_not_found'));
		}

		$this->page_data['page_name'] = "certificate";
		$this->page_data['page_view'] = "course/index";

		$this->page_data['course_info'] = $this->course_model->get_course_list(
			"OBJECT",
			array('id', 'title', 'certificate'),
			array('id' => $course_id)
		)[0];

		$this->page_data['certificate_path'] = $file = constant('FILE_SERVER_ROOT_DIR') . '/certificate/';
		$this->page_data['page_title'] = $this->page_data['course_info']->title;
		$this->page_data['sub_page_view'] = "course_certificate";
		$this->load->view('index', $this->page_data);
	}

	/*
		*   Controller - for showing seo page
	*/
	public function seo($course_id = null) {

		if ($course_id == null || $this->course_model->get_course_list(
			"COUNT",
			array('id', 'title'),
			array('id' => $course_id)
		) == 0) {
			redirect(base_url('home/page_not_found'));
		}

		if (!has_role($this->session->userdata('user_id'), 'COURSE_UPDATE_SEO')) {
			redirect(base_url('page_not_found'));
		}

		$this->page_data['page_name'] = "seo";
		$this->page_data['page_view'] = "course/index";

		$this->page_data['course_info'] = $this->course_model->get_course_list(
			"OBJECT",
			array('id', 'title', 'seo_title', 'meta_description', 'meta_tags'),
			array('id' => $course_id)
		)[0];

		$this->page_data['page_title'] = $this->page_data['course_info']->title;
		$this->page_data['sub_page_view'] = "seo";
		$this->load->view('index', $this->page_data);
	}

	/*
		*   Controller - for showing question bank page
	*/
	public function quiz_set($course_id = null) {
		if (has_role($this->session->userdata('user_id'), 'QUIZ_READ')) {
			if ($course_id == null || $this->course_model->get_course_list(
				"COUNT",
				array('id'),
				array('id' => $course_id)
			) == 0) {
				redirect(base_url('home/page_not_found'));
			}

			$this->page_data['question'] = array();
			$this->page_data['page_name'] = "quiz_set";
			$this->page_data['page_view'] = "course/index";

			$this->page_data['course_info'] = $this->course_model->get_course_list(
				"OBJECT",
				array('id', 'title'),
				array('id' => $course_id)
			)[0];

			$this->page_data['course_info']->lesson_list = $this->lesson_model->get_lesson_list_by_course_id($this->page_data['course_info']->id, array('id', 'title'));
			$this->page_data['question_list'] = $this->quiz_model->get_question_list_by_course($course_id);

			if (isset($_GET['option'])) {

				$this->session->unset_userdata('lesson_id');

				if ($_GET['option'] == 'course') {
					$this->page_data['quiz_set_list'] = $this->quiz_model->get_quiz_set_list(
						'OBJECT',
						true,
						array('quiz_set' => array('id', 'name', 'type', 'question_id_list', 'duration', 'quiz_result', 'free_access')),
						array(
							'quiz_set.course_id' => $course_id,
							'quiz_set.lesson_id' => null,
						)
					);

				} else if ($_GET['option'] == 'lesson') {
					$this->session->set_userdata('lesson_id', $_GET['lesson_id']);
					if (html_escape($this->input->get('lesson_id')) != null && html_escape($this->input->get('lesson_id')) != '') {
						$this->page_data['quiz_set_list'] = $this->quiz_model->get_quiz_set_list(
							'OBJECT',
							true,
							array(
								'quiz_set' => array('id', 'name', 'type', 'question_id_list' , 'duration', 'quiz_result', 'free_access'),
								'lesson' => array('title'),
							),
							array(
								'quiz_set.course_id' => $course_id,
								'quiz_set.lesson_id' => html_escape($this->input->get('lesson_id')),
							)
						);
					}
				}

				$this->session->set_userdata('option', $_GET['option']);
			}

			$this->page_data['page_title'] = $this->page_data['course_info']->title;
			$this->page_data['sub_page_view'] = "quiz_set";
			$this->load->view('index', $this->page_data);
		} else {
			redirect(base_url('page_not_found'));
		}
	}


	public function addQuestionInQuiz($course_id, $quiz_id, $offset = 0){

		if (has_role($this->session->userdata('user_id'), 'QUIZ_READ')) {
			if ($course_id == null || $this->course_model->get_course_list(
				"COUNT",
				array('id'),
				array('id' => $course_id)
			) == 0) {
				redirect(base_url('home/page_not_found'));
			}

			$pagination_config['per_page'] = 0;

			if(isset($_GET['pagination'])){
				$pagination_config['per_page'] = $_GET['pagination'];
			}else{
				$pagination_config['per_page'] = 30;
			}


			$this->page_data['course_info'] = $this->course_model->get_course_list(
				"OBJECT",
				array('id', 'title'),
				array('id' => $course_id)
			)[0];


			$this->page_data['quiz_set_list'] = $this->quiz_model->get_quiz_set_list(
				'OBJECT',
				true,
				array('quiz_set' => array('id','question_id_list')),
				array(
					'quiz_set.course_id' => $course_id,
					'quiz_set.lesson_id' => ($this->session->userdata('lesson_id') ? $this->session->userdata('lesson_id'): null),
				)
			);

			// debug($this->page_data['quiz_set_list']);

			$this->page_data['quiz_question_id'] = array();
			$this->page_data['question_id'] = array();
			foreach($this->page_data['quiz_set_list'] as $quiz_set){
				foreach($quiz_set->question_id_list as $questions){
					array_push($this->page_data['quiz_question_id'], $questions);
				}	
			}
			
			$question = $this->quiz_model->get_all_question_by_course_id(
				"OBJECT",
				$course_id,
				null,
				null,
				array("id")
				
			);
				
			foreach($question as $q){
				array_push($this->page_data['question_id'], $q->id);
			}

			$result = array_diff($this->page_data['question_id'], $this->page_data['quiz_question_id']);

			$this->page_data['question_list'] = array();
			foreach($result as $r){
			
			$question_list = $this->quiz_model->get_all_question_by_course_id(
				"OBJECT",
				null,
				null,
				null,
				array("id", 'question', 'option_list', 'right_option_value', 'explanation'),
				array("id" => $r)
				
				);


			$question_count[] = $this->quiz_model->get_all_question_by_course_id(
				"COUNT",
				null,
				null,
				null,
				null,
				array("id" => $r)
				// $_GET['user_search_type']
			);
			

			
			array_push($this->page_data['question_list'], $question_list);
			// array_push($pagination_config['total_rows'], $question_count);
			}	

			$this->page_data['question_list'] = array_slice($this->page_data['question_list'], $offset, $pagination_config['per_page']); 
			
			$pagination_config['total_rows'] = count($question_count);

		// debug(count($pagination_config['total_rows']));


		$this->page_data['page_name'] = "add_question_for_quiz_set";
		$this->page_data['page_view'] = "course/index";
		$this->page_data['page_title'] = $this->page_data['course_info']->title;
		$this->page_data['quiz_set_id'] = $quiz_id;
		$this->page_data['sub_page_view'] = "add_question_for_quiz_set";

		$this->page_data['page_limit'] = 3;
		$this->page_data['number_of_total_question'] = $pagination_config['total_rows'];
		$this->page_data['offset'] = $offset;

		$pagination_config['base_url'] = site_url('course/addQuestionInQuiz/'.$course_id.'/'.$quiz_id);
		$pagination_config['num_links'] = 10;
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
			redirect(base_url('page_not_found'));
		}
			

	}

	public function show_questions($course_id, $quiz_id){
		if (has_role($this->session->userdata('user_id'), 'QUIZ_READ')) {
			if ($course_id == null || $this->course_model->get_course_list(
				"COUNT",
				array('id'),
				array('id' => $course_id)
			) == 0) {
				redirect(base_url('home/page_not_found'));
			}

			$this->page_data['course_info'] = $this->course_model->get_course_list(
				"OBJECT",
				array('id', 'title'),
				array('id' => $course_id)
			)[0];

			$this->page_data['quiz_set_list'] = $this->quiz_model->get_quiz_set_list(
				'OBJECT',
				true,
				array('quiz_set' => array('id','question_id_list')),
				array(
					'quiz_set.course_id' => $course_id,
					'quiz_set.id' => $quiz_id,
					'quiz_set.lesson_id' => ($this->session->userdata('lesson_id') ? $this->session->userdata('lesson_id'): null),
				)
			)[0];

			// debug($this->page_data['quiz_set_list']);
			// exit;



			$this->page_data['page_name'] = "show_questions";
			$this->page_data['page_view'] = "course/index";
			$this->page_data['page_title'] = $this->page_data['course_info']->title;
			$this->page_data['quiz_set_id'] = $quiz_id;

			$this->page_data['sub_page_view'] = "show_questions";
			$this->load->view('index', $this->page_data);


		}else{
			redirect(base_url('page_not_found'));
		}
	}

	public function save_question_for_course($course_id){

		$quiz_set_id = $this->input->post('quiz_set_id');
		$lesson_id = $this->input->post('lesson_id');
		$question_id_list = ($this->input->post('question_id'));

		
		// exit;
		/*Get Quiz data*/
		$quiz_set_list = $this->quiz_model->get_quiz_set_list(
			'OBJECT',
			true,
			array('quiz_set' => array('question_id_list')),
			array(
				'quiz_set.id' => $quiz_set_id,
				'quiz_set.lesson_id' => ($lesson_id ? $lesson_id: null),
			)
		)[0];
	

	
		$existing_data = implode(',', $quiz_set_list->question_id_list);
		 
		$current_question_data = implode(',', $question_id_list);

		if($existing_data != null && $existing_data != ""){
			$questions = $existing_data.','.$current_question_data;
		}else{
			$questions = $current_question_data;
		}
		

		$questions_explode_list = explode(',', $questions);
	
		$data['question_id_list'] = '["' . implode('","', $questions_explode_list) . '"]';
	
		if($quiz_set_id != null && $quiz_set_id != ""){
			$result = $this->quiz_model->insert_set($data, $quiz_set_id);

			
			if ($result) {
				if ($result['success']) {

					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('save_quiz_set') //Entry type like, Post, Page, Entry
					 ->id($result['id']) //Entry ID
					 ->token('UPDATE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' update quiz set.')
					 ->log(); //Add Database Entry

					$this->session->set_flashdata('quiz_set_save_success', 'Quiz set is inserted successfully.');
				}else{
					$this->session->set_flashdata('quiz_set_save_failed', 'Quiz set failed to insert.');

				}
			}else{
				$this->session->set_flashdata('quiz_set_save_failed', 'Quiz set failed to insert.');

			}
		}
		


		return redirect(base_url('course/addQuestionInQuiz/'.$course_id.'/'.$quiz_set_id));
	}

	public function remove_quiz_question_from_course($course_id){
		$quiz_set_id = $this->input->post('quiz_set_id');
		$lesson_id = $this->input->post('lesson_id');
		$question_id_list = ($this->input->post('question_id'));


		$quiz_set_list = $this->quiz_model->get_quiz_set_list(
			'OBJECT',
			true,
			array('quiz_set' => array('question_id_list')),
			array(
				'quiz_set.id' => $quiz_set_id,
				'quiz_set.lesson_id' => ($lesson_id ? $lesson_id: null),
			)
		)[0];


		$result = array_diff($quiz_set_list->question_id_list, $question_id_list);

		$questions_implode_list = implode(',', $result);

		$questions_explode_list = explode(',', $questions_implode_list);
		
		$data['question_id_list'] = '["' . implode('","', $questions_explode_list) . '"]';

		if($quiz_set_id != null && $quiz_set_id != ""){

				$result = $this->quiz_model->insert_set($data, $quiz_set_id);

				
				if ($result) {
					if ($result['success']) {

						$this->logger
						 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
						 ->user_details($this->user_model->getUserInfoByIpAddress())
						 ->type('remove_quiz_set') //Entry type like, Post, Page, Entry
						 ->id($result['id']) //Entry ID
						 ->token('DELETE') //Token identify Action
						 ->comment($this->session->userdata('name'). ' remove question from quiz set.')
						 ->log(); //Add Database Entry

						$this->session->set_flashdata('quiz_set_save_success', 'Question removed successfully.');
					}else{
						$this->session->set_flashdata('quiz_set_save_failed', 'Failed to remove.');

					}
				}else{
					$this->session->set_flashdata('quiz_set_save_failed', 'Failed to remove.');

				}
		}


		return redirect(base_url('course/show_questions/'.$course_id.'/'.$quiz_set_id));

	}

	/*
		*   Service - for uploading course certificate (course certificate)
	*/
	public function upload_certificate($course_id) {
		$config['upload_path'] = constant('FILE_SERVER_ROOT_DIR') . '/certificate';
		// $config['upload_path'] = './uploads/user_image';
		$config['allowed_types'] = 'jpg|png|JPG|PNG';
		$config['file_name'] = $course_id;
		$config['max_size'] = 2048;
		$config['max_width'] = 1075;
		$config['max_height'] = 1024;
		$config['overwrite'] = TRUE;

		$this->load->library('upload', $config);
		// debug($config);
		// exit;
		if (!$this->upload->do_upload('course_certificate')) {
			$this->session->set_flashdata('certificate_photo_upload_error', $this->upload->display_errors());
		} else {
			if ($this->course_model->upload_certificate($course_id, array("certificate" => $this->upload->data()['file_name']))) {
				/*User Logger info*/

				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('upload_certificate') //Entry type like, Post, Page, Entry
				 ->id($course_id) //Entry ID
				 ->token('UPLOAD') //Token identify Action
				 ->comment($this->session->userdata('name'). ' upload the certificate.')
				 ->log(); //Add Database Entry


				$this->session->set_flashdata('certificate_photo_upload_successful', "Certificate photo uploaded successfully!");
			} else {
				$this->session->set_flashdata('certificate_photo_upload_error', "Failed to save in database!");
			}

		}

		redirect(base_url('/course/certificate/' . $course_id));

	}

	function remove_question_image_from_img($question_img, $question_id, $course_id){
		if (has_role($this->session->userdata('user_id'), 'QUESTION_DELETE')) {

			/* Question Image Upload*/
			if(constant('EDUERA_ROOT_DIR') == "/home2/eduera61/public_html/"){
				$config['upload_path'] = constant('EDUERA_ROOT_DIR').'uploads/question_images/';

			}else{
				$config['upload_path'] = '/var/www/html/eduera/'.'uploads/question_images/';

			}
				$result = unlink($config['upload_path'].$question_img);

				if($result){
					$data['question_img'] = null;
					$result_1 = $this->quiz_model->insert_question($data, $question_id);

					$this->session->set_flashdata('course_question_save_success', "Question Image Removed Successfully");
				}else{
					$this->session->set_flashdata('course_question_save_failed', "Question image removed request failed.");
				}
				

			redirect(base_url('course/question_bank/' . $course_id));
		}else{
			redirect(base_url('page_not_found'));
		}
	}

	/*
		* Service - For remove question
	*/
	public function remove_question_from_course($course_id, $question_id) {
		if (has_role($this->session->userdata('user_id'), 'QUESTION_DELETE')) {
			$quiz_set_list = $this->quiz_model->get_quiz_set_list(
				"OBJECT",
				false,
				array(
					'quiz_set' => array('id', 'course_id', 'question_id_list'),
				),
				array(
					'quiz_set.course_id' => $course_id,
				)
			);

			$failed_to_update_quiz_set = false;

			foreach ($quiz_set_list as $quiz_set) {
				$quiz_set->question_id_list = json_decode($quiz_set->question_id_list);
				for ($i = 0; $i < count($quiz_set->question_id_list); $i++) {
					if ($quiz_set->question_id_list[$i] == $question_id) {
						unset($quiz_set->question_id_list[$i]);
						if ($this->quiz_model->update_quiz_set($quiz_set->id, array('question_id_list' => '["' . implode('","', $quiz_set->question_id_list) . '"]'))) {
							$failed_to_update_quiz_set = false;
						} else {
							$failed_to_update_quiz_set = true;
						}
						break 1;
					}
				}
			}

			if (!$failed_to_update_quiz_set) {
				if (!$this->quiz_model->question_delete($question_id)) {
					/*User Logger info*/

					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('remove_question_from_course') //Entry type like, Post, Page, Entry
					 ->id($course_id .' '. $question_id) //Entry ID
					 ->token('DELETE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' removed the question.')
					 ->log(); //Add Database Entry

					$this->session->set_flashdata('course_question_save_success', "Question Removed Successfully");
				} else {
					$this->session->set_flashdata('course_question_save_failed', "Question removed request failed.");
				}
			}

			redirect(base_url('course/question_bank/' . $course_id));
		} else {
			redirect(base_url('page_not_found'));
		}

	}

	/*
		*   Service - for saving seo page
	*/
	public function save_quiz_set_form($course_id) {
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>', '</div>');

		if ($this->form_validation->run('course_quiz_set_form') == FALSE) {
			$this->redirect(base_url('course/quiz_set/' . $course_id));
		} else {

			$this->service_course->save_quiz_set($course_id);
		}
	}

	/*
		*   Service - for saving seo page
	*/
	public function save_seo_info($course_id) {

		if (!has_role($this->session->userdata('user_id'), 'COURSE_UPDATE_SEO')) {
			redirect(base_url('page_not_found'));
		}

		$data['seo_title'] = html_escape($this->input->post('seo_title'));
		$data['meta_description'] = html_escape($this->input->post('meta_description'));
		$data['meta_tags'] = html_escape($this->input->post('meta_tags'));
		
		if ($this->course_model->update_course_info($course_id, $data)) {
			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('save_seo_info') //Entry type like, Post, Page, Entry
			 ->id($course_id ) //Entry ID
			 ->token('UPDATE') //Token identify Action
			 ->comment($this->session->userdata('name'). ' updated the seo info.')
			 ->log(); //Add Database Entry


			$this->session->set_flashdata('update_seo_info_success', 'Updated successfully.');
		} else {
			$this->session->set_flashdata('update_seo_info_error', 'Failed to update.');
		}

		redirect(base_url('course/seo/') . $course_id);
	}

	/*
		* Service  - for remove quiz set
	*/
	public function remove_quiz_set_from_course($course_id, $quiz_set_id) {
		if (has_role($this->session->userdata('user_id'), 'QUIZ_DELETE')) {
			if ($quiz_set_id != null) {
				$result = $this->quiz_model->delete_quiz_set($quiz_set_id);
				if ($result['success']) {

					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('remove_quiz_set_from_course') //Entry type like, Post, Page, Entry
					 ->id($course_id. ' '. $quiz_set_id ) //Entry ID
					 ->token('DELETE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' removed quiz set.')
					 ->log(); //Add Database Entry


					$this->session->set_flashdata('quiz_set_save_success', 'Quiz set removed successfully.');
				} else {
					$this->session->set_flashdata('quiz_set_save_failed', 'Quiz set failed to remove.');
				}
			}
			redirect(base_url('course/quiz_set/' . $course_id));
		} else {
			redirect(base_url('page_not_found'));
		}
	}

	/*
		*Service - save lesson file upload
	*/
	public function lesson_file_upload($course_id = null) {
	    
		$lesson_id = html_escape($this->input->post('lesson_id'));
		$config['upload_path'] = constant('EDUERA_ROOT_DIR') . '/lesson_files';
		$config['allowed_types'] = 'txt|pdf|xlsx|csv|xls|doc|docx';
		$total_lesson_files = array();
		$all_file_uploaded = true;

		foreach (json_decode($this->lesson_model->get_lesson_list(array('lesson_file'), 'OBJECT', array('id' => $lesson_id))[0]->lesson_file) as $file) {
			array_push($total_lesson_files, $file);
		}

		for ($i = 0; $i < count($_FILES['lesson_files']['tmp_name']); $i++) {
			$_FILES['lesson_file']['type'] = $_FILES['lesson_files']['type'][$i];
			$_FILES['lesson_file']['tmp_name'] = $_FILES['lesson_files']['tmp_name'][$i];
			$_FILES['lesson_file']['size'] = $_FILES['lesson_files']['size'][$i];
			$_FILES['lesson_file']['name'] = $_FILES['lesson_files']['name'][$i];

			$config['max_size'] = 10240;
			$config['overwrite'] = TRUE;
			$config['file_name'] = string_to_slug(pathinfo($_FILES['lesson_file']['name'], PATHINFO_FILENAME)) . '_' . md5(uniqid(rand(), true)) . '.' . pathinfo($_FILES['lesson_file']['name'], PATHINFO_EXTENSION);

			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			array_push($total_lesson_files, $config['file_name']);
			if (!$this->upload->do_upload('lesson_file')) {
				$all_file_uploaded = false;
				break;
			}

		}

		$data['lesson_file'] = '["' . implode('","', $total_lesson_files) . '"]';
		$data['last_modified'] = date("Y-m-d H:i:s");

		if ($all_file_uploaded) {
			if ($this->lesson_model->update_lesson($lesson_id, $data)) {
				/*User Logger info*/

				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('lesson_file_upload') //Entry type like, Post, Page, Entry
				 ->id($lesson_id) //Entry ID
				 ->token('DELETE') //Token identify Action
				 ->comment($this->session->userdata('name'). ' add lesson file.')
				 ->log(); //Add Database Entry




				$this->session->set_flashdata('section_update_success', 'Lesson file uploaded successfully.');
			} else {
				$this->session->set_flashdata('section_update_error', 'Failed to upload file!');
			}
		} else {
			$this->session->set_flashdata('section_update_error', $this->upload->display_errors());
		}

		redirect(base_url('course/curriculum/' . $course_id));

	}

	public function remove_lesson_file($lesson_key) {
		$lesson_details = $this->lesson_model->get_lesson_list(array('lesson_file', 'course_id'), 'OBJECT', array('id' => $_GET['lesson_id']))[0];
		$lesson_files = json_decode($lesson_details->lesson_file);

		foreach ($lesson_files as $key => $lesson) {
			if ($lesson_key == $key) {
				unset($lesson_files[$key]);
				$path = constant('FILE_SERVER_ROOT_DIR') . '/lesson_files/' . $lesson;
				unlink($path);
				break;
			}
		}

		if (count($lesson_files) > 0) {
			$data['lesson_file'] = '["' . implode('","', $lesson_files) . '"]';
		} else {
			$data['lesson_file'] = NULL;
		}
		$data['last_modified'] = date("Y-m-d H:i:s");

		if ($this->lesson_model->update_lesson($_GET['lesson_id'], $data)) {
			/*User Logger info*/

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('remove_lesson_file') //Entry type like, Post, Page, Entry
			 ->id($_GET['lesson_id']) //Entry ID
			 ->token('DELETE') //Token identify Action
			 ->comment($this->session->userdata('name'). ' remove lesson file.')
			 ->log(); //Add Database Entry




			$this->session->set_flashdata('section_update_success', 'Lesson file removed successfully.');
		} else {
			$this->session->set_flashdata('section_update_error', 'Failed to removed file!');
		}

		redirect(base_url('course/curriculum/' . $lesson_details->course_id));

	}

	public function download_lesson_file($file_name) {
		$this->load->helper('download');

		if ($file_name) {
			$file = constant('FILE_SERVER_ROOT_DIR') . '/lesson_files/' . $file_name;
			// check file exists
			if (file_exists($file)) {
				// get file content
				$data = file_get_contents($file);

			

				//force download
				force_download($file_name, $data);
			}
		} else {
			redirect(base_url('course/curriculum/' . $_GET['course_id']));

		}
	}

	/*
		    * Controller - Get all coupon
	*/

	public function coupon() {

		// if (!has_role($this->session->userdata('user_id'), 'COUPON_READ')) {
		// 	redirect(base_url('page_not_found'));
		// }

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
			            <span aria-hidden="true">&times;</span>
			            </button>', '</div>');
		if ($this->form_validation->run('coupon_form') == FALSE) {
			$this->page_data['page_name'] = "coupon";
			$this->page_data['page_title'] = "Coupon";
			$this->page_data['page_view'] = "coupon/index";

			$this->page_data['coupon_list'] = $this->course_model->get_coupon_list(
				"OBJECT",
				array(
					'coupon' => array('coupon_code', 'discount_type', 'discount', 'start_date', 'end_date', 'status', 'id', 'coupon_limit'),
					'course' => array('title'),
				),
				NULL
			);

			$this->page_data['course_list'] = $this->course_model->get_course_list(
				"OBJECT",
				array('id', 'title')
			);
			$this->load->view('index', $this->page_data);
		} else {
			$this->service_course->save_coupon();
		}

		// debug($this->page_data['coupon_list']);
		// exit;

	}

	/*
		*   Controller - for showing media page
	*/
	public function announcement($course_id = null) {
		if (!has_role($this->session->userdata('user_id'), 'ANNOUNCEMENT_READ')) {
			redirect(base_url('page_not_found'));
		}

		if ($course_id == null || $this->course_model->get_course_list(
			"COUNT",
			array('id', 'title'),
			array('id' => $course_id)
		) == 0) {
			redirect(base_url('home/page_not_found'));
		}

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
			            <span aria-hidden="true">&times;</span>
			            </button>', '</div>');
		if ($this->form_validation->run('announcement_form') == FALSE) {


			$this->page_data['page_name'] = "announcement";
			$this->page_data['page_view'] = "course/index";

			$this->page_data['announcement_list'] = $this->course_model->get_all_announcement(
				'OBJECT',
				 array(
				 	'announcement' => array( 'id', 'title', 'description', 'instructor_id',  'created_at')),
	 			array('course_id' => $course_id)
			);

			$this->page_data['course_info'] = $this->course_model->get_course_list(
				"OBJECT",
				array('id', 'title'),
				array('id' => $course_id)
			)[0];
			$this->page_data['page_title'] = $this->page_data['course_info']->title;
			$this->page_data['sub_page_view'] = "announcement";
			$this->load->view('index', $this->page_data);

		}else{
			$this->service_course->save_announcement($course_id);
		}
	}


	/*
		* Service  - for remove quiz set
	*/
	public function remove_announcement_from_course($course_id, $announcement_id) {
		if (has_role($this->session->userdata('user_id'), 'ANNOUNCEMENT_DELETE')) {
			if ($announcement_id != null) {
				$result = $this->course_model->announcement_delete($announcement_id);
				if ($result['success']) {

					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('remove_announcement_from_course') //Entry type like, Post, Page, Entry
					 ->id($course_id. ' '. $announcement_id ) //Entry ID
					 ->token('DELETE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' removed announcement.')
					 ->log(); //Add Database Entry


					$this->session->set_flashdata('announcement_success', 'Announcement removed successfully.');
				} else {
					$this->session->set_flashdata('announcement_failed', 'Announcement failed to remove.');
				}
			}
			redirect(base_url('course/announcement/' . $course_id));
		} else {
			redirect(base_url('page_not_found'));
		}
	}


	public function course_review($course_id = null){
		if(!has_role($this->session->userdata('user_id'), 'REVIEW_UPDATE')){
			redirect(base_url('page_not_found'));
		}

		if(isset($_GET['notify_id'])){
			$this->user_model->update_user_notification($_GET['notify_id']);
		}

		
		$this->page_data['page_name'] = "course_review";
		$this->page_data['page_title'] = "Course Review";

		$this->page_data['page_view'] = "course/index";

	
			

			$this->page_data['course_review_list'] = $this->course_model->get_course_review_list(
				"OBJECT",
				array(
					'course_review' => array('id', 'status', 'rating', 'review', 'created_at'),
					'user' => array('first_name', 'last_name', 'email'),
					'course' => array('id as course_id')
				),
				array('course_id' => $course_id),
				NULL,
				null
			);
			$this->page_data['course_info'] = $this->course_model->get_course_list(
				"OBJECT",
				array('id', 'title'),
				array('id'=>$course_id)
			)[0];

		$this->page_data['page_title'] = $this->page_data['course_info']->title;

		$this->page_data['sub_page_view'] = "course_review";
		$this->load->view('index', $this->page_data);
	}


	function update_course_review($review_id , $status, $course_id){
	
		if(!has_role($this->session->userdata('user_id'), 'REVIEW_UPDATE')){
			redirect(base_url('page_not_found'));
		}
			if($status == 0){
				$data['status'] = 1;
			}else{
				$data['status'] = 0;
			}
			
			if ($review_id != null) {
				$result = $this->course_model->update_course_review($review_id, $data);
				if ($result['success']) {

					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('update course review ') //Entry type like, Post, Page, Entry
					 ->id( $review_id ) //Entry ID
					 ->token('UPDATE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' update this course review.')
					 ->log(); //Add Database Entry


					$this->session->set_flashdata('review_success', 'Review updated successfully.');
				} else {
					$this->session->set_flashdata('review_failed', 'Review failed to update.');
				}
			}
			redirect(base_url('course/course_review/' . $course_id));
		// } else {
		// 	redirect(base_url('page_not_found'));
		// }


	}


}