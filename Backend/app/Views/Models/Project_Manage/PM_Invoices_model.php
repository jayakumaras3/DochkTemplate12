<?php

namespace App\Models\Project_Manage;

use CodeIgniter\Model;

class PM_Invoices_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }
   
    
}
