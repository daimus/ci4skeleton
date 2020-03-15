<?php

namespace App\Libraries;

class Authentication
{

    private $user;
    private $attempt;
    private $session;

    public function __construct()
    {
        $this->user = new \App\Models\UserModel();
        $this->attempt = new \App\Models\AttemptModel();
        $this->session = new \App\Models\SessionModel();
    }

    public function login($email, $password, $params)
    {
        // Initialize response
        $response['error'] = TRUE;

        // Get user with @email
        $user = $this->user->findByEmail($email);
        if (empty($user)) {
            $response['message'] = 'user not found';
            return $response;
        }

        // Check if user is validated
        if ( ! $user->is_validated){
            $response['message'] = 'user is inactive';
            return $response;
        }

        $data['user_id'] = $user->id;

        // Validate password
        if (!password_verify($password, $user->password)) {
            $response['message'] = 'invalid password';
            $this->addAttempt(array_merge($data, $params, $response));
            return $response;
        }

        // Return response
        $response['error'] = FALSE;
        $response['message'] = 'ok';

        $this->addAttempt(array_merge($data, $params, $response));
        $this->ignoreAttempts($user->id);

        $this->createSession(array_merge($data, $params));

        return $response;
    }

    public function register($name, $email, $password, $require_validation = TRUE)
    {
        // Initialize response
        $response['error'] = TRUE;

        // Check if email is already registred
        if (!empty($this->user->findByEmail($email))) {
            $response['message'] = 'email exist';
            return $response;
        }

        // Insert user to database
        $data['name'] = $name;
        $data['email'] = $email;
        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        $data['validate_token'] = $this->generateToken();
        $data['is_validated'] = ($require_validation) ? 0 : 1;
        $query = $this->user->insert($data);

        // Check insert user query
        if (!$query) {
            $response['message'] = 'error when executing queries';
            return $response;
        }

        // Send activation email
        if ($require_validation) {
            $message = view('email/email_verification', ['data' => ['name' => $name, 'link' => base_url('/auth/activate?token='.$data['validate_token'])]]);
            $mail = $this->sendMail($email, 'Confirm your email address!', $message);
            if ( ! $mail){
                $response['message'] = 'error when sending email';
                return $response;
            }
        }

        // Return response
        $response['error'] = FALSE;
        $response['message'] = 'ok';
        return $response;
    }

    public function activate($token)
    {
        $response['error'] = TRUE;
        $query = $this->user->where('validate_token', $token)->set(['validate_token' => '', 'is_validated' => 1])->update();
        if ($query){
            $response['error'] = FALSE;
            $response['message'] = 'ok';
        } else {
            $response['message'] = 'error when executing queries';
        }
        return $response;
    }

    public function requestReset()
    {
    }

    public function logout()
    {
        $this->deleteSession($this->getCurrentHash());
        delete_cookie('session');
    }

    protected function addAttempt($data)
    {
        return $this->attempt->save($data);
    }

    protected function clearAttempt($user_id)
    {
        return $this->attempt->where('user_id', $user_id)->delete();
    }

    protected function ignoreAttempts($user_id)
    {
        return $this->attempt->where('user_id', $user_id)->set(['ignored' => 1])->update();
    }

    protected function createSession($user_data, $remember = FALSE)
    {
        helper('cookie');

        if ($remember) {
            $expire_date = strtotime('+30d', time());
        } else {
            $expire_date = strtotime('+1d', time());
        }

        $session_data = array(
            'user_id' => $user_data['user_id'],
            'hash' => hash('sha256', rand()),
            'ip_address' => $user_data['ip_address'],
            'user_agent' => $user_data['user_agent'],
            'expire' => date('Y-m-d H:i:s', $expire_date),
            'cookie' => hash('sha256', rand())
        );

        set_cookie('session', $session_data['hash'], (int) $expire_date);

        return $this->saveSession($session_data);
    }

    protected function saveSession($data)
    {
        return $this->session->save($data);
    }

    protected function getCurrentHash()
    {
        helper('cookie');
        return get_cookie('session');
    }

    public function getCurrentSession($hash = null)
    {
        if (empty($hash)) {
            $hash = $this->getCurrentHash();
        }

        $session_data = $this->getSession($hash);

        if (empty($session_data)) {
            return false;
        }

        return $this->user->find($session_data['user_id']);
    }

    private function getSession($hash)
    {
        return $this->session->where('hash', $hash)->first();
    }

    private function deleteSession($hash)
    {
        return $this->session->where('hash', $hash)->delete();
    }

    private function clearSession($user_id)
    {
        return $this->session->where('user_id', $user_id)->delete();
    }

    private function generateToken()
    {
        $str = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(50 / strlen($x)))), 1, 50);
        return strtr(base64_encode($str), '+/=', '._-');
    }

    private function decodeToken($token)
    {
        base64_decode(strtr($token, '._-', '+/='));
    }

    private function sendMail($to, $subject, $message)
    {
        $email = \Config\Services::email();

        $config['mailType']  = 'html';
        $config['charset']  = 'utf-8';
        $config['protocol'] = 'smtp';
        $config['SMTPHost'] = 'smtp.gmail.com';
        $config['SMTPUser'] = 'daimuslab@gmail.com';
        $config['SMTPPass'] = 'qq1ww2ee3rr4';
        $config['SMTPCrypto'] = 'ssl';
        $config['SMTPPort'] = 465;

        $email->initialize($config);
        $email->setFrom('daimuslab@gmail.com', 'Event Management System');
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);
        return $email->send();
    }
}
