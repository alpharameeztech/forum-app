<?php

namespace App\Tasks;

use Illuminate\Support\Facades\Auth;
use App\UserTrainingHistory;
use App\Product;
use App\SubscriptionRepository\UserSubscriptions;

class LoggedInUserName {

   public static function get(){

        return Auth::user()->name;

    }

}



