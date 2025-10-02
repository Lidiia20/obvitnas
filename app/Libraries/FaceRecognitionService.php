<?php

namespace App\Libraries;

class FaceRecognitionService
{
    private $pythonPath;
    private $faceAuthPath;
    private $encodingsFile;
    private $datasetPath;
    
    public function __construct()
    {
        // Path ke Python di virtual environment Windows
    $basePath = dirname(_DIR_, 2); // naik 2 folder dari app/Libraries ke root project
    $this->pythonPath = $basePath . '/face_auth/venv310/Scripts/python.exe';
    $this->faceAuthPath = $basePath . '/face_auth/';
    $this->encodingsFile = $this->faceAuthPath . 'encodings.pkl';
    $this->datasetPath = $this->faceAuthPath . 'dataset/';

    $this->ensureDirectoriesExist();
}
    
    private function ensureDirectoriesExist()
    {
        if (!is_dir($this->faceAuthPath)) {
            mkdir($this->faceAuthPath, 0755, true);
        }
        
        if (!is_dir($this->datasetPath)) {
            mkdir($this->datasetPath, 0755, true);
        }
    }
    
    /**
     * Register user face - simpan foto KTP dan generate encoding
     */
    public function registerUserFace($userId, $fotoIdentitasPath)
    {
        try {
            log_message('info', "FaceRecognitionService: Registering user {$userId}");
            
            // Buat folder user di dataset
            $userDatasetPath = $this->datasetPath . $userId . '/';
            if (!is_dir($userDatasetPath)) {
                mkdir($userDatasetPath, 0755, true);
            }
            
            // Copy foto identitas ke dataset
            $datasetImagePath = $userDatasetPath . 'foto_identitas.jpg';
            
            // Pastikan file source ada
            if (!file_exists($fotoIdentitasPath)) {
                log_message('error', "FaceRecognitionService: Source image not found: {$fotoIdentitasPath}");
                return [
                    'success' => false,
                    'error' => 'File foto identitas tidak ditemukan'
                ];
            }
            
            if (!copy($fotoIdentitasPath, $datasetImagePath)) {
                log_message('error', "FaceRecognitionService: Failed to copy image to dataset");
                return [
                    'success' => false,
                    'error' => 'Gagal menyalin foto ke dataset'
                ];
            }
            
            // Generate encoding menggunakan Python script
            $command = escapeshellcmd($this->pythonPath) . ' ' . 
                      escapeshellarg($this->faceAuthPath . 'encode_faces.py') . ' ' .
                      '--single-image ' . escapeshellarg($datasetImagePath) . ' ' .
                      '--user-id ' . escapeshellarg($userId) . ' ' .
                      '--output ' . escapeshellarg($this->encodingsFile) . ' 2>&1';
            
            log_message('info', "FaceRecognitionService: Running command: {$command}");
            
            $output = shell_exec($command);
            $exitCode = 0;
            
            log_message('info', "FaceRecognitionService: Python output: {$output}");
            
            if ($exitCode === 0 && strpos($output, 'Berhasil') !== false) {
                log_message('info', "FaceRecognitionService: Successfully registered user {$userId}");
                return [
                    'success' => true,
                    'message' => 'Face encoding berhasil didaftarkan',
                    'user_id' => $userId,
                    'dataset_path' => $datasetImagePath
                ];
            } else {
                log_message('error', "FaceRecognitionService: Python script failed: {$output}");
                return [
                    'success' => false,
                    'error' => 'Gagal generate face encoding: ' . $output
                ];
            }
            
        } catch (Exception $e) {
            log_message('error', "FaceRecognitionService: Exception in registerUserFace: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error sistem: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Verify user face - bandingkan foto live dengan encoding yang tersimpan
     */
    public function verifyUserFace($userId, $liveImageBase64, $tolerance = 0.5)
    {
        try {
            log_message('info', "FaceRecognitionService: Verifying user {$userId}");
            
            // Pastikan encodings file ada
            if (!file_exists($this->encodingsFile)) {
                log_message('error', "FaceRecognitionService: Encodings file not found");
                return [
                    'success' => false,
                    'error' => 'Database face encoding belum tersedia'
                ];
            }
            
            // Simpan base64 image ke temporary file
            $tempDir = sys_get_temp_dir();
            $tempFile = tempnam($tempDir, 'face_verify_');
            $tempImagePath = $tempFile . '.jpg';
            
            // Decode base64 dan simpan
            $imageData = base64_decode($liveImageBase64);
            if (!$imageData) {
                return [
                    'success' => false,
                    'error' => 'Invalid base64 image data'
                ];
            }
            
            file_put_contents($tempImagePath, $imageData);
            
            try {
                // Jalankan Python recognition script
                $command = escapeshellcmd($this->pythonPath) . ' ' . 
                          escapeshellarg($this->faceAuthPath . 'recognize.py') . ' ' .
                          'verify ' .
                          '--image ' . escapeshellarg($tempImagePath) . ' ' .
                          '--user-id ' . escapeshellarg($userId) . ' ' .
                          '--tolerance ' . escapeshellarg($tolerance) . ' ' .
                          '--encodings ' . escapeshellarg($this->encodingsFile) . ' ' .
                          '--details 2>&1';
                
                log_message('info', "FaceRecognitionService: Running verify command: {$command}");
                
                $output = shell_exec($command);
                
                log_message('info', "FaceRecognitionService: Python verify output: {$output}");
                
                // Parse JSON output dari Python script
                $result = json_decode($output, true);
                
                if ($result === null) {
                    log_message('error', "FaceRecognitionService: Failed to parse Python output as JSON");
                    return [
                        'success' => false,
                        'error' => 'Gagal memproses hasil verifikasi: ' . $output
                    ];
                }
                
                // Return hasil verifikasi
                return [
                    'success' => $result['success'],
                    'verified' => $result['verified'] ?? false,
                    'confidence' => $result['confidence'] ?? 0.0,
                    'distance' => $result['distance'] ?? 1.0,
                    'threshold' => $result['threshold'] ?? $tolerance,
                    'user_id' => $userId,
                    'error' => $result['error'] ?? null
                ];
                
            } finally {
                // Cleanup temporary files
                if (file_exists($tempImagePath)) {
                    unlink($tempImagePath);
                }
                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }
            }
            
        } catch (Exception $e) {
            log_message('error', "FaceRecognitionService: Exception in verifyUserFace: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error sistem: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Identify face - cari user yang paling mirip dari semua encoding
     */
    public function identifyFace($liveImageBase64, $topMatches = 3, $tolerance = 0.5)
    {
        try {
            log_message('info', "FaceRecognitionService: Identifying face from live image");
            
            if (!file_exists($this->encodingsFile)) {
                return [
                    'success' => false,
                    'error' => 'Database face encoding belum tersedia'
                ];
            }
            
            // Simpan base64 ke temporary file
            $tempDir = sys_get_temp_dir();
            $tempFile = tempnam($tempDir, 'face_identify_');
            $tempImagePath = $tempFile . '.jpg';
            
            $imageData = base64_decode($liveImageBase64);
            if (!$imageData) {
                return [
                    'success' => false,
                    'error' => 'Invalid base64 image data'
                ];
            }
            
            file_put_contents($tempImagePath, $imageData);
            
            try {
                // Jalankan Python identification script
                $command = escapeshellcmd($this->pythonPath) . ' ' . 
                          escapeshellarg($this->faceAuthPath . 'recognize.py') . ' ' .
                          'identify ' .
                          '--image ' . escapeshellarg($tempImagePath) . ' ' .
                          '--top ' . escapeshellarg($topMatches) . ' ' .
                          '--encodings ' . escapeshellarg($this->encodingsFile) . ' 2>&1';
                
                $output = shell_exec($command);
                $result = json_decode($output, true);
                
                if ($result === null) {
                    return [
                        'success' => false,
                        'error' => 'Gagal memproses hasil identifikasi: ' . $output
                    ];
                }
                
                return $result;
                
            } finally {
                // Cleanup
                if (file_exists($tempImagePath)) {
                    unlink($tempImagePath);
                }
                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }
            }
            
        } catch (Exception $e) {
            log_message('error', "FaceRecognitionService: Exception in identifyFace: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error sistem: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Rebuild encodings - regenerate semua encodings dari dataset
     */
    public function rebuildEncodings()
    {
        try {
            log_message('info', "FaceRecognitionService: Rebuilding all encodings");
            
            $command = escapeshellcmd($this->pythonPath) . ' ' . 
                      escapeshellarg($this->faceAuthPath . 'encode_faces.py') . ' ' .
                      '--dataset ' . escapeshellarg($this->datasetPath) . ' ' .
                      '--output ' . escapeshellarg($this->encodingsFile) . ' 2>&1';
            
            $output = shell_exec($command);
            
            log_message('info', "FaceRecognitionService: Rebuild output: {$output}");
            
            if (strpos($output, 'Proses encoding selesai') !== false) {
                return [
                    'success' => true,
                    'message' => 'Encodings berhasil di-rebuild',
                    'output' => $output
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Gagal rebuild encodings: ' . $output
                ];
            }
            
        } catch (Exception $e) {
            log_message('error', "FaceRecognitionService: Exception in rebuildEncodings: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error sistem: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get face recognition statistics
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_users' => 0,
                'encodings_file_exists' => file_exists($this->encodingsFile),
                'dataset_path' => $this->datasetPath,
                'encodings_file_size' => 0,
                'last_updated' => null
            ];
            
            if ($stats['encodings_file_exists']) {
                $stats['encodings_file_size'] = filesize($this->encodingsFile);
                $stats['last_updated'] = date('Y-m-d H:i:s', filemtime($this->encodingsFile));
            }
            
            // Hitung jumlah user di dataset
            if (is_dir($this->datasetPath)) {
                $userDirs = array_filter(scandir($this->datasetPath), function($item) {
                    return $item !== '.' && $item !== '..' && is_dir($this->datasetPath . $item);
                });
                $stats['total_users'] = count($userDirs);
            }
            
            return [
                'success' => true,
                'statistics' => $stats
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Error getting statistics: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Check if Python dependencies are installed
     */
    public function checkDependencies()
    {
        try {
            // Test Python availability
            $pythonCheck = shell_exec($this->pythonPath . ' --version 2>&1');
            
            // Test required packages
            $packagesCheck = shell_exec($this->pythonPath . ' -c "import face_recognition, cv2, numpy, pickle; print(\'OK\')" 2>&1');
            
            return [
                'success' => strpos($packagesCheck, 'OK') !== false,
                'python_version' => trim($pythonCheck),
                'packages_status' => trim($packagesCheck),
                'face_auth_path' => $this->faceAuthPath,
                'python_path' => $this->pythonPath
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Error checking dependencies: ' . $e->getMessage()
            ];
        }
    }
}