<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'P';
    public const STATUS_APPROVED = 'A';
    public const STATUS_REJECTED = 'R';

    protected $fillable = [
        'user_id',
        'amount',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvals()
    {
        return $this->hasMany(ExpenseApproval::class);
    }
}
