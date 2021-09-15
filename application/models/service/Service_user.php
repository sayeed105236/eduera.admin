<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_user extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/*
		    * Service - save user information
	*/
	public function save_user($function_type, $user_id = null) {

		if ($function_type == "CREATE_USER") {
			$length = 6;
			$data['email'] = html_escape($this->input->post('email'));
			$password = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz$'), 1, $length);
			$data['password'] = sha1($password);
			$data['created_at'] = date("Y-m-d H:i:s");

			if ($this->crud_model->is_duplicate_email($data['email'])) {
				$this->session->set_flashdata('duplicate_email', "Email already taken!!");
				redirect(site_url('users'));
				return;
			}

		} else if ($function_type == "UPDATE_USER") {
			$data['last_modified'] = date("Y-m-d H:i:s");
		}

		$data['first_name'] = html_escape($this->input->post('first_name'));
		$data['last_name'] = html_escape($this->input->post('last_name'));
		$data['phone'] = html_escape($this->input->post('phone'));
		$data['biography'] = html_escape($this->input->post('biography'));
		$data['status'] = html_escape($this->input->post('status'));
		$instructor = html_escape($this->input->post('instructor'));
		if ($instructor) {
			$data['instructor'] = $instructor;
		} else {
			$data['instructor'] = 0;
		}

		/*Membership */
		$membership = html_escape($this->input->post('membership'));
		$member['payment_method'] = "Manually";
		$member['user_id'] = $user_id;
		$member['status'] = "ACCEPTED";

		
		if($membership == 'silver'){

			$member['membership_type'] = "silver";
			$member['amount'] = 2000;
			
		}else if($membership == 'gold'){
			$member['membership_type'] = "gold";
			$member['amount'] = 5000;
		}else if($membership == 'platinum'){
			$member['membership_type'] = "platinum";
			$member['amount'] = 10000;
		}else{
			$member['membership_type'] = "";
			// $member['amount'] = 2000;
		}
		

		


		if ($this->session->userdata('user_type') === "SUPER_ADMIN") {
			$data['user_type'] = html_escape($this->input->post('user_type'));
		} else {
			if ($function_type == "CREATE_USER") {
				$data['user_type'] = 'USER';
			}
		}

		if ($function_type == "CREATE_USER") {
			$user_id = $this->user_model->register_user($data);

			if ($user_id) {
				$done = $this->email_model->send_email_and_password($data['email'], $password);

				if ($done == '1') {
					/*User Logger info save*/
					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('save_user') //Entry type like, Post, Page, Entry
					 ->id($user_id) //Entry ID
					 ->token('CREATE') //Token identify Action
					 ->comment($this->session->userdata('name'). '  created new user.')
					 ->log(); //Add Database Entry



					$this->session->set_flashdata('user_save', "New user created Successfully!!");
				} else {
					if ($this->user_model->delete_user_by_id($user_id)) {
						$this->session->set_flashdata('duplicate_email', "Failed to send email! Please check it it is an real email");
					} else {
						$this->session->set_flashdata('duplicate_email', "Failed to send email! Data was saved in database. Please take a screenshot and contact developers team.");
					}
				}
			} else {
				$this->session->set_flashdata('duplicate_email', "Failed to create!!");
			}

			redirect(site_url('users'));

		} else if ($function_type == "UPDATE_USER") {

			$response = $this->user_model->insert_and_update_membership_payment(null ,$member);

		
			if ($response['success']) {
					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('save_user_membership') //Entry type like, Post, Page, Entry
					 ->id($user_id) //Entry ID
					 ->token('UPDATE') //Token identify Action
					 ->comment($this->session->userdata('name'). ' has been given access to the membership')
					 ->log(); //Add Database Entry
				
			} else {
				$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('failed_to_save_user_membership') //Entry type like, Post, Page, Entry
					 ->id($user_id) //Entry ID
					 ->token('FAILED') //Token identify Action
					 ->comment($this->session->userdata('name'). ' has try to given access to the membership')
					 ->log(); //Add Database Entry
			}


			$data['verification_code'] = NULL;
			if ($this->user_model->update_user_info($user_id, $data)) {

				/*User Logger info save*/
				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('save_user') //Entry type like, Post, Page, Entry
				 ->id($user_id) //Entry ID
				 ->token('UPDATE') //Token identify Action
				  ->comment($this->session->userdata('name'). '  has change user info.')
				 ->log(); //Add Database Entry


				$this->session->set_flashdata('successfully', 'User info is successfully updated');
			} else {
				$this->session->set_flashdata('user_update_error', 'Failed to updated user info');
			}
			redirect(base_url('users/' . $user_id . '/info'));
		}

	}

	/*
		    * Service - Save user course enroll information
	*/
	public function enroll_in_a_course($user_id) {

		$data['course_id'] = html_escape($this->input->post('course_id'));
		$course_data = $this->course_model->get_course_list(
			"OBJECT",
			array('price', 'discounted_price'),
			array("id", $data['course_id'])
		)[0];

		$data['price'] = $course_data->price;
		$data['discounted_price'] = $course_data->discounted_price;
		$data['enrolled_price'] = html_escape($this->input->post('inserted_price'));
		$data['access'] = html_escape($this->input->post('access'));


		$expiry_date = html_escape($this->input->post('expiry_date'));
		
		if($expiry_date != null || $expiry_date != ""){
			// debug($expiry_date);
			// exit;
			$course_list = $this->course_model->get_course_list("OBJECT", array('id',  'expiry_month'), array('id' => $data['course_id']))[0];
			$default_expiry_date = date('Y-m-d', strtotime('-'.$course_list->expiry_month. ' month ago'));
			if($course_list->expiry_month == NULL){
				$data['expiry_date'] = NULL;
			}else{
				$data['expiry_date'] = $default_expiry_date;
			}
			
			
			
		}else{
			$data['expiry_date'] = $expiry_date;
		}
		

		 // date('Y-m-d', strtotime('-'.$expiry_date. ' month ago'));
		
		$enroll_course = null;

		if (html_escape($this->input->post('purpose')) == 'update_enrollment') {
			$enrollment_id = html_escape($this->input->post('enrollment_id'));
			$data['last_modified'] = date("Y-m-d H:i:s");
			$enroll_course = $this->enroll_model->update_enrollment($enrollment_id, $data);
		} else if (html_escape($this->input->post('purpose')) == 'enroll_user') {
			$data['user_id'] = $user_id;
			$data['created_at'] = date("Y-m-d H:i:s");
			$enroll_course = $this->enroll_model->save_enroll_user($data);
		}

		if ($enroll_course) {
			if ($enroll_course['success']) {
				/* logger*/
				if (html_escape($this->input->post('purpose')) == 'update_enrollment') {
					$this->logger
					 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
					 ->user_details($this->user_model->getUserInfoByIpAddress())
					 ->type('enroll_in_a_course') //Entry type like, Post, Page, Entry
					 ->id($this->input->post('enrollment_id')) //Entry ID
					 ->token('UPDATE') //Token identify Action
					 ->comment($this->session->userdata('name'). '  update existing enrolled user.')
					 ->log(); //Add Database Entry
					}else{
						$this->logger
						 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
						 ->user_details($this->user_model->getUserInfoByIpAddress())
						 ->type('enroll_in_a_course') //Entry type like, Post, Page, Entry
						 ->id($enroll_course['enrolled_id']) //Entry ID
						 ->token('CREATE') //Token identify Action
						 ->comment($this->session->userdata('name'). '  enroller a new user.')
						 ->log(); //Add Database Entry

					}


				$this->session->set_flashdata('success', $enroll_course['message']);
			} else {
				$this->session->set_flashdata('danger', $enroll_course['message']);
			}
		} else {
			$this->session->set_flashdata('danger', 'Failed to enroll for unknown error! Please take a screen shot and contact developers team.');
		}
		redirect(site_url('users/' . $user_id . '/user_enrollment'));

	}

	/*
		    * Service - Save user course enroll information
	*/
	public function update_enrollment($user_id) {

		// $data['course_id'] = html_escape($this->input->post('course_id'));
		// $data['enrolled_price'] = html_escape($this->input->post('inserted_price'));
		// $data['access'] = html_escape($this->input->post('access'));

		// $enroll_course = null;

		// echo (html_escape($this->input->post('purpose')));
		// exit;

		// if (html_escape($this->input->post('purpose')) == 'update_enrollment'){
		//     $enrollment_id = html_escape($this->input->post('enrollment_id'));
		//     $data['last_modified'] = date("Y-m-d H:i:s");
		//     $enroll_course  = $this->enroll_model->update_enrollment($enrollment_id, $data);
		// } else if (html_escape($this->input->post('purpose')) == 'enroll_user'){
		//     $data['user_id'] = $user_id;
		//     $data['created_at'] = date("Y-m-d H:i:s");
		//     $enroll_course  = $this->enroll_model->save_enroll_user($data);
		// }

		// if ($enroll_course) {
		//     if ($enroll_course['success']){
		//         $this->session->set_flashdata('success', 'User has been enrolled to the course successfully');
		//     } else {
		//         $this->session->set_flashdata('danger', $enroll_course['message']);
		//     }
		// } else {
		//     $this->session->set_flashdata('danger', 'Failed to enroll for unknown error! Please take a screen shot and contact developers team.');
		// }
		// redirect(site_url('users/' . $user_id . '/user_enrollment'));

	}

	/*
		    * Service - assign an instructor in a course
	*/
	public function assign_an_instructor_in_a_course($instructor_id) {

		$data['instructor_id'] = $instructor_id;
		$course_id = html_escape($this->input->post('course_id'));
		$data['instructor_share'] = html_escape($this->input->post('profit_share'));
		
		if ($this->course_model->update_course_info($course_id, $data)) {

			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('assign_an_instructor_in_a_course') //Entry type like, Post, Page, Entry
			 ->id($course_id) //Entry ID
			 ->token('UPDATE') //Token identify Action
			 ->comment($this->session->userdata('name'). '  assign instructor in a course.')
			 ->log(); //Add Database Entry


			$this->session->set_flashdata('success', 'Assign instructor successfully!');
		} else {
			$this->session->set_flashdata('danger', 'Failed to assign!');
		}
		redirect(site_url('users/' . $instructor_id . '/instructor_enrollment'));

	}

	/*
		    * Service - assign an instructor in a course
	*/
	public function instructor_payment($instructor_id) {

		$data['instructor_id'] = $instructor_id;
		$data['withdraw_amount'] = html_escape($this->input->post('withdraw_amount'));
		$data['payment_left'] = html_escape($this->input->post('payment_left'));

		if($data['withdraw_amount'] > $data['payment_left']){
			$this->session->set_flashdata('danger', 'Withdraw Amount not avialable on this account!');
		}else{

			$result = $this->user_model->instructor_payment_save($data);
			if ($result) {


				$this->logger
				 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
				 ->user_details($this->user_model->getUserInfoByIpAddress())
				 ->type('instructor_payment') //Entry type like, Post, Page, Entry
				 ->id($result['payment_id']) //Entry ID
				 ->token('CREATE') //Token identify Action
				 ->comment($this->session->userdata('name'). '  saved instructor payment.')
				 ->log(); //Add Database Entry


				$this->session->set_flashdata('success', 'Instructor payment save successfully!');
			} else {
				$this->session->set_flashdata('danger', 'Failed to save payment!');
			}
		}

		
		redirect(site_url('users/' . $instructor_id . '/instructor_payment'));

	}

	/*
		    * Service - make payment a user enrolled course
	*/
	public function user_enroll_make_payment($user_id) {

		$data['enrollment_id'] = html_escape($this->input->post('enrollment_id'));
		$data['amount'] = html_escape($this->input->post('amount'));
		$data['payment_method'] = 'Manually';
		$data['status'] = 'Accepted';
		$data['created_at'] = date("Y-m-d H:i:s");
		$result = $this->enroll_model->save_enrollment_user_payment($data);
		
		if ($result['success']) {
			/* logger*/
			$this->logger
			 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
			 ->user_details($this->user_model->getUserInfoByIpAddress())
			 ->type('user_enroll_make_payment') //Entry type like, Post, Page, Entry
			 ->id($result['enrollment_id']) //Entry ID
			 ->token('UPDATE') //Token identify Action
			 ->comment($this->session->userdata('name'). '  update payment information.')
			 ->log(); //Add Database Entry


			$this->session->set_flashdata('success', 'Payement Done Successfully');
		} else {
			$this->session->set_flashdata('danger', 'Failed to payment!');
		}
		redirect(site_url('users/' . $user_id . '/user_enrollment'));

	}


	public function save_currency() {
	echo 'test';
				exit;
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