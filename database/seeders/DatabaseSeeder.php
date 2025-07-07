<?php

namespace Database\Seeders;

use App\Models\User;
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

        User::create([
            
            'email'     => 'admin@example.com',
            'password'  => bcrypt('@default123'),
            'fname'     => 'System',
            'mname'     => '',
            'lname'     => 'Admin',
            'contactno' => '1234567890',
            'dob'       => Carbon::now()->format('Y-m-d'),
            'is_admin'  => true,
            ]
        );

        $dances = ['Ballet', 'Modern Dance', 'Folk Dance', 'Gymnastics', 'Hip-Hop'];

        foreach($dances as $dance) {
            Dance::create([
                'name' => $dance
            ]);
        }
    }
}
