<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\College;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $citc = College::where('abbreviation', 'CITC')->first();
        $cea = College::where('abbreviation', 'CEA')->first();

        // University-wide Organization (No college_id)
        Organization::create([
            'name' => 'Central Student Government',
            'abbreviation' => 'CSG',
            'description' => 'The highest governing student body of the USTP CDO Campus.',
            'college_id' => null,
        ]);

        // CITC Specific Organizations
        if ($citc) {
            Organization::create([
                'name' => 'Society of Information Technology Enthusiasts',
                'abbreviation' => 'SITE',
                'description' => 'The official student organization for BSIT students.',
                'college_id' => $citc->id,
            ]);
            Organization::create([
                'name' => 'Computer Science Guild',
                'abbreviation' => 'CSG-CITC', // Just to differentiate from the main CSG
                'description' => 'The premier organization for Computer Science trailblazers.',
                'college_id' => $citc->id,
            ]);
        }

        // CEA Specific Organizations
        if ($cea) {
            Organization::create([
                'name' => 'Philippine Institute of Civil Engineers - USTP Chapter',
                'abbreviation' => 'PICE',
                'description' => 'The official organization for aspiring Civil Engineers.',
                'college_id' => $cea->id,
            ]);
        }
    }
}