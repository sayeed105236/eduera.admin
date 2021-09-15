<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_model extends CI_Model {

	function __construct() {
		parent::__construct();

		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}

	/*************************************************************************************
		    **************************************************************************************
		    * Enroll
		    **************************************************************************************
	*/

	/*
		    * Return if an user is already enrolled in a course or not
		    * Return true or false
	*/
	public function is_an_user_already_enrolled_in_a_course($user_id, $course_id) {
		$this->db->select("id");
		$this->db->from("enroll");
		$this->db->where("user_id", $user_id);
		$this->db->where("course_id", $course_id);

		if ($this->db->get()->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function get_frontend_settings_info($attribute_list) {

		$this->db->select('key, value');
		$this->db->from('frontend_settings');

		if ($attribute_list !== null && count($attribute_list) > 0) {
			foreach ($attribute_list as $attribute) {
				$this->db->or_where('key', $attribute);
			}
		}
		$result = array();

		foreach ($this->db->get()->result() as $record) {
			$result[$record->key] = $record->value;
		}

		return $result;
	}

	/*
		    * Enroll count of a counse
		    * Return a number
	*/
	public function get_enroll_count_of_a_course($course_id) {
		$this->db->select('id');
		$this->db->from("enroll");
		$this->db->where("course_id", $course_id);
		return $this->db->get()->num_rows();
	}

	/*
		    * Return enrollment information of an user.
	*/
	public function get_enrollment_info_by_user_id($user_id, $attribute_list, $limit = null, $filter = null) {

		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}

		foreach ($attribute_list as $table => $value) {
			foreach ($value as $attribute) {
				$this->db->select($table . '.' . $attribute);
			}
		}

		$this->db->from('enroll');
		$this->db->where('enroll.user_id', $user_id);

		if ($filter != null) {
			foreach ($filter as $key => $value) {
				if ($key == 'search_text') {
					$this->db->like('course.title', $value, 'both');
				} else if ($key == 'category') {
					$this->db->where_in('course.category_id', $value);
				}
			}
		}

		$this->db->join('course', 'course.id = enroll.course_id', 'LEFT');
		return $this->db->get()->result();
	}

	/*
		    * Enroll in a course
		    * Return the id of the enrolled course.
		    * Not verified yet. From previous code
	*/
	public function enroll_a_course($course_id) {
		$datax['course_id'] = $course_id;
		$datax['user_id'] = $this->session->userdata('user_id');
		if ($this->db->get_where('enrol', $datax)->num_rows() > 0) {

		} else {

			$course_data = $this->get_course_info_by_course_id($course_id, array('id', 'discount_flag', 'discounted_price', 'price'));
			if ($course_data['discount_flag'] == 1) {
				$datax['price'] = $course_data['discounted_price'];
			} else {
				$datax['price'] = $course_data['price'];
			}

			$datax['type'] = 1;
			$datax['date_added'] = strtotime(date('D, d-M-Y'));
			$this->db->insert('enrol', $datax);
		}

		return $this->db->insert_id();
	}

	///////////////////////////////////////////////////
	// End of enroll
	///////////////////////////////////////////////////

	/*************************************************************************************
		    **************************************************************************************
		    * Other features
		    **************************************************************************************
	*/

	/*
		    *  Return if an email has been already used of not
	*/
	public function is_duplicate_email($email) {

		$this->db->select('id');
		$this->db->from('user');
		$this->db->where('email', $email);

		if ($this->db->get()->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/*
		    *   If a verification code is valid or not.
		    *   If the user's status is active, then it is returnning false.
		    *   Because for active user the verification code is useless (means invalid)
	*/
	public function is_valid_verification_code($verification_code) {
		$this->db->select("id");
		$this->db->from("user");
		$this->db->where("verification_code", $verification_code);
		$this->db->where("status", 0);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result()[0]->id;
		} else {
			return false;
		}
	}

	///////////////////////////////////////////////////
	// End of others
	///////////////////////////////////////////////////

	/*************************************************************************************
		    **************************************************************************************
		    * System settings
		    **************************************************************************************
	*/

	/*
		    *  Retrieve settings table data
	*/
	public function get_settings_info($attribute_list) {

		$this->db->select('key, value');
		$this->db->from('settings');

		if ($attribute_list !== null && count($attribute_list) > 0) {
			foreach ($attribute_list as $attribute) {
				$this->db->or_where('key', $attribute);
			}
		}
		$result = array();

		foreach ($this->db->get()->result() as $record) {
			$result[$record->key] = $record->value;
		}

		return $result;
	}

	/*
		    *  Update settings table data
	*/
	public function update_settings($data) {

		foreach ($data as $key => $value) {

			$this->db->set('value', $value);
			$this->db->where('key', $key);
			$this->db->update('settings');
		}

		return true;
	}

	public function update_frontend_settings($data) {

		foreach ($data as $key => $value) {
			
			$this->db->set('value', $value);
			$this->db->where('key', $key);
			$this->db->update('frontend_settings');
		}

		return true;
	}

	// public function get_user_certificate_serial_no($return_type, $user_id, $attribute_list, $filter_list = null) {

	// 	foreach ($attribute_list as $table => $value) {
	// 		foreach ($value as $attribute) {
	// 			$this->db->select($table . '.' . $attribute);
	// 		}
	// 	}

	// 	$this->db->from('enrollment');
	// 	$this->db->where('enrollment.user_id', $user_id);

	// 	if ($filter_list !== null && count($filter_list) > 0) {
	// 		foreach ($filter_list as $filter_key => $filter_value) {
	// 			$this->db->where('' . $filter_key, $filter_value);
	// 		}
	// 	}

	// 	if ($return_type == "OBJECT") {
	// 		return $this->db->get()->result();
	// 	} else if ($return_type == "COUNT") {
	// 		return $this->db->get()->num_rows();
	// 	} else {
	// 		return null;
	// 	}
	// }

	public function getLastLessonDate($user_id, $course_id) {
		$query = $this->db->query('SELECT MAX(user_lesson_status.updated_at) as date FROM `lesson`
            LEFT JOIN user_lesson_status ON lesson.id = user_lesson_status.lesson_id
            WHERE user_lesson_status.user_id =' . $user_id . ' and lesson.course_id =' . $course_id . '');
		return $query->result()[0];
	}

	/*
		    * Retrieve all faq category
	*/
	public function get_faq_list_by_category($return_type, $attribute_list, $filter_list = null) {

		foreach ($attribute_list as $table => $attribute) {
			foreach ($attribute as $attribute_name) {
				$this->db->select($table . '.' . $attribute_name);
			}
		}
		$this->db->from("faq_category");
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
		    * Retrieve all faq
	*/

	public function get_faq_list($return_type, $attribute_list, $filter_list = null) {

		foreach ($attribute_list as $table => $attribute) {
			foreach ($attribute as $attribute_name) {
				$this->db->select($table . '.' . $attribute_name);
			}
		}

		if ($filter_list !== null && count($filter_list) > 0) {
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('' . $filter_key, $filter_value);
			}
		}
		$this->db->from("faq");
		// $this->db->join('faq', 'faq.faq_category_id = faq_category.id ', 'LEFT');
		// $this->db->order_by('lesson.rank', "ASC");
		if ($return_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($return_type == "COUNT") {
			return $this->db->get()->num_rows();
		} else {
			return null;
		}
	}

	/*Insert faq category*/
	public function insert_faq_category($data) {
		if ($this->db->insert('faq_category', $data)) {
			return array('success' => true, 'message' => 'Successfully saved', 'faq_cat_id' => $this->db->insert_id());
		} else {
			return array('success' => false, 'message' => 'Failed to insert');
		}

	}

	/*Insert faq */
	public function insert_faq($data) {
		if ($this->db->insert('faq', $data)) {
			return array('success' => true, 'message' => 'Successfully saved', 'faq_id' => $this->db->insert_id());
		} else {
			return array('success' => false, 'message' => 'Failed to insert');
		}
	}

	public function get_faq_cat_by_id($faq_cat_id) {
		$this->db->where('id', $faq_cat_id);

		return $this->db->get('faq_category')->result()[0];
	}

	/*
		    *  Update faq  category table data
	*/
	public function update_faq_category($data, $id) {

		$this->db->set($data);
		$this->db->where('id', $id);
		if ($this->db->update('faq_category')) {
			return array('success' => true, 'message' => 'Successfully saved');
		} else {
			return array('success' => false, 'message' => 'Failed to insert');
		}

	}


	/*
		    * Get User Chat information
	*/

	public function get_user_chat_info_list($return_type, $attribute_list, $filter_list = null, $limit = null) {

		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}

		foreach ($attribute_list as $table => $attribute) {
			foreach ($attribute as $attribute_name) {
				$this->db->select($table . '.' . $attribute_name);
			}
		}

		if ($filter_list !== null && count($filter_list) > 0) {
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('user_chat_info.' . $filter_key, $filter_value);
			}
		}
		$this->db->from("user_chat_info");
		
		$this->db->join('user', 'user.id  = user_chat_info.user_id', 'LEFT');
		$this->db->order_by('user_chat_info.message_time', 'DESC');
		

		if ($return_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($return_type == "COUNT") {
			return $this->db->get()->num_rows();
		} else {
			return null;
		}
	}

	public function insertUserMessage($data) {
		require_once 'vendor/autoload.php';

      $options = array(
        'cluster' => 'mt1',
        'useTLS' => true
      );
      $pusher = new Pusher\Pusher(
        '46cd25c3a10b34a7f996',
        '64ade491c6029642eedc',
        '1016944',
        $options
      );

  
		
		$chat_message['receiver_id'] = $this->session->userdata('user_id');
		$chat_message['sender_id'] = $data['user_id'];
		$chat_message['chat_messages_text'] = $data['message'];
		$chat_message['chat_messages_status'] = 1;
		$chat_message['chat_messages_datetime'] = date("Y-m-d H:i:s");

		
			if($this->db->insert('chat_message', $chat_message)){
			   $pusher_data['receiver_id'] = $this->db->insert_id();
			   $pusher_data['sender_id'] = $chat_message['sender_id'];
				$pusher->trigger('my-channel', 'my-event', $pusher_data);
				
				
				$this->update_user_message_seen($chat_message['sender_id'], $this->session->userdata('user_id'));
				return array('success' => true, "message" => "Message sent successfully");
			}else{
				return array('success' => false, "message" => $this->db->error());
			}
		

	}

	public function get_user_chat_messages($return_type, $attribute_list, $filter_list = null, $limit = null) {

		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}

		foreach ($attribute_list as $table => $attribute) {
			foreach ($attribute as $attribute_name) {
				$this->db->select($table . '.' . $attribute_name);
			}
		}

		if ($filter_list !== null && count($filter_list) > 0) {
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('' . $filter_key, $filter_value);
			}
		}
		$this->db->from("chat_message");

		if ($return_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($return_type == "COUNT") {
			return $this->db->get()->num_rows();
		} else {
			return null;
		}
	}

	public function get_sender_id($receiver_id){

		$query = $this->db->query('SELECT DISTINCT(sender_id) FROM `chat_message` WHERE receiver_id = '.$receiver_id.' GROUP BY sender_id');

		return $query->result();
	}
	
	public function update_user_message_seen($sender_id, $receiver_id) {
	    $data['message_seen'] = 1;
		$this->db->set($data);
		$this->db->where('sender_id', $sender_id);
		$this->db->where('receiver_id', $receiver_id);
		$this->db->where('chat_messages_status', 0);
		$this->db->where('message_seen', 0);
		return $this->db->update('chat_message');
	}

	/*
	 * Return user certificate serial no.
	 */
		public function get_user_certificate_serial_no($return_type, $user_id = null, $course_id = null, $attribute_list, $filter_list = null) {

			foreach ($attribute_list as $table => $value) {
				foreach ($value as $attribute) {
					$this->db->select($table . '.' . $attribute);
				}
			}

			$this->db->from('certificate');
			if ($user_id != null) {
				$this->db->where('certificate.user_id', $user_id);
			}
			if ($course_id != null) {
				$this->db->where('certificate.course_id', $course_id);
			}

			$this->db->join('user', 'user.id = certificate.user_id', 'LEFT');
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




	public function get_user_certificate_info($course_id, $user_id = '') {
		return $user_certificate_serial = $this->get_user_certificate_serial_no(
			"OBJECT",
			// $this->session->userdata('user_id'),
			$user_id ,
			$course_id,
			array("certificate" => array(
				'certificate_no',
				'course_name',
				'user_seen_duration',
				'created_at',
				'certificate_key',
			),
				"user" => array(
					"first_name",
					"last_name",
				),
			)
		);
	}

	/*Get Enrollment list*/

		public function get_enrollment_list($return_type, $attribute_list, $filter_list = null, $limit = null) {

			if ($limit != null) {
				$this->db->limit($limit['limit'], $limit['offset']);
			}

			foreach ($attribute_list as $attribute) {
				$this->db->select('' . $attribute);
			}
			$this->db->from("enrollment");

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


		public function store_user_certificate_info($course_id, $certificate_serial_no, $user_id = '') {
			date_default_timezone_set('Asia/Dhaka');
			/*get course total duration*/
			$certificate_data['course_duration'] = get_total_course_duration($course_id)->total_course_duration;

			/*get user seen last date*/
			$certificate_data['lesson_seen_last_date'] = $this->getLastLessonDate($user_id, $course_id)->date;

			/*get total user lesson duration*/
			$certificate_data['user_seen_duration'] = 0;
			$finished_time = $this->lesson_model->get_lesson_list_by_users_status(
				$user_id,
				'OBJECT',
				array(
					'user_lesson_status' => array('finished_time'),
				),
				array('course_id' => $course_id)
			);
			foreach ($finished_time as $duration) {
				$certificate_data['user_seen_duration'] += $duration->finished_time;
			}

			/*get course title*/
			$course_title = $this->course_model->get_course_list(
				"OBJECT",
				array('title', 'instructor_id'),
				array('id' => $course_id)
			)[0];

			$certificate_data['course_name'] = $course_title->title;
			$certificate_data['user_id'] = $user_id;
			$certificate_data['created_at'] = date("Y-m-d H:i:s");
			$certificate_data['course_id'] = $course_id;

			$get_date = date('d', strtotime($certificate_data['lesson_seen_last_date']));
			$get_month = date('m', strtotime($certificate_data['lesson_seen_last_date']));
			$get_year = date('Y', strtotime($certificate_data['lesson_seen_last_date']));

			$certificate_data['certificate_no'] = intval($get_year . $get_month . $get_date . '000000000') + intval($course_id . '00000') + intval($certificate_serial_no);

			$generated_key = substr(md5(time()), 0, 8);
			$certificate_data['certificate_key'] = 'EC-' . $generated_key;

			/*Insert certificate info in certificate table*/
			$this->insert_user_certificate_info($certificate_data);

		}




	/*
	 * Get higheast certificate serial no
	 */
		public function getCertificateSerialNo($course_id, $user_id = '') {
			$user_certificate_serial = $this->get_user_certificate_info($course_id, $user_id);
			$get_certificate_no = $this->get_enrollment_list(
				"OBJECT",
				array("certificate_serial_no"),
				array(
					// "user_id" => $this->session->userdata('user_id'),
					"user_id" => $user_id,
					"course_id" => $course_id,
				)
			)[0]->certificate_serial_no;

			if ($get_certificate_no == null || $get_certificate_no == 0) {

				$query = $this->db->query('SELECT certificate_serial_no FROM `enrollment` WHERE course_id =' . $course_id . ' ORDER BY certificate_serial_no DESC LIMIT 1');

				$result = $query->result()[0];

				/*get certificate no from enrollment table*/
				$data['certificate_serial_no'] = $result->certificate_serial_no + 1;
				$this->store_user_certificate_info($course_id, $data['certificate_serial_no'], $user_id);
				/*Insert certificate serial no in enrollment table*/
				$this->crud_model->update_user_certificate_serial_no($course_id, $user_id, $data);

				return $user_certificate_serial = $this->get_user_certificate_info($course_id, $user_id);

			} else {
				if ($this->get_user_certificate_info($course_id, $user_id) == null) {
					$this->store_user_certificate_info($course_id, $get_certificate_no, $user_id);
					$user_certificate_serial = $this->get_user_certificate_info($course_id, $user_id);
				}

				return $user_certificate_serial;
			}

			/* User certificate serial no set end*/
		}


		/*
			 * Insert certificate info
		*/

		public function insert_user_certificate_info($data) {
			if ($this->db->insert('certificate', $data)) {
				return array('success' => true, 'message' => 'Successfully Save data');
			} else {
				return array('success' => false, 'message' => 'Failed to save data for db error');
			}
		}

		/*
		 * Update serial no a particular user
		 */

		public function update_user_certificate_serial_no($course_id, $user_id, $data) {
			$this->db->set($data);
			$this->db->where('course_id', $course_id);
			$this->db->where('user_id', $user_id);
			return $this->db->update('enrollment');
		}

		public function home_page_setting($id = null){
			if($id){
				$query = $this->db->query('SELECT * FROM `home_page_setting` WHERE id='.$id);
			}else{
				$query = $this->db->query('SELECT * FROM `home_page_setting` ORDER BY rank');
			}
			

			return $query->result();
		}



		public function insert_and_update__homePage($data, $id = NULL) {

			if($id == NULL){
				if ($this->db->insert('home_page_setting', $data)) {
					return array('success' => true, 'message' => 'Successfully saved new section', 'id' => $this->db->insert_id());
				} else {
					return array('success' => false, 'message' => 'Failed to insert');
				}
			}else{
				$this->db->set($data);
				$this->db->where('id', $id);

				if ($this->db->update('home_page_setting')) {
					return array('success' => true, 'message' => 'Successfully section  updated.');
				} else {
					return array('success' => false, 'message' => 'Failed to updated.');
				}
			}
			

			// return $this->db->update('coupon');
		}


}
