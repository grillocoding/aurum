<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'value',
        'date',
        'category',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'date' => 'date',
    ];

    public const CATEGORIES = ['Fixas', 'Variáveis', 'Marketing', 'Impostos', 'Outros'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
