<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * 与用户表建立一对多的关联关系
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
