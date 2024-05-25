<?php

namespace App\Http\Controllers\Testing;

// ============================================================================>> Core Library
use Illuminate\Http\Request; // For Getting requested Payload from Client
use Illuminate\Http\Response; // For Responsing data back to Client


class TestingController
{
    public function calculate(Request $req){

        $a = $req->a;
        $b = $req->b;

        return $this->_sum($a, $b);

    }

    private function _sum($x = 0, $y = 0){

        return $x+$y;

    }
    public function sendTelegramBot(Request $req){

        // return $req;

        $token      = "bot7131711527:AAFpnjzefJ6nG6m1drVSMeS1f68PXfsXdq4"; // Yor token
        $tgApiHost  = "https://api.telegram.org/".$token;
        $chatId     = "-1002109105991";  //Receiver Chat Id

        $payload = [
            'chat_id'   =>      $chatId,
            //'text'      =>      $req->message,
            'latitude'      =>      $req->latitude,
            'longitude'      =>      $req->longitude,
        ];

        $ch = curl_init($tgApiHost . '/sendLocation');

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;


    }

}


