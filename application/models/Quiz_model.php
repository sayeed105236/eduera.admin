<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quiz_model extends CI_Model {

	function __construct() {
		parent::__construct();

		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}

/////////////////////////////////////////////////////////////
	// Option
	/////////////////////////////////////////////////////////////

/*
 * Retrieve option list by question_id with value as a index.
 */
	public function get_option_list_by_question_id_with_value_as_a_index($question_id) {
		$this->db->where('question_id', $question_id);
// $this->db->order_by('value', 'ASC');
		$associative_array = [];

		foreach ($this->db->get('question_option')->result_array() as $option) {
			$associative_array[$option["value"]] = $option;
		}

		return $associative_array;
	}

/*
 * Retrieve option list by question_id with value as a index.
 */
	public function get_option_list_by_question_id($question_id) {
		$this->db->where('question_id', $question_id);
// $this->db->order_by('value', 'ASC');
		return $this->db->get('question_option')->result_array();
	}

////////////////////////////////////////////////////////////
	// Question
	////////////////////////////////////////////////////////////

/*
 * Retrieve question by question_id, option list is not included.
 */
// public function get_question_by_id($param1) {
	//     if ($param1 == "") {
	//         return false;
	//     }
	//     $this->db->where('id', $param1);
	// // $this->db->order_by('id', 'ASC');
	//     return $this->db->get('question')->result();
	// }

/*
 * Retrieve question by question_id, with option list.
 */
	public function get_question_with_option_list_by_id($param1) {
		if ($param1 == "") {
			return false;
		}
		$this->db->where('id', $param1);
// $this->db->order_by('id', 'ASC');
		$result = $this->db->get('question')->result_array()[0];
		$result["option_list"] = $this->get_option_list_by_question_id($result["id"]);
		return $result;
	}

/*
 * Get the number of question, by course_id
 */
	public function get_number_of_question_by_course_id($course_id) {
		return count($this->get_question_list_by_course($course_id)->result_array());
	}

/*
 * Retrieve question list by course_id.
 */
	public function get_question_list_by_course($param1) {
		if ($param1 != "") {
			$this->db->where('course_id', $param1);
		}
// $this->db->order_by('id', 'ASC');
		$question_list = $this->db->get('question')->result();

		for ($i = 0; $i < count($question_list); $i++) {
			$question_list[$i]->option_list = json_decode($question_list[$i]->option_list);
		}
		return $question_list;
	}

/*
 * Retrieve question list with included option list, by course_id.
 */
	public function get_question_list_with_options_by_course($param1 = "") {
		$question_list = $this->get_question_list_by_course($param1)->result_array();
		for ($i = 0; $i < count($question_list); $i++) {
			$question_list[$i]['option_list'] = $this->get_option_list_by_question_id_with_value_as_a_index($question_list[$i]["id"]);
		}
		return $question_list;
	}

/*
 * Get a set's question id list, by set id
 */
	public function get_question_id_list_by_set_id($set_id) {
		if ($set_id == null || $set_id == "") {
			return false;
		}
		$this->db->select('question_id');
		$response_array = $this->db->get_where('set_question', array('set_id' => $set_id))->result_array();
		$result_array = [];
		foreach ($response_array as $ob) {
			array_push($result_array, $ob["question_id"]);
		}

		return $result_array;
	}

/////////////////////////////////////////////////////////////
	// Quiz
	/////////////////////////////////////////////////////////////

/*
 * Get quiz set by set id, with questions and options
 */
	public function get_quiz_set_by_id_with_questions_and_options($set_id) {
		$set = $this->db->get_where('quiz_set', array('id' => $set_id))->result_array()[0];
		$set["question_list"] = [];
		$question_id_list = $this->get_question_id_list_by_set_id($set_id);
		for ($i = 0; $i < count($question_id_list); $i++) {
			array_push($set["question_list"], $this->get_question_with_option_list_by_id($question_id_list[$i]));
		}
		return $set;
	}

/*
 * Get a quiz set list, by course id. It only returns the set info, not the question list.
 */
	public function get_quiz_set_list_by_course_id($course_id, $type = "") {
		if ($course_id == null || $course_id == "") {
			return false;
		}
		if ($type == "START" || $type == "END") {
			$this->db->where("type", $type);
		}
		return $this->db->get_where('quiz_set', array('course_id' => $course_id))->result_array();
	}

/*
 * Get a quiz set list, by lesson id. It only returns the set info, not the question list.
 */
	public function get_quiz_set_list_by_lesson_id($lesson_id, $type = "") {
		if ($lesson_id == null || $lesson_id == "") {
			return false;
		}
		if ($type == "START" || $type == "END") {
			$this->db->where("type", $type);
		}

		return $this->db->get_where('quiz_set', array('lesson_id' => $lesson_id))->result_array();
	}

	public function create_a_quiz_set_with_random_questions($course_id, $set_type, $set_lenght = "") {
		if ($course_id == null) {
			return flase;
		}

		if ($set_lenght == null || $set_lenght == "") {
			$set_lenght = 3;
		}

		$question_list = $this->get_question_list_by_course($course_id)->result_array();

		if ($set_lenght > count($question_list)) {
			$set_lenght = count($question_list);
		}

		$data = array(
			'type' => $set_type,
			'name' => 'Random set',
			'course_id' => $course_id,
		);

		$this->db->insert('quiz_set', $data);
		$set_id = $this->db->insert_id();

		for ($i = 0; $i < $set_lenght; $i++) {
			$question = array_splice($question_list, mt_rand(0, count($question_list) - 1), 1)[0];
			$data = array(
				'set_id' => $set_id,
				'question_id' => $question['id'],
			);
			$this->db->insert('set_question', $data);
		}

		return $set_id;
	}

//////////////////////////////////////////////////////////////////////////

/*
 * Just insert question in question table, not any option.
 */
	public function insert_question($question, $id = null) {
		if ($id != null) {
			$this->db->set($question);
			$this->db->where('id', $id);

			if ($this->db->update('question', $question)) {
				return array("success" => true);
			} else {
				return array("success" => false, "message" => "Failed to update in db");
			}
		} else {
			if (count($question) <= 0) {
				return false;
			}

			if ($this->db->insert('question', $question)) {
				return array("success" => true, 'id' => $this->db->insert_id());
			} else {
				return array("success" => false, "message" => "Failed to insert in db");
			}
			// return $this->db->insert('question', $question);
		}

	}

/*
 * Just insert option in option table, not any question.
 */
	public function insert_question_option($question_option) {
		if (count($question_option) <= 0) {
			return false;
		}
		return $this->db->insert('question_option', $question_option);
	}

/*
 * Just insert set in quiz_set table, not anything else.
 */
// public function insert_set($set){
	//   if (count($set) <= 0){
	//     return false;
	//   }
	//   return $this->db->insert('quiz_set', $set);
	// }

	public function insert_set($data, $quiz_set_id = null) {
		if ($quiz_set_id == null) {

			if (count($data) <= 0) {
				return false;
			}

			if ($this->db->insert('quiz_set', $data)) {
				return array("success" => true, 'id' => $this->db->insert_id());
			} else {
				return array("success" => false, "message" => "Failed to insert in db");
			}	

		} else {

			$this->db->set($data);
			$this->db->where('id', $quiz_set_id);

			if ($this->db->update('quiz_set', $data)) {
				return array("success" => true);
			} else {
				return array("success" => false, "message" => "Failed to update in db");
			}
		}

	}

/*
 * Just insert set id and question id in set_question table, not anyting else.
 */
	public function insert_in_set_question_table($set_question) {
		if (count($set_question) <= 0) {
			return false;
		}
		return $this->db->insert('set_question', $set_question);
	}

/*
 * Test
 */
	public function test() {
		return "this is test for quiz controller";
	}

//////////////////////////////////////////////////////////////////

	public function insertUserAssessmentRecord($enroll_id, $set_id) {
		$data = array(
			'enroll_id' => $enroll_id,
			'set_id' => $set_id,
		);
		$this->db->insert('user_assessment', $data);
		return $this->db->insert_id();
	}

	public function insertAssessmentAnswerRecord($assessment_id, $question_id, $given_answer) {
		$data = array(
			'assessment_id' => $assessment_id,
			'question_id' => $question_id,
			'given_answer' => $given_answer,
		);
		$this->db->insert('assessment_answer', $data);
		return $this->db->insert_id();
	}

	public function getAssessmentQuestionWithRightAnswerAndGivenAnswer($assessment_id) {
// $this->db->select('q.id, q.question, q.right_option_value, aa.given_answer');
		// $this->db->from('assessment_answer as aa');
		// $this->db->where('aa.assessment_id', $assessment_id);
		// $this->db->join('question as q', 'q.id = aa.question_id', 'LEFT');
		// return $this->db->get()->result_array();

		$this->db->select('ua.id, ua.set_id, sq.question_id, q.question, q.right_option_value, aa.given_answer');
		$this->db->from('user_assessment as ua');
		$this->db->where('ua.id', $assessment_id);
		$this->db->join('set_question as sq', 'sq.set_id = ua.set_id', 'LEFT');
		$this->db->join('question as q', 'q.id = sq.question_id', 'LEFT');
		$this->db->join('assessment_answer as aa', 'aa.assessment_id = ua.id AND aa.question_id = q.id', 'LEFT');
		return $this->db->get()->result_array();
	}

/*
 *nazmul Get Question List from db
 */

	public function get_all_question_by_course_id($return_type , $course_id=null, $question=null, $limit=null, $attribute_list= null, $filter_list = null) {
		// debug($limit);
		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}
		foreach ($attribute_list as $attribute) {
		    $this->db->select(''.$attribute);
		}


		if ($filter_list !== null && count($filter_list) > 0) {
		    foreach ($filter_list as $filter_key => $filter_value) {
		        $this->db->where('' . $filter_key, $filter_value);
		    }
		}
		if($course_id != null){
			$this->db->where('course_id', $course_id);

		}
		if($question != null){
			$this->db->like('question', $question, 'both');
		}
		$this->db->from('question');


		if ($return_type == "OBJECT") {
			return $this->db->get()->result();
		} else if ($return_type == "COUNT") {
			return $this->db->get()->num_rows();
		} else {
			return null;
		}


		// return $query = $this->db->get('question')->result();
	}

/*
 * nazmul Remove Question from particular course
 */

	public function question_delete($question_id) {
		$this->db->where('id', $question_id);
		$this->db->delete('question');
	}



	public function update_quiz_set($quiz_set_id, $data){
		$this->db->set($data);
		$this->db->where('id', $quiz_set_id);
		return $this->db->update('quiz_set');
	}

	public function get_quiz_set_list($return_type, $with_question, $attribute_list, $filter_list = null, $limit = null) {

		if ($limit != null) {
			$this->db->limit($limit['limit'], $limit['offset']);
		}

		foreach ($attribute_list as $table => $attribute) {
			foreach ($attribute as $attribute_name) {
				$this->db->select($table . '.' . $attribute_name);
			}
		}
		$this->db->from("quiz_set");

		if ($filter_list !== null && count($filter_list) > 0) {
			foreach ($filter_list as $filter_key => $filter_value) {
				$this->db->where('' . $filter_key, $filter_value);
			}
		}

		$this->db->join('lesson', 'quiz_set.lesson_id = lesson.id', 'LEFT');

		if ($return_type == "OBJECT") {
			$quiz_set_list = $this->db->get()->result();
			if ($with_question) {

				for ($i = 0; $i < count($quiz_set_list); $i++) {
					$quiz_set_list[$i]->question_id_list = json_decode($quiz_set_list[$i]->question_id_list);
					$quiz_set_list[$i]->question_list = array();
					for ($j = 0; $j < count($quiz_set_list[$i]->question_id_list); $j++) {
						array_push($quiz_set_list[$i]->question_list, $this->get_question_by_id("OBJECT", array('id', 'question', 'option_list', 'right_option_value'), array('id' => $quiz_set_list[$i]->question_id_list[$j]))[0]);
					}
				}
			}
			return $quiz_set_list;
		} else if ($return_type == "COUNT") {
			return $this->db->get()->num_rows();
		} else {
			return null;
		}
	}

	public function get_question_by_id($return_type, $attribute_list, $filter_list = null) {

		foreach ($attribute_list as $attribute) {
			$this->db->select('' . $attribute);
		}
		$this->db->from("question");

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
 *   Delete quiz set
 */
	public function delete_quiz_set($id) {
		$this->db->where('id', $id);
		if ($this->db->delete('quiz_set')) {
			return array("success" => true, "message" => "Data deleted successfully.");
		} else {
			return array("success" => false, "message" => "Failed to delete data in db.");
		}
	}

}