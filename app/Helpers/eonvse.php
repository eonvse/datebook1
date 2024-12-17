<?php

use Illuminate\Support\Str;

if (!function_exists('get_app_models_list')) {
    function get_app_models_list()
    {
        $dir = app_path().DIRECTORY_SEPARATOR.'Models';
        $files = scandir($dir);

        $models = array();
        $namespace = 'App\\Models\\';
        foreach($files as $file) {
          //skip current and parent folder entries and non-php files
          if ($file == '.' || $file == '..' || !preg_match('/(.*).php/', $file)) continue;
          $models[] = $namespace . preg_replace('/.php$/', '', $file);
        }

        return $models;
    }
}

