<?php

namespace App\Http\Controllers\Testing\ExternalService;

// ============================================================================>> Core Library
use Illuminate\Http\Request; // For Getting requested Payload from Client
use Illuminate\Http\Response; // For Responsing data back to Client
use Illuminate\Support\Facades\Http; // For Calling External Service


class JSReportController
{
    $url = "https://kimhak.jsreportonline.net/api/report";
            // Debugging: Log the constructed URL
            info("Constructed URL: $url");

            // // Get Data from DB
            // $receipt = Order::select('id', 'receipt_number', 'cashier_id', 'total_price', 'ordered_at')
            //     ->with([
            //         'cashier', // M:1
            //         'details' // 1:M
            //     ])
            //     ->where('receipt_number', $receiptNumber)
            //     // ->orderBy('id', 'desc')
            //     ->first();

            // // Find Total Price
            // // $totalPrice = 0;
            // // foreach ($receipt as $row) {
            // //     $totalPrice += $row->total_price;
            // // }

            // // Prepare Payload for JS Report Service
            $payload = [
                "template" => [
                    "name" => "/Invoice/main"
                ],
                // "data" => [
                //     'total' => $totalPrice,
                //     'data'  => $receipt,
                // ],
                'data'  => $receipt,
            ];

            // Send Request to JS Report Service
            $response = Http::withBasicAuth("kimhak029@gmail.com", "22446688Ac")
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);

            // Success Response Back to Client
            return [
                'file_base64'   => base64_encode($response),
                'error'         => '',
            ];

    public function generateInvoice(Request $req){

    }

}



