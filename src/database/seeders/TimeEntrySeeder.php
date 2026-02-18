<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TimeEntry;

class TimeEntrySeeder extends Seeder
{
    public function run(): void
    {
        TimeEntry::factory()->count(20)->create();
    }
}
