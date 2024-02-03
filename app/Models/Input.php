<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    use HasFactory;

    
    public $table = 'inputs';

    protected $fillable = [
        'name',
        'type',
    ];

    public function documentInput()
    {
        return $this->hasMany(DocumentInput::class);
    }
}
