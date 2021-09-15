<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lesson_model extends CI_Model {

	function __construct() {
		parent::__construct();

		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}

	/*
		  	*	Insert lession.
	*/
	public function insert_lesson($data) {
		$this->db->insert('lesson', $data);
		return $this->db->insert_id();
	}

	/*
		  	*	Update lesson
	*/
	public function update_lesson($lesson_id, $data) {
		$this->db->set($data);
		$this->db->where("id", $lesson_id);
		return $this->db->update('lesson');
	}

	/*
		  	*	Retrieve lession list of a course
	*/
	public function get_lesson_list_by_course_id($course_id, $attribute_list) {
		foreach ($attribute_list as $attribute) {
			$this->db->select('' . $attribute);
		}
		$this->db->from("lesson");
		$this->db->where("course_id", $course_id);
		$lesson_list = $this->db->get()->result();
		return $lesson_list;
	}

	public function get_lesson_list($attribute_list, $returen_type, $filter_list = null) {

		foreach ($attribute_list as $attribute) {
			$this->db->select('' . $attribute);
		}

		if ($filter_list !== null && count($filter_list) > 0) {
			$add_grouping_query = true;
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('' . $filter_key, $filter_value);
			}
		}

		$this->db->from('lesson');

		if ($returen_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($returen_type == "COUNT") {
			return $this->db->get()->num_rows();
		}
	}

	/*
		  	*	Retrieve lession list of a section
	*/
	public function get_lesson_list_by_section_id($section_id, $attribute_list) {

		// foreach ($attribute_list as $attribute) {
		// 	$this->db->select(''.$attribute);
		// }
		// $this->db->from("lesson");
		// $this->db->where("section_id", $section_id);
		// return $this->db->get()->result();

		$sql = "SELECT ";
		$attribute_array = array();
		foreach ($attribute_list as $attribute) {
			$attribute_array[] = $attribute;
		}
		$sql .= implode(', ', $attribute_array);
		$sql .= " FROM lesson WHERE section_id = ? ORDER BY rank";
		return $this->db->query($sql, array($section_id))->result();
	}

	public function get_lesson_list_by_users_status($user_id, $return_type, $attribute_list, $filter_list = null) {

		foreach ($attribute_list as $table => $attribute) {
			foreach ($attribute as $attribute_name) {
				$this->db->select($table . '.' . $attribute_name);
			}
		}
		$this->db->from("lesson");
		if ($filter_list !== null && count($filter_list) > 0) {
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('' . $filter_key, $filter_value);
			}
		}

		$this->db->join('user_lesson_status', 'user_lesson_status.lesson_id = lesson.id AND user_lesson_status.user_id = ' . $user_id, 'LEFT');
		// $this->db->order_by('lesson.rank', "ASC");
		$this->db->distinct('DISTINCT `user_lesson_status.id`', false);
		if ($return_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($return_type == "COUNT") {
			return $this->db->get()->num_rows();
		} else {
			return null;
		}
	}

	public function lesson_delete($lesson_id) {
		$this->db->where('id', $lesson_id);
		$this->db->delete('lesson');
	}


	

}
