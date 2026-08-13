<?php

namespace App\Controllers\SCORM;

use App\Controllers\BaseController;

use ZipArchive;
use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\SCORM\Scorm_dashboard_model;
use App\Models\Assessment\Assessment_training_model;
use App\Models\SCORM\Scorm_metacategory_model;
use App\Models\SCORM\Scorm_course_group_model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


#[\AllowDynamicProperties]
class Scorm_courses extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->dropdown_model = new Dropdown_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->scorm_dashboard_model = new Scorm_dashboard_model();
        $this->assessment_training_model = new Assessment_training_model();
        $this->scorm_metacategory_model = new Scorm_metacategory_model();
        $this->scorm_course_group_model = new Scorm_course_group_model();
    }

    public function index()
    {
        if ($response =  $this->requireRole(['5', '44', '3', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['header'] = 'Courses';
        $data['create_new_course_link'] = 'SCORM/scorm_courses/course_add_view';
        $data['settings_link'] = 'SCORM/scorm_courses/course_settings_view';
        $data['edit_link'] = 'SCORM/scorm_courses/course_edit_view';
        $data['assign_user_link'] = 'XAPI/XAPI_courses/courseusersassigned_report';
        $data['delete_link'] = 'SCORM/scorm_courses/deleteCoursedetails';
        $data['page_link'] = 'SCORM/Scorm_course_pages';
        // $data['categoryData'] = $this->scorm_metacategory_model->getCategoryData();
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $client = session()->get('client');
        $id_user = session()->get('id_user');

        // The table itself is now populated entirely via courses_datatable_ajax() (DataTables
        // serverSide mode), so index() only needs a cheap count to decide whether to render the
        // table shell or the "no courses" message — not the full ~1600-row fetch this used to do.
        if ((in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel))) {
            $data['hasCourses'] = $this->scorm_course_model->getCoursesAdminDatatableCount($client, $id_user) > 0;
        } else {
            $data['hasCourses'] = $this->scorm_course_model->getCoursesUserDatatableCount($id_user) > 0;
        }

        return $this->response->setBody(
            view('templates/header_view', $data) .
                view('SCORM/scorm_courses/courses_search_view', $data) .
                view('templates/footer_view')
        );
    }

    // Builds one DataTables row (array of per-column HTML strings) matching the exact markup
    // courses_search_view.php used to render server-side for every row up front.
    private function buildCourseDatatableRow(array $row, int $rank, bool $canManageCourses): array
    {
        $durationText = '';
        if (($row['duration'] ?? 0) > 0) {
            $duration = (int) $row['duration'];
            if ($duration > 60) {
                $hours = intdiv($duration, 60);
                $durationText = $hours . ' ' . lang('UI_Text.Hours') . ' ';
                $balancemin = $duration - $hours * 60;
                if ($balancemin > 0) {
                    $durationText .= $balancemin . ' ' . lang('UI_Text.Minutes');
                }
            } else {
                $durationText = $duration . ' ' . lang('UI_Text.Minutes');
            }
        }

        $typeHtml = '';
        if (isset($row['type'])) {
            if ((int) $row['type'] === 10) {
                $typeHtml = '<span class="badge bg-soft-primary text-primary rounded-pill px-2 py-1">SCORM</span>';
            } elseif ((int) $row['type'] === 11) {
                $typeHtml = '<span class="badge bg-soft-warning text-warning rounded-pill px-2 py-1">CB</span>';
            }
        }

        $modeLabels = [
            '1' => 'UI_Text.Development',
            '3' => 'UI_Text.Alpha',
            '4' => 'UI_Text.Alpha_2',
            '5' => 'UI_Text.Beta',
            '6' => 'UI_Text.Beta_2',
            '7' => 'UI_Text.Gamma',
            '8' => 'UI_Text.Gamma_2',
        ];
        $modeBadgeColors = [
            '1' => 'danger',
            '3' => 'primary',
            '4' => 'primary',
            '5' => 'blue',
            '6' => 'blue',
            '7' => 'pink',
            '8' => 'pink',
        ];
        $courseMode = $row['mode'] ?? null;
        if (isset($modeLabels[$courseMode])) {
            $color = $modeBadgeColors[$courseMode];
            $statusHtml = '<span class="badge bg-soft-' . $color . ' text-' . $color . ' rounded-pill p-1 px-2">' . lang($modeLabels[$courseMode]) . '</span>';
        } elseif (($row['course_status'] ?? null) == '2') {
            // Same color scheme as the my_training summary tiles (scorm_dashboard_view.php):
            // In Progress = warning, Not Started = danger, Completed = success.
            $statusHtml = '<span class="badge bg-soft-success text-success rounded-pill p-1 px-2">' . lang('UI_Text.Completed') . '</span>';
        } elseif (($row['course_status'] ?? null) == '1' || ($row['lesson_status'] ?? null) == 'incomplete' || ($row['lesson_status'] ?? null) == 'failed') {
            $statusHtml = '<span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2">' . lang('UI_Text.In Progress') . '</span>';
        } else {
            $statusHtml = '<span class="badge bg-soft-danger text-danger rounded-pill p-1 px-2">' . lang('UI_Text.Not Started') . '</span>';
        }

        $actionHtml = view('SCORM/scorm_courses/courses_datatable_action_cell', [
            'row' => $row,
            'canManageCourses' => $canManageCourses,
            'settings_link' => 'SCORM/scorm_courses/course_settings_view',
            'assign_user_link' => 'XAPI/XAPI_courses/courseusersassigned_report',
        ]);

        return [
            (string) $rank,
            esc($row['course_code'] ?? ''),
            esc($row['course_name'] ?? ''),
            $durationText,
            $typeHtml,
            esc($row['language'] ?? ''),
            $statusHtml,
            $actionHtml,
        ];
    }

    // DataTables serverSide endpoint backing the SCORM/scorm_courses course list: does the
    // paging/search/sort in SQL so each request only touches one page's worth of rows.
    public function courses_datatable_ajax()
    {
        if ($response = $this->requireRole(['5', '44', '3', '46'])) {
            return $response;
        }

        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $client = session()->get('client');
        $id_user = session()->get('id_user');

        $draw = (int) $this->request->getPost('draw');
        $start = max(0, (int) $this->request->getPost('start'));
        $length = (int) $this->request->getPost('length');
        $length = $length > 0 ? $length : 10;
        $searchValue = trim((string) ($this->request->getPost('search')['value'] ?? ''));

        $orderColIndex = (int) ($this->request->getPost('order')[0]['column'] ?? -1);
        $orderDir = strtolower((string) ($this->request->getPost('order')[0]['dir'] ?? 'asc')) === 'desc' ? 'DESC' : 'ASC';
        // Column 6 (Status) is a derived value (mode + course_status + lesson_status), so it's
        // ordered via the CASE expression in Scorm_course_model::courseStatusSortExpr() rather
        // than a plain column - see getCoursesAdminDatatable()/getCoursesUserDatatable().
        $columns = ['', 'course_code', 'course_name', 'duration', 'type', 'language', 'status', ''];
        $orderColumn = $columns[$orderColIndex] ?? '';

        $isAdmin = in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel);
        $canManageCourses = $isAdmin || in_array('46', $arrayuserlevel);

        if ($isAdmin) {
            $recordsTotal = $this->scorm_course_model->getCoursesAdminDatatableCount($client, $id_user);
            $recordsFiltered = $searchValue !== '' ? $this->scorm_course_model->getCoursesAdminDatatableCount($client, $id_user, $searchValue) : $recordsTotal;
            $rows = $this->scorm_course_model->getCoursesAdminDatatable($client, $id_user, $searchValue, $orderColumn, $orderDir, $length, $start);
        } else {
            $recordsTotal = $this->scorm_course_model->getCoursesUserDatatableCount($id_user);
            $recordsFiltered = $searchValue !== '' ? $this->scorm_course_model->getCoursesUserDatatableCount($id_user, $searchValue) : $recordsTotal;
            $rows = $this->scorm_course_model->getCoursesUserDatatable($id_user, $searchValue, $orderColumn, $orderDir, $length, $start);
        }

        $data = [];
        foreach ($rows as $i => $row) {
            $data[] = $this->buildCourseDatatableRow($row, $start + $i + 1, $canManageCourses);
        }

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }



    public function searchBycourseName()
    {
        if ($response =  $this->requireRole(['5', '44', '3', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['header'] = 'Courses';
        $data['create_new_course_link'] = 'SCORM/scorm_courses/course_add_view';
        $data['settings_link'] = 'SCORM/scorm_courses/course_settings_view';
        $data['edit_link'] = 'SCORM/scorm_courses/course_edit_view';
        $data['delete_link'] = 'SCORM/scorm_courses/deleteCoursedetails';
        $data['page_link'] = 'SCORM/Scorm_course_pages';
        $data['typeval'] = 2;
        $course_name = $this->request->getPost('course_name');
        if (!empty($course_name) && $course_name != ' ') {
            $course_name = trim($course_name);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0024'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
            $coursesDetails = $this->scorm_course_model->coursenamessearchbyform_view('course_name', $course_name);
        } else {
            $coursesDetails = $this->scorm_course_model->coursenamessearch_view('course_name', $course_name);
        }

        $data['categoryData'] = $this->scorm_metacategory_model->getCategoryData();
        if (!empty($coursesDetails)) {
            $data['coursesDetails'] = $coursesDetails;
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0024'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_search_view', $data);
        echo view('templates/footer_view');
    }
    public function searchByCode()
    {
        if ($response =  $this->requireRole(['5', '44', '3', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['header'] = 'Courses';
        $data['create_new_course_link'] = 'SCORM/scorm_courses/course_add_view';
        $data['settings_link'] = 'SCORM/scorm_courses/course_settings_view';
        $data['edit_link'] = 'SCORM/scorm_courses/course_edit_view';
        $data['delete_link'] = 'SCORM/scorm_courses/deleteCoursedetails';
        $data['page_link'] = 'SCORM/Scorm_course_pages';
        $data['typeval'] = 2;
        $code = $this->request->getPost('code');
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
            $coursesDetails = $this->scorm_course_model->coursenamessearchbyform_view('course_code', $code);
        } else {
            $coursesDetails = $this->scorm_course_model->coursenamessearch_view('course_code', $code);
        }

        $data['categoryData'] = $this->scorm_metacategory_model->getCategoryData();
        if (!empty($coursesDetails)) {
            $data['coursesDetails'] = $coursesDetails;
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0024'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_search_view', $data);
        echo view('templates/footer_view');
    }
    public function searchBycourseCategory() //fetch data from projects and project_details table to display
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['header'] = 'Courses';
        $data['create_new_course_link'] = 'SCORM/scorm_courses/course_add_view';
        $data['settings_link'] = 'SCORM/scorm_courses/course_settings_view';
        $data['edit_link'] = 'SCORM/scorm_courses/course_edit_view';
        $data['delete_link'] = 'SCORM/scorm_courses/deleteCoursedetails';
        $data['page_link'] = 'SCORM/Scorm_course_pages';
        $data['typeval'] = 2;
        $category = $this->request->getPost('category');
        // print_r($category);
        // exit();
        $data['categoryData'] = $this->scorm_metacategory_model->getCategoryData();
        $coursesDetails = $this->scorm_course_model->courseCategoryssearch_view($category);
        if (!empty($coursesDetails)) {
            $data['coursesDetails'] = $coursesDetails;
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0024'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_search_view', $data);
        echo view('templates/footer_view');
    }
    public function searchCourseByStatus()
    {
        if ($response =  $this->requireRole(['5', '44', '3', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['header'] = 'Courses';
        $data['create_new_course_link'] = 'SCORM/scorm_courses/course_add_view';
        $data['settings_link'] = 'SCORM/scorm_courses/course_settings_view';
        $data['edit_link'] = 'SCORM/scorm_courses/course_edit_view';
        $data['delete_link'] = 'SCORM/scorm_courses/deleteCoursedetails';
        $data['page_link'] = 'SCORM/Scorm_course_pages';
        $data['typeval'] = 2;
        $status = $this->request->getPost('status');
        // print_r($category);
        // exit();
        $data['categoryData'] = $this->scorm_metacategory_model->getCategoryData();
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
            $coursesDetails = $this->scorm_course_model->courseStatussearchbyform_view($status);
        } else {
            $coursesDetails = $this->scorm_course_model->courseStatussearch_view($status);
        }
        if (!empty($coursesDetails)) {
            $data['coursesDetails'] = $coursesDetails;
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0024'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_search_view', $data);
        echo view('templates/footer_view');
    }
    public function add_new_course_coursegroup()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] = $data['sc_cgid'];
        } else if (isset($_GET['sc_cgid'])) {
            $data['sc_cgid'] = $_GET['sc_cgid'];
        } else if (isset($_SESSION['sc_cgid'])) {
            $data['sc_cgid'] = $_SESSION['sc_cgid'];
        } else {
            return redirect()->to(base_url() . '/SCORM/Scorm_course_pages');
        }
        $data['header'] = 'Course Group Details';
        $data['header_link'] = 'my_training/course_group_list';
        $data['sub_header_1'] = 'Create New Course';
        $data['form_link'] = 'SCORM/scorm_courses/add_new_course_data';
        $data['returnUrl'] = 1;
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/create_new_course', $data);
        echo view('templates/footer_view');
    }
    public function add_new_course()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (in_array('5', $arrayuserlevel)  || in_array('44', $arrayuserlevel)) {
            helper(['form']);
            $data['header'] = 'Courses';
            $data['header_link'] = 'SCORM/scorm_courses';
            $data['sub_header_1'] = 'Create New Course';
            $data['form_link'] = 'SCORM/scorm_courses/add_new_course_data';
            $data['returnUrl'] = 2;
            echo view('templates/header_view', $data);
            echo view('SCORM/scorm_courses/create_new_course', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    public function add_new_course_data()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $user = session()->get('username');
        $client_id = session()->get('client');
        if ($this->request->getPost()) {
            $rules = [
                'course_name' => 'required',
            ];
            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $type = $this->request->getVar('type');
                if ($type == '11') {
                    $mode = 1;
                } else {
                    $mode = 0;
                }
                $newdata = [
                    'client_id' => $client_id,
                    'course_name' => $this->request->getVar('course_name'),
                    'duration' => $this->request->getVar('duration'),
                    'language' => $this->request->getVar('language'),
                    'project_id' => 0,
                    'type' => $this->request->getVar('type'),
                    'mode' => $mode,
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->scorm_course_model->addcoursedetails($newdata);

                if (!empty($result) && isset($_POST['sc_cgid'])) {
                    // print_r($result['scourse_id']);
                    // print_r($_SESSION['sc_cgid']);
                    // exit();
                    $newData = [
                        'course_id' => $result['course_id'],
                        'group_id' => $_POST['sc_cgid'],
                        'status' => 1,
                        'createdby' => session()->get('id_user'),
                        'createdon' => time(),
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    $this->scorm_course_group_model->add_trainnercourse_to_gr($newData);
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                    if ($_POST['returnUrl'] == 1) {
                        return redirect()->to(base_url() . 'my_training/course_group_list');
                    } else {
                        return redirect()->to(base_url() . 'SCORM/scorm_courses');
                    }
                }
            }
        }
    }


    public function course_add_view()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['header'] = 'Projects';
        $data['header_link'] = 'Project_Manage/PM_projects';
        $data['sub_header_1'] = 'Create New Course';
        $data['form_link'] = 'SCORM/scorm_courses/addcourse';

        $data['typeval'] = 2;
        if (isset($_POST['projectid'])) {
            $data['projectid'] = $_POST['projectid'];
            $_SESSION['projectid'] = $data['projectid'];
        } else if (isset($_SESSION['projectid'])) {
            $data['projectid'] = $_SESSION['projectid'];
        } else {
            session()->setFlashdata('message', lang('Alert.Aler_018'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_projects');
        }
        $data['project_data'] = $this->scorm_course_model->get_project_data($data['projectid']);
        $data['coursesDetails'] = $this->scorm_course_model->get_non_assigned_courses();
        $data['courses_by_project'] = $this->scorm_course_model->get_courses_assigned_to_project($data['projectid']);

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_add_view', $data);
        echo view('templates/footer_view');
    }


    public function link_course_project()
    {

        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $course_id = $this->request->getVar('course_id');
        $client = $this->request->getVar('client');
        $newdata = [
            'project_id' => $this->request->getVar('project_id'),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->update_link_course_project($newdata, $course_id, $client);
        session()->setFlashdata('success', lang('Messages.Success_0039'));
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_add_view');
    }


    public function addcourse()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $user = session()->get('username');

        if ($this->request->getPost()) {
            //print_r("sss");
            $rules = [
                'course_name' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'course_name' => $this->request->getVar('course_name'),
                    'duration' => $this->request->getVar('duration'),
                    'language' => $this->request->getVar('language'),
                    'project_id' => $this->request->getVar('project_id'),
                    'type' => $this->request->getVar('type'),
                    'mode' => 1,
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->scorm_course_model->addcoursedetails($newdata);
                $enabled_default = array(1, 2, 3);
                //print_r();
                if ($result) {
                    for ($i = 0; $i < count($enabled_default); $i++) {
                        $tempdata = array(
                            'scourse_id' => $result['course_id'],
                            'type' => $enabled_default[$i],
                            'value' => 'Enabled',
                            'status' => 1,
                            'last_updated_by' => session()->get('id_user'),
                            'last_updated_on' => time(),
                        );
                        $this->assessment_training_model->add_settings($tempdata);
                    }
                    $value_default = array(21, 22, 23, 24);
                    $value_default_input = array('', '', 80, 2);
                    for ($i = 0; $i < count($value_default); $i++) {
                        $tempdata = array(
                            'scourse_id' => $result['course_id'],
                            'type' => $value_default[$i],
                            'value' => $value_default_input[$i],
                            'status' => 1,
                            'last_updated_by' => session()->get('id_user'),
                            'last_updated_on' => time(),
                        );
                        $this->assessment_training_model->add_settings($tempdata);
                    }
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_add_view');
        }
    }
    public function course_edit_view()
    {
        $data = [];
        helper(['form']);
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        $getCourseData = $this->scorm_course_model->getCourseDetails($data['scourse_id']);
        $data['row'] = $getCourseData[0];
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['header_1'] = 'Course Detail';
        $data['header_link_1'] = 'my_training/read_more';
        $data['sub_header_1'] = 'SCORM Upload';
        $data['form_link'] = 'SCORM/scorm_courses/editcourse';
        $data['course_name'] = $getCourseData[0]['course_name'];
        $data['typeval'] = 2;
        $data['form_url_2'] = 'SCORM/scorm_courses/scorm_upload';

        $data['allmetadata'] = $this->scorm_course_model->getAllMetadata(1);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(2);
        $data['getAssignmetadata'] = $this->scorm_course_model->getAssignmetadata($data['scourse_id']);
        $data['getAssigncategorydata'] = $this->scorm_course_model->getAssigncategorydata($data['scourse_id']);
        $data['getAllFileOwner'] = $this->scorm_course_model->getAllFileOwner($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_edit_view', $data);
        echo view('templates/footer_view');
    }


    public function editcourse()
    {
        $data = [];
        helper(['form']);
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        $user = session()->get('username');
        $scourse_id = $this->request->getVar('scourse_id');
        $data['scourse_id'] = $scourse_id;
        if ($this->request->getPost()) {
            $rules = [
                'course_name' => 'required',
                // 'description' => 'required',
            ];
            if (!$this->validate($rules)) {

                $data['courseeditvalidation'] = $this->validator;
            } else {
                $newdata = [
                    'language' => $this->request->getVar('language'),
                    'course_code' => $this->request->getVar('course_code'),
                    'scourse_id' => $this->request->getVar('scourse_id'),
                    'course_name' => $this->request->getVar('course_name'),
                    'description' => $this->request->getVar('description'),
                    'theme' => $this->request->getVar('theme'),
                    'demo' => $this->request->getVar('demo'),
                    'objectives' => $this->request->getVar('objectives'),
                    'duration' => $this->request->getVar('duration'),
                    'status' => $this->request->getVar('status'),
                    // 'launch_link' => $this->request->getVar('launch_link'),
                    'mode' => $this->request->getVar('mode'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                // Only touch the type column when a real value was submitted - a blank/missing
                // "type" (e.g. a stale hidden field) would otherwise get written as-is and land
                // as 0 in this numeric column, silently clobbering whatever type the course was
                // created with.
                $type = $this->request->getVar('type');
                if ($type !== null && $type !== '') {
                    $newdata['type'] = $type;
                }
                $result = $this->scorm_course_model->editcoursedetails($newdata, $scourse_id);
                $enabled_default = array(3);
                // print_r($result);
                if ($result) {
                    // for ($i = 0; $i < count($enabled_default); $i++) {
                    //     $tempdata = array(
                    //         'scourse_id' => $this->request->getVar('scourse_id'),
                    //         'type' => $enabled_default[$i],
                    //         'value' => 'Enabled',
                    //         'createdby' => session()->get('id_user'),
                    //         'createdon' => time(),
                    //         'status' => 1,
                    //         'last_updated_by' =>  session()->get('id_user'),
                    //         'last_updated_on' => time(),
                    //     );
                    //     $this->assessment_training_model->add_settings($tempdata);
                    // }
                    // $value_default = array(23, 24);
                    // $value_default_input = array('', '', 80, 2);
                    // for ($i = 0; $i < count($value_default); $i++) {
                    //     $tempdata = array(
                    //         'scourse_id' => $this->request->getVar('scourse_id'),
                    //         'type' => $value_default[$i],
                    //         'value' => $value_default_input[$i],
                    //         'createdby' => session()->get('id_user'),
                    //         'createdon' => time(),
                    //         'status' => 1,
                    //         'last_updated_by' =>  session()->get('id_user'),
                    //         'last_updated_on' => time(),
                    //     );
                    //     $this->assessment_training_model->add_settings($tempdata);
                    // }
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            if ($this->request->getVar('status') == 1) {
                return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
            } else {
                return redirect()->to(base_url() . 'SCORM/scorm_courses');
            }
        }
    }

    public function course_settings_view()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $data['form_link'] = 'SCORM/scorm_courses/editcourse';
        $data['header'] = 'Courses';
        $data['header_link'] = 'my_training/read_more';
        $data['sub_header_1'] = $data['course_name'];

        // "Courses" breadcrumb link: points back to wherever the user actually came from
        // (My Courses, Marketplace, Learning Plan, Demos, ...) - see BaseController::coursesBreadcrumbLink().
        $coursesBreadcrumb = $this->coursesBreadcrumbLink();
        $data['courses_link'] = $coursesBreadcrumb['link'];
        $data['courses_link_label'] = $coursesBreadcrumb['label'];

        $data['typeval'] = 2;
        $data['form_url_1'] = 'SCORM/scorm_courses/thumbnail_upload';
        $data['form_objective'] = 'SCORM/scorm_courses/add_objectives';
        $data['form_url_2'] = 'SCORM/scorm_courses/scorm_upload';
        $data['form_url_3'] = 'SCORM/scorm_courses/uploadpromovideo';
        $data['form_url_4'] = 'SCORM/scorm_courses/assignmetacategory';
        $data['form_url_5'] = 'SCORM/scorm_courses/deleteasignmetadetails';
        $data['form_url_6'] = 'SCORM/scorm_courses/uploadpdf';
        $data['delete_form'] = 'SCORM/scorm_courses/delete_form';
        $data['edit_form'] = 'SCORM/scorm_courses/edit_objective';


        $getCourseData = $this->scorm_course_model->getCourseDetails($data['scourse_id']);

        $data['row'] = $getCourseData[0];
        $data['allmetadata'] = $this->scorm_course_model->getAllMetadata(1);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(2);
        $data['getAssignmetadata'] = $this->scorm_course_model->getAssignmetadata($data['scourse_id']);
        $data['getAssigncategorydata'] = $this->scorm_course_model->getAssigncategorydata($data['scourse_id']);
        $data['getAllFileOwner'] = $this->scorm_course_model->getAllFileOwner($data['scourse_id']);
        $data['getAllObjectives'] = $this->scorm_course_model->getAllObjectives($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_settings_view', $data);
        echo view('templates/footer_view');
    }
    public function update_objective()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else {
            $_SESSION['tab'] = 2;
        }

        $objective = $_POST['objective'];
        $sco_id = $_POST['sco_id'];

        $data = [
            'objective' => $objective,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '1',
        ];
        $this->scorm_course_model->del_objectives($data, $sco_id);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }

    public function delete_form()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else {
            $_SESSION['tab'] = 2;
        }

        $sco_id = $_POST['sco_id'];

        $data = [
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $this->scorm_course_model->del_objectives($data, $sco_id);
        session()->setFlashdata('danger', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }

    public function add_objectives()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else {
            $_SESSION['tab'] = 2;
        }

        $objective = $_POST['objective'];
        $scourse_id = $_POST['scourse_id'];

        $newdata = [
            'course_id' => $scourse_id,
            'objective' => $objective,
            'created_by' => session()->get('id_user'),
            'created_on' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '1',
        ];
        $this->scorm_course_model->add_objectives($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }
    public function add_category()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $user = session()->get('username');
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['course_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['course_name'] = $_GET['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        $data['form_link'] = 'SCORM/scorm_courses/editcourse';
        $data['header'] = 'Courses';
        $data['header_link'] = 'my_training/read_more';
        $data['sub_header_1'] = $data['course_name'];

        // "Courses" breadcrumb link: points back to wherever the user actually came from
        // (My Courses, Marketplace, Learning Plan, Demos, ...) - see BaseController::coursesBreadcrumbLink().
        $coursesBreadcrumb = $this->coursesBreadcrumbLink();
        $data['courses_link'] = $coursesBreadcrumb['link'];
        $data['courses_link_label'] = $coursesBreadcrumb['label'];

        $data['typeval'] = 2;
        $data['form_url_1'] = 'SCORM/scorm_courses/thumbnail_upload';
        $data['form_url_2'] = 'SCORM/scorm_courses/scorm_upload';
        $data['form_url_3'] = 'SCORM/scorm_courses/uploadpromovideo';
        $data['form_url_4'] = 'SCORM/scorm_courses/assignmetacategory';
        $data['form_url_5'] = 'SCORM/scorm_courses/deleteasignmetadetails';
        $data['form_url_6'] = 'SCORM/scorm_courses/uploadpdf';

        $getCourseData = $this->scorm_course_model->getCourseDetails($data['scourse_id']);
        $data['row'] = $getCourseData[0];
        $data['allmetadata'] = $this->scorm_course_model->getAllMetadata(1);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(2);
        $data['getAssignmetadata'] = $this->scorm_course_model->getAssignmetadata($data['scourse_id']);
        $data['getAssigncategorydata'] = $this->scorm_course_model->getAssigncategorydata($data['scourse_id']);
        // $data['getAssessmentSettings'] = $this->assessment_training_model->get_question_settings($data['scourse_id']);
        $data['getAllFileOwner'] = $this->scorm_course_model->getAllFileOwner($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/add_category_view', $data);
        echo view('templates/footer_view');
    }

    public function activate()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $scourse_id = $this->request->getVar('scourse_id');
        $newdata = [
            'upload' => $this->request->getVar('filename'),
        ];
        $result = $this->scorm_course_model->editcoursedetails($newdata, $scourse_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
    }

    public function del_folder()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['folderloc'])) {
            $dirPath = $_POST['folderloc'];
            $couser_id = $_POST['scourse_id'];
            $folder_name = $_POST['folder_name'];

            $dir = $dirPath . DIRECTORY_SEPARATOR;
            $this->emptyDir($dir);
            rmdir($dir);
            $newdata = [


                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => '0',
            ];
            $this->scorm_course_model->delscormfiles($newdata, $couser_id, $folder_name);
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
    }
    public function del_file()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        if (isset($_POST['fileloc'])) {
            $dirPath = $_POST['fileloc'];
            $scourse_id = $_POST['scourse_id'];
            $folder_name = $_POST['foldername'];
            // print_r($scourse_id);
            // print_r($folder_name);
            // exit();
            if (file_exists($dirPath)) {
                // File exists, proceed with deletion
                unlink($dirPath);
                $newdata = [


                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                    'status' => '0',
                ];
                // print_r($newdata);
                // exit();
                $this->scorm_course_model->delscormfiles($newdata, $scourse_id, $folder_name);
                // Set success message
                session()->setFlashdata('success', lang('Messages.Success_0005'));
            } else {
                // File doesn't exist
                session()->setFlashdata('error', lang('Messages.Error_0003'));
            }
            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
        }
    }
    public function emptyDir($dir)
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
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
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $scourse_id = $_POST['scourse_id'];

        $newdata = [


            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->scorm_course_model->deltecourse($newdata, $scourse_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
    }

    public function duplicateCourse()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $scourse_id = $_POST['scourse_id'];

        $original = $this->scorm_course_model->getCourseDetails($scourse_id);
        if (empty($original)) {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        $row = $original[0];

        // Only the course record itself is copied here - thumbnail, promo video and
        // any other uploaded assets are intentionally left out of $newdata.
        $newdata = [
            'project_id' => $row['project_id'],
            'type' => $row['type'],
            'theme' => $row['theme'],
            'course_code' => $row['course_code'],
            'course_name' => $row['course_name'] . ' - Copy',
            'description' => $row['description'],
            'duration' => $row['duration'],
            'language' => $row['language'],
            'demo' => $row['demo'],
            'mode' => 1,
            'status' => '1',
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];

        $result = $this->scorm_course_model->addcoursedetails($newdata);

        if ($result) {
            $objectives = $this->scorm_course_model->getAllObjectives($scourse_id);
            foreach ($objectives as $objective) {
                $this->scorm_course_model->add_objectives([
                    'course_id' => $result['course_id'],
                    'objective' => $objective['objective'],
                    'created_by' => session()->get('id_user'),
                    'created_on' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                    'status' => '1',
                ]);
            }

            session()->setFlashdata('success', lang('Messages.Success_0054'));
            $_SESSION['scourse_id'] = $result['course_id'];
            // Course Builder (SCORM\Course_builder\Editor) reads 'crid', not 'scourse_id', to
            // decide which course is currently open - without updating it here too, jumping
            // into Course Builder right after duplicating still opened whatever course was
            // last active there instead of the new copy.
            $_SESSION['crid'] = $result['course_id'];
            $_SESSION['course_name'] = $newdata['course_name'];
            $_SESSION['tab'] = 1;
            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
        }

        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->to(base_url() . 'SCORM/scorm_courses');
    }

    public function deleteasignmetadetails()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $mc_id = $_POST['mc_id'];
        $newdata = [


            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->scorm_course_model->deleteassignmeta($newdata, $mc_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'SCORM/scorm_courses/add_category');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/scorm_courses/add_category');
        }
    }

    public function upload_view()
    {

        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['scourse_id'] = $_POST['scourse_id'];
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/upload_view', $data);
        echo view('templates/footer_view');
    }
    public function thumbnail_upload()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['filesystem']);
        $data = [];
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $data['form_link'] = 'SCORM/scorm_courses/editcourse';
        $data['header'] = 'Courses';
        $data['header_link'] = 'my_training/read_more';
        $data['sub_header_1'] = $data['course_name'];

        $data['typeval'] = 2;
        $data['form_url_1'] = 'SCORM/scorm_courses/thumbnail_upload';
        $data['form_objective'] = 'SCORM/scorm_courses/add_objectives';
        $data['form_url_2'] = 'SCORM/scorm_courses/scorm_upload';
        $data['form_url_3'] = 'SCORM/scorm_courses/uploadpromovideo';
        $data['form_url_4'] = 'SCORM/scorm_courses/assignmetacategory';
        $data['form_url_5'] = 'SCORM/scorm_courses/deleteasignmetadetails';
        $data['form_url_6'] = 'SCORM/scorm_courses/uploadpdf';
        $data['delete_form'] = 'SCORM/scorm_courses/delete_form';
        $data['edit_form'] = 'SCORM/scorm_courses/edit_objective';
        $getCourseData = $this->scorm_course_model->getCourseDetails($data['scourse_id']);

        $data['row'] = $getCourseData[0];
        $data['allmetadata'] = $this->scorm_course_model->getAllMetadata(1);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(2);
        $data['getAssignmetadata'] = $this->scorm_course_model->getAssignmetadata($data['scourse_id']);
        $data['getAssigncategorydata'] = $this->scorm_course_model->getAssigncategorydata($data['scourse_id']);
        $data['getAllFileOwner'] = $this->scorm_course_model->getAllFileOwner($data['scourse_id']);
        $data['getAllObjectives'] = $this->scorm_course_model->getAllObjectives($data['scourse_id']);
        if ($this->request->getPost()) {
            $rules = [
                'file' => [
                    'label' => 'Thumbnail',
                    'rules' => 'uploaded[file]|max_size[file,200]|ext_in[file,jpg,jpeg,png]|is_image[file]|mime_in[file,image/jpg,image/jpeg,image/png]',
                    'errors' => [
                        'uploaded' => 'Please upload a thumbnail image.',
                        'max_size' => 'The thumbnail image must not exceed 200 KB.',
                        'ext_in'    => 'Only JPG, JPEG, and PNG files are allowed.',
                        'is_image'  => 'The uploaded file must be a valid image.',
                        'mime_in'   => 'Only JPG, JPEG, and PNG image formats are accepted.',
                    ],
                ],
            ];
            $file = $this->request->getFile('file');

            if (!$this->validate($rules)) {
                // print_r("thumbnailvalidation");
                // exit();
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file->isValid() && !$file->hasMoved()) {

                    // Check image dimensions
                    list($width, $height) = getimagesize($file->getTempName());

                    if ($width != 480) {

                        $data['thumbnailvalidation'] = \Config\Services::validation();

                        $data['thumbnailvalidation']->setError(
                            'file',
                            'Thumbnail width must be exactly 480px. Height can be any size.'
                        );
                    } else {

                        $filename = $file->getName();

                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'])) {

                            mkdir(
                                FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'],
                                0777,
                                true
                            );
                        }


                        if (file_exists(
                            FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' .
                                $data['scourse_id'] . "/" . $filename
                        )) {

                            session()->setFlashdata(
                                'error',
                                $filename . ' ' . lang('Messages.Success_0051')
                            );
                            return redirect()->to(
                                base_url() . 'SCORM/scorm_courses/course_settings_view'
                            );
                        } else {

                            if ($file->move(
                                FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' .
                                    $data['scourse_id'],
                                $filename
                            )) {

                                $newdata = [
                                    'scourse_id' => $data['scourse_id'],
                                    'thumbnail'  => $filename,
                                ];

                                $result = $this->scorm_course_model
                                    ->editcoursedetails($newdata, $data['scourse_id']);

                                if ($result) {

                                    session()->setFlashdata(
                                        'success',
                                        lang('Messages.Success_0009')
                                    );

                                    return redirect()->to(
                                        base_url() . 'SCORM/scorm_courses/course_settings_view'
                                    );
                                } else {

                                    session()->setFlashdata(
                                        'error',
                                        lang('Messages.Error_0001')
                                    );

                                    return redirect()->to(
                                        base_url() . 'SCORM/scorm_courses/course_settings_view'
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_settings_view', $data);
        echo view('templates/footer_view');
    }
    function scorm_upload()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['filesystem']);
        $data = [];
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

                            $fileupload = [
                                'description' => $_POST['description'],
                                'course_id' => $data['scourse_id'],
                                'folder' => $timestamp,
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time(),
                                'last_updated_by' => session()->get('id_user'),
                                'last_updated_on' => time(),
                            ];
                            $this->scorm_course_model->insertFileuploaddata($fileupload);

                            return json_encode($result);
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
    }
    public function assignmetacategory()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
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
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['filesystem']);
        $data = [];
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $data['form_link'] = 'SCORM/scorm_courses/editcourse';
        $data['header'] = 'Courses';
        $data['header_link'] = 'my_training/read_more';
        $data['sub_header_1'] = $data['course_name'];

        $data['typeval'] = 2;
        $data['form_url_1'] = 'SCORM/scorm_courses/thumbnail_upload';
        $data['form_objective'] = 'SCORM/scorm_courses/add_objectives';
        $data['form_url_2'] = 'SCORM/scorm_courses/scorm_upload';
        $data['form_url_3'] = 'SCORM/scorm_courses/uploadpromovideo';
        $data['form_url_4'] = 'SCORM/scorm_courses/assignmetacategory';
        $data['form_url_5'] = 'SCORM/scorm_courses/deleteasignmetadetails';
        $data['form_url_6'] = 'SCORM/scorm_courses/uploadpdf';
        $data['delete_form'] = 'SCORM/scorm_courses/delete_form';
        $data['edit_form'] = 'SCORM/scorm_courses/edit_objective';
        $getCourseData = $this->scorm_course_model->getCourseDetails($data['scourse_id']);

        $data['row'] = $getCourseData[0];
        $data['allmetadata'] = $this->scorm_course_model->getAllMetadata(1);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(2);
        $data['getAssignmetadata'] = $this->scorm_course_model->getAssignmetadata($data['scourse_id']);
        $data['getAssigncategorydata'] = $this->scorm_course_model->getAssigncategorydata($data['scourse_id']);
        $data['getAllFileOwner'] = $this->scorm_course_model->getAllFileOwner($data['scourse_id']);
        $data['getAllObjectives'] = $this->scorm_course_model->getAllObjectives($data['scourse_id']);

        if ($this->request->getPost()) {
            $rules = [
                'file' => [
                    'rules' => 'uploaded[file]|max_size[file,20480]|ext_in[file,mp4]|mime_in[file,video/mp4]',
                    'errors' => [
                        'uploaded' => 'Please select a video file.',
                        'max_size' => 'Video size must not exceed 20 MB.',
                        'ext_in' => 'Only MP4 video format is allowed.',
                        'mime_in' => 'Invalid video format.'
                    ]
                ]
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
                                    return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
                    }
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
                }
            }
            echo view('templates/header_view', $data);
            echo view('SCORM/scorm_courses/courses_settings_view', $data);
            echo view('templates/footer_view');
        }
    }
    public function uploadpdf()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        helper(['filesystem']);
        $data = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        }
        if (isset($_POST['createdon'])) {
            $data['createdon'] = $_POST['createdon'];
            $_SESSION['createdon'] = $data['createdon'];
        }
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,20480]|ext_in[file,pdf]'
            ];
            if (!$this->validate($rules)) {
                $data['pdfvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/PDF')) {
                            mkdir('assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/PDF', 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . "/" . $data['createdon'] . '/assets/PDF/' . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
                        } else {

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/PDF', $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/PDF/' . $filename;
                                $newdata = [
                                    'scourse_id' => $data['scourse_id'],
                                    'pdf_filename' => $filename,
                                    'last_updated_by' => session()->get('id_user'),
                                    'last_updated_on' => time(),
                                ];
                                $fileupload = [
                                    'description' => $_POST['description'],
                                    'course_id' => $data['scourse_id'],
                                    'folder' => $filename,
                                    'status' => 1,
                                    'createdby' => session()->get('id_user'),
                                    'createdon' => time(),
                                    'last_updated_by' => session()->get('id_user'),
                                    'last_updated_on' => time(),
                                ];
                                $this->scorm_course_model->insertFileuploaddata($fileupload);

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
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url() . 'SCORM/course_builder/Scorm_course_pages/page_pdf_view');
    }
    function transcript_pdf_download()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        $data = [];
        $data['coursedata'] = $this->scorm_course_model->getCourseData();
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_transcript_pdf', $data);
        echo view('templates/footer_view', $data);
    }
    function assessment_report()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        $data['assessment_report'] = $this->scorm_course_model->getAssessmentReport();
        echo view('templates/header_view', $data);
        echo view('assessment/assessment_report', $data);
        echo view('templates/footer_view', $data);
    }
    public function exportCoursedetails()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        $rowCount = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Course Code');
        $sheet->setCellValue('B1', 'Course Name');
        $sheet->setCellValue('C1', 'Duration');
        $sheet->setCellValue('D1', 'Language');
        $sheet->setCellValue('E1', 'thumbnail');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Created On');
        $sheet->setCellValue('H1', 'promo_video');

        $headerStyle = $sheet->getStyle('A1:H1');
        $headerStyle->getFont()->setBold(true);

        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        // print_r($arrayuserlevel);
        // exit();
        if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
            $getCourseDetails = $this->scorm_course_model->coursenamessearch_view20();
        } else {
            $getCourseDetails = $this->scorm_course_model->coursenamessearch_view201();
        }

        if (isset($getCourseDetails)) {
            if (count($getCourseDetails) > 0) {
                foreach ($getCourseDetails as $data) {
                    $course_code = strip_tags($data['course_code']);
                    $course_name = strip_tags($data['course_name']);
                    $duration = strip_tags($data['duration'] . ' min');
                    $lesson_status = strip_tags($data['lesson_status']);
                    if ($lesson_status == 'not started' || $lesson_status == 'Not Started') {
                        $lesson_status = 'Not Started';
                    } elseif ($lesson_status == 'incomplete' || $lesson_status == 'Incomplete') {
                        $lesson_status = 'In Progress';
                    } elseif ($lesson_status == 'completed' || $lesson_status == 'Completed' || $lesson_status == 'passed' || $lesson_status == 'Passed') {
                        $lesson_status = 'Completed';
                    } else {
                        $lesson_status = 'Not Attempted';
                    }
                    $language = strip_tags($data['language']);
                    $demo = ($data['demo'] == 1) ? 'Yes' : 'No';
                    $created_by = strip_tags($data['createdby']);
                    $created_on = ($data['createdon'] != '' && $data['createdon'] != '0') ? date("m-d-Y", $data['createdon']) : '';
                    $created_on = strip_tags($created_on);
                    $promo_video = strip_tags($data['promo_video']);
                    $thumbnail = strip_tags($data['thumbnail']);
                    $sheet->setCellValue('A' . $rowCount, $course_code);
                    $sheet->SetCellValue('B' . $rowCount, $course_name);
                    $sheet->SetCellValue('C' . $rowCount, $duration);
                    $sheet->SetCellValue('D' . $rowCount, $language);
                    $sheet->SetCellValue('E' . $rowCount, $thumbnail);
                    $sheet->SetCellValue('F' . $rowCount, $lesson_status);
                    $sheet->SetCellValue('G' . $rowCount, $created_on);
                    $sheet->SetCellValue('H' . $rowCount, $promo_video);
                    $sheet->getRowDimension($rowCount)->setRowHeight(20);
                    $rowCount = $rowCount + 1;
                }
            }
        }

        $today_dt = date("Y-m-d H-i-s-a");
        $filename = 'Course_DETAIL_DOWNLOAD-' . $today_dt . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Save the file to a temporary location
        $tempFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;
        if (!is_dir(sys_get_temp_dir())) {
            mkdir(sys_get_temp_dir(), 0777, true);
        }
        $writer->save($tempFilePath);

        // Set the appropriate headers and offer the file as a download
        return $this->response->download($tempFilePath, null)->setFileName($filename);
    }
}
