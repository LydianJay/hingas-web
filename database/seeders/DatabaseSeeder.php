<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Admin;
use App\Models\Dance;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::create([
            'fname'     => 'System',
            'mname'     => '',
            'lname'     => 'Admin',
            'gender'    => 'other',
            'dob'       => Carbon::now()->format('Y-m-d'),
            'contactno' => '09123456789',
            'email'     => 'hingaslifestylestudios@gmail.com',
        ]);

        $role = Role::create([
            'user_id'       => $user->id,
            'role_name'     => 'System Admin',
        ]);

        Admin::create([
            'user_id'       => $user->id,
            'password'      => bcrypt('@default123'),
            'role_id'       => $role->id,
        ]);

        // $dances = ['Ballet', 'Modern Dance', 'Folk Dance', 'Gymnastics', 'Hip-Hop'];

        // foreach($dances as $dance) {
        //     Dance::create([
        //         'name'          => $dance,
        //         'session_count' => 8,
        //         'price'         => 2500,
        //     ]);
        // }
    }
}
