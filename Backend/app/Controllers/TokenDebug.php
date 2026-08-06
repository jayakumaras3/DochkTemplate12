<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TokenDebug extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
    }

    /**
     * Debug endpoint to check password reset token status
     * Usage: GET /api/token-debug?email=user@example.com&action=show
     */
    public function index()
    {
        try {
            $action = $this->request->getGet('action') ?? 'show';
            $email = $this->request->getGet('email');
            $token = $this->request->getGet('token');

            $output = [];
            $output['timestamp'] = date('Y-m-d H:i:s');
            $output['action'] = $action;

            // Action 1: Show user and their token
            if ($action === 'show' && $email) {
                $output['email'] = $email;
                $user = $this->db->table('users')
                    ->where('email', $email)
                    ->where('valid', 1)
                    ->get()
                    ->getRow();

                if (!$user) {
                    $output['status'] = 'error';
                    $output['message'] = 'User not found';
                    return $this->response->setJSON($output);
                }

                $output['status'] = 'found';
                $output['user'] = [
                    'id_user' => $user->id_user,
                    'email' => $user->email,
                    'username' => $user->username,
                ];

                // Show token info
                $output['userid_field'] = [
                    'is_empty' => empty($user->userid),
                    'length' => strlen($user->userid ?? ''),
                    'first_40_chars' => empty($user->userid) ? null : substr($user->userid, 0, 40),
                    'last_20_chars' => empty($user->userid) ? null : substr($user->userid, -20),
                ];

                $output['update_at'] = $user->update_at ?? '(null)';

                if (!empty($user->update_at)) {
                    $timestamp = strtotime($user->update_at);
                    $now = time();
                    $age = $now - $timestamp;
                    $output['token_age_seconds'] = $age;
                    $output['token_age_minutes'] = round($age / 60, 2);
                    $output['expires_in_seconds'] = max(0, (15 * 60) - $age);
                    $output['token_expired'] = $age > (15 * 60);
                }

                // Check password_resets table if it exists
                if ($this->tableExists('password_resets')) {
                    $output['password_resets_table'] = 'EXISTS';
                    
                    $tokens = $this->db->table('password_resets')
                        ->where('user_id', $user->id_user)
                        ->orderBy('created_at', 'DESC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();

                    $output['password_resets_records'] = [];
                    foreach ($tokens as $rec) {
                        $output['password_resets_records'][] = [
                            'token_hash_start' => substr($rec['token'], 0, 40),
                            'used' => (int) $rec['used'],
                            'created_at' => $rec['created_at'],
                            'expires_at' => $rec['expires_at'],
                            'used_at' => $rec['used_at'] ?? null,
                        ];
                    }
                } else {
                    $output['password_resets_table'] = 'DOES NOT EXIST';
                }
            }

            // Action 2: Test token lookup
            if ($action === 'test-lookup' && $token) {
                $output['token_input'] = [
                    'raw' => substr($token, 0, 50),
                    'length' => strlen($token),
                ];

                // Try to decode
                $decoded = urldecode($token);
                $output['token_after_decode'] = [
                    'value' => substr($decoded, 0, 50),
                    'length' => strlen($decoded),
                ];

                // Try hash
                $hashed = hash('sha256', $decoded);
                $output['token_sha256_hash'] = substr($hashed, 0, 50);

                // Try lookup in users table
                $output['lookup_results'] = [];
                $userByToken = $this->db->table('users')
                    ->where('userid', $decoded)
                    ->where('valid', 1)
                    ->get()
                    ->getRow();

                if ($userByToken) {
                    $output['lookup_results']['users_table'] = 'FOUND';
                    $output['lookup_results']['user_id'] = $userByToken->id_user;
                    $output['lookup_results']['email'] = $userByToken->email;
                } else {
                    $output['lookup_results']['users_table'] = 'NOT FOUND';
                }

                // Try lookup in password_resets table
                if ($this->tableExists('password_resets')) {
                    $resetRec = $this->db->table('password_resets')
                        ->where('token', $hashed)
                        ->get()
                        ->getRow();

                    if ($resetRec) {
                        $output['lookup_results']['password_resets_table'] = 'FOUND';
                        $output['lookup_results']['user_id'] = $resetRec->user_id;
                        $output['lookup_results']['used'] = (int) $resetRec->used;
                    } else {
                        $output['lookup_results']['password_resets_table'] = 'NOT FOUND';
                    }
                }
            }

            return $this->response->setJSON($output);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    private function tableExists($tableName)
    {
        try {
            $result = $this->db->query("SHOW TABLES LIKE ?", [$tableName]);
            $rows = $result->getResultArray();
            return !empty($rows);
        } catch (\Exception $e) {
            return false;
        }
    }
}
