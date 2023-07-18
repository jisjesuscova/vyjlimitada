<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'organizator_id',
        'event_name',
        'ticket_quantity',
        'event_date'
    ];

    public function organizator()
    {
        return $this->belongsTo(User::class);
    }
}
