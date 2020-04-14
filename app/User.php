<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\ForumActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Scopes\ShopScope;

class User extends Authenticatable
{
    use Notifiable;

    const ADMIN_TYPE = 'admin';

    const DEFAULT_TYPE = 'member';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'alias', 'slug', 'email', 'password', 'type','provider', 'provider_id', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug'; // 'name';
    }

    protected static function boot()
    {
        parent::boot();
    }

    public function threads()
    {
        return $this->hasMany(ForumThread::class);
    }

    public function activity()
    {
        return $this->hasMany(ForumActivity::class);
    }

    public function read($thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            Carbon::now()
        );
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function lastReply()
    {
        return $this->hasOne(ForumReply::class)->latest();
    }

    public function forumInfo()
    {
        return $this->hasOne(ForumInfo::class);
    }

    public function updateExperience($incrementBy)
    {
        if ($this->forumInfo != null) {
            $user_experience = $this->forumInfo->experience;

            $this->forumInfo->update([
                'experience' => $user_experience + $incrementBy
            ]);
        } else {
            $this->forumInfo()->create([
                'user_id' => $this->id,
                'experience' => $incrementBy
            ]);
        }

        return true;
    }

    public function isAdmin()
    {
        return $this->type === self::ADMIN_TYPE;
    }

    public function isPublisher()
    {
        return $this->type === 'publisher';
    }
}
