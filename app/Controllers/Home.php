<?php namespace App\Controllers;

class Home extends BaseController
{
	private $authentication;

	public function __construct()
	{
		$this->authentication = new \App\Libraries\Authentication();
	}
	public function index()
	{
		return view('welcome_message');
	}

	public function admin(){
		echo "<h1>Admin Page</h1><hr>";
		d($this->authentication->getCurrentSession());

		echo "<a href='/auth/login'>Back to Login</a> | ";
		echo "<a href='/auth/logout'>Logout</a>";
	}

}
