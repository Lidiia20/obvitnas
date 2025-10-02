<?php
echo "Testing PHP-Python Integration...\n";

$pythonPath = 'C:/laragon/www/obvitnas/face_auth/venv310/Scripts/python.exe';
$script = 'C:/laragon/www/obvitnas/face_auth/recognize.py';
$encodings = 'C:/laragon/www/obvitnas/face_auth/encodings.pkl';
$image = 'C:/laragon/www/obvitnas/face_auth/dataset/test_user_1/foto2.jpeg';

$command = sprintf(
    '"%s" "%s" --encodings "%s" --tolerance 0.6 verify --image "%s" --user-id test_user_1 2>&1',
    $pythonPath, $script, $encodings, $image
);

echo "Command: $command\n\n";
$output = shell_exec($command);
echo "Raw output:\n$output\n\n";

$result = json_decode($output, true);
if ($result && isset($result['success']) && $result['success']) {
    echo "SUCCESS: PHP-Python integration working!\n";
    echo "Verified: " . ($result['verified'] ? 'YES' : 'NO') . "\n";
    echo "Confidence: " . $result['confidence'] . "%\n";
} else {
    echo "FAILED: Could not parse JSON result\n";
    echo "Output: $output\n";
}
?>
