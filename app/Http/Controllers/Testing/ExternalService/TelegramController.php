<?php

namespace App\Http\Controllers\Testing\ExternalService;

// ============================================================================>> Core Library
use Illuminate\Http\Request; // For Getting requested Payload from Client
use Illuminate\Http\Response; // For Responsing data back to Client
use Illuminate\Support\Facades\Http; // For Calling External Service


class TelegramController
{

    public static function sendMessage(Request $req){

        // ===>> Get Credentail from ENV Variable
        $botToken  = env('TELEGRAM_BOT_TOKEN');
        $chatID    = env('TELEGRAM_CHAT_ID');


        // ===>> Send Request to Telegram
        $res = Http::withOptions(['verify' => false])->get("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatID&text=$req->msg");

        // ===>> Success Response Back to Client
        return response()->json($res, Response::HTTP_OK);

    }

    public function sendPicture(Request $req){

    }

    public function sendLocation(Request $req){

    }




}


