<?php

use Illuminate\Database\Seeder;

class PaperTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('paper_type')->delete();
        
        \DB::table('paper_type')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '学历考试',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '职业技能培训',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '语言培训',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '出国留',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '兴趣辅导',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'K12',
                'description' => '',
                'created_at' => '2019-01-01 00:00:00',
                'updated_at' => '2019-01-01 00:00:00',
            ),
        ));
        
        
    }
}