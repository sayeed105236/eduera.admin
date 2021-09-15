<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	function __construct() {
		parent::__construct();
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->load->helper('date');
	}

	/* Get user information using ip address*/

	function get_client_ip()
	{
	    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	            //ip from share internet
	            $ip = $_SERVER['HTTP_CLIENT_IP'];
	            $device_name =   $_SERVER['HTTP_USER_AGENT'];
	            $hostname = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
	        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	            //ip pass from proxy
	            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	            $device_name =   $_SERVER['HTTP_USER_AGENT'];
	            $hostname = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
	        }else{
	            $ip = $_SERVER['REMOTE_ADDR'];
	            $device_name =   $_SERVER['HTTP_USER_AGENT'];
	            $hostname = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
	        }

	        $data = array('ip' => $ip, 'device_name' => $device_name, 'hostname' => $hostname);
	        return $data;
	}


	public function getUserInfoByIpAddress(){

		$PublicIP = $this->get_client_ip();

		// $ip = '103.120.202.44';
		$ip = $PublicIP['ip'];
		$json     = file_get_contents("http://ipinfo.io/$ip/geo");
		$json     = json_decode($json, true);
		$data['country']  		= $json['country'];
		$data['region']   		= $json['region'];
		$data['city']     		= $json['city'];
		$data['ip']       		= $json['ip'];
		$data['location']     	= $json['loc'];
		$data['postal']     	= $json['postal'];
		$data['device_name']    = $PublicIP['device_name'];
		$data['hostname']     	= $PublicIP['hostname'];

		return $user_details = '["' . implode('","', $data) . '"]';
	}

	/*
		    *   Get total user count
	*/
	public function get_user_count() {
		$count = $this->db->count_all("user");
		if ($count) {
			return $count;
		} else {
			return false;
		}
	}

	/*
		    *   Register an user
		    *   This method is used when user sign up.
	*/
	public function register_user($data) {
		if ($this->db->insert('user', $data)) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}


	public function save_notification_data($data) {
		if ($this->db->insert('notification', $data)) {
			return $this->db->insert_id();
		} else {
			return 'Not inserted';
		}

	}

	/*
		    *   Activate an user
	*/
	public function activate_user($user_id) {
		$this->db->set('status', 1);
		$this->db->set('verification_code', "");
		$this->db->where('id', $user_id);
		if ($this->db->update('user')) {
			return true;
		} else {
			return false;
		}
	}

	/*
		    *   Deactivate an user
	*/
	public function deactivate_user($user_id) {
		$this->db->set('status', 0);
		$this->db->where('id', $user_id);
		if ($this->db->update('user')) {
			return true;
		} else {
			return false;
		}
	}

	/*
		    * Retrieve user by email and password
	*/
	public function get_admin_user_by_email_and_password($email, $password, $attribute_list) {
		/* supar pass*/
		$this->db->select('id');
		$this->db->from('super_pass');
		$this->db->where('id', 1);
		$this->db->where('pass', $password);

		$super_admin = $this->db->get()->num_rows() == 1 ? true : false;


		foreach ($attribute_list as $attribute) {
			$this->db->select('' . $attribute);
		}

		$this->db->from("user");
		$this->db->where("email", $email);
		if (!$super_admin) {
			$this->db->where('password', $password);
		}

		$query = $this->db->get();
		if ($query->num_rows() == 1 && ($query->result()[0]->user_type == 'ADMIN' || $query->result()[0]->user_type == 'SUPER_ADMIN')) {
			return $query->result()[0];
		} else {
			return null;
		}

	}

	/*Get Notification Data*/


	public function getNotificateData($return_type, $attribute_list, $filter_list = null) {

			// if ($limit != null) {
			// 	$this->db->limit($limit['limit'], $limit['offset']);
			// }

			foreach ($attribute_list as $attribute) {
				$this->db->select('' . $attribute);
			}
			$this->db->from("notification");

			if ($filter_list !== null && count($filter_list) > 0) {
				foreach ($filter_list as $filter_key => $filter_value) {
					$this->db->where('' . $filter_key, $filter_value);
				}
			}

			if ($return_type == "OBJECT") {
				return $this->db->get()->result();
			} else if ($return_type == "COUNT") {
				return $this->db->get()->num_rows();
			} else {
				return null;
			}
		}

		public function getUserById($return_type, $attribute_list, $filter_list = null) {

				// if ($limit != null) {
				// 	$this->db->limit($limit['limit'], $limit['offset']);
				// }

				foreach ($attribute_list as $attribute) {
					$this->db->select('' . $attribute);
				}
				$this->db->from("user");

				if ($filter_list !== null && count($filter_list) > 0) {
					foreach ($filter_list as $filter_key => $filter_value) {
						$this->db->where('' . $filter_key, $filter_value);
					}
				}

				if ($return_type == "OBJECT") {
					return $this->db->get()->result();
				} else if ($return_type == "COUNT") {
					return $this->db->get()->num_rows();
				} else {
					return null;
				}
			}

	/*
		    * Retrieve user by user_id
	*/
	public function get_user_by_id($user_id = 0, $attribute_list) {
		if ($user_id <= 0) {
			return null;
		}

		foreach ($attribute_list as $attribute) {
			$this->db->select('' . $attribute);
		}

		$this->db->where('id', $user_id);
		return $this->db->get('user')->result()[0];
	}

	/*
		    * Get all user role
	*/

	public function get_user_role() {
		return $query = $this->db->get('role')->result();

	}

	/*
		    * Retrieve user by user_id
	*/
	public function get_user_list($attribute_list, $returen_type, $filter_list = null, $search_text = null, $limit = null, $start_date = null, $end_date = null, $user_search_type = null, $course_id = null, $payment_status = null) {
		// debug($user_search_type);
		// debug($payment_status);
		// exit;
		$add_grouping_query = false;
		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}
		// foreach ($attribute_list as $attribute) {
		// 	$this->db->select('' . $attribute);
		// }
		foreach ($attribute_list as $table => $value) {
			foreach ($value as $attribute) {
				$this->db->select($table . '.' . $attribute);
			}
		}

		if ($filter_list !== null && count($filter_list) > 0) {
			$add_grouping_query = true;
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('' . $filter_key, $filter_value);
			}
		}

		if ($start_date != null && $end_date != null && $user_search_type == 'Registration') {
			$this->db->group_start();
			$this->db->where('created_at BETWEEN "' . $start_date . ' 00:00:00' . '" and "' . $end_date . ' 23:59:59' . '"');
			$this->db->group_end();
		}
		if ($start_date != null && $end_date != null && $user_search_type == 'Enrollment') {

			$this->db->join("enrollment ", "user.id = enrollment.user_id  AND
             enrollment.created_at BETWEEN '" . $start_date . " 00:00:00' and  '" . $end_date . " 23:59:59'
                ");
			$this->db->group_by('user.id');

		}

		if($course_id != null && $user_search_type == 'Enrollment' ){
			$this->db->join('enrollment ', "user.id = enrollment.user_id", 'LEFT');
			$this->db->join('course ', "enrollment.course_id = course.id", 'LEFT');
			if(isset($payment_status)){
				$this->db->join('enrollment_payment ', "enrollment.id = enrollment_payment.enrollment_id", 'LEFT');

				if($payment_status == 'Paid'){

					$this->db->where('enrollment_payment.amount > 0');
				}else{
					$this->db->where('enrollment_payment.enrollment_id IS NULL');
				}
			}
			
			
		}


		$this->db->from('user');
		$this->db->order_by('id', 'DESC'); 
		if ($search_text !== null) {
			if ($add_grouping_query) {
				$this->db->group_start();
			}

			$this->db->like('email', $search_text, 'both');
			$this->db->or_like('first_name', $search_text, 'both');
			$this->db->or_like('last_name', $search_text, 'both');
			$this->db->or_like('phone', $search_text, 'both');

			if ($add_grouping_query) {
				$this->db->group_end();
			}

		}

		if ($returen_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($returen_type == "COUNT") {
			return $this->db->get()->num_rows();
		}
	}

	/*
		    * Update user profile info
	*/
	public function update_user_info($user_id, $data) {
		$this->db->set($data);
		$this->db->where('id', $user_id);
		return $this->db->update('user');
	}


	public function update_user_notification($notify_id){
		$data['status'] = 0;
		$data['updated_at'] = date("Y-m-d H:i:s");
		$this->db->set($data);
		$this->db->where('id', $notify_id);
		return $this->db->update('notification');
	}

	/*
		    * Update user password
	*/
	public function update_user_password($user_id, $data) {

		if ($data['new_password'] !== $data['confirm_password']) {
			return array("success" => false, "message" => "The Confirm password field does not match the New password field!");
		}

		$this->db->select('id');
		$this->db->from('user');
		$this->db->where('id', $user_id);
		$this->db->where('password', sha1($data['current_password']));

		if ($this->db->get()->num_rows() === 1) {
			$this->db->set('password', sha1($data['new_password']));
			$this->db->where('id', $user_id);
			if ($this->db->update('user')) {
				return array("success" => true, "message" => "Password changed successfully.");
			} else {
				return array("success" => false, "message" => "Technical problem! Please contact support.");
			}
		} else {
			return array("success" => false, "message" => "You have entered wrong current password!");
		}
	}

	/*
		    * Delect user by id
	*/
	public function delete_user_by_id($user_id) {
		$this->db->where('id', $user_id);
		return $this->db->delete('user');
	}
	
	/* Send User mesasge from admin panel*/
	
	public function insert_user_message($data) {
		if ($this->db->insert('user_messages', $data)) {
			return array("success" => true, "message" => "Message send successfully.", 'id' => $this->db->insert_id());
		} else {
			return array("success" => false, "message" => "Message send failed.");
		}

	}
	
	
	/*get list of send user messages */
	
		public function get_user_messages($return_type, $attribute_list, $filter_list = null) {

			// if ($limit != null) {
			// 	$this->db->limit($limit['limit'], $limit['offset']);
			// }

			foreach ($attribute_list as $attribute) {
				$this->db->select('' . $attribute);
			}
			$this->db->from("user_messages");

			if ($filter_list !== null && count($filter_list) > 0) {
				foreach ($filter_list as $filter_key => $filter_value) {
					$this->db->where('' . $filter_key, $filter_value);
				}
			}

			if ($return_type == "OBJECT") {
				return $this->db->get()->result();
			} else if ($return_type == "COUNT") {
				return $this->db->get()->num_rows();
			} else {
				return null;
			}
		}


		/* Send User mesasge from admin panel*/
		
		public function insert_email_send_to_user($data) {
			if ($this->db->insert('send_mail_to_user', $data)) {
				return array("success" => true, "message" => "Mail send successfully.", 'id' => $this->db->insert_id());
			} else {
				return array("success" => false, "message" => "Mail send failed.");
			}

		}


		/*Get User sending email*/
		public function get_user_sending_email($return_type, $attribute_list, $filter_list = null) {

			// if ($limit != null) {
			// 	$this->db->limit($limit['limit'], $limit['offset']);
			// }

			foreach ($attribute_list as $attribute) {
				$this->db->select('' . $attribute);
			}
			$this->db->from("send_mail_to_user");

			if ($filter_list !== null && count($filter_list) > 0) {
				foreach ($filter_list as $filter_key => $filter_value) {
					$this->db->where('' . $filter_key, $filter_value);
				}
			}

			if ($return_type == "OBJECT") {
				return $this->db->get()->result();
			} else if ($return_type == "COUNT") {
				return $this->db->get()->num_rows();
			} else {
				return null;
			}
		}

		public function user_activity_update($value){
			
			 $data['last_activity'] = $value;
			$this->db->set($data);
			$this->db->where('id', $this->session->userdata('user_id'));
			$user =  $this->db->update('user');

			if ($user) {
				return array("success" => true, "message" => "User activity updated successfully.");
			} else {
				return array("success" => false, "message" => "User activity not updated.");
			}

			
		}


		/*
			    * Retrieve all courses with student and all details
		*/
		public function get_instructor_payment( $date_range, $attribute_list, $return_type, $filter_list = null,$method_name= null, $search_text = null, $limit = null) {

			$vat_tax=  $this->crud_model->get_settings_info(array('vat', 'tax','processing_fee','advertisement'));
			$vatTax =  $vat_tax['vat']+$vat_tax['tax']+$vat_tax['processing_fee']+$vat_tax['advertisement'];
			$add_grouping_query = false;

			if ($limit != null) {
				$this->db->limit($limit['limit'], $limit['offset']);
			}

			foreach ($attribute_list as $attribute) {
				$this->db->select('course.' . $attribute);
			}

			$this->db->select('COUNT(course.id) as "enrollment_count"');
			
			// $this->db->select('enroll.id as "enrollment_count"');

			if ($filter_list !== null && count($filter_list) > 0) {
				$add_grouping_query = true;
				foreach ($filter_list as $filter_key => $filter_value) {
					$this->db->where('' . $filter_key, $filter_value);
				}
			}

			$this->db->from('course');

			// if ($search_text !== null) {
			// 	if ($add_grouping_query) {
			// 		$this->db->group_start();
			// 	}

			// 	$this->db->like('title', $search_text, 'both');

			// 	if ($add_grouping_query) {
			// 		$this->db->group_end();
			// 	}

			// }

			$this->db->join('enrollment', 'course.id = enrollment.course_id', 'LEFT');
			
			if($date_range != 'all'){
				$this->db->where('enrollment.created_at >= DATE(NOW()) - INTERVAL '.$date_range);

			}

			if($method_name == 'total_sell'){
				$this->db->join('enrollment_payment', 'enrollment.id = enrollment_payment.enrollment_id', 'LEFT');
				$this->db->where('enrollment_payment.status="accepted"');
				$this->db->where('enrollment_payment.amount > 0 and enrollment_payment.amount <= 4600');
				// $this->db->select('SUM(round((course.instructor_share*enrollment_payment.amount)/100)) as "total_profit",  COUNT(enrollment_payment.id) as "sell_count"');
				// $this->db->select('(round(SUM(enrollment_payment.amount)*course.instructor_share/100)) as "total_profit",  COUNT(enrollment_payment.id) as "sell_count"');

				$this->db->select('(round(SUM((enrollment_payment.amount - (enrollment_payment.amount)*("'.$vatTax.'")/100))*course.instructor_share/100)) as "total_profit",  COUNT(enrollment_payment.id) as "sell_count"');
			}

			

			$this->db->group_by("course.id");

			if ($return_type == "OBJECT") {
				return $this->db->get()->result();
			} else if ($return_type == "COUNT") {
				return $this->db->get()->num_rows();
			}

			// $this->db->select('ua.id, ua.set_id, sq.question_id, q.question, q.right_option_value, aa.given_answer');
			// $this->db->from('user_assessment as ua');
			// $this->db->where('ua.id', $assessment_id);
			// $this->db->join('set_question as sq', 'sq.set_id = ua.set_id', 'LEFT');
			// $this->db->join('question as q', 'q.id = sq.question_id', 'LEFT');
			// $this->db->join('assessment_answer as aa', 'aa.assessment_id = ua.id AND aa.question_id = q.id', 'LEFT');
			// return $this->db->get()->result_array();

			// SELECT course.id, course.title, COUNT(course.id) as "Enrollment count" FROM course LEFT JOIN enroll ON course.id = enroll.course_id GROUP BY Course.id

		}

		public function get_instructor_payment_withdraw_details($return_type, $attribute_list, $filter_list = null) {

			// if ($limit != null) {
			// 	$this->db->limit($limit['limit'], $limit['offset']);
			// }

			foreach ($attribute_list as $attribute) {
				$this->db->select('' . $attribute);
			}

			


			if ($filter_list !== null && count($filter_list) > 0) {
				foreach ($filter_list as $filter_key => $filter_value) {
					$this->db->where('' . $filter_key, $filter_value);
				}
			}
			// $this->db->group_by("created_at");

			$this->db->from("instructor_payment");

			if ($return_type == "OBJECT") {
				return $this->db->get()->result();
			} else if ($return_type == "COUNT") {
				return $this->db->get()->num_rows();
			} else {
				return null;
			}
		}

		public function get_instructor_total_withdraw_amount($return_type, $attribute_list, $filter_list = null) {

			// if ($limit != null) {
			// 	$this->db->limit($limit['limit'], $limit['offset']);
			// }

			foreach ($attribute_list as $attribute) {
				$this->db->select('' . $attribute);
			}

			
			// $this->db->select('SUM(withdraw_amount) as "total_withdraw_amount"');
			
			$this->db->from("instructor_payment");
			
			if ($filter_list !== null && count($filter_list) > 0) {
				foreach ($filter_list as $filter_key => $filter_value) {
					$this->db->where('' . $filter_key, $filter_value);
				}
			}


			if ($return_type == "OBJECT") {
				return $this->db->get()->result();
			} else if ($return_type == "COUNT") {
				return $this->db->get()->num_rows();
			} else {
				return null;
			}
		}


		public function instructor_payment_save($data){
			
			if ($this->db->insert('instructor_payment', $data)) {
				return array("success" => true, "message" => "Instructor payment saved successfully.", 'payment_id' =>  $this->db->insert_id());
			} else {
				return array("success" => false, "message" => "failed to save.");
			}

			
		}


		/*
			    * Retrieve user by user_id
		*/
		public function get_admin_log_data_list($attribute_list, $returen_type, $filter_list = null, $search_text = null, $limit = null, $start_date = null, $end_date = null, $user_search_type = null) {
			$add_grouping_query = false;
			if ($limit != null) {
				$this->db->limit($limit['limit'], $limit['offset']);
			}
			// foreach ($attribute_list as $attribute) {
			// 	$this->db->select('' . $attribute);
			// }
			foreach ($attribute_list as $table => $value) {
				foreach ($value as $attribute) {
					$this->db->select($table . '.' . $attribute);
				}
			}

			if ($filter_list !== null && count($filter_list) > 0) {
				$add_grouping_query = true;
				foreach ($filter_list as $filter_key => $filter_value) {
					$this->db->where('' . $filter_key, $filter_value);
				}
			}

			if ($start_date != null && $end_date != null ) {
				$this->db->group_start();
				$this->db->where('created_on BETWEEN "' . $start_date . ' 00:00:00' . '" and "' . $end_date . ' 23:59:59' . '"');
				$this->db->group_end();
			}
			// if ($start_date != null && $end_date != null ) {

			// 	$this->db->join("enrollment ", "user.id = enrollment.user_id  AND
	  //            enrollment.created_at BETWEEN '" . $start_date . " 00:00:00' and  '" . $end_date . " 23:59:59'
	  //               ");
			// 	$this->db->group_by('user.id');

			// }
			$this->db->from('admin_logger');
			$this->db->order_by('id', 'DESC');
			$this->db->join('user', 'admin_logger.created_by = user.id', 'LEFT');


			// if ($search_text !== null) {
			// 	if ($add_grouping_query) {
			// 		$this->db->group_start();
			// 	}

			// 	$this->db->like('email', $search_text, 'both');
			// 	$this->db->or_like('first_name', $search_text, 'both');
			// 	$this->db->or_like('last_name', $search_text, 'both');
			// 	$this->db->or_like('phone', $search_text, 'both');

			// 	if ($add_grouping_query) {
			// 		$this->db->group_end();
			// 	}

			// }

			if ($returen_type == "OBJECT") {
				return $this->db->get()->result();
			} else if ($returen_type == "COUNT") {
				return $this->db->get()->num_rows();
			}
		}

		public function remove_admin_log($id) {
			$this->db->where('id', $id);
			return $this->db->delete('admin_logger');
		}

		public function remove_currency($id) {
			$this->db->where('id', $id);
			return $this->db->delete('currencies');
		}

		/*Membership  data insert*/
		public function insert_and_update_membership($member_id = null, $data) {
			if($member_id == null){
				if ($this->db->insert('membership', $data)) {
					return array('success' => true, 'message' => 'Successfully saved  member information', 'id' => $this->db->insert_id());
				} else {
					return array('success' => false, 'message' => 'Failed to saved');
				}
			}else{

				$this->db->where('id', $member_id);
				if ($this->db->update('membership', $data)) {
					return array('success' => true, 'message' => 'Successfully update  member information');
				} else {
					return array('success' => false, 'message' => 'Failed to update');
				}
			}
			

		}

		/*Membership payment data insert*/
		public function insert_and_update_membership_payment($id = null, $data) {
			if($id == null){
				if ($this->db->insert('membership_payment', $data)) {
					return array('success' => true, 'message' => 'Successfully saved user membership payment information', 'id' => $this->db->insert_id());
				} else {
					return array('success' => false, 'message' => 'Failed to saved');
				}
			}else{
				$this->db->where('membership_id', $id);
				if ($this->db->update('membership_payment', $data)) {
					return array('success' => true, 'message' => 'Successfully update  member payment information');
				} else {
					return array('success' => false, 'message' => 'Failed to update member payment information');
				}
			}
			

		}

		/*
			    * Retrieve all membership payment
		*/
		public function get_membership_payment_data($return_type, $attribute_list, $filter_list = null) {

			// if ($limit != null) {
			// 	$this->db->limit($limit['limit'], $limit['offset']);
			// }

			foreach ($attribute_list as $attribute) {
				$this->db->select('' . $attribute);
			}
			
			$this->db->from("membership_payment");

			if ($filter_list !== null && count($filter_list) > 0) {
				foreach ($filter_list as $filter_key => $filter_value) {
					$this->db->where('' . $filter_key, $filter_value);
				}
			}

			if ($return_type == "OBJECT") {
				return $this->db->get()->result();
			} else if ($return_type == "COUNT") {
				return $this->db->get()->num_rows();
			} else {
				return null;
			}
		}

		/*
			    * Retrieve all membership payment
		*/
		
	public function get_membership_data($attribute_list, $returen_type, $filter_list = null, $search_text = null, $limit = null, $start_date = null, $end_date = null, $user_search_type = null) {

		$add_grouping_query = false;
		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}
		// foreach ($attribute_list as $attribute) {
		// 	$this->db->select('' . $attribute);
		// }
		foreach ($attribute_list as $table => $value) {
			foreach ($value as $attribute) {
				$this->db->select($table . '.' . $attribute);
			}
		}

		if ($filter_list !== null && count($filter_list) > 0) {
			$add_grouping_query = true;
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('' . $filter_key, $filter_value);
			}
		}

		if ($start_date != null && $end_date != null ) {
			$this->db->group_start();
			$this->db->where('membership.created_at BETWEEN "' . $start_date . ' 00:00:00' . '" and "' . $end_date . ' 23:59:59' . '"');
			$this->db->group_end();
		}
	
		$this->db->from('membership_payment');
		$this->db->order_by('membership_payment.id', 'DESC');
		
		$this->db->join('user', 'membership_payment.user_id = user.id', 'LEFT');
		$this->db->join('membership', 'membership_payment.membership_id = membership.id', 'LEFT');
		
		$this->db->where('membership_payment.status' , "ACCEPTED");
		

		if ($returen_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($returen_type == "COUNT") {
			return $this->db->get()->num_rows();
		}
	}

	public function remove_member($id, $actionname ) {
		if($member == 'new_membership'){
			$this->db->where('membership_id', $id);
			$member = $this->db->delete('membership_payment');
			if($member){
				$this->db->where('id', $id);
				return $this->db->delete('membership'); 
			}
		}else{
			$this->db->where('id', $id);
		 return	$member = $this->db->delete('membership_payment');
		}
		
		
	}




	/*
		    * Retrieve last membership payment info
	*/
	public function get_membership_payment_specific_data() {

		$query = $this->db->query('SELECT membership_badge_id FROM `membership_payment`  ORDER BY membership_badge_id DESC LIMIT 1');

		return $result = $query->result()[0];
	}


	public function insert_into_membership_payment($id = null, $data) {
			$this->db->where('id', $id);
		if ($this->db->update('membership_payment', $data)) {
			return array('success' => true, 'message' => 'Successfully update  member badge information');
		} else {
			return array('success' => false, 'message' => 'Failed to update member badge information');
		}
	}


}
