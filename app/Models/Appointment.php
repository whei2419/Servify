<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public $table = 'appointments';
    protected $appends = ['status'];

    protected $fillable = [
        'date',
        'email',
        'values',
        'code'
    ]; 
    public function getStatusAttribute()
    {
        return $this->status()->first();
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function queue()
    {
        return $this->hasMany(Queue::class);
    }
}
