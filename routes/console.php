<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('league:reset')->twiceMonthly(1, 15, '00:00');
