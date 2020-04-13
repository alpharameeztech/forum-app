<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use App\SubscriptionRepository\UserSubscriptions;

class LoggedInUserAvatar {

   public static function get(){

        return Auth::user()->photo_url;

    }

}



