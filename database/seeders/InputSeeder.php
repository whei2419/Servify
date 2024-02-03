<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Input;

class InputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Input::create(['name' => 'Name','type'=>'text']);
        Input::create(['name' => 'Age','type'=>'number']);
        Input::create(['name' => 'Date','type'=>'date']);
        Input::create(['name' => 'Expiration Date','type'=>'date']);
        Input::create(['name' => 'Trade Name','type'=>'text']);
        Input::create(['name' => 'Type Of Business','type'=>'text']);
        Input::create(['name' => 'Location','type'=>'text']);
        Input::create(['name' => 'Purpose','type'=>'text']);
        Input::create(['name' => 'Make/Color','type'=>'text']);
        Input::create(['name' => 'Motor No.','type'=>'text']);
        Input::create(['name' => 'Plate No.','type'=>'text']);
        Input::create(['name' => 'Body No.','type'=>'text']);
        Input::create(['name' => 'Chasis No.','type'=>'text']);
        Input::create(['name' => 'Residential','type'=>'radio']);
        Input::create(['name' => 'Commercial/Industrial','type'=>'radio']);
        Input::create(['name' => 'Commercial','type'=>'radio']);
        Input::create(['name' => 'Institutional','type'=>'radio']);
        Input::create(['name' => 'Government','type'=>'radio']);

        



    }
}
