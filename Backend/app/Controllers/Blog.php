<?php
namespace App\Controllers;

use CodeIgniter\Controller;
class Blog  extends BaseController
{

    use \CodeIgniter\API\ResponseTrait;
    public function index(){
        return view('welcome');
    }
    public function show($id){
        return $this->respond($id);
    }
}
?>