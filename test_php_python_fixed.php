<?php
echo "Testing PHP-Python Integration (Fixed)...\n";

$pythonPath = 'C:/laragon/www/obvitnas/face_auth/venv310/Scripts/python.exe';
$script = 'C:/laragon/www/obvitnas/face_auth/recognize.py';
$encodings = 'C:/laragon/www/obvitnas/face_auth/encodings.pkl';
$image = 'C:/laragon/www/obvitnas/face_auth/dataset/test_user_1/foto2.jpeg';

$command = sprintf(
    '"%s" "%s" --encodings "%s" --tolerance 0.6 verify --image "%s" --user-id test_user_1 2>&1',
    $pythonPath, $script, $encodings, $image
);

$output = shell_exec($command);
echo "Raw output:\n$output\n\n";

// Extract JSON dari output yang mengandung log messages
$lines = explode("\n", $output);
$jsonStarted = false;
$jsonLines = [];

foreach ($lines as $line) {
    if (strpos($line, '{') !== false && !$jsonStarted) {
        $jsonStarted = true;
    }
    
    if ($jsonStarted) {
        $jsonLines[] = $line;
    }
}

$jsonString = implode("\n", $jsonLines);
echo "Extracted JSON:\n$jsonString\n\n";

$result = json_decode($jsonString, true);
if ($result && isset($result['success']) && $result['success']) {
    echo "SUCCESS: PHP-Python integration working!\n";
    echo "Verified: " . ($result['verified'] ? 'YES' : 'NO') . "\n";
    echo "Confidence: " . $result['confidence'] . "%\n";
} else {
    echo "FAILED: Could not parse JSON\n";
    echo "JSON Error: " . json_last_error_msg() . "\n";
}
?>
