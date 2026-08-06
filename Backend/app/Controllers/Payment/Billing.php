<?php

namespace App\Controllers\Payment;

use App\Controllers\BaseController;

use App\Models\Payment\Billing_model;
use App\Models\Payment\Pricing_model;
use App\Models\Users_model;
use Dompdf\Dompdf;
use Dompdf\Options;


class Billing extends BaseController
{
    private $db;

    public function __construct()
    {
        // $this->is_session_available();
        $this->billing_model = new Billing_model();
        $this->pricing_model = new Pricing_model();
        // $this->billing_model = new Billing_model();
    }
    // private function is_session_available()
    // {
    //     $userlevel = session()->get('userlevel');
    //     if (empty($userlevel)) {
    //         header('Location:' . base_url('my_training'));
    //         exit();
    //     }

    //     $arrayuserlevel = explode(',', $userlevel);
    //     if (!in_array('8', $arrayuserlevel) && !in_array('3', $arrayuserlevel)) {
    //         session()->setFlashdata('message', lang('Messages.Error_0004'));
    //         header('Location:' . base_url('my_training'));
    //         exit();
    //     }
    // }
    public function index()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'My Billing';

        $id_user = session()->get('id_user');
        $username = session()->get('username');
        $partner_code = session()->get('partner_code');
        // $scourse_id =  $_POST['scourse_id'];
        // $data['billing_cycle']  =  $_POST['billing_cycle'];
        $data['price']  =  $_POST['price'];
        // print_r($partner_code);
        // exit();
        $data['priceDetails'] = $this->pricing_model->getPriceDetails();
        // print_r($data['priceDetails']);
        // exit();
        $data['myBilling'] = $this->billing_model->getMyBilling($id_user);
        // $data['discount'] = $this->billing_model->getDiscount($username);
        // $data['partnerDiscount'] = $this->billing_model->getPartnerDiscount($partner_code);
        $data['menu'] = 'Profile';
        $data['submenu'] = 'Payment/billing';
        echo view('templates/header_view', $data);
        echo view('payment/billing/billing_user_view');
        echo view('templates/footer_view');
    }
    public function subscribe()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        $currentDate = new \DateTime();
        // Add one month to the current date
        $currentDate->add(new \DateInterval('P1M'));
        // Format the expiration date as a string
        $expire_date = $currentDate->format('Y-m-d');
        // $expire_date = 
        $newdata = [
            'user_id' => $this->request->getVar('user_id'),
            'amount' => $this->request->getVar('price') / 12,
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),

        ];
        $result = $this->billing_model->addSubscribe($newdata);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0036'));
            return redirect()->to(base_url('Payment/billing'));
        } else {
            session()->setFlashdata('message', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url('Payment/billing'));
        }
    }
    function viewBillingDetails()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }

        $data = [];
        $id_user = session()->get('id_user');
        $data['myBilling'] = $this->billing_model->getMyBilling($id_user);
        echo view('templates/header_view', $data);
        echo view('billing/billing_invoice_view');
        echo view('templates/footer_view');
    }

    function delete_billing()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        if ($this->request->getPost()) {
            $bill_id = $this->request->getVar('bill_id');
            $newdata = [
                'status' => 0,
                'expire_date' => '2024-12-30',
                
                 
                'last_updated_on' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->billing_model->deleteSubscribe($newdata, $bill_id);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0037'));
                return redirect()->to(base_url('Payment/billing'));
            } else {
                session()->setFlashdata('message', 'Data not updated!');
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url('Payment/billing'));
            }
        }
    }
    function applyCoupon()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $id_user = session()->get('id_user');
        if ($this->request->getPost()) {
            $coupon_code = $this->request->getVar('coupon_code');
            $postdata = $this->users_model->getAllpartnercode($coupon_code);
            if (!empty($postdata)) {
                $result = $this->billing_model->updateClientByCoupon($postdata);
                if ($result) {
                    $newdata['id_user'] = session()->get('id_user');
                    $newdata['activity_type'] = 'Applied Coupon';
                    $newdata['details'] = $coupon_code;
                    $newdata['timestamp'] = time();
                    $this->users_model->savelogdata($newdata);
                    session()->setFlashdata('success', lang('Messages.Success_0038'));
                    return redirect()->to(base_url('Payment/billing'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url('Payment/billing'));
                }
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url('Payment/billing'));
            }
        }
    }
    function purchase_history()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $userId = session()->get('id_user');  // or passed as param
        $data['purchasedCourses'] = $this->billing_model->getUserPurchasedCourses($userId);
        echo view('templates/header_view', $data);
        echo view('payment/billing/purchase_hsitory_view');
        echo view('templates/footer_view');
    }
    public function generateInvoicepdf($paymentId)
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        $data['payment'] = $this->billing_model->getInvoicedata($paymentId);
        // print_r($payment);
        // exit();

        if (!$data['payment']) {
            // return redirect()->back()->with('error', 'Payment not found');
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('Payment/Billing/purchase_history'));
        }

        // Generate Invoice Number
        $data['invoice_no'] = 'INV-' . date('Ymd') . '-' . $paymentId;

        // Load HTML view
        $html = view('payment/billing/billing_invoice_pdf', $data);

        // Dompdf setup
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Download PDF
        return $this->response
            ->setContentType('application/pdf')
            ->setBody($dompdf->output());
    }
}
