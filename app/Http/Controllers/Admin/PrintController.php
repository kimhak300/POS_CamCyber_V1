<?php

namespace App\Http\Controllers\Admin;

// ============================================================================>> Core Library
use Illuminate\Support\Facades\Http;

// ============================================================================>> Custom Library
// Controller
use App\Http\Controllers\MainController;

// Model
use App\Models\Order\Order;

class PrintController extends MainController
{
    //====================Global variable====================
    private $JS_BASE_URL;
    private $JS_USERNAME;
    private $JS_PASSWORD;
    private $JS_TEMPLATE;

    public function __construct()
    {
        $this->JS_BASE_URL   = env('JS_BASE_URL');
        $this->JS_USERNAME   = env('JS_USERNAME');
        $this->JS_PASSWORD   = env('JS_PASSWORD');
        $this->JS_TEMPLATE   = env('JS_TEMPLATE');
    }

    public function printInvioceOrder($receipt_number = 0)
    {
        try {
            $body = [
                "template" => [
                    "name" => $this->JS_TEMPLATE, // name or path
                ],
                "data" => $this->getData($receipt_number),
            ];

            $response = Http::withBasicAuth($this->JS_USERNAME, $this->JS_PASSWORD)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->JS_BASE_URL . '/api/report', $body);

            return [
                'file_base64' => base64_encode($response),
                'error' => '',
            ];
        } catch (\Exception $e) {
            // Handle the exception
            return [
                'file_base64' => '',
                'error' => $e->getMessage(),
            ];
        }
    }

    public static function getData($receipt_number = 0)
    {
        try {
            $data = Order::select('id', 'receipt_number', 'cashier_id', 'total_price', 'ordered_at')
                ->where('receipt_number', $receipt_number)
                ->with([
                    'cashier',
                    'details'
                ])
                ->orderBy('id', 'desc')
                ->get();

            $total = 0;
            foreach ($data as $row) {
                $total += $row->total_price;
            }

            $payload = [
                'total' => $total,
                'data'  => $data,
            ];

            return $payload;
        } catch (\Exception $e) {
            // Handle the exception
            return [
                'total' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ];
        }
    }
}
