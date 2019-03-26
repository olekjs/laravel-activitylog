<?php

namespace Olekjs\ActivityLog\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'username', 'activity', 'description'];

    protected $table = 'activity_log';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
