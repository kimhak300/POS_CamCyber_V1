<?php

namespace App\Http\Controllers\Admin;

// ============================================================================>> Core Library
use Illuminate\Support\Facades\Http; // Handling HTTP Request to other service

// ============================================================================>> Custom Library
// Controller
use App\Http\Controllers\MainController;

// Model
use App\Models\Order\Order;

class PrintController extends MainController
{
    //==================== Public Variable ====================
    private $JS_BASE_URL;
    private $JS_USERNAME;
    private $JS_PASSWORD;
    private $JS_TEMPLATE;

    public function __construct()
    {
        // ===>> Get JS Report Configration from ENV
        $this->JS_BASE_URL   = env('JS_BASE_URL');
        $this->JS_USERNAME   = env('JS_USERNAME');
        $this->JS_PASSWORD   = env('JS_PASSWORD');
    }

    public function printInvoice($receiptNumber = 0){

        try {

            // Payload to be sent to JS Report Service
            $payload = [
                "template" => [
                    "name" => '/Invoice/main',
                ],
                "data" => $this->_getReceiptData($receiptNumber),
            ];

            // ===>> Send Request ot JS Report Service
            $response = Http::withBasicAuth($this->JS_USERNAME, $this->JS_PASSWORD)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($this->JS_BASE_URL . '/api/report', $payload);

            // ===> Success Response Back to Client
            return [
                'file_base64'   => base64_encode($response),
                'error'         => '',
            ];

        } catch (\Exception $e) {
            // Handle the exception
            return [
                'file_base64' => '',
                'error' => $e->getMessage(),
            ];
        }
    }

    private function _getReceiptData($receiptNumber = 0){

        try {

            // ===>> Get Data from DB
            $data = Order::select('id', 'receipt_number', 'cashier_id', 'total_price', 'ordered_at')
            ->with([
                'cashier', // M:1
                'details' // 1:M
            ])
            ->where('receipt_number', $receiptNumber) // Condition
            ->first();

            // Return data Back
            return $data;

        } catch (\Exception $e) {

            // ===> Handle the exception
            return [
                'total' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ];

        }
    }

}
