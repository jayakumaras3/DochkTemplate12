<?php

namespace App\Controllers\Mobile;

use App\Controllers\BaseController;
use App\Models\Etrack\Attendance_model;
use App\Models\User_login\Login_model;
use App\Models\User_login\Users_model;

#[\AllowDynamicProperties]
class Api extends BaseController
{
    public function __construct()
    {
        $this->login_model = new Login_model();
        $this->users_model = new Users_model();
        $this->attendance_model = new Attendance_model();
    }

    // GET /api/mobile/profile
    public function profile()
    {
        if ($response = $this->requireLogin()) {
            return $response;
        }

        $id_user = session()->get('id_user');

        $userdata = $this->users_model->usersedit_view($id_user);
        $user = $userdata[0] ?? null;

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found.',
            ]);
        }

        $personal = $this->users_model->get_personal_data($id_user);
        $personalData = $personal[0] ?? null;

        $profileImageUrl = null;
        if (!empty($user['profile_image']) && !empty($user['profile_foldername'])) {
            $profileImageUrl = base_url(
                'assets/assets/uploads/profile/' . $id_user . '/' . $user['profile_foldername'] . '/' . $user['profile_image']
            );
        }

        $clientLogoUrl = null;
        if (!empty($user['logo'])) {
            $clientLogoUrl = base_url(
                'assets/assets/uploads/client_logo/' . trim($user['client_id'] ?? $user['id_c'] ?? '') . '/' . trim($user['logo'])
            );
        }

        return $this->response->setJSON([
            'success' => true,
            'user' => [
                'id_user' => $user['id_user'],
                'username' => $user['username'],
                'name' => $user['name'],
                'last_name' => $user['last_name'] ?? null,
                'email' => $user['email'] ?? null,
                'personal_email' => $personalData['personal_mail'] ?? null,
                'profile_image_url' => $profileImageUrl,
                'client_logo_url' => $clientLogoUrl,
                'client_id' => $user['client_id'] ?? null,
            ],
        ]);
    }

    // POST /api/mobile/change-password
    public function changePassword()
    {
        if ($response = $this->requireLogin()) {
            return $response;
        }

        $contentType = strtolower($this->request->getHeaderLine('Content-Type'));
        if (strpos($contentType, 'application/json') === false) {
            return $this->response->setStatusCode(415)->setJSON([
                'success' => false,
                'message' => 'Invalid request format.',
            ]);
        }

        $json = $this->request->getJSON(true);
        if (!$json) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request format.',
            ]);
        }

        $currentPassword = trim($json['current_password'] ?? '');
        $newPassword = trim($json['new_password'] ?? '');

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]|max_length[255]',
        ];

        if (!$this->validateData($json, $rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $id_user = session()->get('id_user');
        $userdata = $this->users_model->usersedit_view($id_user);
        $user = $userdata[0] ?? null;

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ]);
        }

        $newdata = [
            'password' => trim(password_hash($newPassword, PASSWORD_DEFAULT)),
            'last_updated_by' => $id_user,
            'last_updated_on' => time(),
        ];
        $this->users_model->updateUsersData($id_user, $newdata);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    // POST /api/mobile/logout
    public function logout()
    {
        if ($response = $this->requireLogin()) {
            return $response;
        }

        $newdata = [
            'username' => session()->get('username'),
            'exittime' => '',
            'ipaddress' => $this->getClientIpAddress(),
            'dateandtime' => time(),
            'extra' => '',
        ];
        $this->login_model->updateUserActive($newdata);

        session()->destroy();
        unset($_COOKIE['clientid']);
        setcookie('clientid', '', -1, '/');

        return $this->response->setJSON([
            'success' => true,
        ]);
    }

    // GET /api/mobile/wfh-summary
    public function wfhSummary()
    {
        if ($response = $this->requireLogin()) {
            return $response;
        }

        $id_user = session()->get('id_user');
        date_default_timezone_set('Asia/Kolkata');

        $monthOffset = (int) ($this->request->getGet('month_offset') ?? 0);
        if ($monthOffset > 0) {
            $monthOffset = 0;
        }

        $today = date('Y-m-d');
        $prevWorkingDay = $this->previousWorkingDay($id_user);

        $periodStart = $this->cycleStartDate($monthOffset);
        $periodEnd = $this->cycleEndDate($monthOffset);

        $totalWfh = $this->attendance_model->sumWfhForPeriod($id_user, $periodStart, $periodEnd);
        $recentRows = $this->attendance_model->getRecentWfh($id_user, 5);
        $cutoff = date('Y-m-d', strtotime('-5 day'));

        $recent = array_map(function ($row) use ($cutoff) {
            return [
                'et_wfh_id' => $row['et_wfh_id'],
                'start_date' => $row['start_date'],
                'number_wfh' => (float) $row['number_wfh'],
                'can_delete' => $row['start_date'] > $cutoff,
            ];
        }, $recentRows);

        return $this->response->setJSON([
            'success' => true,
            'period' => [
                'start_date' => $periodStart,
                'end_date' => $periodEnd,
                'total_wfh' => $totalWfh,
                'month_offset' => $monthOffset,
            ],
            'today' => [
                'date' => $today,
                'applied' => $this->wfhAppliedOn($id_user, $today),
            ],
            'prev_working_day' => [
                'date' => $prevWorkingDay,
                'applied' => $this->wfhAppliedOn($id_user, $prevWorkingDay),
            ],
            'recent' => $recent,
        ]);
    }

    // POST /api/mobile/wfh-apply  { type: "1"|"2", value: "1"|"0.5" }
    public function wfhApply()
    {
        if ($response = $this->requireLogin()) {
            return $response;
        }

        $contentType = strtolower($this->request->getHeaderLine('Content-Type'));
        if (strpos($contentType, 'application/json') === false) {
            return $this->response->setStatusCode(415)->setJSON([
                'success' => false,
                'message' => 'Invalid request format.',
            ]);
        }

        $json = $this->request->getJSON(true);
        if (!$json) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request format.',
            ]);
        }

        $type = (string) ($json['type'] ?? '');
        $value = (string) ($json['value'] ?? '');

        if (!in_array($type, ['1', '2'], true) || !in_array($value, ['1', '0.5', '.5'], true)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Invalid WFH request.',
            ]);
        }

        $value = (float) $value;
        $id_user = session()->get('id_user');
        date_default_timezone_set('Asia/Kolkata');

        $startDt = $type === '1' ? date('Y-m-d') : $this->previousWorkingDay($id_user);
        $applied = $this->wfhAppliedOn($id_user, $startDt);

        $canApply = 0;
        if ($applied > 0) {
            if ($value == 1 && $applied == 1) {
                $canApply = 0;
            } elseif ($value == .5 && $applied == .5) {
                $canApply = .5;
            }
        } else {
            $canApply = $value;
        }

        if ($canApply <= 0) {
            return $this->response->setStatusCode(409)->setJSON([
                'success' => false,
                'message' => 'Work From Home has already been applied for this day.',
            ]);
        }

        $this->attendance_model->add_wfh_data([
            'emp_id' => $id_user,
            'number_wfh' => $canApply,
            'start_date' => $startDt,
            'last_updated_by' => $id_user,
            'last_updated_on' => time(),
            'status' => 1,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Work From Home applied.',
            'start_date' => $startDt,
        ]);
    }

    // POST /api/mobile/wfh-cancel  { et_wfh_id }
    public function wfhCancel()
    {
        if ($response = $this->requireLogin()) {
            return $response;
        }

        $contentType = strtolower($this->request->getHeaderLine('Content-Type'));
        if (strpos($contentType, 'application/json') === false) {
            return $this->response->setStatusCode(415)->setJSON([
                'success' => false,
                'message' => 'Invalid request format.',
            ]);
        }

        $json = $this->request->getJSON(true);
        $et_wfh_id = $json['et_wfh_id'] ?? null;
        if (!$et_wfh_id) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request format.',
            ]);
        }

        $id_user = session()->get('id_user');
        $row = $this->attendance_model->getWfhById($et_wfh_id);

        if (!$row || (string) $row['emp_id'] !== (string) $id_user || (int) $row['status'] !== 1) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'WFH record not found.',
            ]);
        }

        $cutoff = date('Y-m-d', strtotime('-5 day'));
        if ($row['start_date'] <= $cutoff) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'This record is more than 5 days old and can no longer be cancelled.',
            ]);
        }

        $this->attendance_model->delete_remarks_from_wfh([
            'status' => 0,
            'last_updated_by' => $id_user,
            'last_updated_on' => time(),
        ], $et_wfh_id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'WFH request cancelled.',
        ]);
    }

    private function wfhAppliedOn($id_user, $date)
    {
        $rows = $this->attendance_model->getattendancebydate($id_user, $date);
        return $rows ? (float) ($rows[0]['number_wfh'] ?? 0) : 0.0;
    }

    // Mirrors Etrack\Attendance::add_wfh()'s "type 2" (previous working day) logic:
    // rolls back over holidays and weekends, then re-checks holidays once more.
    private function previousWorkingDay($id_user)
    {
        $country = $id_user == '1124' ? 2 : 1;

        $start_dt = date('Y-m-d', strtotime('-1 day'));
        if ($this->isHoliday($start_dt, $country)) {
            $start_dt = date('Y-m-d', strtotime('-1 day', strtotime($start_dt)));
        }

        $weekday = date('w', strtotime($start_dt));
        if ($weekday === '6') { // Saturday
            $start_dt = date('Y-m-d', strtotime('-1 day', strtotime($start_dt)));
        } elseif ($weekday === '0') { // Sunday
            $start_dt = date('Y-m-d', strtotime('-2 day', strtotime($start_dt)));
        }

        if ($this->isHoliday($start_dt, $country)) {
            $start_dt = date('Y-m-d', strtotime('-1 day', strtotime($start_dt)));
        }

        return $start_dt;
    }

    private function isHoliday($date, $country)
    {
        $holidays = $this->attendance_model->getholidays($date, $date, $country);
        return in_array($date, array_column($holidays, 'holiday_dt'));
    }

    // 26th of previous month to 25th of this month, shifted by $offset months
    // (negative = earlier months; capped at 0 by the caller so it never looks ahead).
    private function cycleStartDate($offset = 0)
    {
        $anchor = new \DateTime('first day of this month');
        $anchor->modify($offset . ' months');
        $prevMonth = (clone $anchor)->modify('-1 day');
        return $prevMonth->format('Y-m') . '-26';
    }

    private function cycleEndDate($offset = 0)
    {
        $anchor = new \DateTime('first day of this month');
        $anchor->modify($offset . ' months');
        return $anchor->format('Y-m') . '-25';
    }

    private function getClientIpAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    }
}
