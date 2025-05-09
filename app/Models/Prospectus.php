<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospectus extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        //'course_id', // Removed for many-to-many relationship
        'status_id',
        'effective_year',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Remove single course relationship
    // public function course()
    // {
    //     return $this->belongsTo(Course::class);
    // }

    // Add many-to-many relationship with courses
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_prospectus', 'prospectus_id', 'course_id')
                    ->withPivot('semester');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
