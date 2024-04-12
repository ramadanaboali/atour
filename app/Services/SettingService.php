<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Business;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private Setting $setting;

    public function __construct()
    {
        $this->setting  = new Setting();
    }

    public function updateSettings($request): void
    {
        unset($request['_method']);
        unset($request['_token']);
        unset($request['key']);

        $arr = [];
        $keys = [];
        foreach ($request as $key => $value) {
            $keys[] = $key;
            if (empty($value)) {
                continue;
            }
            $file = request()->file($key);
            if ($file) {
                $value = $this->storeFile($file);
            }
            $arr[] = [
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            Cache::forget('setting-' . $key);
        }
        $this->setting->whereIn('key', $keys)->delete();
        if ($arr > 0) {
            $this->setting->insert($arr);
        }
    }
    public function storeFile($image)
    {
        $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/settings', $fileName);
        return $fileName;
    }
}
