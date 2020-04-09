<?php

namespace App\Repositories;

use App\Interfaces\MemberRepositoryInterface;
use App\User;

class MemberRepository implements MemberRepositoryInterface
{

    /**
     * Get's all Members.
     *
     * @return mixed
     */
    public function all()
    {
        $query = User::where('type', 'member')
            ->with('forumInfo');

        return $this->filter($query);
    }

    public function filter($query)
    {

        if (request()->ban === '1' || request()->ban === '0') {

            return $query->where('is_ban', request('ban'))->get();
        } else {
            return $query->get();
        }
    }

    /**
     * Ban a $member
     * @param $memberId
     * @param $boolean
     */
    public function ban($memberId, $boolean)
    {

        $member = User::find($memberId);

        $member->is_ban = $boolean;

        $member->save();

    }

}
