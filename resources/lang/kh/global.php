<?php

return [
	
	'user-management' => [
		'title' => 'គ្រប់គ្រងអ្នកប្រើប្រាស់',
		'created_at' => 'ម៉ោង',
		'fields' => [
		],
	],
	
	'permissions' => [
		'title' => 'សិទ្ធ',
		'created_at' => 'ម៉ោង',
		'fields' => [
			'name' => 'ឈ្មោះ',
		],
	],

	'sites' => [
		'title' => 'មន្ទីរពេទ្យ',
		'created_at' => 'ម៉ោង',
		'fields' => [
			'username' => 'ឈ្មោះអ្នកប្រើប្រាស់',
			'sitecode' => 'លេខកូដមន្ទីរពេទ្យ',
			'province' => 'ខេត្ត',
			'name' => 'Site EN',
			'site' => 'Site KH',
		],
	],
	
	'roles' => [
		'title' => 'តួនាទី',
		'created_at' => 'ម៉ោង',
		'fields' => [
			'name' => 'ឈ្មោះ',
			'permission' => 'សិទ្ធ',
		],
	],
	
	'users' => [
		'title' => 'អ្នកប្រើប្រាស់',
		'created_at' => 'ម៉ោង',
		'fields' => [
			'name' => 'ឈ្មោះ',
			'email' => 'អ៊ីម៉ែល',
			'password' => 'លេខសម្ងាត់',
			'roles' => 'តួនាតី',
			'remember-token' => 'Remember token',
		],
		
	],
	'reporting' => [
		'patient_title' => 'របាយការណ៍ការផ្តល់ព័ត៌មានត្រឡប់របស់អ្នកជំងឺ',
		'created_at' => 'ម៉ោង',
		
		],
		
	'hfs' => [
		'title' => 'របាយការណ៍របស់បុគ្គលិកសុខាភិបាល',
		'created_at' => 'ម៉ោង',
		
		],
	
	'app_create' => 'បង្កើតថ្មី',
	'app_save' => 'រក្សាទុក',
	'app_edit' => 'កែប្រែ',
	'app_view' => 'ចូលមើល',
	'app_update' => 'បច្ចុប្បន្នភាព',
	'app_list' => 'តារាង',
	'app_no_entries_in_table' => 'មិនមានលទ្ធផល',
	'custom_controller_index' => 'Custom controller index.',
	'app_logout' => 'ចាកចេញ',
	'app_add_new' => 'បង្កើតថ្មី',
	'app_are_you_sure' => 'តើអ្នកពិតជាចង់បន្តទៀត?',
	'app_back_to_list' => 'ត្រឡប់ក្រោយ',
	'app_dashboard' => 'ប៉ាណូបរិធាន',
	'app_delete' => 'លុប',
	'global_title' => 'PSF',
	'app_change_pwd' =>'ប្តូរលេខសម្ងាត់',
	'app_filter' => 'លក្ខខណ្ឌ',
	'app_startdate' => 'ចាប់ពី',
	'app_enddate' => 'រហូតដល់',

];