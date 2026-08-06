<?php

namespace App\Models\eTrack;

use CodeIgniter\Model;

class Finance_model extends Model
{

    public function get_purchase_order_admin()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('purchase_order as du');
        $builder->select('du.*, c.client_name as client_name, u.name as acmanager, pul.ucn as ucn');
        // $builder->join('projects_assignment as pa', 'pa.db_id = du.po_id', 'left');

        $builder->join('project_ucn_link as pul', 'pul.table_id = du.po_id AND pul.type_of_link = 2 AND pul.status!=0', 'left');
        $builder->join('client as c', 'c.id_c = du.client_id', 'left');
        $builder->join('users as u', 'u.id_user = du.account_manager', 'left');
        // $builder->where('pa.type_of_assignment', 4);
        $builder->where('du.status !=', 0);
        $builder->orderBy('du.po_id', 'DESC');
        $builder->limit(20);
        $data = $builder->get()->getResultArray();
        return  $data;
    }


    public function get_sales_data_dashboard()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('et_sales as et_sales');
        $builder->select('SUM(et_sales.value) as total_amount, MONTH(et_sales.expected_date) as month');
        $builder->where('et_sales.status !=', 0);
        $builder->where('et_sales.status <', 10);
        $builder->groupBy('YEAR(et_sales.expected_date), MONTH(et_sales.expected_date)');
        $builder->orderBy('YEAR(et_sales.expected_date)', 'ASC');
        $builder->orderBy('MONTH(et_sales.expected_date)', 'ASC');
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    public function get_purchase_order_issued($year)
    {
        $builder = $this->db->table('purchase_order as du');
        $builder->select('du.*, c.client_name as client_name, u.name as acmanager, pul.ucn as ucn');
        $builder->join('project_ucn_link as pul', 'pul.table_id = du.po_id AND pul.type_of_link = 2 AND pul.status!=0', 'left');
        $builder->join('client as c', 'c.id_c = du.client_id', 'left');
        $builder->join('users as u', 'u.id_user = du.account_manager', 'left');
        $builder->where('du.status !=', 0);
        $builder->orderBy('du.po_id', 'DESC');
        $builder->limit(20);
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    public function get_all_vendors()
    {
        $builder = $this->db->table('et_vendor as v');
        $builder->select('v.vendor_short_name, v.contact_name_01, v.email_01, v.phone_01, v.vendor_id');
        $builder->where('v.status !=', 0);
        $builder->orderBy('v.vendor_short_name', 'DESC');
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    public function add_data_to_vendor($newdata)
    {
        $builder = $this->db->table('et_vendor');
        $builder->insert($newdata);
        return true;
    }

    public function get_vendor_details_by_ID($vendor_id)
    {
        $builder = $this->db->table('et_vendor as v');
        $builder->select('v.*');
        $builder->where('v.vendor_id', $vendor_id);
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    public function approve_claim_stat($newdata, $vd_id)
    {
        $builder = $this->db->table('et_vendor_details as v');
        $builder->where('v.vd_id', $vd_id);
        $builder->update($newdata);
        return true;
    }

    public function delete_payslips($newdata, $year, $month)
    {
        $builder = $this->db->table('user_payslip as up');
        $builder->where('up.pay_month', $month);
        $builder->where('up.pay_yr', $year);
        $builder->update($newdata);
        return true;
    }

    public function update_vendor_data($newdata, $vendor_id)
    {
        $builder = $this->db->table('et_vendor as v');
        $builder->where('v.vendor_id', $vendor_id);
        $builder->update($newdata);
        return true;
    }
    public function get_vendor_payments_by_ID($vendor_id, $year)
    {
        $start_dt = $year . '-01-01';
        $end_dt = $year . '-12-31';
        $builder = $this->db->table('et_vendor_details  as vd');
        $builder->select('vd.*');
        $builder->where('vd.status !=', 0);
        $builder->where('vd.vendor_id', $vendor_id);
        $builder->where('vd.payment_dt  >=', $start_dt);
        $builder->where('vd.payment_dt  <=', $end_dt);
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    function get_active_salary_users_dashboard(int $year)
    {
        $builder = $this->db->table('user_payslip as payslip');
        $builder->select('sum(payslip.gross_salary) as total_salary, payslip.pay_month');
        $builder->where('payslip.pay_yr', $year);
        $builder->where('payslip.status', 1);
        $builder->groupBy('payslip.pay_month');
        $data = $builder->get()->getResultArray();
        return  $data;
    }


    function get_active_tasks_users_dashboard(int $client)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.id_user');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user AND du.status != 0 AND du.fk_id_dc = 2 AND du.fk_id_d = 7', 'left');
        $builder->where('u.valid !=', 0);
        $builder->where('u.client_id', $client);
        $builder->where('du.fk_id_user IS NOT NULL');
        $builder->groupBy('u.id_user');
        $builder->where('u.region', 1);
        return  $builder->countAllResults();
    }

    function list_task_users(int $client)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.id_user, u.name, u.last_name, u.engage_type, u.default_dashboard2, u.default_dashboard1, u.gender');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user AND du.status != 0 AND du.fk_id_dc = 2 AND du.fk_id_d = 7', 'left');
        $builder->where('u.valid !=', 0);
        $builder->where('u.client_id', $client);
        $builder->where('u.region', 1);
        $builder->where('du.fk_id_user IS NOT NULL');
        $builder->groupBy('u.id_user');
        $builder->orderBy('u.name', 'ASC');
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    function get_operation_cost(int $year)
    {
        $builder = $this->db->table('et_vendor_details as oc');
        $builder->select('sum(oc.claim_amount_usd) as usd_cost, MONTH(oc.requested_on) as month');
        $builder->where('YEAR(oc.requested_on)', $year);
        $builder->groupBy('MONTH(oc.requested_on)');
        $builder->where('oc.status !=', 0);
        $builder->where('oc.status !=', 10);
        $builder->where('oc.claim_amount_usd !=', NULL);
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    function get_bespoke_revenue(int $year)
    {
        $startDate = $year . '-01-01';
        $endDate   = $year . '-12-31';
        $builder = $this->db->table('project_milestones');

        $builder->select('SUM(project_milestones.value) as total_amount, MONTH(project_milestones.invoicing_dt) as month');
        $builder->where('project_milestones.status !=', 0);
        $builder->where('project_milestones.invoicing_dt >=', $startDate);
        $builder->where('project_milestones.invoicing_dt <=', $endDate);

        $builder->groupBy('MONTH(project_milestones.invoicing_dt)');
        // $builder->groupBy('project_milestones.milestone_id');
        $builder->orderBy('MONTH(project_milestones.invoicing_dt)', 'ASC');
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    function get_linked_ucn($vd_id)
    {
        $builder = $this->db->table('et_vendor_ucn  as vu');
        $builder->select('vu.*, ucn.name');
        $builder->join('project_ucn as ucn', 'ucn.ucn_id = vu.ucn', 'left');
        $builder->where('vu.vendor_detail_id', $vd_id);
        $builder->where('vu.status !=', 0);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function get_active_users()
    {
        $builder = $this->db->table('users  as u');
        $builder->select('u.id_user, u.emp_id, u.name, u.last_name, u.engage_type, u.gender, u.default_dashboard1');
        $builder->where('u.valid !=', 0);
        $builder->where('u.client_id', 1);
        $builder->where('u.region', 1);
        $builder->orderBy('u.name', 'ASC');
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    function get_active_users_dashboard(int $client)
    {

        $builder = $this->db->table('users as u');
        $builder->select('u.id_user');
        $builder->where('u.valid !=', 0);
        $builder->where('u.client_id', $client);
        $builder->where('u.region', 1);
        return  $builder->countAllResults();
    }

    function get_active_male_users_dashboard(int $client)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.id_user');
        $builder->where('u.valid !=', 0);
        $builder->where('u.client_id', $client);
        $builder->where('u.gender', 2);
        $builder->where('u.region', 1);
        return  $builder->countAllResults();
    }

    function get_active_India_users_dashboard(int $client)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.id_user');
        $builder->where('u.valid !=', 0);
        $builder->where('u.client_id', $client);
        $builder->where('u.region', 1);
        return  $builder->countAllResults();
    }

    function get_active_permanent_users_dashboard(int $client)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.id_user');
        $builder->where('u.valid !=', 0);
        $builder->where('u.client_id', $client);
        $builder->where('u.region', 1);
        $builder->where('u.engage_type', 1);
        return  $builder->countAllResults();
    }
    function get_active_claims()
    {
        $builder = $this->db->table('et_vendor_details as vd');
        $builder->select('vd.*, u.name as name');
        //  $builder->join('et_vendor as v', 'v.vendor_id = vd.vendor_id', 'left');
        $builder->join('users as u', 'u.id_user = vd.requested_by', 'left');
        $builder->where('vd.status !=', 0);
        //$builder->where('vd.status !=', 4);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_claim_details_byID($vd_id)
    {
        $builder = $this->db->table('et_vendor_details as vd');
        $builder->select('vd.*,  u.name as name');
        //  $builder->join('et_vendor as v', 'v.vendor_id = vd.vendor_id', 'left');
        $builder->join('users as u', 'u.id_user = vd.requested_by', 'left');
        $builder->where('vd.vd_id', $vd_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function import_payslip_excel($sheetData, $month, $year)
    {
        $rowid = 0;
        foreach ($sheetData as $Row) {
            if (isset($Row[0]) && isset($Row[1])) {
                if ($rowid == 0) {
                    $rowid = 1;
                    continue;
                }
                $emp_code = $Row[1];


                $builder = $this->db->table('users as u');
                $builder->select('u.id_user');
                $builder->where('u.emp_id', $emp_code);

                $userdata = $builder->get()->getResultArray();

                if ($userdata) {
                    $account_num =  $Row[3];
                    $bank_name =  $Row[4];
                    $pan =  $Row[5];
                    $uan =  $Row[6];
                    $designation = $Row[8];
                    $lop = $Row[11];
                    $working_days = $Row[12];
                    $basic = $Row[13];
                    $hra = $Row[14];
                    $edu_allowance = $Row[15];
                    $lta = $Row[16];
                    $meal_allowance = $Row[17];
                    $medical_allwance = $Row[18]; //Internet Allowance
                    $flexi_allowance = $Row[19];
                    $arrears_others = $Row[20];
                    $gross_salary = $Row[21];
                    $pt = $Row[24];
                    $pf_ee = $Row[25];
                    $pf_er = $Row[26];
                    $esi = $Row[27];
                    $sal_adv_other_deduct = $Row[28];
                    $other_deduct = isset($Row[29]) ? $Row[29] : '0';
                    $sodexo = $Row[30];
                    $income_tax = $Row[31];
                    $total_deduction = $Row[32];
                    $net_pay_amount = $Row[33];
                    $note = isset($Row[34]) ? $Row[34] : '';


                    $userid = $userdata[0]['id_user'];
                    $insertdata = [
                        'user_id' =>  $emp_code,
                        'full_name' => $userid,
                        'working_days' => $working_days,
                        'lop' => $lop,
                        'pay_month' => $month,
                        'pay_yr' => $year,
                        'gross_salary' => $gross_salary,
                        'total_deduction' => $total_deduction,
                        'net_pay_amount' => $net_pay_amount,
                        'note' => $note,
                        'basic' => $basic,
                        'hra' => $hra,
                        'edu_allowance' => $edu_allowance,
                        'lta' => $lta,
                        'meal_allowance' => $meal_allowance,
                        'medical_allwance' => $medical_allwance,
                        'flexi_allowance' => $flexi_allowance,
                        'arrears_others' => $arrears_others,
                        'pt' => $pt,
                        'pf_ee' => $pf_ee,
                        'pf_er' => $pf_er,
                        'esi' => $esi,
                        'sal_adv_other_deduct' => $sal_adv_other_deduct,
                        'other_deduct' => $other_deduct,
                        // 'late_come_deduct' => $late_come_deduct,
                        'income_tax' => $income_tax,
                        'status' => '1',
                        'createdby' => session()->get('id_user'),
                        'sodexo' => $sodexo,
                        'account_num' => $account_num,
                        'bank_name' => $bank_name,
                        'pan' => $pan,
                        'designation' => $designation,
                        // 'VFPEmployee' => $VFPEmployee,
                        'uan' => $uan,
                        'createdon' => time()
                    ];
                    $builder = $this->db->table('user_payslip');
                    $builder->insert($insertdata);
                    $rowid++;
                }
            }
        }
        return $rowid;
    }
}
