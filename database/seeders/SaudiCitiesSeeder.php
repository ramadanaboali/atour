<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\CityTranslation;
use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaudiCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get Saudi Arabia country ID
        $saudiArabia = Country::whereHas('translations', function($query) {
            $query->where('title', 'like', '%Saudia%')
                  ->orWhere('title', 'المملكة العربية السعودية');
        })->first();

        if (!$saudiArabia) {
            $this->command->error('Saudi Arabia country not found. Please create it first.');
            return;
        }

        $cities = [
            ['en' => 'Riyadh', 'ar' => 'الرياض'],
            ['en' => 'Jeddah', 'ar' => 'جدة'],
            ['en' => 'Mecca', 'ar' => 'مكة المكرمة'],
            ['en' => 'Medina', 'ar' => 'المدينة المنورة'],
            ['en' => 'Dammam', 'ar' => 'الدمام'],
            ['en' => 'Khobar', 'ar' => 'الخبر'],
            ['en' => 'Dhahran', 'ar' => 'الظهران'],
            ['en' => 'Taif', 'ar' => 'الطائف'],
            ['en' => 'Buraidah', 'ar' => 'بريدة'],
            ['en' => 'Tabuk', 'ar' => 'تبوك'],
            ['en' => 'Khamis Mushait', 'ar' => 'خميس مشيط'],
            ['en' => 'Hail', 'ar' => 'حائل'],
            ['en' => 'Hofuf', 'ar' => 'الهفوف'],
            ['en' => 'Mubarraz', 'ar' => 'المبرز'],
            ['en' => 'Jubail', 'ar' => 'الجبيل'],
            ['en' => 'Yanbu', 'ar' => 'ينبع'],
            ['en' => 'Abha', 'ar' => 'أبها'],
            ['en' => 'Najran', 'ar' => 'نجران'],
            ['en' => 'Jazan', 'ar' => 'جازان'],
            ['en' => 'Arar', 'ar' => 'عرعر'],
            ['en' => 'Sakaka', 'ar' => 'سكاكا'],
            ['en' => 'Qatif', 'ar' => 'القطيف'],
            ['en' => 'Unaizah', 'ar' => 'عنيزة'],
            ['en' => 'Ras Tanura', 'ar' => 'رأس تنورة'],
            ['en' => 'Hafar Al-Batin', 'ar' => 'حفر الباطن'],
            ['en' => 'Dawadmi', 'ar' => 'الدوادمي'],
            ['en' => 'Qurayyat', 'ar' => 'القريات'],
            ['en' => 'Bisha', 'ar' => 'بيشة'],
            ['en' => 'Al Bahah', 'ar' => 'الباحة'],
            ['en' => 'Safwa', 'ar' => 'صفوى'],
            ['en' => 'Rabigh', 'ar' => 'رابغ'],
            ['en' => 'Majmaah', 'ar' => 'المجمعة'],
            ['en' => 'Wadi Al-Dawasir', 'ar' => 'وادي الدواسر'],
            ['en' => 'Zulfi', 'ar' => 'الزلفي'],
            ['en' => 'Shaqra', 'ar' => 'شقراء'],
            ['en' => 'Afif', 'ar' => 'عفيف'],
            ['en' => 'Al-Kharj', 'ar' => 'الخرج'],
            ['en' => 'Al-Diriyah', 'ar' => 'الدرعية'],
            ['en' => 'Thuwal', 'ar' => 'ثول'],
            ['en' => 'Samtah', 'ar' => 'صامطة'],
            ['en' => 'Sabya', 'ar' => 'صبيا'],
            ['en' => 'Abu Arish', 'ar' => 'أبو عريش'],
            ['en' => 'Ahad Rafidah', 'ar' => 'أحد رفيدة'],
            ['en' => 'Muhayil', 'ar' => 'محايل'],
            ['en' => 'Badr', 'ar' => 'بدر'],
            ['en' => 'Khafji', 'ar' => 'الخفجي'],
            ['en' => 'Ras Al Khair', 'ar' => 'رأس الخير'],
            ['en' => 'Sharurah', 'ar' => 'شرورة'],
            ['en' => 'Turaif', 'ar' => 'طريف'],
            ['en' => 'Al Wajh', 'ar' => 'الوجه'],
            ['en' => 'Umluj', 'ar' => 'أملج'],
            ['en' => 'Al Ula', 'ar' => 'العلا'],
            ['en' => 'Dumat Al-Jandal', 'ar' => 'دومة الجندل'],
            ['en' => 'Tayma', 'ar' => 'تيماء'],
            ['en' => 'Layla', 'ar' => 'ليلى'],
            ['en' => 'Aflaj', 'ar' => 'الأفلاج'],
            ['en' => 'Hotat Bani Tamim', 'ar' => 'حوطة بني تميم'],
            ['en' => 'Al Ghat', 'ar' => 'الغاط'],
            ['en' => 'Rumah', 'ar' => 'رماح'],
            ['en' => 'Muzahmiyya', 'ar' => 'المزاحمية'],
            ['en' => 'Quwayiyah', 'ar' => 'القويعية'],
            ['en' => 'Shaqra', 'ar' => 'شقراء'],
            ['en' => 'Huraymila', 'ar' => 'حريملاء'],
            ['en' => 'Thadiq', 'ar' => 'ثادق'],
            ['en' => 'Al Artawiyah', 'ar' => 'الأرطاوية'],
            ['en' => 'Rass', 'ar' => 'الرس'],
            ['en' => 'Al Bukayriyah', 'ar' => 'البكيرية'],
            ['en' => 'Riyadh Al Khabra', 'ar' => 'رياض الخبراء'],
            ['en' => 'Al Badai', 'ar' => 'البدائع'],
            ['en' => 'Al Asyah', 'ar' => 'الأسياح'],
            ['en' => 'Nairiyah', 'ar' => 'النعيرية'],
            ['en' => 'Tarout', 'ar' => 'تاروت'],
            ['en' => 'Sihat', 'ar' => 'سيهات'],
            ['en' => 'Awamiyah', 'ar' => 'العوامية'],
        ];

        foreach ($cities as $cityData) {
            // Create city
            $city = City::create([
                'country_id' => $saudiArabia->id,
                'active' => true,
                'created_by' => null, // Set to null since user might not exist
            ]);

            // Create translations
            CityTranslation::create([
                'city_id' => $city->id,
                'locale' => 'en',
                'title' => $cityData['en'],
            ]);

            CityTranslation::create([
                'city_id' => $city->id,
                'locale' => 'ar',
                'title' => $cityData['ar'],
            ]);
        }

        $this->command->info('Saudi Arabia cities seeded successfully with ' . count($cities) . ' cities.');
    }
}
