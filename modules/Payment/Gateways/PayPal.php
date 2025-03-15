<?php

namespace Modules\Payment\Gateways;

use Exception;
use PayPalHttp\IOException;
use Illuminate\Http\Request;
use PayPalHttp\HttpException;
use Modules\Order\Entities\Order;
use Modules\Payment\GatewayInterface;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use Modules\Payment\Responses\PayPalResponse;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PayPal implements GatewayInterface
{
    public $label;
    public $description;


    public function __construct()
    {
        $this->label = setting('paypal_label');
        $this->description = setting('paypal_description');
    }


    /**
     * @throws HttpException
     * @throws IOException
     */
    public function purchase(Order $order, Request $req)
    {
        try {
            $request = new OrdersCreateRequest;
            $request->prefer('return=representation');
            $request->body = $this->buildRequestBody($order);
        } catch (Exception $e) {
            throw new Exception(json_decode($e->getMessage())->message ?? '');
        }

        return new PayPalResponse($order, $this->client()->execute($request));
    }


    public function client()
    {
        return new PayPalHttpClient($this->environment());
    }


    /**
     * @throws HttpException
     * @throws IOException
     * @throws Exception
     */
    public function complete(Order $order)
    {
        try {
            $request = new OrdersCaptureRequest(request('resourceId'));
        } catch (Exception $e) {
            throw new Exception(json_decode($e->getMessage())->message ?? '');
        }

        return new PayPalResponse($order, $this->client()->execute($request));
    }


    private function buildRequestBody($order)
    {
        return [
            'intent' => 'CAPTURE',
            'payer' => [
                'name' => [
                    'given_name' => $order->customer_name,
                    'surname' => $order->customer_name,
                ],
                'email_address' => $order->customer_email,
                'address' => [
                    'address_line_1' => $order->shipping_address,
                    'address_line_2' => $order->shipping_address,
                    'admin_area_2' => $order->billing_city,
                    'admin_area_1' => $order->shipping_district,
                    'postal_code' => $order->shipping_ward,
                    'country_code' => $order->shipping_city,
                ],
            ],
            'purchase_units' => [
                [
                    'reference_id' => $order->id,
                    'amount' => [
                        'currency_code' => setting('default_currency'),
                        'value' => (string) $order->total->round()->amount(),
                    ],
                    'shipping' => [
                        'name' => [
                            'full_name' => $order->customer_name,
                        ],
                        'address' => [
                            'address_line_1' => $order->shipping_address,
                            'address_line_2' => $order->shipping_address,
                            'admin_area_2' => $order->billing_city,
                            'admin_area_1' => $order->shipping_district,
                            'postal_code' => $order->shipping_ward,
                            'country_code' => $order->shipping_city,
                        ],
                    ],
                ],
            ],
            'application_context' => [
                'brand_name' => setting('store_name'),
                'shipping_preferences' => 'SET_PROVIDED_ADDRESS',
                'user_action' => 'PAY_NOW',
            ],
        ];
    }


    private function environment()
    {
        if (setting('paypal_test_mode')) {
            return new SandboxEnvironment(setting('paypal_client_id'), setting('paypal_secret'));
        }

        return new ProductionEnvironment(setting('paypal_client_id'), setting('paypal_secret'));
    }
}
