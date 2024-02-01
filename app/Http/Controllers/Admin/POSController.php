<?php

namespace App\Http\Controllers\Admin;

// ============================================================================>> Core Library
use Illuminate\Http\Request; // For Getting requested Data from Client
use Illuminate\Http\Response; // For Responsing data back to Client

// ============================================================================>> Third Library
use Tymon\JWTAuth\Facades\JWTAuth; // Get Current Logged User

// ============================================================================>> Core Library
// Controller
use App\Http\Controllers\MainController;

// Service
use App\Services\TelegramService; //Send Notifications to Telegram Bot

// Model
use App\Models\Order\Detail;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Product\Type as ProductType;


class POSController extends MainController
{
    public function getProducts()
    {
        $data = ProductType::select('id', 'name')
            ->with([
                'products:id,name,image,type_id,unit_price'
            ])
            ->get();

        return response()->json($data, Response::HTTP_OK);
    }

    public function makeOrder(Request $req)
    {

        //==============================>> Check validation
        $this->validate($req, [
            'cart'      => 'required|json'
        ]);

        //==============================>> Get Current Login User to save who make orders.
        $user = JWTAuth::parseToken()->authenticate();

        // ===>> Create Order
        $order                  = new Order;
        $order->cashier_id      = $user->id;
        $order->total_price     = 0;
        $order->receipt_number  = $this->_generateReceiptNumber();
        $order->save();

        // ===>> Find Total Price & Order Detail
        $details    = [];
        $totalPrice = 0;
        $cart       = json_decode($req->cart); // Turn Json String to PHP Array.

        foreach ($cart as $productId => $qty) {

            $product = Product::find($productId);
            if ($product) {
                $details[] = [
                    'order_id'      => $order->id,
                    'product_id'    => $productId,
                    'qty'           => $qty,
                    'unit_price'    => $product->unit_price,
                ];

                $totalPrice +=  $qty * $product->unit_price;
            }
        }

        // ===>> Save to Details
        Detail::insert($details);

        // ===>> Update Order
        $order->total_price     = $totalPrice;
        $order->ordered_at      = Date('Y-m-d H:i:s');
        $order->save();

        // ===> Get Data for Client Reponse to view the order in Popup.
        $orderData = Order::select('*')
            ->with([

                'cashier:id,name,type_id',
                'cashier.type:id,name',

                'details:id,order_id,product_id,unit_price,qty',
                'details.product:id,name,type_id',
                'details.product.type:id,name'

            ])
            ->find($order->id);

        // ===>> Send Notification
        $this->_sendNotification($orderData);

        return response()->json([
            'order'         => $orderData,
            'message'       => 'ការបញ្ជាទិញត្រូវបានបង្កើតដោយជោគជ័យ។'
        ], Response::HTTP_OK);
    }

    private function _generateReceiptNumber()
    {
        $number = rand(1000000, 9999999);
        $check  = Order::where('receipt_number', $number)->first();
        if ($check) {
            return $this->_generateReceiptNumber();
        }

        return $number;
    }

    private function _sendNotification($orderData)
    {
        // Send Telegram Notification
        $htmlMessage = "<b>ការបញ្ជាទិញទទួលបានជោគជ័យ!</b>\n";
        $htmlMessage .= "- លេខវិកយប័ត្រ៖ " . $orderData->receipt_number . "\n";
        $htmlMessage .= "- អ្នកគិតលុយ៖ " . $orderData->cashier->name;

        $productList = '';
        $totalProducts = 0;

        foreach ($orderData->details as $detail) {
            $productList .= sprintf(
                "%-20s | %-15s | %-10s | %s\n",
                $detail->product->name,
                $detail->unit_price,
                $detail->qty,
                PHP_EOL
            );
            $totalProducts += $detail->qty;
        }

        $htmlMessage .= "\n---------------------------------------\n";
        $htmlMessage .= "ផលិតផល             | តម្លៃដើម(៛)     | បរិមាណ\n";
        $htmlMessage .= $productList . "\n";
        $htmlMessage .= "<b>* សរុបទាំងអស់៖</b> $totalProducts ទំនិញ $orderData->total_price ៛\n";
        $htmlMessage .= "- កាលបរិច្ឆេទ: " . $orderData->ordered_at;

        //=================================
        TelegramService::sendMessage($htmlMessage);
    }
}
