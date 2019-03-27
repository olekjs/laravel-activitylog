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
     * Get all logs from months.
     *
     * @param int $value
     * @return collection
     */
    public function lastMonths($value = 1)
    {
        $from = Carbon::today()->subMonths($value)->format('Y-m-d');
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

    /**
     * Get user logs.
     *
     * @param  int|model $user
     * @return collection
     */
    public function userLogs($user)
    {
        $userId;

        if ($user instanceof Model) {
            $userId = $user->id;
        }

        if (is_int($user)) {
            $userId = $user;
        }

        return Activity::where('user_id', $userId)->get();
    }

    /**
     * Get user logs from specific date.
     *
     * @param  int|model $user
     * @param  string|array $date
     * @return collection
     */
    public function userLogsWhereDate($user, $date)
    {
        $userId;

        if ($user instanceof Model) {
            $userId = $user->id;
        }

        if (is_int($user)) {
            $userId = $user;
        }

        if (is_array($date)) {
            $from = $date[0];
            $to   = $date[1];

            if (is_string($date[0])) {
                $from = Carbon::parse($date[0]);
            }

            if (is_string($date[1])) {
                $to = Carbon::parse($date[1]);
            }

            return Activity::whereBetween('created_at', [$from->format('Y-m-d'), $to->format('Y-m-d')])->where('user_id', $userId)->get();
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);

            return Activity::whereDate('created_at', $date->format('Y-m-d'))->where('user_id', $userId)->get();
        }
    }

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
