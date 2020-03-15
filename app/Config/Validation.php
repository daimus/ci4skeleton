<?php

namespace Config;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var array
	 */
	public $ruleSets = [
		\CodeIgniter\Validation\Rules::class,
		\CodeIgniter\Validation\FormatRules::class,
		\CodeIgniter\Validation\FileRules::class,
		\CodeIgniter\Validation\CreditCardRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------

	public $login = [
		'email' => [
			'label'  => 'Email',
			'rules'  => 'required',
			'errors' => [
				'required' => '{field} is required.'
			]
		],
		'password' => [
			'label'  => 'Password',
			'rules'  => 'required',
			'errors' => [
				'required' => '{field} is required.'
			]
		]
	];

	public $register = [
		'name' => [
			'label'  => 'Name',
			'rules'  => 'required',
			'errors' => [
				'required' => '{field} is required.'
			]
		],
		'email' => [
			'label'  => 'Email',
			'rules'  => 'required',
			'errors' => [
				'required' => '{field} is required.'
			]
		],
		'password' => [
			'label'  => 'Password',
			'rules'  => 'required',
			'errors' => [
				'required' => '{field} is required.'
			]
		]
	];
}
