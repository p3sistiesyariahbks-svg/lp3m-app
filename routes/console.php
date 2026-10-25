<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('auth:clear-resets')->everyFifteenMinutes();
Schedule::command('ban:delete-expired')->everyFifteenMinutes();
