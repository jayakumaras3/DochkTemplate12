<?php

namespace App\Models\Settings;

use CodeIgniter\Model;

use DateTime;

class Event_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
        // OR $this->db = \Config\Database::connect();
    }
    function dealevent_view($projectid, $course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('event as de');
        $builder->select('de.*,d.name as statusname,u1.name as add_by,de.createdon as add_dt');
        $builder->join('dropdown as d', 'd.id_d = de.level', 'left');
        $builder->join('users as u1', 'u1.id_user = de.createdby', 'left');
        $builder->where('de.fk_course_id', $course_id);
        $builder->where('de.fk_projectid', $projectid);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function prodealevent_view($projectid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('event as de');
        $builder->select('de.*,d.name as statusname,u1.name as add_by,de.createdon as add_dt,p.projectname,c.course_name');
        $builder->join('dropdown as d', 'd.id_d = de.level', 'left');
        $builder->join('users as u1', 'u1.id_user = de.createdby', 'left');
        $builder->join('projects as p', 'p.projectid = de.fk_projectid', 'left');
        $builder->join('courses as c', 'c.course_id =de.fk_course_id', 'left');
        $builder->where('de.fk_projectid', $projectid);

        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function adddealevent($newdata, $projectid, $course_id)
    {
        $db = \Config\Database::connect();
        $dealevent = [
            'fk_projectid' => $projectid,
            'fk_course_id' => $course_id,
            'event_description' => $newdata['event_description'],
            'action_item' => $newdata['action_item'],
            'level' => $newdata['level'],
            'status' => '1',
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $builder = $this->db->table('event');
        $builder->insert($dealevent);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function getprojectclient($projectid)
    {
        $builder = $this->db->table('projects as p');
        $builder->select('p.client,c.client_name');
        $builder->join('client as c', 'c.id_c  = p.client', 'left');
        $builder->where('p.projectid', $projectid);
        $data = $builder->get()->getResultArray();
        //print_r($data);
        // exit();
        return $data;
    }
    function dealtimeline_view($course_id)
    {
        // print_r($course_id);
        // exit();
        $db = \Config\Database::connect();
        $builder = $this->db->table('deal_timeline as dlt');
        $builder->select('dlt.*,dlt.level as levelid,c.client_name as itemtypename,d1.name as levelname,p.projectname,ph.header_name');
        $builder->join('projects as p', 'p.projectid  = dlt.fk_course_id', 'left');
        $builder->join('client as c', 'c.id_c= dlt.item_type', 'left');
        $builder->join('dropdown as d1', 'd1.id_d = dlt.level and d1.fk_id_dc =10 and d1.status=1', 'left');
        $builder->join('projectplan_header as ph', 'ph.id_ph = dlt.header and d1.fk_id_dc =10', 'left');
        $builder->where('dlt.status', '1');
        $builder->where('ph.status', '1');
        $builder->where('dlt.fk_course_id', $course_id);
        $builder->orderBy('dlt.start_date');
        $data = $builder->get()->getResultArray();
        // print_r($data);
        return $data;
    }
    function addtimeline($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('deal_timeline');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function addFileHistory($hisdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('dealtimeline_history');
        $builder->insert($hisdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function addeventHistory($hisdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('event_history');
        $builder->insert($hisdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function updatedateformat($value, $column, $id)
    {
        $value = trim($value);
        $db = \Config\Database::connect();
        $builder = $this->db->table('deal_timeline as t');
        $builder->select('t.*');
        $builder->where('t.dt_id', $id);
        $timelinedata = $builder->get()->getResultArray();

        if ($column == 'start_date' || $column == 'duration') {
            if ($column == 'start_date') {
                // $end_date = date('Y-m-d', strtotime($value . ' +' . $timelinedata['0']['duration'] . ' day'));
                $end_date =  $this->calenddaate($value, $timelinedata['0']['duration']);
                //exit();
                $builder = $this->db->table('deal_timeline as dtd');
                $builder->set('dtd.end_date', $end_date);
                $builder->set($column, $value);
                $builder->where('dtd.dt_id', $id);
                $builder->update();
                $dataformat = $builder->get()->getResultArray();
                if ($timelinedata['0']['link'] != 0) {
                    //echo "tttt";
                    $newdata['link'] = $timelinedata['0']['link'];
                    $course_id = $timelinedata['0']['fk_course_id'];
                    $dt_id = $timelinedata['0']['dt_id'];
                    $this->updatelinkId($newdata, $course_id, $dt_id);
                }
            } else {
                //$end_date = date('Y-m-d', strtotime($timelinedata['0']['start_date'] . ' +' . $value . ' day'));
                $end_date =  $this->calenddaate($timelinedata['0']['start_date'], $value);
                $builder = $this->db->table('deal_timeline as dtd');
                $builder->set('dtd.end_date', $end_date);
                $builder->set($column, $value);
                $builder->where('dtd.dt_id', $id);
                $builder->update();
                $dataformat = $builder->get()->getResultArray();
            }
        } elseif ($column == 'start_day' && $value > 0) {
            $builder = $this->db->table('deal_timeline as t');
            $builder->select('t.*');
            $builder->where('t.link', $id);
            $linkenddate = $builder->get()->getResultArray();
            $start_date = date('Y-m-d', strtotime($linkenddate[0]['end_date'] . ' +' . $value . ' day'));
            $builder = $this->db->table('deal_timeline as dtd');
            $builder->set('dtd.start_date', $start_date);
            $builder->set($column, $value);
            $builder->where('dtd.dt_id', $id);
            $builder->update();
            $dataformat = $builder->get()->getResultArray();
        } else {
            $db = \Config\Database::connect();
            $builder = $this->db->table('deal_timeline as dt');
            $builder->set($column, $value);
            $builder->where('dt.dt_id', $id);
            $builder->update();
            $dataformat = $builder->get()->getResultArray();
        }

        if (isset($dataformat)) {
            $hisdata['fk_dt_id'] = $id;
            $hisdata['content'] = $value;
            $hisdata['typeofvalue'] = $column;
            $hisdata['status'] = 1;
            $hisdata['createdon'] = time();
            $hisdata['createdby'] = session()->get('id_user');
            $hisdata['last_updated_on'] = time();
            $hisdata['last_updated_by'] = session()->get('id_user');

            $data = $this->addFileHistory($hisdata);
            //print_r($data);
            // exit();
            if (!empty($data)) {
                $data['status'] = "OK";
            } else {
                $data['status'] = "Error";
            }
            return  $data;
        }
    }
    function updateEventformat($value, $column, $id)
    {
        $builder = $this->db->table('event as dt');
        $builder->set($column, $value);
        $builder->where('dt.e_id', $id);
        $builder->update();
        $dataformat = $builder->get()->getResultArray();
        if (isset($dataformat)) {
            $hisdata['fk_e_id'] = $id;
            $hisdata['content'] = $value;
            $hisdata['typeofvalue'] = $column;
            $hisdata['status'] = 1;
            $hisdata['createdon'] = time();
            $hisdata['createdby'] = session()->get('id_user');
            $hisdata['last_updated_on'] = time();
            $hisdata['last_updated_by'] = session()->get('id_user');

            $data = $this->addeventHistory($hisdata);
            //print_r($data);
            // exit();*/
            if (!empty($data)) {
                $data['status'] = "OK";
            } else {
                $data['status'] = "Error";
            }
            return  $data;
        }
    }
    function deleteDealtimeline($newdata, $dt_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('deal_timeline as dlt');
        $builder->where('dlt.dt_id', $dt_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getdealhistory($dt_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('dealtimeline_history as dh');
        $builder->select('dh.*,u.name as add_by,dh.createdon as add_dt');
        $builder->join('deal_timeline as dt', 'dt.dt_id = dh.fk_dt_id', 'left');
        $builder->join('dropdown as d1', 'd1.id_d = dh.typeofvalue', 'left');
        $builder->join('users as u', 'u.id_user = dh.createdby', 'left');
        $builder->where('dh.fk_dt_id', $dt_id);
        $builder->orderBy('dh.his_id', 'desc');
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function geteventhistory($e_id, $course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('event_history as dh');
        $builder->select('dh.*,u.name as add_by,u1.name as contentname,dh.createdon as add_dt');
        $builder->join('event as dt', 'dt.e_id = dh.fk_e_id', 'left');
        $builder->join('dropdown as d1', 'd1.id_d = dh.typeofvalue', 'left');
        $builder->join('users as u', 'u.id_user = dh.createdby', 'left');
        $builder->join('users as u1', 'u1.id_user = dh.content', 'left');
        $builder->where('dh.fk_e_id', $e_id);
        $builder->orderBy('dh.his_e_id', 'desc');
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function geteachdealtimeline($dt_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('deal_timeline as dlt');
        $builder->select('dlt.*,d.name as itemtypename');
        $builder->join('dropdown as d', 'd.id_d = dlt.item_type', 'left');
        $builder->where('dlt.status', '1');
        $builder->where('dlt.dt_id', $dt_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function geteacheventtimeline($e_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('event as e');
        $builder->select('e.*,d.name as levelname');
        $builder->join('dropdown as d', 'd.id_d = e.level and fk_id_dc =10', 'left');
        $builder->where('e.status', '1');
        $builder->where('e.e_id', $e_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function insetTemplate($course_id, $template_type)
    {
        if (!empty($course_id)) {
            $db = \Config\Database::connect();
            $builder = $this->db->table('template_details as td');
            $builder->select('td.*');
            $builder->where('td.fk_template_id', $template_type);
            $builder->where('td.status', 1);
            $tempdata = $builder->get()->getResultArray();
            foreach ($tempdata as $eachtempdata) {
                $inserttempdata = [
                    'fk_course_id' => $course_id,
                    'item_type' => $eachtempdata['item_type'],
                    'item_description' => $eachtempdata['item_description'],
                    'completion' => $eachtempdata['completion'],
                    'duration' => $eachtempdata['duration'],
                    'status' => '1',
                    'level' => 39,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time()
                ];
                $db = \Config\Database::connect();
                $builder = $this->db->table('deal_timeline');
                $builder->insert($inserttempdata);
                //return redirect()->to(base_url().'/project_plan?projectid='.$projectid.'&course_id='.$course_id); 
            }
        }
    }
    function projectPlanData($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('deal_timeline as td');
        $builder->select('td.*');
        $builder->where('td.fk_course_id', $course_id);
        $builder->where('td.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function importnewpage($courseid, $sheetData, $clientdata)
    {
        // print_r(count($sheetData));
        //exit();
        for ($i = 0; $i < count($sheetData); $i++) {
            // print_r($sheetData[$i][0]);
            // exit();

            if (isset($sheetData[$i][0])) {
                if (trim($sheetData[$i][0]) == 'Id') {
                    continue;
                }
            }
            if (isset($sheetData[$i][10])) {
                if ($sheetData[$i][10] != 0  && $sheetData[$i][0] > $sheetData[$i][10]) {
                    echo "link id (excelsheet column11) should always greater than Id (excelsheet column1)";
                    exit();
                }
            }
            $dt_id = "";
            if (isset($sheetData[$i][0])) {
                $dt_id = trim($sheetData[$i][0]);
            }
            $item_type = "";
            if (isset($sheetData[$i][1])) {
                $type = trim($sheetData[$i][1]);
                if ($type == 'TQ') {
                    $item_type = 1;
                } else {
                    $item_type = $clientdata[0]['client'];
                }
            }
            $header = "";
            if (isset($sheetData[$i][2])) {
                $header_name = trim($sheetData[$i][2]);
                $createdby = session()->get('id_user');
                $createdon = time();
                $db = \Config\Database::connect();
                $builder = $this->db->table('deal_timeline as t');
                $builder->select('t.*');
                $builder->where('fk_course_id', $courseid);
                $builder->where('t.dt_id', $dt_id);
                $builder->where('t.status', 1);
                $data = $builder->get()->getResultArray();

                if (count($data) > 0) {
                } else {
                    $db = \Config\Database::connect();
                    $q = "INSERT INTO projectplan_header VALUES ('','$courseid','$header_name', '1', '$createdby', '$createdon','0','0','0','0')";
                    if ($db->query($q)) {
                        $insert_id = $db->InsertID();
                        //$insertdata = [];
                    }
                }
            }

            $item_description = "";
            if (isset($sheetData[$i][3])) {
                $item_description = trim($sheetData[$i][3]);
            }

            $completion = 0;
            if (isset($sheetData[$i][4])) {
                $completion = trim($sheetData[$i][4]);
            }
            $start_date = "";
            if (isset($sheetData[$i][5])) {
                $start_date = date('Y-m-d', strtotime($sheetData[$i][5]));
            }
            $duration = 0;
            if (isset($sheetData[$i][6])) {
                $duration = trim($sheetData[$i][6]);
            }

            $end_date = "";
            if (isset($sheetData[$i][8])) {
                $end_date = date('Y-m-d', strtotime($sheetData[$i][8]));
                //$end_dt = date('Y-m-d', strtotime($sheetData[$i][5] . ' +' . $duration . ' day'));
                $end_dt =  $this->calenddaate($sheetData[$i][5], $duration);
            }
            $link = 0;
            if (isset($sheetData[$i][11])) {
                $link = trim($sheetData[$i][11]);
            }
            $start_day = 0;
            if (isset($sheetData[$i][7])) {
                $start_day = trim($sheetData[$i][7]);
            }
            $level = 0;
            if (isset($sheetData[$i][10])) {
                if (trim($sheetData[$i][10]) === 'Progress') {
                    $level = 40;
                } elseif (trim($sheetData[$i][10]) === 'Yet to Start') {
                    $level = 39;
                } elseif (trim($sheetData[$i][10]) === 'Completed') {
                    $level = 41;
                } elseif (trim($sheetData[$i][10]) === 'Delayed') {
                    $level = 42;
                }
            }
            //$end_dt = date('Y-m-d', strtotime($end_date . ' +' . $duration . ' day'));
            $updatedata = [
                'fk_course_id' => $courseid,
                'item_type' => $item_type,
                'item_description' => $item_description,
                'completion' => $completion,
                'duration' => $duration,
                'note' => '',
                'start_date' => $start_date,
                'end_date' => $end_dt,
                'link' => $link,
                'start_day' => $start_day,
                'status' => '1',
                'level' => $level,
                'createdby' => session()->get('id_user'),
                'createdon' => time()
            ];
            $db = \Config\Database::connect();
            $builder = $this->db->table('deal_timeline as t');
            $builder->select('t.*');
            $builder->where('fk_course_id', $courseid);
            $builder->where('dt_id', $dt_id);
            $builder->where('status', 1);
            $data = $builder->get()->getResultArray();
            //print_r($data);
            //exit();
            if (count($data) > 0) {
                $db = \Config\Database::connect();
                $builder = $this->db->table('deal_timeline');
                $builder->where('fk_course_id', $courseid);
                $builder->where('dt_id', $dt_id);
                $builder->update($updatedata);
                $data = $builder->get()->getResultArray();
            } else {
                $insertdata = [
                    'fk_course_id' => $courseid,
                    'header' => $insert_id,
                    'item_type' => $item_type,
                    'item_description' => $item_description,
                    'completion' => $completion,
                    'duration' => $duration,
                    'note' => '',
                    'start_date' => $start_date,
                    'end_date' => $end_dt,
                    'link' => $link,
                    'start_day' => $start_day,
                    'status' => '1',
                    'level' => $level,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time()
                ];
                $builder = $this->db->table('deal_timeline');
                $builder->insert($insertdata);
                $data = $builder->get()->getResultArray();
            }
        }
        $builder = $this->db->table('deal_timeline as d');
        $builder->select('d.*');
        $builder->where('fk_course_id', $courseid);
        $builder->orderBy('d.dt_id', 'Asc');
        $Postdata = $builder->get()->getResultArray();
        $newdata['link'] = isset($Postdata[0]['link']) ? $Postdata[0]['link'] : '';
        $dt_id = $Postdata[0]['dt_id'];
        //  print_r($dt_id);
        //  print_r($courseid);
        //  print_r($newdata);
        //  exit();
        $result = $this->updatelinkId($newdata, $courseid, $dt_id);
        return  $result;
    }
    function getitemdescription($courseid, $dt_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('deal_timeline as t');
        $builder->select('t.*');
        $builder->where('fk_course_id', $courseid);
        $builder->where('dt_id >', $dt_id);
        $builder->where('status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function updatelinkId($newdata, $course_id, $dt_id)
    {
        $db = \Config\Database::connect();
        if ($newdata['link'] != 0) {
            $builder = $this->db->table('deal_timeline as dt');
            $builder->where('dt_id', $dt_id);
            $builder->update($newdata);
            $data = $builder->get()->getResultArray();
            if (!empty($data)) {

                $builder = $this->db->table('deal_timeline as dtd');
                $builder->select('dtd.*');
                $builder->where('dtd.dt_id', $dt_id);
                $itemdata = $builder->get()->getResultArray();
                $days = $itemdata[0]['duration'];
                // $end_dt = date('Y-m-d', strtotime($itemdata[0]['start_date'] . ' +' . $days . ' day'));
                $end_dt =  $this->calenddaate($itemdata[0]['start_date'], $days);

                $builder = $this->db->table('deal_timeline as dt');
                $builder->set('end_date', $end_dt);
                $builder->where('dt_id', $dt_id);
                $builder->update();

                //print_r($end_dt);
                // exit();
                $builder = $this->db->table('deal_timeline as lt');
                $builder->select('lt.*');
                $builder->where('lt.dt_id', $newdata['link']);
                $linkdata = $builder->get()->getResultArray();
                $linkid = $linkdata[0]['dt_id'];
                $start_dt = $linkdata[0]['start_date'];
                $duration =  $linkdata[0]['duration'];
                //print_r($linkdata);
                //exit();
                $db = \Config\Database::connect();
                $builder = $this->db->table('deal_timeline as dealt');
                $builder->select('dealt.*');
                $builder->where('dealt.fk_course_id', $course_id);
                $builder->where('dealt.status', 1);
                $builder->where('dealt.link !=', 0);
                $dealdata = $builder->get()->getResultArray();
                for ($i = 1; $i <= count($dealdata); $i++) {
                    $builder = $this->db->table('deal_timeline as lt');
                    $builder->select('lt.*');
                    $builder->where('lt.dt_id', $linkid);
                    $seclinkdata = $builder->get()->getResultArray();
                    if (!isset($seclinkdata[0]['link'])) {
                        break;
                    }
                    $start_dt = $seclinkdata[0]['start_date'];
                    $duration = $seclinkdata[0]['duration'];
                    $builder = $this->db->table('deal_timeline as dtd');
                    $builder->select('dtd.*');
                    $builder->where('dtd.dt_id', $dt_id);
                    $secitemdata = $builder->get()->getResultArray();
                    $days = $secitemdata[0]['duration'];
                    // $end_dt = date('Y-m-d', strtotime($secitemdata[0]['start_date'] . ' +' . $days . ' day'));
                    $end_dt =  $this->calenddaate($secitemdata[0]['start_date'], $days);
                    //if($start_dt <= $end_dt){
                    if ($seclinkdata[0]['start_day'] == 0) {
                        $linkedStart_date  = date('Y-m-d', strtotime($end_dt));
                    } else {
                        $linkedStart_date  = date('Y-m-d', strtotime($end_dt . ' +1 day'));
                    }
                    $linkedEnd_date  = date('Y-m-d', strtotime($linkedStart_date . ' +' . $duration . ' day'));
                    $builder = $this->db->table('deal_timeline as dt');
                    $builder->set('start_date', $linkedStart_date);
                    $builder->set('end_date', $linkedEnd_date);
                    $builder->set('level', '40');
                    $builder->where('dt_id', $linkid);
                    $builder->update();
                    //$postdata = $builder->get()->getResultArray();
                    // }
                    $linkid = $seclinkdata[0]['link'];
                    $dt_id = $seclinkdata[0]['dt_id'];
                }
                if (isset($data)) {
                    $hisdata = [
                        'fk_dt_id' => $itemdata[0]['dt_id'],
                        'typeofvalue' => 'linked',
                        'content' => $newdata['link'],
                        'status' => 1,
                        'createdby' => session()->get('id_user'),
                        'createdon' => time(),
                        'last_updated_by' =>  session()->get('id_user'),
                        'last_updated_on' => time()
                    ];
                    $this->addFileHistory($hisdata);
                }
                return $data;
            } else {
                return 0;
            }
        } elseif ($newdata['link'] == 0) {
            $builder = $this->db->table('deal_timeline as dt');
            $builder->where('dt_id', $dt_id);
            $builder->update($newdata);
            $data = $builder->get()->getResultArray();
            return $data;
        } else {
            return false;
        }
    }
    function getProjectPlanData($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('deal_timeline as dlt');
        $builder->select('dlt.*,cl.client_name as itemtypename,d1.name as level,u.name as username,p.projectname,ph.header_name');
        $builder->join('projects as p', 'p.projectid = dlt.fk_course_id', 'left');
        $builder->join('client as cl', 'cl.id_c = dlt.item_type', 'left');
        $builder->join('dropdown as d1', 'd1.id_d = dlt.level and d1.fk_id_dc =10', 'left');
        $builder->join('users as u', 'u.id_user = dlt.createdby', 'left');
        $builder->join('projectplan_header as ph', 'ph.id_ph = dlt.header and d1.fk_id_dc =10', 'left');
        $builder->where('dlt.status', '1');
        $builder->where('ph.status', '1');
        $builder->where('dlt.fk_course_id', $course_id);
        $data = $builder->get()->getResultArray();
        //print_r($data);
        return $data;
    }
    function addpagesequence($allPageArray)
    {
        $db = \Config\Database::connect();
        $sequence = 1;
        foreach ($allPageArray as $pageID) {
            $builder = $this->db->table('deal_timeline as pg');
            $builder->set('pg.sequence', $sequence);
            $builder->where('dt_id', $pageID);
            $builder->update();
            $sequence++;

            $oneless = $sequence - 1;
            $newdata = [
                'fk_dt_id' => $pageID,
                'typeofvalue' => 'Sequence',
                'content' =>  $oneless,
                'status' => '1',
                'createdby' => session()->get('username'),
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $builder = $this->db->table('dealtimeline_history');
            $builder->insert($newdata);
            $data = $builder->get()->getResultArray();
        }
        if (!$data) {
            $data['status'] = "Sorted!";
        } else {
            $data['status'] = "Error";
        }
        return  $data;
    }
    function addheaderdata($newdata)
    {
        $builder = $this->db->table('projectplan_header');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getheaderdata($course_id)
    {
        $builder = $this->db->table('projectplan_header as ph');
        $builder->select('ph.*');
        $builder->where('ph.fk_course_id', $course_id);
        $builder->where('ph.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function geteachheaderdata($id_ph)
    {
        $builder = $this->db->table('projectplan_header as ph');
        $builder->select('ph.*');
        $builder->where('ph.id_ph', $id_ph);
        $builder->where('ph.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function editheaderdata($newdata, $id_ph)
    {
        $builder = $this->db->table('projectplan_header');
        $builder->where('id_ph', $id_ph);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function calenddaate($start_date, $duration)
    {
        $d = new DateTime($start_date);
        $t = $d->getTimestamp();
        // loop for X days
        for ($i = 0; $i < $duration; $i++) {

            // add 1 day to timestamp
            $addDay = 86400;

            // get what day it is next day
            $nextDay = date('w', ($t + $addDay));

            // if it's Saturday or Sunday get $i-1
            if ($nextDay == 0 || $nextDay == 6) {
                $i--;
            }

            // modify timestamp, add 1 day
            $t = $t + $addDay;
        }

        $d->setTimestamp($t);

        $end_date =  $d->format('Y-m-d');
        return $end_date;
    }
}
