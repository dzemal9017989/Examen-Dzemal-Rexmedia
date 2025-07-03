<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Invitation extends Model
{


    protected $fillable = [
        'name',
        'email',
        'role', // voeg dit toe als je 'role' wil kunnen vullen via create()
        'token',
        'expires_at',
        'accepted'
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'accepted' => 'boolean',
        ];
    }


    public static function boot()
    {
        parent::boot();
        static::creating(
            function ($invitation) {
                $invitation->token = Str::random(60);
                $invitation->expires_at = Carbon::now()->addDays(7); // 7 dagen geldig
            }
        );
    }
    public function isExpired()
    {
        return Carbon::now()->isAfter($this->expires_at);
    }
    public function isValid()
    {
        return !$this->accepted && !$this->isExpired();
    }



    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
