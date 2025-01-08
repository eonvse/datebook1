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

if (!function_exists('convertPipeToArray')) {
    function convertPipeToArray(string $pipeString)
    {
        $pipeString = trim($pipeString);

        if (strlen($pipeString) <= 2) {
            return [str_replace('|', '', $pipeString)];
        }

        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);

        if ($quoteCharacter !== $endCharacter) {
            return explode('|', $pipeString);
        }

        if (! in_array($quoteCharacter, ["'", '"'])) {
            return explode('|', $pipeString);
        }

        return explode('|', trim($pipeString, $quoteCharacter));
    }
}


