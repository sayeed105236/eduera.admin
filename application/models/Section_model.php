<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section_model extends CI_Model {

    function __construct()
    {
        parent::__construct();

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }


    /*
    *   Insert section of a course
    */
    public function insert_section($data){
        if ($this->db->insert('section', $data)){
            return array("success" => true, "id" => $this->db->insert_id());
        } else {
            return array("success" => false, "message" => "Failed to insert in db");
        }
    }



    /*
    *   Update section of a course
    */
    public function update_section($section_id, $data){
        $this->db->set($data);
        $this->db->where("id", $section_id);
        return $this->db->update("section");
    }



    /*
    *   Get section by id
    */
    public function get_section_list($return_type, $attribute_list, $filter_list = null){
        foreach ($attribute_list as $attribute) {
            $this->db->select(''.$attribute);
        }

        $this->db->from("section");
        $this->db->order_by("rank", "ASC");

        if ($filter_list !== null && count($filter_list) > 0) {
            foreach ($filter_list as $filter_key => $filter_value) {
                $this->db->where('' . $filter_key, $filter_value);
            }
        }

        if ($return_type == "OBJECT") {
            return $this->db->get()->result();
        } else if ($returen_type == "COUNT"){
            return $this->db->get()->num_rows();
        } else {
            return null;
        }
    }



    private function update_next_sections_rank($rank, $course_id){
        $this->db->set('rank', 'rank+1', FALSE);
        $this->db->where('course_id', $course_id);
        $this->db->where('rank >= ', $rank);        
        return $this->db->update('section');
    }

}
