<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use App\Models\Basket;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class PayPalController extends Controller
{
    private $client; //PayPal client

    public function __construct() //Constructor
    {
        $env = new SandboxEnvironment(config('paypal.client_id'), config('paypal.secret')); //Environment
        $this->client = new PayPalHttpClient($env); //Client
    }

    public function createOrder(Request $request) //Create an order
    {
        $requestPayPal = new OrdersCreateRequest(); //Request
        $requestPayPal->prefer('return=representation'); //Prefer
        $requestPayPal->body = [ //Body
            'intent' => 'CAPTURE', //Intent
            'purchase_units' => [[ //Purchase units
                'amount' => [ //Amount
                    'currency_code' => 'USD', //Currency code
                    'value' => '10.00' //Value
                ]
            ]]
        ];

        try {
            $response = $this->client->execute($requestPayPal); //Execute the request
            return response()->json($response->result); //Return the response
        } catch (\Exception $ex) { //Catch the exception
            return response()->json(['error' => $ex->getMessage()], 500); //Return the error
        }
    }

    public function captureOrder($orderId) //Capture an order
    {
        $request = new OrdersCaptureRequest($orderId); //Request
        $response = $this->client->execute($request); //Execute the request
        
        if ($response->result->status === 'COMPLETED') { //If the response status is completed
            $baskets = Basket::where('user_id', Auth::id())->get(); // Get all the products of the user's cart
            
            foreach ($baskets as $basket) { 
                $product = Product::find($basket->product_id); //If $products is = PRoduct, find the product id on basket
                
                if ($product) { 
                    Transaction::create([ //Create the transaction with the next data
                        'user_id' => $product->user_id,
                        'buyer_id' => Auth::id(),
                        'category_id' => $product->category_id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'price' => $product->price,
                        'longitude' => $product->longitude,
                        'latitude' => $product->latitude,
                        'image' => $product->image,
                        'status' => 'completed',
                        'bid' => false
                    ]);

                    // Delete the product if status is false
                    $product->status = false;
                    $product->save();
                    
                    // Delete the basket
                    $basket->delete();
                }
            }
        }
        
        return response()->json($response->result);
    }
}
