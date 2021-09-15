<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }


    public function get_category_list_with_nested_sub_category_list($attribute_list){

        foreach ($attribute_list as $attrubute) {
            $this->db->select(''.$attrubute);
        }

        $category_list = $this->get_category_list($attribute_list);
    
        for( $i = 0; $i < count($category_list); $i++){
            $category_list[$i]->sub_category_list = $this->get_sub_category_list_by_category_id($category_list[$i]->id, $attribute_list);
        }
        return $category_list;
    }


    public function get_sub_category_list_by_category_id($category_id, $attribute_list){
        foreach ($attribute_list as $attribute) {
            $this->db->select(''.$attribute);
        }
        $this->db->from("category");
        $this->db->where("parent", $category_id);        
        return $this->db->get()->result();
    }


    // Get category list
    public function get_category_list($attribute_list = []) {
    
        foreach ($attribute_list as $attrubute) {
            $this->db->select(''.$attrubute);
        }

        $this->db->from('category');
        $this->db->where('parent', 0);
        return $this->db->get()->result();
    }


    // Get category by id
    public function get_category_by_id($id, $attributes = []) {
    
        foreach ($attributes as $attrubute) {
          $this->db->select($attrubute);
        }
        $this->db->from('category');
        $this->db->where('id', $id);
        
        return $this->db->get()->result();
    }
  

    /*
    *   Add category
    */
    public function add_category($data) {
        if ($this->db->insert('category', $data)){
            return $this->db->insert_id();
        } else {
            return false;
        }

    }


    /*
    * nazmul - Category info update
    */
    public function update_category($category_id, $data){   
       
       $this->db->set($data);
       $this->db->where('id', $category_id);
       return $this->db->update('category');
    }
    

    public function delete_category($category_id) {
        $this->db->where('id', $category_id);
        $this->db->delete('category');
    }

     public function is_parent_category($category_id) {
        $this->db->select('parent');
        $this->db->where('id', $category_id);
         $result =  $this->db->get('category')->result()[0];
         if($result != null && $result->parent == 0){
             return true;
         }else{
             return false;
         }
    }

}