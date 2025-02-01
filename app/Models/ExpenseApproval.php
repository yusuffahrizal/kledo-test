<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseApproval extends Model
{
    use HasFactory;

    public const STATUS_APPROVED = 'A';
    public const STATUS_REJECTED = 'R';

    protected $fillable = [
        'expense_id',
        'user_id',
        'stage',
        'status'
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
