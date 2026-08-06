<?php


namespace App\Controllers;

use App\Models\SCORM\Scorm_dashboard_model;
use App\Models\XAPI\API_model;

#[\AllowDynamicProperties]
class API extends BaseController
{
    public function __construct()
    {
        $this->scorm_dashboard_model = new Scorm_dashboard_model();
        $this->API_model = new API_model();
    }
    public function APIaccess($temp,$course_id) //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        // $temp = $_GET['code'] ?? null;
        // $course_id = $_GET['cid'] ?? null;
        if (isset($temp) && isset($course_id)) {
            // $useridlength =  substr($temp, 0, 1);
            // $id_user = substr($temp, 1, $useridlength);
            //   echo $id_user.' user id ';
            $checkUserValid =  $this->scorm_dashboard_model->getUserAthenticate($temp);
            if ($checkUserValid) {
                $id_user = strlen($checkUserValid[0]['id_user']);
                // $getUserTimestamp = $checkUserValid[0]['timestamp'];
                // $userLength = strlen($useridlength . '' . $id_user);
                // $length =  6 - $userLength;
                // $timestampLastDigit = substr($getUserTimestamp, -$length);
                // $generatedNumber = $useridlength . $id_user . $timestampLastDigit;
                $app_username = $checkUserValid[0]['app_username'];
                if ($app_username  === $temp) {
                    //  echo $id_user.' course '.$course_id;
                    $id_user = intval($id_user);
                    $user_assigned_id = $this->API_model->checkUserAssignment($id_user, $course_id);
                    //   echo  $user_assigned_id[0]['user_assign_id'];
                    if ($user_assigned_id) {
                        $user_assigned_id_val = $user_assigned_id[0]['user_assign_id'];
                        $scenario_id = $user_assigned_id[0]['scenario_id'];
                        $current_attempt = 0;
                        $attempt = $this->API_model->checkattempts($user_assigned_id_val);
                        if ($attempt) {
                            $current_attempt = $attempt[0]['attempt'];
                        }
                        $current_attempt++;
                        $newdata = [
                            'user_assign_id' => $user_assigned_id_val,
                            'lesson_status' => 'incomplete',
                            'attempt' => $current_attempt,
                            'last_active' => time(),
                            'createdon' => time(),
                            'last_updated_on' => time(),
                            'status' => '1',
                        ];
                        $record_id = $this->API_model->createNewActivity($newdata);
                        if ($record_id) {
                            $newdata2 = [
                                'sc_uid' => $record_id,
                                'variable' => 'login',
                                'createdon' => time(),
                                'last_updated_on' => time(),
                                'status' => '1',
                            ];
                            $this->API_model->enterUserData($newdata2);
                            $data['result'] = 'Valid||recordID|' . $record_id;
                            $data['result'] = $data['result'] . '||User Name|' . $checkUserValid[0]['name'];
                            $scenario_settings = $this->API_model->getScenarioSettings($scenario_id);
                            if ($scenario_settings) {
                                foreach ($scenario_settings as $sce_set) {
                                    $data['result'] =  $data['result'] . '||' . $sce_set['variable_name'] . '|' . $sce_set['value'];
                                }
                            }
                        } else {
                            $data['result'] = 'Invalid Data. New Activity not created';
                        }
                    } else {
                        $data['result'] = 'Course not assigned to User.';
                    }
                } else {
                    $data['result'] = 'App Username doesn\'t match';
                }
            } else {
                $data['result'] = 'User not found';
            }
        } else {
            $data['result'] = 'Invalid URL link';
        }
        echo view('XAPI/API_view', $data);
    }
    public function VRaccess($temp, $course_id, $app_pwd)
    {
        $data = [];
        helper(['form']);
        // $temp = $_GET['code'] ?? null;
        // $course_id = $_GET['cid'] ?? null;
        // $app_pwd =  trim($_GET['app_pwd']) ?? null;
        if (isset($temp) && isset($course_id) && isset($app_pwd)) {
            $checkUserValid =  $this->scorm_dashboard_model->getUserAthenticate($temp);
            if ($checkUserValid) {
                $username = $checkUserValid[0]['username'];
                $id_user = $checkUserValid[0]['id_user'];
                $password = $checkUserValid[0]['password'];
                $app_password = trim($checkUserValid[0]['app_password']);
                //print_r($app_pwd);
                $checkapppass = false;
                if ($app_password == $app_pwd) {
                    $checkapppass = true;
                }
                // $checkapppass = password_verify($app_pwd, $app_password);
                $checkpass = password_verify($app_pwd, $password);
                if ($checkpass == true || $checkapppass == true) {
                    // $useridlength = strlen($checkUserValid[0]['id_user']);
                    // $getUserTimestamp = $checkUserValid[0]['timestamp'];
                    // $userLength = strlen($useridlength . '' . $id_user);
                    // $length =  6 - $userLength;
                    // $timestampLastDigit = substr($getUserTimestamp, -$length);
                    // $generatedNumber = $useridlength . $id_user . $timestampLastDigit;
                    $app_username = $checkUserValid[0]['app_username'];
                    if ($app_username === $temp ||  $username == $temp) {
                        //  echo $id_user.' course '.$course_id;
                        // $id_user = ($app_username === $temp) ? intval($id_user) : $user_id;
                        $user_assigned_id = $this->API_model->checkUserAssignment($id_user, $course_id);
                        //   echo  $user_assigned_id[0]['user_assign_id'];
                        if ($user_assigned_id) {
                            $UserassignedScenariodata =  $this->API_model->getUserassignedScenario($course_id, $id_user);
                            if (!empty($UserassignedScenariodata) && $UserassignedScenariodata[0]['role'] != '') {
                                if ($UserassignedScenariodata[0]['role'] == 1) {
                                    $role = 'User';
                                } else {
                                    $role = 'Instructor';
                                }
                                $user_assigned_id_val = $user_assigned_id[0]['user_assign_id'];
                                $scenario_id = $UserassignedScenariodata[0]['scenario_id'];
                                // print_r($scenario_id);
                                // exit();
                                $current_attempt = 0;
                                $attempt = $this->API_model->checkattempts($user_assigned_id_val);
                                if ($attempt) {
                                    $current_attempt = $attempt[0]['attempt'];
                                }
                                $current_attempt++;
                                $newdata = [
                                    'user_assign_id' => $user_assigned_id_val,
                                    'lesson_status' => 'incomplete',
                                    'attempt' => $current_attempt,
                                    'last_active' => time(),
                                    'createdon' => time(),
                                    'last_updated_on' => time(),
                                    'status' => '1',
                                ];
                                $record_id = $this->API_model->createNewActivity($newdata);
                                if ($record_id) {
                                    $newdata2 = [
                                        'sc_uid' => $record_id,
                                        'variable' => 'login',
                                        'createdon' => time(),
                                        'last_updated_on' => time(),
                                        'status' => '1',
                                    ];
                                    $this->API_model->enterUserData($newdata2);

                                    $data['result'] = 'Valid||User id|' . $checkUserValid[0]['id_user'] . '||recordID|' . $record_id;
                                    $data['result'] = $data['result'] . '||User Name|' . $checkUserValid[0]['name'] . '|| Role |' . $role;
                                    $scenario_settings = $this->API_model->getScenarioSettings($scenario_id);
                                    if ($scenario_settings) {
                                        foreach ($scenario_settings as $sce_set) {
                                            $data['result'] =  $data['result'] . '||' . $sce_set['variable_name'] . '|' . $sce_set['value'];
                                        }
                                    }
                                } else {
                                    $data['result'] = 'Invalid Data. New Activity not created';
                                }
                            } else {
                                $data['result'] = 'Role is not Assigned to User';
                            }
                        } else {
                            $data['result'] = 'Course not assigned to User.';
                        }
                    } else {
                        $data['result'] = 'App Username doesn\'t match';
                    }
                } else {
                    $data['result'] = "Password Does't Match";
                }
            } else {
                $data['result'] = 'User not found';
            }
        } else {
            $data['result'] = 'Invalid URL link';
        }
        echo view('XAPI/API_view', $data);
    }
    function verifypass($app_pwd, $app_password)
    {
        return password_verify($app_pwd, $app_password);
    }
    public function reportData($recordID,$variable,$value) //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        // $recordID = $_GET['recordID'] ?? null;
        // $variable = $_GET['variable'] ?? null;
        // $value = $_GET['value'] ?? null;
        if (isset($recordID) && isset($variable)) {
            $newdata2 = [
                'sc_uid' => $recordID,
                'variable' => $variable,
                'value' => $value,
                'createdon' => time(),
                'last_updated_on' => time(),
                'status' => '1',
            ];
            $datasave = $this->API_model->enterUserData($newdata2);

            $sitevariables = array("a" => "score", "b" => "simulation_status",  "c" => "total_time");

            $ifsiteVariable = array_search($variable, $sitevariables);
            if ($ifsiteVariable) {
                if ($ifsiteVariable == "a") {
                    $updatedata =  [
                        'raw' => $value,
                    ];
                }
                if ($ifsiteVariable == "b") {
                    $updatedata =  [
                        'lesson_status' => $value,
                    ];
                }
                if ($ifsiteVariable == "c") {
                    $updatedata =  [
                        'session_time' => $value,
                    ];
                }
                $this->API_model->updateStat($updatedata, $recordID);
                if ($ifsiteVariable == "b") {
                    $updatedata =  [
                        'last_active' => time(),
                    ];
                }
                $this->API_model->updateStat($updatedata, $recordID);
            }
            //  }


            if ($datasave) {
                $data['result'] = 'Data Saved.';
            } else {
                $data['result'] = 'Invaid Data Error!6345';
            }
        } else {
            $data['result'] = 'Invaid Data Error!6335';
        }
        echo view('XAPI/API_view', $data);
    }
    public function suspendata_in($id_user, $course_id, $attempt, $suspend_data)
    {
        // $id_user = $_GET['id_user'] ?? null;
        // $course_id = $_GET['course_id'] ?? null;
        // $attempt =  trim($_GET['attempt']) ?? null;
        // $suspend_data =  trim($_GET['suspend_data']) ?? null;

        if (isset($id_user) && isset($course_id) && isset($attempt) && isset($suspend_data)) {
            // print_r($suspend_data);
            $user_assigned_id = $this->API_model->checkUserAssignment($id_user, $course_id);
            // print_r( $user_assigned_id);
            if ($user_assigned_id) {
                $user_assigned_id_val = $user_assigned_id[0]['user_assign_id'];
                $newdata = [
                    'suspend_data' =>  $suspend_data,
                    'createdon' => time(),
                    'last_updated_on' => time(),
                    'status' => '1',
                ];
                $updateSuspenddatain = $this->API_model->updateSuspendDataIn($newdata, $user_assigned_id_val, $attempt);
                if ($updateSuspenddatain) {
                    $data['result'] = 'Suspend data updated';
                } else {
                    $data['result'] = 'Suspend data Not updated';
                }
            } else {
                $data['result'] = 'Course not assigned to User.';
            }
        } else {
            $data['result'] = 'Course not assigned to User.';
        }
        echo view('XAPI/API_view', $data);
    }
    public function suspendata_out($id_user, $course_id, $attempt)
    {
        // $id_user = $_GET['id_user'] ?? null;
        // $course_id = $_GET['course_id'] ?? null;
        // $attempt =  trim($_GET['attempt']) ?? null;

        if (isset($id_user) && isset($course_id) && isset($attempt)) {
            $user_assigned_id = $this->API_model->checkUserAssignment($id_user, $course_id);
            if ($user_assigned_id) {
                $user_assigned_id_val = $user_assigned_id[0]['user_assign_id'];

                $suspend_data = $this->API_model->getSuspendata($user_assigned_id_val, $attempt);
                if ($suspend_data) {
                    $data['result'] = $suspend_data[0]['suspend_data'];
                } else {
                    $data['result'] = 'Suspend data Not found';
                }
            } else {
                $data['result'] = 'Course not assigned to User.';
            }
        } else {
            $data['result'] = 'Course not assigned to User.';
        }
        echo view('XAPI/API_view', $data);
    }
}
