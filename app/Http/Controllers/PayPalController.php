<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PayPalController extends Controller
{
    private $client;

    public function __construct()
    {
        $env = new SandboxEnvironment(config('paypal.client_id'), config('paypal.secret'));
        $this->client = new PayPalHttpClient($env);
    }

    public function createOrder(Request $request)
    {
        $requestPayPal = new OrdersCreateRequest();
        $requestPayPal->prefer('return=representation');
        $requestPayPal->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => '10.00'
                ]
            ]]
        ];

        try {
            $response = $this->client->execute($requestPayPal);
            return response()->json($response->result);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function captureOrder($orderId)
    {
        $request = new OrdersCaptureRequest($orderId);
        try {
            $response = $this->client->execute($request);
            return response()->json($response->result);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function clear()
    {
        Auth::user()->basket()->delete();
        return response()->json(['message' => 'Carrito limpiado con éxito']);
    }
}
