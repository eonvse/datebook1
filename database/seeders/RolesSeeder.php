<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('roles')->insert([
            ['name' => 'Root', 'description'=>'Супер админ. Доступно всё.'],
            ['name' => 'Admin', 'description'=>'Доступ к панели администрирования, просмотр ролей, добавление-редактирование пользователей.'],
            ['name' => 'Control', 'description'=>'Доступ к панели Control'],
            ['name' => 'Teams Admin', 'description'=>'Администратор групп (добавление, редактирование)'],
            ['name' => 'Materials Admin', 'description'=>'Администратор материалов (добавление редактирование категорий материалов, полное управление самими материалами)'],
            ['name' => 'Materials Manager', 'description'=>'Полный доступ к материалам.'],
            ['name' => 'Materials User', 'description'=>'Просмотр всех материалов. Редактирование/удаление только своих.'],

        ]);
    }
}
