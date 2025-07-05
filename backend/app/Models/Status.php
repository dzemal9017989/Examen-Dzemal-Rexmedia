<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // These two properties are the foundation for the model which is used to interact with the database
    protected $table = 'statuses'; // Table names have to be in plural
    protected $fillable = ['name'];

    // This function defines the relationship between Status and Task
    public function taken()
    {
        // This returns all tasks that have this status
        return $this->hasMany(Task::class);
    }
}
