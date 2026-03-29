<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'college_id',
        'title',
        'description',
        'event_date',
        'location',
    ];

    // Tell Laravel to treat this column as a Carbon date object automatically
    protected $casts = [
        'event_date' => 'datetime',
    ];

    // An event belongs to a College
    public function college()
    {
        return $this->belongsTo(College::class);
    }
}