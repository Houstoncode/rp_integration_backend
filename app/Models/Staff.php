<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'middle_name', 'gender', 'birthday'];

    public function pastJobs() {
        return $this->hasMany(PastJob::class);
    }
}
