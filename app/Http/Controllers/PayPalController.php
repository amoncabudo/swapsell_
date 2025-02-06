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
            
            // Si el pago es exitoso, crear la transacción
            if ($response->result->status === 'COMPLETED') {
                // Obtener el producto del carrito
                $basket = Basket::where('user_id', Auth::id())->first();
                $product = Product::find($basket->product_id);
                
                // Crear la transacción
                Transaction::create([
                    'user_id' => $product->user_id, // Vendedor
                    'buyer_id' => Auth::id(), // Comprador
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
                // return redirect()->route('products_favs');

                // Limpiar el carrito
                // $this->clear();
            }
            
            return response()->json($response->result);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    // public function clear()
    // {
    //     Auth::user()->basket()->delete();
    //     return response()->json(['message' => 'Carrito limpiado con éxito']);
    // }
}
