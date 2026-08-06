<?php

namespace App\Controllers\Project_Manage;

use App\Controllers\BaseController;
use App\Models\Project_Manage\PM_Effort_Tracker_Model;

#[\AllowDynamicProperties]

class Effort_Tracker extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->PM_Effort_Tracker_model = new PM_Effort_Tracker_Model();
    }

    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');

        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('7', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    /**
     * Resolves the work-week currently in scope: a posted selection wins and is
     * persisted to the session, otherwise falls back to whatever was previously
     * selected, otherwise the current week.
     */
    private function resolveSelectedWeek(): string
    {
        $posted = $this->request->getPost('weekDate');
        if ($posted) {
            session()->set('selected_week', $posted);
            return $posted;
        }

        return session()->get('selected_week') ?? date('Y-\WW');
    }

    /**
     * Flashes success/error based on $ok and redirects to $to, preserving input.
     */
    private function respondAndRedirect(bool $ok, string $successMsg, string $errorMsg, string $to)
    {
        session()->setFlashdata($ok ? 'success' : 'error', lang($ok ? $successMsg : $errorMsg));
        return redirect()->to(base_url($to))->withInput();
    }

    /**
     * An effort record may only be touched by the person it belongs to or by
     * that person's manager. Without this, any signed-in manager could pass an
     * arbitrary pe_id and edit/delete/approve someone else's entry (IDOR).
     */
    private function canAccessEffort(int $pe_id): bool
    {
        $owner = $this->PM_Effort_Tracker_model->get_effort_owner($pe_id);

        if ($owner === null) {
            return false;
        }

        $currentUserId = (int) session()->get('id_user');

        return (int) $owner['user'] === $currentUserId || (int) $owner['manager'] === $currentUserId;
    }

    public function index()
    {
        $data = [];
        $data['selected_week'] = $this->resolveSelectedWeek();
 
        $user_id = session()->get('id_user');
        $data['projects'] = $this->PM_Effort_Tracker_model->get_projects($user_id);
        $data['effort_data'] = $this->PM_Effort_Tracker_model->get_effort_data($data['selected_week'], $user_id);
        echo view('templates/header_view', $data);
        echo view('project_management/effort/effort_tracker_view', $data);
        echo view('templates/footer_view');
    }

    /**
     * Shared by AddEffort (self) and AddEffort_for_member (manager logging on
     * behalf of a direct report) - they differed only in whose id is recorded
     * and which status/redirect applies.
     */
    private function saveEffort(?string $forUserId, int $status, string $redirectTo)
    {
        $project_id = $this->request->getVar('project_id');
        $ucn = $this->PM_Effort_Tracker_model->get_UCN($project_id);
        $weekDate = $this->request->getVar('weekDate');
        $effort = (float) $this->request->getVar('effort_hours') + (float) $this->request->getVar('effort_minutes');
        $description = $this->request->getVar('description');

        $newdata = [
            'projectid' => $project_id,
            'ucn' => $ucn['ucn'] ?? null,
            'effort' => $effort,
            'work_week' => $weekDate,
            'description' => $description,
            'status' => $status,
            'user' => $forUserId ?? session()->get('id_user'),
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];

        $pe_id = $this->PM_Effort_Tracker_model->add_new_effort($newdata);
        session()->set('selected_week', $weekDate);

        if ($this->request->isAJAX()) {
            $hours = floor($effort);
            $minutes = ($effort - $hours) * 60;

            return $this->response->setJSON([
                'status' => $pe_id !== false ? 'OK' : 'error',
                'message' => lang($pe_id !== false ? 'Effort Data Added Successfully' : 'Failed to Add Effort Data'),
                'pe_id' => $pe_id !== false ? (int) $pe_id : null,
                'projectid' => (int) $project_id,
                'effort' => $effort,
                'formatted_time' => sprintf('%d:%02d', $hours, $minutes),
                'description' => esc($description),
                'work_week' => $weekDate,
                'csrfHash' => csrf_hash(),
            ]);
        }

        return $this->respondAndRedirect($pe_id !== false, 'Effort Data Added Successfully', 'Failed to Add Effort Data', $redirectTo);
    }

    public function AddEffort_for_member()
    {
        return $this->saveEffort(
            $this->request->getVar('member_id'),
            2,
            'Project_Manage/Effort_Tracker/view_member_effort'
        );
    }

    public function AddEffort()
    {
        return $this->saveEffort(null, 1, 'Project_Manage/Effort_Tracker');
    }

    public function RequestAccess()
    {
        $data = [];
        $data['projects'] = $this->PM_Effort_Tracker_model->get_active_projects();
        $data['projects_with_access'] = $this->PM_Effort_Tracker_model->get_projects_with_access(session()->get('id_user'));
        echo view('templates/header_view', $data);
        echo view('project_management/effort/request_access_view', $data);
        echo view('templates/footer_view');
    }

    public function AddRequest()
    {
        $project_id = $this->request->getVar('project_id');
        $description = $this->request->getVar('description');
        $newdata = [
            'project_id' => $project_id,
            'user_id' => session()->get('id_user'),
            'description' => $description,
            'status' => 2, // Pending
        ];

        $this->PM_Effort_Tracker_model->add_project_access_request($newdata);
        session()->setFlashdata('success', 'Project access request submitted successfully.');
        return redirect()->to(base_url() . 'Project_Manage/Effort_Tracker/RequestAccess');
    }

    public function Delete_request()
    {
        $pu_id = $this->request->getPost('pu_id');

        if ($pu_id === null) {
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/RequestAccess'))->withInput();
        }

        $result = $this->PM_Effort_Tracker_model->delete_project_access_request($pu_id);

        return $this->respondAndRedirect($result, 'Project access request deleted successfully.', 'Failed to delete project access request.', 'Project_Manage/Effort_Tracker/RequestAccess');
    }

    public function Approve_access()
    {
        $data = [];
        if ($response =  $this->requireRole(['4'])) {
            return $response;
        }
        $data['access_requests'] = $this->PM_Effort_Tracker_model->get_pending_access_requests();
        $data['my_active_projects'] = $this->PM_Effort_Tracker_model->get_my_active_projects(session()->get('id_user'));
        echo view('templates/header_view', $data);
        echo view('project_management/effort/approve_access_view', $data);
        echo view('templates/footer_view');
    }

    /**
     * A project manager approves/rejects a pending project_user access request.
     * Restricted to requests against projects the current user actually
     * manages, mirroring the filtering already done for the Approve_access
     * listing - otherwise any role-4 user could pass an arbitrary pu_id.
     */
    public function pm_response()
    {
        if ($response = $this->requireRole(['4'])) {
            return $response;
        }

        $pu_id = $this->request->getPost('pu_id');
        $status = $this->request->getPost('status');
        $returnurl = $this->request->getPost('returnurl');
        $isAjax = $this->request->isAJAX();

        if ($pu_id === null || $status === null || !in_array((int) $status, [1, 3], true)) {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => lang('Data cannot be fetched.'), 'csrfHash' => csrf_hash()]);
            }
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            if ($returnurl === '1') {
                return redirect()->to(base_url('Project_Manage/Effort_Tracker/Approve_access'))->withInput();
            } else {
                return redirect()->to(base_url('Project_Manage/Effort_Tracker/PM_Project_Effort'))->withInput();
            }
        }

        $accessRequest = $this->PM_Effort_Tracker_model->get_access_request_by_id((int) $pu_id);
        $myProjectIds = array_map('intval', array_column($this->PM_Effort_Tracker_model->get_my_active_projects(session()->get('id_user')), 'projectid'));

        if ($accessRequest === null || !in_array((int) $accessRequest['project_id'], $myProjectIds, true)) {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => lang('You do not have permission to update this request.'), 'csrfHash' => csrf_hash()]);
            }
            session()->setFlashdata('error', lang('You do not have permission to update this request.'));
            if ($returnurl === '1') {
                return redirect()->to(base_url('Project_Manage/Effort_Tracker/Approve_access'))->withInput();
            } else {
                return redirect()->to(base_url('Project_Manage/Effort_Tracker/PM_Project_Effort'))->withInput();
            }
        }

        $result = $this->PM_Effort_Tracker_model->update_project_user_status((int) $pu_id, (int) $status);

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => $result ? 'OK' : 'error',
                'message' => lang($result ? 'Request Updated Successfully' : 'Failed to Update Request'),
                'pu_id' => (int) $pu_id,
                'csrfHash' => csrf_hash(),
            ]);
        }
        if ($returnurl === '1') {
            return $this->respondAndRedirect($result, 'Request Updated Successfully', 'Failed to Update Request', 'Project_Manage/Effort_Tracker/Approve_access');
        } else {
            return $this->respondAndRedirect($result, 'Request Updated Successfully', 'Failed to Update Request', 'Project_Manage/Effort_Tracker/PM_Project_Effort');
        }
       // return $this->respondAndRedirect($result, 'Request Updated Successfully', 'Failed to Update Request', 'Project_Manage/Effort_Tracker/Approve_access');
    }

    /**
     * Bulk-approves every pending (status 2) project access request passed in
     * from the "Project Access Requests" table on Approve_access. Only
     * requests against projects the current user actually manages are
     * approved - re-checked per row here (rather than trusting the pu_ids
     * from the client), mirroring pm_response.
     */
    public function pm_bulk_approve()
    {
        if ($response = $this->requireRole(['4'])) {
            return $response;
        }

        $pu_ids = $this->request->getPost('pu_ids');
        $isAjax = $this->request->isAJAX();

        if (empty($pu_ids) || !is_array($pu_ids)) {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => lang('Data cannot be fetched.'), 'csrfHash' => csrf_hash()]);
            }
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/Approve_access'))->withInput();
        }

        $currentUserId = (int) session()->get('id_user');
        $myProjectIds = array_map('intval', array_column($this->PM_Effort_Tracker_model->get_my_active_projects($currentUserId), 'projectid'));
        $approved = 0;
        $skipped = 0;

        foreach ($pu_ids as $pu_id) {
            $pu_id = (int) $pu_id;
            $accessRequest = $this->PM_Effort_Tracker_model->get_access_request_by_id($pu_id);

            if ($accessRequest === null || (int) $accessRequest['status'] !== 2 || !in_array((int) $accessRequest['project_id'], $myProjectIds, true)) {
                $skipped++;
                continue;
            }

            if ($this->PM_Effort_Tracker_model->update_project_user_status($pu_id, 1)) {
                $approved++;
            } else {
                $skipped++;
            }
        }

        $message = $approved . ' record(s) approved successfully.';
        if ($skipped > 0) {
            $message .= ' ' . $skipped . ' record(s) could not be approved because they were no longer pending or are not yours to approve.';
        }

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'OK',
                'approved' => $approved,
                'skipped' => $skipped,
                'message' => $message,
                'csrfHash' => csrf_hash(),
            ]);
        }

        session()->setFlashdata($approved > 0 ? 'success' : 'error', $message);
        return redirect()->to(base_url('Project_Manage/Effort_Tracker/Approve_access'));
    }

    public function mng_response()
    {
        $pe_id = $this->request->getPost('pe_id');
        $status = $this->request->getPost('status');
        $isAjax = $this->request->isAJAX();

        if ($pe_id === null || $status === null) {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => lang('Data cannot be fetched.'), 'csrfHash' => csrf_hash()]);
            }
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/Team_data'))->withInput();
        }

        if (!in_array((int) $status, [1, 2, 3], true) || !$this->canAccessEffort((int) $pe_id)) {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => lang('You do not have permission to update this effort entry.'), 'csrfHash' => csrf_hash()]);
            }
            session()->setFlashdata('error', lang('You do not have permission to update this effort entry.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/Team_data'))->withInput();
        }

        $updatedData = [
            'status' => $status,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];

        $result = $this->PM_Effort_Tracker_model->update_effort((int) $pe_id, $updatedData);

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => $result ? 'OK' : 'error',
                'message' => lang($result ? 'Effort Data Updated Successfully' : 'Failed to Update Effort Data'),
                'pe_id' => (int) $pe_id,
                'csrfHash' => csrf_hash(),
            ]);
        }

        return $this->respondAndRedirect($result, 'Effort Data Updated Successfully', 'Failed to Update Effort Data', 'Project_Manage/Effort_Tracker/Team_data');
    }

    /**
     * Bulk-approves every pending (status 1) effort entry passed in from the
     * "Items for Approval" table on Team_data. Only entries whose owner
     * actually reports to the current user are approved - re-checked per row
     * here (rather than trusting the pe_ids from the client) since the list
     * came from a page that may be stale by the time this is submitted.
     */
    public function mng_bulk_approve()
    {
        $pe_ids = $this->request->getPost('pe_ids');
        $isAjax = $this->request->isAJAX();

        if (empty($pe_ids) || !is_array($pe_ids)) {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => lang('Data cannot be fetched.'), 'csrfHash' => csrf_hash()]);
            }
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/Team_data'))->withInput();
        }

        $currentUserId = (int) session()->get('id_user');
        $approved = 0;
        $skipped = 0;

        foreach ($pe_ids as $pe_id) {
            $pe_id = (int) $pe_id;
            $owner = $this->PM_Effort_Tracker_model->get_effort_owner($pe_id);

            if ($owner === null || (int) $owner['manager'] !== $currentUserId) {
                $skipped++;
                continue;
            }

            $existing = $this->PM_Effort_Tracker_model->get_effort_data_by_id($pe_id);
            $existing = $existing[0] ?? null;

            if ($existing === null || (int) $existing['status'] !== 1) {
                $skipped++;
                continue;
            }

            $updatedData = [
                'status' => 2,
                'last_updated_on' => time(),
                'last_updated_by' => $currentUserId,
            ];

            if ($this->PM_Effort_Tracker_model->update_effort($pe_id, $updatedData)) {
                $approved++;
            } else {
                $skipped++;
            }
        }

        $message = $approved . ' record(s) approved successfully.';
        if ($skipped > 0) {
            $message .= ' ' . $skipped . ' record(s) could not be approved because they were no longer pending or are not yours to approve.';
        }

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'OK',
                'approved' => $approved,
                'skipped' => $skipped,
                'message' => $message,
                'csrfHash' => csrf_hash(),
            ]);
        }

        session()->setFlashdata($approved > 0 ? 'success' : 'error', $message);
        return redirect()->to(base_url('Project_Manage/Effort_Tracker/Team_data'));
    }

    /**
     * Inline (AJAX) edit of effort hours + description from the effort tracker
     * table. Restricted to entries that are still Active (status 1) and within
     * the current/previous work week - the same window the table already uses
     * to decide whether the Delete button is shown.
     */
    public function Update_Effort_Ajax()
    {
        $pe_id = $this->request->getPost('pe_id');

        if ($pe_id === null) {
            return $this->response->setJSON(['status' => 'error', 'message' => lang('Data cannot be fetched.'), 'csrfHash' => csrf_hash()]);
        }

        if (!$this->canAccessEffort((int) $pe_id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => lang('You do not have permission to update this effort entry.'), 'csrfHash' => csrf_hash()]);
        }

        $existing = $this->PM_Effort_Tracker_model->get_effort_data_by_id((int) $pe_id);
        $existing = $existing[0] ?? null;

        $editableWeeks = [date('Y-\WW'), date('Y-\WW', strtotime('-1 week'))];

        if ($existing === null || (int) $existing['status'] !== 1 || !in_array($existing['work_week'], $editableWeeks, true)) {
            return $this->response->setJSON(['status' => 'error', 'message' => lang('This effort entry can no longer be edited.'), 'csrfHash' => csrf_hash()]);
        }

        $effort = (float) $this->request->getPost('effort_hours') + (float) $this->request->getPost('effort_minutes');
        $description = $this->request->getPost('description');

        if ($effort < 0 || $effort > 40) {
            return $this->response->setJSON(['status' => 'error', 'message' => lang('Failed to Update Effort Data'), 'csrfHash' => csrf_hash()]);
        }

        $updatedData = [
            'effort' => $effort,
            'description' => $description,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];

        $result = $this->PM_Effort_Tracker_model->update_effort((int) $pe_id, $updatedData);

        $hours = floor($effort);
        $minutes = ($effort - $hours) * 60;

        return $this->response->setJSON([
            'status' => $result ? 'OK' : 'error',
            'message' => lang($result ? 'Effort Data Updated Successfully' : 'Failed to Update Effort Data'),
            'pe_id' => (int) $pe_id,
            'effort' => $effort,
            'formatted_time' => sprintf('%d:%02d', $hours, $minutes),
            'description' => esc($description),
            'csrfHash' => csrf_hash(),
        ]);
    }

    public function Edit_effort()
    {
        $data = [];
        $pe_id = $this->request->getPost('pe_id');

        if ($pe_id === null) {
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker'))->withInput();
        }

        if (!$this->canAccessEffort((int) $pe_id)) {
            session()->setFlashdata('error', lang('You do not have permission to edit this effort entry.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker'))->withInput();
        }

        $data['pe_id'] = $pe_id;
        $data['effort_data'] = $this->PM_Effort_Tracker_model->get_effort_data_by_id((int) $pe_id);
        $user = session()->get('id_user');
        $data['projects'] = $this->PM_Effort_Tracker_model->get_projects($user);
        echo view('templates/header_view');
        echo view('project_management/effort/edit_effort_view', $data);
        echo view('templates/footer_view');
    }

    public function Delete_effort()
    {
        $pe_id = $this->request->getPost('pe_id');

        if ($pe_id === null) {
            session()->setFlashdata('error', lang('Data cannot be fetched'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker'))->withInput();
        }

        if (!$this->canAccessEffort((int) $pe_id)) {
            session()->setFlashdata('error', lang('You do not have permission to delete this effort entry.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker'))->withInput();
        }

        $updatedData = [
            'status' => 0,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];

        $result = $this->PM_Effort_Tracker_model->update_effort((int) $pe_id, $updatedData);

        return $this->respondAndRedirect($result, 'Effort Data Deleted Successfully', 'Failed to Delete Effort Data', 'Project_Manage/Effort_Tracker');
    }

    public function Update_Effort()
    {
        $pe_id = $this->request->getPost('pe_id');

        if ($pe_id === null) {
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker'))->withInput();
        }

        if (!$this->canAccessEffort((int) $pe_id)) {
            session()->setFlashdata('error', lang('You do not have permission to update this effort entry.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker'))->withInput();
        }

        $project_id = $this->request->getVar('project_id');
        $ucn = $this->PM_Effort_Tracker_model->get_UCN($project_id);

        $updatedData = [
            'projectid' => $project_id,
            'ucn' => $ucn['ucn'] ?? null,
            'effort' => $this->request->getVar('effort_hours') + $this->request->getVar('effort_minutes'),
            'work_week' => $this->request->getVar('weekDate'),
            'description' => $this->request->getVar('description'),
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];

        $result = $this->PM_Effort_Tracker_model->update_effort((int) $pe_id, $updatedData);

        return $this->respondAndRedirect($result, 'Effort Data Updated Successfully', 'Failed to Update Effort Data', 'Project_Manage/Effort_Tracker');
    }

    public function PM_Project_Effort()
    {
        $data = [];

        $posted = $this->request->getPost('projectid');
        if ($posted) {
            $data['projectid'] = $posted;
            session()->set('selected_project', $posted);
        } elseif (session()->get('selected_project')) {
            $data['projectid'] = session()->get('selected_project');
        } else {
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/PM_ucn/edit_ucn'))->withInput();
        }

        $data['effort_data'] = $this->PM_Effort_Tracker_model->get_effort_data_by_project($data['projectid']);
        $data['users_by_project'] = $this->PM_Effort_Tracker_model->get_users_by_project($data['projectid']);

        $data['all_users'] = $this->PM_Effort_Tracker_model->get_all_users();
        echo view('templates/header_view');
        echo view('project_management/effort/project_effort_view', $data);
        echo view('templates/footer_view');
    }

    public function View_User_Effort()
    {
        $data = [];

        if (session()->get('selected_project')) {
            $data['projectid'] = session()->get('selected_project');
        } else {
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/PM_Project_Effort'))->withInput();
        }

        $user_id = $this->request->getPost('user_id');
        if ($user_id === null) {
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/PM_Project_Effort'))->withInput();
        }
        $data['user_id'] = $user_id;

        $data['user_information'] = $this->PM_Effort_Tracker_model->get_user_info($data['user_id']);
        $data['user_data'] = $this->PM_Effort_Tracker_model->view_user_effort_by_project($data['user_id'], $data['projectid']);

        echo view('templates/header_view');
        echo view('project_management/effort/employee_data_view', $data);
        echo view('templates/footer_view');
    }

    /**
     * A project manager (role 4) rejects an employee's effort entry from the
     * per-user project view (employee_data_view). Uses a distinct status (4)
     * from the direct manager's own reject (3) so the two rejection paths
     * stay distinguishable. Restricted to projects the PM actually manages,
     * mirroring pm_response, so an arbitrary role-4 user can't reject entries
     * on projects they don't own (IDOR).
     */
    public function Reject_effort_by_pm()
    {
        if ($response = $this->requireRole(['4'])) {
            return $response;
        }

        $pe_id = $this->request->getPost('pe_id');
        $isAjax = $this->request->isAJAX();

        if ($pe_id === null) {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => lang('Data cannot be fetched.'), 'csrfHash' => csrf_hash()]);
            }
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/PM_Project_Effort'))->withInput();
        }

        $effort = $this->PM_Effort_Tracker_model->get_effort_data_by_id((int) $pe_id);
        $effort = $effort[0] ?? null;
        $myProjectIds = array_map('intval', array_column($this->PM_Effort_Tracker_model->get_my_active_projects(session()->get('id_user')), 'projectid'));

        if ($effort === null || !in_array((int) $effort['projectid'], $myProjectIds, true)) {
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => lang('You do not have permission to update this effort entry.'), 'csrfHash' => csrf_hash()]);
            }
            session()->setFlashdata('error', lang('You do not have permission to update this effort entry.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/PM_Project_Effort'))->withInput();
        }

        $comment = substr((string) $this->request->getPost('comment'), 0, 50);

        $updatedData = [
            'status' => 4,
            'pm_comment' => $comment,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];

        $result = $this->PM_Effort_Tracker_model->update_effort((int) $pe_id, $updatedData);

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => $result ? 'OK' : 'error',
                'message' => lang($result ? 'Effort Data Updated Successfully' : 'Failed to Update Effort Data'),
                'pe_id' => (int) $pe_id,
                'csrfHash' => csrf_hash(),
            ]);
        }

        return $this->respondAndRedirect($result, 'Effort Data Updated Successfully', 'Failed to Update Effort Data', 'Project_Manage/Effort_Tracker/PM_Project_Effort');
    }

    public function Add_user_to_project()
    {
        $user_ids = $this->request->getPost('users');
        $project_id = $this->request->getPost('project_id');

        if (empty($user_ids) || $project_id === null) {
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/PM_Project_Effort'))->withInput();
        }

        $result = $this->PM_Effort_Tracker_model->add_user_to_project((array) $user_ids, $project_id);

        return $this->respondAndRedirect($result, 'User Added to Project Successfully', 'Failed to Add User to Project', 'Project_Manage/Effort_Tracker/PM_Project_Effort');
    }

    public function Remove_user_from_project()
    {
        $pu_id = $this->request->getPost('pu_id');

        if ($pu_id === null) {
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/PM_Project_Effort'))->withInput();
        }

        $result = $this->PM_Effort_Tracker_model->remove_user_from_project($pu_id);

        return $this->respondAndRedirect($result, 'User Removed from Project Successfully', 'Failed to Remove User from Project', 'Project_Manage/Effort_Tracker/PM_Project_Effort');
    }

    public function Team_data()
    {
        $data = [];
        $data['selected_week'] = $this->resolveSelectedWeek();

        $user_id = session()->get('id_user');

        $data['my_team'] = $this->PM_Effort_Tracker_model->get_my_team($user_id, $data['selected_week']);
        $data['effort_data'] = $this->PM_Effort_Tracker_model->get_team_effort_data($user_id);

        echo view('templates/header_view', $data);
        echo view('project_management/effort/team_effort_view', $data);
        echo view('templates/footer_view');
    }

    public function All_data()
    {
        $data = [];
        $data['selected_week'] = $this->resolveSelectedWeek();

        $data['my_team'] = $this->PM_Effort_Tracker_model->get_all_data($data['selected_week']);
        $data['weekly_totals'] = $this->PM_Effort_Tracker_model->get_weekly_effort_totals();

        echo view('templates/header_view', $data);
        echo view('project_management/effort/all_effort_view', $data);
        echo view('templates/footer_view');
    }

    public function view_member_effort()
    {
        $data = [];

        $posted_member = $this->request->getPost('member_id');
        if ($posted_member) {
            $member_id = $posted_member;
            session()->set('selected_member', $member_id);
        } elseif (session()->get('selected_member')) {
            $member_id = session()->get('selected_member');
        } else {
            session()->setFlashdata('error', lang('Data cannot be fetched.'));
            return redirect()->to(base_url('Project_Manage/Effort_Tracker/Team_data'))->withInput();
        }

        $data['selected_week'] = $this->resolveSelectedWeek();

        $data['projects'] = $this->PM_Effort_Tracker_model->get_projects($member_id);
        $data['effort_data'] = $this->PM_Effort_Tracker_model->get_effort_data($data['selected_week'], $member_id);

        $data['member_info'] = $this->PM_Effort_Tracker_model->get_member_info($member_id);
        $data['weekly_totals'] = $this->PM_Effort_Tracker_model->get_member_weekly_totals($member_id, 10);

        echo view('templates/header_view', $data);
        echo view('project_management/effort/member_effort_view', $data);
        echo view('templates/footer_view');
    }
}
