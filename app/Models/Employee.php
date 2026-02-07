<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'names',
        'email',
        'employee_identifier',
        'phone_number',
    ];

   
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}

