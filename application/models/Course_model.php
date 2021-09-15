<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
class Course_model extends CI_Model {

	function __construct() {
		parent::__construct();
		
	}

	/*
		    *   Create course
	*/
	public function insert_course($data) {
		$this->db->insert('course', $data);
		return $this->db->insert_id();
	}
	
	public function get_instructor_courses_info($user_id)
	{
		$result = array();
		$search = array(
				'instructor_id' => $user_id
			);
		$this->db->select('id');
		$this->db->from("course");
		$this->db->where($search);
		$res = $this->db->get()->result();
		if(count($res)> 0)
		{
			foreach($res as $reow)
			{
				$result[] = (INT)$reow->id;
			}
		}
		return $result;
	}
	
	/*
		    * Update course info
	*/
	public function update_course_info($course_id, $data) {
		$this->db->set($data);
		$this->db->where('id', $course_id);
		return $this->db->update('course');
	}

	/*Upload Course Certificate*/

	public function upload_certificate($course_id, $data) {
		$this->db->set($data);
		$this->db->where('id', $course_id);
		return $this->db->update('course');
	}


	/*Upload Question Image*/

	public function upload_question_photo($question_id, $data) {
		$this->db->set($data);
		$this->db->where('id', $question_id);
		return $this->db->update('question');
	}

	/*
		    * Get Course Details by Id
	*/

	public function get_course_details_by_id($course_id) {
		return $this->db->get_where('course', array('course_id' => $course_id));
	}

	

	/*
		    * Retrieve all courses
	*/
	public function get_course_list($return_type, $attribute_list, $filter_list = null, $limit = null) {

		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}

		foreach ($attribute_list as $attribute) {
			$this->db->select('' . $attribute);
		}
		$this->db->from("course");

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
		    * Retrieve all courses count
	*/
	public function get_all_courses_count() {
		$this->db->select('id');
		$this->db->from("course");
		return $this->db->get()->num_rows();
	}

	/*
		    * Retrieve all courses with filter and search text
	*/
	public function get_course_list_with_enrollment_admin($attribute_list, $return_type, $filter_list = null, $search_text = null, $limit = null,$courses_arra = []) {

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
		
		$user_id = $this->session->userdata['user_id'];
		$user_type = $this->session->userdata['user_type'];
		$instructor = $this->session->userdata['instructor'];
		if($user_type == 'ADMIN' && $instructor == 1)
		{
			//$course_id = $this->get_instructor_courses_info($user_id);
			//debug($course_id);
			$course_id = array(4,5);
			
			$this->db->where_in('course.id', $courses_arra);
		}
		

		if ($search_text !== null) {
			if ($add_grouping_query) {
				$this->db->group_start();
			}

			$this->db->like('title', $search_text, 'both');

			if ($add_grouping_query) {
				$this->db->group_end();
			}

		}

		$this->db->join('enrollment', 'course.id = enrollment.course_id', 'LEFT');

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
	
	public function get_course_list_with_enrollment($attribute_list, $return_type, $filter_list = null, $search_text = null, $limit = null) {

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
		
		$user_id = $this->session->userdata['user_id'];
		$user_type = $this->session->userdata['user_type'];
		$instructor = $this->session->userdata['instructor'];
		if($user_type == 'ADMIN' && $instructor == 1)
		{
			//$course_id = $this->get_instructor_courses_info($user_id);
			//debug($course_id);
			$course_id = array(4,5);
			
			//$this->db->where_in('course.id', $course_id);
		}
		

		if ($search_text !== null) {
			if ($add_grouping_query) {
				$this->db->group_start();
			}

			$this->db->like('title', $search_text, 'both');

			if ($add_grouping_query) {
				$this->db->group_end();
			}

		}

		$this->db->join('enrollment', 'course.id = enrollment.course_id', 'LEFT');

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

	/*
		    * Retrieve all coupon
	*/
	public function get_coupon_list($return_type, $attribute_list, $coupon_id = null, $filter_list = null, $limit = null) {

		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}

		foreach ($attribute_list as $table => $value) {
			foreach ($value as $attribute) {
				$this->db->select($table . '.' . $attribute);
			}
		}
		$this->db->from("coupon");

		if ($filter_list !== null && count($filter_list) > 0) {
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('' . $filter_key, $filter_value);
			}
		}
		if ($coupon_id) {
			$this->db->where('coupon.id', $coupon_id);
			$this->db->join('course', 'course.id = coupon.course_id ', 'LEFT');
		} else {
			$this->db->join('course', 'course.id = coupon.course_id ', 'LEFT');
		}

		// $this->db->group_by("coupon.id");
		if ($return_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($return_type == "COUNT") {
			return $this->db->get()->num_rows();
		} else {
			return null;
		}
	}

	/*Insert course coupon */
	public function insert_coupon($data) {
		if ($this->db->insert('coupon', $data)) {
			return array('success' => true, 'message' => 'Successfully saved new coupon', 'id' => $this->db->insert_id());
		} else {
			return array('success' => false, 'message' => 'Failed to insert');
		}

	}

	public function update_coupon($coupon_id, $data) {
		$this->db->set($data);
		$this->db->where('id', $coupon_id);

		if ($this->db->update('coupon')) {
			return array('success' => true, 'message' => 'Successfully coupon info updated.');
		} else {
			return array('success' => false, 'message' => 'Failed to updated.');
		}

		// return $this->db->update('coupon');
	}

	/* Get Course Max rank*/
	public function get_max_course(){
		$this->db->select_max('rank');
		$this->db->from('course');
	    return $this->db->get()->result();
	}

	public function get_all_announcement($return_type, $attribute_list, $filter_list = null) {

		// if ($limit != null) {
		// 	$this->db->limit($limit['limit'], $limit['offset']);
		// }

		foreach ($attribute_list as $table => $attribute) {
			foreach ($attribute as $attribute_name) {
				$this->db->select($table . '.' . $attribute_name);
			}
		}

		$this->db->from("announcement");
		$this->db->join('user', 'announcement.instructor_id = user.id', 'LEFT');


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

	public function insert_and_update__announcement( $data, $announcement_id = NULL) {

		if($announcement_id == NULL){
			if ($this->db->insert('announcement', $data)) {
				return array('success' => true, 'message' => 'Successfully saved new announcement', 'id' => $this->db->insert_id());
			} else {
				return array('success' => false, 'message' => 'Failed to insert');
			}
		}else{
			$this->db->set($data);
			$this->db->where('id', $announcement_id);

			if ($this->db->update('announcement')) {
				return array('success' => true, 'message' => 'Successfully announcement updated.');
			} else {
				return array('success' => false, 'message' => 'Failed to updated.');
			}
		}
		

		// return $this->db->update('coupon');
	}

	public function announcement_delete($announcement_id) {
		$this->db->where('id', $announcement_id);
		if($this->db->delete('announcement')){
			return array('success' => true, 'message' => 'Successfully announcement removed.');
		}else{
			return array('success' => false, 'message' => 'Failed to removed.');
		}
		
	}
	

	/*
		    * Retrieve all coupon
	*/
	public function get_currency_list($return_type, $attribute_list, $filter_list = null, $limit = null) {

		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}

		foreach ($attribute_list as $table => $value) {
			foreach ($value as $attribute) {
				$this->db->select($table . '.' . $attribute);
			}
		}

		$this->db->from("currencies");

		if ($filter_list !== null && count($filter_list) > 0) {
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('' . $filter_key, $filter_value);
			}
		}
	

		// $this->db->group_by("coupon.id");
		if ($return_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($return_type == "COUNT") {
			return $this->db->get()->num_rows();
		} else {
			return null;
		}
	}


	public function update_currency($currency_id, $data) {
		$this->db->set($data);
		$this->db->where('id', $currency_id);

		if ($this->db->update('currencies')) {
			return array('success' => true, 'message' => 'Successfully currency info updated.');
		} else {
			return array('success' => false, 'message' => 'Failed to updated.');
		}

		// return $this->db->update('coupon');
	}


	/*Insert course currency */
	public function insert_currency($data) {
		if ($this->db->insert('currencies', $data)) {
			return array('success' => true, 'message' => 'Successfully saved new currency', 'id' => $this->db->insert_id());
		} else {
			return array('success' => false, 'message' => 'Failed to insert');
		}

	}


	/*
		    * Retrieve all reviews
	*/
	public function get_course_review_list($return_type, $attribute_list,  $filter_list = null, $limit = null) {

		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}

		foreach ($attribute_list as $table => $value) {
			foreach ($value as $attribute) {
				$this->db->select($table . '.' . $attribute);
			}
		}
		$this->db->from("course_review");

		if ($filter_list !== null && count($filter_list) > 0) {
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('' . $filter_key, $filter_value);
			}
		}
		// if ($coupon_id) {
		// 	$this->db->where('id', $coupon_id);
		// } else {
			$this->db->join('course', 'course.id = course_review.course_id ', 'LEFT');
			$this->db->join('user', 'user.id = course_review.user_id ', 'LEFT');
		// }

		// $this->db->group_by("coupon.id");
		if ($return_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($return_type == "COUNT") {
			return $this->db->get()->num_rows();
		} else {
			return null;
		}
	}

	public function update_course_review($review_id, $data) {
		$this->db->set($data);
		$this->db->where('id', $review_id);

		if ($this->db->update('course_review')) {
			return array('success' => true, 'message' => 'Successfully course review  updated.');
		} else {
			return array('success' => false, 'message' => 'Failed to updated.');
		}

		// return $this->db->update('coupon');
	}

}