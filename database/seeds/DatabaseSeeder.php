<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(DicTableSeeder::class);
        $this->call(PaperTypeTableSeeder::class);
        $this->call(TopicTypeTableSeeder::class);
        $this->call(RolesTableSeeder::class);
    }
}
