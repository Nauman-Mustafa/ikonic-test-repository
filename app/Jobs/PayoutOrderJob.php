<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\ApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PayoutOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Order $order
    ) {}

    /**
     * Use the API service to send a payout of the correct amount.
     * Note: The order status must be paid if the payout is successful, or remain unpaid in the event of an exception.
     *
     * @return void
     */
    public function handle(ApiService $apiService)
    {
        // TODO: Complete this method

        try {
          
            $payoutAmount = $this->calculatePayoutAmount($this->order);
            $apiService->sendPayout($this->order->affiliate->user->email, $payoutAmount);

            // If the payout is successful, updating the order status also
            $this->order->update(['payout_status' => Order::STATUS_PAID]);
        } catch (\Exception $e) {
       
            \Log::error('Payout failed for order ID: ' . $this->order->id . ' - ' . $e->getMessage());
        }

    }

    private function calculatePayoutAmount(Order $order): float
    {
        // the logic to calculate the payout amount,I might calculate it based on the order subtotal and commission rate

        return $order->subtotal * $order->affiliate->commission_rate;
    }
}
