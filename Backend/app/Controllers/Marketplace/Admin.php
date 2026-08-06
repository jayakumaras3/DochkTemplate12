<?php

namespace App\Controllers\Marketplace;

use App\Controllers\BaseController;
use App\Models\Marketplace\M_Dashboard_model;
use App\Models\SCORM\Scorm_client_model;

#[\AllowDynamicProperties]
class Admin extends BaseController
{


    public function __construct()
    {
        $this->is_session_available();
        $this->M_Dashboard_model = new M_Dashboard_model();
        $this->scorm_client_model = new Scorm_client_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('44', $arrayuserlevel) && !in_array('5', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        if ($data['type'] == 1) {
            $data['header_title'] = "Marketplace Dashboard";
            $data['header_link'] = "marketplace/dashboard";
            $data['admin'] = "Create New Marketplace";
        } else {
            $data['header_title'] = "Learning Plan Dashboard";
            $data['header_link'] = "marketplace/learning_dashboard";
            $data['admin'] = "Create New Learning Plan";
        }
        $data['admin_page'] = "marketplace/admin";

        $data['get_marketplace'] = $this->M_Dashboard_model->get_all_marketplace($data['type']);

        echo view('templates/header_view', $data);
        echo view('marketplace/mp_admin', $data);
        echo view('templates/footer_view', $data);
    }

    public function add_new_marketplace()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        $marketplace_name = $this->request->getPost(index: 'marketplace_name');
        $remarks = $this->request->getPost('remarks');
        if ($marketplace_name != '') {
            $data = [
                'client_id' => session()->get('client'),
                'mp_name' => $marketplace_name,
                'remarks' => $remarks,
                'description' => $this->request->getPost('description'),
                'language' => $this->request->getPost('language'),
                'duration' => $this->request->getPost('duration'),
                'type' => $this->request->getPost('type'),
                'mode' => $this->request->getPost('mode'),
                'status' => 1,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $mp_id = $this->M_Dashboard_model->add_leave_data($data);
            if ($data['type'] == 1) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url('marketplace/dashboard'));
            } else {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                session()->set('mp_id', $mp_id);
                session()->set('mp_name', $marketplace_name);
                return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
            }
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('marketplace/dashboard'));
        }
    }

    public function delete_marketplace()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $mp_id = $this->request->getPost('mp_id');
        $data = [
            'status' => 0,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];
        $this->M_Dashboard_model->delete_marketplace($data, $mp_id);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url('marketplace/learning_dashboard'));
    }

    public function edit_client()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        if ($data['type'] == 1) {
            $data['header_title'] = "Marketplace Dashboard";
            $data['header_link'] = "marketplace/dashboard";
            $data['admin'] = "Create New Marketplace";
        } else {
            $data['header_title'] = "Learning Plan Dashboard";
            $data['header_link'] = "marketplace/learning_dashboard";
            $data['admin'] = "Create New Learning Plan";
        }
        $data['admin_page'] = "marketplace/admin";
        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $data['mp_name'] = $_POST['mp_name'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_name'] = $_POST['mp_name'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
            $data['mp_name'] = $_SESSION['mp_name'];
        }

        $data['get_client_by_id'] = $this->M_Dashboard_model->get_client_by_id($data['mp_id']);
        $data['get_active_clients'] = $this->M_Dashboard_model->get_active_clients();

        echo view('templates/header_view', $data);
        echo view('marketplace/mp_client', $data);
        echo view('templates/footer_view', $data);
    }

    public function add_client_to_marketplace()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $mp_id = $this->request->getPost('mp_id');
        $client_id = $this->request->getPost('client_id');
        $discount = round($this->request->getPost('discount'));
        if ($discount === '' || !is_numeric($discount)) {
            $discount = 0;
        }
        if ($client_id == '') {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('marketplace/admin/edit_client'));
        }
        if ($discount < 0 || $discount > 100) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('marketplace/admin/edit_client'));
        } else {
            $data = [
                'mp_id' => $mp_id,
                'cost' => $this->request->getPost('cost'),
                'payment_type' => $this->request->getPost('payment_type'),
                'billing_cycle' => $this->request->getPost('billing_cycle'),
                'currency' => $this->request->getPost('currency'),
                'discount' => $this->request->getPost('discount'),
                'client_id' => $client_id,
                'status' => 1,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->M_Dashboard_model->add_client_to_marketplace_mod($data);

            session()->setFlashdata('success', lang('Messages.Success_0011'));
            return redirect()->to(base_url('marketplace/admin/edit_client'));
        }
    }

    public function delete_marketplace_client()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $mp_cl_id = $this->request->getPost('mp_cl_id');

        $data = [
            'status' => 0,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];

        $this->M_Dashboard_model->del_marketplace_client($data, $mp_cl_id);

        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url('marketplace/admin/edit_client'));
    }


    public function edit_courses()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        if ($data['type'] == 1) {
            $data['header_title'] = "Marketplace Admin";
            $data['header_link'] = "marketplace/admin";
            $data['admin'] = "Create New Marketplace";
        } else {
            $data['header_title'] = "Learning Plan";
            $data['header_link'] = "marketplace/learning_dashboard";
            $data['admin'] = "Create New Learning Plan";
        }
        $data['admin_page'] = "marketplace/admin";
        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $data['mp_name'] = $_POST['mp_name'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_name'] = $_POST['mp_name'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
            $data['mp_name'] = $_SESSION['mp_name'];
        }

        $data['get_courses_by_id'] = $this->M_Dashboard_model->get_courses_by_id($data['mp_id']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (in_array('6', $arrayuserlevel)) {
            $data['get_all_courses'] = $this->M_Dashboard_model->get_all_courses();
        } else {
            $client = session()->get('client');
            $data['get_all_courses'] = $this->scorm_client_model->getUserCourses($client);
        }

        echo view('templates/header_view', $data);
        echo view('marketplace/mp_courses', $data);
        echo view('templates/footer_view', $data);
    }
    public function export_courses_excel()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $data['mp_name'] = $_POST['mp_name'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_name'] = $_POST['mp_name'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
            $data['mp_name'] = $_SESSION['mp_name'];
        }
        $courses = $this->M_Dashboard_model->get_courses_by_id($data['mp_id']);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Courses');

        // Header row
        $sheet->setCellValue('A1', '#')
            ->setCellValue('B1', 'Code')
            ->setCellValue('C1', 'Courses')
            ->setCellValue('D1', 'Duration')
            ->setCellValue('E1', 'Language')
            ->setCellValue('F1', 'Category')
            ->setCellValue('G1', 'Description')
            ->setCellValue('H1', 'Objectives')
            ->setCellValue('I1', 'Price')
            ->setCellValue('J1', 'Total Questions')
            ->setCellValue('K1', 'Pass Percentage');

        // Header style
        $headerStyle = $sheet->getStyle('A1:K1');
        $headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF000000');
        // $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $sheet->freezePane('A2');

        // Write data
        $row = 2;
        $count = 1;
        foreach ($courses as $course) {

            // ✅ Clean up objectives (remove HTML and format nicely)
            $objectives = $course['objective'] ?? '';
            $objectives = str_ireplace(['<br>', '<br/>', '<br />'], "\n", $objectives); // convert <br> to newline
            $objectives = strip_tags($objectives); // remove all HTML tags
            // strip_tags() only removes tags, not entities like &nbsp;/&amp; left over
            // from the rich-text editor, so decode those too (then collapse the
            // resulting non-breaking spaces to normal ones for a clean export).
            $objectives = str_replace("\xc2\xa0", ' ', html_entity_decode($objectives, ENT_QUOTES, 'UTF-8'));
            $objectives = str_replace('|', "\n• ", $objectives); // convert '|' into bullets

            // ✅ Clean description too (remove HTML)
            $description = strip_tags($course['description'] ?? '');
            $description = str_replace("\xc2\xa0", ' ', html_entity_decode($description, ENT_QUOTES, 'UTF-8'));

            $sheet->setCellValue('A' . $row, $course['scourse_id'])
                ->setCellValue('B' . $row, $course['course_code'])
                ->setCellValue('C' . $row, $course['course_name'])
                ->setCellValue('D' . $row, $course['duration'])
                ->setCellValue('E' . $row, $course['language'])
                ->setCellValue('F' . $row, $course['category_name'])
                ->setCellValue('G' . $row, $description)
                ->setCellValue('H' . $row, trim($objectives))
                ->setCellValue('I' . $row, $course['price']);
            if (isset($course['total_questions'])) {
                $sheet->setCellValue('J' . $row, $course['total_questions']);
            } else {
                $sheet->setCellValue('J' . $row, 'N/A');
            }
            if (isset($course['pass_percentage'])) {
                $sheet->setCellValue('K' . $row, $course['pass_percentage']);
            } else {
                $sheet->setCellValue('K' . $row, 'N/A');
            }

            // Wrap text for description & objectives
            $sheet->getStyle('G' . $row . ':H' . $row)->getAlignment()->setWrapText(true);

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download
        $filename = 'Courses_Export_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }


    public function downloadCoursesJson($mp_id)
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        // Get data by passed mp_id
        $data = $this->M_Dashboard_model->get_courses_by_id($mp_id);

        if (empty($data)) {
            return $this->response->setStatusCode(404)->setBody('No courses found for this Marketplace ID.');
        }

        $jsonData = [];

        foreach ($data as $item) {
            $basePrice = (float) $item['price'];
            $dealPrice = round($basePrice * 1.2);
            if ($dealPrice > 0) {
                $discount = round((($dealPrice - $basePrice) / $dealPrice) * 100);
            } else {
                $discount = 0; // or null, depending on your logic
            }

            $skill = $item['skill'];
            if (!empty($skill)) {
                if ($skill == "126") {
                    $skillname = "Business Skills";
                } elseif ($skill == "127") {
                    $skillname = "Compliance";
                } elseif ($skill == "128") {
                    $skillname = "DEI (Diversity, Equity, and Inclusion)";
                } elseif ($skill == "129") {
                    $skillname = "Technology";
                } elseif ($skill == "130") {
                    $skillname = "Safety";
                } elseif ($skill == "131") {
                    $skillname = "Wellness";
                } elseif ($skill == "132") {
                    $skillname = "Healthcare";
                }
            }

            $jsonData[] = [
                'id' => (int) $item['scourse_id'],
                'imagePath' => base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $item['scourse_id'] . '/' . $item['thumbnail']),
                'product_name' => $item['course_name'],
                'skill' => $skillname ?? 'General',
                'categories' => array_map('trim', explode(',', $item['category_name'] ?? 'General')),
                'date' => date('D, M d Y'),
                'status' => (bool) $item['status'],
                'base_price' => $basePrice,
                'dealPrice' => $dealPrice,
                'discountPercent' => $discount,
                'rating' => (float) $item['avg_rating'],
                'language' => $item['language'] ?? 'English',
                'description' => $item['description'] ?? '',
                'objectives' => $item['objective'] ?? '',
                'duration' => $item['duration'] ?? 'N/A',
            ];
        }

        $filename = 'marketplace_courses_' . $mp_id . '.json';

        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody(json_encode($jsonData, JSON_PRETTY_PRINT));
    }


    public function add_course_to_marketplace()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $mp_id = $this->request->getPost('mp_id');
        $course_id = $this->request->getPost('course_id');
        $price = $this->request->getPost('price');
        if ($price < 0) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('marketplace/admin/edit_courses'));
        }
        if ($course_id != '' && $price != '') {
            $data = [
                'mp_id' => $mp_id,
                'scorm_id' => $course_id,
                'price' => $price,
                'status' => 1,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->M_Dashboard_model->add_course_to_marketplace_mod($data);

            session()->setFlashdata('success', lang('Messages.Success_0011'));
            return redirect()->to(base_url('marketplace/admin/edit_courses'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('marketplace/admin/edit_courses'));
        }
    }

    public function delete_marketplace_course()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $mp_co_id = $this->request->getPost('mp_co_id');

        $data = [
            'status' => 0,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];

        $this->M_Dashboard_model->del_marketplace_course($data, $mp_co_id);

        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url('marketplace/admin/edit_courses'));
    }

    public function edit_marketplace()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];

        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $data['mp_name'] = $_POST['mp_name'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_name'] = $_POST['mp_name'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
            $data['mp_name'] = $_SESSION['mp_name'];
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        if ($data['type'] == 1) {
            $data['header_title'] = "Marketplace Dashboard";
            $data['header_link'] = "marketplace/dashboard";
            $data['admin'] = "Create New Marketplace";
        } else {
            $data['header_title'] = "Learning Plan Dashboard";
            $data['header_link'] = "marketplace/learning_dashboard";
            $data['admin'] = "Create New Learning Plan";
        }
        $data['admin_page'] = "marketplace/admin";
        $data['row'] = $this->M_Dashboard_model->get_marketplace_details($data['mp_id']);
        echo view('templates/header_view', $data);
        echo view('marketplace/mp_edit_marketplace', $data);
        echo view('templates/footer_view', $data);
    }
    function edit_marketplace_client()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];

        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $data['mp_name'] = $_POST['mp_name'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_name'] = $_POST['mp_name'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
            $data['mp_name'] = $_SESSION['mp_name'];
        }
        if (isset($_POST['mp_cl_id'])) {
            $data['mp_cl_id'] = $_POST['mp_cl_id'];
            $_SESSION['mp_cl_id'] = $_POST['mp_cl_id'];
        } elseif (isset($_SESSION['mp_cl_id'])) {
            $data['mp_cl_id'] = $_SESSION['mp_cl_id'];
        }

        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        if ($data['type'] == 1) {
            $data['header_title'] = "Marketplace Dashboard";
            $data['header_link'] = "marketplace/dashboard";
            $data['admin'] = "Create New Marketplace";
        } else {
            $data['header_title'] = "Learning Plan Dashboard";
            $data['header_link'] = "marketplace/learning_dashboard";
            $data['admin'] = "Create New Learning Plan";
        }
        $data['admin_page'] = "marketplace/admin";
        $data['get_client_by_id'] = $this->M_Dashboard_model->get_client_by_id($data['mp_id']);
        $data['get_active_clients'] = $this->M_Dashboard_model->get_active_clients();

        $data['row'] = $this->M_Dashboard_model->get_marketplace_client_details($data['mp_cl_id']);
        echo view('templates/header_view', $data);
        echo view('marketplace/mp_edit_client', $data);
        echo view('templates/footer_view', $data);
    }
    function edit_client_to_marketplace()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $mp_cl_id = $this->request->getPost('mp_cl_id');
        $client_id = $this->request->getPost('client_id');
        $discount = $this->request->getPost('discount');
        if ($discount === '' || !is_numeric($discount)) {
            $discount = 0;
        }
        if ($client_id == '') {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('marketplace/admin/edit_client'));
        }
        if ($discount < 0 || $discount > 100) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('marketplace/admin/edit_client'));
        } else {
            $data = [
                'cost' => $this->request->getPost('cost'),
                'payment_type' => $this->request->getPost('payment_type'),
                'billing_cycle' => $this->request->getPost('billing_cycle'),
                'currency' => $this->request->getPost('currency'),
                'discount' => $this->request->getPost('discount'),
                'client_id' => $client_id,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->M_Dashboard_model->update_marketplace_client($data, $mp_cl_id);

            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url('marketplace/admin/edit_client'));
        }
    }


    function update_marketplace_name()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $mp_id = $this->request->getPost('mp_id');
        $marketplace_name = $this->request->getPost('marketplace_name');

        $data = [
            'mp_name' => $marketplace_name,
            'remarks' => $this->request->getPost('remarks'),
            'description' => $this->request->getPost('description'),
            'language' => $this->request->getPost('language'),
            'duration' => $this->request->getPost('duration'),
            'type' => $this->request->getPost('type'),
            'mode' => $this->request->getPost('mode'),
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
            'status' => $this->request->getPost('status'),
        ];
        $this->M_Dashboard_model->update_marketplace_name($data, $mp_id);
        if ($data['status'] == 0) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url('marketplace/learning_dashboard'));
        }
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
    }
    public function thumbnail_upload()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $data['mp_name'] = $_POST['mp_name'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_name'] = $_POST['mp_name'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
            $data['mp_name'] = $_SESSION['mp_name'];
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        if ($data['type'] == 1) {
            $data['header_title'] = "Marketplace Dashboard";
            $data['header_link'] = "marketplace/dashboard";
            $data['admin'] = "Create New Marketplace";
        } else {
            $data['header_title'] = "Learning Plan Dashboard";
            $data['header_link'] = "marketplace/learning_dashboard";
            $data['admin'] = "Create New Learning Plan";
        }
        $data['admin_page'] = "marketplace/admin";


        if ($this->request->getPost()) {
            $rules = [
                'file' => [
                    'label' => 'Thumbnail',
                    'rules' => 'uploaded[file]|max_size[file,1024]|ext_in[file,jpg,jpeg,png]|is_image[file]|mime_in[file,image/jpg,image/jpeg,image/png]',
                    'errors' => [
                        'uploaded' => 'Please upload a thumbnail image.',
                        'max_size' => 'The thumbnail image must not exceed 1 MB.',
                        'ext_in'    => 'Only JPG, JPEG, and PNG files are allowed.',
                        'is_image'  => 'The uploaded file must be a valid image.',
                        'mime_in'   => 'Only JPG, JPEG, and PNG image formats are accepted.',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                // print_r("thumbnailvalidation");
                // exit();
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {

                    // Check image dimensions
                    list($width, $height) = getimagesize($file->getTempName());

                    if ($width > 500) {

                        $data['thumbnailvalidation'] = \Config\Services::validation();

                        $data['thumbnailvalidation']->setError(
                            'file',
                            'Thumbnail width must not exceed 500px. Height can be any size.'
                        );
                    } else {
                        if ($file->isValid() && !$file->hasMoved()) {
                            $filename = $file->getName();
                            if (!is_dir(FCPATH . 'assets/assets/uploads/learning_path/' . $data['mp_id'])) {
                                mkdir('assets/assets/uploads/learning_path/' . $data['mp_id'], 0777, true);
                            }
                            if (file_exists(FCPATH . 'assets/assets/uploads/learning_path/' . $data['mp_id'] . "/" . $filename)) {
                                session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                                return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
                            } else {
                                if ($file->move(FCPATH . 'assets/assets/uploads/learning_path/' . $data['mp_id'], $filename)) {
                                    $filepath = FCPATH . 'assets/assets/uploads/learning_path/' . $data['mp_id'] . '/' . $filename;
                                    $newdata = [
                                        'mp_id' => $data['mp_id'],
                                        'thumbnail' => $filename,
                                    ];
                                    $result = $this->M_Dashboard_model->update_marketplace_name($newdata, $data['mp_id']);
                                    if ($result) {
                                        session()->setFlashdata('success', lang('Messages.Success_0009'));
                                        session()->setFlashdata('alert-class', 'alert-danger');
                                        return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
                                    } else {
                                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                                        session()->setFlashdata('alert-class', 'alert-danger');
                                        return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
                                    }
                                }
                            }
                        } else {
                            session()->setFlashdata('error', lang('Messages.Error_0001'));
                            session()->setFlashdata('alert-class', 'alert-danger');
                            return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
                        }
                    }
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
                }
            }
            $data['admin_page'] = "marketplace/admin";
            $data['row'] = $this->M_Dashboard_model->get_marketplace_details($data['mp_id']);
        }
        echo view('templates/header_view', $data);
        echo view('marketplace/mp_edit_marketplace', $data);
        echo view('templates/footer_view', $data);
    }

    public function delete_thumbnail()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $mp_id = $this->request->getPost('mp_id');
        $row = $this->M_Dashboard_model->get_marketplace_details($mp_id);
        if (!empty($row['thumbnail'])) {
            $filepath = FCPATH . 'assets/assets/uploads/learning_path/' . $mp_id . '/' . $row['thumbnail'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            $this->M_Dashboard_model->update_marketplace_name(['thumbnail' => ''], $mp_id);
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        }
        return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
    }

    public function banner_upload()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $data['mp_name'] = $_POST['mp_name'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_name'] = $_POST['mp_name'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
            $data['mp_name'] = $_SESSION['mp_name'];
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        if ($data['type'] == 1) {
            $data['header_title'] = "Marketplace Dashboard";
            $data['header_link'] = "marketplace/dashboard";
            $data['admin'] = "Create New Marketplace";
        } else {
            $data['header_title'] = "Learning Plan Dashboard";
            $data['header_link'] = "marketplace/learning_dashboard";
            $data['admin'] = "Create New Learning Plan";
        }
        $data['admin_page'] = "marketplace/admin";


        if ($this->request->getPost()) {
            $rules = [
                'file' => [
                    'rules' => 'uploaded[file]'
                        . '|is_image[file]'
                        . '|mime_in[file,image/jpg,image/jpeg,image/png]'
                        . '|max_size[file,1024]', // 1 MB
                    'errors' => [
                        'uploaded' => 'Please upload a thumbnail.',
                        'is_image' => 'The uploaded file must be an image.',
                        'mime_in' => 'Only JPG, JPEG or PNG images are allowed.',
                        'max_size' => 'Banner size must not exceed 1 MB.'
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                // print_r("thumbnailvalidation");
                // exit();
                $data['bannervalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {

                    $imageInfo = getimagesize($file->getTempName());
                    $width = $imageInfo[0];

                    if ($width != 1200) {
                        session()->setFlashdata('error', lang('Messages.Error_0022'));
                        return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
                    }
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/learning_banner_path/' . $data['mp_id'])) {
                            mkdir('assets/assets/uploads/learning_banner_path/' . $data['mp_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/learning_banner_path/' . $data['mp_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/learning_banner_path/' . $data['mp_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/learning_banner_path/' . $data['mp_id'] . '/' . $filename;
                                $newdata = [
                                    'mp_id' => $data['mp_id'],
                                    'banner' => $filename,
                                ];
                                $result = $this->M_Dashboard_model->update_marketplace_name($newdata, $data['mp_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
                    }
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
                }
            }
            $data['admin_page'] = "marketplace/admin";
            $data['row'] = $this->M_Dashboard_model->get_marketplace_details($data['mp_id']);
        }
        echo view('templates/header_view', $data);
        echo view('marketplace/mp_edit_marketplace', $data);
        echo view('templates/footer_view', $data);
    }

    public function delete_banner()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $mp_id = $this->request->getPost('mp_id');
        $row = $this->M_Dashboard_model->get_marketplace_details($mp_id);
        if (!empty($row['banner'])) {
            $filepath = FCPATH . 'assets/assets/uploads/learning_banner_path/' . $mp_id . '/' . $row['banner'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            $this->M_Dashboard_model->update_marketplace_name(['banner' => ''], $mp_id);
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        }
        return redirect()->to(base_url('marketplace/admin/edit_marketplace'));
    }

    public function update_course_order()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = $this->request->getJSON(true);   // CI4 correct method
        $order = $data['order'];
        $result = $this->M_Dashboard_model->update_course_order($order);
        // session()->setFlashdata('success', 'Sorted !!!');
        return $this->response->setJSON(['status' => 'success']);
    }
}
