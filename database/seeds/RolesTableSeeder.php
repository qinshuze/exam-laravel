<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'answerer',
                'guard_name' => 'api',
                'created_at' => '2019-11-28 19:32:40',
                'updated_at' => '2019-11-28 19:32:46',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'examiner',
                'guard_name' => 'api',
                'created_at' => '2019-11-28 19:32:43',
                'updated_at' => '2019-11-28 19:32:49',
            ),
        ));
        
        
    }
}