<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
        // $this->dbforge->create_database('prod_db');
    }


    public function index(){
        $this->convert_users();
        $this->convert_course();
        $this->convert_category();
        $this->convert_enrollment();
        $this->convert_lesson();
        $this->convert_section();
        $this->convert_payment_to_enrollment_payment();
        $this->convert_payment_to_transaction();
        // $this->convert_settings();

        $this->delete_tables();
        $this->change_table_names_and_create_new_tables();
    }



    public function delete_tables(){
        $this->dbforge->drop_table('assessment_answer',TRUE);
        $this->dbforge->drop_table('category',TRUE);
        $this->dbforge->drop_table('comment',TRUE);
        $this->dbforge->drop_table('course',TRUE);
        $this->dbforge->drop_table('enrol',TRUE);
        $this->dbforge->drop_table('enrol_instructor',TRUE);
        $this->dbforge->drop_table('language',TRUE);
        $this->dbforge->drop_table('lesson',TRUE);
        $this->dbforge->drop_table('message',TRUE);
        $this->dbforge->drop_table('message_thread',TRUE);
        $this->dbforge->drop_table('payment',TRUE);
        $this->dbforge->drop_table('payment_info',TRUE);
        $this->dbforge->drop_table('question',TRUE);
        $this->dbforge->drop_table('question_old',TRUE);
        $this->dbforge->drop_table('question_option',TRUE);
        $this->dbforge->drop_table('quiz_set',TRUE);
        $this->dbforge->drop_table('quiz_slot',TRUE);
        $this->dbforge->drop_table('rating',TRUE);
        $this->dbforge->drop_table('role',TRUE);
        $this->dbforge->drop_table('section',TRUE);
        $this->dbforge->drop_table('set_question',TRUE);
        $this->dbforge->drop_table('slot_question',TRUE);
        $this->dbforge->drop_table('tag',TRUE);
        $this->dbforge->drop_table('users',TRUE);
        $this->dbforge->drop_table('user_assessment',TRUE);
    }


    public function change_table_names_and_create_new_tables(){
        $this->dbforge->rename_table('prod_user', 'user');
        $this->dbforge->rename_table('prod_course', 'course');
        $this->dbforge->rename_table('prod_category', 'category');
        $this->dbforge->rename_table('prod_enrollment', 'enrollment');
        $this->dbforge->rename_table('prod_lesson', 'lesson');
        $this->dbforge->rename_table('prod_section', 'section');
        $this->dbforge->rename_table('prod_enrollment_payment', 'enrollment_payment');
        $this->dbforge->rename_table('prod_transaction', 'transaction');


        // Creating user_type table
        $this->dbforge->drop_table('user_type',TRUE);
        $this->dbforge->add_field('id');
        $fields = array(
            'name'             => array('type' => 'VARCHAR', 'constraint' => '15'),
            'type'            => array('type' => 'VARCHAR', 'constraint' => '15'),
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('user_type');


        // Inserting data in user_type table
        $this->db->insert('user_type', array('name' => 'Admin', 'type' => 'ADMIN'));
        $this->db->insert('user_type', array('name' => 'Super Admin', 'type' => 'SUPER_ADMIN'));
        $this->db->insert('user_type', array('name' => 'User', 'type' => 'USER'));





        // Creating role table
        $this->dbforge->drop_table('role',TRUE);
        $this->dbforge->add_field('id');
        $fields = array(
            'role_category'    => array('type' => 'VARCHAR', 'constraint' => '50'),
            'name'             => array('type' => 'VARCHAR', 'constraint' => '100'),
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('role');


        // Inserting data in role table
        $this->db->insert('role', array('role_category' => 'USER', 'name' => 'USER_CREATE'));
        $this->db->insert('role', array('role_category' => 'USER', 'name' => 'USER_READ'));
        $this->db->insert('role', array('role_category' => 'USER', 'name' => 'USER_UPDATE'));
        $this->db->insert('role', array('role_category' => 'USER', 'name' => 'USER_DELETE'));
        $this->db->insert('role', array('role_category' => 'COURSE', 'name' => 'COURSE_CREATE'));
        $this->db->insert('role', array('role_category' => 'COURSE', 'name' => 'COURSE_READ'));
        $this->db->insert('role', array('role_category' => 'COURSE', 'name' => 'COURSE_UPDATE'));
        $this->db->insert('role', array('role_category' => 'COURSE', 'name' => 'COURSE_DELETE'));
        $this->db->insert('role', array('role_category' => 'CATEGORY', 'name' => 'CATEGORY_CREATE'));
        $this->db->insert('role', array('role_category' => 'CATEGORY', 'name' => 'CATEGORY_READ'));
        $this->db->insert('role', array('role_category' => 'CATEGORY', 'name' => 'CATEGORY_UPDATE'));
        $this->db->insert('role', array('role_category' => 'CATEGORY', 'name' => 'CATEGORY_DELETE'));


    }

    public function convert_users(){

        $this->dbforge->drop_table('prod_user',TRUE);

        $this->dbforge->add_field('id');
        $fields = array(
            'user_type'             => array('type' => 'VARCHAR', 'constraint' => '20'),
            'first_name'            => array('type' => 'VARCHAR', 'constraint' => '100'),
            'last_name'             => array('type' => 'VARCHAR', 'constraint' => '100'),
            'email'                 => array('type' => 'VARCHAR', 'constraint' => '255'),
            'phone'                 => array('type' => 'VARCHAR', 'constraint' => '20'),
            'biography'             => array('type' => 'LONGTEXT', 'null' => TRUE),
            'password'              => array('type' => 'VARCHAR', 'constraint' => '40'),
            'created_at'            => array('type' => 'DATETIME'),
            'last_modified'         => array('type' => 'DATETIME'),
            'wishlist'              => array('type' => 'LONGTEXT', 'null' => TRUE),
            'verification_code'     => array('type' => 'VARCHAR', 'constraint' => '50'),
            'status'                => array('type' => 'BOOL'),
            'social_links'          => array('type' => 'LONGTEXT'),
            'profile_photo_name'    => array('type' => 'VARCHAR', 'constraint' => '50'),
            'instructor'            => array('type' => 'INT', 'constraint' => 11),
            'role_list'             => array('type' => 'LONGTEXT')
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('prod_user');


        $user_list = $this->db->get('users')->result();
        echo "Count - " . count($user_list) . "<br />";
        foreach ($user_list as $user) {
            $data = array(
                'id'                    => $user->id,
                'user_type'             => 'USER',
                'first_name'            => $user->first_name,
                'last_name'             => $user->last_name,
                'email'                 => $user->email,
                'phone'                 => $user->phone,
                'biography'             => $user->biography,
                'password'              => $user->password,
                'wishlist'              => $user->wishlist,
                'verification_code'     => "",
                'status'                => 1,
                'social_links'          => $user->social_links,
                'profile_photo_name'    => '',
                'instructor'            => 0,
                'role_list'             => ''
            );


            if ($user->date_added == '' || $user->date_added == null){
                $data['created_at'] = date('Y/m/d H:i:s');
            } else {
                $data['created_at'] = date('Y/m/d H:i:s', $user->date_added);
            }


            if ($user->last_modified == '' || $user->last_modified == null){
                $data['last_modified'] = date('Y/m/d H:i:s');
            } else {
                $data['last_modified'] = date('Y/m/d H:i:s', $user->last_modified);
            }

            $this->db->insert('prod_user', $data);

        }
    }

    public function convert_course(){

        $this->dbforge->drop_table('prod_course',TRUE);

        $this->dbforge->add_field('id');
        $fields = array(
            // 'id'                    => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'title'                 => array('type' => 'VARCHAR', 'constraint' => '255'),
            'slug'                  => array('type' => 'VARCHAR', 'constraint' => '510', 'unique' => TRUE),
            'short_description'     => array('type' => 'LONGTEXT'),
            'description'           => array('type' => 'LONGTEXT'),
            'outcomes'              => array('type' => 'LONGTEXT'),
            'language'              => array('type' => 'VARCHAR', 'constraint' => '10'),
            'category_id'           => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'requirements'          => array('type' => 'LONGTEXT'),
            'price'                 => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'discount_flag'         => array('type' => 'BOOLEAN'),
            'discounted_price'      => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'level'                 => array('type' => 'VARCHAR', 'constraint' => '50'),
            'preview_video_id'      => array('type' => 'VARCHAR', 'constraint' => '50'),
            'created_at'            => array('type' => 'DATETIME'),
            'last_modified'         => array('type' => 'DATETIME'),
            'status'                => array('type' => 'BOOLEAN'),
            'instructor_id'         => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'instructor_share'      => array('type' => 'TINYINT', 'unsigned' => TRUE)
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('prod_course');

        


        $data_list = $this->db->get('course')->result();
        echo "Count - " . count($data_list) . "<br />";
        foreach ($data_list as $data) {

            $base_slug = string_to_slug($data->title);
            $slug = $base_slug;
            $slug_counter = 1;

            while ( $this->get_course_list("COUNT", array('id'), array('slug' => $slug)) > 0) {
                $slug = $base_slug . '-' . $slug_counter;
                $slug_counter++;
            }

            $data->slug = $slug;


            $data_to_insert = array(
                'id'                    => $data->id,
                'title'                 => $data->title,
                'slug'                  => $data->slug,
                'short_description'     => $data->short_description,
                'description'           => $data->description,
                'outcomes'              => $data->outcomes,
                'language'              => $data->language,
                'category_id'           => $data->sub_category_id,
                'requirements'          => $data->requirements,
                'price'                 => $data->price,
                'discount_flag'         => $data->discount_flag ? $data->discount_flag: 0,
                'discounted_price'      => $data->discounted_price,
                'level'                 => $data->level,
                'preview_video_id'      => '',
                'status'                => $data->status,
                'instructor_id'         => $data->assigned_to,
                'instructor_share'      => 0
            );


            if ($data->date_added == '' || $data->date_added == null){
                $data_to_insert['created_at'] = date('Y/m/d H:i:s');
            } else {
                $data_to_insert['created_at'] = date('Y/m/d H:i:s', $data->date_added);
            }


            if ($data->last_modified == '' || $data->last_modified == null){
                $data_to_insert['last_modified'] = date('Y/m/d H:i:s');
            } else {
                $data_to_insert['last_modified'] = date('Y/m/d H:i:s', $data->last_modified);
            }

            $this->db->insert('prod_course', $data_to_insert);

        }
    }

    public function convert_category(){

        $this->dbforge->drop_table('prod_category',TRUE);

        $this->dbforge->add_field('id');
        $fields = array(
            'name'                  => array('type' => 'VARCHAR', 'constraint' => '255'),
            'slug'                  => array('type' => 'VARCHAR', 'constraint' => '510'),
            'parent'                => array('type' => 'INT', 'constraint' => 11),
            'created_at'            => array('type' => 'DATETIME'),
            'last_modified'         => array('type' => 'DATETIME')
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('prod_category');

        


        $data_list = $this->db->get('category')->result();
        echo "Count - " . count($data_list) . "<br />";
        foreach ($data_list as $data) {


            $data_to_insert = array(
                'id'                   => $data->id,
                'name'                 => $data->name,
                'slug'                 => $data->slug,
                'parent'               => $data->parent
            );


            if ($data->date_added == '' || $data->date_added == null){
                $data_to_insert['created_at'] = date('Y/m/d H:i:s');
            } else {
                $data_to_insert['created_at'] = date('Y/m/d H:i:s', $data->date_added);
            }


            if ($data->last_modified == '' || $data->last_modified == null){
                $data_to_insert['last_modified'] = date('Y/m/d H:i:s');
            } else {
                $data_to_insert['last_modified'] = date('Y/m/d H:i:s', $data->last_modified);
            }

            $this->db->insert('prod_category', $data_to_insert);

        }
    }

    public function convert_enrollment(){

        $this->dbforge->drop_table('prod_enrollment',TRUE);

        $this->dbforge->add_field('id');
        $fields = array(
            'user_id'               => array('type' => 'INT', 'constraint' => 11),
            'course_id'             => array('type' => 'INT', 'constraint' => 11),
            'enrolled_price'        => array('type' => 'INT', 'constraint' => 11),
            'access'                => array('type' => 'TINYINT'),
            'created_at'            => array('type' => 'DATETIME'),
            'last_modified'         => array('type' => 'DATETIME')
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('prod_enrollment');  


        $data_list = $this->db->get('enrol')->result();
        echo "Count - " . count($data_list) . "<br />";
        $user = array();
        foreach ($data_list as $data) {

            if ($data->user_id == null){
                continue;
            }

            if (!is_array($user[$data->user_id])) 
                $user[$data->user_id] = array();

            if (in_array($data->course_id, $user[$data->user_id]))
                continue;

            array_push($user[$data->user_id], $data->course_id);

            $data_to_insert = array(
                'id'                        => $data->id,
                'user_id'                   => $data->user_id,
                'course_id'                 => $data->course_id,
                'enrolled_price'            => $data->price,
                'access'                    => 0
            );


            if ($data->date_added == '' || $data->date_added == null){
                $data_to_insert['created_at'] = date('Y/m/d H:i:s');
            } else {
                $data_to_insert['created_at'] = date('Y/m/d H:i:s', $data->date_added);
            }


            if ($data->last_modified == '' || $data->last_modified == null){
                $data_to_insert['last_modified'] = date('Y/m/d H:i:s');
            } else {
                $data_to_insert['last_modified'] = date('Y/m/d H:i:s', $data->last_modified);
            }

            $this->db->insert('prod_enrollment', $data_to_insert);

        }
    }

    public function convert_lesson(){

        $this->dbforge->drop_table('prod_lesson',TRUE);

        $this->dbforge->add_field('id');
        $fields = array(
            'title'                 => array('type' => 'VARCHAR', 'constraint' => '255'),
            'duration_in_second'    => array('type' => 'INT', 'constraint' => 11),
            'course_id'             => array('type' => 'INT', 'constraint' => 11),
            'section_id'            => array('type' => 'INT', 'constraint' => 11),
            'created_at'            => array('type' => 'DATETIME'),
            'last_modified'         => array('type' => 'DATETIME'),
            'summary'               => array('type' => 'LONGTEXT'),
            'rank'                  => array('type' => 'INT', 'constraint' => 11),
            'vimeo_id'              => array('type' => 'VARCHAR', 'constraint' => '50')
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('prod_lesson');

        


        $data_list = $this->db->get('lesson')->result();
        echo "Count - " . count($data_list) . "<br />";
        foreach ($data_list as $data) {


            $data_to_insert = array(
                'id'                        => $data->id,
                'title'                     => $data->title,
                'duration_in_second'        => $data->duration ? $data->duration : 0,
                'course_id'                 => $data->course_id,
                'section_id'                => $data->section_id,
                'summary'                   => $data->summary,
                'rank'                      => $data->order,
                'vimeo_id'                  => '',
            );


            if ($data->date_added == '' || $data->date_added == null){
                $data_to_insert['created_at'] = date('Y/m/d H:i:s');
            } else {
                $data_to_insert['created_at'] = date('Y/m/d H:i:s', $data->date_added);
            }


            if ($data->last_modified == '' || $data->last_modified == null){
                $data_to_insert['last_modified'] = date('Y/m/d H:i:s');
            } else {
                $data_to_insert['last_modified'] = date('Y/m/d H:i:s', $data->last_modified);
            }

            $this->db->insert('prod_lesson', $data_to_insert);

        }
    }

    public function convert_section(){

        $this->dbforge->drop_table('prod_section',TRUE);

        $this->dbforge->add_field('id');
        $fields = array(
            'title'                 => array('type' => 'VARCHAR', 'constraint' => '255'),
            'course_id'             => array('type' => 'INT', 'constraint' => 11),
            'rank'                  => array('type' => 'INT', 'constraint' => 11)
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('prod_section');

        


        $data_list = $this->db->get('section')->result();
        echo "Count - " . count($data_list) . "<br />";
        foreach ($data_list as $data) {


            $data_to_insert = array(
                'id'                        => $data->id,
                'title'                     => $data->title,
                'course_id'                 => $data->course_id,
                'rank'                      => $data->order
            );


            $this->db->insert('prod_section', $data_to_insert);

        }
    }

    public function convert_payment_to_enrollment_payment(){

        $this->dbforge->drop_table('prod_enrollment_payment',TRUE);

        $this->dbforge->add_field('id');
        $fields = array(
            'invoice_id'        => array('type' => 'VARCHAR', 'constraint' => '100'),
            'payment_method'    => array('type' => 'VARCHAR', 'constraint' => '15'),
            'enrollment_id'     => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'amount'            => array('type' => 'DOUBLE'),
            'created_at'        => array('type' => 'DATETIME'),
            'status'            => array('type' => 'VARCHAR', 'constraint' => '15')
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('prod_enrollment_payment');

        


        $data_list = $this->db->get('payment')->result();
        echo "Count - " . count($data_list) . "<br />";
        foreach ($data_list as $data) {


            $this->db->select('id');
            $this->db->from('enrol');
            $this->db->where('user_id', $data->user_id);
            $this->db->where('course_id', $data->course_id);

            $data_to_insert = array(
                'id'                => $data->id,
                'invoice_id'        => $data->invoice_id,
                'payment_method'    => $data->invoice_id == null || $data->invoice_id == '' ? 'manual' : 'portwallet',
                'enrollment_id'     => $this->db->get()->result()[0]->id,
                'amount'            => $data->amount,
                'status'            => strtoupper($data->purchase_status)
            );


            if ($data->date_added == '' || $data->date_added == null){
                $data_to_insert['created_at'] = date('Y/m/d H:i:s');
            } else {
                $data_to_insert['created_at'] = date('Y/m/d H:i:s', $data->date_added);
            }


            $this->db->insert('prod_enrollment_payment', $data_to_insert);

        }
    }

    public function convert_payment_to_transaction(){

        $this->dbforge->drop_table('prod_transaction',TRUE);

        $this->dbforge->add_field('id');
        $fields = array(
            'invoice_id'        => array('type' => 'VARCHAR', 'constraint' => '100'),
            'amount'            => array('type' => 'DOUBLE'),
            'status'            => array('type' => 'VARCHAR', 'constraint' => '15'),
            'created_at'        => array('type' => 'DATETIME')
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('prod_transaction');

        


        $data_list = $this->db->get('payment_info')->result();
        echo "Count - " . count($data_list) . "<br />";
        foreach ($data_list as $data) {


            $data_to_insert = array(
                'id'                => $data->id,
                'invoice_id'        => $data->invoice_id,
                'amount'            => $data->amount,
                'status'            => strtoupper($data->payment_status)
            );


            if ($data->date_added == '' || $data->date_added == null){
                $data_to_insert['created_at'] = date('Y/m/d H:i:s');
            } else {
                $data_to_insert['created_at'] = date('Y/m/d H:i:s', $data->date_added);
            }


            $this->db->insert('prod_transaction', $data_to_insert);

        }
    }

    public function convert_settings(){

        $this->dbforge->drop_table('prod_settings',TRUE);

        $this->dbforge->add_field('id');
        $fields = array(
            'key'        => array('type' => 'VARCHAR', 'constraint' => '100'),
            'value'      => array('type' => 'LONGTEXT', 'null' => TRUE)
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('prod_settings');

        


        $data_list = $this->db->get('settings')->result();
        echo "Count - " . count($data_list) . "<br />";
        foreach ($data_list as $data) {

            $this->db->insert('prod_settings', $data);

        }
    }

    public function get_course_list($return_type, $attribute_list, $filter_list = null, $limit = null){

        if ($limit != null){
            $this->db->limit($limit['limit'], $limit['offset']);
        }

        foreach ($attribute_list as $attribute) {
            $this->db->select(''.$attribute);
        }
        $this->db->from("prod_course");

        if ($filter_list !== null && count($filter_list) > 0) {
            foreach ($filter_list as $filter_key => $filter_value) {
                $this->db->where('' . $filter_key, $filter_value);
            }
        }

        if ($return_type == "OBJECT") {
            return $this->db->get()->result();
        } else if ($return_type == "COUNT"){
            return $this->db->get()->num_rows();
        } else {
            return null;
        }
    }
}