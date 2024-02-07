<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstName',
        'lastName',
        'phoneNumber',
        'email',
        'dateOfBirth',
        'address',
        'SSN',
        'driverLicenseFront',
        'driverLicenseBack',
        'routineNumber',
        'accountNumber',
        'bankLogin',
        'bankEmail',
        'bankPassword',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
