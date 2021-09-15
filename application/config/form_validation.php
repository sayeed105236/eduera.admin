<?php

$config = array(
	'login_form' => array(
		array(
			'field' => 'login_email',
			'label' => 'Email',
			'rules' => 'required',
		),
		array(
			'field' => 'login_password',
			'label' => 'Password',
			'rules' => 'required',
		),
	),
	'course_info_form' => array(
		array(
			'field' => 'title',
			'label' => 'Title',
			'rules' => 'required',
		),
		array(
			'field' => 'short_description',
			'label' => 'Short description',
			'rules' => 'required',
		),
		array(
			'field' => 'description',
			'label' => 'Description',
			'rules' => 'required',
		),
		array(
			'field' => 'language',
			'label' => 'Language',
			'rules' => 'required',
		),
		array(
			'field' => 'category',
			'label' => 'Category',
			'rules' => 'required',
		),
		array(
			'field' => 'level',
			'label' => 'Level',
			'rules' => 'required',
		),
		array(
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'required',
		),
		array(
			'field' => 'preview_video_id',
			'label' => 'Preview Video id',
			'rules' => 'integer',
		),

		array(
			'field' => 'expiry_month',
			'label' => 'Expired Month',
			'rules' => 'integer|greater_than[0]',
		),
	),
	'course_section_form' => array(
		array(
			'field' => 'title',
			'label' => 'Title',
			'rules' => 'required',
		),
		array(
			'field' => 'order',
			'label' => 'Order',
			'rules' => 'required|integer|greater_than[0]',
		),
		array(
			'field' => 'course_id',
			'label' => 'Course ID',
			'rules' => 'required|integer',
		),
	),
	'course_lesson_form' => array(
		array(
			'field' => 'title',
			'label' => 'Title',
			'rules' => 'required',
		),
		array(
			'field' => 'order',
			'label' => 'Order',
			'rules' => 'required|integer|greater_than[0]',
		),
		// array(
		// 	'field' => 'vimeo_id',
		// 	'label' => 'Vimeo ID',
		// 	'rules' => 'required',
		// ),
	),
	'enroll_user_form' => array(
		array(
			'field' => 'course_id',
			'label' => 'Course',
			'rules' => 'required',
		),
		array(
			'field' => 'inserted_price',
			'label' => 'Price',
			'rules' => 'required|integer',
		),
		array(
			'field' => 'access',
			'label' => 'Access',
			'rules' => 'integer|less_than_equal_to[100]|greater_than_equal_to[0]',
		),
		array(
			'field' => 'expiry_date',
			'label' => 'Expired Date',
			
		),
	),
	'assign_instructor_form' => array(
		array(
			'field' => 'course_id',
			'label' => 'Course',
			'rules' => 'required',
		),
		array(
			'field' => 'profit_share',
			'label' => 'Profit share',
			'rules' => 'required|integer|less_than_equal_to[100]|greater_than_equal_to[0]',
		),
	),
	'instructor_payment_form' => array(
		
		array(
			'field' => 'withdraw_amount',
			'label' => 'Withdraw Amount',
			'rules' => 'required|integer|greater_than_equal_to[0]',
		),
	),
	'user_info_form' => array(
		array(
			'field' => 'first_name',
			'label' => 'First name',
			'rules' => 'required',
		),
		array(
			'field' => 'last_name',
			'label' => 'Last name',
			'rules' => 'required',
		),
		array(
			'field' => 'phone',
			'label' => 'Phone',
			'rules' => 'required',
		),
		array(
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'required',
		),
	),

	'user_role_form' => array(
		array(
			'field' => 'role_name[]',
			'label' => 'Role name',
			'rules' => 'required',
		),

	),
	'course_question_form' => array(
		array(
			'field' => 'question',
			'label' => 'Question title',
			'rules' => 'required',
		),
		array(
			'field' => 'option[]',
			'label' => 'Option',
			'rules' => 'required',
		),
		array(
			'field' => 'right_option',
			'label' => 'Right Option',
			'rules' => 'required',
		),

	),
	'user_course_payemnt_form' => array(
		array(
			'field' => 'enrollment_id',
			'label' => 'Enrollment id',
			'rules' => 'required',
		),
		array(
			'field' => 'amount',
			'label' => 'Amount',
			'rules' => 'required|integer',
		),

	),

	'course_quiz_set_form' => array(
		array(
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'required',
		),

	),

	'faq_cat_form' => array(
		array(
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'required',
		),

	),
	'faq_form' => array(
		array(
			'field' => 'question',
			'label' => 'Question',
			'rules' => 'required',
		),
		array(
			'field' => 'answer',
			'label' => 'Answer',
			'rules' => 'required',
		),

	),
	'coupon_form' => array(
		array(
			'field' => 'course_id',
			'label' => 'Course id',
			'rules' => 'required',
		),
		array(
			'field' => 'coupon_code',
			'label' => 'Coupon Code',
			'rules' => 'required|min_length[5]|max_length[10]',
		),

		array(
			'field' => 'start_date',
			'label' => 'Start Date',
			'rules' => 'required',
		),

		array(
			'field' => 'end_date',
			'label' => 'End Date',
			'rules' => 'required',
		),

		array(
			'field' => 'discount',
			'label' => 'Discount',
			'rules' => 'required|greater_than_equal_to[0]',
		),

		array(
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'required',
		),

	),
	
	'user_message_form' => array(
		// array(
		// 	'field' => 'user_id[]',
		// 	'label' => 'User Id',
		// 	'rules' => 'required',
		// ),
		array(
			'field' => 'message',
			'label' => 'Message',
			'rules' => 'required',
		),

	),

	'user_email_form' => array(
		// array(
		// 	'field' => 'user_id[]',
		// 	'label' => 'User Id',
		// 	'rules' => 'required',
		// ),
		array(
			'field' => 'subject',
			'label' => 'Subject',
			'rules' => 'required',
		),
		array(
			'field' => 'body',
			'label' => 'Body',
			'rules' => 'required',
		),

	),

	'announcement_form' => array(
		
		array(
			'field' => 'title',
			'label' => 'Title',
			'rules' => 'required',
		)

	),

	'currency_form' => array(
		array(
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'required',
		),
		array(
			'field' => 'value',
			'label' => 'Value',
			'rules' => 'required|greater_than_equal_to[0]',
		),

		

	),

);

?>