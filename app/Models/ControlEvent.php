<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'control_id',
        'event_id'
    ];
}
