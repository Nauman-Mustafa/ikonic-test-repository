<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use App\Models\CommissionLog;
use App\Jobs\PayoutOrderJob;


class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
    ) {}

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        // TODO: Complete this method

        $order = Order::firstOrNew(['id' => $data['order_id']]);
        if ($order->exists) {
            return;
        }
        $affiliate = Affiliate::where('user_id', auth()->id())
        ->where('customer_email', $data['customer_email'])
        ->first();
        if (!$affiliate) {
            // creating  new affiliate if dosen exist already
            $merchant = Merchant::where('domain', $data['merchant_domain'])->first();
        
$this->affiliateService->register($merchant, $data['customer_email'], $data['customer_name'], 0.0);
        }

        
        $commissionRate = $affiliate->commission_rate;
        $commission = $data['subtotal_price'] * $commissionRate;

        // here im Logging commission for the order
        $commissionLog = CommissionLog::create([
            'order_id' => $order->id,
            'affiliate_id' => $affiliate->id,
            'commission_amount' => $commission,
         
        ]);

        //here is the logic of  Updating order with commission information

        $order->commission_owed = $commission;
        $order->payout_status = Order::STATUS_UNPAID;

        $order->save();
//here im despatching the  payout order job
        PayoutOrderJob::dispatch($order);

    }
}
