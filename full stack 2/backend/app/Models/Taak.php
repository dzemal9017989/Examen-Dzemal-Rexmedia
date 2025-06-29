<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Taak extends Model
{
    use HasFactory;

    protected $fillable = ['titel', 'beschrijving', 'status_id', 'deadline', 'gebruiker_id'];

    public function gebruiker()
    {
        return $this->belongsTo(User::class, 'gebruiker_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
