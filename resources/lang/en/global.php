<?php

return [
	
	'user-management' => [
		'title' => 'User Management',
		'created_at' => 'Time',
		'fields' => [
		],
	],
	
	'permissions' => [
		'title' => 'Permissions',
		'created_at' => 'Time',
		'fields' => [
			'name' => 'Name',
		],
	],

	'sites' => [
		'title' => 'Sites',
		'created_at' => 'Time',
		'fields' => [
			'username' => 'User Name',
			'sitecode' => 'Site Code',
			'province' => 'Province',
			'name' => 'Site EN',
			'site' => 'Site KH',
		],
	],
	
	'roles' => [
		'title' => 'Roles',
		'created_at' => 'Time',
		'fields' => [
			'name' => 'Name',
			'permission' => 'Permissions',
		],
	],
	
	'users' => [
		'title' => 'Users',
		'created_at' => 'Time',
		'fields' => [
			'name' => 'Name',
			'email' => 'Email',
			'password' => 'Password',
			'roles' => 'Roles',
			'remember-token' => 'Remember token',
		],
		
	],
	'reporting' => [
		'patient_title' => 'PSF Patient Report',
		'created_at' => 'Time',
		
		],
		
	'hfs' => [
		'title' => 'Health Care Worker Report',
		'created_at' => 'Time',
		
		],
	
	'app_create' => 'New',
	'app_save' => 'Save',
	'app_edit' => 'Edit',
	'app_view' => 'Filter',
	'app_update' => 'Update',
	'app_list' => 'List',
	'app_no_entries_in_table' => 'No entries in table',
	'custom_controller_index' => 'Custom controller index.',
	'app_logout' => 'Logout',
	'app_add_new' => 'Add new',
	'app_are_you_sure' => 'Are you sure?',
	'app_back_to_list' => 'Back to list',
	'app_dashboard' => 'Dashboard',
	'app_delete' => 'Delete',
	'global_title' => 'PSF Report System',
	'app_change_pwd' =>'Change password',
	'app_filter' => 'Filter',
	'app_startdate' => 'Start Date',
	'app_enddate' => 'End Date',
];