<?php

namespace App\Services;

use App\Exceptions\AffiliateCreateException;
use App\Mail\AffiliateCreated;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AffiliateService
{
    public function __construct(
        protected ApiService $apiService
    ) {}

    /**
     * Create a new affiliate for the merchant with the given commission rate.
     *
     * @param  Merchant $merchant
     * @param  string $email
     * @param  string $name
     * @param  float $commissionRate
     * @return Affiliate
     */
    public function register(Merchant $merchant, string $email, string $name, float $commissionRate): Affiliate
    {
        // TODO: Complete this method

        $user = User::where('email', $email)->first();

        if (!$user) {
            // if  may want to create a new user here if the  user don't exist
            
            // like this: $user = User::create(['email' => $email, 'name' => $name]);
        
//otherwise throw an error 
            throw new AffiliateCreateException('User not found for the given email');
        }

        // Checking  if the affiliate already exists for this user and merchant
        $existingAffiliate = Affiliate::where('user_id', $user->id)
            ->where('merchant_id', $merchant->id)
            ->first();

        if ($existingAffiliate) {
            return $existingAffiliate;
        }

        // Creating new affiliate
        $affiliate = Affiliate::create([
            'user_id' => $user->id,
            'merchant_id' => $merchant->id,
            'commission_rate' => $commissionRate,
         
        ]);

        // Send an email notification also if nedded 
        Mail::to($user->email)->send(new AffiliateCreated($affiliate));

        return $affiliate;
    }
}
