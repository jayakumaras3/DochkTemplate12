<?php

namespace App\Diagnostics;

use App\Controllers\BaseController;

/**
 * Database Configuration Diagnostic Test
 * Run directly via URL: /api/db-test
 */

class DatabaseTest extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        header('Content-Type: text/html; charset=utf-8');
    }

    public function index()
    {
        echo "<pre style='background: #f0f0f0; padding: 20px; font-family: monospace; color: #333;'>";
        
        echo "=== DATABASE DIAGNOSTIC TEST ===\n\n";

        // Test 1: Connection
        echo "TEST 1: Database Connection\n";
        try {
            $this->db->connect();
            echo "✅ Connected\n";
        } catch (\Exception $e) {
            echo "❌ Connection failed: " . $e->getMessage() . "\n";
            return;
        }

        // Test 2: Users table exists
        echo "\nTEST 2: Users Table Structure\n";
        try {
            $result = $this->db->query("DESC `users` LIMIT 1");
            $cols = $result->getFieldNames();
            echo "✅ Table exists. Columns found: " . count($cols) . "\n";
            echo "   - userid exists: " . (in_array('userid', $cols) ? 'YES' : 'NO') . "\n";
            echo "   - update_at exists: " . (in_array('update_at', $cols) ? 'YES' : 'NO') . "\n";
            echo "   - id_user exists: " . (in_array('id_user', $cols) ? 'YES' : 'NO') . "\n";
            echo "   - email exists: " . (in_array('email', $cols) ? 'YES' : 'NO') . "\n";
            echo "   - valid exists: " . (in_array('valid', $cols) ? 'YES' : 'NO') . "\n";
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }

        // Test 3: Find a user
        echo "\nTEST 3: Find Test User\n";
        try {
            $user = $this->db->table('users')
                ->where('email', 'jayakumar.k@touchstonelc.com')
                ->limit(1)
                ->get()
                ->getRow();
            
            if ($user) {
                echo "✅ User found\n";
                echo "   - id_user: " . $user->id_user . "\n";
                echo "   - email: " . $user->email . "\n";
                echo "   - username: " . $user->username . "\n";
                echo "   - userid (current): " . (empty($user->userid) ? '(empty)' : substr($user->userid, 0, 40) . '...') . "\n";
                echo "   - update_at (current): " . $user->update_at . "\n";
                echo "   - valid: " . $user->valid . "\n";
                
                // Test 4: Try UPDATE
                echo "\nTEST 4: Test UPDATE Query\n";
                try {
                    $testToken = bin2hex(random_bytes(32));
                    $testTime = date('Y-m-d H:i:s');
                    
                    echo "   Attempting to update user " . $user->id_user . " with test token...\n";
                    echo "   - New token: " . substr($testToken, 0, 40) . "...\n";
                    echo "   - New timestamp: " . $testTime . "\n";
                    
                    $result = $this->db->table('users')
                        ->where('id_user', $user->id_user)
                        ->where('valid', 1)
                        ->update([
                            'userid'    => $testToken,
                            'update_at' => $testTime
                        ]);
                    
                    echo "   - Update returned: " . ($result ? 'true' : 'false') . "\n";
                    
                    // Check for DB error
                    $dbError = $this->db->error();
                    if (!empty($dbError)) {
                        echo "   ❌ Database Error: " . json_encode($dbError) . "\n";
                    } else {
                        echo "   ✅ No database error reported\n";
                    }
                    
                    // Verify the update
                    $updated = $this->db->table('users')
                        ->where('id_user', $user->id_user)
                        ->get()
                        ->getRow();
                    
                    echo "   - Verify userid updated: " . (substr($updated->userid, 0, 40) === substr($testToken, 0, 40) ? 'YES' : 'NO') . "\n";
                    echo "   - Verify update_at updated: " . ($updated->update_at === $testTime ? 'YES' : 'NO') . "\n";
                    
                } catch (\Exception $e) {
                    echo "   ❌ UPDATE FAILED: " . $e->getMessage() . "\n";
                    echo "   File: " . $e->getFile() . "\n";
                    echo "   Line: " . $e->getLine() . "\n";
                }
            } else {
                echo "❌ User not found\n";
            }
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }

        // Test 5: Check password_resets table
        echo "\nTEST 5: Password Resets Table\n";
        try {
            $result = $this->db->query("DESC `password_resets` LIMIT 1");
            echo "✅ Table exists\n";
            $cols = $result->getFieldNames();
            echo "   Columns: " . implode(', ', $cols) . "\n";
        } catch (\Exception $e) {
            echo "⚠️  Table does not exist (this is OK): " . $e->getMessage() . "\n";
        }

        // Test 6: Check database permissions
        echo "\nTEST 6: Database User Permissions\n";
        try {
            $result = $this->db->query("SELECT USER() as user, version() AS version");
            $row = $result->getRow();
            echo "✅ Connected as: " . $row->user . "\n";
            echo "   MySQL Version: " . $row->version . "\n";
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }

        echo "\n=== END TEST ===\n";
        echo "</pre>";
    }
}
