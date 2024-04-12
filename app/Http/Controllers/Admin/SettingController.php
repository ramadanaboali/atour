<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    private $viewGeneral = 'admin.pages.settings.general';
    private $viewPrivacy = 'admin.pages.settings.privacy';
    private $viewTerms = 'admin.pages.settings.terms';
    private $viewAbout = 'admin.pages.settings.about';

    private Setting $setting;
    private $service;

    public function __construct()
    {
        $this->service = new SettingService();
        $this->setting = new Setting();
    }

    public function index(): View
    {
        $items = $this->setting
            ->where('key', 'LIKE', 'general_%')
            ->get();
        return view($this->viewGeneral, get_defined_vars());
    }

    public function privacy(): View
    {
        $items = $this->setting
            ->where('key', 'LIKE', 'privacy_%')
            ->get();
        return view($this->viewPrivacy, get_defined_vars());
    }

    public function terms(): View
    {
        $items = $this->setting
            ->where('key', 'LIKE', 'terms_%')
            ->get();
        return view($this->viewTerms, get_defined_vars());
    }

    public function about(): View
    {
        $items = $this->setting
            ->where('key', 'LIKE', 'about_%')
            ->get();

        return view($this->viewAbout, get_defined_vars());
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            $this->service->updateSettings($request->all());
            flash(__('settings.messages.saved'))->success();
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
        }
        return back();
    }
}
