<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentInput;

class DocumentInputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DocumentInput::create(['document_id'=>1,'input_id'=>1]);
        DocumentInput::create(['document_id'=>1,'input_id'=>2]);
        DocumentInput::create(['document_id'=>1,'input_id'=>3]);
        DocumentInput::create(['document_id'=>1,'input_id'=>8]);


        DocumentInput::create(['document_id'=>2,'input_id'=>1]);
        DocumentInput::create(['document_id'=>2,'input_id'=>5]);
        DocumentInput::create(['document_id'=>2,'input_id'=>6]);
        DocumentInput::create(['document_id'=>2,'input_id'=>7]);
        DocumentInput::create(['document_id'=>2,'input_id'=>3]);

        DocumentInput::create(['document_id'=>3,'input_id'=>1]);
        DocumentInput::create(['document_id'=>3,'input_id'=>2]);
        DocumentInput::create(['document_id'=>3,'input_id'=>8]);

        DocumentInput::create(['document_id'=>4,'input_id'=>1]);
        DocumentInput::create(['document_id'=>4,'input_id'=>8]);

        DocumentInput::create(['document_id'=>5,'input_id'=>1]);
        DocumentInput::create(['document_id'=>5,'input_id'=>7]);
        DocumentInput::create(['document_id'=>5,'input_id'=>9]);
        DocumentInput::create(['document_id'=>5,'input_id'=>10]);
        DocumentInput::create(['document_id'=>5,'input_id'=>11]);
        DocumentInput::create(['document_id'=>5,'input_id'=>12]);
        DocumentInput::create(['document_id'=>5,'input_id'=>13]);
        DocumentInput::create(['document_id'=>5,'input_id'=>3]);

        DocumentInput::create(['document_id'=>6,'input_id'=>1]);
        DocumentInput::create(['document_id'=>6,'input_id'=>14]);
        DocumentInput::create(['document_id'=>6,'input_id'=>15]);
        DocumentInput::create(['document_id'=>6,'input_id'=>17]);
        DocumentInput::create(['document_id'=>6,'input_id'=>18]);
        DocumentInput::create(['document_id'=>6,'input_id'=>7]);
        DocumentInput::create(['document_id'=>6,'input_id'=>4]);
        DocumentInput::create(['document_id'=>6,'input_id'=>3]);

        DocumentInput::create(['document_id'=>7,'input_id'=>1]);
        DocumentInput::create(['document_id'=>7,'input_id'=>14]);
        DocumentInput::create(['document_id'=>7,'input_id'=>16]);
        DocumentInput::create(['document_id'=>7,'input_id'=>7]);
        DocumentInput::create(['document_id'=>7,'input_id'=>4]);
        DocumentInput::create(['document_id'=>7,'input_id'=>3]);

    }
}
