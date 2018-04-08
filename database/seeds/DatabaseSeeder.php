<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);\
        for($i=0;$i<=50;$i++){
            DB::table('post_tag')->insert([
                'post_id' => rand(51,100),
                'tag_id' => rand(1,19),
            ]);
        }

    }
}
