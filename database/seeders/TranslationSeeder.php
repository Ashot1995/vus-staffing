<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locales = ['en', 'sv'];
        $group = 'messages';

        foreach ($locales as $locale) {
            $filePath = lang_path("{$locale}/{$group}.php");

            if (File::exists($filePath)) {
                $translations = require $filePath;

                // Check if translations is an array
                if (! is_array($translations)) {
                    $this->command->warn("Translation file {$filePath} does not return an array. Skipping.");

                    continue;
                }

                foreach ($translations as $key => $value) {
                    Translation::updateOrCreate(
                        [
                            'key' => $key,
                            'locale' => $locale,
                            'group' => $group,
                        ],
                        [
                            'value' => $value,
                        ]
                    );
                }

                $this->command->info("Seeded {$locale} translations: ".count($translations).' keys');
            } else {
                $this->command->warn("Translation file not found: {$filePath}");
            }
        }
    }
}
