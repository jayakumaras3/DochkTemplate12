<?php

namespace App\Models\SCORM;

use ZipArchive;

use CodeIgniter\Model;

class Scorm_course_model extends Model
{
    protected $table = 'scorm_courses';
    protected $primaryKey = 'scourse_id';
    protected $allowedFields = ['course_name', 'description', 'thumbnail', 'status', 'createdby', 'createdon', 'deletedby', 'deletedon'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];



    public function getfullCourseDetails()
    {
        // $db = \Config\Database::connect();
        // $builder = $this->db->table('scorm_courses as c');
        // $builder->select('c.*, u.name as createdby');
        // $builder->join('users as u', 'u.id_user = c.createdby', 'left');
        // $builder->where('c.status !=', '0');
        // $builder->where('c.type !=', '11');
        // $data = $builder->get()->getResultArray();
        // return $data;
        $builder = $this->db->table('marketplace_courses as mpc');
        $builder->select('ANY_VALUE(mpc.mp_co_id) as mp_co_id, ANY_VALUE(mpc.price) as price, ANY_VALUE(mpc.scorm_id) as scorm_id, ANY_VALUE(c.course_name) as course_name, c.status,GROUP_CONCAT(DISTINCT(cat.description) ORDER BY cat.description ASC SEPARATOR ", ") as category_name, c.course_code, c.duration, c.language, c.avg_rating,c.thumbnail,c.scourse_id');
        $builder->join('scorm_courses as c', 'c.scourse_id = mpc.scorm_id AND c.status != 0', 'left');
        $builder->join('assign_meta_category as asn', 'asn.fk_scourse_id  = c.scourse_id AND asn.status!=0', 'left');
        $builder->join('scorm_meta_category as cat', 'cat.sc_mcid = asn.fk_sc_mcid AND cat.status!=0', 'left');
        $builder->where('mpc.status !=', 0);
        $builder->where('mpc.mp_id', 1);
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function fullCourseDetails()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*, u.name as createdby');
        $builder->join('users as u', 'u.id_user = c.createdby', 'left');
        $builder->where('c.status !=', '0');
        // $builder->where('c.type !=', '11');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getCourseDetails($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*');
        $builder->where('c.scourse_id', $course_id);
        $builder->where('c.status !=', '0');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_courses_assigned_to_project($projectid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*');
        $builder->where('c.project_id', $projectid);
        $builder->where('c.status !=', '0');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_non_assigned_courses()
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.scourse_id, c.course_name');
        $builder->where('c.project_id', 0);
        $builder->where('c.status !=', '0');
        $builder->orderBy('c.course_name', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_project_data($projectid)
    {
        $builder = $this->db->table('projects as p');
        $builder->select('p.*');
        $builder->where('p.projectid', $projectid);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getInputVariables($scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_input_variables as x');
        $builder->select('x.*,GROUP_CONCAT(dv.text ORDER BY dv.xiv ASC SEPARATOR ", ") as textName');
        $builder->join('xapi_input_dropdown_values as dv', 'dv.xiv = x.xiv', 'left');
        $builder->groupBy('x.xiv');
        $builder->where('x.scourse_id', $scourse_id);
        $builder->where('x.status !=', '0');
        $data = $builder->get()->getResultArray();
        return $data;
    }


    public function getOutputVariables($scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_output_variables as x');
        $builder->select('x.*');
        $builder->where('x.scourse_id', $scourse_id);
        $builder->where('x.status !=', '0');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getOutputVariable_detailed($scourse_id, $limit)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_output_variables as x');
        $builder->select('x.counter, x.variable_description, v.negative_verb');
        $builder->join('verbs as v', 'v.verb = x.verb', 'left');
        $builder->where('x.scourse_id', $scourse_id);
        $builder->where('x.counter !=', '0');
        $builder->where('x.status !=', '0');
        $builder->orderBy('x.counter', 'DESC');
        if ($limit > 1) {
            $builder->limit(7);
        }

        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    public function getOutputVariable_detailed_v1($scourse_id, $limit)
    {
        $db = \Config\Database::connect();
        $client_id = session()->get('client');
        $builder = $this->db->table('xapi_output_variables as x');
        $builder->select('count(xd.xov) as counter, x.variable_description, v.negative_verb,x.xov');
        $builder->join('verbs as v', 'v.verb = x.verb', 'left');
        $builder->join('xapi_output_data as xd', 'xd.xov = x.xov  ', 'left');
        $builder->where('x.scourse_id', $scourse_id);
        $builder->where('xd.client_id', $client_id);
        $builder->where('x.status !=', '0');
        $builder->groupBy('xd.xov');
        $builder->orderBy('count(x.xov)', 'DESC');
        if ($limit > 1) {
            $builder->limit(7);
        }
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    public function getOutputVariable_detailed_v2($scourse_id, $limit)
    {
        $db = \Config\Database::connect();
        $client_id = session()->get('client');
        $builder = $this->db->table('assessment_question_report as ar');
        $builder->select('count(ar.qr_id) as counter, aq.question,ar.qr_id as xov');
        $builder->join('assessment_questions as aq', 'aq.q_id = ar.question_id', 'left');
        $builder->where('ar.scourse_id', $scourse_id);
        $builder->where('ar.client_id', $client_id);
        $builder->where('ar.scored', '0');
        $builder->groupBy('ar.qr_id');
        $builder->orderBy('count(ar.qr_id)', 'DESC');
        if ($limit > 1) {
            $builder->limit(7);
        }
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    public function getUsersdefectdata($xov)
    {
        $builder = $this->db->table('xapi_output_data as x');
        $builder->select('x.*,u.name as username,sc.attempt,sc.last_active,sc.sc_uid,c.course_name,c.type');
        $builder->join('users as u', 'u.id_user = x.id_user', 'left');
        // $builder->join('xapi_output_variables as xo', 'xo.xov = x.xov', 'left');
        $builder->join('scorm_user_details as sc', 'sc.sc_uid = x.sc_uid', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = x.course_id', 'left');
        $builder->where('x.xov', $xov);
        // $builder->where('xa.variable', 'missed');
        // $builder->groupBy('sc.sc_uid');
        $builder->orderBy('u.id_user', 'DESC');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    public function getUserswrongquestiondata($xov)
    {
        $builder = $this->db->table('assessment_question_report as x');
        $builder->select('x.*,u.name as username,sc.attempt,sc.last_active,sc.sc_uid,c.course_name,x.qr_id as xov,c.type');
        $builder->join('users as u', 'u.id_user = x.user_id', 'left');
        // $builder->join('xapi_output_variables as xo', 'xo.xov = x.xov', 'left');
        $builder->join('scorm_user_details as sc', 'sc.sc_uid = x.sc_uid', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = x.scourse_id', 'left');
        $builder->where('x.qr_id', $xov);
        // $builder->where('xa.variable', 'missed');
        // $builder->groupBy('sc.sc_uid');
        $builder->orderBy('u.id_user', 'DESC');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    public function getDropDownValues($xiv)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_input_dropdown_values as x');
        $builder->select('x.*');
        $builder->where('x.xiv', $xiv);
        $builder->where('x.status !=', '0');
        $builder->orderBy('x.value', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function checkdroddownvalexist($dropdownval, $xiv)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_input_dropdown_values as x');
        $builder->select('x.*');
        $builder->where('x.xiv', $xiv);
        $builder->where('x.value', $dropdownval);
        $builder->where('x.status !=', '0');

        $totalrows = $builder->countAllResults();
        return $totalrows;
    }

    public function getInputVariablesDetails($xiv)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_input_variables as x');
        $builder->select('x.*');
        $builder->where('x.xiv', $xiv);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getOutputVariablesDetails($xov)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_output_variables as x');
        $builder->select('x.*');
        $builder->where('x.xov', $xov);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getpageAssignmetadatabyID($course_id, $page_id, $type)
    {
        $builder = $this->db->table('assessment_settings as as');
        $builder->select('as.*');
        $builder->where('as.type', $type);
        $builder->where('as.scourse_id', $course_id);
        $builder->where('as.page_id', $page_id);
        $builder->where('as.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAssignmetadatabyID($course_id, $type)
    {
        $builder = $this->db->table('assessment_settings as as');
        $builder->select('as.*');
        $builder->where('as.type', $type);
        $builder->where('as.scourse_id', $course_id);
        $builder->where('as.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllFileOwner($scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_files as x');
        $builder->select('x.folder,x.description');
        $builder->where('x.course_id', $scourse_id);
        $builder->where('x.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllObjectives($scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_courses_objectives as x');
        $builder->select('x.*');
        $builder->where('x.course_id', $scourse_id);
        $builder->where('x.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function add_objectives($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_courses_objectives');
        $builder->insert($newdata);
        return;
    }
    public function addNewInputVariable($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_input_variables');
        $builder->insert($newdata);
        return $db->insertID();
    }
    public function addNewOutputVariable($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_output_variables');
        $builder->insert($newdata);
        return $db->insertID();
    }
    public function addDropDown($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_input_dropdown_values');
        $builder->insert($newdata);
        return $db->insertID();
    }
    public function insertFileuploaddata($scormFile)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_files');
        $builder->insert($scormFile);
        return $db->insertID();
    }
    /* course details */
    // public function addcoursedetails($newdata)
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $this->db->table('scorm_courses');
    //     $builder->insert($newdata);

    //     $data['course_id'] = $db->insertID();
    //     if (isset($data['course_id'])) {
    //         $scourse_id = $data['course_id'];
    //         $scorm_courses_assigned = [
    //             'client_id' => session()->get('client'),
    //             'course_id' => $scourse_id,
    //             'editable' => 1,
    //             'status' => 1,
    //             'createdby' => session()->get('id_user'),
    //             'createdon' => time(),
    //         ];
    //         $builder = $this->db->table('scorm_courses_assigned');
    //         $builder->insert($scorm_courses_assigned);
    //         $userlevel = session()->get('userlevel');
    //         $arrayuserlevel = array_map('intval', explode(',', $userlevel));
    //         if (in_array('4', $arrayuserlevel)) {
    //             $builder = $this->db->table('projects as p');
    //             $builder->select('p.client');
    //             $builder->where('p.projectid', $newdata['project_id']);
    //             $clientasssigndata = $builder->get()->getResultArray();
    //             if (!empty($clientasssigndata)) {
    //                 $client = $clientasssigndata[0]['client'];
    //                 $scorm_courses_assigned = [
    //                     'client_id' => $client,
    //                     'course_id' => $scourse_id,
    //                     'editable' => 1,
    //                     'status' => 1,
    //                     'createdby' => session()->get('id_user'),
    //                     'createdon' => time(),
    //                 ];
    //                 $builder = $this->db->table('scorm_courses_assigned');
    //                 $builder->insert($scorm_courses_assigned);
    //             }
    //         }


    //         $scorm_users_courses_assigned = [
    //             'id_user' => session()->get('id_user'),
    //             'course_id' => $scourse_id,
    //             'status' => 1,
    //             'createdby' => session()->get('id_user'),
    //             'createdon' => time(),
    //         ];
    //         $builder = $this->db->table('scorm_users_courses_assigned');
    //         $builder->insert($scorm_users_courses_assigned);

    //         $timestamp = time();
    //         $hash = hash('sha256', $scourse_id . '' . $timestamp, false);
    //         $db = \Config\Database::connect();
    //         $builder = $this->db->table('scorm_courses as sc');
    //         $builder->set('sc.hash', $hash);
    //         $builder->where('sc.scourse_id', $scourse_id);
    //         $builder->update();
    //         // if ($newdata['type'] == 6) {  // Emanual default english langauage
    //         //     $langdata = [
    //         //         'document_id' => $scourse_id,
    //         //         'lang_id' => 103,
    //         //         'status' => 1,
    //         //         'createdby' => session()->get('id_user'),
    //         //         'createdon' => time(),
    //         //         'last_updated_by' => session()->get('id_user'),
    //         //         'last_updated_on' => time()
    //         //     ];
    //         //     $builder = $this->db->table('emanual_lang');
    //         //     $builder->insert($langdata);
    //         // }
    //         if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id)) {
    //             mkdir('assets/assets/uploads/SCORM_course_document/' . $scourse_id, 0777, true);
    //         }
    //         // if ($newdata['upload_type'] == 2) {
    //         // if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . time())) {
    //         //     mkdir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . time(), 0777, true);
    //         // }

    //         // $zipFilePath = FCPATH . 'assets/assets/uploads/fixed_template.zip';
    //         // $targetDir = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . time();

    //         // // Check if the zip file exists
    //         // if (file_exists($zipFilePath)) {
    //         //     // Open the zip file
    //         //     $zip = new ZipArchive();
    //         //     $zip->open($zipFilePath);

    //         //     // Extract the contents to the target directory
    //         //     $zip->extractTo($targetDir);

    //         //     // Close the zip file
    //         //     $zip->close();
    //         // } else {
    //         //     echo 'The zip file does not exist.';
    //         // }
    //         // $builder = $this->db->table('scorm_courses as sc');
    //         // $builder->set('sc.upload', time());
    //         // $builder->where('sc.scourse_id', $scourse_id);
    //         // $builder->update();
    //         // }
    //         // print_r($scourse_id);
    //         // exit();
    //         return $data;
    //     }
    // }
    public function addcoursedetails($newdata)
    {
        // Reuse existing DB connection
        $builder = $this->db->table('scorm_courses');

        // Insert new course data
        if ($builder->insert($newdata)) {
            // Get inserted course_id
            $scourse_id = $this->db->insertID();

            // Create common data for inserting into scorm_courses_assigned
            $scorm_courses_assigned = [
                'client_id' => session()->get('client'),
                'course_id' => $scourse_id,
                'editable' => 1,
                'status' => 1,
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
            ];

            // Insert into scorm_courses_assigned
            $this->db->table('scorm_courses_assigned')->insert($scorm_courses_assigned);

            // If userlevel includes 4, perform additional actions
            if (in_array('4', array_map('intval', explode(',', session()->get('userlevel'))))) {
                // Fetch the client from project data
                $client = $this->getClientFromProject($newdata['project_id']);
                if ($client) {
                    $courses_assigned = [
                        'client_id' => $client,
                        'course_id' => $scourse_id,
                        'editable' => 1,
                        'status' => 1,
                        'createdby' => session()->get('id_user'),
                        'createdon' => time(),
                    ];

                    $this->db->table('scorm_courses_assigned')->insert($courses_assigned);
                }
            }

            // Assign course to the user
            $this->assignCourseToUser($scourse_id);

            // Generate hash for the course and update
            $this->updateCourseHash($scourse_id);

            // Create course directory if not exists
            $this->createCourseDirectory($scourse_id);

            return ['course_id' => $scourse_id];
        }

        return false; // Return false if insertion fails
    }

    private function getClientFromProject($project_id)
    {
        // Get client info from project data
        $builder = $this->db->table('projects as p');
        $builder->select('p.client');
        $builder->where('p.projectid', $project_id);
        $result = $builder->get()->getRowArray();

        return $result ? $result['client'] : null;
    }

    private function assignCourseToUser($scourse_id)
    {
        // Prepare the user-course assignment data
        $scorm_users_courses_assigned = [
            'client_id' => session()->get('client'),
            'id_user' => session()->get('id_user'),
            'course_id' => $scourse_id,
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
        ];

        // Insert into scorm_users_courses_assigned
        $this->db->table('scorm_users_courses_assigned')->insert($scorm_users_courses_assigned);
    }

    private function updateCourseHash($scourse_id)
    {
        // Generate hash for the course and update
        $hash = hash('sha256', $scourse_id . time(), false);
        $builder = $this->db->table('scorm_courses as sc');
        $builder->set('sc.hash', $hash);
        $builder->where('sc.scourse_id', $scourse_id);
        $builder->update();
    }

    private function createCourseDirectory($scourse_id)
    {
        // Create directory for the SCORM course if it doesn't exist
        $directory = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id;
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    public function addClientcoursedetails($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_courses');
        $builder->insert($newdata);
        $data['course_id'] = $db->insertID();
        if (isset($data['course_id'])) {
            $scourse_id = $data['course_id'];
            $timestamp = time();
            $hash = hash('sha256', $scourse_id . '' . $timestamp, false);

            $builder = $this->db->table('scorm_courses as sc');
            $builder->set('sc.hash', $hash);
            $builder->where('sc.scourse_id', $scourse_id);
            $builder->update();
            $scorm_course_group_assigned = ['client_id' => session()->get('client'), 'course_id' => $data['course_id'], 'group_id' => 0, 'status' => 1, 'createdby' => session()->get('id_user'), 'createdon' => time()];
            $builder = $this->db->table('scorm_courses_assigned');
            $builder->insert($scorm_course_group_assigned);
            return $data;
        }
    }

    public function del_objectives($data, $sco_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_courses_objectives');
        $builder->where('sco_id', $sco_id);
        $builder->update($data);
        return;
    }

    public function update_link_course_project($newdata, $course_id, $client)
    {
        $builder = $this->db->table('scorm_courses');

        $builder->where('scourse_id', $course_id);
        $updateStatus = $builder->update($newdata);

        if ($updateStatus) {

            $scorm_course_group_assigned = [
                'client_id' => $client,
                'course_id' => $course_id,
                'group_id'  => 0,
                'status'    => 1,
                'createdby' => session()->get('id_user'),
                'createdon' => time()
            ];

            $assignBuilder = $this->db->table('scorm_courses_assigned');
            $assignBuilder->insert($scorm_course_group_assigned);
        }

        return;
    }

    public function delscormfiles($newdata, $couser_id, $folder_name)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_files');
        $builder->where('course_id', $couser_id);
        $builder->where('folder', $folder_name);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray($newdata);
        return $data;
    }
    public function editcoursedetails($newdata, $scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_courses');
        $builder->where('scourse_id', $scourse_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray($newdata);
        // print_r($data);
        // exit();
        if (!empty($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error";
        }
        return $data;
    }
    public function getCoursesDetails01($type)
    {
        $db = \Config\Database::connect();
        $client_id = session()->get('client');
        $query = $db->query("SELECT c.course_name as course_name, c.scourse_id as scourse_id
        FROM scorm_courses as c 
        left join scorm_courses_assigned as sca on sca.course_id = c.scourse_id and sca.status>0
        left join scorm_users_courses_assigned as suca on suca.course_id = sca.course_id and suca.status=1
        left join dropdown_users as du on du.fk_id_user = suca.id_user and du.fk_id_dc =1 and du.status =1 and du.fk_id_d=$client_id
        WHERE c.status >0  group by c.scourse_id order by c.course_name Asc");
        $data = $query->getResultArray();
        return $data;
    }
    public function getCoursesDetails($type)
    {
        $db = \Config\Database::connect();
        $client_id = session()->get('client');
        $query = $db->query("SELECT c.course_name as course_name, c.scourse_id as scourse_id,  c.promo_video as promo_video, c.course_code as course_code, c.duration as duration,c.thumbnail,c.upload,c.pdf_filename,c.createdby as addby,c.upload_type,c.mode,
        GROUP_CONCAT(DISTINCT(sca.client_id) ORDER BY (sca.sc_cr_as_id)  ASC SEPARATOR ', ')  as client_id, GROUP_CONCAT(DISTINCT(sca.editable) ORDER BY (sca.sc_cr_as_id)  ASC SEPARATOR ', ')  as editable,count(distinct(du.`fk_id_user`)) as user_count
        FROM scorm_courses as c 
        left join assign_meta_category as am on am.fk_scourse_id = c.scourse_id
        left join scorm_courses_assigned as sca on sca.course_id = c.scourse_id and sca.status>0
        left join scorm_users_courses_assigned as suca on suca.course_id = sca.course_id and suca.status=1
        left join dropdown_users as du on du.fk_id_user = suca.id_user and du.fk_id_dc =1 and du.status =1 and du.fk_id_d=$client_id
        WHERE c.status >0  group by c.scourse_id order by c.course_name Asc");
        $data = $query->getResultArray();
        return $data;
    }
    // public function coursenamessearch_view($course_name)
    // {
    //     $db = \Config\Database::connect();
    //     $client_id = session()->get('client');
    //     // print_r($client_id);
    //     // exit();
    //     $query = $db->query("SELECT c.course_name as course_name, c.scourse_id as scourse_id,  c.promo_video as promo_video, c.course_code as course_code, c.duration as duration,c.thumbnail,c.upload,c.pdf_filename,sca.*,c.createdby as addby,c.type as course_type,c.mode,
    //     GROUP_CONCAT(DISTINCT(sca.client_id) ORDER BY ANY_VALUE(sca.sc_cr_as_id)  ASC SEPARATOR ', ')  as client_id, GROUP_CONCAT(DISTINCT(sca.editable) ORDER BY ANY_VALUE(sca.sc_cr_as_id)  ASC SEPARATOR ', ')  as editable,count(distinct(du.fk_id_user)) as user_count
    //     FROM scorm_courses as c 
    //     left join assign_meta_category as am on am.fk_scourse_id = c.scourse_id
    //     left join scorm_courses_assigned as sca on sca.course_id = c.scourse_id and sca.status>0
    //     left join scorm_users_courses_assigned as suca on suca.course_id = sca.course_id and suca.status=1
    //     left join dropdown_users as du on du.fk_id_user = suca.id_user and du.fk_id_dc =1 and du.status =1 and du.fk_id_d=$client_id
    //     WHERE c.status >0 AND sca.status>0 and c.course_name LIKE '%$course_name%'  group by c.scourse_id order by c.scourse_id");
    //     $data = $query->getResultArray();
    //     // echo $this->db->getLastQuery();
    //     // // print_r($query);
    //     // exit();
    //     return  $data;
    // }
    public function coursenamessearch_view201()
    {
        $id_user = session()->get('id_user');
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        //  $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status');
        $builder->select('c.course_name as course_name,c.course_code,c.type,c.duration as duration,c.scourse_id as scourse_id,c.demo as demo,c.language,u.name as createdby,c.createdon,MAX(su.lesson_status) as lesson_status, MAX(sca.last_updated_on) as last_updated_on,c.promo_video as promo_video,c.thumbnail,MAX(sca.course_status) as course_status');
        $builder->join('scorm_courses as c', 'c.scourse_id = sca.course_id', 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id = sca.user_assign_id and su.status =1', 'left');
        $builder->join('users as u', 'u.id_user = c.createdby', 'left');
        //$builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id and am.status =1', 'left');
        //  $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->where('sca.id_user', $id_user);
        $builder->where('sca.status', 1);
        $builder->where('c.status', '1');
        $builder->where('sca.type', 0);
        //  $builder->where('sca.mp_id', 0);
        // $builder->groupBy('sca.user_assign_id');
        $builder->groupBy('sca.id_user');
        $builder->groupBy('sca.course_id');
        $builder->orderBy('last_updated_on', 'ASC');
        // $builder->limit(20);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    public function coursenamessearch_view20()
    {
        $id_user = session()->get('id_user');
        $client = session()->get('client');
        $builder = $this->db->table('scorm_courses_assigned as sc');
        $builder->select('c.course_name as course_name,c.course_code,c.duration as duration,c.scourse_id as scourse_id,c.demo as demo,c.language,u.name as createdby,c.createdon,MAX(su.lesson_status) as lesson_status, MAX(sca.last_updated_on) as last_updated_on,c.promo_video as promo_video,c.thumbnail,c.type,MAX(sca.course_status) as course_status');
        $builder->join('scorm_users_courses_assigned as sca', 'sca.course_id = sc.course_id and  sca.id_user =' . $id_user . ' and sca.status =1 and sca.type = 0', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = sc.course_id', 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id = sca.user_assign_id and su.status =1', 'left');
        $builder->join('users as u', 'u.id_user = c.createdby', 'left');
        //   $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id and am.status =1', 'left');
        //  $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->where('sc.client_id', $client);
        $builder->where('sc.status', 1);
        $builder->where('c.status', '1');

        // $builder->where('sca.type', 0);
        $builder->groupBy('sc.course_id');
        // $builder->orderBy('sca.last_updated_on', 'ASC');
        //  $builder->limit(20);

        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }

    public function isFavorite($crid, $user)
    {
        $builder = $this->db->table('scorm_favorite_courses');
        $builder->select('fav_id');
        $builder->where('scourse_id', $crid);
        $builder->where('user_id', $user);
        $favorite = $builder->get()->getResultArray();
        return $favorite;
    }

    public function addFavorite($data)
    {
        $builder = $this->db->table('scorm_favorite_courses');
        $builder->insert($data);
        return;
    }   

    public function getFavbyID($fav_id)
    {
        $builder = $this->db->table('scorm_favorite_courses');
        $builder->select('*');
        $builder->where('fav_id', $fav_id);
        $favorite = $builder->get()->getResultArray();
        return $favorite;
    }

    public function deleteFavorite($fav_id, $user_id)
    {
        $builder = $this->db->table('scorm_favorite_courses');
        $builder->where('fav_id', $fav_id);
        $builder->where('user_id', $user_id);
        $builder->delete();
        return;
    }

    public function coursenamessearchbyform_view($header, $course_name)
    {
        $client = session()->get('client');
        $id_user = session()->get('id_user');
        $builder = $this->db->table('scorm_courses_assigned as sc');
        $builder->select('c.course_name as course_name,c.course_code,c.duration as duration,c.scourse_id as scourse_id,c.demo as demo, MAX(su.lesson_status) as lesson_status');
        $builder->join('scorm_users_courses_assigned as sca', 'sca.course_id = sc.course_id and sca.id_user= ' . $id_user . ' and sca.status =1', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = sc.course_id', 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id = sca.user_assign_id and su.status =1', 'left');
        // $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id and am.status =1', 'left');
        // $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->like('c.' . $header, '%' . $course_name . '%');

        $builder->where('sc.client_id', $client);
        $builder->where('sc.status', 1);
        $builder->where('c.status', '1');
        $builder->groupBy('sc.course_id');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    public function coursenamessearch_view($header, $course_name)
    {
        $id_user = session()->get('id_user');
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('c.course_name as course_name,c.course_code,c.duration as duration,c.scourse_id as scourse_id,c.demo as demo, MAX(su.lesson_status) as lesson_status, MAX(sca.last_updated_on) as last_updated_on');
        $builder->join('scorm_courses as c', 'c.scourse_id = sca.course_id ', 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id = sca.user_assign_id and su.status =1', 'left');
        // $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        // $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->like('c.' . $header, '%' . $course_name . '%');
        $builder->where('sca.id_user', $id_user);
        $builder->where('sca.status', 1);
        $builder->where('c.status', '1');
        // $builder->groupBy('sca.user_assign_id');
        // $builder->orderBy('su.last_active', 'DESC');
        // $builder->limit(4);
        $builder->groupBy('sca.id_user');
        $builder->groupBy('sca.course_id');
        $builder->orderBy('last_updated_on', 'ASC');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getlastQuery();
        // exit();
        return $data;
    }
    public function courseCategoryssearch_view($category)
    {
        $id_user = session()->get('id_user');
        $builder = $this->db->table('scorm_courses c ');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status');
        $builder->join('scorm_users_courses_assigned as sca', 'sca.course_id = c.scourse_id and sca.id_user = ' . $id_user, 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id = sca.user_assign_id', 'left');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id and am.status=1', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->where('am.fk_sc_mcid', $category);
        $builder->where('c.status', '1');
        $builder->groupBy(' c.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function courseStatussearchbyform_view($status)
    {
        if ($status == 0) {
            $lesson_status = ['Not Started', 'not started'];
        } elseif ($status == 1) {
            $lesson_status = ['Incomplete', 'incomplete'];
        } elseif ($status == 2) {
            $lesson_status = ['completed', 'Completed', 'passed'];
        }

        $client = session()->get('client');
        $builder = $this->db->table('scorm_courses_assigned as sc');
        $builder->select('c.course_name as course_name,c.course_code,c.duration as duration,c.scourse_id as scourse_id,c.demo as demo, MAX(su.lesson_status) as lesson_status');
        $builder->join('scorm_users_courses_assigned as sca', 'sca.course_id = sc.course_id and sca.status =1', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = sc.course_id', 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id = sca.user_assign_id and su.status =1', 'left');
        // $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id and am.status =1', 'left');
        // $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->where('sc.client_id', $client);
        $builder->where('sc.status', 1);
        $builder->where('c.status', '1');
        $builder->groupBy('sc.course_id');

        // Use the HAVING clause for filtering the aggregate
        $builder->having('MAX(su.lesson_status) IN (' . implode(',', array_map(function ($status) {
            return "'$status'";
        }, $lesson_status)) . ')');

        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }

    public function courseStatussearch_view($status)
    {
        $id_user = session()->get('id_user');
        if ($status == 0) {
            $lesson_status = ['Not Started', 'not started'];
        } elseif ($status == 1) {
            $lesson_status = ['Incomplete', 'incomplete'];
        } elseif ($status == 2) {
            $lesson_status = ['completed', 'Completed', 'passed'];
        }

        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('c.course_name as course_name,c.course_code,c.duration as duration,c.scourse_id as scourse_id,c.demo as demo, MAX(su.lesson_status) as lesson_status, MAX(sca.last_updated_on) as last_updated_on');
        $builder->join('scorm_courses as c', 'c.scourse_id = sca.course_id', 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id = sca.user_assign_id', 'left');
        // $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        // $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->having('MAX(su.lesson_status) IN (' . implode(',', array_map(function ($status) {
            return "'$status'";
        }, $lesson_status)) . ')');

        $builder->where('sca.id_user', $id_user);
        $builder->where('sca.status', 1);
        $builder->where('c.status', '1');
        // $builder->groupBy('sca.user_assign_id');
        // $builder->orderBy('su.last_active', 'DESC');
        // $builder->limit(4);
        $builder->groupBy('sca.id_user');
        $builder->groupBy('sca.course_id');
        $builder->orderBy('last_updated_on', 'ASC');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    // public function getCoursesDetails($type)
    // {
    //     $db = \Config\Database::connect();
    //     $client_id = session()->get('client');
    //     $query = $db->query("SELECT c.course_name as course_name, c.scourse_id as scourse_id,  c.promo_video as promo_video, c.course_code as course_code, c.duration as duration,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ', ')  as metadata,GROUP_CONCAT(DISTINCT(sm1.description) ORDER BY sm1.description ASC SEPARATOR ', ')  as category,c.thumbnail,c.upload,c.pdf_filename,sca.*,c.createdby as addby,c.upload_type,c.mode,
    //     GROUP_CONCAT(DISTINCT(sca.client_id) ORDER BY ANY_VALUE(sca.sc_cr_as_id)  ASC SEPARATOR ', ')  as client_id, GROUP_CONCAT(DISTINCT(sca.editable) ORDER BY ANY_VALUE(sca.sc_cr_as_id)  ASC SEPARATOR ', ')  as editable,count(distinct(du.`fk_id_user`)) as user_count
    //     FROM scorm_courses as c 
    //     left join assign_meta_category as am on am.fk_scourse_id = c.scourse_id
    //     left join scorm_meta_category as sm on sm.sc_mcid = am.fk_sc_mcid and am.typeofval =1 and am.status>0
    //     left join scorm_meta_category as sm1 on sm1.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and am.status>0
    //     left join scorm_courses_assigned as sca on sca.course_id = c.scourse_id and sca.status>0
    //     left join scorm_users_courses_assigned as suca on suca.course_id = sca.course_id and suca.status=1
    //     left join dropdown_users as du on du.fk_id_user = suca.id_user and du.fk_id_dc =1 and du.status =1 and du.fk_id_d=$client_id
    //     WHERE c.status >0 AND c.type =$type group by c.scourse_id order by c.scourse_id");
    //     $data = $query->getResultArray();
    //     return  $data;
    // }
    // public function getCourselist($type)
    // {
    //     $db = \Config\Database::connect();
    //     $query = $db->query("SELECT c.course_name as course_name, c.scourse_id as scourse_id,  c.promo_video as promo_video, c.course_code as course_code, c.duration as duration,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ', ')  as metadata,GROUP_CONCAT(DISTINCT(sm1.description) ORDER BY sm1.description ASC SEPARATOR ', ')  as category,c.thumbnail,c.upload,c.pdf_filename,sca.*,c.createdby as addby,
    //     GROUP_CONCAT(DISTINCT(sca.client_id) ORDER BY ANY_VALUE(sca.sc_cr_as_id)  ASC SEPARATOR ', ')  as client_id, GROUP_CONCAT(DISTINCT(sca.editable) ORDER BY ANY_VALUE(sca.sc_cr_as_id)  ASC SEPARATOR ', ')  as editable
    //     FROM scorm_courses as c 
    //     left join assign_meta_category as am on am.fk_scourse_id = c.scourse_id
    //     left join scorm_meta_category as sm on sm.sc_mcid = am.fk_sc_mcid and am.typeofval =1 and am.status>0
    //     left join scorm_meta_category as sm1 on sm1.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and am.status>0
    //     left join scorm_courses_assigned as sca on sca.course_id = c.scourse_id and sca.status>0
    //     WHERE c.status >0 AND c.type =$type group by c.scourse_id order by c.scourse_id");
    //     $data = $query->getResultArray();
    //     return  $data;
    // }
    public function getassignedCourselist($type)
    {
        $client = session()->get('client');
        $db = \Config\Database::connect();
        $query = $db->query("SELECT c.course_name as course_name, c.scourse_id as scourse_id,  c.promo_video as promo_video, c.course_code as course_code, c.duration as duration,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ', ')  as metadata,GROUP_CONCAT(DISTINCT(sm1.description) ORDER BY sm1.description ASC SEPARATOR ', ')  as category,c.thumbnail,c.upload,c.pdf_filename,sca.*,c.createdby as addby,
        GROUP_CONCAT(DISTINCT(sca.client_id) ORDER BY (sca.sc_cr_as_id)  ASC SEPARATOR ', ')  as client_id, sca.editable as editable
        FROM  scorm_courses_assigned as sca 
        left join assign_meta_category as am on am.fk_scourse_id = sca.course_id
        left join scorm_meta_category as sm on sm.sc_mcid = am.fk_sc_mcid and am.typeofval =1 and am.status>0
        left join scorm_meta_category as sm1 on sm1.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and am.status>0
        left join scorm_courses as c on c.scourse_id = sca.course_id and c.status>0
        WHERE sca.status >0 AND c.type =$type and sca.client_id=$client group by c.scourse_id order by sca.course_id");
        $data = $query->getResultArray();
        // print_r($data);
        return $data;
    }
    public function deleteDropdown($newdata, $xidv)
    {
        $builder = $this->db->table('xapi_input_dropdown_values as c');
        $builder->where('c.xidv', $xidv);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function delVariable($newdata, $xiv)
    {
        $builder = $this->db->table('xapi_input_variables as c');
        $builder->where('c.xiv', $xiv);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function updateOuptutVariable($newdata, $xov)
    {
        $builder = $this->db->table('xapi_output_variables as c');
        $builder->where('c.xov', $xov);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function deltecourse($newdata, $scourse_id)
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->where('c.scourse_id', $scourse_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function deltecoursegroup($newdata, $assign_id)
    {
        $builder = $this->db->table('scorm_course_group_assigned');
        $builder->where('assign_id', $assign_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getAllMetadata($type)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_meta_category as sc');
        $builder->select('sc.*');
        $builder->where('sc.typeofval', $type);
        $builder->where('sc.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function showreport()
    {
        $q2 = $this->db->query("SELECT * FROM mycart where expirystatus=1 ORDER BY cartod desc limit 30");
        $result2 = $q2->getResultArray();
        $num_rows2 = count($result2);
        if (!$result2 || ($num_rows2 < 0)) {
            echo "Error displaying info";
            return;
        }
        if ($num_rows2 == 0) {
            echo "No Reports available to view.";
            return;
        } else {
            return $result2;
        }
    }
    public function assignmetacategorydata($newdata)
    {
        $db = \Config\Database::connect();

        foreach ($newdata['fk_sc_mcid'] as $fk_sc_mcid) {
            $assign_meta_category = [
                'fk_sc_mcid' => $fk_sc_mcid,
                'fk_scourse_id' => $newdata['fk_scourse_id'],
                'typeofval' => $newdata['typeofval'],
                'status' => $newdata['status'],
                'createdby' => $newdata['createdby'],
                'createdon' => time(),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $builder = $this->db->table('assign_meta_category');
            $builder->insert($assign_meta_category);
            $data = $builder->get()->getResultArray();
            if (!empty($data)) {
                $data['status'] = "OK";
            } else {
                $data['status'] = "Error";
            }
        }
        return $data;
    }
    public function getAssignmetadata($scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('assign_meta_category as am');
        $builder->select('am.*,sc.description');
        // $builder->join('scorm_courses as sc','sc.scourse_id = am.fk_scourse_id','left');
        $builder->join('scorm_meta_category as sc', 'sc.sc_mcid  = am.fk_sc_mcid', 'left');
        $builder->where('am.fk_scourse_id', $scourse_id);
        $builder->where('am.typeofval', '1');
        $builder->where('am.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAssignAssessment($scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('assign_meta_category as am');
        $builder->select('am.*,sc.course_name as description');
        // $builder->join('scorm_courses as sc','sc.scourse_id = am.fk_scourse_id','left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id  = am.fk_sc_mcid', 'left');
        $builder->where('am.fk_scourse_id', $scourse_id);
        $builder->where('am.typeofval', '3');
        $builder->where('am.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAssigncategorydata($scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('assign_meta_category as am');
        $builder->select('am.*,sc.description');
        // $builder->join('scorm_courses as sc','sc.scourse_id = am.fk_scourse_id','left');
        $builder->join('scorm_meta_category as sc', 'sc.sc_mcid = am.fk_sc_mcid', 'left');
        $builder->where('am.fk_scourse_id', $scourse_id);
        //$builder->where('am.typeofval', '2');
        $builder->where('am.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function deleteassignmeta($newdata, $mc_id)
    {
        $builder = $this->db->table('assign_meta_category');
        $builder->where('mc_id', $mc_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getCourseData()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*');
        $builder->where('c.status !=', '0');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAssessmentReport()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('scorm_course_group_assigned as sg');

        $builder->select("
    sc.course_name as question,
    COUNT(DISTINCT  aq.q_id) AS No_of_questions,
    MAX(CASE WHEN ase.type = 24 THEN ase.value END) AS Attempt_allowed,
    MAX(CASE WHEN ase.type = 23 THEN ase.value END) AS passing_per,
    MAX(CASE WHEN ase.type = 22 THEN ase.value END) AS max_question,
    MAX(CASE WHEN ase.type = 21 THEN ase.value END) AS Duration,
    MAX(CASE WHEN (ase.type = 4 AND ase.value = 'Enabled') THEN 'Post Attempt' ELSE 'Pre Attempt' END) AS PreAttempt_PostAttempt,
    MAX(CASE WHEN (ase.type = 3 AND ase.value = 'Enabled') THEN 'Post Test' ELSE 'Pre Test' END) AS PreTest_PostTest,
    MAX(CASE WHEN (ase.type = 2) THEN  ase.value END) AS Randomize_Options,
    MAX(CASE WHEN (ase.type = 1) THEN  ase.value END) AS Randomize_Questions,
    MAX(CASE WHEN (ase.type = 62 AND ase.value = 1) THEN 'Enabled' ELSE 'Disabled' END) AS free_navigation,
    MAX(CASE WHEN (ase.type = 63 AND ase.value = 0) THEN 'Disabled' ELSE 'Enabled' END) AS page_level_course_completion,
    MAX(CASE WHEN (ase.type = 74 AND ase.value = 1) THEN 'Enabled' ELSE 'Disabled' END) AS Certificate_Enabled
");

        $builder->join('scorm_courses as sc', 'sc.scourse_id = sg.course_id', 'left');
        $builder->join('page as p', 'p.fk_course_id = sc.scourse_id AND p.status = 1', 'left');
        $builder->join('assessment_questions as aq', 'aq.page_id = p.page_id', 'left');
        $builder->join('assessment_settings as ase', 'ase.page_id = p.page_id', 'left');

        $builder->where('sg.group_id', 11);
        $builder->where('sg.status', 1);
        $builder->where('sc.status', 1);
        $builder->where('aq.status', 1);

        $builder->groupBy(['p.page_id', 'p.page_name']);

        $data = $builder->get()->getResultArray();
        return $data;
    }
}
