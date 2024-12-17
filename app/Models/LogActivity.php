<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{

    protected $fillable = ['operation', 'model', 'item_id','item_name', 'user_id','user_name', 'url', 'method', 'ip', 'agent'];

    protected $appends = [
        'model_name'
    ];

    public function getModelNameAttribute() {
        return str_replace("App\\Models\\", "", $this->model);
    }

}
