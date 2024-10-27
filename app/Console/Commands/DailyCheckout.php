<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Command;

class DailyCheckout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'force check out at 24:00';


    public function handle()
    {
        $users =Attendance::where('check_out',null)->get();
        foreach ($users as $user) {
            $user->check_out = now()->toTimeString();
            $user->check_out_lat = $user->check_in_lat;
            $user->check_out_long = $user->check_in_long;
            $user->force_checkout  = 1;
            $user->save();
        }
    }
}
