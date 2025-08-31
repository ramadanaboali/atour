<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\CountryTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            ['en' => 'Kuwait', 'ar' => 'الكويت'],
            ['en' => 'Qatar', 'ar' => 'قطر'],
            ['en' => 'Bahrain', 'ar' => 'البحرين'],
            ['en' => 'Oman', 'ar' => 'عمان'],
            ['en' => 'Jordan', 'ar' => 'الأردن'],
            ['en' => 'Lebanon', 'ar' => 'لبنان'],
            ['en' => 'Syria', 'ar' => 'سوريا'],
            ['en' => 'Iraq', 'ar' => 'العراق'],
            ['en' => 'Yemen', 'ar' => 'اليمن'],
            ['en' => 'Morocco', 'ar' => 'المغرب'],
            ['en' => 'Tunisia', 'ar' => 'تونس'],
            ['en' => 'Algeria', 'ar' => 'الجزائر'],
            ['en' => 'Libya', 'ar' => 'ليبيا'],
            ['en' => 'Sudan', 'ar' => 'السودان'],
        ];

        foreach ($countries as $countryData) {
            // Create country
            $country = Country::create([
                'active' => true,
            ]);

            // Create translations
            CountryTranslation::create([
                'country_id' => $country->id,
                'locale' => 'en',
                'title' => $countryData['en'],
            ]);

            CountryTranslation::create([
                'country_id' => $country->id,
                'locale' => 'ar',
                'title' => $countryData['ar'],
            ]);
        }

        $this->command->info('Countries seeded successfully with ' . count($countries) . ' countries.');
    }
}
