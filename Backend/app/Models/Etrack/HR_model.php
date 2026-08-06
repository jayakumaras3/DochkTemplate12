<?php

namespace App\Models\eTrack;

use CodeIgniter\Model;

class HR_model extends Model
{

    public function getpersonaldata($temp_user)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('users_personal_data as upd');
        $builder->select('upd.*');
        $builder->where('upd.userid', $temp_user);
        $builder->where('upd.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getpersonaldocs($temp_user)
    {
        $builder = $this->db->table('et_documents as etd');
        $builder->select('etd.*');
        $builder->where('etd.id_user', $temp_user);
        $builder->where('etd.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getAllappraisalbydate($start_dt, $end_date)
    {
        $builder = $this->db->table('salary as sal');
        $builder->select('sal.*, man.name, man.last_name, man.emp_id');
        $builder->join('users as man', 'man.id_user = sal.id_user', 'left');
        $builder->where('sal.status', 1);
        $builder->where('sal.effectivedate >=', $start_dt);
        $builder->where('sal.effectivedate <=', $end_date);
        $builder->orderBy('sal.id_user');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getpersonalalldocs()
    {
        $builder = $this->db->table('et_documents as etd');
        $builder->select('etd.*, man.name, man.last_name, man.username');
        $builder->join('users as man', 'man.id_user = etd.id_user', 'left');
        $builder->where('etd.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getusername($temp_user)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.*');
        $builder->where('u.id_user', $temp_user);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function all_users($client)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.*');
        $builder->where('u.valid', 1);
        //$builder->where('u.emp_id <', 40000);
        $builder->orderBy('u.department', 'ASC');
        $builder->where('u.client_id', $client);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getDropdown($type)
    {
        $builder = $this->db->table('dropdown as d');
        $builder->select('d.value, d.name,d.id_d');
        $builder->where('d.fk_id_dc', $type);
        $builder->where('d.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getlatestSalary($user)
    {
        $builder = $this->db->table('salary as sal');
        $builder->select('sal.yearly');
        $builder->where('sal.id_user', $user);
        $builder->where('sal.yearly !=', 0);
        $builder->orderBy('sal.effectivedate', 'DESC');
        $builder->limit(1);
        $builder->where('sal.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_dropdown($department, $type)
    {
        $builder = $this->db->table('dropdown as d');
        $builder->select('d.name');
        $builder->where('d.fk_id_dc', $type);
        $builder->where('d.value', $department);
        $builder->where('d.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function update_personal_data($newdata, $upd_id)
    {
        $builder = $this->db->table('users_personal_data as up');
        $builder->where('up.upd_id', $upd_id);
        $builder->update($newdata);
        return true;
    }

    public function update_main_data($main_data, $id_user)
    {
        $builder = $this->db->table('users as up');
        $builder->where('up.id_user', $id_user);
        $builder->update($main_data);
        return true;
    }
    public function update_appr($newdata, $salid)
    {
        $builder = $this->db->table('salary as sal');
        $builder->where('sal.salid', $salid);
        $builder->update($newdata);
        return true;
    }

    public function update_breakup($newdata, $salid)
    {
        $builder = $this->db->table('et_appraisal_breakup as sal');
        $builder->where('sal.salid', $salid);
        $builder->update($newdata);
        return true;
    }
    public function add_personal_data($newdata)
    {
        $builder = $this->db->table('users_personal_data');
        $builder->insert($newdata);
        return true;
    }

    public function add_new_appr($newdata)
    {
        $builder = $this->db->table('salary');
        $builder->insert($newdata);
        $insert_id = $this->db->insertID();
        return $insert_id;
    }

    public function add_new_breakup($breakup)
    {
        $builder = $this->db->table('et_appraisal_breakup');
        $builder->insert($breakup);
        return true;
    }

    function import_salary($sheetData, $month, $year)
    {
        if (trim($sheetData[0][0]) == 'Sl. No.') {
            foreach ($sheetData as $Row) {


                if (trim($Row[0]) == 'Sl. No.') {
                    continue;
                }

                $employee_code = "";
                if (isset($Row[1])) {
                    $employee_code = trim($Row[1]);

                    $builder = $this->db->table('users as u');
                    $builder->select('u.id_user');
                    $builder->where('u.emp_id', $employee_code);
                    $userdata = $builder->get()->getResultArray();

                    if (empty($userdata)) {
                        continue;
                    }
                    $employee_code = $userdata[0]['id_user'];
                }
                $empid = trim($Row[1]);
                $acnumber = trim($Row[3]);
                $bankname = trim($Row[4]);
                $pan = trim($Row[5]);
                $uan = trim($Row[6]);
                $name = trim($Row[7]);
                $designation = trim($Row[8]);
                $lop = trim($Row[11]);
                $working_days = trim($Row[12]);
                $basic = trim($Row[13]);
                $hra = trim($Row[14]);
                $edu_allowance = trim($Row[15]);
                $lta = trim($Row[16]);
                $meal_allow = trim($Row[17]);
                $int_allow = trim($Row[18]);
                $flex_allow = trim($Row[19]);
                $inscentive = trim($Row[20]);
                $gross_sal = trim($Row[21]);
                $pf_employer = trim($Row[22]);
                $profess_tax = trim($Row[24]);
                $pf_employee = trim($Row[25]);
                $esi = trim($Row[27]);
                $vpf = trim($Row[28]);
                $sal_advance = trim($Row[29]);
                $other_Deduc = trim($Row[30]);
                $sodexo = trim($Row[31]);
                $late_coming = trim($Row[32]);
                $income_tax = trim($Row[33]);
                $total_ded = trim($Row[34]);
                $net_pay = trim($Row[35]);
                $remarks = trim($Row[36]);

                $insertdata = [
                    'user_id' => $empid,
                    'full_name' => $employee_code,
                    'working_days' => $working_days,
                    'lop' => $lop,
                    'pay_month' => $month,
                    'pay_yr' => $year,
                    'gross_salary' => $gross_sal,
                    'gross_salary' => $gross_sal,
                    'total_deduction' => 0,
                    'net_pay_amount' => $net_pay,
                    'note' => $remarks,
                    'basic' => $basic,
                    'hra' => $hra,
                    'conv' => 0,
                    'edu_allowance' => $edu_allowance,
                    'lta' => $lta,
                    'meal_allowance' => $meal_allow,
                    'medical_allwance' => 0,
                    'car_lease_allowance' => $int_allow,
                    'flexi_allowance' => $flex_allow,
                    'arrears_others' => $inscentive,
                    'pt' => $profess_tax,
                    'pf_ee' => $pf_employee,
                    'pf_er' => $pf_employer,
                    'esi' => $esi,
                    'sal_adv_other_deduct' => $sal_advance,
                    'other_deduct' => $other_Deduc,
                    'late_come_deduct' => $late_coming,
                    'income_tax' => $income_tax,
                    'status' => 1,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'sodexo' => $sodexo,
                    'account_num' => $acnumber,
                    'bank_name' => $bankname,
                    'pan' => $pan,
                    'designation' => $designation,
                    'VFPEmployee' => $vpf,
                    'uan' => $uan
                ];

                $builder = $this->db->table('user_payslip');
                $builder->insert($insertdata);
            }
        } else {
            $data['error'] = 'Data not found!. Importing Wrong format ';
            return $data;
        }
        $data['success'] = 'Data imported Successfully ';
        return $data;
    }
    function updateLwd($newdata, $id_user)
    {
        $builder = $this->db->table('users');
        $builder->where('id_user', $id_user);
        $builder->update($newdata);
        return true;
    }
    function getMangerEmailid($manager)
    {
        $builder = $this->db->table('users');
        $builder->select('email');
        $builder->where('id_user', $manager);
        $userdata = $builder->get()->getResultArray();
        // echo "<pre>";
        // print_r($userdata);
        // exit();
        return $userdata;
    }
    function exitUsers()
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.*');
        $builder->select("GROUP_CONCAT(ec.fk_header_id) as fk_header_id", false);
        $builder->select("GROUP_CONCAT(ec.status) as ecstatus", false);
        $builder->join('exit_clearance as ec', 'ec.id_user = u.id_user', 'left');
        $builder->where("CAST(u.LWD AS CHAR) != '0000-00-00'", null, false);

        $builder->where('u.name !=', 'Demo User');
        $builder->groupBy('u.id_user');

        $userdata = $builder->get()->getResultArray();
        // echo "<pre>";
        // print_r($userdata);
        // exit();
        return $userdata;
    }
    function getExitclearanceheader($type)
    {
        $builder = $this->db->table('exit_clearance_header as h');
        $builder->select('h.*');
        $builder->where('h.type', $type);
        $builder->where('h.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getUserexitformstatus($id_user)
    {
        $builder = $this->db->table('exit_clearance as ec');
        $builder->select('ec.*');
        $builder->where('ec.id_user', $id_user);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function updateExitstatus($newdata)
    {
        $builder = $this->db->table('exit_clearance as ec');
        $builder->select('ec.*');
        $builder->where('ec.id_user', $newdata['id_user']);
        $builder->where('ec.fk_header_id', $newdata['fk_header_id']);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $builder = $this->db->table('exit_clearance as ec');
            $builder->where('ec.id_user', $newdata['id_user']);
            $builder->where('ec.fk_header_id', $newdata['fk_header_id']);
            $builder->update($newdata);
        } else {
            $builder = $this->db->table('exit_clearance');
            $builder->insert($newdata);
        }
        return true;
    }
    function getUserexitInterviewtatus($id_user)
    {
        $builder = $this->db->table('exit_interview as ei');
        $builder->select('ei.*');
        $builder->where('ei.id_user', $id_user);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function addUpdateExitInterview($user_id, $newdata)
    {
        $builder = $this->db->table('exit_interview as ei');
        $builder->select('ei.*');
        $builder->where('ei.id_user', $newdata['id_user']);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            // print_r($newdata);
            // exit();
            $builder = $this->db->table('exit_interview');
            $builder->where('id_user', $newdata['id_user']);
            $builder->update($newdata);
        } else {
            $builder = $this->db->table('exit_interview');
            $builder->insert($newdata);
        }
        return true;
    }
}
