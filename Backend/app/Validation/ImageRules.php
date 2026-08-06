<?php

namespace App\Validation;

class ImageRules
{
    public function uploadedWithDimensions($field): bool
    {
        // Debugging
        var_dump('Inside uploadedWithDimensions');
        var_dump($_FILES);

        // Ensure the 'file' key exists in $_FILES
        if (!isset($_FILES[$field])) {
            return false;
        }

        $uploadedFile = $_FILES[$field];

        // Check if the file is an image
        if (!exif_imagetype($uploadedFile['tmp_name'])) {
            return false;
        }

        // Check image dimensions
        list($width, $height) = getimagesize($uploadedFile['tmp_name']);
        $maxWidth = 450;
        $maxHeight = 250;

        return ($width <= $maxWidth && $height <= $maxHeight);
    }
}
