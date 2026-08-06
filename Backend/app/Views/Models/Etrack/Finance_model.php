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
    function get_active_ucn()
    {
        $builder = $this->db->table('project_ucn  as u');
        $builder->select('u.name, u.ucn_id');
        $builder->where('u.status !=', 0);
        $builder->where('u.status !=', 10);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function get_active_users()
    {
        $builder = $this->db->table('users  as u');
        $builder->select('u.id_user, u.emp_id');
        $builder->where('u.status !=', 0);
        $builder->where('u.client_id', 1);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function get_active_claims()
    {
        $builder = $this->db->table('et_vendor_details as vd');
        $builder->select('vd.*, v.vendor_short_name, u.name as name');
        $builder->join('et_vendor as v', 'v.vendor_id = vd.vendor_id', 'left');
        $builder->join('users as u', 'u.id_user = vd.requested_by', 'left');
        $builder->where('vd.status !=', 0);
        //$builder->where('vd.status !=', 4);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_claim_details_byID($vd_id)
    {
        $builder = $this->db->table('et_vendor_details as vd');
        $builder->select('vd.*, v.vendor_short_name, u.name as name');
        $builder->join('et_vendor as v', 'v.vendor_id = vd.vendor_id', 'left');
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
                    $working_days = $Row[12];
                    $lop = $Row[11];

                    $gross_salary = $Row[21];

                    $total_deduction = $Row[32];
                    $net_pay_amount = $Row[33];
                    $note = $Row[34];
                    $basic = $Row[13];
                    $hra = $Row[14];
                    //$conv = $Row[14];
                    $edu_allowance = $Row[15];
                    $lta = $Row[16];
                    $meal_allowance = $Row[17];
                    $medical_allwance = $Row[18]; //Internet Allowance
                    //$car_lease_allowance = $Row[18]; 
                    $flexi_allowance = $Row[19];
                    $arrears_others = $Row[20];
                    $pt = $Row[24];
                    $pf_ee = $Row[25];
                    $pf_er = $Row[26];
                    $esi = $Row[27];
                    $sal_adv_other_deduct = $Row[28];
                    $other_deduct = isset($Row[29]) ? $Row[29] : '0';
                    //   $late_come_deduct = isset($Row[32]) ? $Row[32] : '0';
                    $income_tax = $Row[31];
                    $sodexo = $Row[30];
                    $account_num =  $Row[3];
                    $bank_name =  $Row[4];
                    $pan =  $Row[5];
                    $designation = $Row[8];
                    // $VFPEmployee =  isset($Row[28]) ? $Row[28] : '0';
                    $uan =  $Row[6];
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
