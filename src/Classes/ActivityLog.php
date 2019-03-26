<?php

namespace Olekjs\ActivityLog\Classes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Olekjs\ActivityLog\Models\ActivityLog as Activity;

class ActivityLog
{
    /**
     * Add simple log.
     *
     * @param string $activity
     * @param string $description
     * @return statement
     */
    public function add(string $activity, string $description)
    {
        $data = [
            'activity'    => $activity,
            'description' => $description,
        ];

        return $this->createLog($data);
    }

    /**
     * Add log with user id.
     *
     * @param model/int $user
     * @param string $activity
     * @param string $description
     * @return statement
     */
    public function addWithUser($user, string $activity, string $description)
    {
        $data = [
            'activity'    => $activity,
            'description' => $description,
        ];

        if ($user instanceof Model) {
            $data['user_id'] = $user->id;
        }

        if (is_int($user)) {
            $data['user_id'] = $user;
        }

        return $this->createLog($data);
    }

    /**
     * Add log with username.
     *
     * @param string $username
     * @param string $activity
     * @param string $description
     * @return statement
     */
    public function addWithUsername(string $username, string $activity, string $description)
    {
        $data = [
            'activity'    => $activity,
            'description' => $description,
        ];

        if (is_string($username)) {
            $data['username'] = $username;
        }

        return $this->createLog($data);
    }

    /**
     * Get all logs.
     *
     * @return collection
     */
    public function all()
    {
        return Activity::all();
    }

    /**
     * Get all logs from today.
     *
     * @return collection
     */
    public function today()
    {
        return Activity::whereDate('created_at', Carbon::today())->get();
    }

    /**
     * Get all logs from last month.
     *
     * @return collection
     */
    public function lastMonth()
    {
        $from = Carbon::today()->subMonth()->format('Y-m-d');
        $to   = Carbon::today()->format('Y-m-d');

        return Activity::whereBetween('created_at', [$from, $to])->get();
    }

    /**
     * Get all logs from between date.
     *
     * @param date|string $from
     * @param date|string $to
     * @return collection
     */
    public function between($from, $to)
    {
        if (is_string($from)) {
            $from = Carbon::parse($from);
        }

        if (is_string($to)) {
            $to = Carbon::parse($to);
        }

        $from = $from->format('Y-m-d');
        $to   = $to->format('Y-m-d');

        return Activity::whereBetween('created_at', [$from, $to])->get();
    }

    // /**
    //  * Get users with the most activity.
    //  *
    //  * @param  int $value
    //  * @return model
    //  */
    // public function mostActivityUsers($value = null)
    // {
    //     return Activity::whereBetween('created_at', [$from, $to])->get();
    // }

    /**
     * Create activitylog record in database.
     *
     * @param array $data
     * @return statement
     */
    private function createLog(array $data)
    {
        if (Activity::create($data)) {
            return true;
        }

        return false;
    }
}
