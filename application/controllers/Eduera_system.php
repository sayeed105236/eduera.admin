<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eduera_system extends CI_Controller {

	private $page_data = [];

	public function __construct() {
		parent::__construct();
		$this->load->library('session');

		$this->page_data['system_name'] = get_settings('system_name');

		if ($this->session->userdata('user_type') !== "ADMIN" && $this->session->userdata('user_type') !== "SUPER_ADMIN") {
			redirect(site_url('login'), 'refresh');
		}
		$this->load->library('logger');
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
	}

	public function index() {
		$this->basic();
	}

	public function basic() {
		$this->page_data['question'] = array();
		$this->page_data['page_name'] = "eduera_system_basic";
		$this->page_data['page_view'] = "eduera_system/index";
		$this->page_data['page_title'] = "System basic settings";

		$this->page_data['basic_info'] = $this->crud_model->get_settings_info(
			array('vat', 'tax','processing_fee','advertisement', 'address', 'phone', 'contact_email'));

		$this->page_data['sub_page_view'] = "basic";
		$this->load->view('index', $this->page_data);
	}

	public function seo() {
		$this->page_data['question'] = array();
		$this->page_data['page_name'] = "eduera_system_seo";
		$this->page_data['page_view'] = "eduera_system/index";
		$this->page_data['page_title'] = "System seo settings";
		$this->page_data['sub_page_view'] = "seo";
		$this->page_data['seo_info'] = $this->crud_model->get_settings_info(array('seo_title', 'meta_description', 'meta_tags'));

		$this->load->view('index', $this->page_data);
	}

	public function save_sytem_seo_info() {
		$data['seo_title'] = html_escape($this->input->post('seo_title'));
		$data['meta_description'] = html_escape($this->input->post('meta_description'));
		$data['meta_tags'] = html_escape($this->input->post('meta_tags'));

		if ($this->crud_model->update_settings($data)) {

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('save_sytem_seo_info') //Entry type like, Post, Page, Entry
			 ->id(0) //Entry ID
			 ->token('UPDATE') //Token identify Action
			 ->comment($this->session->userdata('name'). ' save system seo information.')
			 ->log(); //Add Database Entry

			$this->session->set_flashdata('update_settings_success', 'Updated successfully.');
		} else {
			$this->session->set_flashdata('update_settings_error', 'Failed to update.');
		}

		redirect(base_url('eduera_system/seo'));
	}

	public function save_sytem_basic_info() {
		$data['tax'] = html_escape($this->input->post('tax'));
		$data['vat'] = html_escape($this->input->post('vat'));
		$data['processing_fee'] = html_escape($this->input->post('processing_fee'));
		$data['advertisement'] = html_escape($this->input->post('advertisement'));
		$data['address'] = html_escape($this->input->post('address'));
		$data['phone'] = html_escape($this->input->post('phone'));
		$data['contact_email'] = html_escape($this->input->post('contact_email'));

		if ($this->crud_model->update_settings($data)) {

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('save_sytem_basic_info') //Entry type like, Post, Page, Entry
			 ->id(0) //Entry ID
			 ->token('UPDATE') //Token identify Action
			 ->comment($this->session->userdata('name'). ' save system basic information.')
			 ->log(); //Add Database Entry

			$this->session->set_flashdata('update_settings_success', 'Updated successfully.');
		} else {
			$this->session->set_flashdata('update_settings_error', 'Failed to update.');
		}

		redirect(base_url('eduera_system/basic'));
	}

	public function email() {
		$this->page_data['question'] = array();
		$this->page_data['page_name'] = "eduera_system_email";
		$this->page_data['page_view'] = "eduera_system/index";
		$this->page_data['page_title'] = "System basic settings";
		$this->page_data['sub_page_view'] = "email";

		$this->page_data['email_info'] = $this->crud_model->get_settings_info(
			array('system_email', 'protocol', 'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass'));

		$this->load->view('index', $this->page_data);
	}

	public function save_sytem_email_info() {
		$data['system_email'] = html_escape($this->input->post('system_email'));
		$data['protocol'] = html_escape($this->input->post('protocol'));
		$data['smtp_host'] = html_escape($this->input->post('smtp_host'));
		$data['smtp_port'] = html_escape($this->input->post('smtp_port'));
		$data['smtp_user'] = html_escape($this->input->post('smtp_user'));
		$data['smtp_pass'] = html_escape($this->input->post('smtp_pass'));

		if ($this->crud_model->update_settings($data)) {

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('save_sytem_email_info') //Entry type like, Post, Page, Entry
			 ->id(0) //Entry ID
			 ->token('UPDATE') //Token identify Action
			 ->comment($this->session->userdata('name'). ' save system email information.')
			 ->log(); //Add Database Entry

			$this->session->set_flashdata('update_settings_success', 'Updated successfully.');
		} else {
			$this->session->set_flashdata('update_settings_error', 'Failed to update.');
		}

		redirect(base_url('eduera_system/email'));
	}

	public function home_page_setting() {
		// $this->page_data['question'] = array();
		$this->page_data['page_name'] = "home_page_setting";
		$this->page_data['page_view'] = "eduera_system/index";
		$this->page_data['page_title'] = "Home page settings";
		$this->page_data['sub_page_view'] = "home_page_setting";

		$this->page_data['home_page_setting'] = $this->crud_model->home_page_setting();
		// debug($this->page_data['home_page_setting']);
		$this->load->view('index', $this->page_data);
	}

	public function home_page_setting_update(){

		$data['name'] = $this->input->post('name');
		$data['rank'] = $this->input->post('rank');
		$id = $this->input->post('home_page_setting_id');
	
		if($id){
			if($this->crud_model->insert_and_update__homePage($data, $id)){

				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('update_home_page_setting') //Entry type like, Post, Page, Entry
				 ->id(0) //Entry ID
				 ->token('UPDATE') //Token identify Action
				 ->comment($this->session->userdata('name'). ' update home page section.')
				 ->log(); //Add Database Entry

				$this->session->set_flashdata('update_settings_success', 'UPdated new section for home page successfully.');
			}else{
				$this->session->set_flashdata('update_settings_error', 'Failed to update.');
			}


		}else{
			if($this->crud_model->insert_and_update__homePage($data)){

				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('save_home_page_setting') //Entry type like, Post, Page, Entry
				 ->id(0) //Entry ID
				 ->token('INSERT') //Token identify Action
				 ->comment($this->session->userdata('name'). ' save home page section.')
				 ->log(); //Add Database Entry

				$this->session->set_flashdata('update_settings_success', 'Save new section for home page successfully.');
			}else{
				$this->session->set_flashdata('update_settings_error', 'Failed to save.');
			}
		}
		redirect(base_url('eduera_system/home_page_setting'));
	}


	public function frontend_setting(){
		// $this->page_data['question'] = array();
		$this->page_data['page_name'] = "frontend_setting";
		$this->page_data['page_view'] = "eduera_system/index";
		$this->page_data['page_title'] = "Frontend Settings";
		$this->page_data['sub_page_view'] = "frontend_setting";

		$this->page_data['frontend_setting'] = $this->crud_model->get_frontend_settings_info(array('about_us', 'terms_and_condition', 'privacy_policy'));
				

		// debug($this->page_data['frontend_setting']);
		$this->load->view('index', $this->page_data);
	}

	public function save_frontend_settings(){

		$data['about_us'] = $this->input->post('about_us');
		$data['privacy_policy'] = $this->input->post('privacy_policy');
		$data['terms_and_condition'] = $this->input->post('terms_and_condition');

		// debug($this->crud_model->update_settings($data));
		// exit;

		if ($this->crud_model->update_frontend_settings($data)) {

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('save_frontend_settings_info') //Entry type like, Post, Page, Entry
			 ->id(0) //Entry ID
			 ->token('UPDATE') //Token identify Action
			 ->comment($this->session->userdata('name'). ' save frontend setting information.')
			 ->log(); //Add Database Entry

			$this->session->set_flashdata('update_settings_success', 'Updated successfully.');
		} else {
			$this->session->set_flashdata('update_settings_error', 'Failed to update.');
		}

		redirect(base_url('eduera_system/frontend_setting'));
	}
}