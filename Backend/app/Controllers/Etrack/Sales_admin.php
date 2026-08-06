<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Sales_model;


#[\AllowDynamicProperties]
class Sales_admin extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Sales_model = new Sales_model();
    }
    private function is_session_available()
    {
        $client = session()->get('client');
        if ($client != 1) {
            header('Location:' . base_url('my_training'));
            exit();
        }
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }
        $arrayuserlevel = explode(',', $userlevel);

        if (in_array('69', $arrayuserlevel) || in_array('68', $arrayuserlevel)) {

        } else {

            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    public function index()
    {
   
        $data = [];
        helper(['form']);

        if (isset($_POST['sales_manager'])) {
            $data['sales_manager'] = $_POST['sales_manager'];
            $_SESSION['sales_manager'] = $_POST['sales_manager'];
        } elseif (isset($_SESSION['sales_manager'])) {
            $data['sales_manager'] = $_SESSION['sales_manager'];
        } else {
            $data['sales_manager'] = 1;
        }
        if (isset($_POST['status'])) {
            $data['status'] = $_POST['status'];
            $_SESSION['sales_status'] = $_POST['status'];
        } elseif (isset($_SESSION['sales_status'])) {
            $data['status'] = $_SESSION['sales_status'];
        } else {
            $data['status'] = 1;
        }

        $data['closed_sales_value'] = $this->Sales_model->get_closed_sales_value($data['sales_manager']);
        $data['all_sales'] = $this->Sales_model->get_sales_data($data['sales_manager'], $data['status']);

        echo view('templates/header_view', $data);
        echo view('etrack/sales/sales_dashboard', $data);
        echo view('templates/footer_view');
    }

    public function create_new_sales()
    {

        helper(['form']);
        $sales_data = [
            'client' => $this->request->getPost('client_name'),
            'sales_manager' => $this->request->getPost('sales_manager'),
            'expected_date' => $this->request->getPost('expected_date'),
            'status' => 1,
            'value' => $this->request->getPost('sales_value'),
            'details' => $this->request->getPost('details'),
            'remarks' => $this->request->getPost('remarks'),
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];
        $sales_id = $this->Sales_model->add_sales_data($sales_data);

        $sales_history_data = [
            'sales_id' => $sales_id,
            'expected_date' => $this->request->getPost('expected_date'),
            'sales_status' => 1,
            'status' => 1,
            'details' => $this->request->getPost('details'),
            'remarks' => $this->request->getPost('remarks'),
            'value' => $this->request->getPost('sales_value'),
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];
        $this->Sales_model->add_sales_history($sales_history_data);
        session()->setFlashdata('success', 'New sales opportunity created successfully.');
        return redirect()->to(base_url('etrack/sales_admin'));
    }

    public function view_sales_details()
    {
        
        helper(['form']);
        if (isset($_POST['sales_id'])) {
            $sales_id = $_POST['sales_id'];
            $_SESSION['sales_id'] = $_POST['sales_id'];
        } elseif (isset($_SESSION['sales_id'])) {
            $sales_id = $_SESSION['sales_id'];
        } else {
            session()->setFlashdata('error', 'Sales ID is required to view details.');
            redirect()->to(base_url('etrack/sales_admin'));
        }

        $data['sales_details'] = $this->Sales_model->get_sales_data_by_id($sales_id);
        $data['sales_id'] = $sales_id;
        $data['sales_history'] = $this->Sales_model->get_sales_history($sales_id);
        echo view('templates/header_view', $data);
        echo view('etrack/sales/sales_details_view', $data);
        echo view('templates/footer_view');
    }

    public function update_sales_details()
    {
       
        helper(['form']);
        $sales_id = $this->request->getPost('sales_id');
        $sales_data = [
            'client' => $this->request->getPost('client'),
            'sales_manager' => $this->request->getPost('sales_manager'),
            'expected_date' => $this->request->getPost('expected_date'),
            'status' => $this->request->getPost('status'),
            'value' => $this->request->getPost('sales_value'),
            'details' => $this->request->getPost('details'),
            'remarks' => $this->request->getPost('remarks'),
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];
        $this->Sales_model->update_sales_data($sales_id, $sales_data);

        // Add to sales history
        $sales_history_data = [
            'sales_id' => $sales_id,
            'expected_date' => $this->request->getPost('expected_date'),
            'sales_status' => $this->request->getPost('status'),
            'details' => $this->request->getPost('details'),
            'remarks' => $this->request->getPost('remarks'),
            'value' => $this->request->getPost('sales_value'),
            'status' => 1,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];
        $this->Sales_model->add_sales_history($sales_history_data);

        session()->setFlashdata('success', 'Sales details updated successfully.');
        return redirect()->to(base_url('etrack/sales_admin/view_sales_details'));
    }

    public function update_sales_row()
    {
        helper(['form']);
        $isAjax = $this->request->isAJAX();

        $sales_id = $this->request->getPost('sales_id');
        $status = $this->request->getPost('status');
        $remarks = $this->request->getPost('remarks');
        $details = $this->request->getPost('details');
        $value = $this->request->getPost('sales_value');
        $expected_date = $this->request->getPost('expected_date');

        if (empty($sales_id) || $status === null || $status === '') {
            $message = 'Sales ID and Status are required.';
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => $message, 'csrfHash' => csrf_hash()]);
            }
            session()->setFlashdata('error', $message);
            return redirect()->to(base_url('etrack/sales_admin'));
        }

        $sales_details = $this->Sales_model->get_sales_data_by_id($sales_id);

        $sales_data = [
            'status' => $status,
            'remarks' => $remarks,
            'details' => $details !== null && $details !== '' ? $details : ($sales_details['details'] ?? null),
            'value' => $value !== null && $value !== '' ? $value : ($sales_details['value'] ?? null),
            'expected_date' => $expected_date !== null && $expected_date !== '' ? $expected_date : ($sales_details['expected_date'] ?? null),
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];
        $result = $this->Sales_model->update_sales_data($sales_id, $sales_data);

        $sales_history_data = [
            'sales_id' => $sales_id,
            'expected_date' => $sales_data['expected_date'],
            'sales_status' => $status,
            'status' => 1,
            'remarks' => $remarks,
            'details' => $sales_data['details'],
            'value' => $sales_data['value'],
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];
        $this->Sales_model->add_sales_history($sales_history_data);

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => $result ? 'OK' : 'error',
                'message' => $result ? 'Sales opportunity updated successfully.' : 'Failed to update sales opportunity.',
                'sales_id' => (int) $sales_id,
                'value' => $sales_data['value'],
                'last_updated_on' => date('Y-M-d', time()),
                'csrfHash' => csrf_hash(),
            ]);
        }

        session()->setFlashdata('success', 'Sales opportunity updated successfully.');
        return redirect()->to(base_url('etrack/sales_admin'));
    }

    public function delete_sales()
    {
       

        helper(['form']);
        $sales_id = $this->request->getPost('sales_id');
        $sales_data = [
            'status' => 0,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];
        $this->Sales_model->update_sales_data($sales_id, $sales_data);

        // Add to sales history
        $sales_history_data = [
            'sales_id' => $sales_id,
            'remarks' => 'Opportunity deleted: ',
            'details' => $sales_data['details'] ?? null,
            'status' => 1,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
        ];
        $this->Sales_model->add_sales_history($sales_history_data);

        session()->setFlashdata('success', 'Sales opportunity deleted successfully.');
        return redirect()->to(base_url('etrack/sales_admin'));
    }
}
