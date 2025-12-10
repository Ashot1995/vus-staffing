<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Category 1: Construction/Building
        \App\Models\Job::create([
            'title' => 'Erfaren Snickare Sökes',
            'description' => 'Vi söker en erfaren snickare för uppdrag i Stockholmsområdet. Du kommer att arbeta med renovering och nybyggnation. Vi erbjuder ett varierande arbete med möjlighet till utveckling.',
            'location' => 'Stockholm',
            'employment_type' => 'full-time',
            'salary' => '28000-35000 SEK/månad',
            'requirements' => '- Minst 3 års erfarenhet\n- Giltigt B-körkort\n- Kunskap i svenska\n- Strukturerad och noggrann',
            'responsibilities' => '- Montering och installation\n- Läsa ritningar\n- Kvalitetssäkring\n- Samarbete med kollegor',
            'is_published' => true,
            'deadline' => now()->addDays(30),
        ]);

        // Category 2: Warehouse/Logistics
        \App\Models\Job::create([
            'title' => 'Lagerarbetare Till Lager',
            'description' => 'VUS söker nu erfarna lagerarbetare för arbete på vårt lager i Göteborg. Vi erbjuder ett dynamiskt arbetsmiljö med goda utvecklingsmöjligheter.',
            'location' => 'Göteborg',
            'employment_type' => 'temporary',
            'salary' => '22000-26000 SEK/månad',
            'requirements' => '- Tidigare erfarenhet av lagerarbete\n- Truckkort är meriterande\n- Flexibel och ansvarstagande\n- Fysisk arbetsförmåga',
            'responsibilities' => '- Plockning och packning\n- Lastning/lossning\n- Lagerhantering\n- Inventering',
            'is_published' => true,
            'deadline' => now()->addDays(15),
        ]);

        // Category 3: Management/Project Management
        \App\Models\Job::create([
            'title' => 'Projektledare Bygg',
            'description' => 'Vi söker en driven projektledare för spännande byggprojekt. Du kommer att leda team och ansvara för projektets framgång från start till mål.',
            'location' => 'Malmö',
            'employment_type' => 'full-time',
            'salary' => '40000-50000 SEK/månad',
            'requirements' => '- Civilingenjör eller motsvarande\n- Minst 5 års erfarenhet\n- Goda kunskaper i MS Project\n- Ledaregenskaper',
            'responsibilities' => '- Leda byggprojekt\n- Budgetansvar\n- Samordning av leverantörer\n- Rapportering till ledning',
            'is_published' => true,
            'deadline' => now()->addDays(45),
        ]);

        // Category 4: IT/Technology
        \App\Models\Job::create([
            'title' => 'Systemutvecklare',
            'description' => 'Vi söker en erfaren systemutvecklare för att arbeta med moderna webbapplikationer. Du kommer att vara en del av ett kreativt team och arbeta med spännande projekt.',
            'location' => 'Stockholm',
            'employment_type' => 'full-time',
            'salary' => '45000-60000 SEK/månad',
            'requirements' => '- Minst 3 års erfarenhet av webbutveckling\n- Kunskaper i PHP/Laravel eller JavaScript/React\n- Erfarenhet av databaser\n- God kommunikationsförmåga',
            'responsibilities' => '- Utveckla och underhålla webbapplikationer\n- Samarbeta med designers och andra utvecklare\n- Testa och felsöka\n- Dokumentera kod',
            'is_published' => true,
            'deadline' => now()->addDays(25),
        ]);

        // Category 5: Healthcare/Medical
        \App\Models\Job::create([
            'title' => 'Sjuksköterska',
            'description' => 'Vi söker en engagerad sjuksköterska för vårdavdelning. Du kommer att arbeta med patientvård och vara en viktig del av vårt team.',
            'location' => 'Uppsala',
            'employment_type' => 'part-time',
            'salary' => '32000-38000 SEK/månad',
            'requirements' => '- Legitimerad sjuksköterska\n- Minst 2 års erfarenhet\n- Goda kunskaper i svenska\n- Empatisk och patientsäker',
            'responsibilities' => '- Patientvård och omvårdnad\n- Administrera medicin\n- Dokumentera vård\n- Samarbeta med läkare och kollegor',
            'is_published' => true,
            'deadline' => now()->addDays(20),
        ]);
    }
}
