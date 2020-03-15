<?php

namespace App\Controllers;

// use App\Libraries\Authentication;

class Auth extends BaseController
{

    private $authentication;

    public function __construct()
    {
        $this->authentication = new \App\Libraries\Authentication();
    }

    public function login()
    {
        $validation =  \Config\Services::validation();
        if ($this->request->getPost()){
            if ($this->validate('login')){
                $params = array(
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => $this->request->getUserAgent()->__toString(),
                    'geolocation' => $this->request->getVar('geolocation')
                );
                $response = $this->authentication->login($this->request->getVar('email'), $this->request->getVar('password'), $params);
    
                if ($response['error']) {
                    $session = \Config\Services::session();
                    $session->setFlashdata('message', $response['message']);
                } else {
                    return redirect()->to('/home/admin');
                }
            }
        }
        echo view('auth/login', ['validation' => $validation]);
    }

    public function register()
    {
        $validation =  \Config\Services::validation();
        if ($this->request->getPost()){
            if ($this->validate('register')){
                $response = $this->authentication->register($this->request->getVar('name'), $this->request->getVar('email'), $this->request->getVar('password'));
    
                if ($response['error']) {
                    $session = \Config\Services::session();
                    $session->setFlashdata('message', $response['message']);
                } else {
                    echo view('auth/register_success');
                    return;
                }
            }
        }
        echo view('auth/register', ['validation' => $validation]);
    }

    public function activate()
    {
        $token = $this->request->getVar('token');
        $response = $this->authentication->activate($token);
        if ($response['error']){
            
        } else {
            echo view('auth/email_verified');
        }
    }

    public function logout()
    {
    }

    public function tes(){
        $v = view('email/email_verification', ['data' => ['name' => 'daimus', 'link' => 'http://aaa.com']]);
        d($v);
    }
}
