<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
        'description',
        'college_id',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}