<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'employee_id',
        'check_in_at',
        'check_out_at',
    ];

   
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

