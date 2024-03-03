<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Requirement;

class RequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Requirement::create([
            'name' => 'Valid ID',
            'document_id' => 1,
        ]);
        Requirement::create([
            'name' => 'Home Owners Clearance (HOA)',
            'document_id' => 1,
        ]);

        Requirement::create([
            'name' => 'Valid ID',
            'document_id' => 3,
        ]);
        Requirement::create([
            'name' => 'Home Owners Clearance (HOA)',
            'document_id' => 3,
        ]);

        Requirement::create([
            'name' => 'Valid ID',
            'document_id' => 4,
        ]);
        Requirement::create([
            'name' => 'Home Owners Clearance (HOA)',
            'document_id' => 4,
        ]);
        
        Requirement::create([
            'name' => 'Last year license for (Renewal)',
            'document_id' => 2,
        ]);
        Requirement::create([
            'name' => 'DTI or SEC',
            'document_id' => 2,
        ]);
        Requirement::create([
            'name' => 'Market Clearance (Public Market Tenants)',
            'document_id' => 2,
        ]);
        Requirement::create([
            'name' => 'Award Notice (SM Tenants)',
            'document_id' => 2,
        ]);
        Requirement::create([
            'name' => 'Sedula',
            'document_id' => 2,
        ]);
        Requirement::create([
            'name' => 'Home Owners Clearance (HOA)',
            'document_id' => 2,
        ]);

        Requirement::create([
            'name' => 'Stencil Form',
            'document_id' => 5,
        ]);
        Requirement::create([
            'name' => 'TODA Cleraance',
            'document_id' => 5,
        ]);
        Requirement::create([
            'name' => 'Home Owners Clearance (HOA)',
            'document_id' => 5,
        ]);
        
    }
}
