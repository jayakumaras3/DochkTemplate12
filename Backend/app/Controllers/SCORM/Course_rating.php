<?php

namespace App\Controllers;

use App\Models\SCORM\CourseRating_model;

#[\AllowDynamicProperties]
class Course_rating extends BaseController
{
    public function __construct()
    {

        $this->courseRating_model = new CourseRating_model();
    }
    public function submitRating()
    {
        if ($response =  $this->requireRole(['6', '44', '3'])) {
            return $response;
        }
        $courseId = $this->request->getPost('course_id');
        $rating = $this->request->getPost('rating');
        $comment = $this->request->getPost('comment');

        $courseRating_model = new CourseRating_model();

        // Assuming you have a user authentication system, get the user ID
        $userId = session()->get('id_user');

        // Insert data into the database
        $data = [
            'course_id' => $courseId,
            'user_id' => $userId,
            'rating' => $rating,
            'comment' => $comment,
        ];

        $result = $courseRating_model->insert($data);
        if ($result) {
            $this->courseRating_model->getratingCourse($courseId);
        }


        return json_encode(['success' => true]);
    }
    public function exportCourseRating()
    {
        if ($response =  $this->requireRole(['6', '44', '3'])) {
            return $response;
        }
        if ($this->request->getPost()) {
            $rules = [
                'start_date' => 'required|valid_date',
                'end_date' => 'required|valid_date',
            ];
            if (!$this->validate($rules)) {
                $data['courseRatevalidation'] = $this->validator;
            } else {

                $startDate = $this->request->getPost('start_date');
                $endDate = $this->request->getPost('end_date');

                $data = $this->courseRating_model->getCourseRateData($startDate, $endDate);
                $csvData = "Sl no,Course Name,User Name,Rating,Comment,Created On\n";
                $i = 0;
                foreach ($data as $row) {
                    $i++;
                    // Encoding each field and wrapping it in double quotes
                    $csvData .= $i . ',' . $this->encodeForCSV($row['course_name']) . ',' . $this->encodeForCSV($row['username']) . ',' . $this->encodeForCSV($row['rating']) . ',' . $this->encodeForCSV($row['comment']) . ',' . date('m-d-Y', strtotime($row['createdon'])) . "\n";
                }

                header('Content-Type: application/csv');
                header('Content-Disposition: attachment; filename="Report_' . $startDate . '_to_' . $endDate . '".csv"');

                echo $csvData;
                exit();
            }
        }
    }
    // Define the encodeForCSV method here
    protected function encodeForCSV($str)
    {
        if ($response =  $this->requireRole(['6', '44', '3'])) {
            return $response;
        }
        // Double quotes are escaped by doubling them
        $str = str_replace('"', '""', $str);
        // Wrapping the string in double quotes
        $str = '"' . $str . '"';
        return $str;
    }
}
