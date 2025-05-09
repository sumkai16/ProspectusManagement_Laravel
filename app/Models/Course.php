<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['course_code', 
                            'course_description',
                            'course_units',];
    public function prospectuses()
    {
    return $this->belongsToMany(Prospectus::class, 'course_prospectus', 'course_id', 'prospectus_id');
    }
}
