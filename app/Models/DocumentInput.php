<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentInput extends Model
{
    use HasFactory;

    public $table = 'document_inputs';

    protected $fillable = [
        'document_id',
        'input_id',
    ];

    protected $appends = ['input'];
    protected $hidden = ['document_id','input_id'];


    public function getInputAttribute()
    {
        return $this->input()->first();
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function input()
    {
        return $this->belongsTo(Input::class);
    }


}
