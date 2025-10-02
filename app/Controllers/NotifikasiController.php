<?php

namespace App\Controllers;

class NotifikasiController extends BaseController
{
    public function kirimWA($tujuan, $pesan)
    {
        $token = 'ajVq92Yp3fUYg2Dozbdz';

        $data = [
            'target' => $tujuan,
            'message' => $pesan
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.fonnte.com/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => [
                "Authorization: $token"
            ]
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            log_message('error', "Fonnte Error: $err");
            return false;
        }

        return $response;
    }
}
