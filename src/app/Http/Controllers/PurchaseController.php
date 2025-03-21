<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    public function purchase($id) // 購入ページの表示
    {
        $item = Item::find($id);

        // 自分が出品したアイテムかどうか確認
        if ($item->user_id == Auth::id()) {
            return redirect()->back()->with('error', '自分が出品した商品を購入することはできません！');
        }
        $user = Auth::user();
        $paymentMethods = PaymentMethod::all();
        $defaultPaymentMethod = $paymentMethods->first();
        $address = $user->address;
        $postalCode = $user->postal_code;
        $building = $user->building;

        return view('purchase',compact('item','user','paymentMethods','defaultPaymentMethod','address', 'postalCode', 'building'));
    }


    public function showAddress($itemId) // 住所登録のページ
    {
        $item = Item::findOrFail($itemId);
        return view('address', compact('item'));
    }


    public function updateAddress(AddressRequest $request) // 住所変更の処理
    {
        $user = Auth::user();
        $purchase = Purchase::updateOrCreate(
            ['user_id' => $user->id, 'item_id' => $request->input('item_id')],
            [
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'building' => $request->input('building')
            ],
        );
        session([
            'postal_code' => $request->input('postal_code'),
            'address' => $request->input('address'),
            'building' => $request->input('building')
        ]);
    }


    public function processPurchase(Request $request) // 商品購入の処理
    {
        if ($request->payment_method_id == 1) {
        return app(StripeController::class)->createCheckoutSession($request);
        } else {
            $this->savePurchase($request);
            return redirect()->route('index')->with('message', '購入が完了しました。');
        }
    }


    protected function savePurchase(Request $request)
    {
        $user = Auth::user();
        $purchase = Purchase::updateOrCreate(
            ['user_id' => $user->id, 'item_id' => $request->input('item_id')],
            [
                'payment_method_id' => $request->input('payment_method_id'),
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'building' => $request->input('building')
            ],
        );
    }


    public function completePurchase(Request $request)
    {
        $sessionId = $request->query('session_id');
        if ($sessionId) {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);
            $itemId = $session->metadata->item_id;
            $paymentMethodId = $session->metadata->payment_method_id;
            $postalCode = $session->metadata->postal_code;
            $address = $session->metadata->address;
            $building = $session->metadata->building;

            if ($itemId) {
                $purchase = new Purchase();
                $purchase->user_id = Auth::id();
                $purchase->item_id = $itemId;
                $purchase->payment_method_id = $paymentMethodId;
                $purchase->postal_code = $postalCode;
                $purchase->address = $address;
                $purchase->building = $building;

                $purchase->save();
                return view('checkout-success');
            }
        }
        return view('checkout-cancel');
    }

}
