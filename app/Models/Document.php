<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public $table = 'documents';

    protected $fillable = [
        'name',
    ];

    protected $hidden = ['documentInputs'];

    protected $appends = ['input'];

    public function getInputAttribute()
    {
        return $this->documentInputs;
    }
    
    public function documentInputs()
    {
        return $this->hasMany(DocumentInput::class);
    }

    public function appointment()
    {
        return $this->hasMany(Appointment::class);
    }




}
