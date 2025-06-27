<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statussen'; // 💡 belangrijk: tabelnaam in meervoud
    protected $fillable = ['naam'];

    public function taken()
    {
        return $this->hasMany(Taak::class);
    }
}
