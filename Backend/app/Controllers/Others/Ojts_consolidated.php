<?php

namespace App\Controllers\Others;

use App\Controllers\BaseController;
use App\Models\Others\Ojts_model;
use Dompdf\Dompdf;
use Dompdf\Options;
use iio\libmergepdf\Merger;
use ZipArchive;
use Mpdf\Mpdf;

#[\AllowDynamicProperties]
class Ojts_consolidated extends BaseController
{
    public function __construct()
    {
        $this->Ojts_model = new Ojts_model();
    }
    public function index() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['ojts_id'])) {
            $data['ojts_id'] = $_POST['ojts_id'];
            $_SESSION['ojts_id'] = $data['ojts_id'];
        } elseif (isset($_SESSION['ojts_id'])) {
            $data['ojts_id'] = $_SESSION['ojts_id'];
        }
        $ojts_id = $data['ojts_id'];
        $data['ojts_consolidatedData'] = $this->Ojts_model->getojts_consolidatedData($ojts_id);
        $data['ojts_row'] = $this->Ojts_model->getojtsfilenameData($ojts_id);
        echo view('templates/header_view', $data);
        echo view('others/ojts_consolidated_view', $data);
        echo view('templates/footer_view');
    }
    public function ojts() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        echo view('templates/header_view', $data);
        echo view('others/ojtsfilename', $data);
        echo view('templates/footer_view');
    }
    public function ojtsfilenameedit()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['ojts_id'])) {
            $data['ojts_id'] = $_POST['ojts_id'];
            $_SESSION['ojts_id'] = $data['ojts_id'];
        } elseif (isset($_SESSION['ojts_id'])) {
            $data['ojts_id'] = $_SESSION['ojts_id'];
        }
        $ojts_id = $data['ojts_id'];
        $data['ojts_row'] = $this->Ojts_model->getojtsfilenameData($ojts_id);
        echo view('templates/header_view', $data);
        echo view('others/ojtsfilenameedit_view', $data);
        echo view('templates/footer_view');
    }
    public function updatefilenameojts()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['ojts_id'])) {
            $data['ojts_id'] = $_POST['ojts_id'];
            $_SESSION['ojts_id'] = $data['ojts_id'];
        } elseif (isset($_SESSION['ojts_id'])) {
            $data['ojts_id'] = $_SESSION['ojts_id'];
        }
        $ojts_id = $data['ojts_id'];
        if ($this->request->getPost()) {
            $rules = [
                'filename' => 'required',
            ];
            if (!$this->validate($rules)) {

                $data['ojtsvalidation'] = $this->validator;
            } else {
                $newdata = [
                    'filename' =>  $this->request->getVar('filename'),
                    'language' =>  $this->request->getVar('language'),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];

                $result = $this->Ojts_model->editfilenameOjts($newdata, $ojts_id);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'Others/Ojts_consolidated');
        }
        echo view('templates/header_view', $data);
        echo view('others/ojtsfilenameedit_view', $data);
        echo view('templates/footer_view');
    }
    public function ojts_add() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['ojts_id'])) {
            $data['ojts_id'] = $_POST['ojts_id'];
            $_SESSION['ojts_id'] = $data['ojts_id'];
        } elseif (isset($_SESSION['ojts_id'])) {
            $data['ojts_id'] = $_SESSION['ojts_id'];
        }
        $ojts_id = $data['ojts_id'];
        $data['ojts_consolidatedData'] = $this->Ojts_model->getsl_no($ojts_id);

        echo view('templates/header_view', $data);
        echo view('others/ojts_add_view', $data);
        echo view('templates/footer_view');
    }

    public function ojts_edit() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['ojd_id'])) {
            $data['ojd_id'] = $_POST['ojd_id'];
            $_SESSION['ojd_id'] = $data['ojd_id'];
        } elseif (isset($_SESSION['ojd_id'])) {
            $data['ojd_id'] = $_SESSION['ojd_id'];
        }
        $ojd_id = $data['ojd_id'];
        $data['ojts_row'] = $this->Ojts_model->getojts_Data($ojd_id);
        echo view('templates/header_view', $data);
        echo view('others/ojts_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function add_ojtsfilename()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $rules = [
                'filename' => 'required',
            ];
            if (!$this->validate($rules)) {

                $data['ojtsavalidation'] = $this->validator;
            } else {
                $newdata = [
                    'filename' =>  $this->request->getVar('filename'),
                    'language' =>  $this->request->getVar('language'),
                    'status' => 1,
                    'createdby' =>  session()->get('id_user'),
                    'createdon' => time(),
                ];

                $result = $this->Ojts_model->addojtsfilename($newdata);
                if ($result) {
                    $insertID = $result['insertID'];
                    $_SESSION['ojts_id'] = $insertID;
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                    return redirect()->to(base_url() . 'Others/Ojts_consolidated');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'Others/Ojts_consolidated/ojts_download_pdf');
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('others/ojts_add_view', $data);
        echo view('templates/footer_view');
    }
    public function add_ojts()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['ojts_id'])) {
            $data['ojts_id'] = $_POST['ojts_id'];
            $_SESSION['ojts_id'] = $data['ojts_id'];
        } elseif (isset($_SESSION['ojts_id'])) {
            $data['ojts_id'] = $_SESSION['ojts_id'];
        }
        $ojts_id = $data['ojts_id'];
        helper(['form']);
        if ($this->request->getPost()) {
            $rules = [
                'sl_no' => 'required',
                'task' => 'required',
            ];
            if (!$this->validate($rules)) {

                $data['ojtsavalidation'] = $this->validator;
            } else {
                $newdata = [
                    'sl_no' =>  $this->request->getVar('sl_no'),
                    'ojts_id' => $ojts_id,
                    'title' => $this->request->getVar('title'),
                    'task' => $this->request->getVar('task'),
                    'status' => 1,
                    'createdby' =>  session()->get('id_user'),
                    'createdon' => time(),
                ];

                $result = $this->Ojts_model->addOjts($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                    return redirect()->to(base_url() . 'Others/Ojts_consolidated');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'Others/Ojts_consolidated');
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('others/ojts_add_view', $data);
        echo view('templates/footer_view');
    }
    public function update_ojts()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $user = session()->get('username');
        $ojd_id = $this->request->getVar('ojd_id');
        $data['ojd_id'] = $ojd_id;
        if ($this->request->getPost()) {
            $rules = [
                'sl_no' => 'required',
                'task' => 'required',
            ];
            if (!$this->validate($rules)) {

                $data['ojtsvalidation'] = $this->validator;
            } else {
                $newdata = [
                    'sl_no' =>  $this->request->getVar('sl_no'),
                    'title' => $this->request->getVar('title'),
                    'task' => $this->request->getVar('task'),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];

                $result = $this->Ojts_model->editOjts($newdata, $ojd_id);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'Others/Ojts_consolidated/ojts_edit');
        }
        echo view('templates/header_view', $data);
        echo view('others/ojts_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function ojtsfilenamedelete()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $user = session()->get('username');
        $ojts_id = $this->request->getVar('ojts_id');
        $data['ojts_id'] = $ojts_id;
        if ($this->request->getPost()) {
            $newdata = [
                'status' => $this->request->getVar('status'),
                 
                 
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];

            $result = $this->Ojts_model->editfilenameOjts($newdata, $ojts_id);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . 'Others/Ojts_consolidated/ojts_download_pdf');
        }
        return redirect()->to(base_url() . 'Others/Ojts_consolidated/ojts_download_pdf');
    }
    public function ojts_delete()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $user = session()->get('username');
        $ojd_id = $this->request->getVar('ojd_id');
        $data['ojd_id'] = $ojd_id;
        if ($this->request->getPost()) {
            $newdata = [
                'status' => $this->request->getVar('status'),
                 
                 
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];

            $result = $this->Ojts_model->editOjts($newdata, $ojd_id);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . 'Others/Ojts_consolidated');
        }
        return redirect()->to(base_url() . 'Others/Ojts_consolidated');
    }

    public function ojts_download_pdf() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['ojts_consolidatedData'] = $this->Ojts_model->viewojts_consolidatedData();
        echo view('templates/header_view', $data);
        echo view('others/ojts_consolidated_pdf_view', $data);
        echo view('templates/footer_view');
    }
    public function ojts_conslidated_pdf()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $dompdf = new Dompdf();
        if (isset($_POST['ojts_id'])) {
            $data['ojts_id'] = $_POST['ojts_id'];
            $_SESSION['ojts_id'] = $data['ojts_id'];
        } elseif (isset($_SESSION['ojts_id'])) {
            $data['ojts_id'] = $_SESSION['ojts_id'];
        }
        $ojts_id = $data['ojts_id'];
        if (isset($_POST['filename'])) {
            $data['filename'] = $_POST['filename'];
            $_SESSION['filename'] = $data['filename'];
        } elseif (isset($_SESSION['filename'])) {
            $data['filename'] = $_SESSION['filename'];
        }
        $filename = $data['filename'];
        // print_r($data['filename']);
        // exit();
        $data = [
            'header' => $this->imageToBase64(ROOTPATH . '/assets/assets/img/Header.png'),
            'footer' => $this->imageToBase64(ROOTPATH . '/assets/assets/img/Footer.jpg'),
            "filename" => $filename
        ];

        $data['ojts_consolidatedData'] = $this->Ojts_model->pdfojts_consolidatedData($ojts_id);
        // echo "<pre>";
        // print_r($data['ojts_consolidatedData']);
        // exit();
        if (!empty($data['ojts_consolidatedData'])) {

            $html = view('others/pdf_ojts_view', $data);
            // Ensure UTF-8 encoding
            $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

            // Enable HTML5 and font settings
            $dompdf->getOptions()->set('isHtml5ParserEnabled', true);
            $dompdf->getOptions()->set('isPhpEnabled', true);
            $dompdf->loadHtml($html);

            // Set paper size and orientation
            // $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $canvas = $dompdf->getCanvas();

            $title = str_replace('_', ' ', $filename);
            $title = mb_convert_encoding($title, 'UTF-8', 'UTF-8');

            $font = $dompdf->getFontMetrics()->getFont('tahoma', 'normal');
            $fontSize = 8;

            $y = 768; // tweak this number if needed

            // Calculate width and position manually for perfect centering
            $width = $dompdf->getFontMetrics()->getTextWidth($title, $font, $fontSize);
            $centerX = ($canvas->get_width() - $width) / 2;

            // $lineY = $y - 5; // Adjust this to move the line a bit above the text
            // $canvas->line(30, $lineY, $canvas->get_width() - 30, $lineY, [0, 0, 0], 0.5); // x1, y1, x2, y2, color, thickness


            // Draw title
            $canvas->page_text(30, $y, $title, $font, $fontSize, [0, 0, 0]);

            // Draw page number right aligned (adjust X as needed)
            $canvas->page_text(558, $y, "{PAGE_NUM}", $font, $fontSize, [0, 0, 0]);

            $dompdf->stream($filename . '.pdf', ['Attachment' => true]);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Others/Ojts_consolidated/ojts_download_pdf');
        }
    }
    private function imageToBase64($path)
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
    function ojts_group_view()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['ojtsgroupdata'] = $this->Ojts_model->getojtsgroupdata();
        echo view('templates/header_view', $data);
        echo view('others/ojts_group_view', $data);
        echo view('templates/footer_view');
    }
    function add_group_name()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $rules = [
                'group_name' => 'required',
            ];
            if (!$this->validate($rules)) {

                $data['ojtsavalidation'] = $this->validator;
            } else {
                $newdata = [
                    'group_name' =>  $this->request->getVar('group_name'),
                    'status' => 1,
                    'createdby' =>  session()->get('id_user'),
                    'createdon' => time(),
                ];

                $result = $this->Ojts_model->addojtsgroupname($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'Others/Ojts_consolidated/ojts_group_view');
        }
        echo view('templates/header_view', $data);
        echo view('others/ojts_add_view', $data);
        echo view('templates/footer_view');
    }
    function group_edit()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['oj_group_id'])) {
            $data['oj_group_id'] = $_POST['oj_group_id'];
            $_SESSION['oj_group_id'] = $data['oj_group_id'];
        } elseif (isset($_SESSION['oj_group_id'])) {
            $data['oj_group_id'] = $_SESSION['oj_group_id'];
        }
        $oj_group_id = $data['oj_group_id'];
        $data['oj_group_row'] = $this->Ojts_model->getojts_groupData($oj_group_id);
        echo view('templates/header_view', $data);
        echo view('others/ojts_group_edit', $data);
        echo view('templates/footer_view');
    }
    function edit_group_name()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $user = session()->get('username');
        $oj_group_id = $this->request->getVar('oj_group_id');
        $data['oj_group_id'] = $oj_group_id;
        if ($this->request->getPost()) {
            $rules = [
                'group_name' => 'required',
            ];
            if (!$this->validate($rules)) {

                $data['ojtsvalidation'] = $this->validator;
            } else {
                $newdata = [
                    'group_name' =>  $this->request->getVar('group_name'),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];

                $result = $this->Ojts_model->editOjtsgroup($newdata, $oj_group_id);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'Others/Ojts_consolidated/addojts_togroup');
        }
        echo view('templates/header_view', $data);
        echo view('others/ojts_group_edit', $data);
        echo view('templates/footer_view');
    }
    function group_delete()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        if (isset($_POST['oj_group_id'])) {
            $data['oj_group_id'] = $_POST['oj_group_id'];
            $_SESSION['oj_group_id'] = $data['oj_group_id'];
        } elseif (isset($_SESSION['oj_group_id'])) {
            $data['oj_group_id'] = $_SESSION['oj_group_id'];
        }

        $newData = [
            'status' => $_POST['status'],
             
             
        ];
        $result = $this->Ojts_model->editOjtsgroup($newData, $data['oj_group_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'Others/Ojts_consolidated/ojts_group_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Others/Ojts_consolidated/ojts_group_view');
        }
    }
    function addojts_togroup()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['oj_group_id'])) {
            $data['oj_group_id'] = $_POST['oj_group_id'];
            $_SESSION['oj_group_id'] = $data['oj_group_id'];
        } elseif (isset($_SESSION['oj_group_id'])) {
            $data['oj_group_id'] = $_SESSION['oj_group_id'];
        }
        $oj_group_id = $data['oj_group_id'];
        $data['titles'] = $this->Ojts_model->viewojts_consolidatedData();
        $data['ojts_group_assigned'] = $this->Ojts_model->getojts_group_assigned($oj_group_id);
        $data['oj_group_row'] = $this->Ojts_model->getojts_groupData($oj_group_id);
        $data['rowojts_groupData'] = $this->Ojts_model->rowojts_groupData($oj_group_id);
        echo view('templates/header_view', $data);
        echo view('others/add_ojts_togroup_view', $data);
        echo view('templates/footer_view');
    }
    function assign_ojts_group()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $newData = [
            'ojts_id' => $_POST['ojts_id'],
            'oj_group_id' => $_POST['oj_group_id'],
            'sequence' => $_POST['sequence'],
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->Ojts_model->add_ojts_to_gr($newData);
        echo json_encode($result);
    }
    function assign_ojts_delete()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        if (isset($_POST['oj_group_id'])) {
            $data['oj_group_id'] = $_POST['oj_group_id'];
            $_SESSION['oj_group_id'] = $data['oj_group_id'];
        } elseif (isset($_SESSION['oj_group_id'])) {
            $data['oj_group_id'] = $_SESSION['oj_group_id'];
        }
        if (isset($_POST['og_assign_id'])) {
            $data['og_assign_id'] = $_POST['og_assign_id'];
            $_SESSION['og_assign_id'] = $data['og_assign_id'];
        } elseif (isset($_SESSION['og_assign_id'])) {
            $data['og_assign_id'] = $_SESSION['og_assign_id'];
        }
        $newData = [
            'status' => $_POST['status'],
             
             
        ];
        $result = $this->Ojts_model->assign_ojts_delete($newData, $data['og_assign_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'Others/Ojts_consolidated/addojts_togroup');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Others/Ojts_consolidated/addojts_togroup');
        }
    }
    // function ojts_group_pdf()
    // {

    //     if (isset($_POST['oj_group_id'])) {
    //         $data['oj_group_id'] = $_POST['oj_group_id'];
    //         $_SESSION['oj_group_id'] = $data['oj_group_id'];
    //     } elseif (isset($_SESSION['oj_group_id'])) {
    //         $data['oj_group_id'] = $_SESSION['oj_group_id'];
    //     }

    //     $oj_group_id = $data['oj_group_id'];
    //     $ojts_ids = $this->Ojts_model->getojts_group_assigned($oj_group_id);
    //     if (!empty($ojts_ids)) {
    //         // Initialize Dompdf
    //         $options = new Options();
    //         $options->set('isHtml5ParserEnabled', true);
    //         $options->set('isPhpEnabled', true);
    //         $options->set('defaultFont', 'DejaVu Sans'); // supports Unicode characters

    //         $dompdf = new Dompdf($options);

    //         // Start combining HTML
    //         $fullHtml = '';
    //         $ojts_count = count($ojts_ids);
    //         $current_index = 0;

    //         $fullHtml = '';
    //         $first = true;

    //         foreach ($ojts_ids as $ojts_id) {
    //             $data['ojts_consolidatedData'] = $this->Ojts_model->pdfojts_consolidatedData($ojts_id['ojts_id']);
    //             if (empty($data['ojts_consolidatedData'])) continue;

    //             $data['header'] = $this->imageToBase64(ROOTPATH . '/assets/assets/img/Header.png');
    //             $data['footer'] = $this->imageToBase64(ROOTPATH . '/assets/assets/img/Footer.jpg');
    //             $data['filename'] = $ojts_id['filename'];

    //             $html = view('others/pdf_ojts_view', $data);
    //             $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

    //             // Insert page break before every section except the first
    //             if (!$first) {
    //                 $fullHtml .= '<div class="page-break"></div>';
    //             }
    //             $first = false;

    //             $fullHtml .= $html;
    //         }
    //         $finalHtml = '<!DOCTYPE html>
    //     <html>
    //     <head>
    //         <meta charset="UTF-8">
    //         <style>
    //             body { font-family: DejaVu Sans, sans-serif; }
    //             .page-break { page-break-before: always; }
    //         </style>
    //     </head>
    //     <body>' . $fullHtml . '</body>
    //     </html>';




    //         $dompdf->loadHtml($finalHtml);
    //         $dompdf->setPaper('A4', 'portrait'); // optional
    //         $dompdf->render();
    //         $canvas = $dompdf->getCanvas();

    //         $title = str_replace('_', ' ', $ojts_id['filename']);
    //         $title = mb_convert_encoding($title, 'UTF-8', 'UTF-8');

    //         $font = $dompdf->getFontMetrics()->getFont('tahoma', 'normal');
    //         $fontSize = 6;

    //         $y = 820; // tweak this number if needed

    //         // Calculate width and position manually for perfect centering
    //         $width = $dompdf->getFontMetrics()->getTextWidth($title, $font, $fontSize);
    //         $centerX = ($canvas->get_width() - $width) / 2;

    //         // Draw title
    //         $canvas->page_text($centerX, $y, $title, $font, $fontSize, [0, 0, 0]);

    //         // Draw page number right aligned (adjust X as needed)
    //         $canvas->page_text(553, $y, "{PAGE_NUM}", $font, $fontSize, [0, 0, 0]);

    //         // Output the single merged PDF
    //         $bulkFilename = 'OJTs_Bulk_Report.pdf';
    //         $dompdf->stream($bulkFilename, ['Attachment' => true]); // true = download; false = open in browser
    //         exit();
    //     } else {
    //         session()->setFlashdata('error', lang('Messages.Error_0001'));
    //         session()->setFlashdata('alert-class', 'alert-danger');
    //         return redirect()->to(base_url() . 'Others/Ojts_consolidated/ojts_group_view');
    //     }
    // }


    function ojts_group_pdf()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['oj_group_id'])) {
            $data['oj_group_id'] = $_POST['oj_group_id'];
            $_SESSION['oj_group_id'] = $data['oj_group_id'];
        } elseif (isset($_SESSION['oj_group_id'])) {
            $data['oj_group_id'] = $_SESSION['oj_group_id'];
        }

        $oj_group_id = $data['oj_group_id'];
        $ojts_ids = $this->Ojts_model->getojts_group_assigned($oj_group_id);

        if (!empty($ojts_ids)) {

            $tmpPdfFiles = []; // to store generated pdf file paths

            // Setup Dompdf options once
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');

            foreach ($ojts_ids as $ojts) {
                $data['ojts_consolidatedData'] = $this->Ojts_model->pdfojts_consolidatedData($ojts['ojts_id']);
                if (empty($data['ojts_consolidatedData'])) continue;

                $data['header'] = $this->imageToBase64(ROOTPATH . '/assets/assets/img/Header.png');
                $data['footer'] = $this->imageToBase64(ROOTPATH . '/assets/assets/img/Footer.jpg');
                $data['filename'] = $ojts['filename'];

                $html = view('others/pdf_ojts_view', $data);
                $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                // Add page footer with page number and title
                $canvas = $dompdf->getCanvas();
                $font = $dompdf->getFontMetrics()->getFont('tahoma', 'normal');
                $fontSize = 8;
                $y = 820;

                $title = str_replace('_', ' ', $ojts['filename']);
                $width = $dompdf->getFontMetrics()->getTextWidth($title, $font, $fontSize);
                // $centerX = ($canvas->get_width() - $width) / 2;

                $canvas->page_text(30, $y, $title, $font, $fontSize, [0, 0, 0]);
                $canvas->page_text(558, $y, "{PAGE_NUM}", $font, $fontSize, [0, 0, 0]);

                // Save individual pdf to temp file
                $tmpPath = WRITEPATH . 'temp_pdf_' . $ojts['ojts_id'] . '.pdf';
                file_put_contents($tmpPath, $dompdf->output());
                $tmpPdfFiles[] = $tmpPath;
            }

            // Merge all generated PDFs
            $merger = new Merger();
            foreach ($tmpPdfFiles as $file) {
                $merger->addFile($file);
            }

            $mergedPdfContent = $merger->merge();

            // Cleanup temp files
            foreach ($tmpPdfFiles as $file) {
                if (file_exists($file)) unlink($file);
            }

            // Output merged PDF
            return $this->response->setContentType('application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename=OJTs_Bundle_' . $ojts_ids[0]['group_name'] . '.pdf')
                ->setBody($mergedPdfContent);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Others/Ojts_consolidated/ojts_group_view');
        }
    }
    public function update_sequence()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $order = $this->request->getPost('order');

        if (is_array($order)) {
            foreach ($order as $item) {
                $og_assign_id = (int) $item['id'];          // og_assign_id
                $position = (int) $item['position']; // new sequence
                $newdata = [
                    'sequence' => $position
                ];
                // Update the sequence field in DB
                $this->Ojts_model->assign_ojts_delete($newdata, $og_assign_id);
            }

            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid input']);
    }
    function getlanguagefromfilename()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $this->Ojts_model->getlanguagefromfilename();
    }
    public function export_all_OJTS_excelformat()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $allOjts = $this->Ojts_model->viewojts_consolidatedData();

        $zip = new \ZipArchive();

        $zipFileName = WRITEPATH . 'uploads/All_OJTS_Excel.zip';

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            foreach ($allOjts as $item) {

                $ojts_id = $item['ojts_id'];

                $data['ojts_consolidatedData'] = $this->Ojts_model->pdfojts_consolidatedData($ojts_id);

                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $headerStyle = $sheet->getStyle('A1:B1');
                $headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
                $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF9900');
                $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->freezePane('A2');

                $sheet->setCellValue('A1', '#')
                    ->setCellValue('B1', 'Task');

                $row = 2;
                $j = 0;

                foreach ($data['ojts_consolidatedData'] as $ojts) {

                    $j++;

                    $text = $ojts['task'] ?? '';
                    $text = mb_convert_encoding($text, 'UTF-8', 'auto');
                    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                    $text = str_replace('<li>', "\n• ", $text);
                    $text = str_replace('</li>', "", $text);

                    $text = str_replace(['<br>', '<br/>', '<br />'], "\n", $text);
                    $text = str_replace(['</p>', '<p>'], ["\n", ""], $text);
                    $text = str_replace('&nbsp;', ' ', $text);

                    $text = strip_tags($text);

                    $sheet->setCellValue('A' . $row, $j)
                        ->setCellValue('B' . $row, $text);
                    $sheet->getStyle('B' . $row)
                        ->getAlignment()
                        ->setWrapText(true);

                    // auto adjust row height
                    $sheet->getRowDimension($row)->setRowHeight(-1);
                    // Center align sequence columns
                    $sheet->getStyle('A' . $row)->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);




                    $row++;
                }
                foreach (range('A', 'B') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                $filename = $data['ojts_consolidatedData'][0]['filename'] . '.xlsx';

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

                ob_start();
                $writer->save('php://output');
                $exceldata = ob_get_clean();

                $zip->addFromString($filename, $exceldata);
            }

            $zip->close();
        }

        return $this->response->download($zipFileName, null)->setFileName('All_OJTS_Excel.zip');
    }
    public function export_OJTS_excelformat()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['ojts_id'])) {
            $data['ojts_id'] = $_POST['ojts_id'];
            $_SESSION['ojts_id'] = $data['ojts_id'];
        } elseif (isset($_SESSION['ojts_id'])) {
            $data['ojts_id'] = $_SESSION['ojts_id'];
        }

        $data['ojts_consolidatedData'] = $this->Ojts_model->pdfojts_consolidatedData($data['ojts_id']);
        // echo "<pre>";
        // print_r($data['ojts_consolidatedData']);
        // exit();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setTitle($data['title']);


        $sheet->setCellValue('A1', '#')
            ->setCellValue('B1', 'Task');



        $headerStyle = $sheet->getStyle('A1:B1');
        $headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF9900');
        $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->freezePane('A2');

        $row = 2;
        $j = 0;
        foreach ($data['ojts_consolidatedData'] as $ojts) {

            $j = $j + 1;
            $text = $ojts['task'] ?? '';
            $text = mb_convert_encoding($text, 'UTF-8', 'auto');
            $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            // convert list items to bullet points
            $text = str_replace('<li>', "\n• ", $text);
            $text = str_replace('</li>', "", $text);
            // $text = str_replace(['<li>', '</li>'], ['• ', "\n"], $text);

            // convert line break tags
            $text = str_replace(['<br>', '<br/>', '<br />'], "\n", $text);
            $text = str_replace(['</p>', '<p>'], ["\n", ""], $text);
            $text = str_replace('&nbsp;', ' ', $text);

            // remove remaining HTML tags
            $text = strip_tags($text);

            // enable wrap text so bullets show properly
            $sheet->getStyle('B' . $row)->getAlignment()->setWrapText(true);
            $title = (isset($ojts['title']) && $ojts['title'] != '')
                ? '<b>' . $ojts['title'] . ': </b>'
                : '';
            $sheet->setCellValue('A' . $row,   $j)
                ->setCellValue('B' . $row, strip_tags($title) . $text);


            $sheet->getStyle('B' . $row)
                ->getAlignment()
                ->setWrapText(true);

            // auto adjust row height
            $sheet->getRowDimension($row)->setRowHeight(-1);
            // Center align sequence columns
            $sheet->getStyle('A' . $row)->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);



            $row++;
        }

        foreach (range('A', 'B') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $filename = $data['ojts_consolidatedData'][0]['filename'] . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    function export_group_OJTS_excelformat()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['oj_group_id'])) {
            $data['oj_group_id'] = $_POST['oj_group_id'];
            $_SESSION['oj_group_id'] = $data['oj_group_id'];
        } elseif (isset($_SESSION['oj_group_id'])) {
            $data['oj_group_id'] = $_SESSION['oj_group_id'];
        }

        $oj_group_id = $data['oj_group_id'];
        $ojts_ids = $this->Ojts_model->getojts_group_assigned($oj_group_id);

        if (!empty($ojts_ids)) {

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();


            $sheet->setCellValue('A1', '#')
                ->setCellValue('B1', 'Title')
                ->setCellValue('C1', '#')
                ->setCellValue('D1', 'Task');


            $headerStyle = $sheet->getStyle('A1:D1');
            $headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
            $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF9900');
            $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->freezePane('A2');

            $row = 2;
            $j = 0;
            foreach ($ojts_ids as $ojts) {
                $j = $j + 1;
                // print_r($ojts);
                // exit();
                $ojts_consolidatedData = $this->Ojts_model->pdfojts_consolidatedData($ojts['ojts_id']);

                if (empty($ojts_consolidatedData)) continue;
                $i = 0;
                foreach ($ojts_consolidatedData as $ojt) {
                    $i = $i + 1;
                    $text = $ojt['task'] ?? '';
                    $text = mb_convert_encoding($text, 'UTF-8', 'auto');
                    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    // convert list items to bullet points


                    $text = str_replace('<li>', "\n• ", $text);
                    $text = str_replace('</li>', "", $text);

                    // convert line breaks
                    $text = str_replace(['<br>', '<br/>', '<br />'], "\n", $text);

                    // convert paragraphs
                    $text = str_replace(['</p>', '<p>'], ["\n", ""], $text);

                    // replace nbsp
                    $text = str_replace('&nbsp;', ' ', $text);

                    // remove remaining HTML
                    $text = strip_tags($text);

                    // enable wrap text so bullets show properly
                    $sheet->getStyle('D' . $row)->getAlignment()->setWrapText(true);
                    $title = (isset($ojt['title']) && $ojt['title'] != '')
                        ? '<b>' . $ojt['title'] . ': </b>'
                        : '';

                    $sheet->setCellValue('A' . $row, $j  ?? '')
                        ->setCellValue('B' . $row, $ojts['filename'] ?? '')
                        ->setCellValue('C' . $row, $i)
                        // ->setCellValue('D' . $row, $ojt['title'] ?? '')
                        ->setCellValue('D' . $row, strip_tags($title) . $text);
                    $sheet->getStyle('D' . $row)
                        ->getAlignment()
                        ->setWrapText(true);

                    // Center align sequence columns
                    $sheet->getStyle('A' . $row)->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $sheet->getStyle('C' . $row)->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    // auto adjust row height
                    $sheet->getRowDimension($row)->setRowHeight(-1);

                    $row++; // MUST be here

                }
            }

            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $filename =  $ojts_ids[0]['group_name'] . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit();
        }
    }
}
