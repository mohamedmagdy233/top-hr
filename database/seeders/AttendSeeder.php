<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Admin;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AttendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 30; $i++) {
            Attendance::query()->create([
                'date' => date('Y-m-d', strtotime('-' . $i . ' days')),
                'diff_time' => rand(400,500),
                'user_id' => 3,
                'check_in' => date('H:i:s', strtotime('-' . $i . ' days')),
                'check_out' => date('H:i:s', strtotime('-' . $i . ' days')),
                'lat' => rand(1, 100),
                'long' => rand(1, 100),
            ]);
        }
    }

}
