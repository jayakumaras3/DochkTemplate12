<?php

namespace App\Controllers\Demo;

use App\Controllers\BaseController;

use App\Models\Demo\Demos_model;

#[\AllowDynamicProperties]
class Demos extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->demos_model = new Demos_model();
    }
    public function index()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['uidc'] = $this->request->getVar('Yusdd');
        $uidc =  $data['uidc'];
        $data['uaccess_code'] = $this->request->getVar('jdick18');
        $access_code = $this->request->getVar('access_code');
        $uid = $this->my_simple_crypt($uidc, 'd');
        if (isset($_POST['access_check'])) {
            if (empty($access_code)) {
                $data['comment'] = 'Please enter the App Username !';
            } else {
                $getMycart = $this->demos_model->getMycartData($access_code, $uid);
                if (isset($getMycart)) {
                    $maccess_code = isset($getMycart['access_code']) ? $getMycart['access_code'] : '';
                    if (!empty($maccess_code) && $getMycart['diff']->format("%R%a") <= -1) {
                        $data['comment'] = 'Demos expired please contact admininstrator !';
                    }
                    if ($maccess_code == $access_code) {
                        if ($getMycart['diff']->format("%R%a") >= -1) {
                            return redirect()->to(base_url('category_schedule?us43k=' . $this->my_simple_crypt($getMycart['uid'], 'e') . '&jdick18=' . $this->my_simple_crypt($getMycart['access_code'], 'e')));
                        }
                    } else if ($maccess_code != $access_code) {
                        $data['comment'] = 'Please enter the correct App Username !';
                    } else {
                        return redirect()->to(base_url('Demo/demos?Yusdd=' . $uidc . '&jdick18=' . $access_code));
                    }
                }
            }
        }
        echo view('demos/access_login_view', $data);
    }
    function my_simple_crypt($string, $action = 'e')
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        // you may change these values to your own
        $secret_key = 'my_simple_secret_key';
        $secret_iv = 'my_simple_secret_iv';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
    public function view_category()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $data['searchval'] = $this->request->getVar();
        // print_r( $data['searchval']);
        if (isset($data['searchval'])) {
            $searchval = $data['searchval'];
        } else {
            $searchval = 1;
        }
        $data['tqDemoAccess'] = $this->demos_model->tqDemoUsers();
        // print_r($data['tqDemoAccess']);
        $getallcat_vc = $this->demos_model->getallcat_vc($searchval);
        $data['postval'] = isset($getallcat_vc['postval']) ? $getallcat_vc['postval'] : '';
        $data['getallcat_vc'] = isset($getallcat_vc['showactiveprojects_vc']) ? $getallcat_vc['showactiveprojects_vc'] : '';
        $data['getsearchallcat_vc'] = isset($getallcat_vc['searchactiveprojects_vc']) ? $getallcat_vc['searchactiveprojects_vc'] : '';


        // print_r($data['getsearchallcat_vc']);
        // exit();
        // $data['getval1_vc'] =  $this->demos_model->getval1_vc($demoid, $typeofval, $dbnum);
        echo view('templates/header_view', $data);
        echo view('demos/category_view', $data);
        echo view('templates/footer_view');
    }
    function demo_categories()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $data['getcat'] = $this->demos_model->getcatdata();
        echo view('templates/header_view', $data);
        echo view('demos/demo_categories_view', $data);
        echo view('templates/footer_view');
    }
    function addcategoryval()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        if (isset($_POST['addcategoryval'])) {

            $category = $_POST['category'];
            $data['getcat'] = $this->demos_model->getcatdata();
            $result = $this->demos_model->addcategoryval($category);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url('demos/demo_categories'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                return redirect()->to(base_url('demos/demo_categories'));
            }
            echo view('templates/header_view', $data);
            echo view('demos/demo_categories_view', $data);
            echo view('templates/footer_view');
        }
    }
    function typevalitem()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $user = session()->get('username');
        if (isset($_POST['valid'])) {
            $data['valid'] = $_POST['valid'];
        } else if (isset($_GET['valid'])) {
            $data['valid'] = $_GET['valid'];
        } else {
            $data['valid'] = 1;
        }
        $data['get'] = $this->demos_model->getdata($data['valid']);
        $data['getcatitem'] = $this->demos_model->getcatitemdata($data['valid']);
        echo view('templates/header_view', $data);
        echo view('demos/categories_typevalitem_view', $data);
        echo view('templates/footer_view');
    }
    function additemval()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data['valid'] = $_POST['valid'];
        $categoryitem = $_POST['categoryitem'];
        $result = $this->demos_model->additemval($data['valid'], $categoryitem);
        $data['get'] = $this->demos_model->getdata($data['valid']);
        $data['getcatitem'] = $this->demos_model->getcatitemdata($data['valid']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
            return redirect()->to(base_url('demos/typevalitem'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url('demos/typevalitem'));
        }
        echo view('templates/header_view', $data);
        echo view('demos/categories_typevalitem_view', $data);
        echo view('templates/footer_view');
    }
    function delcatitem()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $mainvalid = $_POST['mainvalid'];
        $data['valid'] = $_POST['valid'];
        //print_r($data['valid']);
        //exit();
        $data['get'] = $this->demos_model->getdata($data['valid']);
        $data['getcatitem'] = $this->demos_model->getcatitemdata($data['valid']);
        $result = $this->demos_model->delcatitem($data['valid']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
            return redirect()->to(base_url('demos/typevalitem'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url('demos/typevalitem'));
        }
        echo view('templates/header_view', $data);
        echo view('demos/categories_typevalitem_view', $data);
        echo view('templates/footer_view');
    }

    public function mycart()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $username = session()->get('username');
        //$data['showmycart'] = $this->demos_model->showmycart();
        echo view('templates/header_view', $data);
        echo view('demos/mycart_view', $data);
        echo view('templates/footer_view');
    }
    public function featured()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $username = session()->get('username');
        $catid =  $this->request->getVar('cat');
        $tagid =  $this->request->getVar('tag');
        $data['tqDemoAccess'] = $this->demos_model->tqDemoUsers();
        $data['featuredData'] = $this->demos_model->getfeatured_vc($username, $catid, $tagid);
        echo view('demos/featured_view', $data);
    }
    public function popup()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $demoid =  $this->request->getVar('demoid');
        $data['result'] = $this->demos_model->showdetails($demoid);
        echo view('demos/popup_view', $data);
    }
    public function popup_vid()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $data['demoid'] =  $this->request->getVar('demoid');
        $data['filename'] =  $this->request->getVar('filename');
        echo view('demos/popup_vid_view', $data);
    }
    public function ajaxsubmit()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        if (!empty($_POST['cart_button'])) {
            if ($_POST['cart_button'] == 'add') {
                $data['udemoid'] = $_POST['unique_ids'];
                $data['product_name'] = $_POST['product_name'];
                $data['description'] = $_POST['description'];
                $data['case_study'] = $_POST['pdf'];
                $data['video_one'] = $_POST['video_one'];
                $data['video_launch'] = $_POST['video_two'];
                $data['course_link'] = $_POST['course_link'];
                $returncount = $this->demos_model->productByCode($data);
                echo json_encode($returncount);
            }
        }
    }
    function report()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $data['showreport'] = $this->demos_model->showreport();
        echo view('templates/header_view', $data);
        echo view('demos/demo_report_view', $data);
        echo view('templates/footer_view');
    }
    function view_report_schedule()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $uid = $_GET['us43k'];
        $data['auid'] = $this->my_simple_crypt($_GET['us43k'], 'd');
        $enc = $_GET['jdick18'];
        $getallcat = $this->demos_model->getallcat($uid, $enc);
        $data['postval'] = isset($getallcat['postval']) ? $getallcat['postval'] : '';
        $data['getallcat'] = isset($getallcat['showactiveprojects_vc']) ? $getallcat['showactiveprojects_vc'] : '';
        //print_r($data['getallcat']);
        //exit();
        echo view('templates/header_view', $data);
        echo view('demos/report_schedule_view', $data);
        echo view('templates/footer_view');
    }
    function edit_date()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $data['cart_id'] = $this->request->getVar('cart_id');
        $data['get_cart_details'] = $this->demos_model->get_cart_details($data['cart_id']);
        echo view('templates/header_view', $data);
        echo view('demos/edit_date_view', $data);
        echo view('templates/footer_view');
    }
    function updatecartdata()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $p = $_POST;
        $data['showreport'] = $this->demos_model->showreport();
        $demodate = date("Y-m-d", strtotime($p['demodate']));
        $result = $this->demos_model->updatecartdata($demodate, $p);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
            return redirect()->to(base_url('demos/report'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url('demos/report'));
        }
        echo view('templates/header_view', $data);
        echo view('demos/demo_report_view', $data);
        echo view('templates/footer_view');
    }
    public function demo_loader()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        return view('demos/demo_loader_view', $data);
    }
    public function popup_ws()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $demoid =  $this->request->getVar('demoid');
        $data['result'] = $this->demos_model->wsshowdetails($demoid);
        echo view('demos/popup_ws_view', $data);
    }
}
