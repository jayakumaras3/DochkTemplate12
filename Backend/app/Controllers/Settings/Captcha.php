<?php

namespace App\Controllers;

use App\Controllers\BaseController;
#[\AllowDynamicProperties]
class Captcha extends BaseController
{
    public function index()
    {

        try {
            $captcha = $this->generateCaptcha();
           // $_SESSION['captcha'] = $captcha['word'];
           
             session()->set('captcha', $captcha['word']);

            // Save CAPTCHA image to a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'captcha');
            imagepng($captcha['image'], $tempFile);

            // Output CAPTCHA image directly
            header('Content-Type: image/png');
            readfile($tempFile); // Output the contents of the temporary file
            unlink($tempFile); // Delete the temporary file after serving it
        } catch (\Exception $e) {
            // Handle any exceptions
            echo 'Error: ' . $e->getMessage();
        }
    }

    private function generateCaptcha($length = 6)
    {
        // Generate random CAPTCHA text
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $captchaText = '';
        for ($i = 0; $i < $length; $i++) {
            $captchaText .= $alphabet[rand(0, strlen($alphabet) - 1)];
        }

        // Create image with CAPTCHA text
        $image = imagecreatetruecolor(120, 40);
        if (!$image) {
            throw new \Exception('Failed to create image');
        }

        $backgroundColor = imagecolorallocate($image, 255, 255, 255);
        if ($backgroundColor === false) {
            throw new \Exception('Failed to allocate background color');
        }

        imagefilledrectangle($image, 0, 0, 200, 45, $backgroundColor);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        if ($textColor === false) {
            throw new \Exception('Failed to allocate text color');
        }

        imagestring($image, 5, 10, 10, $captchaText, $textColor);

        return ['word' => $captchaText, 'image' => $image];
    }
}
