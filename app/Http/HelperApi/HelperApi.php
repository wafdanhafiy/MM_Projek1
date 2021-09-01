<?php

namespace App\Http\HelperApi;

use App\Models\Activity;
use Carbon\Carbon;

class HelperApi
{
    public static function createobject($user, $i)
    {

        //date
        $date = Carbon::today()->subDays($i)->toDateString();

        //count a day

        $today = Activity::whereDate('start', now()->subDays($i))
            ->where('user_id', $user['id'])
            ->orderBy('name', 'ASC')
            ->count();

        //work count
        $work = Activity::whereDate('start', now()->subDays($i))
            ->where('user_id', $user['id'])
            ->where('category_id', '1')
            ->orderBy('name', 'ASC')
            ->count();

        //learn count
        $learn = Activity::whereDate('start', now()->subDays($i))
            ->where('user_id', $user['id'])
            ->where('category_id', '2')
            ->orderBy('name', 'ASC')
            ->count();

        //play count
        $play = Activity::whereDate('start', now()->subDays($i))
            ->where('user_id', $user['id'])
            ->where('category_id', '3')
            ->orderBy('name', 'ASC')
            ->count();


        $response = [
            'tanggal' => $date,
            'total' => $today,
            'category' => [
                'work' => $work,
                'learn' => $learn,
                'play' => $play,
            ],
        ];

        return $response;
    }
}
