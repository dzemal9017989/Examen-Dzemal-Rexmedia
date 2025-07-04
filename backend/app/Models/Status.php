<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses'; // 💡 belangrijk: tabelnaam in meervoud
    protected $fillable = ['name'];

    public function taken()
    {
        return $this->hasMany(Task::class);
    }
}
