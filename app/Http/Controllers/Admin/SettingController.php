<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function invoice()
    {
        $settings = [
            'invoice_company_name_show' => SiteSetting::getValue('invoice_company_name_show', '1'),
            'invoice_prefix' => SiteSetting::getValue('invoice_prefix', 'REM'),
            'invoice_logo' => SiteSetting::getValue('invoice_logo'),
            'invoice_signature' => SiteSetting::getValue('invoice_signature'),
            'invoice_page_size' => SiteSetting::getValue('invoice_page_size', 'A4'),
            'invoice_custom_fields' => json_decode(SiteSetting::getValue('invoice_custom_fields', '[]'), true),
        ];

        return view('admin.settings.invoice', compact('settings'));
    }

    public function updateInvoice(Request $request)
    {
        SiteSetting::setValue('invoice_company_name_show', $request->has('invoice_company_name_show') ? '1' : '0');
        SiteSetting::setValue('invoice_company_name', $request->invoice_company_name ?? 'REMENANT');
        SiteSetting::setValue('invoice_prefix', $request->invoice_prefix);
        SiteSetting::setValue('invoice_page_size', $request->invoice_page_size);

        if ($request->hasFile('invoice_logo')) {
            $path = $request->file('invoice_logo')->store('settings', 'public');
            SiteSetting::setValue('invoice_logo', $path);
        }

        if ($request->hasFile('invoice_signature')) {
            $path = $request->file('invoice_signature')->store('settings', 'public');
            SiteSetting::setValue('invoice_signature', $path);
        }

        $customFields = [];
        if ($request->has('custom_field_keys')) {
            foreach ($request->custom_field_keys as $index => $key) {
                if ($key && isset($request->custom_field_values[$index])) {
                    $customFields[] = [
                        'key' => $key,
                        'value' => $request->custom_field_values[$index]
                    ];
                }
            }
        }
        SiteSetting::setValue('invoice_custom_fields', json_encode($customFields));

        return redirect()->back()->with('success', 'Invoice settings updated successfully.');
    }
}
