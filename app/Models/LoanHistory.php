<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'status',
        'amount',
        'purpose',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
