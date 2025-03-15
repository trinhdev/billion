<?php

namespace Modules\Checkout\Services;

use Modules\Cart\CartTax;
use Modules\Cart\CartItem;
use Modules\Cart\Facades\Cart;
use Modules\Order\Entities\Order;
use Modules\Address\Entities\Address;
use Modules\FlashSale\Entities\FlashSale;
use Modules\Currency\Entities\CurrencyRate;
use Modules\Account\Entities\DefaultAddress;
use Modules\Shipping\Facades\ShippingMethod;

class OrderService
{
    public function create($request)
    {
        $this->mergeShippingAddress($request);
        $this->saveAddress($request);
        $this->addShippingMethodToCart($request);

        return tap($this->store($request), function ($order) {
            $this->storeOrderProducts($order);
            $this->storeOrderDownloads($order);
            $this->storeFlashSaleProductOrders($order);
            $this->incrementCouponUsage($order);
            $this->attachTaxes($order);
            $this->reduceStock();
        });
    }


    public function reduceStock()
    {
        Cart::reduceStock();
    }


    public function delete(Order $order)
    {
        $order->delete();

        Cart::restoreStock();
    }


    private function mergeShippingAddress($request)
    {
        $request->merge([
            'shipping' => $request->ship_to_a_different_address ? $request->shipping : $request->billing,
        ]);
    }


    private function saveAddress($request)
    {
        if (auth()->guest()) {
            return;
        }
        if ($request->newBillingAddress) {
            $address = auth()
                ->user()
                ->addresses()
                ->create($this->extractAddress($request->billing));

            $this->makeDefaultAddress($address);
        }

        if ($request->ship_to_a_different_address && $request->newShippingAddress) {
            auth()
                ->user()
                ->addresses()
                ->create($this->extractAddress($request->shipping));
        }
    }


    private function extractAddress($data)
    {
        return [
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'district' => $data['state'],
            'ward' => $data['zip']
        ];
    }


    private function makeDefaultAddress(Address $address)
    {
        if (
            auth()
                ->user()
                ->addresses()
                ->count() > 1
        ) {
            return;
        }

        DefaultAddress::create([
            'address_id' => $address->id,
            'customer_id' => auth()->id(),
        ]);
    }


    private function addShippingMethodToCart($request)
    {
        if (!Cart::allItemsAreVirtual() && !Cart::hasShippingMethod()) {
            Cart::addShippingMethod(ShippingMethod::get($request->shipping_method));
        }
    }


    private function store($request)
    {
        return Order::create([
            'customer_id' => auth()->id(),
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_name' => $request->billing['customer_name'],
            'shipping_name' => $request->shipping['shipping_name'],
            'shipping_phone' => $request->shipping['phone'],
            'shipping_address' => $request->shipping['address'],
            'shipping_city' => $request->shipping['city'],
            'shipping_district' => $request->shipping['district'],
            'shipping_ward' => $request->shipping['ward'],
            'sub_total' => Cart::subTotal()->amount(),
            'shipping_method' => Cart::shippingMethod()->name(),
            'shipping_cost' => Cart::shippingCost()->amount(),
            'coupon_id' => Cart::coupon()->id(),
            'discount' => Cart::discount()->amount(),
            'total' => Cart::total()->amount(),
            'payment_method' => $request->payment_method,
            'currency' => currency(),
            'currency_rate' => CurrencyRate::for(currency()),
            'locale' => locale(),
            'status' => Order::PENDING_PAYMENT,
            'note' => $request->order_note,
        ]);
    }


    private function storeOrderProducts(Order $order)
    {
        Cart::items()->each(function (CartItem $cartItem) use ($order) {
            $order->storeProducts($cartItem);
        });
    }


    private function storeOrderDownloads(Order $order)
    {
        Cart::items()->each(function (CartItem $cartItem) use ($order) {
            $order->storeDownloads($cartItem);
        });
    }


    private function storeFlashSaleProductOrders(Order $order)
    {
        Cart::items()->each(function (CartItem $cartItem) use ($order) {
            if (!FlashSale::contains($cartItem->product)) {
                return;
            }

            FlashSale::pivot($cartItem->product)
                ->orders()
                ->attach([
                    $cartItem->product->id => [
                        'order_id' => $order->id,
                        'qty' => $cartItem->qty,
                    ],
                ]);
        });
    }


    private function incrementCouponUsage()
    {
        Cart::coupon()->usedOnce();
    }


    private function attachTaxes(Order $order)
    {
        Cart::taxes()->each(function (CartTax $cartTax) use ($order) {
            $order->attachTax($cartTax);
        });
    }
}
