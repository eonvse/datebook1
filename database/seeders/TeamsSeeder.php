<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('teams')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('teams')->insert([
            ['name' => 'Public', 'slug'=>'public', 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')]
        ]);

        /*$dataRaw = DB::table('users')->selectRaw('`id` as user_id, 1 as team_id')->get('user_id','team_id')->toArray();
        $data = json_decode(json_encode($dataRaw), true);
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('team_user')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('team_user')->insert($data);*/


    }
}
