<?php

namespace App\Http\Controllers\Admin;

// =================================================>> Core Library
use Illuminate\Http\Response; // For Responsing data back to Client

// ============================================================================>> Custom Library
// Controller
use App\Http\Controllers\MainController;

// Model


class DashboardController extends MainController
{
<<<<<<< HEAD
    public function getDashboardInfo(){

        // ===>> Get order data from DB and sum total_price using Function Sum
        $totalSaleToday = Order::sum('total_price');

        // ===>> Prepare response format
        $data = [
            'total_sale_today'      => $totalSaleToday
        ];

        // ===>> Success Response Back to Client
=======
    public function getDashboardInfo()
    {
        $totalSaleToday = 0;


>>>>>>> 8f263181d1538d00f91cd807ef16c74ed76ffea6
        return response()->json($data, Response::HTTP_OK);

    }
}
