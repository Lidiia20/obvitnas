<?php
require_once 'app/Libraries/FaceRecognitionService.php';

echo "Testing FaceRecognitionService...\n";

$service = new App\Libraries\FaceRecognitionService();

// Test dependencies
echo "1. Dependencies check: ";
$deps = $service->checkDependencies();
echo ($deps['success'] ? 'OK' : 'FAILED') . "\n";

// Test statistics  
echo "2. Statistics: ";
$stats = $service->getStatistics();
if ($stats['success']) {
    echo "Users: " . $stats['statistics']['total_users'] . "\n";
} else {
    echo "FAILED\n";
}

// Test face verification dengan foto yang sudah ada
echo "3. Face verification test: ";
$imagePath = 'face_auth/dataset/test_user_1/foto2.jpeg';
if (file_exists($imagePath)) {
    $imageData = base64_encode(file_get_contents($imagePath));
    $result = $service->verifyUserFace('test_user_1', $imageData, 0.6);
    
    if ($result['success'] && $result['verified']) {
        echo "SUCCESS - Confidence: " . $result['confidence'] . "%\n";
    } else {
        echo "FAILED - " . ($result['error'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "Image not found\n";
}

echo "Test completed.\n";
?>
