<?php
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR); // manual define ROOTPATH
require_once 'app/Libraries/FaceRecognitionService.php';

echo "Testing Face Recognition Service...\n";

try {
    $faceService = new App\Libraries\FaceRecognitionService();
    
    // Test dependencies
    echo "Checking dependencies...\n";
    $deps = $faceService->checkDependencies();
    print_r($deps);
    
    // Test statistics
    echo "\nGetting statistics...\n";
    $stats = $faceService->getStatistics();
    print_r($stats);
    
    echo "\nPHP Integration test completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
"@ | Out-File -FilePath "test_face_service.php" -Encoding utf8