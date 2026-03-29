<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\College;
use App\Models\Program;

class CollegeProgramSeeder extends Seeder
{
    public function run(): void
    {
        // 1. College of Information Technology and Computing (CITC)
        $citc = College::create([
            'name' => 'College of Information Technology and Computing',
            'abbreviation' => 'CITC',
        ]);

        $citcPrograms = [
            ['name' => 'Bachelor of Science in Information Technology', 'abbreviation' => 'BSIT'],
            ['name' => 'Bachelor of Science in Computer Science', 'abbreviation' => 'BSCS'],
            ['name' => 'Bachelor of Science in Data Science', 'abbreviation' => 'BSDS'],
            ['name' => 'Bachelor of Science in Technology Communication Management', 'abbreviation' => 'BSTCM'],
        ];

        foreach ($citcPrograms as $program) {
            Program::create(array_merge($program, ['college_id' => $citc->id]));
        }

        // 2. College of Engineering and Architecture (CEA)
        $cea = College::create([
            'name' => 'College of Engineering and Architecture',
            'abbreviation' => 'CEA',
        ]);

        $ceaPrograms = [
            ['name' => 'Bachelor of Science in Architecture', 'abbreviation' => 'BSArch'],
            ['name' => 'Bachelor of Science in Civil Engineering', 'abbreviation' => 'BSCE'],
            ['name' => 'Bachelor of Science in Electrical Engineering', 'abbreviation' => 'BSEE'],
            ['name' => 'Bachelor of Science in Electronics Engineering', 'abbreviation' => 'BSECE'],
            ['name' => 'Bachelor of Science in Mechanical Engineering', 'abbreviation' => 'BSME'],
            ['name' => 'Bachelor of Science in Computer Engineering', 'abbreviation' => 'BSCpE'],
        ];

        foreach ($ceaPrograms as $program) {
            Program::create(array_merge($program, ['college_id' => $cea->id]));
        }

        // 3. College of Science and Mathematics (CSM)
        $csm = College::create([
            'name' => 'College of Science and Mathematics',
            'abbreviation' => 'CSM',
        ]);

        $csmPrograms = [
            ['name' => 'Bachelor of Science in Applied Mathematics', 'abbreviation' => 'BSAppMath'],
            ['name' => 'Bachelor of Science in Applied Physics', 'abbreviation' => 'BSAppPhys'],
            ['name' => 'Bachelor of Science in Chemistry', 'abbreviation' => 'BSChem'],
            ['name' => 'Bachelor of Science in Environmental Science', 'abbreviation' => 'BSEnviSci'],
        ];

        foreach ($csmPrograms as $program) {
            Program::create(array_merge($program, ['college_id' => $csm->id]));
        }

        // 4. College of Technology (COT)
        $cot = College::create([
            'name' => 'College of Technology',
            'abbreviation' => 'COT',
        ]);

        $cotPrograms = [
            ['name' => 'Bachelor of Science in Electro-Mechanical Technology', 'abbreviation' => 'BSEMT'],
            ['name' => 'Bachelor of Science in Autotronics', 'abbreviation' => 'BSAutotronics'],
            ['name' => 'Bachelor of Science in Electronics Technology', 'abbreviation' => 'BSET'],
        ];

        foreach ($cotPrograms as $program) {
            Program::create(array_merge($program, ['college_id' => $cot->id]));
        }
    }
}