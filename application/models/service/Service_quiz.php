<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_quiz extends CI_Model {

	function __construct() {
		parent::__construct();

		$this->load->helper('file');
	}

	/*
		    * Servie -- Save Question
	*/

	public function save_question_for_course($course_id) {

		$id = html_escape($this->input->post('id'));
	
		$data['course_id'] = html_escape($this->input->post('course_id'));
		$data['question'] = html_escape($this->input->post('question'));
		// $data['question_img'] = html_escape($this->input->post('question_im'));
		$data['explanation'] = html_escape($this->input->post('explanation'));
		$option_list = html_escape($this->input->post('option'));

		$data['right_option_value'] = html_escape($this->input->post('right_option'));
		$data['option_list'] = '["' . implode('","', $option_list) . '"]';
		
		$question_img = basename($_FILES["question_img"]["name"]);
	

		/* Question Image Upload*/
		if(constant('EDUERA_ROOT_DIR') == "/home1/edueracom/public_html/"){
			$config['upload_path'] = constant('EDUERA_ROOT_DIR').'uploads/question_images/';

		}else{
			$config['upload_path'] = '/var/www/html/eduera/'.'uploads/question_images/';

		}

		if ($id) {
		
			$question = $this->quiz_model->get_question_by_id(
				'OBJECT',
				array('question_img'),
				array('id' => $id)
			)[0];
		
			if(isset($question_img)){
				$config['allowed_types'] = 'jpg|JPG|JPEG|jpeg|PNG|png';
				$config['file_name'] = $id.'.jpg';
				$config['max_size'] = 512;
				$config['overwrite'] = TRUE;
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('question_img')) {
					
					$this->session->set_flashdata('question_images_upload_message', $this->upload->display_errors());
				} else {
					
					$result = $this->course_model->upload_question_photo($id, array("question_img" => $this->upload->data()['file_name']));
					// unlink($config['upload_path'].$question->question_img);

					if ($result) {
						
						$this->session->set_flashdata('question_image_upload_message', "Question photo uploaded successfully!");
					} else {
						
						$this->session->set_flashdata('question_image_upload_message', "Failed to save in database!");
					}
				}

			}		
		

			// $data['question_img'] = $question->question_img;
			if (has_role($this->session->userdata('user_id'), 'QUESTION_UPDATE')) {
				$result = $this->quiz_model->insert_question($data, $id);
				if ($result) {
					if ($result['success']) {

					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('save_question_for_course') //Entry type like, Post, Page, Entry
					 ->id($id) //Entry ID
					 ->token('UPDATE') //Token identify Action
					 ->comment($this->session->userdata('name'). '  updated question info.')
					 ->log(); //Add Database Entry



						$this->session->set_flashdata('course_question_save_success', "Question Updated Successfully");
					} else {
						$this->session->set_flashdata('course_question_save_failed', $result['message']);
					}
				} else {
					$this->session->set_flashdata('course_question_save_failed', 'Failed to updated!');
				}
			} else {
				redirect(base_url('page_not_found'));
			}

		} else {
			if (has_role($this->session->userdata('user_id'), 'QUESTION_CREATE')) {
				$result = $this->quiz_model->insert_question($data);
				if ($result) {
				
					$config['allowed_types'] = 'jpg|JPG|JPEG|jpeg|PNG|png';
					$config['file_name'] = $result['id'].'.jpg';
					$config['max_size'] = 512;
					$config['overwrite'] = TRUE;
				
					$this->load->library('upload', $config);
					
					if (!$this->upload->do_upload('question_img')) {
					
						$this->session->set_flashdata('question_image_upload_message', $this->upload->display_errors());
					} else {
						
						if ($this->course_model->upload_question_photo($result['id'], array("question_img" => $this->upload->data()['file_name']))) {
							

							$this->session->set_flashdata('question_image_upload_message', "Question photo uploaded successfully!");
						} else {
							$this->session->set_flashdata('question_image_upload_message', "Failed to save in database!");
						}
					}


					if ($result['success']) {

						$this->logger
						 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
						 ->user_details($this->user_model->getUserInfoByIpAddress())
						 ->type('save_question_for_course') //Entry type like, Post, Page, Entry
						 ->id($id) //Entry ID
						 ->token('CREATE') //Token identify Action
						 ->comment($this->session->userdata('name'). '  create new question.')
						 ->log(); //Add Database Entry



						$this->session->set_flashdata('course_question_save_success', "Question Save Successfully");
					} else {
						$this->session->set_flashdata('course_question_save_failed', $result['message']);
					}
				} else {
					$this->session->set_flashdata('course_question_save_failed', 'Failed to updated!');
				}
			} else {
				redirect(base_url('page_not_found'));
			}
		}
		redirect(base_url('course/question_bank/' . $course_id));

	}

}