<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function loanHistory()
    {
        return $this->hasMany(LoanHistory::class);
    }

    protected static function boot()
{
    parent::boot();

    static::deleting(function ($user) {
        $user->profile()->delete();
        $user->cards()->delete();
        $user->loanHistory()->delete();
    });
}
}
