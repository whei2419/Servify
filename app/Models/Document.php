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

    protected $hidden = ['requirements','documentInputs'];

    protected $appends = ['requirement','input'];

    public function getRequirementAttribute()
    {
        return $this->requirements;
    }

    public function getInputAttribute()
    {
        return $this->documentInputs;
    }

   
    
    public function documentInputs()
    {
        return $this->hasMany(DocumentInput::class);
    }

    public function requirement()
    {
        return $this->hasMany(Appointment::class);
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }




}
