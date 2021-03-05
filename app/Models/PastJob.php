<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PastJob extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'staff_id', 'start_date', 'end_date'];
}
