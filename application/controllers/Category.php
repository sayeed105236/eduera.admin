<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

        if ($this->session->userdata('user_type') !== "SUPER_ADMIN" && $this->session->userdata('user_type') !== "ADMIN") {
            redirect(site_url('login'), 'refresh');
        }

        $this->page_data['total_users'] = $this->user_model->get_user_count();
        $this->page_data['total_enrollment'] = $this->enroll_model->get_enrollment_list("COUNT", array('id'));
        $this->page_data['total_courses'] = $this->course_model->get_all_courses_count();
        $this->load->library('logger');
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

    /*
    * Service - Save category information
    */

    public function category_save(){

        if (!has_role($this->session->userdata('user_id'), 'CATEGORY_CREATE')) {
            redirect(base_url('page_not_found'));
        }

        $data['name'] = html_escape($this->input->post('name'));

        if(html_escape($this->input->post('parent')) == ''){
            $data['parent'] = 0;
        }else{
            $data['parent']  = html_escape($this->input->post('parent'));
        }
       

       
        if($data['parent'] != 0 && !$this->category_model->is_parent_category($data['parent'])){
           
            $this->session->set_flashdata('category_save_failed', "Failed to save sub category!!");
        }else{
           
            $data['created_at'] = date("Y-m-d H:i:s");
            
            $base_slug = string_to_slug($data['name']);
            $data['slug'] = $base_slug;
            $category_id = $this->category_model->add_category($data);
            
            // debug($data);
            // exit;
            if ($category_id){

                $this->logger
                 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
                 ->user_details($this->user_model->getUserInfoByIpAddress())
                 ->type('category_save') //Entry type like, Post, Page, Entry
                 ->id($category_id) //Entry ID
                 ->token('CREATE') //Token identify Action
                 ->comment($this->session->userdata('name'). '  create new category.')
                 ->log(); //Add Database Entry



                $this->session->set_flashdata('category_save_success', "New Category Create Successfully!!");
            } else {
                $this->session->set_flashdata('category_save_failed', "Failed to save category!!");
            }


        }

        redirect(base_url('categories'));   

        
    }
    


    /*
    * Category - Update category details by category id
    */
    public function category_update(){ 


        if (!has_role($this->session->userdata('user_id'), 'CATEGORY_UPDATE')) {
            redirect(base_url('page_not_found'));
        }

        $data['parent'] =  $this->input->post('parent');
        $data['last_modified'] = date("Y-m-d H:i:s");
        $data['name'] = $this->input->post('name');
       
        if(!$this->category_model->is_parent_category($data['parent'])){

            $this->session->set_flashdata('category_save_failed', "Failed to update sub category!!");
        }else{

           
            $category_id = $this->input->post('cat_id');




            if($this->category_model->update_category($category_id, $data)){

                $this->logger
                 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
                 ->user_details($this->user_model->getUserInfoByIpAddress())
                 ->type('category_update') //Entry type like, Post, Page, Entry
                 ->id($category_id) //Entry ID
                 ->token('UPDATE') //Token identify Action
                 ->comment($this->session->userdata('name'). '  update a category.')
                 ->log(); //Add Database Entry

                $this->session->set_flashdata('category_save_success', "Category information Updated Successfully!!");
            } else {
                $this->session->set_flashdata('category_save_failed', "Category update fialied!!");
            }
        }
        redirect(base_url('categories'));
    }





}

?>

