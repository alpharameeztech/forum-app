<?php

namespace App\Services;

use App\Product;
use App\SubscriptionRepository\UserSubscriptions;
use App\UserTrainingHistory;
use Illuminate\Support\Facades\Auth;

class IsUserBan
{

    public static function get()
    {

        return Auth::user()->is_ban;

    }

}



