<?php

function send_wa($to, $message)
{
    $token = getenv('FONNTE_TOKEN');
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'target' => $to,
            'message' => $message
        ],
        CURLOPT_HTTPHEADER => ["Authorization: $token"]
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
}
