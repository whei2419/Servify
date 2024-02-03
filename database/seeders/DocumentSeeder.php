<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Document::create(['name'=>'Barangay Clearance']);
        Document::create(['name'=>'Business Clearance']);
        Document::create(['name'=>'Certificate of Indigency']);
        Document::create(['name'=>'Barangay Certification']);
        Document::create(['name'=>'Tricycle Permit']);
        Document::create(['name'=>'Barangay Clearance (Wiring Building)']);
        Document::create(['name'=>'Barangay Clearance (Working)']);


    }
}
