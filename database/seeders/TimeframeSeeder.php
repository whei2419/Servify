<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DateTime;
use DateInterval;
use App\Models\Timeframe;

class TimeframeSeeder extends Seeder
{
    public function run()
    {
        $startTime = new DateTime('07:00:00');
        $endTime = new DateTime('18:00:00');

        while ($startTime < $endTime) {
            $endTimeFrame = clone $startTime;
            $endTimeFrame->add(new DateInterval('PT59M')); // Add 59 minutes to get end time

            Timeframe::create([
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTimeFrame->format('H:i:s'),
            ]);

            $startTime->add(new DateInterval('PT1H')); // Move to next hour
        }
    }
}
