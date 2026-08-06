<?php

namespace App\Controllers\Open;

use App\Controllers\BaseController;

use CodeIgniter\I18n\Time;

use App\Models\User_login\Login_model;

#[\AllowDynamicProperties]
class Login_forgot_password extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->login_model = new Login_model();
    }
    public function forgotpasswordVerify()
    {
        helper(['form']);

        // Get JSON input from Angular
        $json = $this->request->getJSON(true); // true = return as array
        $email = isset($json['email']) ? filter_var($json['email'], FILTER_SANITIZE_EMAIL) : null;

        if (empty($email)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email is required'
            ]);
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid email address'
            ]);
        }

        // Check email in database
        $userdata = $this->login_model->verifyEmail($email);

        if (empty($userdata)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => "If the email address is registered, a password reset link has been sent."
            ]);
        }

        // Update token time, send email etc.
        if ($this->login_model->updateAt($userdata['userid'])) {
            $to = $email;
            $subject = 'Reset Password Link';
            $token = $userdata['userid'];
            $resetLink = base_url('forgot_password/reset_password/' . $token);

            $message = 'Hi ' . esc($userdata['name']) . ',<br><br>'
                . 'Your reset password request has been received.<br><br>'
                . '<a href="' . $resetLink . '" target="_blank">Click here to Reset Password</a><br><br>'
                //. '<small>Note: Reset link expires in 15 minutes.</small><br><br>'
                . 'Thanks,<br>Dochek Team';

            $emailService = \Config\Services::email();
            $emailService->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
            $emailService->setTo($to);
            $emailService->setSubject($subject);
            $emailService->setMessage($message);

            if ($emailService->send()) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'If the email address is registered, a password reset link has been sent. Please verify within 15 minutes.'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Unable to send email. Please try again.'
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => false,
            'message' => 'Unable to process request.'
        ]);
    }

    public function index()
    {
        $data = [];
        helper(['form']);
        $data['landing_type'] = 2;
        if ($this->request->getPost()) {
            $rules = [
                'email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => '{field} field required',
                        'valid_email' => 'Vaild {field} required',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $email = $this->request->getVar('email', FILTER_SANITIZE_EMAIL);
                $userdata = $this->login_model->verifyEmail($email);
                if (!empty($userdata)) {
                    if ($this->login_model->updateAt($userdata['userid'])) {
                        $to = $email;
                        $subject = 'Reset Password Link';
                        $token = $userdata['userid'];
                        $message = 'Hi ' . $userdata['name'] . '<br><br>'
                            . 'Your reset password request has been received. Please click '
                            . 'the below link to reset your password.<br><br>'
                            . '<a href="' . base_url('forgot_password/reset_password/' . $token) . '" target="_blank">Click here to Reset Password</a><br><br>'
                            // . '<small>Note : Reset Password link will be expire after 15 mins.Please reset your password with in time limit.</small><br><br>'
                            . 'Thanks,<br>'
                            . 'Dochek Team';
                        $email = \Config\Services::email();
                        $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
                        $email->setTo($to);
                        // $email->setCC('another@another-example.com');
                        //$email->setBCC('them@their-example.com');

                        $email->setSubject($subject);
                        $email->setMessage($message);

                        if ($email->send()) {
                            session()->setFlashdata('success', "If the email address is registered, a password reset link has been sent.");
                            return redirect()->to(base_url('/'));
                        } else {
                            $edata = $email->printDebugger(['headers']);
                            print_r($edata);
                        }
                    } else {
                        session()->setFlashdata('error', "Sorry! unable to update. try again");
                        return redirect()->to(base_url('forgot_password'));
                    }
                } else {
                    session()->setFlashdata('error', "Email doesn't exists");
                    return redirect()->to(base_url('forgot_password'));
                }
            }
        }
        echo view('landing/header', $data);
        echo view('landing/index', $data);
        echo view('landing/footer', $data);
    }
    public function reset_password($token = NULL)
    {
        $data = [];
        $data['landing_type'] = 3; // refer landing/index.php
        $data['token'] = $token; // Pass the token to the view
        if (!empty($token)) {
            $userdata = $this->login_model->verifyToken($token);
            if (!empty($userdata)) {
                if ($this->request->getPost()) {
                    $rules = [
                        // 'password' => 'required|max_length[255]',
                        // 'password_confirm' => 'matches[password]',
                        'password' => 'required',
                        'password' => [
                            'label' => 'Password',
                            'rules' => 'required|min_length[8]|max_length[64]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/]',
                            'errors' => [
                                'regex_match' => 'Password must include uppercase, lowercase, number, and special character.'
                            ]
                        ],
                        'password_confirm' => 'required|matches[password]'
                    ];
                    if (!$this->validate($rules)) {
                        $data['validation'] = $this->validator;
                    } else {
                        $pwd = trim(password_hash($this->request->getVar('password'), PASSWORD_DEFAULT));
                        if ($this->login_model->updatePassword($token, $pwd)) {

                            // 🔒 Invalidate token after successful use
                            $this->login_model->invalidateToken($token);

                            // session()->setFlashdata('success', "Password reset successfully. You can now login with your new password.");
                            //  $data['success'] = "Password reset successfully. You can now login with your new password.";
                            // return redirect()->to(base_url('/ang/login'));
                            return redirect()->to(base_url() . 'ang/login?success=Password reset successfully. You can now login with your new password.');
                        } else {
                            session()->setFlashdata('error', "Sorry! Unable to update password.Try again");
                            return redirect()->to(current_url());
                        }
                    }
                }
            } else {
                $data['error'] = "This password reset link is no longer valid.";
            }
        } else {
            $data['error'] = 'Sorry! Unauthourized access';
        }
        // echo view('landing/header', $data);
        echo view('landing/reset_password', $data);
        // echo view('landing/footer', $data);
    }
    function checkExpiryDate($time)
    {
        $update_time = $time;
        $end_time = strtotime("+15 minutes", strtotime($update_time));
        $endtime = date('Y-m-d h:i:s', $end_time);
        $currenttime = date('Y-m-d h:i:s');
        if ($currenttime <= $endtime) {
            return true;
        } else {
            return false;
        }
    }
}
