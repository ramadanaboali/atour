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
    public function header(): View
    {
        $items = $this->setting
            ->where('key', 'LIKE', 'header_%')
            ->get();
        return view('admin.pages.settings.header', get_defined_vars());
    }
    public function footer_settings(): View
    {
        $items = $this->setting
            ->where('key', 'LIKE', 'footer_%')
            ->get();
        return view('admin.pages.settings.footer', get_defined_vars());
    }

    public function privacy(): View
    {
        $item = $this->setting
            ->where('key', 'privacy')
            ->first();
        return view('admin.pages.settings.privacy', get_defined_vars());
    }
    public function cancel_terms(): View
    {
        $item = $this->setting
            ->where('key', 'cancel_terms')
            ->first();

        return view('admin.pages.settings.cancel_terms', get_defined_vars());

    }

    public function terms(): View
    {
        $item = $this->setting
            ->where('key', 'terms')
            ->first();
        return view('admin.pages.settings.terms', get_defined_vars());
    }

    public function about(): View
    {
        $item = $this->setting
            ->where('key', 'about')
            ->first();
        return view('admin.pages.settings.about', get_defined_vars());

    }
    public function helping(): View
    {
        $item = $this->setting
            ->where('key', 'helping')
            ->first();

        return view('admin.pages.settings.helping', get_defined_vars());

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
    public function updateTerm(Request $request): RedirectResponse
    {
        // try {
        $item = $this->setting->where('key', $request->type)->first();
        $item = $item ?? new Setting();
        $item->key = $request->type;
        $item->save();
        //if key==privacy , if key==terms
        if ($request->type == "terms") {
            $item->termsTranslations()->delete();
        }
        if ($request->type == "privacy") {
            $item->termsTranslations()->delete();
        }

        if ($request->type == "about") {
            $item->aboutTranslations()->delete();
        }

        if ($request->type == "cancel_terms") {
            $item->cancelTermTranslations()->delete();
        }
        if ($request->type == "helping") {
            $item->helpingTranslations()->delete();
        }




        foreach ($request->translations as $tr) {
            $tr['type'] = $request->type;
            if ($request->type == 'helping') {
                $item->helpingTranslations()->create($tr);
            }
            if ($request->type == 'about') {
                $item->aboutTranslations()->create($tr);
            }

            if ($request->type == 'privacy') {
                $item->privacyTranslations()->create($tr);
            }
            if ($request->type == 'cancel_terms') {
                $item->cancelTermTranslations()->create($tr);
            }
            if ($request->type == 'terms') {
                $item->termsTranslations()->create($tr);
            }

        }

        flash(__('settings.messages.saved'))->success();
        // } catch (\Exception $e) {
        //     flash($e->getMessage())->error();
        // }
        return back();
    }
}
