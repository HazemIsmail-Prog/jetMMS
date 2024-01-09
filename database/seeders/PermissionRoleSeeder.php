<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission_role = array(
            array('permission_id' => '1', 'role_id' => '2'),
            array('permission_id' => '40', 'role_id' => '2'),
            array('permission_id' => '1', 'role_id' => '3'),
            array('permission_id' => '2', 'role_id' => '3'),
            array('permission_id' => '11', 'role_id' => '3'),
            array('permission_id' => '12', 'role_id' => '3'),
            array('permission_id' => '13', 'role_id' => '3'),
            array('permission_id' => '25', 'role_id' => '3'),
            array('permission_id' => '26', 'role_id' => '3'),
            array('permission_id' => '27', 'role_id' => '3'),
            array('permission_id' => '29', 'role_id' => '3'),
            array('permission_id' => '30', 'role_id' => '3'),
            array('permission_id' => '31', 'role_id' => '3'),
            array('permission_id' => '32', 'role_id' => '3'),
            array('permission_id' => '34', 'role_id' => '3'),
            array('permission_id' => '35', 'role_id' => '3'),
            array('permission_id' => '36', 'role_id' => '3'),
            array('permission_id' => '38', 'role_id' => '3'),
            array('permission_id' => '40', 'role_id' => '3'),
            array('permission_id' => '41', 'role_id' => '3'),
            array('permission_id' => '42', 'role_id' => '3'),
            array('permission_id' => '43', 'role_id' => '3'),
            array('permission_id' => '1', 'role_id' => '4'),
            array('permission_id' => '29', 'role_id' => '4'),
            array('permission_id' => '30', 'role_id' => '4'),
            array('permission_id' => '31', 'role_id' => '4'),
            array('permission_id' => '34', 'role_id' => '4'),
            array('permission_id' => '35', 'role_id' => '4'),
            array('permission_id' => '40', 'role_id' => '4'),
            array('permission_id' => '1', 'role_id' => '5'),
            array('permission_id' => '30', 'role_id' => '5'),
            array('permission_id' => '34', 'role_id' => '5'),
            array('permission_id' => '37', 'role_id' => '5'),
            array('permission_id' => '39', 'role_id' => '5'),
            array('permission_id' => '40', 'role_id' => '5'),
            array('permission_id' => '1', 'role_id' => '6'),
            array('permission_id' => '29', 'role_id' => '6'),
            array('permission_id' => '38', 'role_id' => '6'),
            array('permission_id' => '1', 'role_id' => '7'),
            array('permission_id' => '29', 'role_id' => '7'),
            array('permission_id' => '38', 'role_id' => '7')
        );

        DB::table('permission_role')->insert($permission_role);
    }
}
