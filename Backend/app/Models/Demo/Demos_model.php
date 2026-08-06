<?php

namespace App\Models\Demo;

use CodeIgniter\Model;

class Demos_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
        // OR $this->db = \Config\Database::connect();
    }
    protected $table = 'mycart';
    protected $primaryKey = 'cartod ';
    protected $allowedFields = ['cartod', 'demoid', 'status', 'uid', 'comment', 'keyval', 'demodate', 'expirystatus'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    public function tqDemoUsers()
    {
        $users = session()->get('username');
        $tqDemoUsers = array("admin", "mahimm", "bthallemer", "tracy", "barbara", "kevin", "rpatil", "jbaumohl", "20144");
        return in_array($users, $tqDemoUsers);
    }
    public function getMycartData($access_code, $uidc)
    {
        $q2 = $this->db->query("SELECT * FROM mycart where keyval = '" . $access_code . "' AND uid = '" . $uidc . "'");
        $result2 = $q2->getResultArray();
        $num_rows2 = count($result2);
        //print_r($num_rows2);
        for ($t = 0; $t < $num_rows2; $t++) {
            $data['uid'] = $result2[$t]["uid"];
            $data['cartod'] = $result2[$t]["cartod"];
            $data['access_code'] = $result2[$t]["keyval"];
            $demo_date = $result2[$t]["demodate"];
        }
        $demo_date = isset($demo_date) ? $demo_date : '0000-00-00';
        $today = date("Y-m-d");
        $date1 = date_create($today);
        $date2 = date_create($demo_date);
        $data['diff'] = date_diff($date1, $date2);
        return $data;
    }
    function getfeatured_vc($usersetting, $catid, $tagid)
    {

        if ($this->tqDemoUsers()) {

            $demoidArray = array();

            $qc = $this->db->query("SELECT * FROM integerval where status = 1 && details = '$tagid' ");

            $resultc = $qc->getResultArray();
            $num_rowsc = count($resultc);
            if ($num_rowsc > 0) {
                //echo $usersetting.'/'.$catid.'/'.$tagid;
                for ($i = 0; $i < $num_rowsc; $i++) {
                    $demoidArray[] = $resultc[$i]["demoid"];
                }

                foreach ($demoidArray as $demoid) {
                    $result[] = $this->showfeaturesearch_vc($demoid);
                }
                return $result;
            } else {
                return false;
            }
        }
    }
  
    function getval1_vc($demoid, $typeofval, $dbnum)
    {
        $db = \Config\Database::connect();
        if ($dbnum == 1) {
            $qc = $db->query("SELECT * FROM integerval where typeofval = '$typeofval' and demoid = '$demoid' and status = 1");
        } else {
            $qc = $db->query("SELECT * FROM textval where typeofval = '$typeofval' and demoid = '$demoid' and status = 1");
        }
        $resultc = $qc->getResultArray();
        $num_rowsc = count($resultc);
        $details = "-";
        if (!$resultc || ($num_rowsc < 0)) {
            return;
        }
        if ($num_rowsc == 0) {
            return;
        }
        $details = $resultc[0]["details"];
        return $details;
    }
    function showdetails($demoid)
    {
        $db = \Config\Database::connect();
        $qc = $db->query("SELECT * FROM typeofval where status = 1");
        $resultc = $qc->getResultArray();
        $num_rowsc = count($resultc);
        $data['projectname'] = $this->getval1_vc($demoid, 3, 2);
        $data['Description'] = $this->getval1_vc($demoid, 10, 2);
        for ($i = 0; $i < $num_rowsc; $i++) {
            $id = $resultc[$i]["valie"];
            $valuedesc = $resultc[$i]["valuedesc"];
            $getids = $this->getids($id, $demoid);
            $data[] = [
                'id' => $id,
                'valuedesc' => $valuedesc,
                'getids' => $getids
            ];
        }
        if (!$resultc || ($num_rowsc < 0)) {
            return;
        }
        if ($num_rowsc == 0) {
            return;
        } else {
            return $data;
        }
    }

    function getids($id, $demoid)
    {
        $qc = $this->db->query("SELECT * FROM integerval where typeofval = '9' and demoid = '$demoid' and status = 1");
        $resultc = $qc->getResultArray();
        $num_rowsc = count($resultc);
        if (!$resultc || ($num_rowsc < 0)) {
            return;
        }
        if ($num_rowsc == 0) {
            return;
        }

        for ($i = 0; $i < $num_rowsc; $i++) {
            $details = $resultc[$i]["details"];
            $qk = $this->db->query("SELECT * FROM typevaldescription where typevalid='$id' AND id='$details' AND status = 1");

            $resultk = $qk->getResultArray();
            $num_rowsk = count($resultk);
            if (!$resultk || ($num_rowsk < 0)) {
            }
            if ($num_rowsk == 0) {
            } else {

                $valuedesc = $resultk[0]["description"];
                return $valuedesc;
                return ',';
            }
        }
    }

    function my_simple_crypt($string, $action = 'e')
    {
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

    function demo_details_save($newdata) //demo_master
    {
        $db = \Config\Database::connect();
        $description = $newdata["description"];
        $createdon = $newdata["createdon"];
        $createdby = $newdata["createdby"];
        $q = "INSERT INTO demo_details_demo VALUES ('','$description','1','$createdon','$createdby')";
        $result = $db->query($q);
        if ($result) {
            $last_demo_id = $db->InsertID();
            $sql2 = "INSERT INTO textval VALUES ('', '" . $last_demo_id . "', '3', '$description', '1');";
            $db->query($sql2);

            $sql3 = "INSERT INTO textval VALUES ('', '" . $last_demo_id . "', '10', '$description', '1');";
            $db->query($sql3);
            return $last_demo_id;
        } else {
            return false;
        }
    }
    function showactiveprojects() // demo_master
    {
        $newcheck = $this->db->query("SELECT i.* from demo_details_demo as i where  i.status = 1 ORDER BY id desc");
        $showactiveprojects = $newcheck->getResultArray();

        $num_rowsresultnewcheck = count($showactiveprojects);
        if ($num_rowsresultnewcheck > 0) {
            foreach ($showactiveprojects as $eachactiveproject) {
                $demoid[] = $eachactiveproject['id'];
                $projectname[] = $this->getval1_vc($eachactiveproject['id'], 3, 2);
                //print_r($projectname);
                $description[] = $this->getval1_vc($eachactiveproject['id'], 10, 2);
            }
            $data['demoid'] = $demoid;
            $data['projectname'] = $projectname;
            $data['description'] = $description;
            return $data;
        }
        return false;
    }
    function updateDesc($newdata, $demoid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('demo_details_demo');
        $builder->where('id', $demoid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function inputbox($demoid, $typeofval)
    {
        $qc = $this->db->query("SELECT * FROM textval where typeofval = '$typeofval' and demoid = '$demoid' and status = 1");
        $resultc = $qc->getResultArray();
        $num_rowsc = count($resultc);
        return $resultc;
    }
    function updatedata($data)
    {
        $demoid = $data['demoid'];
        $typeofval = $data['typeofval'];
        $valdetails = $data['valdetails'];
        $valid = $data['valid'];
        if ($valid == "na") {
            $q = "INSERT INTO textval VALUES ('', '$valid', '$typeofval', '$valdetails', '1')";
        } else {
            $q = "UPDATE textval SET details = '$valdetails' WHERE id = '$valid'";
        }
        $qc = $this->db->query($q);
        $result = $qc->getResultArray();
        return $result;
    }
    function addImgData($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('textval');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return true;
    }
    function addcatdata($demoid)
    {
        $qc = $this->db->query("SELECT * FROM typeofval where status = 1");
        $resultc = $qc->getResultArray();
        return $resultc;
    }
    function savecategory($data)
    {
        $demoid = $data['demoid'];
        $catlist = $data['catlist'];
        $qc = $this->db->query("INSERT INTO integerval VALUES ('', '$demoid', '9', '$catlist', '1')");
        $resultc = $qc->getResultArray();
        return $resultc;
    }
    function removetag($id)
    {
        $qc = $this->db->query("UPDATE integerval SET status = '0' WHERE id = '$id'");
        $status = $qc->getResultArray();
        if ($status) {
            $data['status'] = 'sucess';
        } else {
            $data['status'] = 'error';
        }
    }
    function getallcat_vc($sval)
    {
        // print_r($sval['searchval_0']);
        if ($this->tqDemoUsers()) {
            $qc = $this->db->query("SELECT * FROM typeofval where status = 1");
        } else {
            $qc = $this->db->query("SELECT * FROM typeofval where status = 1 && valie != '14' ");
        }
        $resultc = $qc->getResultArray();

        $num_rowsc = count($resultc);
        if (!$resultc || ($num_rowsc < 0)) {
            return;
        }
        if ($num_rowsc == 0) {
            return;
        }
        $searchval = array();
        $resulval = array();
        $searchcriteria = array();
        $postval = 0;
        for ($k = 0; $k < $num_rowsc; $k++) {
            $postvaltemp = isset($sval['searchval_' . $k]) ? $sval['searchval_' . $k] : '0';
            // print_r($postvaltemp."tt");
            //exit();
            $postval = $postval + $postvaltemp;
        }
        if ($postval == 0) {
            //  print_r($postval);
            $data['postval'] = $postval;
            $data['showactiveprojects_vc'] = $this->showsearch_vc();
            return $data;
            //print_r($showactiveprojects_vc);
        } else {
            for ($i = 0; $i < $num_rowsc; $i++) {
                if (isset($sval['searchval_' . $i]) && $sval['searchval_' . $i] > 0) {
                    $searchvalt[$i] = array();
                    $getdemoidarray = $sval['searchval_' . $i];
                    array_push($searchcriteria, $getdemoidarray);
                    $q1 = $this->db->query("SELECT * FROM integerval WHERE typeofval='9' AND details='$getdemoidarray' and status = 1");
                    $result1 = $q1->getResultArray();
                    $num_rows1 = count($result1);
                    if (!$result1 || ($num_rows1 < 0)) {
                        return;
                    }
                    if ($num_rows1 == 0) {
                        return;
                    }
                    for ($ss = 0; $ss < $num_rows1; $ss++) {
                        $demoid = $result1[$ss]["demoid"];
                        if (count($resulval) > 0) {
                            if (in_array($demoid, $resulval)) {
                                array_push($searchvalt[$i], $demoid);
                            }
                        } else {
                            array_push($searchvalt[$i], $demoid);
                        }
                    }
                    $resulval = $searchvalt[$i];
                }
            }
            $searchcriteriaval = count($searchcriteria);
            echo '<div class="alert alert-block alert-success">';
            echo 'Search criteria selected are :- ';
            for ($ssf = 0; $ssf < $searchcriteriaval; $ssf++) {
                $this->showsearchpar_vc($searchcriteria[$ssf], session()->get('username'));
                if ($ssf < ($searchcriteriaval - 1)) {
                    echo ', ';
                }
            }
            echo '</div>';
            $conter = 1;
            for ($kk = 0; $kk < count($resulval); $kk++) {
                if ($resulval[0]) {
                    $showactiveprojects_vc[] = $this->showsearch_vc($resulval[$kk], session()->get('username'), $conter);
                    $conter = $conter + 1;
                }
            }
            $data['searchactiveprojects_vc'] = $showactiveprojects_vc;
            return $data;
        }
    }
    function showsearchpar_vc($id, $usersetting)
    {
        global $database;
        if ($this->tqDemoUsers()) {
            $qc = $this->db->query("SELECT * FROM typevaldescription where id='$id'");
        } else {
            $qc = $this->db->query("SELECT * FROM typevaldescription where id='$id' && typevalid != '14' && id!='91'");
        }

        $resultc = $qc->getResultArray();
        $valuedesc = $resultc[0]["description"];
        $typevalid = $resultc[0]["typevalid"];
        $this->typeofvalget_vc($typevalid);
        echo $valuedesc;
    }
    function typeofvalget_vc($typevalid)
    {
        global $database;
        $qc = $this->db->query("SELECT * FROM typeofval where valie='$typevalid'");
        $resultc = $qc->getResultArray();
        $valuedesc = $resultc[0]["valuedesc"];
        echo $valuedesc;
        echo ' : ';
    }

    function showsearch_vc($demoid = '', $usersetting = '', $conter = '')
    {
        if ($this->tqDemoUsers()) {
            if ($demoid) {
                // print_r($demoid);
                $newcheck = $this->db->query("SELECT i.*,d.description FROM integerval as i
                left join demo_details_demo as  d on d.id = i.demoid 
                 where i.demoid = '$demoid' and d.status = 1 and i.details= '91'");
                $resultnewcheck = $newcheck->getResultArray();
                $num_rowsresultnewcheck = count($resultnewcheck);
                // print_r($num_rowsresultnewcheck);
                // exit();
                if ($num_rowsresultnewcheck > 0) {
                    for ($t = 0; $t < $num_rowsresultnewcheck; $t++) {
                        $data['demo_det'] = $this->getval1_vc($demoid, 3, 2);
                        $data['casestudy'] = $this->getval1_vc($demoid, 10, 2);
                        $data['casestudy_pdf'] = $this->getval1_vc($demoid, 7, 2);
                        $data['vid'] = $this->getval1_vc($demoid, 8, 2);
                        $data['vidx'] = $this->getval1_vc($demoid, 4, 2);
                        $data['courselink'] = $this->getval1_vc($demoid, 6, 2);
                        $data['demoid'] = $resultnewcheck[$t]['demoid'];
                        $data['description'] = $resultnewcheck[$t]['description'];
                        $result[] = $data;
                    }
                    //print_r($result);
                    //exit();
                    return $result;
                } else {
                    return;
                }
            } else {
                $newcheck = $this->db->query("SELECT i.*,d.description FROM integerval as i
                                     left join demo_details_demo as  d on d.id = i.demoid 
                                    where i.status = 1 and i.details= '91' and d.status = 1");
                $data['resultnewcheck'] = $newcheck->getResultArray();

                $num_rowsresultnewcheck = count($data['resultnewcheck']);
                if ($num_rowsresultnewcheck > 0) {
                    for ($t = 0; $t < $num_rowsresultnewcheck; $t++) {
                        $data['demo_det'] = $this->getval1_vc($data['resultnewcheck'][$t]['demoid'], 3, 2);
                        $data['casestudy'] = $this->getval1_vc($data['resultnewcheck'][$t]['demoid'], 10, 2);
                        $data['casestudy_pdf'] = $this->getval1_vc($data['resultnewcheck'][$t]['demoid'], 7, 2);
                        $data['vid'] = $this->getval1_vc($data['resultnewcheck'][$t]['demoid'], 8, 2);
                        $data['vidx'] = $this->getval1_vc($data['resultnewcheck'][$t]['demoid'], 4, 2);
                        $data['courselink'] = $this->getval1_vc($data['resultnewcheck'][$t]['demoid'], 6, 2);
                        $data['demoid'] = $data['resultnewcheck'][$t]['demoid'];
                        $data['description'] = $data['resultnewcheck'][$t]['description'];
                        $result[] = $data;
                    }
                    return $result;
                } else {
                    return;
                }
            }
        }
    }
    function getallcatdata()
    {
        $uid = $_GET['us43k'];
        $enc = $_GET['jdick18'];
        $qc = $this->db->query("SELECT * FROM typeofval where status = 1");
        $resultc = $qc->getResultArray();

        $num_rowsc = count($resultc);
        if (!$resultc || ($num_rowsc < 0)) {
            return;
        }
        if ($num_rowsc == 0) {
            return;
        }
        $searchval = array();
        $resulval = array();
        $searchcriteria = array();
        $postval = 0;
        for ($k = 0; $k < $num_rowsc; $k++) {
            $postvaltemp = 0; //isset($_POST['searchval_' . $k])? $_POST['searchval_' . $k] : '';
            $postval = $postval + $postvaltemp;
        }
        if ($postval == 0) {
            $data['postval'] = $postval;
            $data['showactiveprojects_vc'] = $this->showmycartactiveprojects($uid, $enc);
            return $data;
            //print_r($showactiveprojects_vc);
        } else {
        }
    }
    function productByCode($data)
    {
        $udemoid = $data['udemoid'];
        $qc = $this->db->query("SELECT id FROM demo_details_demo where id='" . $udemoid . "' AND status = 1");
        $productByCode = $qc->getResultArray();
        $row_id = $productByCode[0]["id"];
        $itemArray = array(
            $row_id => array(
                'demo_id' => $row_id,
                'product_name' => $data['product_name'],
                'description' => $data['description'],
                'case_study' => $data['case_study'],
                'video_one' => $data['video_one'],
                'video_launch' => $data['video_launch'],
                'course_link' => $data['course_link'],
            )
        );
        if (!empty($_SESSION["dochk_cart_item"])) {
            if (in_array($row_id, array_keys($_SESSION["dochk_cart_item"]))) {
                foreach ($_SESSION["dochk_cart_item"] as $k => $v) {
                }
                $returncount['count'] = count($_SESSION["dochk_cart_item"]);
            } else {
                $_SESSION["dochk_cart_item"] = array_merge($_SESSION["dochk_cart_item"], $itemArray);

                $returncount['count'] = count($_SESSION["dochk_cart_item"]);
            }
        } else {
            $_SESSION["dochk_cart_item"] = $itemArray;
            $returncount['count'] = count($_SESSION["dochk_cart_item"]);
        }
        return $returncount;
    }
    function showmycartactiveprojects($uid, $enc)
    {
        $uid = $this->my_simple_crypt($uid, 'd');
        $enc = $this->my_simple_crypt($enc, 'd');
        $q2 = $this->db->query("SELECT * FROM mycart where keyval = '" . $enc . "' AND uid = '" . $uid . "'");
        $result2 = $q2->getResultArray();
        $num_rows2 = count($result2);
        if ($result2) {
            for ($t = 0; $t < $num_rows2; $t++) {
                $did[] = $result2[$t]["demoid"];
                $statusCount = $result2[$t]["status"];
                $finexp = explode(",", $did[0]);
                $data['gdemoid'] = $did[0];
                foreach ($finexp as $demoid) {
                    $newcheck = $this->db->query("SELECT * FROM demo_details_demo  where id='$demoid' AND status=1 ORDER BY id desc");
                    $newcheckresult = $newcheck->getResultArray();
                    $description = $newcheckresult[0]["description"];
                    $data['casestudy'] = $this->getval1_vc($demoid, 10, 2);
                    $data['casestudy_pdf'] = $this->getval1_vc($demoid, 7, 2);
                    $data['vid'] = $this->getval1_vc($demoid, 8, 2);
                    $data['vidx'] = $this->getval1_vc($demoid, 4, 2);
                    $data['courselink'] = $this->getval1_vc($demoid, 6, 2);
                    $data['demoid'] = $demoid;
                    $data['description'] = $description;
                    $result[] = $data;
                }
            }
        }

        $statusCount = $statusCount + 1;

        $updateStatus = "update mycart set status ='" . $statusCount . "' where keyval = '" . $enc . "' AND uid = '" . $uid . "'";

        $resultupdateStatus = $this->db->query($updateStatus);

        return $result;
    }
    function wsshowdetails($demoid)
    {
        $db = \Config\Database::connect();
        $qc = $db->query("SELECT * FROM typeofval where status = 1");
        $resultc = $qc->getResultArray();
        $num_rowsc = count($resultc);
        for ($i = 0; $i < $num_rowsc; $i++) {
            $id = $resultc[$i]["valie"];
            $valuedesc = $resultc[$i]["valuedesc"];
            $getids = $this->getids($id, $demoid);
            $data[] = [
                'id' => $id,
                'valuedesc' => $valuedesc,
                'getids' => $getids
            ];
        }
        if (!$resultc || ($num_rowsc < 0)) {
            return;
        }
        if ($num_rowsc == 0) {
            return;
        } else {
            return $data;
        }
    }
    function getcatdata()
    {
        $qc = $this->db->query("SELECT * FROM typeofval where status = 1");
        $resultc = $qc->getResultArray();
        $num_rowsc = count($resultc);
        return $resultc;
    }
    function getdata($valid)
    {
        $qc = $this->db->query("SELECT * FROM typeofval where valie = '$valid'");
        $resultc = $qc->getResultArray();
        $num_rowsc = count($resultc);
        if (!$resultc || ($num_rowsc < 0)) {
            return;
        }
        if ($num_rowsc == 0) {
            return;
        }

        $valuedesc = $resultc[0]["valuedesc"];
        return $valuedesc;
    }
    function getcatitemdata($valid)
    {
        $qc = $this->db->query("SELECT * FROM typevaldescription where typevalid='$valid' AND status = 1");
        $resultc = $qc->getResultArray();
        $num_rowsc = count($resultc);
        if (!$resultc || ($num_rowsc < 0)) {
            return;
        }
        if ($num_rowsc == 0) {
            return;
        }
        return $resultc;
    }
    function additemval($id, $categoryitem)
    {
        $q = $this->db->query("INSERT INTO typevaldescription VALUES ('', '$id', '$categoryitem', '1', '1')");
        $result = $q->getResultArray();
        return $result;
    }
    function delcatitem($valid)
    {
        $q = $this->db->query("UPDATE typevaldescription SET status = '0' WHERE id = '$valid'");
        $result = $q->getResultArray();
        return $result;
    }
    function addcategoryval($category)
    {
        $q = $this->db->query("INSERT INTO typeofval VALUES ('', '$category', '1', '1')");
        $result = $q->getResultArray();
        return $result;
    }
    function showreport()
    {
        $q2 = $this->db->query("SELECT * FROM mycart where expirystatus=1 ORDER BY cartod desc limit 30");
        $result2 = $q2->getResultArray();
        $num_rows2 = count($result2);
        if (!$result2 || ($num_rows2 < 0)) {
            echo "Error displaying info";
            return;
        }
        if ($num_rows2 == 0) {
            echo "No Reports available to view.";
            return;
        } else {
            return $result2;
        }
    }
    function get_cart_details($cart_id)
    {
        //print_r($cart_id);
        $q2 = $this->db->query("SELECT * FROM mycart where cartod='$cart_id' ORDER BY cartod desc");
        $resultc = $q2->getResultArray();
        //print_r($resultc);
        $num_rowsc = count($resultc);
        if (!$resultc || ($num_rowsc < 0)) {
            echo "No records Found.";
            return;
        } else if ($num_rowsc == 0) {
            echo "No records Found.";
            return;
        } else {
            $cartDetails = array(
                'cartod' => $resultc[0]["cartod"],
                'demoid' => $resultc[0]["demoid"],
                'uid' => $resultc[0]["uid"],
                'comment' => $resultc[0]["comment"],
                'keyval' => $resultc[0]["keyval"],
                'demodate' => $resultc[0]["demodate"],
            );
            //echo '<pre>';
            //print_r($cartDetails);
            //exit();

            return $cartDetails;
        }
    }
    function updatecartdata($demodate, $p)
    {
        $q = $this->db->query("UPDATE mycart SET comment = '" . $p['comment'] . "' , demodate = '" . $demodate . "' WHERE cartod = '" . $p['cart_id'] . "'");
        $resultc = $q->getResultArray();
        return $resultc;
    }
    function getallcat($uid, $enc)
    {
        $qc = $this->db->query("SELECT * FROM typeofval where status = 1");
        $resultc = $qc->getResultArray();
        $num_rowsc = count($resultc);
        if (!$resultc || ($num_rowsc < 0)) {
            return;
        }
        if ($num_rowsc == 0) {
            return;
        }
        $searchval = array();
        $resulval = array();
        $searchcriteria = array();
        $postval = 0;
        for ($k = 0; $k < $num_rowsc; $k++) {
            $postvaltemp = 0;
            $postval = $postval + $postvaltemp;
        }
        if ($postval == 0) {
            $data['postval'] = $postval;
            $data['showactiveprojects_vc'] = $this->showmycartactiveprojects($uid, $enc);
            return $data;
        }
    }
}
