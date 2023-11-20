<?php

namespace App\Interfaces;

interface MerchantServiceInterface
{
  
    public function register(array $data);
    public function updateMerchant(\App\Models\User $user, array $data);
    public function findMerchantByEmail(string $email);
    public function payout(\App\Models\Affiliate $affiliate);
    public function getOrderStatistics(string $fromDate, string $toDate);
}
