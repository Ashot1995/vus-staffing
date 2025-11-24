<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Job::create([
            'title' => 'Erfaren Snickare Sökes',
            'description' => 'Vi söker en erfaren snickare för uppdrag i Stockholmsområdet. Du kommer att arbeta med renovering och nybyggnation.',
            'location' => 'Stockholm',
            'employment_type' => 'full-time',
            'salary' => '28000-35000 SEK/månad',
            'requirements' => '- Minst 3 års erfarenhet\n- Giltigt B-körkort\n- Kunskap i svenska',
            'responsibilities' => '- Montering och installation\n- Läsa ritningar\n- Kvalitetssäkring',
            'is_published' => true,
            'deadline' => now()->addDays(30),
        ]);

        \App\Models\Job::create([
            'title' => 'Lagerarbetare Till Lager',
            'description' => 'VUS Bemanning söker nu erfarna lagerarbetare för arbete på vårt lager i Göteborg.',
            'location' => 'Göteborg',
            'employment_type' => 'temporary',
            'salary' => '22000-26000 SEK/månad',
            'requirements' => '- Tidigare erfarenhet av lagerarbete\n- Truckkort är meriterande\n- Flexibel och ansvarstagande',
            'responsibilities' => '- Plockning och packning\n- Lastning/lossning\n- Lagerhantering',
            'is_published' => true,
            'deadline' => now()->addDays(15),
        ]);

        \App\Models\Job::create([
            'title' => 'Projektledare Bygg',
            'description' => 'Vi söker en driven projektledare för spännande byggprojekt.',
            'location' => 'Malmö',
            'employment_type' => 'full-time',
            'salary' => '40000-50000 SEK/månad',
            'requirements' => '- Civilingenjör eller motsvarande\n- Minst 5 års erfarenhet\n- Goda kunskaper i MS Project',
            'responsibilities' => '- Leda byggprojekt\n- Budgetansvar\n- Samordning av leverantörer',
            'is_published' => true,
            'deadline' => now()->addDays(45),
        ]);
    }
}
