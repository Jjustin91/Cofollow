<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\College;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $citc = College::where('abbreviation', 'CITC')->first();
        $cea = College::where('abbreviation', 'CEA')->first();

        if ($citc) {
            Event::create([
                'college_id' => $citc->id,
                'title' => 'CITC General Assembly',
                'description' => 'Mandatory assembly for all IT, CS, DS, and TCM students.',
                'event_date' => Carbon::now()->addDays(2)->setTime(13, 0),
                'location' => 'USTP Main Gym',
            ]);

            Event::create([
                'college_id' => $citc->id,
                'title' => 'Code Marathon 2026',
                'description' => '24-hour hackathon for developers.',
                'event_date' => Carbon::now()->addDays(5)->setTime(8, 0),
                'location' => 'Computer Lab 3',
            ]);
        }

        if ($cea) {
            Event::create([
                'college_id' => $cea->id,
                'title' => 'Engineering Week Kickoff',
                'description' => 'Opening ceremonies for the CEA week.',
                'event_date' => Carbon::now()->addDays(3)->setTime(9, 0),
                'location' => 'Engineering Building Atrium',
            ]);
        }
    }
}