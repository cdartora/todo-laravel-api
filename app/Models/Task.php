<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'title',
        'description',
    ];

    use HasFactory;
}