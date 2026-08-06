<?php

namespace App\Controllers\User_login;

use App\Controllers\BaseController;
use ZipArchive;
use App\Models\User_login\Login_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;
use App\Models\Settings\Settings_model;
use App\Models\SCORM\Scorm_client_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\XAPI\XAPI_scenarios_model;
use App\Models\Assessment\Assessment_training_model;
use App\Models\SCORM\Scorm_learn_group_model;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[\AllowDynamicProperties]
class Client_users extends BaseController
{
    private $db;

    public function __construct()
    {

        // print_r($arrayuserlevel);
        // exit();
        $this->is_session_available();
        $this->login_model = new Login_model();
        $this->xapi_scenarios_model = new XAPI_scenarios_model();
        $this->dropdown_model = new Dropdown_model();
        $this->scorm_client_model = new Scorm_client_model();
        $this->users_model = new Users_model();
        $this->settings_model = new Settings_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->assessment_training_model = new Assessment_training_model();
        $this->scorm_learn_group_model = new Scorm_learn_group_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('44', $arrayuserlevel)) {

            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $clientid = session()->get('client');
        $data['clientid'] = $clientid;
        $data['usertable'] = $this->login_model->users_view($clientid);
        if ($data['usertable'][0]['client_id'] != $clientid) {
            return redirect()->to('my_training')->with('error', 'Unauthorized Access');
        }
        $data['clientlicense'] = $this->settings_model->clientlicensedata($clientid);

        $data['active_users_count'] = $this->users_model->countUsersByStatus($clientid, 1);
        $data['inactive_users_count'] = $this->users_model->countUsersByStatus($clientid, 0);
        $data['total_users_count'] = $data['active_users_count'] + $data['inactive_users_count'];
        $data['admin_users_count'] = $this->users_model->countUsersByRole($clientid, 44);
        $data['trainer_users_count'] = $this->users_model->countUsersByRole($clientid, 5);

        $data['temppass'] = $this->generateTempPassword(12);
        echo view('templates/header_view', $data);
        echo view('users/client_users_view', $data);
        echo view('templates/footer_view');
    }

    function clientregister()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $model = new Login_model();
        $data['clientid'] = base64_decode($this->request->getVar('cid'));
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $data['temppass'] = $this->generateTempPassword(12);
        echo view('templates/header_view', $data);
        echo view('users/users_clientadd_view', $data);
        echo view('templates/footer_view');
    }
    // function addclientregister()
    // {
    //     if ($response =  $this->requireRole(['6', '44', '4'])) {
    //         return $response;
    //     }
    //     $data = [];
    //     helper(['form']);
    //     $model = new Login_model();
    //     $data['clientid'] = $this->request->getVar('cid');
    //     $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
    //     // $data['timezone'] = $this->login_model->gettimezonedata();
    //     $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    //     $password = array();
    //     $alpha_length = strlen($alphabet) - 1;
    //     for ($i = 0; $i < 8; $i++) {
    //         $n = rand(0, $alpha_length);
    //         $password[] = $alphabet[$n];
    //     }
    //     $data['temppass'] = $this->generateTempPassword(12);
    //     if ($this->request->getPost()) {
    //         $rules = [
    //             'name' => [
    //                 'label' => 'First Name',
    //                 'rules' => ['required', 'min_length[1]', 'max_length[32]'],

    //             ],
    //             'last_name' => [
    //                 'label' => 'Last Name',
    //                 'rules' => ['required'],

    //             ],
    //             'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/]',
    //             'userlevelItem' => 'required',
    //             'password' => 'required|max_length[255]',
    //             // 'app_username' => [
    //             //     'label' => 'App Username',
    //             //     'rules' => ['required', 'max_length[10]', 'is_unique[users.app_username]'],
    //             //     'errors' => [
    //             //         'required' => 'The App Username field is required.',
    //             //         'max_length' => 'The App Username field cannot exceed 10 characters.', // Corrected max_length
    //             //     ],
    //             // ],
    //             // 'app_password' => [
    //             //     'label' => 'App Password',
    //             //     'rules' => ['required', 'max_length[255]'],
    //             //     'errors' => [
    //             //         'required' => 'The App Password field is required.',
    //             //         'max_length' => 'The App Password field cannot exceed 255 characters.', // Corrected max_length
    //             //     ],
    //             // ],
    //             // 'timezone' => 'required',
    //             // 'lang' => 'required',

    //         ];
    //         if (!$this->validate($rules)) {
    //             // $data['validation'] = $this->validator;
    //             if (! $this->validate($rules)) {

    //                 $data['validation'] = $this->validator;
    //                 $data['clientid']   = $this->request->getPost('cid');
    //                 $data['temppass']   = $this->request->getPost('password');
    //                 $clientid = session()->get('client');
    //                 $data['clientid'] = $clientid;
    //                 $data['usertable'] = $this->login_model->users_view($clientid);
    //                 $data['clientlicense'] = $this->settings_model->clientlicensedata($clientid);
    //                 return view('templates/header_view', $data)
    //                     . view('users/client_users_view', $data)
    //                     . view('templates/footer_view');
    //             }
    //         } else {

    //             $timestamp = time();
    //             $last_name = $this->request->getVar('last_name');
    //             $last_name = isset($last_name) ? $last_name : '';
    //             //  $designation = $this->request->getVar('designation');
    //             //  $designation = isset($designation) ? $designation : '';
    //             $newdata = [
    //                 'name' => $this->request->getVar('name'),
    //                 'last_name' => $last_name,
    //                 'username' => $this->request->getVar('email'),
    //                 'email' => $this->request->getVar('email'),
    //                 //'designation' =>  $designation,
    //                 'password' => trim(password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)),
    //                 'app_username' => $this->request->getVar('app_username'),
    //                 'app_password' => $this->request->getVar('app_password'),
    //                 'timezone' => $this->request->getVar('timezone'),
    //                 'lang' => $this->request->getVar('lang'),
    //                 'userid' => uniqid(),
    //                 'timestamp' => $timestamp,
    //                 'client_id' => $this->request->getVar('cid'),
    //                 'valid' => '1',
    //                 'createdby' => session()->get('id_user'),
    //                 'createdon' => time(),
    //                 'last_updated_by' => session()->get('id_user'),
    //                 'last_updated_on' => time(),
    //             ];
    //             if ($model->save($newdata)) {
    //                 $insertedid = $model->InsertID();
    //                 if ($this->request->getVar('cid')) {
    //                     $userdata['fk_id_user'] = $insertedid;
    //                     $userdata['fk_id_dc'] = 1;
    //                     $userdata['fk_id_d'] = $this->request->getVar('cid');
    //                     $userdata['status'] = 1;
    //                     $userdata['createdby'] = session()->get('id_user');
    //                     $userdata['createdon'] = time();
    //                     $result = $this->dropdown_model->updateCategory($userdata); //add client in dropdown_users table
    //                 }
    //                 if ($this->request->getVar('userlevelItem')) {
    //                     $userleveldata['fk_id_user'] = $insertedid;
    //                     $userleveldata['fk_id_dc'] = 2;
    //                     $userleveldata['fk_id_d'] = $this->request->getVar('userlevelItem');
    //                     $userleveldata['status'] = 1;
    //                     $userleveldata['createdby'] = session()->get('id_user');
    //                     $userleveldata['createdon'] = time();
    //                     $result = $this->dropdown_model->updateCategory($userleveldata); //add roles in dropdown_users table
    //                 }
    //                 if ($result) {
    //                     $session = session();
    //                     $session->setFlashdata('success', 'Successful Registration');
    //                     return redirect()->to(base_url() . 'User_login/client_users');
    //                 }
    //             }
    //             $session = session();
    //             $session->setFlashdata('error', 'Data not found, Try again');
    //             return redirect()->to(base_url() . 'User_login/client_users');
    //         }
    //     }
    //     echo view('templates/header_view', $data);
    //     echo view('users/users_clientadd_view', $data);
    //     echo view('templates/footer_view');
    // }
    public function addclientregister()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $session = session();
        $loggedInUserId = $session->get('id_user');
        $clientId = $session->get('client');

        if (!$loggedInUserId || !$clientId) {
            return redirect()->to(base_url())->with('error', 'Unauthorized access');
        }

        $data['clientid'] = $clientId;
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);

        // Generate temporary password
        $data['temppass'] = $this->generateTempPassword(12);

        $model = new Login_model();

        // Handle POST request
        if ($this->request->getPost()) {
            $rules = [
                'name' => 'required|min_length[1]|max_length[32]',
                'last_name' => 'required',
                'email' => 'required|valid_email|max_length[50]|is_unique[users.email]',
                // 'password' => 'required|max_length[255]',
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]|max_length[64]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/]',
                    'errors' => [
                        'regex_match' => 'Password must include uppercase, lowercase, number, and special character.'
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                $data['temppass'] = $this->request->getPost('password');
                $data['usertable'] = $this->login_model->users_view($clientId);
                $data['clientlicense'] = $this->settings_model->clientlicensedata($clientId);

                return view('templates/header_view', $data)
                    . view('users/client_users_view', $data)
                    . view('templates/footer_view');
            }

            // Get the role of the logged-in user
            $userRole = $this->dropdown_model->getAllowedRoles($loggedInUserId); // should return stdClass with fk_id_d
            // print_r($userRole);
            // exit();
            $requestedRole = ($this->request->getVar('userlevelItem')) ? $this->request->getVar('userlevelItem') : 3; // default to 3 if not set
            $userlevelItem = $requestedRole;
            // print_r($requestedRole);
            // exit();
            if (!in_array($requestedRole, $userRole)) {
                return redirect()->back()->with('error', 'You do not have permission to assign this role.');
            }

            // Prepare new user data
            $newdata = [
                'name' => $this->request->getVar('name'),
                'last_name' => $this->request->getVar('last_name') ?? '',
                'username' => $this->request->getVar('email'),
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'app_username' => $this->request->getVar('app_username'),
                'app_password' => ($this->request->getVar('app_password')) ? $this->request->getVar('app_password') : 'Welcome123',
                'timezone' => $this->request->getVar('timezone'),
                'lang' => $this->request->getVar('lang'),
                'userid' => bin2hex(random_bytes(8)),
                'timestamp' => time(),
                'client_id' => $clientId, // from session
                'valid' => '1',
                'createdby' => $loggedInUserId,
                'createdon' => time(),
                'last_updated_by' => $loggedInUserId,
                'last_updated_on' => time(),
            ];

            // Database transaction
            $db = \Config\Database::connect();
            $db->transStart();
            $result = false;

            if ($model->save($newdata)) {
                $insertedId = $model->getInsertID();

                // Map user to client
                $userdata = [
                    'fk_id_user' => $insertedId,
                    'fk_id_dc' => 1,
                    'fk_id_d' => $clientId,
                    'status' => 1,
                    'createdby' => $loggedInUserId,
                    'createdon' => time(),
                ];
                $result = $this->dropdown_model->updateCategory($userdata);

                // Map role
                $userleveldata = [
                    'fk_id_user' => $insertedId,
                    'fk_id_dc' => 2,
                    'fk_id_d' => $requestedRole,
                    'status' => 1,
                    'createdby' => $loggedInUserId,
                    'createdon' => time(),
                ];
                $result = $this->dropdown_model->updateCategory($userleveldata) && $result;
            }

            $db->transComplete();

            if ($db->transStatus() === false || !$result) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.');
            }

            return redirect()->to(base_url('User_login/client_users'))
                ->with('success', 'User registered successfully');
        }

        // Initial page load
        return view('templates/header_view', $data)
            . view('users/users_clientadd_view', $data)
            . view('templates/footer_view');
    }
    function generateTempPassword($length = 12)
    {
        $lower   = 'abcdefghijklmnopqrstuvwxyz';
        $upper   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $special = '!@#$%^&*()_+-=[]{}|;:<>?';

        $password = '';
        $password .= $lower[random_int(0, strlen($lower) - 1)];
        $password .= $upper[random_int(0, strlen($upper) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];

        $allChars = $lower . $upper . $numbers . $special;
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Secure shuffle (Fisher-Yates)
        $passwordArray = str_split($password);
        for ($i = count($passwordArray) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $temp = $passwordArray[$i];
            $passwordArray[$i] = $passwordArray[$j];
            $passwordArray[$j] = $temp;
        }

        return implode('', $passwordArray);
    }
    public function exportUsers()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $rowCount = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Username');
        $sheet->setCellValue('B1', 'First Name');
        $sheet->setCellValue('C1', 'App Username');
        $sheet->setCellValue('D1', 'App Password');
        $sheet->setCellValue('E1', 'Last Active');

        $cid = $this->request->getPost('cid');
        // exit();
        $headerStyle = $sheet->getStyle('A1:B1');
        $headerStyle->getFont()->setBold(true);
        $userclientlist = $this->users_model->getUserclientlist($cid);
        // print_r($userclientlist);
        // exit();
        if (isset($userclientlist)) {
            if (count($userclientlist) > 0) {
                foreach ($userclientlist as $eachuserclientddata) {
                    $username = strip_tags($eachuserclientddata['username']);
                    $first_name = strip_tags($eachuserclientddata['name']);
                    $app_username = strip_tags($eachuserclientddata['app_username']);
                    $app_password = strip_tags($eachuserclientddata['app_password']);
                    $lastActive = ($eachuserclientddata['dateandtime'] != '') ? date("m-d-Y", $eachuserclientddata['dateandtime']) : '';
                    $lastActive = strip_tags($lastActive);

                    $sheet->setCellValue('A' . $rowCount, $username);
                    $sheet->SetCellValue('B' . $rowCount, $first_name);
                    $sheet->setCellValue('C' . $rowCount, $app_username);
                    $sheet->setCellValue('D' . $rowCount, $app_password);
                    $sheet->setCellValue('E' . $rowCount, $app_password);
                    $sheet->setCellValue('E' . $rowCount, $lastActive);

                    $sheet->getRowDimension($rowCount)->setRowHeight(20);
                    $rowCount = $rowCount + 1;
                }
            }
        }

        $filename = 'Users_details.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Save the file to a temporary location
        $tempFilePath = sys_get_temp_dir() . '/' . $filename;
        $writer->save($tempFilePath);

        // Set the appropriate headers and offer the file as a download
        return $this->response->download($tempFilePath, null)->setFileName($filename);
    }
    public function importUsers()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        echo view('templates/header_view', $data);
        echo view('users/usersbulkimport_view', $data);
        echo view('templates/footer_view');
    }
    function bulkUsers()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        // print_r($_FILES);
        // exit();
        if (isset($_FILES)) {

            $rules = [
                'file' => 'uploaded[file]|ext_in[file,csv,xls,xlsx]', // 10 MB
            ];
            if (!$this->validate($rules)) {
                $data['excelvalidation'] = $this->validator;
                // print_r( $data['validation']);
                // exit;
            } else {
                // print_r($this->request->getFile('file'));
                // exit;
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {

                        // Get random file name
                        $newfilename = $file->getRandomName();
                        // print_r($newfilename);
                        // exit();
                        $a = FCPATH . '/assets/assets/uploads';
                        $file->move(FCPATH . '/assets/assets/uploads', $newfilename);
                        $filepath = FCPATH . 'assets/assets/uploads/' . $newfilename;
                        $extension = pathinfo($newfilename, PATHINFO_EXTENSION);
                        if ($extension == 'csv') {
                            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                        } elseif ($extension == 'xls') {
                            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                        } else {
                            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        }
                        $spreadsheet = $reader->load($filepath);
                        $sheetData = $spreadsheet->getActiveSheet()->toArray();
                        // echo "<pre>";
                        // print_r($sheetData);
                        // exit();
                        $result = $this->users_model->importnewusers($sheetData);
                        //  echo "<pre>";
                        // print_r($result);
                        // exit();
                        if (isset($result['success'])) {
                            $session = session();
                            $session->setFlashdata('success', $result['success']);
                            return redirect()->to(base_url() . 'User_login/client_users/importUsers');
                        } else if (isset($result['error'])) {
                            session()->setFlashdata('error', $result['error']);
                            session()->setFlashdata('alert-class', 'alert-danger');
                            return redirect()->to(base_url() . 'User_login/client_users/importUsers');
                        }
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('users/usersbulkimport_view', $data);
            echo view('templates/footer_view');
        }
    }
    public function importemail_view()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        echo view('templates/header_view', $data);
        echo view('users/importemail_view', $data);
        echo view('templates/footer_view');
    }
    // function importemail()
    // {
    //     $data = [];
    //     helper(['form']);
    //     // print_r($_FILES);
    //     // exit();
    //     if (isset($_FILES)) {

    //         $rules = [
    //             'file' => 'uploaded[file]|ext_in[file,csv,xls,xlsx]', // 10 MB
    //         ];
    //         if (!$this->validate($rules)) {
    //             $data['excelvalidation'] = $this->validator;
    //             // print_r( $data['validation']);
    //             // exit;
    //         } else {
    //             // print_r($this->request->getFile('file'));
    //             // exit;
    //             if ($file = $this->request->getFile('file')) {
    //                 if ($file->isValid() && !$file->hasMoved()) {

    //                     // Get random file name
    //                     $newfilename = $file->getRandomName();
    //                     // print_r($newfilename);
    //                     // exit();
    //                     $a = FCPATH . '/assets/assets/uploads';
    //                     $file->move(FCPATH . '/assets/assets/uploads', $newfilename);
    //                     $filepath = FCPATH . 'assets/assets/uploads/' . $newfilename;
    //                     $extension = pathinfo($newfilename, PATHINFO_EXTENSION);
    //                     if ($extension == 'csv') {
    //                         $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    //                     } elseif ($extension == 'xls') {
    //                         $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
    //                     } else {
    //                         $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //                     }
    //                     $spreadsheet = $reader->load($filepath);
    //                     $sheetData = $spreadsheet->getActiveSheet()->toArray();

    //                     if (trim($sheetData[0][0]) == 'email') {
    //                         foreach ($sheetData as $Row) {

    //                             if (isset($Row[0]) && isset($Row[1])) {
    //                                 if (trim($Row[0]) == 'email') {
    //                                     continue;
    //                                 }

    //                                 if (isset($Row[0])) {
    //                                     $email = utf8_encode(trim($Row[0]));
    //                                 }

    //                                 $password = "";
    //                                 if (isset($Row[1])) {
    //                                     $password = trim($Row[1]);
    //                                 }
    //                                 $to = $email;
    //                                 $subject = 'DOCHEK LMS Login Credentials';
    //                                 $comments = 'Hi,<br><br>'
    //                                     . 'You have been provided access to the DOCHEK Learning Management System<br>'
    //                                     . '(LMS). Please find your login details below:<br>'
    //                                     . '<li>Username: your email address</li><br>'
    //                                     . '<li>Password: ' . $password . '</li><br>'
    //                                     . '👉 To log in, visit: https://dochek.com/ang/login<br>'
    //                                     . 'For security purposes, we recommend changing your password after your first login. If you encounter any issues accessing the platform, please reach out to support@dochek.com.<br/><br/><br/>'
    //                                     . 'Best regards,<br>'
    //                                     . 'Support Admin<br>'
    //                                     . 'DOCHEK';
    //                                 $email = \Config\Services::email();
    //                                 $email->setFrom('support@dochek.com', 'Support Email');
    //                                 $email->setTo($to);
    //                                 // $email->setCC('another@another-example.com');
    //                                 //$email->setBCC('them@their-example.com');

    //                                 $email->setSubject($subject);
    //                                 $email->setMessage($comments);

    //                                 if ($email->send()) {
    //                                     session()->setFlashdata('success', 'Mail Sent successfully! We will contact soon');
    //                                     // return redirect()->to(base_url('login'));
    //                                 } else {
    //                                     $data = $email->printDebugger(['headers']);
    //                                     print_r($data);
    //                                     exit();
    //                                 }
    //                             }
    //                         }
    //                     } else {
    //                         session()->setFlashdata('error', 'Data not found!. Importing Wrong format');
    //                         session()->setFlashdata('alert-class', 'alert-danger');
    //                         return redirect()->to(base_url() . 'User_login/client_users/importUsers');
    //                     }
    //                     $session = session();
    //                     $session->setFlashdata('success', 'Data imported Successfully');
    //                     return redirect()->to(base_url() . 'User_login/client_users/importUsers');
    //                 }
    //             }
    //         }
    //         echo view('templates/header_view', $data);
    //         echo view('users/usersbulkimport_view', $data);
    //         echo view('templates/footer_view');
    //     }
    // }
    public function importemail()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);

        if (isset($_FILES)) {
            //  print_r("ttt");
            //         exit();

            $rules = [
                'file' => 'uploaded[file]|ext_in[file,csv,xls,xlsx]'
            ];

            if (!$this->validate($rules)) {
                $data['excelvalidation'] = $this->validator;
            } else {


                $file = $this->request->getFile('file');


                if ($file && $file->isValid() && !$file->hasMoved()) {

                    $tmpPath = $file->getTempName();
                    $extension = $file->getClientExtension();

                    if ($extension == 'csv') {
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                    } elseif ($extension == 'xls') {
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                    } else {
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    }

                    try {
                        $spreadsheet = $reader->load($tmpPath);
                    } catch (\Exception $e) {
                        session()->setFlashdata('error', 'Error reading file');
                        return redirect()->back();
                    }

                    $sheetData = $spreadsheet->getActiveSheet()->toArray();

                    // ✅ Validate header
                    if (empty($sheetData) || trim($sheetData[0][0]) !== 'email') {
                        session()->setFlashdata('error', 'Invalid format. First column must be "email"');
                        return redirect()->back();
                    }


                    foreach ($sheetData as $key => $Row) {

                        // Skip header row
                        if ($key == 0) continue;

                        // Skip empty rows
                        if (empty($Row[0]) || empty($Row[1])) continue;

                        $email = trim($Row[0]);
                        $password = trim($Row[1]);

                        // Validate email
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

                        $subject = 'DOCHEK LMS Login Credentials';
                        $message = 'Hi,<br><br>'
                            . 'You have been provided access to the DOCHEK Learning Management System(LMS). Please find your login details below:<br/>'
                            . '<ul><li><b>Username:</b> your email address</li>'
                            . '<li><b>Password:</b> ' . $password . '</li></ul><br>'
                            . '👉 To log in, visit: <a href="https://dochek.com/ang/login">https://dochek.com/ang/login</a><br><br>'
                            . 'For security purposes, we recommend changing your password after your first login. If you encounter any issues accessing the platform, please reach out to support@dochek.com.<br/><br/><br/>'
                            . 'Best regards,<br>'
                            . 'Support Admin<br>'
                            . 'DOCHEK';
                        $mail = \Config\Services::email();

                        $mail->setFrom('support@dochek.com', 'Support Email');
                        $mail->setTo($email);
                        $mail->setSubject($subject);
                        $mail->setMessage($message);

                        if (!$mail->send()) {
                            // Debug if needed
                            log_message('error', $mail->printDebugger(['headers']));
                        }
                    }

                    session()->setFlashdata('success', 'Emails sent successfully!');
                    return redirect()->to(base_url('User_login/client_users/importemail_view'));
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('users/importemail_view', $data);
        echo view('templates/footer_view');
    }
    public function searchByName()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $name = $this->request->getPost('name');
        $clientid = session()->get('client');
        // print_r($name);
        // exit();
        $clientlicense = $this->settings_model->clientlicensedata($clientid);

        // $searchResults = $this->settings_model->clientlicensesearchdata($clientid,$name);
        $searchResults = $this->login_model->namessearch_view($name);
        if (!empty($searchResults)) {
            $data = [
                'searchResults' => $searchResults, // Replace with your search result data
                'clientlicense' => $clientlicense
            ];
        } else {
            session()->setFlashdata('error', 'Frist name not found');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/client_users');
        }
        echo view('templates/header_view', $data);
        echo view('users/client_users_view', $data);
        echo view('templates/footer_view');
    }
    public function searchBylastName()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $name = $this->request->getPost('last_name');
        $clientid = session()->get('client');
        // print_r($name);
        // exit();
        $clientlicense = $this->settings_model->clientlicensedata($clientid);
        // $searchResults = $this->settings_model->clientlicensesearchdata($clientid,$name);
        $searchResults = $this->login_model->lastnamessearch_view($name);
        if (!empty($searchResults)) {
            $data = [
                'searchResults' => $searchResults, // Replace with your search result data
                'clientlicense' => $clientlicense
            ];
        } else {
            session()->setFlashdata('error', 'Last name not found');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/client_users');
        }
        echo view('templates/header_view', $data);
        echo view('users/client_users_view', $data);
        echo view('templates/footer_view');
    }
    public function searchByuserName()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $username = $this->request->getPost('username');
        // print_r($username);
        // exit();
        $clientid = session()->get('client');
        $clientlicense = $this->settings_model->clientlicensedata($clientid);
        // print_r($username);
        // exit();
        // $searchResults = $this->settings_model->clientlicensesearchdata($clientid,$name);
        $searchResults = $this->login_model->usernamessearch_view($username);
        if (!empty($searchResults)) {
            $data = [
                'searchResults' => $searchResults, // Replace with your search result data
                'clientlicense' => $clientlicense
            ];
        } else {
            session()->setFlashdata('error', 'Email not found');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/client_users');
        }
        echo view('templates/header_view', $data);
        echo view('users/client_users_view', $data);
        echo view('templates/footer_view');
    }
    public function editUsers($id_user) // update data from users table to display
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        $clientid = session()->get('client');
        $userdata = $this->users_model->usersedit_view($userencrptid);
        if ($userdata[0]['client_id'] != $clientid) {
            return redirect()->to('my_training')->with('error', 'Unauthorized Access');
        }
        $data['clientid'] = $clientid;
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $data['menu_view'] = $this->settings_model->menu_view($clientid);
        $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($userencrptid);
        $data['default_dashboard'] = $this->settings_model->clientlicensedata($clientid);
        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];
        $data['manager'] = $this->login_model->users_list($data['row']['client_id']);
        $data['temppass'] = $this->generateTempPassword(12);
        $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($userencrptid);
        //print_r($data['clientUserlevelData']);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $data['lastLoginTime'] =  date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
        }

        if ($this->request->getPost()) {
            $rules = [
                'name' => 'required|min_length[1]|max_length[32]',
                'last_name' => 'required|min_length[1]|max_length[32]',
                'username' => 'required|max_length[50]|is_unique[users.username,id_user,' . $userencrptid . ']',
            ];
            if (!$this->validate($rules)) {
                $data['validationEditUsers'] = $this->validator;
            } else {
                $id_user  = $this->request->getVar('id_user');
                $last_name =  $this->request->getVar('last_name');
                $last_name = isset($last_name) ? $last_name : '';
                $newdata = [
                    'name' => htmlspecialchars($this->request->getVar('name'), ENT_QUOTES, 'UTF-8'),
                    'last_name' => htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8'),
                    'email' => htmlspecialchars($this->request->getVar('email'), ENT_QUOTES, 'UTF-8'),
                    'username' => htmlspecialchars($this->request->getVar('username'), ENT_QUOTES, 'UTF-8'),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->users_model->updateUsersData($userencrptid, $newdata);
                if ($result) {
                    $session = session();
                    $session->setFlashdata('success', lang('Messages.Success_0008'));
                    return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . $id_user);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . $id_user);
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view', $data); 
    }
    public function passeditUsers($id_user)
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        $clientid = session()->get('client');
        $userdata = $this->users_model->usersedit_view($userencrptid);
        if ($userdata[0]['client_id'] != $clientid) {
            return redirect()->to('my_training')->with('error', 'Unauthorized Access');
        }

        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $data['menu_view'] = $this->settings_model->menu_view($clientid);
        // $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($userencrptid);
        // $data['default_dashboard'] = $this->settings_model->clientlicensedata($clientid);

        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];
        $data['manager'] = $this->login_model->users_list($data['row']['client_id']);
        //print_r($data['row']);
        $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($userencrptid);
        //print_r($data['clientUserlevelData']);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $last_loginTime = $this->users_model->userActiveData($data['row']['username']);

        $data['temppass'] = $this->generateTempPassword(12);
        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
        }

        if ($this->request->getPost()) {
            $rules = [
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]|max_length[64]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/]',
                    'errors' => [
                        'regex_match' => 'Password must include uppercase, lowercase, number, and special character.'
                    ]
                ],
            ];
            if (!$this->validate($rules)) {
                $data['validationpassEditUsers'] = $this->validator;
            } else {
                $id_user = $this->request->getVar('id_user');
                $newdata = [
                    'password' => trim(password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)),
                ];
                $result = $this->users_model->updateUsersData($userencrptid, $newdata);
                if ($result) {
                    $session = session();
                    $session->setFlashdata('success', 'Password updated Successfully!');
                    return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . $id_user);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . $id_user);
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function editprofileAdvance($id_user) // update data from users table to display
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        $clientid = session()->get('client');
        $userdata = $this->users_model->usersedit_view($userencrptid);
        if ($userdata[0]['client_id'] != $clientid) {
            return redirect()->to('my_training')->with('error', 'Unauthorized Access');
        }
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $data['menu_view'] = $this->settings_model->menu_view($clientid);
        $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($userencrptid);
        $data['default_dashboard'] = $this->settings_model->clientlicensedata($clientid);
        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];
        $data['manager'] = $this->login_model->users_list($data['row']['client_id']);
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = array();
        $alpha_length = strlen($alphabet ?? '') - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alpha_length);
            $password[] = $alphabet[$n];
        }
        $data['temppass'] = $this->generateTempPassword(12);
        $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($userencrptid);
        //print_r($data['clientUserlevelData']);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
        }

        if ($this->request->getPost()) {
            // $rules = [
            // 'name' => 'required|min_length[3]|max_length[32]',
            // 'email' => 'required|min_length[6]|max_length[50]|valid_email',
            // ];
            // if (!$this->validate($rules)) {
            //     $data['validationEditUsers'] = $this->validator;
            // } else {
            $DOJ = $this->request->getPost('DOJ');
            $DOJ = $DOJ;
            $LWD = $this->request->getPost('LWD');
            $LWD = $LWD;
            $newdata = [
                'emp_id' => $this->request->getVar('emp_id'),
               // 'level' => $this->request->getVar('level'),
              //  'DOJ' => $DOJ,
              //  'status' => $this->request->getVar('status'),
                'manager' => $this->request->getVar('manager'),
              //  'gender' => $this->request->getVar('gender'),
             //   'department' => $this->request->getVar('department'),
                'default_dashboard2' => $this->request->getVar('default_dashboard2'),
                'default_dashboard1' => $this->request->getVar('default_dashboard1'),
             //   'LWD' => $LWD,
                'designation' => $this->request->getVar('designation'),
                'report_to_you' => $this->request->getVar('report_to_you'),
              //  'engage_type' => $this->request->getVar('engage_type'),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->users_model->updateUsersData($userencrptid, $newdata);
            if ($result) {
                $session = session();
                $session->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . $id_user);
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . $id_user);
            }
            // }
        }
        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function appUsernameChange($id_user)
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        $clientid = session()->get('client');
        $userdata = $this->users_model->usersedit_view($userencrptid);
        if ($userdata[0]['client_id'] != $clientid) {
            return redirect()->to('my_training')->with('error', 'Unauthorized Access');
        }
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $data['menu_view'] = $this->settings_model->menu_view($clientid);
        $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($userencrptid);
        $data['default_dashboard'] = $this->settings_model->clientlicensedata($clientid);

        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];
        $data['manager'] = $this->login_model->users_list($data['row']['client_id']);
        // print_r($data['row']);
        // exit();
        $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($userencrptid);
        //print_r($data['clientUserlevelData']);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = array();
        $alpha_length = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alpha_length);
            $password[] = $alphabet[$n];
        }
        $data['temppass'] = $this->generateTempPassword(12);
        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
        }

        if ($this->request->getPost()) {
            $rules = [
                'app_username' => [
                    'label' => 'App Username',
                    'rules' => ['required', 'max_length[10]', 'is_unique[users.app_username]'],
                    'errors' => [
                        'required' => 'The App Username field is required.',
                        'max_length' => 'The App Username field cannot exceed 10 characters.', // Corrected max_length
                    ],
                ],
            ];
            if (!$this->validate($rules)) {
                // print_r('tt');
                // exit();
                $data['validationappUser'] = $this->validator;
            } else {
                $newdata = [
                    'app_username' => $this->request->getVar('app_username'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];

                $result = $this->users_model->updateUsersData($userencrptid, $newdata);
                if ($result) {
                    // print_r($result);
                    // exit();
                    $session = session();
                    $session->setFlashdata('success', 'App Username updated Successful');
                    return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . $id_user);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . $id_user);
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function appPasswordChange($id_user)
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        // print_r($_POST);
        // exit();
        $userencrptid = base64_decode($id_user);
        $clientid = session()->get('client');
        $userdata = $this->users_model->usersedit_view($userencrptid);
        if ($userdata[0]['client_id'] != $clientid) {
            return redirect()->to('my_training')->with('error', 'Unauthorized Access');
        }
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $data['menu_view'] = $this->settings_model->menu_view($clientid);
        $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($userencrptid);
        $data['default_dashboard'] = $this->settings_model->clientlicensedata($clientid);

        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];
        $data['manager'] = $this->login_model->users_list($data['row']['client_id']);
        //print_r($data['row']);
        $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($userencrptid);
        //print_r($data['clientUserlevelData']);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = array();
        $alpha_length = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alpha_length);
            $password[] = $alphabet[$n];
        }
        $data['temppass'] = $this->generateTempPassword(12);
        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
        }

        if ($this->request->getPost()) {
            $rules = [

                'app_password' => [
                    'label' => 'App Password',
                    'rules' => ['required', 'max_length[255]'],
                    'errors' => [
                        'required' => 'The App Password field is required.',
                        'max_length' => 'The App Password field cannot exceed 255 characters.', // Corrected max_length
                    ],
                ],

            ];
            if (!$this->validate($rules)) {
                // print_r('tt');
                // exit();
                $data['validationappPass'] = $this->validator;
            } else {
                $newdata = [
                    'app_password' => $this->request->getVar('app_password'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                // print_r($newdata);
                // exit();
                $result = $this->users_model->updateUsersData($userencrptid, $newdata);
                if ($result) {
                    $session = session();
                    $session->setFlashdata('success', 'App Password updated Successful');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . $id_user);
        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view', $data);
    }
    // public function deleteUsers($id_user = null)
    // {
    //     if ($response =  $this->requireRole(['6', '44', '4'])) {
    //         return $response;
    //     }
    //     $result = $this->users_model->deleteUser($id_user);
    //     if ($result) {
    //         $sessionData = session();
    //         $sessionData->setFlashdata('success', 'User deleted successfully');
    //         return redirect()->to(base_url() . 'User_login/client_users');
    //     }
    //     session()->setFlashdata('error', lang('Messages.Error_0001'));
    //     session()->setFlashdata('alert-class', 'alert-danger');
    //     return redirect()->route('client_users')->withInput();
    // }
    public function deleteUsers($id_user = null)
    {
        // Role-based access (only allowed roles)
        if ($response = $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }

        $session = session();
        $loggedInUserId = $session->get('id_user');
        $clientId = $session->get('client');

        // Validate input
        if (empty($id_user) || !is_numeric($id_user)) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        //  Fetch user details (for ownership check)
        $user = $this->users_model->getUserById($id_user);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        //  BOLA Protection: Ensure same client/tenant
        if ($user->client_id != $clientId) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        //  Prevent self deletion
        if ($id_user == $loggedInUserId) {
            return redirect()->back()->with('error', 'You cannot delete your own account');
        }

        // Perform delete (prefer soft delete)
        $result = $this->users_model->deleteUser($id_user, $loggedInUserId);

        if ($result) {
            $session->setFlashdata('success', 'User deleted successfully');
            return redirect()->to(base_url('User_login/client_users'));
        }

        return redirect()->back()->with('error', 'Something went wrong');
    }

    public function updateCategory($id_user = null)
    { // add client name to user
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        if ($this->request->getPost()) {
            $data['table'] = $this->dropdown_model->categoryDetails();
            $data['categoryData'] = $this->dropdown_model->getCategoryData();
            $data['clientData'] = $this->dropdown_model->getclientData();
            $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
            $userdata = $this->users_model->usersedit_view($userencrptid);
            $clientid = session()->get('client');
            if ($userdata[0]['client_id'] != $clientid) {
                return redirect()->to('my_training')->with('error', 'Unauthorized Access');
            }
            $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($userencrptid);
            $data['row'] = $userdata['0'];
            $data['manager'] = $this->login_model->users_list($data['row']['client_id']);
            $data['timezone'] = $this->login_model->gettimezonedata();
            $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
            $clientid = session()->get('client');
            $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
            $data['menu_view'] = $this->settings_model->menu_view($clientid);
            $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($userencrptid);
            $data['default_dashboard'] = $this->settings_model->clientlicensedata($clientid);
            if (!$last_loginTime) {
                $data['lastLoginTime'] = ' ';
            } else {
                $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
            }
            $rulesData = [
                'clientItem' => 'required'

            ];
            if (!$this->validate($rulesData)) {
                $data['validationData'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'fk_id_user' => $userencrptid,
                    'fk_id_dc' => '1',
                    'fk_id_d' => $this->request->getVar('clientItem'),
                    'createdon' => $timestamp,
                    'createdby' => session()->get('id_user'),
                    'status' => '1',
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                //print_r($newdata);
                $result = $this->dropdown_model->updateCategory($newdata);
                $this->dropdown_model->updateCilenttoUsertable($newdata['fk_id_d'], $newdata['fk_id_user']);
                if ($result) {
                    session()->setFlashdata('success', $result);
                    return redirect()->to(base_url() . 'User_login/client_users');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/client_users');
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view');
    }
    public function updateCategoryItem($id_user = null)
    { // add userlevel name to user
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel));
        $clientid = session()->get('client');
        $userdata = $this->users_model->usersedit_view($userencrptid);
        if ($userdata[0]['client_id'] != $clientid) {
            return redirect()->to('my_training')->with('error', 'Unauthorized Access');
        }
        if ($this->request->getPost()) {
            $data['table'] = $this->dropdown_model->categoryDetails();
            $data['categoryData'] = $this->dropdown_model->getCategoryData();
            $data['clientData'] = $this->dropdown_model->getclientData();
            $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);

            $data['row'] = $userdata['0'];
            $data['manager'] = $this->login_model->users_list($data['row']['client_id']);
            $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($id_user);
            $data['timezone'] = $this->login_model->gettimezonedata();
            $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
            if (!$last_loginTime) {
                $data['lastLoginTime'] = ' ';
            } else {
                $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
            }
            $rules = [
                'userlevelItem' => 'required'
            ];
            $clientid = session()->get('client');
            $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
            $data['menu_view'] = $this->settings_model->menu_view($clientid);
            $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($userencrptid);
            $data['default_dashboard'] = $this->settings_model->clientlicensedata($clientid);
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {

                $userlevelItem = $this->request->getVar('userlevelItem');
                // print_r($arrayuserlevel);
                // exit();
                if ($userlevelItem == '6') {
                    print_r($userlevelItem);
                    // exit();
                    if (!in_array('6', $arrayuserlevel)) {
                        return redirect()->to(base_url() . 'User_login/client_users');
                    }
                }


                $timestamp = time();
                $newdata = [
                    'fk_id_user' => $userencrptid,
                    'fk_id_dc' => '2',
                    'fk_id_d' => $this->request->getVar('userlevelItem'),
                    'createdon' => $timestamp,
                    'createdby' => session()->get('id_user'),
                    'status' => '1',
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];

                //print_r($newdata);
                $result = $this->dropdown_model->updateCategoryItem($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0002'));
                    return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . $id_user);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . $id_user);
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view');
    }
    // public function deleteUserCategory($id_du, $id_user)
    // {
    //     if ($response =  $this->requireRole(['6', '44', '4'])) {
    //         return $response;
    //     }
    //     $sessionData = session();
    //     $timestamp = time();
    //     $newdata = [
    //         'id_du' => $id_du,
    //          
    //          
    //         'status' => '0',
    //         'last_updated_by' => session()->get('id_user'),
    //         'last_updated_on' => time(),
    //     ];
    //     $result = $this->users_model->deleteUserCategory($newdata, $id_du);
    //     if ($result) {
    //         $sessionData->setFlashdata('success', 'Access unassigned successfully!');
    //         return redirect()->to(base_url() . 'User_login/client_users/editUsers/' . base64_encode($id_user));
    //     } else {
    //         session()->setFlashdata('error', lang('Messages.Error_0001'));
    //         session()->setFlashdata('alert-class', 'alert-danger');
    //         return redirect()->to(base_url() . 'User_login/client_users');
    //     }
    // }
    // public function deleteUserCategory($id_du, $id_user)
    // {
    //     if ($response = $this->requireRole(['6', '44', '4'])) {
    //         return $response;
    //     }

    //     $session = session();
    //     $loggedInUserId = $session->get('id_user');
    //     $clientId = $session->get('client');

    //     //  Fetch record securely
    //     $record = $this->users_model->getUserCategoryById($id_du);

    //     if (!$record) {
    //         return redirect()->back()->with('error', 'Invalid request');
    //     }

    //     //  BOLA Protection (ownership check)
    //     if ($record->client_id != $clientId) {
    //         return redirect()->back()->with('error', 'Unauthorized access');
    //     }

    //     // Optional: prevent self-delete or higher privilege delete
    //     if ($record->fk_id_user == $loggedInUserId) {
    //         return redirect()->back()->with('error', 'You cannot remove your own access');
    //     }

    //     $newdata = [
    //         'id_du' => $id_du,
    //          
    //          
    //         'status' => '0',
    //         'last_updated_by' => $loggedInUserId,
    //         'last_updated_on' => time(),
    //     ];

    //     $result = $this->users_model->deleteUserCategory($newdata, $id_du);

    //     if ($result) {
    //         $session->setFlashdata('success', 'Access unassigned successfully!');
    //         return redirect()->to(base_url('User_login/client_users/editUsers/' . base64_encode($id_user)));
    //     }

    //     return redirect()->to(base_url('User_login/client_users'))
    //         ->with('error', 'Something went wrong');
    // }
    public function deleteUserCategory()
    {
        // print_r($this->request->getMethod());
        // exit();
        // Only allow POST requests
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }

        if ($response = $this->requireRole(['6', '44', '4'])) {
            return $response;
        }

        $session = session();
        $loggedInUserId = $session->get('id_user');
        $clientId = $session->get('client');

        // Retrieve POST data
        $id_du = $this->request->getPost('category_id');
        $id_user = $this->request->getPost('user_id');

        if (!$id_du || !$id_user) {
            return redirect()->back()->with('error', 'Invalid request data.');
        }

        // Fetch record securely
        $record = $this->users_model->getUserCategoryById($id_du);

        if (!$record) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        // BOLA protection (ownership check)
        if ($record->client_id != $clientId) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Optional: prevent self-delete
        if ($record->fk_id_user == $loggedInUserId) {
            return redirect()->back()->with('error', 'You cannot remove your own access.');
        }

        // Prepare data for soft-delete
        $newdata = [
            'id_du' => $id_du,
            'status' => '0',
            'last_updated_by' => $loggedInUserId,
            'last_updated_on' => time(),
        ];

        $result = $this->users_model->deleteUserCategory($newdata, $id_du);

        if ($result) {
            $session->setFlashdata('success', 'Access unassigned successfully!');
            return redirect()->to(base_url('User_login/client_users/editUsers/' . base64_encode($id_user)));
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }
    function deleted_userslist()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data['header_link'] = 'User_login/client_users';
        $data['header'] = 'Users';
        if (isset($_POST['cid'])) {
            $data['cid'] = $_POST['cid'];
        } else if (isset($_GET['cid'])) {
            $data['cid'] = $_GET['cid'];
        } else {
            $data['cid'] = session()->get('client');
        }
        $deleteUserData = $this->users_model->deleteUserlist($data['cid']);
        $data['deleteUserlist'] = $deleteUserData;
        echo view('templates/header_view', $data);
        echo view('users/deleted_users_view', $data);
        echo view('templates/footer_view');
    }
    function activateuser($id_user)
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $cid = session()->get('client');
        $result = $this->users_model->activateuser($id_user, $cid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0048'));
            return redirect()->to(base_url() . 'User_login/client_users');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/client_users');
        }
    }
    function client_settings()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['clientData'] = $this->dropdown_model->clientuserlist();
        //print_r($data['clientData']);
        echo view('templates/header_view', $data);
        echo view('settings/client_setting_view', $data);
        echo view('templates/footer_view');
    }

    public function usersassigned_scenarios()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        $data['getClientCourses'] = $this->scorm_client_model->getUserCourses($client_id);
        $data['getAllCoursesEachClient'] = $this->scorm_client_model->getAllCoursesEachClient($client_id);

        echo view('templates/header_view');
        echo view('users/sc_client_scemaro_assigned', $data);
        echo view('templates/footer_view');
    }
    public function manageTrainings()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'Courses';
        $data['settings_link'] = 'SCORM/scorm_courses/course_settings_view';
        $data['edit_link'] = 'User_login/client_users/course_edit_view';
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);

        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        $data['getClientCourses'] = $this->scorm_client_model->getUserCourses($client_id);
        $data['getTypeCourses'] = $this->scorm_client_model->getTypeCourses($client_id);
        //  $data['getAllCoursesEachClient'] = $this->scorm_client_model->getAllCoursesEachClient($client_id);
        echo view('templates/header_view');
        echo view('users/sc_client_courses_assigned', $data);
        echo view('templates/footer_view');
    }

    public function report_admin()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $data['coursename'] = $this->scorm_client_model->getCoursename($data['scourse_id']);

        $data['all_attempts'] = $this->scorm_client_model->getAllAttempts($data['scourse_id'], $data['client_id']);
        if ($data['coursename'][0]['type'] == 5) {
            $data['allmissed'] = $this->scorm_client_model->allmissed($data['scourse_id'], $data['client_id']);
            // print_r($data['allmissed']);
            $data['outputVariables'] = $this->scorm_course_model->getOutputVariable_detailed($data['scourse_id'], 10);
            $data['outputVariables_v1'] = $this->scorm_course_model->getOutputVariable_detailed_v1($data['scourse_id'], 10);
        }
        if ($data['coursename'][0]['type'] == 8) {
            $data['allmissed'] = $this->scorm_client_model->allmissed($data['scourse_id'], $data['client_id']);
            // print_r($data['allmissed']);
            // $data['outputVariables'] = $this->scorm_course_model->getOutputVariable_detailed($data['scourse_id'], 10);
            $data['outputVariables_v2'] = $this->scorm_course_model->getOutputVariable_detailed_v2($data['scourse_id'], 10);
        }
        $data['getUserlatestclientCourseByScenario'] = $this->scorm_client_model->getUserlatestclientCourseByScenario($data['scourse_id'], $client_id);
        $data['header_link'] = 'User_login/client_users/manageTrainings';
        $data['header_sub_link'] = 'User_login/client_users/usersassigned_report';
        $data['form_link'] = 'User_login/client_users/userallcoursedetails';
        $data['form_link_1'] = 'User_login/client_users/user_defect_full_list';
        $data['form_link_2'] = 'User_login/client_users/user_wronganswer_full_list';
        $data['header_link_name'] = 'Courses';

        echo view('templates/header_view');
        echo view('users/sc_report', $data);
        echo view('templates/footer_view');
    }

    public function process_defects()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $data['allmissed'] = $this->scorm_client_model->allmissed($data['scourse_id'], $data['client_id']);
        // print_r($data['allmissed']);
        $OutputVariables = $this->scorm_course_model->getOutputVariables($data['scourse_id']);

        $value = $data['allmissed'][0]['value'];
        $valueArray = explode(",", $value);

        foreach ($valueArray as $valuedetails) {
            $resultx = array_search($valuedetails, array_column($OutputVariables, 'variable_name'));
            if (is_numeric($resultx)) {
                $xov = $OutputVariables[$resultx]['xov'];
                $counter = $OutputVariables[$resultx]['counter'] + 1;
                $newdata = [
                    'counter' => $counter,
                ];
                $result = $this->scorm_client_model->addCounter($newdata, $xov);
            }
        }

        $xapi_act_id = $data['allmissed'][0]['xapi_act_id'];
        $newdata1 = [
            'status' => 2,
        ];
        $result = $this->scorm_client_model->updateMissed($newdata1, $xapi_act_id);

        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0049'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'User_login/client_users/report_admin');
    }
    public function process_defects_v1()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;

        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $data['allmissed'] = $this->scorm_client_model->allmissed($data['scourse_id'], $data['client_id']);
        // print_r($data['allmissed']);
        // exit();
        $OutputVariables = $this->scorm_course_model->getOutputVariables($data['scourse_id']);
        $value = $data['allmissed'][0]['value'];
        $user_assiged_id = $data['allmissed'][0]['user_assign_id'];
        $sc_uid = $data['allmissed'][0]['sc_uid'];
        $id_user = $data['allmissed'][0]['id_user'];
        $valueArray = explode(",", $value);

        foreach ($valueArray as $valuedetails) {
            // print_r($valuedetails);
            $resultx = array_search($valuedetails, array_column($OutputVariables, 'variable_name'));
            if (is_numeric($resultx)) {
                $xov = $OutputVariables[$resultx]['xov'];
                // $counter = $OutputVariables[$resultx]['counter'] + 1;
                // $newdata = [
                //     'counter' => $counter,
                // ];
                // $result = $this->scorm_client_model->addCounter($newdata, $xov);
                $newdata = [
                    'xov' => $xov,
                    'sc_uid' => $sc_uid,
                    'user_assign_id' => $user_assiged_id,
                    'id_user' => $id_user,
                    'course_id' => $data['scourse_id'],
                    'client_id' => $client_id,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                    'status' => 1
                ];
                $result = $this->scorm_client_model->addXapiOutData($newdata);
            }
        }

        $xapi_act_id = $data['allmissed'][0]['xapi_act_id'];
        $newdata1 = [
            'status' => 2,
        ];
        $result = $this->scorm_client_model->updateMissed($newdata1, $xapi_act_id);

        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0049'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'User_login/client_users/report_admin');
    }


    public function defect_full_list()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $data['coursename'] = $this->scorm_client_model->getCoursename($data['scourse_id']);


        $data['allmissed'] = $this->scorm_course_model->allmissed($data['scourse_id'], $data['client_id']);
        $data['outputVariables'] = $this->scorm_course_model->getOutputVariable_detailed($data['scourse_id'], 1);

        $data['header_link'] = 'User_login/client_users/manageTrainings';
        $data['header_link2'] = 'User_login/client_users/report_admin';
        $data['header_sub_link'] = 'User_login/client_users/usersassigned_report';
        $data['form_link'] = 'User_login/client_users/userallcoursedetails';
        $data['header_link_name'] = 'Courses';

        echo view('templates/header_view');
        echo view('users/sc_full_list', $data);
        echo view('templates/footer_view');
    }
    public function defect_full_list_v1()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $data['coursename'] = $this->scorm_client_model->getCoursename($data['scourse_id']);


        $data['allmissed'] = $this->scorm_client_model->allmissed($data['scourse_id'], $data['client_id']);
        $data['outputVariables'] = $this->scorm_course_model->getOutputVariable_detailed_v1($data['scourse_id'], 1);

        $data['header_link'] = 'User_login/client_users/manageTrainings';
        $data['header_link2'] = 'User_login/client_users/report_admin';
        $data['header_sub_link'] = 'User_login/client_users/usersassigned_report';
        $data['form_link'] = 'User_login/client_users/userallcoursedetails';
        $data['header_link_name'] = 'Courses';

        echo view('templates/header_view');
        echo view('users/sc_full_list', $data);
        echo view('templates/footer_view');
    }
    public function defect_full_list_v2()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $data['coursename'] = $this->scorm_client_model->getCoursename($data['scourse_id']);


        $data['allmissed'] = $this->scorm_client_model->allmissed($data['scourse_id'], $data['client_id']);
        $data['outputVariables'] = $this->scorm_course_model->getOutputVariable_detailed_v2($data['scourse_id'], 1);

        $data['header_link'] = 'User_login/client_users/manageTrainings';
        $data['header_link2'] = 'User_login/client_users/report_admin';
        $data['header_sub_link'] = 'User_login/client_users/usersassigned_report';
        $data['form_link'] = 'User_login/client_users/userallcoursedetails';
        $data['header_link_name'] = 'Courses';

        echo view('templates/header_view');
        echo view('users/Assessment_full_list', $data);
        echo view('templates/footer_view');
    }
    function user_defect_full_list($xov)
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }

        $data = [];
        helper(['form']);
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        if (isset($_GET['xov'])) {
            $data['xov'] = $_GET['xov'];
            $_SESSION['xov'] = $data['xov'];
        } elseif (isset($_SESSION['xov'])) {
            $data['xov'] = $_SESSION['xov'];
        }

        // print_r($xov);
        $data['coursename'] = $this->scorm_client_model->getCoursename($data['scourse_id']);


        // $data['allmissed'] = $this->scorm_client_model->allmissed($data['scourse_id'], $data['client_id']);
        // // print_r( $data['allmissed']);
        // $data['outputVariables'] = $this->scorm_course_model->getOutputVariable_detailed_v1($data['scourse_id'], 1);
        $data['getUsersdefectdata'] = $this->scorm_course_model->getUsersdefectdata($xov);
        // print_r($data['outputVariables']);
        $data['header_link'] = 'User_login/client_users/manageTrainings';
        $data['header_link2'] = 'User_login/client_users/report_admin';
        $data['header_sub_link'] = 'User_login/client_users/usersassigned_report';
        $data['form_link'] = 'User_login/client_users/userallcoursedetails';
        $data['header_link_name'] = 'Courses';
        $data['view_details'] = 'User_login/client_users/viewDetailedReport';

        echo view('templates/header_view');
        echo view('users/sc_user_full_list', $data);
        echo view('templates/footer_view');
    }
    function user_wronganswer_full_list($xov)
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        if (isset($_GET['xov'])) {
            $data['xov'] = $_GET['xov'];
            $_SESSION['xov'] = $data['xov'];
        } elseif (isset($_SESSION['xov'])) {
            $data['xov'] = $_SESSION['xov'];
        }

        // print_r($xov);
        $data['coursename'] = $this->scorm_client_model->getCoursename($data['scourse_id']);


        // $data['allmissed'] = $this->scorm_client_model->allmissed($data['scourse_id'], $data['client_id']);
        // // print_r( $data['allmissed']);
        // $data['outputVariables'] = $this->scorm_course_model->getOutputVariable_detailed_v1($data['scourse_id'], 1);
        $data['getUsersdefectdata'] = $this->scorm_course_model->getUserswrongquestiondata($xov);
        // print_r($data['outputVariables']);
        $data['header_link'] = 'User_login/client_users/manageTrainings';
        $data['header_link2'] = 'User_login/client_users/report_admin';
        $data['header_sub_link'] = 'User_login/client_users/usersassigned_report';
        $data['form_link'] = 'User_login/client_users/userallcoursedetails';
        $data['header_link_name'] = 'Courses';
        $data['view_details'] = 'User_login/client_users/viewDetailedAssessReport';

        echo view('templates/header_view');
        echo view('users/sc_user_full_list', $data);
        echo view('templates/footer_view');
    }
    function viewDetailedReport()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        // print_r($_POST);
        // exit();
        if (isset($_POST['sc_uid'])) {
            $data['sc_uid'] = $_POST['sc_uid'];
            $_SESSION['sc_uid'] = $data['sc_uid'];
            $data['attempt'] = $_POST['attempt'];
            $_SESSION['attempt'] = $data['attempt'];
            $data['xov'] = $_POST['xov'];
            $_SESSION['xov'] = $data['xov'];
            $data['tempusername'] = $_POST['tempusername'];
            $_SESSION['tempusername'] = $data['tempusername'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['sc_uid'] = $_SESSION['sc_uid'];
            $data['attempt'] = $_SESSION['attempt'];
            $data['xov'] = $_SESSION['xov'];
            $data['tempusername'] = $_SESSION['tempusername'];
        } else {
            return redirect()->to(base_url() . 'User_login/client_users/manageTrainings');
        }

        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['header'] = 'Course Report - ' . $_SESSION['course_name'];
        $data['header_link'] = 'User_login/client_users/report_admin';
        $data['header2'] = '';
        //$data['header2_link'] = 'XAPI/XAPI_users/userallcoursedetails';
        $data['header3'] = 'User Full list';
        $data['header3_link'] = 'User_login/client_users/user_defect_full_list/' . $data['xov'];
        $data['header4'] = 'Attempt ' . $_SESSION['attempt'] . '  Details';

        $data['deleteAction'] = 'XAPI/XAPI_users/delete_activity';

        $data['userActivity'] = $this->xapi_scenarios_model->activityDetails($data['sc_uid']);
        $data['OutputVariables'] = $this->xapi_scenarios_model->getOutputVariable($data['sc_uid']);
        // print_r( $data['OutputVariables']);
        //exit();
        echo view('templates/header_view');
        echo view('XAPI/scenario_user_report_details', $data);
        echo view('templates/footer_view');
    }
    function viewDetailedAssessReport()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        // print_r($_POST);
        // exit();
        if (isset($_POST['sc_uid'])) {
            $data['sc_uid'] = $_POST['sc_uid'];
            $_SESSION['sc_uid'] = $data['sc_uid'];
            $data['attempt'] = $_POST['attempt'];
            $_SESSION['attempt'] = $data['attempt'];
            $data['xov'] = $_POST['xov'];
            $_SESSION['xov'] = $data['xov'];
            $data['tempusername'] = $_POST['tempusername'];
            $_SESSION['tempusername'] = $data['tempusername'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['sc_uid'] = $_SESSION['sc_uid'];
            $data['attempt'] = $_SESSION['attempt'];
            $data['xov'] = $_SESSION['xov'];
            $data['tempusername'] = $_SESSION['tempusername'];
        } else {
            return redirect()->to(base_url() . 'User_login/client_users/manageTrainings');
        }

        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['header'] = 'Course Report - ' . $_SESSION['course_name'];
        $data['header_link'] = 'User_login/client_users/report_admin';
        $data['header2'] = '';
        $data['header3'] = 'User Full list';
        $data['header3_link'] = 'User_login/client_users/user_wronganswer_full_list/' . $data['xov'];
        $data['header4'] = 'Attempt ' . $_SESSION['attempt'] . '  Details';

        // $data['deleteAction'] = 'XAPI/XAPI_users/delete_activity';

        // $data['userActivity'] = $this->xapi_scenarios_model->activityDetails($data['sc_uid']);
        $data['getassessmentReport'] = $this->xapi_scenarios_model->getassessmentReport($data['sc_uid']);
        // print_r($data['getassessmentReport']);
        // exit();
        echo view('templates/header_view');
        echo view('assessment/assessment_user_report', $data);
        echo view('templates/footer_view');
    }
    public function usersassigned_report()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $data['coursename'] = $this->scorm_client_model->getCoursename($data['scourse_id']);
        // print_r($data['coursename']);
        $data['scenarios'] = $this->xapi_scenarios_model->getAllScenarios($_SESSION['scourse_id']);
        $data['header_link'] = 'User_login/client_users/manageTrainings';
        $data['header_sub_link'] = 'User_login/client_users/usersassigned_report';
        $data['form_link'] = 'XAPI/XAPI_users/userallcoursedetails';
        $data['form_link1'] = 'XAPI/XAPI_scenarios/deleteEnrollment';
        $data['delete_enrollment'] = 'User_login/client_users/delete_enrollment';
        $data['header_link_name'] = 'Courses';
        $data['getUserlatestCourse'] = $this->scorm_client_model->getUserlatestclientCourse($data['scourse_id'], $client_id);
        $data['getUserclientlist'] = $this->users_model->getUserclientlist($client_id);
        $data['getUserlatestclientCourseByScenario'] = $this->scorm_client_model->getUserlatestclientCourseByScenario($data['scourse_id'], $client_id);

        // $data['getAllUsersForCourses'] = $this->scorm_client_model->getAllUsersForCourses($data['scourse_id']);
        echo view('templates/header_view');
        echo view('users/sc_userslatestassigned_view', $data);
        echo view('templates/footer_view');
    }
    function userallcoursedetails()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_GET['course_id'])) {
            $data['course_id'] = $_GET['course_id'];
        } elseif ($_SESSION['course_id']) {
            $data['course_id'] = $_SESSION['course_id'];
        }
        if (isset($_POST['student_id'])) {
            $data['student_id'] = $_POST['student_id'];
            $_SESSION['student_id'] = $data['student_id'];
        } else if (isset($_GET['student_id'])) {
            $data['student_id'] = $_GET['student_id'];
        } elseif (isset($_SESSION['student_id'])) {
            $data['student_id'] = $_SESSION['student_id'];
        }
        $data['header_link'] = 'User_login/client_users/manageTrainings';
        $data['header_sub_link'] = 'User_login/client_users/usersassigned_report';
        $data['coursename'] = $this->scorm_client_model->getCoursename($data['course_id']);
        $data['header_link_name'] = 'Courses';
        $data['form_link1'] = 'User_login/client_users/deleteScormdetails';
        $data['form_link'] = 'User_login/client_users/userallcoursedetails';
        $data['delete_enrollment'] = 'User_login/client_users/delete_enrollment';
        $data['getAllUsersForCourses'] = $this->scorm_client_model->getcourseusersdetails($data['student_id'], $data['course_id']);
        echo view('templates/header_view');
        echo view('users/sc_usersassigned_report_view', $data);
        echo view('templates/footer_view');
    }
    public function delete_enrollment()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        if (isset($_POST['user_assign_id'])) {
            $data['user_assign_id'] = $_POST['user_assign_id'];
            $_SESSION['user_assign_id'] = $data['user_assign_id'];
        } else if (isset($_GET['user_assign_id'])) {
            $data['user_assign_id'] = $_GET['user_assign_id'];
        } elseif (isset($_SESSION['user_assign_id'])) {
            $data['user_assign_id'] = $_SESSION['user_assign_id'];
        }
        $newdata = [


            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->deleteEnrollment($newdata, $data['user_assign_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'User_login/client_users/usersassigned_report');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/client_users/usersassigned_report');
        }
    }
    public function deleteScormdetails()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $sc_uid = $_POST['sc_uid'];
        $newdata = [


            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->deleteScormUserdetails($newdata, $sc_uid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'User_login/client_users/usersassigned_report');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/client_users/usersassigned_report');
        }
    }
    // public function course_report($id_user)
    // {
    //     if ($response =  $this->requireRole(['6', '44', '4'])) {
    //         return $response;
    //     }
    //     $data = [];
    //     helper(['form']);
    //     // $data['id_user'] = $id_user;
    //     $data['encode'] = $id_user;
    //     $data['id_user'] = base64_decode($id_user);
    //     $data['userid'] = $data['id_user'];
    //     $userlevel = session()->get('userlevel');
    //     $arrayuserlevel = explode(',', $userlevel);
    //     $client_id = session()->get('client');
    //     $data['client_id'] = $client_id;
    //     $data['username'] = $this->scorm_client_model->getUserName($data['id_user']);
    //     $data['getlatestCoursesForUsers'] = $this->scorm_client_model->getUserlatestCourseUsers($data['id_user']);
    //     //  print_r( $data['getlatestCoursesForUsers']);
    //     $data['getAllCoursesForClient'] = $this->scorm_client_model->getClientCourses($data['client_id']);
    //     // $data['courselearndata'] =  $this->scorm_learn_group_model->getCoursegroupdate(3);
    //     echo view('templates/header_view');
    //     echo view('users/users_courselatest_report_view', $data);
    //     echo view('templates/footer_view');
    // }
    public function course_report($id_user)
    {
        if ($response = $this->requireRole(['6', '44', '4'])) {
            return $response;
        }

        helper(['form']);
        $data = [];

        $session = session();
        $loggedInUserId = $session->get('id_user');
        $clientId = $session->get('client');

        // Decode safely
        $decodedUserId = base64_decode($id_user, true);

        if (!$decodedUserId || !is_numeric($decodedUserId)) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        // Fetch user from DB
        $user = $this->users_model->getUserById($decodedUserId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        //  BOLA Protection (same client check)
        if ($user->client_id != $clientId) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }



        // Safe data assignment
        $data['encode'] = $id_user;
        $data['id_user'] = $decodedUserId;
        $data['userid'] = $decodedUserId;
        $data['client_id'] = $clientId;

        // Fetch data securely
        $data['username'] = $this->scorm_client_model->getUserName($decodedUserId);
        $data['getlatestCoursesForUsers'] = $this->scorm_client_model->getUserlatestCourseUsers($decodedUserId);
        $data['getAllCoursesForClient'] = $this->scorm_client_model->getClientCourses($clientId);

        return view('templates/header_view')
            . view('users/users_courselatest_report_view', $data)
            . view('templates/footer_view');
    }
    public function searchbyCoursereport($id_user)
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        // $data['id_user'] = $id_user;
        $data['encode'] = $id_user;
        $data['id_user'] = base64_decode($id_user);
        $data['userid'] = $data['id_user'];
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $client_id = session()->get('client');
        $data['course_name'] = $_POST['course_name'];
        $data['client_id'] = $client_id;
        $data['username'] = $this->scorm_client_model->getUserName($data['id_user']);
        $data['getlatestCoursesForUsers'] = $this->scorm_client_model->getUserlatestCourseUsers($data['id_user']);
        $getUserlatestCourseUsersbysearch = $this->scorm_client_model->getUserlatestCourseUsersbysearch($data['id_user'], $data['course_name']);
        if (!empty($getUserlatestCourseUsersbysearch)) {
            $data['getUserlatestCourseUsersbysearch'] = $getUserlatestCourseUsersbysearch;
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0024'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/client_users/course_report/' . $id_user);
        }
        //  print_r( $data['getlatestCoursesForUsers']);
        $data['getAllCoursesForClient'] = $this->scorm_client_model->getClientCourses($data['client_id']);
        // $data['courselearndata'] =  $this->scorm_learn_group_model->getCoursegroupdate(3);
        echo view('templates/header_view');
        echo view('users/users_courselatest_report_view', $data);
        echo view('templates/footer_view');
    }
    function searchcoursereportByStatus($id_user)
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        // $data['id_user'] = $id_user;
        $data['encode'] = $id_user;
        $data['id_user'] = base64_decode($id_user);
        $data['userid'] = $data['id_user'];
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $client_id = session()->get('client');
        $data['status'] = $_POST['status'];
        // print_r($data['status']);
        // exit();
        $data['client_id'] = $client_id;
        $data['username'] = $this->scorm_client_model->getUserName($data['id_user']);
        $data['getlatestCoursesForUsers'] = $this->scorm_client_model->getUserlatestCourseUsers($data['id_user']);
        $getUserlatestCourseUsersbysearch = $this->scorm_client_model->searchcoursereportByStatus($data['id_user'], $data['status']);
        if (!empty($getUserlatestCourseUsersbysearch)) {
            $data['getUserlatestCourseUsersbysearch'] = $getUserlatestCourseUsersbysearch;
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0024'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/client_users/course_report/' . $id_user);
        }
        //  print_r( $data['getlatestCoursesForUsers']);
        $data['getAllCoursesForClient'] = $this->scorm_client_model->getClientCourses($data['client_id']);
        // $data['courselearndata'] =  $this->scorm_learn_group_model->getCoursegroupdate(3);
        echo view('templates/header_view');
        echo view('users/users_courselatest_report_view', $data);
        echo view('templates/footer_view');
    }

    // public function deleteEnrollment()
    // {
    //     if ($response =  $this->requireRole(['6', '44', '4'])) {
    //         return $response;
    //     }
    //     $data = [];
    //     helper(['form']);
    //     if (isset($_POST['user_assign_id'])) {
    //         $data['user_assign_id'] = $_POST['user_assign_id'];
    //     } else {
    //         return redirect()->to(base_url() . 'User_login/client_users/usersassigned_report');
    //     }
    //     $data['encode'] = $_POST['encode'];

    //     if ($this->request->getPost()) {
    //         $newdata = [
    //             'status' => 2,
    //             
    //              
    //             'last_updated_by' => session()->get('id_user'),
    //             'last_updated_on' => time(),
    //         ];
    //         $result = $this->xapi_scenarios_model->delEnrollment($newdata, $data['user_assign_id']);

    //         if ($result) {
    //             session()->setFlashdata('success', lang('Messages.Success_0020'));
    //         } else {
    //             session()->setFlashdata('error', lang('Messages.Error_0001'));
    //             session()->setFlashdata('alert-class', 'alert-danger');
    //         }
    //         return redirect()->to(base_url() . 'User_login/client_users/course_report/' . $data['encode']);
    //     }
    // }
    public function deleteEnrollment()
    {
        if ($response = $this->requireRole(['6', '44', '4'])) {
            return $response;
        }

        helper(['form']);
        $session = session();
        $loggedInUserId = $session->get('id_user');
        $clientId = $session->get('client');

        // Validate input
        $user_assign_id = $this->request->getPost('user_assign_id');
        $encode = $this->request->getPost('encode');

        if (empty($user_assign_id)) {
            return redirect()->to(base_url('User_login/client_users/usersassigned_report'))
                ->with('error', 'Invalid request');
        }

        // Fetch enrollment
        $enrollment = $this->xapi_scenarios_model->getEnrollmentById($user_assign_id);

        if (!$enrollment) {
            return redirect()->back()->with('error', 'Enrollment not found');
        }

        //  BOLA Protection
        if ($enrollment->client_id != $clientId) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // // Optional: Prevent deleting own enrollment (if needed)
        // if ($enrollment->fk_user_id == $loggedInUserId) {
        //     return redirect()->back()->with('error', 'You cannot delete your own enrollment');
        // }

        //  Update (soft delete)
        $newdata = [
            'status' => 2,


            'last_updated_by' => $loggedInUserId,
            'last_updated_on' => time(),
        ];

        $result = $this->xapi_scenarios_model->delEnrollment($newdata, $user_assign_id);

        if ($result) {
            $session->setFlashdata('success', lang('Messages.Success_0020'));
        } else {
            $session->setFlashdata('error', lang('Messages.Error_0001'));
        }

        return redirect()->to(base_url('User_login/client_users/course_report/' . $encode));
    }

    // public function getscormuserdetails()
    // {
    //     if ($response =  $this->requireRole(['6', '44', '4'])) {
    //         return $response;
    //     }
    //     $data = [];
    //     helper(['form']);
    //     $userlevel = session()->get('userlevel');
    //     $arrayuserlevel = explode(',', $userlevel);
    //     if (isset($_POST['user_assign_id'])) {
    //         $data['user_assign_id'] = $_POST['user_assign_id'];
    //         $_SESSION['user_assign_id'] = $data['user_assign_id'];
    //     } else if (isset($_GET['user_assign_id'])) {
    //         $data['user_assign_id'] = $_GET['user_assign_id'];
    //     } elseif ($_SESSION['user_assign_id']) {
    //         $data['user_assign_id'] = $_SESSION['user_assign_id'];
    //     }

    //     $client_id = session()->get('client');
    //     $data['client_id'] = $client_id;
    //     $data['username'] = $this->scorm_client_model->getUserasignedName($data['user_assign_id']);
    //     $data['view_details'] = 'XAPI/XAPI_users/viewDetailedReport';
    //     $data['delete_enrollment'] = 'XAPI/XAPI_users/users_delete_enrollment';
    //     $data['getAllCoursesForUsers'] = $this->scorm_client_model->getcourseusersdetails($data['user_assign_id']);
    //     // print_r($data['getAllCoursesForUsers']);
    //     echo view('templates/header_view');
    //     echo view('users/user_courses_report_view', $data);
    //     echo view('templates/footer_view');
    // }
    public function getscormuserdetails()
    {
        if ($response = $this->requireRole(['6', '44', '4'])) {
            return $response;
        }

        helper(['form']);
        $session = session();
        $loggedInUserId = $session->get('id_user');
        $clientId = $session->get('client');

        // Always prefer POST over GET, avoid SESSION trust
        $user_assign_id = $this->request->getPost('user_assign_id')
            ?? $this->request->getGet('user_assign_id');

        if (empty($user_assign_id) || !is_numeric($user_assign_id)) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        // Fetch enrollment
        $enrollment = $this->xapi_scenarios_model->getEnrollmentById($user_assign_id);

        if (!$enrollment) {
            return redirect()->back()->with('error', 'Record not found');
        }

        // BOLA Protection
        if ($enrollment->client_id != $clientId) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }


        // Safe session usage (after validation only)
        $session->set('user_assign_id', $user_assign_id);

        $data = [];
        $data['user_assign_id'] = $user_assign_id;
        $data['client_id'] = $clientId;

        // Safe queries
        $data['username'] = $this->scorm_client_model->getUserasignedName($user_assign_id);
        $data['view_details'] = 'XAPI/XAPI_users/viewDetailedReport';
        $data['delete_enrollment'] = 'XAPI/XAPI_users/users_delete_enrollment';
        $data['getAllCoursesForUsers'] = $this->scorm_client_model->getcourseusersdetails($user_assign_id);

        return view('templates/header_view')
            . view('users/user_courses_report_view', $data)
            . view('templates/footer_view');
    }
    // public function deletecourseuserdetails()
    // {
    //     if ($response =  $this->requireRole(['6', '44', '4'])) {
    //         return $response;
    //     }
    //     $sc_uid = $_POST['sc_uid'];
    //     $id_user = $_POST['id_user'];
    //     $newdata = [
    //         'sc_uid' => $sc_uid,
    //          
    //         
    //         'status' => '0',
    //         'last_updated_by' => session()->get('id_user'),
    //         'last_updated_on' => time(),
    //     ];
    //     $result = $this->scorm_client_model->deleteScormUserdetails($newdata, $sc_uid);
    //     if ($result) {
    //         $sessionData = session();
    //         $sessionData->setFlashdata('success', 'Course deleted successfully!');
    //         return redirect()->to(base_url() . 'User_login/client_users/course_report/' . $id_user);
    //     }
    //     session()->setFlashdata('error', lang('Messages.Error_0001'));
    //     session()->setFlashdata('alert-class', 'alert-danger');
    //     return redirect()->to(base_url() . 'User_login/client_users/course_report/' . $id_user);
    // }
    public function deletecourseuserdetails()
    {
        if ($response = $this->requireRole(['6', '44', '4'])) {
            return $response;
        }

        $session = session();
        $loggedInUserId = $session->get('id_user');
        $clientId = $session->get('client');

        //  Safe input handling
        $sc_uid = $this->request->getPost('sc_uid');
        $id_user = $this->request->getPost('id_user');

        if (empty($sc_uid) || !is_numeric($sc_uid)) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        // Fetch record
        $record = $this->scorm_client_model->getScormUserDetailsById($sc_uid);

        if (!$record) {
            return redirect()->back()->with('error', 'Record not found');
        }

        // BOLA Protection
        if ($record->client_id != $clientId) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // Prevent self-impact (optional)
        if ($record->fk_user_id == $loggedInUserId) {
            return redirect()->back()->with('error', 'You cannot delete your own course data');
        }

        // Soft delete
        $newdata = [


            'status' => '0',
            'last_updated_by' => $loggedInUserId,
            'last_updated_on' => time(),
        ];

        $result = $this->scorm_client_model->deleteScormUserdetails($newdata, $sc_uid);

        if ($result) {
            $session->setFlashdata('success', 'Course deleted successfully!');
        } else {
            $session->setFlashdata('error', lang('Messages.Error_0001'));
        }

        return redirect()->to(base_url('User_login/client_users/course_report/' . $id_user));
    }
    public function delete_userenrollment()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $course_id = $_GET['course_id'];
        $id_user = $_GET['id_user'];
        // print_r($course_id.''. $id_user);
        // exit();
        $newdata = [


            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->deleteEnrollment($newdata, $id_user, $course_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'User_login/client_users/course_report/' . base64_encode($id_user));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/client_users/course_report/' . base64_encode($id_user));
        }
    }
    public function course_add_view()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'Courses';
        $data['header_link'] = 'User_login/client_users/manageTrainings';
        $data['sub_header_1'] = 'Create New Course';
        $data['form_link'] = 'User_login/client_users/addcourse';

        $data['typeval'] = 2;

        echo view('templates/header_view', $data);
        echo view('tranings/courses_add_view', $data);
        echo view('templates/footer_view');
    }
    public function addcourse()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $user = session()->get('username');
        $clientid = session()->get('client');
        $type = 50 + $clientid;

        if ($this->request->getPost()) {

            $rules = [
                'course_name' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'client_id' => session()->get('client'),
                    'course_name' => $this->request->getVar('course_name'),
                    'description' => $this->request->getVar('description'),
                    'objectives' => $this->request->getVar('objectives'),
                    'duration' => $this->request->getVar('duration'),
                    'type' => $type,
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->scorm_course_model->addClientcoursedetails($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'User_login/client_users/manageTrainings');
        }
    }
    public function course_edit_view()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);


        $data['header'] = 'Courses';
        $data['header_link'] = 'User_login/client_users/manageTrainings';
        $data['sub_header_1'] = 'Edit Course';
        $data['form_link'] = 'User_login/client_users/editcourse';

        $data['typeval'] = 2;

        $user = session()->get('username');
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'User_login/client_users/manageTrainings');
        }
        $getCourseData = $this->scorm_course_model->getCourseDetails($data['scourse_id']);
        $data['row'] = $getCourseData[0];
        $data['allmetadata'] = $this->scorm_course_model->getAllMetadata(1);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(2);
        $data['getAssignmetadata'] = $this->scorm_course_model->getAssignmetadata($data['scourse_id']);
        $data['getAssigncategorydata'] = $this->scorm_course_model->getAssigncategorydata($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('tranings/courses_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function editcourse()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $scourse_id = $this->request->getVar('scourse_id');
        if ($this->request->getPost()) {
            $newdata = [
                'course_name' => $this->request->getVar('course_name'),
                'description' => $this->request->getVar('description'),
                'objectives' => $this->request->getVar('objectives'),
                'duration' => $this->request->getVar('duration'),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->scorm_course_model->editcoursedetails($newdata, $scourse_id);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'User_login/client_users/course_edit_view');
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'User_login/client_users/course_edit_view');
            }
            return redirect()->to(base_url() . 'User_login/client_users/course_edit_view');
        }
    }
    public function activate()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $scourse_id = $this->request->getVar('scourse_id');
        $newdata = [
            'upload' => $this->request->getVar('filename'),
        ];
        $result = $this->scorm_course_model->editcoursedetails($newdata, $scourse_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
        } else {
            session()->setFlashdata('error', 'Error in submitting the request.');
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }

    public function del_folder()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        if (isset($_POST['folderloc'])) {
            $dirPath = $_POST['folderloc'];
            $couser_id = $_POST['scourse_id'];
            $folder_name = $_POST['folder_name'];

            $dir = $dirPath . DIRECTORY_SEPARATOR;
            $this->emptyDir($dir);
            rmdir($dir);
            $newdata = [


                'status' => '0',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->scorm_course_model->delscormfiles($newdata, $couser_id, $folder_name);
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }
    public function del_file()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        if (isset($_POST['fileloc'])) {
            $dirPath = $_POST['fileloc'];
            unlink($dirPath);
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }
    public function emptyDir($dir)
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        if (is_dir($dir)) {
            $scn = scandir($dir);
            foreach ($scn as $files) {
                if ($files !== '.') {
                    if ($files !== '..') {
                        if (!is_dir($dir . '/' . $files)) {
                            unlink($dir . '/' . $files);
                        } else {
                            $this->emptyDir($dir . '/' . $files);
                            rmdir($dir . '/' . $files);
                        }
                    }
                }
            }
        }
    }

    public function deleteCoursedetails()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $scourse_id = $_POST['scourse_id'];
        $newdata = [
            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->deltecourse($newdata, $scourse_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'User_login/client_users');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/client_users');
        }
    }

    public function deleteasignmetadetails()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $mc_id = $_POST['mc_id'];
        $newdata = [
            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->deleteassignmeta($newdata, $mc_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'User_login/client_users/course_edit_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/client_users/course_edit_view');
        }
    }

    public function upload_view()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['scourse_id'] = $_POST['scourse_id'];
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/upload_view', $data);
        echo view('templates/footer_view');
    }
    public function thumbnail_upload()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,150]|ext_in[file,jpg]'
            ];
            if (!$this->validate($rules)) {
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'])) {
                            mkdir('assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'] . '/' . $filename;
                                $newdata = [
                                    'scourse_id' => $data['scourse_id'],
                                    'thumbnail' => $filename,
                                    'last_updated_by' => session()->get('id_user'),
                                    'last_updated_on' => time(),
                                ];
                                $result = $this->scorm_course_model->editcoursedetails($newdata, $data['scourse_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }
    function scorm_upload()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);

        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        if ($this->request->getPost()) {
            if ($file = $this->request->getFile('zip_file')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $filename = $file->getName();
                    $timestamp = time();
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    if ($extension == 'zip') {
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp)) {
                            mkdir('assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp, 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp)) {
                            $getfiles = glob(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/*');
                            // print_r($getfiles);
                            //exit();
                            foreach ($getfiles as $eachfile) {
                                if (is_file($eachfile)) {
                                    // Delete the given file
                                    unlink($eachfile);
                                } elseif (is_dir($eachfile)) {
                                    delete_files($eachfile, true);
                                    rmdir($eachfile);
                                }
                            }
                            $targetzip = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/' . $filename;
                            $filenoext = basename($filename, '.zip');  // absolute path to the directory where zipper.php is in (lowercase)
                            $filenoext = basename($filenoext, '.ZIP');  // absolute path to the directory where zipper.php is in (when uppercase)
                            //$targetdir = $path . $filenoext; // target directory

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp, $filename)) {
                                $zip = new ZipArchive();
                                $x = $zip->open($targetzip);
                                if ($x === true) {
                                    $zip->extractTo(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp); // place in the directory with same name  
                                    $zip->close();
                                    unlink($targetzip);
                                }
                            }

                            $newdata = [
                                'scourse_id' => $data['scourse_id'],
                                'upload' => $timestamp,
                                'last_updated_by' => session()->get('id_user'),
                                'last_updated_on' => time(),
                            ];
                            $result = $this->scorm_course_model->editcoursedetails($newdata, $data['scourse_id']);
                            return json_encode($result);
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }
    public function assignmetacategory()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $newData = [
            'fk_scourse_id' => $_POST['scourse_id'],
            'fk_sc_mcid' => $_POST['metaCategory'],
            'typeofval' => $_POST['typeofval'],
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->assignmetacategorydata($newData);
        echo json_encode($result);
    }
    public function uploadpromovideo()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        }
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,20480]|ext_in[file,mp4]'
            ];
            if (!$this->validate($rules)) {
                $data['promovalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'])) {
                            mkdir('assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
                        } else {

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'] . '/' . $filename;
                                $newdata = [
                                    'scourse_id' => $data['scourse_id'],
                                    'promo_video' => $filename,
                                    'last_updated_by' => session()->get('id_user'),
                                    'last_updated_on' => time(),
                                ];
                                $result = $this->scorm_course_model->editcoursedetails($newdata, $data['scourse_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }
    function calcualte_totaltime()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $getSCORMuserdetails = $this->scorm_client_model->getScuserdetails();
        foreach ($getSCORMuserdetails as $all_attempts_data) {
            $totalTime = '00:00:00';
            $trimmedsessionTime = '00:00:00';
            $splitotalTime = '00:00:00';
            if (strlen($all_attempts_data['session_time']) > 4) {
                if ($all_attempts_data['total_time'] == '' || $all_attempts_data['total_time'] == '00:00:00.00') {
                    $splitotalTime = '00:00:00';
                } else {
                    $splitotalTime = explode('.', $all_attempts_data['total_time'])[0];
                }
                if (strlen($all_attempts_data['session_time']) > 8) {
                    $splitsession_time = explode('.', $all_attempts_data['session_time']);
                    $trimmedsessionTime = substr($splitsession_time[0], 2);
                }
                if (strlen($all_attempts_data['session_time']) == 8) {
                    $trimmedsessionTime = explode('.', $all_attempts_data['session_time'])[0];
                }
                $matches0 = explode(':', $splitotalTime); // split up the string
                $matches1 = explode(':', $trimmedsessionTime);
                $sec0 = $matches0[0] * 60 * 60 + $matches0[1] * 60 + $matches0[2];
                $sec1 = $sec0 + $matches1[0] * 3600 + $matches1[1] * 60 + $matches1[2]; // get total seconds
                $h = intval(($sec1) / 3600);
                $m = intval(($sec1 - $h * 3600) / 60);
                $s = $sec1 - $h * 3600 - $m * 60;
                $totalTime = str_pad($h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($m, 2, '0', STR_PAD_LEFT) . ':' . str_pad($s, 2, '0', STR_PAD_LEFT);

                $data = [
                    'cal_totaltime' => $totalTime
                ];
                $result = $this->scorm_client_model->insertTotaltime($data, $all_attempts_data['sc_uid']);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url() . 'User_login/client_users/report_admin');
    }
    public function give_grace()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $user_assign_id = $_POST['user_assign_id'];
        // $course_id = $_POST['course_id'];
        $data = array(
            'grace' => 1
        );
        $this->assessment_training_model->del_sim_user($user_assign_id, $data);
        return redirect()->to(base_url() . 'User_login/client_users/usersassigned_report');
    }
    public function add_learnGroup_to_users()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $numcourses = 0;
        if ($this->request->getPost()) {
            if (isset($_POST['id_user'])) {
                $data['user_id'] = $_POST['id_user'];
                $_SESSION['user_id'] = $data['user_id'];
            }
            $group_id = $this->request->getVar('group_id');
            $due_date = $this->request->getVar('due_date');


            $coursesInGroup = $this->scorm_learn_group_model->getCoursesAssignedto($group_id);

            foreach ($coursesInGroup as $assigned) {

                $course_id = $assigned['course_id'];
                $checkifassigned = $this->scorm_learn_group_model->checklearnassigned($data['user_id'], $group_id);
                // print_r($checkifassigned);
                // exit();
                if ($checkifassigned == 0) {
                    $numcourses++;
                    $newdata = [
                        'id_user' => $data['user_id'],
                        'group_id' => $group_id,
                        'due_date' => $due_date,
                        'status' => '1',
                        'createdby' => session()->get('id_user'),
                        'createdon' => time(),
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    $this->scorm_learn_group_model->addLearncoursetoUser($newdata);
                }
            }
            if ($numcourses > 0) {
                session()->setFlashdata('success', $numcourses . ' ' . lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'User_login/client_users/assign_learnGroup_to_user');
            } else {
                session()->setFlashdata('error', 'No courses from the group got added.');
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'User_login/client_users/assign_learnGroup_to_user');
            }
        }
    }
    function assign_learnGroup_to_user()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['id_user'] = isset($_POST['id_user']) ? $_POST['id_user'] : session()->get('user_id');
        $data['username'] = $this->scorm_client_model->getUserName($data['id_user']);

        $data['courselearndata'] = $this->scorm_learn_group_model->getCoursegroupdate(3);
        $data['learngroupdata'] = $this->scorm_learn_group_model->getlearngroupdata($data['id_user']);
        //  print_r($data['courselearndata'] );
        // exit();
        echo view('templates/header_view', $data);
        echo view('learn_group/assign_learngroup_view', $data);
        echo view('templates/footer_view');
    }
}
