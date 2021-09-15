<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_course extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/*
		    *   Service - for updateing basic course info
	*/
	


		public function save_lesson() {


			$this->load->model('service/service_vimeo', 'service_vimeo');
			$lesson_id = html_escape($this->input->post('lesson_id'));
			$data['course_id'] = html_escape($this->input->post('course_id'));
			$data['section_id'] = html_escape($this->input->post('section_id'));
			$data['title'] = html_escape($this->input->post('title'));
			$data['rank'] = html_escape($this->input->post('order'));
			$data['summary'] = html_escape($this->input->post('summary'));
			$data['vimeo_id'] = html_escape($this->input->post('vimeo_id'));
			$data['video_type'] = html_escape($this->input->post('video_type'));
			
			$preview = html_escape($this->input->post('preview'));
			$youtube_video_url = html_escape($this->input->post('youtube_video_url'));
	
			$data['video_id'] = $this->service_vimeo->get_youtube_video_id($youtube_video_url);
			
			if($data['vimeo_id'] != NULL){

				$data['duration_in_second'] = $this->service_vimeo->get_video_duration($data['vimeo_id']);
			}else{

				$data['duration_in_second'] = $this->service_vimeo->get_youtube_video_duration($youtube_video_url);
			}


			
			$lesson_count = $this->lesson_model->get_lesson_list(
				array('id'),
				'COUNT',
				array('course_id' => $data['course_id'])
			);

			$preview_count = $this->lesson_model->get_lesson_list(
				array('id'),
				'COUNT',
				array('preview' => 1, 'course_id' => $data['course_id'])
			);

			$preview_content_count = round($lesson_count*50/100);
			
	// 		if($preview != NULL || $preview != ''){
	// 			$data['preview'] = 1;
	// 		}else{
	// 			$data['preview'] = 0;
	// 		}


	// 		if($preview_content_count <= $preview_count){
	// 			$data['preview'] = 0;
	// 			$this->session->set_flashdata('section_update_error', 'Already previewed 50% content.');

	// 		}else{
	// 			$data['preview'] = 1;
	// 		}

	        if($preview != NULL || $preview != ''){
				// $data['preview'] = 1;

				if($preview_content_count <= $preview_count){
					$data['preview'] = 0;
					$this->session->set_flashdata('section_update_error', 'Already previewed 50% content.');
					redirect(base_url('course/curriculum/' . $data['course_id']));
				}else{
					$data['preview'] = 1;
				}
				
			}else{
				$data['preview'] = 0;
			}

			// debug($data);
			// exit;
			if ($data['duration_in_second']['error']) {
				$this->session->set_flashdata('section_update_error', $data['duration_in_second']['error']);
			} else {
				if ($lesson_id === "") {
					$data['created_at'] = date("Y-m-d H:i:s");
				
					$result = $this->lesson_model->insert_lesson($data);
					if ($result) {

						/*User Logger info*/

						$this->logger
						 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
						 ->user_details($this->user_model->getUserInfoByIpAddress())
						 ->type('save_lesson') //Entry type like, Post, Page, Entry
						 ->id($result) //Entry ID
						 ->token('CREATE') //Token identify Action
						 ->comment($this->session->userdata('name'). ' create new lesson.')
						 ->log(); //Add Database Entry




						$this->session->set_flashdata('section_update_success', 'Lesson is inserted successfully.');
					} else {
						$this->session->set_flashdata('section_update_error', 'Failed to insert!');
					}
				} else {
					$data['last_modified'] = date("Y-m-d H:i:s");
					if ($this->lesson_model->update_lesson($lesson_id, $data)) {
						/*User Logger info*/

						$this->logger
						 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
						 ->user_details($this->user_model->getUserInfoByIpAddress())
						 ->type('save_lesson') //Entry type like, Post, Page, Entry
						 ->id($lesson_id) //Entry ID
						 ->token('UPDATE') //Token identify Action
						 ->comment($this->session->userdata('name'). ' update lesson info.')
						 ->log(); //Add Database Entry


						$this->session->set_flashdata('section_update_success', 'Lesson is updated successfully.');
					} else {
						$this->session->set_flashdata('section_update_error', 'Failed to update!');
					}
				}
			}

			redirect(base_url('course/curriculum/' . $data['course_id']));
		}

	/*
		    *   Service - for updateing basic course info
	*/
	public function save_course_info($course_id) {
		$this->load->model('service/service_vimeo', 'service_vimeo');
		$data['title'] = trim(html_escape($this->input->post('title')));
		$data['short_description'] = html_escape($this->input->post('short_description'));
		$data['description'] = html_escape($this->input->post('description'));
		$data['language'] = html_escape($this->input->post('language'));
		$data['level'] = $this->input->post('level');
		$data['status'] = $this->input->post('status');
		$data['preview_video_id'] = $this->input->post('preview_video_id');
		$data['price'] = $this->input->post('price');
		$data['discounted_price'] = $this->input->post('discounted_price');
		$data['send_greeting_mail'] = $this->input->post('send_greeting_mail');
		$data['certification_course'] = $this->input->post('certification_course');
		$data['mock_test'] = $this->input->post('mock_test');
		
		$data['expiry_month'] = $this->input->post('expiry_month');

		if($data['expiry_month'] == ""){
			$data['expiry_month'] = NULL;
		}
		$rank = $this->input->post('rank');
		if ($data['discounted_price'] > 0) {
			$data['discount_flag'] = 1;

		} else {
			if($data['price'] == 0 && $data['discounted_price'] == 0){
				$data['discount_flag'] = 1;
			}else{
				$data['discount_flag'] = 0;
			}

			

		}

		
		$category = $this->input->post('category');
		$sub_category = $this->input->post('sub_category');
		$duration_in_second = $this->service_vimeo->get_video_duration($data['preview_video_id']);

		/*Update Ordering*/

		$updatedOrder =	 $this->course_model->get_course_list(
				"OBJECT",
				array('id', 'rank'),
				array('rank' => $rank)
			)[0];

		$currentCourseOrder = $this->course_model->get_course_list(
				"OBJECT",
				array('id', 'rank'),
				array('id'=>$course_id)
			)[0];

		$data['rank'] = $rank;
		$updatedCourseOrder['rank'] = $currentCourseOrder->rank;
		

		if ($duration_in_second['error']) {
			$this->session->set_flashdata('course_info_update_error', $duration_in_second['error']);
		} else {
			if ($this->course_model->get_course_list("COUNT", array('id'), array('id' => $course_id, 'title' => $data['title'])) == 0) {
				$base_slug = string_to_slug($data['title']);
				$slug = $base_slug;
				$slug_counter = 1;

				while ($this->course_model->get_course_list("COUNT", array('id'), array('slug' => $slug)) > 0) {
					$slug = $base_slug . '-' . $slug_counter;
					$slug_counter++;
				}

				// $data['slug'] = $slug;
			}

			if ($sub_category === "-1") {
				$data['category_id'] = $category;
			} else {
				$data['category_id'] = $sub_category;
			}

			if ($this->course_model->update_course_info($course_id, $data)) {
				$this->course_model->update_course_info($updatedOrder->id, $updatedCourseOrder);

				/*User Logger info*/

				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('save_course_info') //Entry type like, Post, Page, Entry
				 ->id($course_id) //Entry ID
				 ->token('UPDATE') //Token identify Action
				 ->comment($this->session->userdata('name'). ' updated course info.')
				 ->log(); //Add Database Entry


				$this->session->set_flashdata('course_info_update_success', 'Course info is Successfully Updated.');
			} else {
				$this->session->set_flashdata('course_info_update_error', 'Failed to update course info!');
			}
		}

		redirect(base_url('course/info/' . $course_id));

	}

	/*
		    *   Service - for updateing section info
	*/
	public function save_section() {

		$id = html_escape($this->input->post('id'));
		$data['course_id'] = html_escape($this->input->post('course_id'));
		$data['title'] = html_escape($this->input->post('title'));
		$data['rank'] = html_escape($this->input->post('order'));

		if ($id === "") {
			$result = $this->section_model->insert_section($data);
			if ($result) {
				if ($result['success']) {
					/*User Logger info*/

					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('save_section') //Entry type like, Post, Page, Entry
					 ->id($result['id']) //Entry ID
					 ->token('CREATE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' create new section.')
					 ->log(); //Add Database Entry



					$this->session->set_flashdata('section_update_success', 'Section is inserted successfully.');
				} else {
					$this->session->set_flashdata('section_update_error', $result['message']);
				}
			} else {
				$this->session->set_flashdata('section_update_error', 'Failed to insert!');
			}
		} else {
			if ($this->section_model->update_section($id, $data)) {
				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('save_section') //Entry type like, Post, Page, Entry
				 ->id($id) //Entry ID
				 ->token('UPDATE') //Token identify Action
				 ->comment($this->session->userdata('name'). ' update section info.')
				 ->log(); //Add Database Entry


				$this->session->set_flashdata('section_update_success', 'Section is updated successfully.');
			} else {
				$this->session->set_flashdata('section_update_error', 'Failed to update!');
			}
		}
		redirect(base_url('course/curriculum/' . $data['course_id']));
	}

	/*
		    *   Service - for save quiz set info
	*/
	public function save_quiz_set($course_id) {
		$quiz_set_id = html_escape($this->input->post('quiz_set_id'));
		$data['course_id'] = $course_id;
		$data['name'] = html_escape($this->input->post('name'));
		$data['type'] = html_escape($this->input->post('type'));
		$data['duration'] = html_escape($this->input->post('duration'));
		$free_access = html_escape($this->input->post('free_access'));
		$quiz_result = html_escape($this->input->post('quiz_result'));
		if($quiz_result != NULL || $quiz_result != ''){
			$data['quiz_result'] = 1;
		}else{
			$data['quiz_result'] = 0;
		}

		if($free_access != NULL || $free_access != ''){
			$data['free_access'] = 1;
		}else{
			$data['free_access'] = 0;
		}
		
		
		if (html_escape($this->input->post('option')) == 'lesson') {
			$data['lesson_id'] = html_escape($this->input->post('lesson_id'));
		} else {
			$data['lesson_id'] = NULL;
		}
		// $question_id_list = html_escape($this->input->post('question_id_list'));
		// $questions = explode(',', $question_id_list);
		// $data['question_id_list'] = '["' . implode('","', $questions) . '"]';

		
		if ($quiz_set_id != null && $quiz_set_id != '') {
			if (has_role($this->session->userdata('user_id'), 'QUIZ_UPDATE')) {
				$result = $this->quiz_model->insert_set($data, $quiz_set_id);
			
				if ($result) {
					if ($result['success']) {

						$this->logger
						 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
						 ->user_details($this->user_model->getUserInfoByIpAddress())
						 ->type('save_quiz_set') //Entry type like, Post, Page, Entry
						 ->id($quiz_set_id) //Entry ID
						 ->token('UPDATE') //Token identify Action
						 ->comment($this->session->userdata('name'). ' updated quiz set.')
						 ->log(); //Add Database Entry



						$this->session->set_flashdata('quiz_set_save_success', 'Quiz set is updated successfully.');
					} else {
						$this->session->set_flashdata('quiz_set_save_failed', $result['message']);
					}
				} else {
					$this->session->set_flashdata('quiz_set_save_failed', 'Failed to updated!');
				}
			} else {
				redirect(base_url('page_not_found'));
			}

		} else {
			if (has_role($this->session->userdata('user_id'), 'QUIZ_CREATE')) {
				$result = $this->quiz_model->insert_set($data);
				if ($result) {
					if ($result['success']) {


						$this->logger
						 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
						 ->user_details($this->user_model->getUserInfoByIpAddress())
						 ->type('save_quiz_set') //Entry type like, Post, Page, Entry
						 ->id($result['id']) //Entry ID
						 ->token('CREATE') //Token identify Action
						 ->comment($this->session->userdata('name'). ' create new quiz set.')
						 ->log(); //Add Database Entry





						$this->session->set_flashdata('quiz_set_save_success', 'Quiz set is inserted successfully.');
					} else {
						$this->session->set_flashdata('quiz_set_save_failed', $result['message']);
					}
				} else {
					$this->session->set_flashdata('quiz_set_save_failed', 'Failed to insert!');
				}
			} else {
				redirect(base_url('page_not_found'));
			}
		}

		redirect(base_url('course/quiz_set/' . $course_id));
	}

	public function upload_photo($course_id) {

		// echo getcwd(). "<br />";
		// echo basename(__DIR__). "<br />";
		// echo dirname( __FILE__ ). "<br />";
		// echo __FILE__. "<br />";
		// echo __DIR__. "<br />";
		$upload_path = $_SERVER['DOCUMENT_ROOT'] . "/photo_server_2/course/thumbnail/";

		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'jpg|png|jpeg';
		$config['max_size'] = 2048;
		$config['max_width'] = 1920;
		$config['max_height'] = 1080;
		$config['file_name'] = 'thumb_' . md5($course_id);
		$config['overwrite'] = true;

		// debug($config);
		// exit;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('course_thumbnail')) {
			$this->session->set_flashdata('photo_upload_error', $this->upload->display_errors());
		} else {

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('upload_photo') //Entry type like, Post, Page, Entry
			 ->id($course_id) //Entry ID
			 ->token('CREATE') //Token identify Action
			 ->comment($this->session->userdata('name'). ' upload the course photo.')
			 ->log(); //Add Database Entry



			$this->session->set_flashdata('photo_upload_success', "Photo uploaded successfully!");
		}

		redirect(base_url('course/media/' . $course_id));
	}

/*Save coupon info*/

	public function save_coupon() {
		$coupon_id = html_escape($this->input->post('coupon_id'));
		$data['course_id'] = html_escape($this->input->post('course_id'));
		$data['coupon_code'] = html_escape($this->input->post('coupon_code'));
		$data['start_date'] = html_escape($this->input->post('start_date'));
		$data['end_date'] = html_escape($this->input->post('end_date'));
		$data['discount_type'] = html_escape($this->input->post('discount_type'));
		$data['discount'] = html_escape($this->input->post('discount'));
		$data['status'] = html_escape($this->input->post('status'));
		$data['coupon_limit'] = html_escape($this->input->post('coupon_limit'));

		if ($data['discount_type'] == 'percentage') {
			if ($data['discount'] > 100) {
				$this->session->set_flashdata('coupon_update_error', "Please set the discount value below 100 !");
			} else if ($data['discount'] <= 0) {
				$this->session->set_flashdata('coupon_update_error', "Please set the discount value above 0 !");
			}
		}

		$d1 = strtotime($data['start_date']);
		$d2 = strtotime($data['end_date']);
		// debug($d1);

		if ($d1 > $d2) {
			$this->session->set_flashdata('coupon_update_error', "First date comes before the second date !");
			redirect(base_url('course/coupon/'));
		}

		if ($coupon_id) {
			if ($this->course_model->update_coupon($coupon_id, $data)['success']) {
				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('save_coupon') //Entry type like, Post, Page, Entry
				 ->id($coupon_id) //Entry ID
				 ->token('UPDATE') //Token identify Action
				 ->comment($this->session->userdata('name'). ' updated coupon info.')
				 ->log(); //Add Database Entry


				$this->session->set_flashdata('coupon_update_success', 'Saved Successfully coupon info updated.');
			} else {
				$this->session->set_flashdata('coupon_update_error', 'Failed to updated!');
			}
		} else {
			$course_id = $this->course_model->get_coupon_list(
				"OBJECT",
				array('coupon' => array('status')),
				NULL,
				array("course_id" => $data['course_id'])
			)[0];

			// if (count($course_id) > 0 && $course_id->status == 1) {
			// 	$this->session->set_flashdata('coupon_update_error', 'Already One coupon exists for this course');
			// } else {
				if ($this->course_model->insert_coupon($data)['success']) {
					/*User Logger info*/

					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('save_coupon') //Entry type like, Post, Page, Entry
					 ->id($result['id']) //Entry ID
					 ->token('CREATE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' create new coupon.')
					 ->log(); //Add Database Entry



					$this->session->set_flashdata('coupon_update_success', 'Saved Successfully new coupon');
				} else {
					$this->session->set_flashdata('coupon_update_error', 'Failed to save!');
				}
			// }
		}

		redirect(base_url('course/coupon/'));

	}


	/*
			    *   Service - for save announcement info
		*/
		public function save_announcement($course_id) {

			$id = html_escape($this->input->post('announcement_id'));
			$data['title'] = html_escape($this->input->post('title'));
			$data['description'] = html_escape($this->input->post('description'));
			$data['instructor_id'] = $this->session->userdata('user_id');
			 $data['course_id'] = $course_id;


		 	
			 // debug($id);
			 // debug($data);
			 // exit;
			// redirect($_SERVER['HTTP_REFERER']);

			if ($id === "" || $id === NULL) {
				$result = $this->course_model->insert_and_update__announcement( $data);
				if ($result) {
					if ($result['success']) {

						/*User Logger info*/

						$this->logger
						 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
						 ->user_details($this->user_model->getUserInfoByIpAddress())
						 ->type('save_announcement') //Entry type like, Post, Page, Entry
						 ->id($result['id']) //Entry ID
						 ->token('CREATE') //Token identify Action
						 ->comment($this->session->userdata('name'). ' create new announcement.')
						 ->log(); //Add Database Entry

						$this->session->set_flashdata('announcement_success', 'Anouncement save successfully.');
					} else {
						$this->session->set_flashdata('announcement_failed', $result['message']);
					}
				} else {
					$this->session->set_flashdata('announcement_failed', 'Anouncement failed to insert!');
				}
			} else {
				if ($this->course_model->insert_and_update__announcement($data, $id)) {


					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('save_announcement') //Entry type like, Post, Page, Entry
					 ->id($id) //Entry ID
					 ->token('UPDATE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' update the announcement.')
					 ->log(); //Add Database Entry


					$this->session->set_flashdata('announcement_success', 'Anouncement is updated successfully.');
				} else {
					$this->session->set_flashdata('announcement_failed', 'Failed to update!');
				}
			}
			redirect(base_url('course/announcement/' . $course_id));
		}



		


}