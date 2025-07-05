<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    // This protects the models against mass assignment vulnerabilities 
    // fillable allows you to specify which attributes should be mass assignable
    protected $fillable = ['title', 'description', 'status_id', 'deadline', 'user_id'];

    // This function defines the relationship between Task and User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // This function defines the relationship between Task and Status
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

}
