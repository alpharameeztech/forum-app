<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class Users
{
    public static function all()
    {

        $users  = User::get();

        /*if no users exist
         * for the current store
         * return a custom error message
         * so the client requesting the api
         * can understand why
         */
        if(! $users){

            return Response::json([
                'error' =>[
                    'message' => 'No users found'
                ]
            ], 404);
        }

        /*
         * return the tranformed
         * users collection
         * with only the data needed
         * not all db structure
         */
        return Response::json([
            'data' => Users::transformCollection($users)
        ], 200);

        return $users;
    }

    private static function transformCollection($users)
    {
        return array_map([Users::class, 'transform'], $users->toArray());
    }

    private static function transform($user)
    {
        return [
             'key' => $user['alias'] ? $user['alias'] : $user['name'],
             'value' => $user['alias'] ? $user['alias'] : $user['name'],
        ];
    }


}
