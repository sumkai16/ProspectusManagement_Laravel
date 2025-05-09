<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'program_id', 
        'birth_date',
        'year_level',
        'status_id',];

    public function course(){
        return $this->belongsTo(Program::class);
    }  
}
