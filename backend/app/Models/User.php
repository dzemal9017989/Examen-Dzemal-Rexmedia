<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Task;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // This dictates which attributes should be mass assignable
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
    ];

    // This will be hidden when the model is converted to an array or JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // This function returns thes fields that should be cast to a specific data type
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // This function defines the relationship between User and Task
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // This function checks if the user is a admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
