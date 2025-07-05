<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Invitation extends Model
{

    // This allows laravel to use these fileds for mass assignment
    protected $fillable = [
        'name',
        'email',
        'role', 
        'token',
        'expires_at',
        'accepted'
    ];

    // This function specifies how certain filed types should be handled automatically
    protected function casts(): array
    {
        // This returns an array that defines the data types for certain fields
        return [
            'expires_at' => 'datetime',
            'accepted' => 'boolean',
        ];
    }


    // This function is called when the model is being created
    public static function boot()
    {
        // This calls the parent boot method to ensure that the model's booting process is executed
        parent::boot();
        static::creating(
            function ($invitation) {
                // This generates a random token for the invitation
                $invitation->token = Str::random(60);
                // This sets the expiration date for the invitation to 7 days from now
                $invitation->expires_at = Carbon::now()->addDays(7); // 7 dagen geldig
            }
        );
    }

    // This function looks if the invitation is expired
    public function isExpired()
    {
        // This will return true or false if the invitation is expired
        return Carbon::now()->isAfter($this->expires_at);
    }

    // This function look if in the invitation is accepted
    public function isValid()
    {
        // This returns a invitation if is not accepted and not expired
        return !$this->accepted && !$this->isExpired();
    }


    // This function returns true if the invitation is for an admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
