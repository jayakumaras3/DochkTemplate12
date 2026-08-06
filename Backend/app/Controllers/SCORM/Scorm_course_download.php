<?php

namespace App\Controllers\SCORM;

use App\Controllers\BaseController;

use ZipArchive;
use App\Models\SCORM\Scorm_dashboard_model;
use App\Models\SCORM\Scorm_course_model;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[\AllowDynamicProperties]
class Scorm_course_download extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->scorm_dashboard_model = new Scorm_dashboard_model();
        $this->scorm_course_model = new Scorm_course_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('6', $arrayuserlevel) && !in_array('73', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from projects and project_details table to display
    {
         if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['all_courses'] = $this->scorm_course_model->getCoursesDetails(2);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/scorm_export_view', $data);
        echo view('templates/footer_view');
    }
    public function AllC4UCoursesExport() //fetch data from projects and project_details table to display
    {
        
         if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $csvData = "Course Code,Category,Objectives,Description,Course Name,Duration (min),Metadata,Folder,Course id,Thumbnail filename\n";
        $clientCourseddata = $this->scorm_dashboard_model->getAllCoursedexportdata(2);
        if (isset($clientCourseddata)) {
            if (count($clientCourseddata) > 0) {
                foreach ($clientCourseddata as $eachclientCourseddata) {
                    $course_name = $eachclientCourseddata['course_name'];
                    $course_code = $eachclientCourseddata['course_code'];
                    $duration = $eachclientCourseddata['duration'];
                    $cleaneddescription = html_entity_decode($eachclientCourseddata['description']);
                    $description = strip_tags($cleaneddescription);
                    $objectives = str_replace(
                        ['<ul>', '</ul>', '<li>', '</li>', '<p>', '</p>'],
                        ['At the end of this course you will be able to:', '', '•', '', '', ''],
                        $eachclientCourseddata['objectives']
                    );
                    $thumbnail = $eachclientCourseddata['thumbnail'];
                    $scourse_id = $eachclientCourseddata['scourse_id'];
                    $Category = $eachclientCourseddata['category'];
                    $Metadata = $eachclientCourseddata['metadata'];
                    $csvData .= $course_code . ',"' . $Category . '","' . $objectives . '","' . $description . '","' . $course_name . '","' . $duration . '","' . $Metadata . '",' . 'SCORM_course_thumbnail' . ',' . $scourse_id . ',' . $thumbnail . "\n";
                }
            }
        }
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="course_list.csv"');
        echo $csvData;
        exit();
    }


    
    public function selectedC4UCoursesExport()
    {
        
         if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        // $rowCount = 2;
        // $spreadsheet = new Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setCellValue('A1', 'Course Code');
        // $sheet->setCellValue('B1', 'Category');
        // $sheet->setCellValue('C1', 'Objectives');
        // $sheet->setCellValue('D1', 'Description');
        // $sheet->setCellValue('E1', 'Course Name');
        // $sheet->setCellValue('F1', 'Duration (min)');
        // $sheet->setCellValue('G1', 'Metadata');
        // $sheet->setCellValue('H1', 'Course id');
        // $sheet->setCellValue('I1', 'Thumbnail filename');
        // $headerStyle = $sheet->getStyle('A1:I1');
        // $headerStyle->getFont()->setBold(true);

        $csvData = "Course Code,Category,Objectives,Description,Course Name,Duration (min),Metadata,Folder,Course id,Thumbnail filename\n";

        $course_id = $this->request->getVar('course_id');
        $clientCourseddata = $this->scorm_dashboard_model->getSelectedCoursesdetails($course_id);
        if (isset($clientCourseddata)) {
            if (count($clientCourseddata) > 0) {
                foreach ($clientCourseddata as $eachclientCourseddata) {
                    $course_name = strip_tags($eachclientCourseddata['course_name']);
                    $course_code = strip_tags($eachclientCourseddata['course_code']);
                    $duration = strip_tags($eachclientCourseddata['duration']);
                    $cleaneddescription = html_entity_decode($eachclientCourseddata['description']);
                    $description = strip_tags($cleaneddescription);
                    $objectives = str_replace(
                        ['<ul>', '</ul>', '<li>', '</li>', '<p>', '</p>'],
                        ['At the end of this course you will be able to:', '', "\n• ", '', '', ''],
                        $eachclientCourseddata['objectives']
                    );
                    $thumbnail = strip_tags($eachclientCourseddata['thumbnail']);
                    $scourse_id = strip_tags($eachclientCourseddata['scourse_id']);

                    $Category = strip_tags($eachclientCourseddata['category']);
                    $Metadata = strip_tags($eachclientCourseddata['metadata']);

                    $csvData .= $course_code . ',"' . $Category . '","' . $objectives . '","' . $description . '","' . $course_name . '","' . $duration . '","' . $Metadata . '",' . 'SCORM_course_thumbnail' . ',' . $scourse_id . ',' . $thumbnail . "\n";

                    // $sheet->setCellValue('A' . $rowCount, $course_code);
                    // $sheet->SetCellValue('B' . $rowCount, $Category);

                    // $sheet->getStyle('C' . $rowCount)->getAlignment()->setWrapText(true);
                    // $sheet->setCellValue('C' . $rowCount, $objectives);

                    // $sheet->getStyle('D' . $rowCount)->getAlignment()->setWrapText(true);
                    // $sheet->setCellValue('D' . $rowCount, $description);

                    // $sheet->SetCellValue('E' . $rowCount, $course_name);
                    // $sheet->setCellValue('F' . $rowCount, $duration);

                    // $sheet->SetCellValue('G' . $rowCount, $Metadata);
                    // $sheet->SetCellValue('H' . $rowCount, $scourse_id);
                    // $sheet->SetCellValue('I' . $rowCount, $thumbnail);
                    // $sheet->getRowDimension($rowCount)->setRowHeight(20);
                    // $rowCount = $rowCount + 1;
                }
            } 
        }
        header('Content-Type: text/csv; charset=utf-8');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        header('Content-Disposition: attachment; filename="course_list.csv"');

        echo $csvData;
        exit();
        // return redirect()->to(base_url('SCORM/scorm_course_download'));
        // $filename = 'C4U_courses.xlsx';
        // $writer = new Xlsx($spreadsheet);

        // Save the file to a temporary location
        // $tempFilePath = sys_get_temp_dir() . '/' . $filename;
        // $writer->save($tempFilePath);

        // Set the appropriate headers and offer the file as a download
        // return $this->response->download($tempFilePath, null)->setFileName($filename);
    }
}
