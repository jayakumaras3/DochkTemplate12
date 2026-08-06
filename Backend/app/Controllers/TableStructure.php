<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TableStructure extends BaseController
{
    public function users()
    {
        try {
            $db = \Config\Database::connect();
            
            // Get table structure
            $query = $db->query("DESCRIBE users");
            $columns = $query->getResultArray();
            
            return $this->response->setJSON([
                'status' => 'success',
                'table' => 'users',
                'columns' => $columns
            ]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
