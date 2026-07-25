<?php

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:process-all-queues')
    ->everyMinute()
    ->withoutOverlapping();