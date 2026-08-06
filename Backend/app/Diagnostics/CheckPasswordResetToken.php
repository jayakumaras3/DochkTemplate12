<?php

namespace App\Diagnostics;

/**
 * Diagnostic script to check password reset token status
 * Run via URL: /Diagnostics/CheckPasswordResetToken?email=user@example.com
 * Or modify for your email and access directly
 */

class CheckPasswordResetToken
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Get email from parameter
        $email = isset($_GET['email']) ? trim($_GET['email']) : null;
        
        if (!$email) {
            echo "Usage: Add ?email=user@example.com to check their token status\n";
            return;
        }

        echo "=== Password Reset Token Diagnostic ===\n\n";
        echo "Looking for email: " . htmlspecialchars($email) . "\n\n";

        // Find user
        $user = $db->table('users')
            ->where('email', $email)
            ->where('valid', 1)
            ->get()
            ->getRow();

        if (!$user) {
            echo "❌ User not found with that email\n";
            return;
        }

        echo "✅ User found:\n";
        echo "  - ID: " . $user->id_user . "\n";
        echo "  - Email: " . $user->email . "\n";
        echo "  - Username: " . $user->username . "\n";
        echo "  - userid field: " . (empty($user->userid) ? "(empty)" : substr($user->userid, 0, 20) . "...") . "\n";
        echo "  - updated_at: " . $user->updated_at . "\n\n";

        // Check updated_at age
        if (!empty($user->updated_at)) {
            $updatedTime = strtotime($user->updated_at);
            $now = time();
            $ageSeconds = $now - $updatedTime;
            $ageMinutes = round($ageSeconds / 60, 2);
            
            echo "Token age: " . $ageSeconds . " seconds (" . $ageMinutes . " minutes)\n";
            
            if ($ageSeconds > (15 * 60)) {
                echo "⏰ Token is EXPIRED (over 15 minutes old)\n";
            } else {
                echo "✅ Token is still VALID (expires in " . round((900 - $ageSeconds) / 60, 2) . " minutes)\n";
            }
        } else {
            echo "⚠️ No updated_at timestamp - token may not be valid\n";
        }

        // Check if password_resets table exists
        echo "\n--- Table Check ---\n";
        $tableExists = $this->tableExists($db, 'password_resets');
        if ($tableExists) {
            echo "✅ password_resets table EXISTS\n\n";
            
            // Check for user's tokens in password_resets table
            $tokens = $db->table('password_resets')
                ->where('user_id', $user->id_user)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
            
            if (!empty($tokens)) {
                echo "Recent tokens in password_resets:\n";
                foreach ($tokens as $i => $token) {
                    $status = $token['used'] == 1 ? "USED" : "UNUSED";
                    $expiryTime = strtotime($token['expires_at']);
                    $nowTime = time();
                    $expired = $nowTime > $expiryTime ? "EXPIRED" : "VALID";
                    
                    echo "  " . ($i + 1) . ". Token " . substr($token['token'], 0, 20) . "...\n";
                    echo "     Status: " . $status . "\n";
                    echo "     Expiry: " . $expired . "\n";
                    echo "     Created: " . $token['created_at'] . "\n";
                    if ($token['used'] == 1) {
                        echo "     Used at: " . $token['used_at'] . "\n";
                    }
                    echo "\n";
                }
            } else {
                echo "No tokens found in password_resets table for this user\n";
            }
        } else {
            echo "❌ password_resets table does NOT exist - using fallback method\n";
        }

        echo "\n--- Summary ---\n";
        if (empty($user->userid)) {
            echo "❌ No token found - try requesting password reset first\n";
        } else {
            echo "✅ Token appears to be stored\n";
            if (!empty($user->updated_at)) {
                $ageSeconds = time() - strtotime($user->updated_at);
                if ($ageSeconds <= (15 * 60)) {
                    echo "✅ Token has not expired\n";
                } else {
                    echo "⏰ Token has expired - request new reset\n";
                }
            }
        }
    }

    private function tableExists($db, $tableName)
    {
        try {
            $result = $db->query("SHOW TABLES LIKE ?", [$tableName]);
            $rows = $result->getResultArray();
            return !empty($rows);
        } catch (\Exception $e) {
            return false;
        }
    }
}
