<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use function Sodium\compare;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    /**
     * 生成全球通用头像
     * @param string $size
     * @return string
     */
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
        });

    }

    /**
     * 与微博内容表建议关联关系
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * 用户列表博客
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feed()
    {
        return $this->statuses()->orderBy('created_at','desc');
    }

    /**
     * 用户粉丝
     */
    public function followers()
    {
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }

    /**
     * 用户关注的人
     */
    public function followings()
    {
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }


    /**
     * 关注
     * @param $user_ids  integer|array  用户ID
     */
    public function follow($user_ids)
    {
        if (! is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids,false);
    }

    /**
     * 取消关注
     * @param $user_ids
     */
    public function unfollow($user_ids)
    {
        if (! is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    /**
     * 是否为粉丝，或者关注某人
     * @param $user_id
     * @return
     */
    public function isFollowers($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
