<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User_login\Login_model;

#[\AllowDynamicProperties]
class PasswordReset extends BaseController
{
    private $db;
    private $login_model;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->login_model = new Login_model();
        
        // Enable CORS headers for API requests
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token');
        header('Access-Control-Max-Age: 3600');
    }

    // POST /api/forgot_password
    public function forgot_password()
    {
        try {
            log_message('info', 'forgot_password: [1] START');
            $json = $this->request->getJSON(true);
            log_message('info', 'forgot_password: [2] JSON parsed');
            
            $email = isset($json['email']) ? filter_var(trim($json['email']), FILTER_SANITIZE_EMAIL) : null;
            log_message('info', 'forgot_password: [3] Email extracted: ' . (empty($email) ? '(empty)' : 'OK'));

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                log_message('info', 'forgot_password: [4] Email invalid');
                return $this->response->setStatusCode(400)
                    ->setJSON(['status' => 'error', 'message' => 'Invalid email address']);
            }

            log_message('info', 'forgot_password: [5] Email validated, querying user');
            $user = $this->db->table('users')
                ->where('email', $email)
                ->where('valid', 1)
                ->get()
                ->getRow();
            log_message('info', 'forgot_password: [6] User query done');

            if (!$user) {
                log_message('info', 'forgot_password: [7] User not found');
                return $this->response->setStatusCode(200)
                    ->setJSON(['status' => 'success', 'message' => 'If that email exists in our system, you will receive a password reset link shortly']);
            }

            log_message('info', 'forgot_password: [8] User found, generating token');

            $token = null;

            if ($this->tableExists('password_resets')) {
                $now = date('Y-m-d H:i:s');
                $expiresAt = date('Y-m-d H:i:s', time() + (15 * 60));
                $token = bin2hex(random_bytes(32));
                $tokenHash = hash('sha256', $token);

                // Invalidate all previous unused reset tokens for this user.
                $this->db->table('password_resets')
                    ->where('user_id', $user->id_user)
                    ->where('used', 0)
                    ->update([
                        'used' => 1,
                        'used_at' => $now,
                    ]);

                $inserted = $this->db->table('password_resets')->insert([
                    'user_id' => $user->id_user,
                    'token' => $tokenHash,
                    'used' => 0,
                    'created_at' => $now,
                    'expires_at' => $expiresAt,
                    'used_at' => null,
                ]);

                if (!$inserted) {
                    $dbError = $this->db->error();
                    log_message('error', 'forgot_password: failed to insert password_resets row: ' . json_encode($dbError));
                    return $this->response->setStatusCode(500)
                        ->setJSON(['status' => 'error', 'message' => 'Unable to create reset link']);
                }

                log_message('info', 'forgot_password: [9] secure token generated and stored in password_resets');
            } else {
                // Legacy fallback for deployments that still use users.userid.
                $userIdColumnType = $this->getUserIdColumnType();
                if ($this->isUserIdColumnNumeric()) {
                    $token = (string) $user->id_user;
                } else {
                    $tokenMaxLen = $this->getUserIdTokenMaxLength();
                    $token = $this->generateResetToken($tokenMaxLen);
                }

                log_message('info', 'forgot_password: [9] legacy token generated: ' . substr($token, 0, 40) . '... | userid type: ' . $userIdColumnType);

                $updatedToken = $this->db->table('users')
                    ->where('id_user', $user->id_user)
                    ->where('valid', 1)
                    ->update(['userid' => $token]);

                if (!$updatedToken) {
                    $dbError = $this->db->error();
                    log_message('error', 'forgot_password: failed to store legacy token in users.userid: ' . json_encode($dbError));
                    return $this->response->setStatusCode(500)
                        ->setJSON(['status' => 'error', 'message' => 'Unable to create reset link']);
                }

                // Now set the timestamp in whichever column exists.
                $tsCol = $this->getUsersTimestampColumn();
                $updatedTs = $this->db->table('users')
                    ->where('id_user', $user->id_user)
                    ->update([$tsCol => date('Y-m-d H:i:s')]);

                if (!$updatedTs) {
                    $dbError = $this->db->error();
                    log_message('error', 'forgot_password: failed to store token timestamp in users.' . $tsCol . ': ' . json_encode($dbError));
                    return $this->response->setStatusCode(500)
                        ->setJSON(['status' => 'error', 'message' => 'Unable to create reset link']);
                }

                log_message('info', 'forgot_password: token stored using timestamp column: ' . $tsCol);
            }

            log_message('info', 'forgot_password: [12] Building reset link');
            $userName = $user->name ?? ($user->username ?? 'User');
            $resetLink = base_url('forgot_password/reset_password/' . urlencode($token));
            log_message('info', 'forgot_password: [13] Reset link built');

            try {
                $this->send_reset_email($email, $userName, $resetLink);
            } catch (\Throwable $emailErr) {
                log_message('warning', 'forgot_password: email failed (ignored): ' . $emailErr->getMessage());
            }

            return $this->response->setStatusCode(200)
                ->setJSON([
                    'status' => 'success',
                    'message' => 'If the email address is registered, a password reset link has been sent.',
                ]);
        } catch (\Throwable $e) {
            log_message('error', 'forgot_password error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Server error',
                    'debug' => $e->getMessage()
                ]);
        }
    }

    // GET /api/verify_token?token=XYZ
    public function verify_token()
    {
        try {
            $token = $this->request->getGet('token');
            $token = $token ? trim(urldecode($token)) : null;

            if (empty($token)) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['status' => false, 'message' => 'Token parameter is missing']);
            }

            if ($this->tableExists('password_resets')) {
                $tokenHash = hash('sha256', $token);

                $record = $this->db->table('password_resets pr')
                    ->select('pr.user_id, pr.used, pr.expires_at, u.valid')
                    ->join('users u', 'u.id_user = pr.user_id', 'left')
                    ->where('pr.token', $tokenHash)
                    ->get()
                    ->getRow();

                if (!$record) {
                    return $this->response->setStatusCode(400)
                        ->setJSON(['status' => false, 'message' => 'Token is invalid or already used']);
                }

                if (isset($record->valid) && (int) $record->valid !== 1) {
                    return $this->response->setStatusCode(400)
                        ->setJSON(['status' => false, 'message' => 'Token is invalid']);
                }

                if ((int) $record->used === 1) {
                    return $this->response->setStatusCode(400)
                        ->setJSON(['status' => false, 'message' => 'This password reset link has already been used. Request a new one.']);
                }

                if (empty($record->expires_at) || (time() >= strtotime($record->expires_at))) {
                    return $this->response->setStatusCode(400)
                        ->setJSON(['status' => false, 'message' => 'Token has expired. Please request a new password reset.']);
                }

                return $this->response->setStatusCode(200)
                    ->setJSON(['status' => true, 'user_id' => $record->user_id, 'message' => 'Token is valid']);
            }

            // Legacy fallback: users.userid + users.(updated_at|update_at)
            $tsCol = $this->getUsersTimestampColumn();
            $tokenForLookup = $this->fitTokenToUserIdColumn($token);

            $user = $this->db->table('users')
                ->select("id_user, email, userid, {$tsCol} as ts_value")
                ->where('userid', $tokenForLookup)
                ->where('valid', 1)
                ->get()
                ->getRow();

            if (!$user) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['status' => false, 'message' => 'Token is invalid or already used']);
            }

            if (empty($user->ts_value)) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['status' => false, 'message' => 'Token is invalid']);
            }

            $ageSeconds = time() - strtotime($user->ts_value);
            if ($ageSeconds > 900) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['status' => false, 'message' => 'Token has expired. Please request a new password reset.']);
            }

            return $this->response->setStatusCode(200)
                ->setJSON(['status' => true, 'user_id' => $user->id_user, 'message' => 'Token is valid']);

        } catch (\Throwable $e) {
            log_message('error', 'verify_token: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => false, 'message' => 'Server error', 'debug' => $e->getMessage()]);
        }
    }

    // POST /api/reset_password
    public function reset_password()
    {
        try {
            $json     = $this->request->getJSON(true);
            $token    = isset($json['token'])    ? trim($json['token'])    : null;
            $password = isset($json['password']) ? trim($json['password']) : null;

            log_message('info', 'reset_password called with token: ' . substr($token, 0, 10) . '...');

            // Validate inputs
            if (empty($token)) {
                log_message('warning', 'reset_password: No token provided');
                return $this->response->setStatusCode(400)
                    ->setJSON(['status' => false, 'message' => 'Token is required']);
            }

            if (empty($password)) {
                log_message('warning', 'reset_password: No password provided');
                return $this->response->setStatusCode(400)
                    ->setJSON(['status' => false, 'message' => 'Password is required']);
            }

            if (strlen($password) < 8) {
                log_message('warning', 'reset_password: Password too short');
                return $this->response->setStatusCode(400)
                    ->setJSON(['status' => false, 'message' => 'Password must be at least 8 characters long']);
            }

            $token = trim($token);
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

            if ($this->tableExists('password_resets')) {
                $tokenHash = hash('sha256', $token);

                $record = $this->db->table('password_resets pr')
                    ->select('pr.id, pr.user_id, pr.used, pr.expires_at, u.valid')
                    ->join('users u', 'u.id_user = pr.user_id', 'left')
                    ->where('pr.token', $tokenHash)
                    ->get()
                    ->getRow();

                if (!$record) {
                    return $this->response->setStatusCode(400)
                        ->setJSON(['status' => false, 'message' => 'Token is invalid']);
                }

                if (isset($record->valid) && (int) $record->valid !== 1) {
                    return $this->response->setStatusCode(400)
                        ->setJSON(['status' => false, 'message' => 'Token is invalid']);
                }

                if ((int) $record->used === 1) {
                    return $this->response->setStatusCode(400)
                        ->setJSON(['status' => false, 'message' => 'This password reset link has already been used. Request a new one.']);
                }

                if (empty($record->expires_at) || (time() >= strtotime($record->expires_at))) {
                    return $this->response->setStatusCode(400)
                        ->setJSON(['status' => false, 'message' => 'Token has expired. Please request a new password reset.']);
                }

                $this->db->transStart();

                $updated = $this->db->table('users')
                    ->where('id_user', $record->user_id)
                    ->where('valid', 1)
                    ->update([
                        'password' => $hashedPassword,
                    ]);

                $markedUsed = $this->db->table('password_resets')
                    ->where('id', $record->id)
                    ->where('used', 0)
                    ->update([
                        'used' => 1,
                        'used_at' => date('Y-m-d H:i:s'),
                    ]);

                $this->db->transComplete();

                if (!$this->db->transStatus() || !$updated || !$markedUsed) {
                    $dbError = $this->db->error();
                    log_message('error', 'reset_password: secure flow update failed: ' . json_encode($dbError));
                    return $this->response->setStatusCode(500)
                        ->setJSON(['status' => false, 'message' => 'Failed to update password']);
                }

                return $this->response->setStatusCode(200)
                    ->setJSON(['status' => true, 'message' => 'Password reset successfully. You can now login with your new password.']);
            }

            // Legacy fallback: users.userid + users.(updated_at|update_at)
            $tsCol = $this->getUsersTimestampColumn();
            $tokenForLookup = $this->fitTokenToUserIdColumn($token);

            $user = $this->db->table('users')
                ->select("id_user, email, userid, {$tsCol} as ts_value")
                ->where('userid', $tokenForLookup)
                ->where('valid', 1)
                ->get()
                ->getRow();

            if (!$user) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['status' => false, 'message' => 'Token is invalid']);
            }

            if (empty($user->ts_value)) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['status' => false, 'message' => 'Token is invalid']);
            }

            $tokenAgeSeconds = time() - strtotime($user->ts_value);
            if ($tokenAgeSeconds > (15 * 60)) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['status' => false, 'message' => 'Token has expired. Please request a new password reset.']);
            }

            $updated = $this->db->table('users')
                ->where('id_user', $user->id_user)
                ->where('valid', 1)
                ->update([
                    'password' => $hashedPassword,
                    'userid'   => null,
                    $tsCol     => null,
                ]);

            if (!$updated) {
                return $this->response->setStatusCode(500)
                    ->setJSON(['status' => false, 'message' => 'Failed to update password']);
            }

            return $this->response->setStatusCode(200)
                ->setJSON(['status' => true, 'message' => 'Password reset successfully. You can now login with your new password.']);

        } catch (\Throwable $e) {
            log_message('error', 'reset_password error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => false, 'message' => 'Server error']);
        }
    }

    // ============= HELPER METHODS =============

    /**
     * Send password reset email with HTML template
     * @param string $email
     * @param string $userName
     * @param string $resetLink
     * @return bool
     */
    private function send_reset_email($email, $userName, $resetLink)
    {
        try {
            $emailConfig = config('Email');

            if (!$emailConfig || empty($emailConfig->SMTPHost)) {
                log_message('warning', 'send_reset_email: SMTP not configured, skipping email');
                return true;
            }

            $htmlMessage = $this->get_email_template($userName, $resetLink);

            $emailService = \Config\Services::email();
            $emailService->setFrom('do-not-reply@touchstonelc.com', 'Dochek Security');
            $emailService->setTo($email);
            $emailService->setSubject('Reset Your Password - Dochek');
            $emailService->setMessage($htmlMessage);
            $emailService->setMailType('html');
            $emailService->send(false);

            log_message('info', 'send_reset_email: email sent to ' . substr($email, 0, 10) . '...');
            return true;

        } catch (\Throwable $e) {
            log_message('warning', 'send_reset_email: email failed (ignored): ' . $e->getMessage());
            return true; // Never fail the reset flow because of email
        }
    }

    /**
     * Generate professional HTML email template
     * @param string $userName
     * @param string $resetLink
     * @return string
     */
    private function get_email_template($userName, $resetLink)
    {
        $currentYear = date('Y');
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background-color: #f9f9f9; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .cta-button { display: inline-block; background-color: #667eea; color: white; padding: 12px 30px; border-radius: 5px; text-decoration: none; margin: 20px 0; font-weight: bold; }
        .cta-button:hover { background-color: #764ba2; }
        .alert { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .footer { background-color: #f0f0f0; padding: 20px; text-align: center; font-size: 12px; color: #666; border-top: 1px solid #ddd; }
        .divider { height: 1px; background-color: #eee; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color:black">🔐 Password Reset Request</h1>
        </div>
        
        <div class="content">
            <p>Hi <strong>{$userName}</strong>,</p>
            
            <p>We received a request to reset the password for your Dochek account. If you didn't make this request, please ignore this email.</p>
            
            <p style="text-align: center;">
                <a href="{$resetLink}" class="cta-button">Reset Your Password</a>
            </p>
            
            <p>Or copy this link in your browser:<br>
            <small style="word-break: break-all; color: #666;">
                {$resetLink}
            </small></p>
            
            <div class="divider"></div>
            
            <div class="alert">
                <strong>⏰ Important:</strong> *This password reset link is valid for one-time use only. Once your password has been reset, the link will automatically expire.
            </div>
            
            <p><strong>For your security:</strong></p>
            <ul>
                <li>Never share this link with anyone</li>
                <li>Your password should be at least 8 characters</li>
                <li>Use a combination of uppercase, lowercase, numbers, and special characters</li>
            </ul>
            
            <p>If you need further assistance, please contact our support team.</p>
            
            <p>Best regards,<br>
            <strong>Dochek Security Team</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; {$currentYear} Dochek. All rights reserved. | <a href="https://dochek.com/ang/privacy">Privacy</a> | <a href="https://dochek.com/ang/terms">Terms</a></p>
            <p>This is an automated email. Please do not reply to this address.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Check if a table exists in the database
     * @param string $tableName
     * @return bool
     */
    private function tableExists($tableName)
    {
        try {
            // Sanitize table name to prevent injection
            $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', $tableName);
            
            if (empty($tableName)) {
                log_message('warning', 'tableExists: Table name is empty after sanitization');
                return false;
            }
            
            log_message('info', 'tableExists: Checking for table "' . $tableName . '"');
            
            // Try using INFORMATION_SCHEMA (more reliable)
            try {
                $result = $this->db->query("
                    SELECT TABLE_NAME 
                    FROM INFORMATION_SCHEMA.TABLES 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = '" . $this->db->escapeString($tableName) . "'
                ");
                $rows = $result->getResultArray();
                $exists = !empty($rows);
                log_message('info', 'tableExists: Table "' . $tableName . '" ' . ($exists ? 'EXISTS' : 'DOES NOT EXIST'));
                return $exists;
            } catch (\Exception $e) {
                log_message('warning', 'tableExists: INFORMATION_SCHEMA query failed, trying SHOW TABLES');
                
                // Fallback: Try DESC approach
                try {
                    $result = $this->db->query("DESC `" . $this->db->escapeString($tableName) . "`");
                    $exists = !empty($result->getResultArray());
                    log_message('info', 'tableExists (DESC fallback): Table "' . $tableName . '" ' . ($exists ? 'EXISTS' : 'DOES NOT EXIST'));
                    return $exists;
                } catch (\Exception $e2) {
                    log_message('info', 'tableExists: Table "' . $tableName . '" DOES NOT EXIST (DESC also failed)');
                    return false;
                }
            }
            
        } catch (\Exception $e) {
            log_message('error', 'tableExists UNCAUGHT: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Return the timestamp column used by users table for reset-token age checks.
     */
    private function getUsersTimestampColumn()
    {
        $colCheck = $this->db->query("SHOW COLUMNS FROM users LIKE 'updated_at'")->getRow();
        return $colCheck ? 'updated_at' : 'update_at';
    }

    /**
     * Read max length of users.userid (VARCHAR) so generated tokens always fit.
     */
    private function getUserIdTokenMaxLength()
    {
        try {
            $col = $this->db->query("SHOW COLUMNS FROM users LIKE 'userid'")->getRowArray();
            if (!$col || empty($col['Type'])) {
                return 64;
            }

            $type = strtolower((string) $col['Type']);

            if (preg_match('/(?:varchar|char)\((\d+)\)/i', $type, $m)) {
                $len = (int) $m[1];
                return $len > 0 ? $len : 64;
            }

            if (preg_match('/(?:tinyint|smallint|mediumint|int|bigint)\((\d+)\)/i', $type, $m)) {
                $len = (int) $m[1];
                return $len > 0 ? $len : 11;
            }

            return 64;
        } catch (\Throwable $e) {
            log_message('warning', 'getUserIdTokenMaxLength: fallback to 64: ' . $e->getMessage());
            return 64;
        }
    }

    /**
     * Generate a hex token that fits the users.userid column width.
     */
    private function generateResetToken($maxLen)
    {
        $maxLen = (int) $maxLen;
        if ($maxLen <= 0) {
            $maxLen = 64;
        }

        // Always stay within DB column width; avoid silent truncation/update failure.
        $targetLen = min(64, $maxLen);
        if ($targetLen <= 1) {
            $targetLen = 1;
        }

        $bytes = max(1, (int) ceil($targetLen / 2));
        $token = bin2hex(random_bytes($bytes));
        return substr($token, 0, $targetLen);
    }

    /**
     * Read the raw SQL type for users.userid.
     */
    private function getUserIdColumnType()
    {
        try {
            $col = $this->db->query("SHOW COLUMNS FROM users LIKE 'userid'")->getRowArray();
            return strtolower((string) ($col['Type'] ?? ''));
        } catch (\Throwable $e) {
            log_message('warning', 'getUserIdColumnType: fallback empty: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Determine if users.userid is numeric-only (e.g., int).
     */
    private function isUserIdColumnNumeric()
    {
        $type = $this->getUserIdColumnType();
        return strpos($type, 'int') !== false
            || strpos($type, 'decimal') !== false
            || strpos($type, 'numeric') !== false;
    }

    /**
     * Trim incoming token to users.userid column width to match DB-stored value.
     */
    private function fitTokenToUserIdColumn($token)
    {
        $maxLen = $this->getUserIdTokenMaxLength();
        return substr((string) $token, 0, $maxLen);
    }
}
