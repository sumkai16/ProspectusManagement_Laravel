<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $primaryKey = 'enrollment_id';

    protected $fillable = [
        'user_id',
        'program_id',
        'status_id',
        'academic_year',
        'semester',
        'enrollment_date',
        'birth_date',
        'year_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
