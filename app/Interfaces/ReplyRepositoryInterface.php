<?php

namespace App\Interfaces;

interface ReplyRepositoryInterface
{
    /**
     * Get's a $Reply by it's ID
     *
     * @param int
     */
    public function get($reply_id);

    /**
     * Get's all $Replies.
     *
     * @return mixed
     */
    public function all();

    /**
     * Updates a $Reply.
     *
     * @param int
     * @param array
     */
    public function update($reply_data);

    /**
     * Ban a Reply
     * $param $Reply_id
     * @param $reply_id
     * @param $boolean
     */
    public function ban($reply_id, $boolean);

    /**
     * When called with any
     * query parameter,
     * the filter will applied
     * @param $query
     * @return mixed
     */
    public function filter($query);


}
