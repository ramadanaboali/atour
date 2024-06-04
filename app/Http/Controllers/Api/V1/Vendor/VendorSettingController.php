<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\VendorSettingRequest;
use App\Models\VendorSetting;
use App\Services\Vendor\VendorSettingService;
use function response;

class VendorSettingController extends Controller
{
    protected VendorSettingService $service;
    public function __construct(VendorSettingService $service)
    {
        $this->service = $service;

    }
    public function getSetting(){
        return response()->apiSuccess($this->service->getSetting());
    }

    public function updateSetting(VendorSettingRequest $request)
    {
        $data = $request->only(['content']);
        return response()->apiSuccess($this->service->updateSetting($data));
    }


}
