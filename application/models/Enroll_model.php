<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enroll_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/*
		    *   Get total user count
	*/

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

	/*
		    * Enroll user a new course
	*/

	public function save_enroll_user($data) {
		if ($this->db->get_where('enrollment', array('user_id' => $data['user_id'], 'course_id' => $data['course_id']))->num_rows() > 0) {
			return array('success' => false, 'message' => 'This user is already enrolled to this course');
		} else {
			if ($this->db->insert('enrollment', $data)) {
				return array('success' => true, 'message' => 'Successfully enrolled', 'enrolled_id' => $this->db->insert_id());
			} else {
				return array('success' => false, 'message' => 'Failed to enroll for db error');
			}
		}
	}

	/*
		    * Enroll user a new course
	*/

	public function update_enrollment($enrollment_id, $data) {
		$this->db->set($data);
		$this->db->where("id", $enrollment_id);
		if ($this->db->update('enrollment')) {
			return array('success' => true, 'message' => 'Enrollment updated successfully!');
		} else {
			return array('success' => false, 'message' => $this->db->error());
		}
	}

	/*
		    * Get particular User Enroll History
	*/
	public function get_user_enroll_history($user_id) {

		return $this->db->select('c.id as course_id, c.title, e.user_id, e.enrolled_price, e.created_at, e.id as enroll_id, e.access')
			->from('enrollment as e')
			->where('e.user_id', $user_id)
			->join('course as c', 'e.course_id = c.id', "LEFT")
			->get()->result();
		// return $this->db->get_where('enroll', array('user_id' => $user_id));

	}

	/*
		    * Get particular User Enroll History
	*/
	public function get_instructor_enroll_history($instructor_id, $attribute_list) {
		foreach ($attribute_list as $attribute) {
			$this->db->select('' . $attribute);
		}
		return $this->db->get_where('course', array('instructor_id' => $instructor_id))->result();
	}

	/*
		    * Delete particular user enroll course
	*/
	public function remove_enrollment_by_id($id) {
		$this->db->where('id', $id);
		return $this->db->delete('enrollment');
	}

	/*
		    * Return enrollment information of an user.
	*/
	public function get_enrollment_info_by_user_id($return_type, $user_id, $attribute_list, $limit = null, $filter_list = null, $paid_amount_sum = null) {

		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}

		foreach ($attribute_list as $table => $value) {
			foreach ($value as $attribute) {
				$this->db->select($table . '.' . $attribute);
			}
		}

		if ($paid_amount_sum != null) {
			$this->db->select('SUM(ep.amount) as paid_amount');
		}

		$this->db->from('enrollment');
		$this->db->where('enrollment.user_id', $user_id);

		if ($filter_list != null) {
			foreach ($filter_list as $key => $value) {
				if ($key == 'search_text') {
					$this->db->like('course.title', $value, 'both');
				} else if ($key == 'category') {
					$this->db->where_in('course.category_id', $value);
				}
			}
		}

		$this->db->join('course', 'course.id = enrollment.course_id', 'LEFT');
		if ($paid_amount_sum != null) {
			$this->db->join('enrollment_payment as ep', 'enrollment.id = ep.enrollment_id', 'left');
			$this->db->group_by('enrollment.id');
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
		    * Return enrollment information of an course.
	*/
	public function get_enrollment_info_by_course_id($return_type, $course_id, $attribute_list,  $filter_list = null) {

		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}

		foreach ($attribute_list as $table => $value) {
			foreach ($value as $attribute) {
				$this->db->select($table . '.' . $attribute);
			}
		}

		

		$this->db->from('enrollment');
		$this->db->where('enrollment.course_id', $course_id);

		if ($filter_list !== null && count($filter_list) > 0) {
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('' . $filter_key, $filter_value);
			}
		}

		$this->db->join('course', 'course.id = enrollment.course_id', 'LEFT');
		$this->db->join('user', 'user.id = enrollment.user_id', 'LEFT');
	

		if ($return_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($return_type == "COUNT") {
			return $this->db->get()->num_rows();
		} else {
			return null;
		}
	}

	/*
		    * Enroll user a new course
	*/

	public function save_enrollment_user_payment($data) {

		if ($this->db->insert('enrollment_payment', $data)) {
			return array('success' => true, 'message' => 'Successfully user payment done', 'enrollment_id' => $this->db->insert_id());
		} else {
			return array('success' => false, 'message' => 'Failed to user payment for db error');
		}

	}

	public function getTotalAmountForToday() {
		$query = $this->db->query('SELECT SUM(amount) as amount, COUNT(id) as total_times FROM `enrollment_payment` WHERE created_at BETWEEN "' . date("Y-m-d") . ' 00:00:00' . '" AND "' . date("Y-m-d") . ' 23:00:00' . '" AND status = "accepted"');

		return $query->result()[0];
	}

}