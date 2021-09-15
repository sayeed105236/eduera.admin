<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    private $page_data = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

        $this->page_data['system_name'] = get_settings('system_name');
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('logger');
    }


    /*
    *   Controller for login page
    */
    public function index() {

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>', '</div>');

        if ($this->form_validation->run('login_form') == FALSE) {
            $this->load->view('login/index');
        } else {
            $this->validate_login();
            
        }

    }

    
    



    /*
    *   Service - to validate login
    */
    public function validate_login() {
        $email = $this->input->post('login_email');
        $password = sha1($this->input->post('login_password'));
        $user = $this->user_model->get_admin_user_by_email_and_password($email, $password, array('id', 'user_type', 'first_name', 'last_name','instructor', 'email', 'status', 'verification_code', 'profile_photo_name'));
        // debug($user);
        if ($user == null) {
            $this->session->set_flashdata('invalid_credential', "You have entered invalid credential!!");
            redirect(site_url('login'), 'refresh');
        }
        if ($user->verification_code == null || $user->verification_code == '') {
            if ($user->status == 0){
                redirect(site_url('login/default_message/1'), 'refresh');
            } else {
                 $this->session->set_userdata('user_id', $user->id);
                $this->session->set_userdata('user_type', $user->user_type);
                $this->session->set_userdata('instructor', $user->instructor);
                // $this->session->set_userdata('role_type', $user->role_type);
                $this->session->set_userdata('email', $user->email);
                $this->session->set_userdata('name', $user->first_name.' '.$user->last_name);
                $this->session->set_userdata('profile_photo_name', $user->profile_photo_name);
                $user_activity = $this->user_model->user_activity_update(1);


                /*User Logger info*/

                $this->logger
                 ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
                 ->user_details($this->user_model->getUserInfoByIpAddress())
                 ->type('login') //Entry type like, Post, Page, Entry
                 ->id(1) //Entry ID
                 ->token('LOGIN') //Token identify Action
                 ->comment($this->session->userdata('name'). '  has logged in.')
                 ->log(); //Add Database Entry

               

                redirect(site_url('home'), 'refresh');
            }
        } else {
            redirect(site_url('login/default_message/2'), 'refresh');
        }
    }

    


    /*
    *   Controller for default message page.
    */
    public function default_message($message_type_id){
        $this->page_data['page_name'] = 'default_message_page';
        $this->page_data['page_title'] = 'Eduera';
        $this->page_data['page_view'] = 'default_message_page';
        if ($message_type_id == 1) {
            $this->page_data['page_body_title'] = "Your account has been deactivated!";
            $this->page_data['page_body_text'] = "Please contact our support.";
        } else if ($message_type_id == 2) {
            $this->page_data['page_body_title'] = "You have not verified your email yet!";
            $this->page_data['page_body_text'] = "Please verify your email to activate your account.";
        }
        
        $this->load->view('index', $this->page_data);
    }
    

    
    /*
    *   Service to logout user.
    */
    public function logout($from = "") {
        $user_activity = $this->user_model->user_activity_update(0);

      
        /*User Logger info*/

        $this->logger
         ->user($this->session->userdata('user_id')) //Set UserID, who created this  Action
         ->user_details($this->user_model->getUserInfoByIpAddress())
         ->type('logout') //Entry type like, Post, Page, Entry
         ->id(2) //Entry ID
         ->token('LOGOUT') //Token identify Action
         ->comment($this->session->userdata('name'). '  has logout.')
         ->log(); //Add Database Entry

        //destroy sessions of specific userdata. We've done this for not removing the cart session
        $this->session_destroy();
        redirect(site_url('home'), 'refresh');
    }



    /*
    *   Service to destroy session
    */
    public function session_destroy() {
      


        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('user_type');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('name');
       
        if ($this->session->userdata('admin_login') == 1) {
            $this->session->unset_userdata('admin_login');
        }else {
            $this->session->unset_userdata('user_login');
        }
    }


}
