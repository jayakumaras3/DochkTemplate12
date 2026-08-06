<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;

use App\Models\Cron\Cron_model;

#[\AllowDynamicProperties]
class Send_mail_cron extends BaseController
{
    public function __construct()
    {
        $this->Cron_model = new Cron_model();
    }
    public function index()
    {
        $newdata = [
            'last_updated_on' => time(),
        ];
        $this->Cron_model->insert_cron_time($newdata);




        //Birthday Reminder
        $dateofbirth = date('Y-m-d');
        $birthdaylist = $this->Cron_model->get_birthday_buddies($dateofbirth);
        // print_r($birthdaylist);
        // exit();
        if (count($birthdaylist) > 0) {

            $buddies = '';
            foreach ($birthdaylist as $row) {
                $buddies .= $row['fname'] . ' ' . $row['lname'] . '<br>';
                echo $row['fname'] . ' ' . $row['lname'] . '<br>';
            }
            $to = 'pramod.c@TouchstoneLC.com,shwetha.k@TouchstoneLC.com';
            $subject = 'Birthday Reminder : ' . date('d-m-Y');
            $message = 'Hi,<br><br>'
                . 'This is a reminder for the birthdays of our employees today.<br><br>'
                . 'Please find the list of employees having birthdays today:<br>';

            $messagefull = $message . '<br> ' . $buddies . '<br>'
                . 'Regards,<br>'
                . 'Dochek Team';

            $email = \Config\Services::email();
            $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($messagefull);
            if ($email->send()) {

                //  session()->setFlashdata('success', 'Mail Sent.');
            } else {
                $data = $email->printDebugger(['headers']);
                print_r($data);
                exit();
            }
        }
        //End Birthday Reminder

        //WIP Reminder
        $day_of_month = date('d');
        if ($day_of_month == 1) {

            $to = 'pramod.c@TouchstoneLC.com, shivakumar.m@TouchstoneLC.com, shruti.s@touchstonelc.com, padmalika.b@touchstonelc.com';
            $subject = 'UCN WIP Update Reminder';
            $message = 'Hi,<br><br>'
                . 'Please update UCN WIP numbers in Dochek.<br><br>'
                . 'Note: WIP can be updated only between 1 to 5 of the month.<br><br>'
                . 'Regards,<br>'
                . 'Dochek Team';

            $email = \Config\Services::email();
            $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($message);
            if ($email->send()) {
                // session()->setFlashdata('success', 'Mail Sent.');
            } else {
                $data = $email->printDebugger(['headers']);
                print_r($data);
                exit();
            }
        }
        //WIP Reminder End

        //LWD Reminder
        $today = date('Y-m-d');
        $employees = $this->Cron_model->get_employees_lwd();
        // print_r($employees);
        // exit();
        $to = 'shwetha.k@TouchstoneLC.com,pramod.c@TouchstoneLC.com';
        if (empty($employees)) {
            log_message('info', 'No employees with LWD in next 5 days.');
            return;
        }

        $subject = "Reminder: Upcoming Last Working Days (Next 5 Days)";

        $messagefull = "<p>Hi,</p>";
        $messagefull .= "<p>Below are the employees whose last working day is coming up in the next 5 days:</p>";
        $messagefull .= "<table border='1' cellspacing='0' cellpadding='6'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Last Working Day</th>
                                <th>Days Left</th>
                            </tr>
                        </thead>
                        <tbody>";

        $i = 1;
        foreach ($employees as $emp) {
            $days_left = (new \DateTime($emp['LWD']))->diff(new \DateTime($today))->days;
            $messagefull .= "<tr>
                            <td>{$i}</td>
                            <td>{$emp['fname']} {$emp['lname']}</td>
                            <td>{$emp['LWD']}</td>
                            <td>{$days_left}</td>
                         </tr>";
            $i++;
        }

        $messagefull .= "</tbody></table>
                     <br><p>Please take the necessary actions.</p>
                     <br>Regards,<br>
                     <strong>Dochek Team</strong>";

        // ✅ Send a single email
        $email = \Config\Services::email();
        $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($messagefull);

        if ($email->send()) {
            session()->setFlashdata('success', 'Mail Sent.');
        } else {
            $data = $email->printDebugger(['headers']);
            print_r($data);
            exit();
        }


    }
    // function testcron()
    // {

    //     $today = date('Y-m-d');
    //     $five_days_later = date('Y-m-d', strtotime('+4 days'));
    //     $employees = $this->Cron_model->get_employees_lwd($five_days_later);
    //     // print_r($employees);
    //     // exit();
    //     $to = 'srividya.a@touchstonelc.com';
    //     foreach ($employees as $emp) {
    //         $days_left = (new \DateTime($emp['LWD']))->diff(new \DateTime($today))->days;

    //         $subject = "Reminder: Upcoming Last Working Day for " . $emp['fname'] . ' ' . $emp['lname'];
    //         $messagefull = "
    //         <p>Hi,</p>
    //         <p>This is a reminder that <strong>" . $emp['fname'] . ' ' . $emp['lname'] . "</strong>'s last working day is on <strong>" . $emp['LWD'] . "</strong>.</p>
    //         <p>There are <strong>{$days_left}</strong> day(s) left until their last working day.</p>
    //         <p>Please take necessary actions.</p>
    //         <br><br>
    //         Regards,<br>
    //         Dochek Team';
    //     ";
    //         $email = \Config\Services::email();
    //         $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
    //         $email->setTo($to);
    //         $email->setSubject($subject);
    //         $email->setMessage($messagefull);
    //         if ($email->send()) {
    //             session()->setFlashdata('success', 'Mail Sent.');
    //         } else {
    //             $data = $email->printDebugger(['headers']);
    //             print_r($data);
    //             exit();
    //         }
    //     }
    // }
    public function testcron()
    {
        $today = date('Y-m-d');
        $employees = $this->Cron_model->get_employees_lwd();
        // print_r($employees);
        // exit();
        // $to = 'srividya.a@touchstonelc.com';
        $to = 'keerthana.mk@TouchstoneLC.com';
        if (empty($employees)) {
            log_message('info', 'No employees with LWD in next 5 days.');
            return;
        }

        // $to = 'srividya.a@touchstonelc.com'; // HR email address
        $subject = "Reminder: Upcoming Last Working Days & End of Probation Period";

        // Build one email body for all employees
        $messagefull = "<p>Hi,</p>";
        $messagefull .= "<p>Below are the employees whose last working day is coming up in the next 5 days:</p>";
        $messagefull .= "<table border='1' cellspacing='0' cellpadding='6'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Last Working Day</th>
                                <th>Days Left</th>
                            </tr>
                        </thead>
                        <tbody>";

        $i = 1;
        foreach ($employees as $emp) {
            $days_left = (new \DateTime($emp['LWD']))->diff(new \DateTime($today))->days;
            $messagefull .= "<tr>
                            <td>{$i}</td>
                            <td>{$emp['fname']} {$emp['lname']}</td>
                            <td>{$emp['LWD']}</td>
                            <td>{$days_left}</td>
                         </tr>";
            $i++;
        }

        $messagefull .= "</tbody></table>
                     <br><p>Please take the necessary actions.</p>
                     <br>Regards,<br>
                     <strong>Dochek Team</strong>";

        // ✅ Send a single email
        $email = \Config\Services::email();
        $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($messagefull);

        if ($email->send()) {
            session()->setFlashdata('success', 'Mail Sent.');
        } else {
            $data = $email->printDebugger(['headers']);
            print_r($data);
            exit();
        }
    }


}
