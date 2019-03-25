<?php

namespace Olekjs\ActivityLog;

use Olekjs\ActivityLog\Models\ActivityLog as Activity;

class ActivityLog
{
    public function test()
    {
        return Activity::find(1)->user;
    }
}