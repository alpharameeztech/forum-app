<?php

namespace App\Repositories;

use App\ForumPublisher;
use App\Interfaces\PublisherRepositoryInterface;
use App\Notifications\SendLoginToNewPublisherAccount;
use App\User;
use Illuminate\Support\Facades\Hash;

class PublisherRepository implements PublisherRepositoryInterface
{


    /**
     * Get's all Publishers.
     *
     * @return mixed
     */
    public function all()
    {
        $query = User::where('type', 'publisher')
            ->where('shop_id', $this->shop_id);

        return $this->filter($query);
    }

    public function filter($query)
    {

        if (request()->ban === '1' || request()->ban === '0') {

            $publishers = $query->where('is_ban', request('ban'))->get();

            return $publishers;
        } else {
            return $query->get();
        }
    }

    /**
     * Updates a Publisher.
     *
     * @param $id
     * @param $ban
     */
    public function update($id, $ban)
    {

        $user = User::where('id', $id)
            ->where('type', 'publisher')
            ->first();

        $user->is_ban = $ban;

        $user->save();

    }

    /**
     * Store a user
     * @param $user_data
     */
    public function store($name, $email, $password)
    {

        $user = new User;

        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->type = 'publisher';

        $user->save();

        //after save
        //pass the un-encryped password
        //to the notification
        $user->unencryptedPassword = $password;

        $user->notify(new SendLoginToNewPublisherAccount($user));
    }
}
