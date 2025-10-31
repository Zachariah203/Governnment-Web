<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\generalSettings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class generalSetting extends Controller
{
    /**
     * Display the general settings form.
     */
    public function create()
    {
        $settings = generalSettings::first(); // Fetches the first record (or null)
        return view('components.admin.settings.general-setting', compact('settings'));
    }

    /**
     * Store or update general settings.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'abbreviation' => 'nullable|string|max:10',
            'logo_dark_mode' => 'nullable|array|max:5',
            'logo_dark_mode.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_light_mode' => 'nullable|array|max:5',
            'logo_light_mode.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'date_of_establishment' => 'required|date',
            'primary_phone_number' => 'required|string|min:10',
            'secondary_phone_number' => 'nullable|string|min:10',
            'email' => 'required|email',
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'nullable|string',
            'instagram_links' => 'nullable|url',
            'facebook_links' => 'nullable|url',
            'twitter_links' => 'nullable|url',
            'websites_URL' => 'nullable|url',
            'mission_statement' => 'nullable|string',
            'vision_statement' => 'nullable|string',
            'core_value' => 'nullable|array',
            'about_company' => 'nullable|string',
            'industry' => 'required|string',
            'company_type' => 'required|string|in:private,public,n.g.o',
            'size' => 'required|integer|min:1',
            'registration_number' => 'nullable|string',
            'certifications' => 'nullable|array|max:10',
            'certifications.*' => 'file|mimes:pdf,jpg,png|max:5120',
            'brand_colour' => 'nullable|string|max:7',
            'brochures' => 'nullable|array|max:10',
            'brochures.*' => 'file|mimes:pdf|max:10240',
            'corporate_presentation' => 'nullable|array|max:5',
            'corporate_presentation.*' => 'file|mimes:pdf,ppt,pptx|max:10240',
            'promotional_photos' => 'nullable|array|max:20',
            'promotional_photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'promotional_videos' => 'nullable|array|max:5',
            'promotional_videos.*' => 'file|mimes:mp4,avi,mov|max:51200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Prepare data for updateOrCreate
        $data = [
            'company_name' => $request->company_name,
            'slogan' => $request->slogan,
            'abbreviation' => $request->abbreviation,
            'date_of_establishment' => $request->date_of_establishment,
            'phone_number' => $request->primary_phone_number,
            'secondary_phone_number' => $request->secondary_phone_number ?? null,
            'email_address' => $request->email,
            'address' => $request->address,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'instagram_links' => $request->instagram_links,
            'facebook_links' => $request->facebook_links,
            'twitter_links' => $request->twitter_links,
            'websites_url' => $request->websites_URL,
            'mission_statement' => $request->mission_statement,
            'vision_statement' => $request->vision_statement,
            'core_values' => json_encode($request->core_value ?? []),
            'about_company' => $request->about_company,
            'industry' => $request->industry,
            'organization_type' => $request->company_type,
            'size' => $request->size,
            'registration_details' => $request->registration_number,
            'brand_color' => $request->brand_colour,
            'status' => 1,
        ];

        // Create or find the settings record
        $settings = generalSettings::firstOrCreate(['settings_id' => 1], $data);

        // Handle file uploads after record exists
        $this->updateFiles($settings, $request);

        // Update non-file fields (in case files changed something)
        $settings->update($data);

        return redirect()->route('components.admin.settings.general-setting')->with('success', 'Settings updated successfully!');
    }

    /**
     * Handle file uploads and updates.
     */
    private function updateFiles($settings, $request)
    {
        $fileFields = [
            'logo_dark_mode' => 'logo_darkmode',
            'logo_light_mode' => 'logo_lightmode',
            'certifications' => 'certifications',
            'brochures' => 'brochures',
            'corporate_presentation' => 'corperate_presentations',
            'promotional_photos' => 'promotional_photos',
            'promotional_videos' => 'promotional_videos',
        ];

        foreach ($fileFields as $inputName => $dbField) {
            if ($request->hasFile($inputName)) {
                // Delete old files if exist
                if ($settings->$dbField) {
                    $oldFiles = json_decode($settings->$dbField, true) ?? [];
                    foreach ($oldFiles as $oldFile) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }
                $paths = [];
                foreach ($request->file($inputName) as $file) {
                    $paths[] = $file->store("uploads/{$inputName}", 'public');
                }
                $settings->$dbField = json_encode($paths);
                $settings->save(); // Save after each file update
            }
        }

        // Handle single favicon
        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $settings->favicon = $request->file('favicon')->store('favicons', 'public');
            $settings->save();
        }
    }
}