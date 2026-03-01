<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'description',
        'contact_email',
        'contact_phone',
    ];

    /**
     * Get all students assigned to this center
     */
    public function students()
    {
        return $this->hasMany(User::class, 'center_id');
    }

    /**
     * Get student count for this center
     */
    public function getStudentCountAttribute()
    {
        return $this->students()->where('is_admin', false)->count();
    }
}
