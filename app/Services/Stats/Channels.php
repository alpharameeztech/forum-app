<?php

namespace App\Services\Stats;

use App\ForumChannel;

class Channels
{

    static public function total()
    {

        $channels = ForumChannel::all();

        foreach ($channels as $channel) {
            $channel['label'] = $channel->name;

            $channel['value'] = $channel->threads->count();

            unset($channel['id']);

            unset($channel['threads']);

        }
        return $channels;

    }

}
