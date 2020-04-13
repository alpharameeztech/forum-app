<?php

namespace App\Traits;

use App\ForumActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Log;
use ReflectionClass;

trait RecordForumActivity
{
    // this method is called automatically gets called whereve we use it i.e :use RecordForumActivity;
    protected static function bootRecordForumActivity()
    {

        if (auth()->guest()) return;

        static::created(function ($thread) {
            $thread->recordActivity('created');
        });

        //when the thread is being deletng,make sure the activity model is deleted too from activities table
        static::deleting(function ($model) {

            Log::info('deleting a model' . $model);

            $model->activity()->delete();
        });
    }

    protected function recordActivity($event)
    {

        $this->activity()->create([

            'shop_id' => Cache::get('shop_id'),
            'user_id' => Auth::id(),
            'type' => $this->getActivityType($event)

        ]);

    }

    public function activity()
    {
        return $this->morphMany('App\ForumActivity', 'subject');
    }

    protected function getActivityType($event)
    {

        $type = strtolower((new ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }

}
