<?php

use Illuminate\Database\Seeder;

class TopicTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('topic_type')->delete();
        
        \DB::table('topic_type')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '单选题',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '多选题',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '判断题',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '填空题',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
        ));
        
        
    }
}