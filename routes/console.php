<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('league:reset')->weeklyOn(1, '00:00');
