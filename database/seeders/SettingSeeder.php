<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $defaultSettings = config('trikaro.setting');
        foreach($defaultSettings as $settingKey => $settingValue){
            $setting = Setting::create([
                'key' => $settingKey,
                'value' => serialize($settingValue)
            ]);
        }
    }
}
