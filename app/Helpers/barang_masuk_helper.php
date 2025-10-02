<?php

// app/Helpers/barang_masuk_helper.php
if (!function_exists('character_limiter')) {
    function character_limiter($str, $n = 100, $end_char = '...')
    {
        if (strlen($str) < $n) {
            return $str;
        }
        
        $str = preg_replace("/\s+/", ' ', str_replace(["\r\n", "\r", "\n"], ' ', $str));
        
        if (strlen($str) <= $n) {
            return $str;
        }
        
        $out = '';
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';
            
            if (strlen($out) >= $n) {
                $out = trim($out);
                return (strlen($out) === strlen($str)) ? $out : $out . $end_char;
            }
        }
        
        return $out;
    }
}

if (!function_exists('format_file_size')) {
    function format_file_size($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

if (!function_exists('validate_image')) {
    function validate_image($file)
    {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $maxSize = 4 * 1024 * 1024; // 4MB
        
        $errors = [];
        
        if (!$file->isValid()) {
            $errors[] = 'File tidak valid';
            return $errors;
        }
        
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            $errors[] = 'File harus berformat JPG, JPEG, PNG, atau GIF';
        }
        
        if ($file->getSize() > $maxSize) {
            $errors[] = 'Ukuran file maksimal 4MB';
        }
        
        return $errors;
    }
}

if (!function_exists('generate_image_thumbnail')) {
    function generate_image_thumbnail($sourcePath, $thumbnailPath, $width = 150, $height = 150)
    {
        if (!extension_loaded('gd')) {
            return false;
        }
        
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            return false;
        }
        
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Create source image
        switch ($mimeType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }
        
        if (!$sourceImage) {
            return false;
        }
        
        // Calculate thumbnail dimensions
        $ratio = min($width / $sourceWidth, $height / $sourceHeight);
        $thumbnailWidth = (int)($sourceWidth * $ratio);
        $thumbnailHeight = (int)($sourceHeight * $ratio);
        
        // Create thumbnail
        $thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);
        
        // Preserve transparency for PNG and GIF
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            imagefilledrectangle($thumbnail, 0, 0, $thumbnailWidth, $thumbnailHeight, $transparent);
        }
        
        // Resize image
        imagecopyresampled(
            $thumbnail, $sourceImage,
            0, 0, 0, 0,
            $thumbnailWidth, $thumbnailHeight,
            $sourceWidth, $sourceHeight
        );
        
        // Save thumbnail
        $result = false;
        switch ($mimeType) {
            case 'image/jpeg':
                $result = imagejpeg($thumbnail, $thumbnailPath, 90);
                break;
            case 'image/png':
                $result = imagepng($thumbnail, $thumbnailPath, 9);
                break;
            case 'image/gif':
                $result = imagegif($thumbnail, $thumbnailPath);
                break;
        }
        
        // Clean up memory
        imagedestroy($sourceImage);
        imagedestroy($thumbnail);
        
        return $result;
    }
}

if (!function_exists('format_indonesian_date')) {
    function format_indonesian_date($date, $includeDay = false)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $days = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        
        $timestamp = strtotime($date);
        $day = date('j', $timestamp);
        $month = $months[(int)date('n', $timestamp)];
        $year = date('Y', $timestamp);
        
        $result = $day . ' ' . $month . ' ' . $year;
        
        if ($includeDay) {
            $dayName = $days[date('l', $timestamp)];
            $result = $dayName . ', ' . $result;
        }
        
        return $result;
    }
}

if (!function_exists('sanitize_filename')) {
    function sanitize_filename($filename)
    {
        // Remove special characters
        $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);
        
        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);
        
        // Remove leading/trailing underscores
        $filename = trim($filename, '_');
        
        return $filename;
    }
}