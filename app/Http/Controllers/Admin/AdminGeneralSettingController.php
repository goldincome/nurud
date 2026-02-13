<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateGeneralSettingRequest;
use App\Models\GeneralSetting;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class AdminGeneralSettingController extends Controller
{
    /**
     * Show the form for editing general settings.
     * Use first() to get the single row of settings or create default.
     */
    public function edit(): View
    {
        $settings = GeneralSetting::first();
        if (!$settings) {
            $settings = new GeneralSetting();
        }
        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Update the general settings in storage.
     */
    public function update(UpdateGeneralSettingRequest $request): RedirectResponse
    {
        $settings = GeneralSetting::first();
        if (!$settings) {
            GeneralSetting::create($request->validated());
        } else {
            $settings->update($request->validated());
        }

        return redirect()->route('admin.settings.general')->with('success', 'General settings updated successfully.');
    }
}
