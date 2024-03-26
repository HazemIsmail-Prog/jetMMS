<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = array(
            array('id' => '1', 'name_ar' => 'مسؤول عام',                            'department_id' => 1,    'name_en' => 'Super Admin', 'username' => 'admin', 'email' => NULL, 'cid' => NULL, 'email_verified_at' => NULL, 'password' => '$2y$10$K0H3iM2WPC1hXjDbE6jh/uW6L7/LFY8M4cepcLeE/O6sof9agEosu', 'active' => '1', 'remember_token' => '5fNQa2kUtSxW81DLhLFgJlab0i1k4oAF9BgZgSaEmtjqNqKgRrYpx6ZGPcQq', 'created_at' => NULL, 'updated_at' => '2023-07-16 19:53:34', 'deleted_at' => NULL, 'title_id' => '2', 'shift_id' => NULL),
        );

        User::insert($users);


        User::find(1)->roles()->attach(1);
    
    }
}
