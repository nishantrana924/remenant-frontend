@extends('admin.layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-slate-900 leading-tight">Invoice Settings</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Invoice Configuration</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Branding • Layout • Custom Fields</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.invoice.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Branding Section -->
        <div class="saas-card p-8">
            <div class="flex items-center gap-3 mb-8 pb-4 border-b border-slate-50">
                <div class="h-8 w-8 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500">
                    <i data-lucide="building" class="w-4 h-4"></i>
                </div>
                <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Company Branding</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="invoice_company_name_show" class="sr-only peer" {{ $settings['invoice_company_name_show'] == '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-tight">Show Company Name on Invoice</span>
                    </label>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Company Name</label>
                        <input type="text" name="invoice_company_name" value="{{ SiteSetting::getValue('invoice_company_name', 'REMENANT') }}" class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" placeholder="e.g. REMENANT">
                        <p class="text-[9px] text-slate-400 mt-2 font-medium">Shown at the top of every invoice.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Invoice Number Prefix</label>
                        <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] }}" class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" placeholder="e.g. REM">
                        <p class="text-[9px] text-slate-400 mt-2 font-medium">Example: {{ $settings['invoice_prefix'] }}001</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Company Logo</label>
                        <div class="flex items-center gap-4">
                            @if($settings['invoice_logo'])
                                <div class="h-16 w-16 rounded-xl border border-slate-100 bg-white p-2 shadow-sm">
                                    <img src="{{ Storage::url($settings['invoice_logo']) }}" class="h-full w-full object-contain">
                                </div>
                            @endif
                            <input type="file" name="invoice_logo" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:uppercase file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Authorized Signature</label>
                        <div class="flex items-center gap-4">
                            @if($settings['invoice_signature'])
                                <div class="h-16 w-16 rounded-xl border border-slate-100 bg-white p-2 shadow-sm">
                                    <img src="{{ Storage::url($settings['invoice_signature']) }}" class="h-full w-full object-contain">
                                </div>
                            @endif
                            <input type="file" name="invoice_signature" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:uppercase file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Settings -->
        <div class="saas-card p-8">
            <div class="flex items-center gap-3 mb-8 pb-4 border-b border-slate-50">
                <div class="h-8 w-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                </div>
                <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Page Settings</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <label class="relative flex cursor-pointer rounded-2xl border-2 p-6 transition-all focus:outline-none {{ $settings['invoice_page_size'] == 'A4' ? 'border-orange-500 bg-orange-50/30' : 'border-slate-100 bg-white hover:border-slate-200' }}">
                    <input type="radio" name="invoice_page_size" value="A4" class="sr-only" {{ $settings['invoice_page_size'] == 'A4' ? 'checked' : '' }} onclick="this.closest('form').querySelectorAll('.border-orange-500').forEach(el => el.classList.remove('border-orange-500', 'bg-orange-50/30')); this.closest('label').classList.add('border-orange-500', 'bg-orange-50/30');">
                    <div class="flex w-full items-center justify-between">
                        <div class="flex items-center">
                            <div class="text-sm">
                                <p class="font-bold text-slate-900 uppercase tracking-tight">Standard Desktop Printers</p>
                                <p class="text-xs text-slate-500 mt-1">Size A4 (8"x11") - Single Invoice on one Sheet</p>
                            </div>
                        </div>
                        @if($settings['invoice_page_size'] == 'A4')
                        <i data-lucide="check-circle" class="h-5 w-5 text-orange-500"></i>
                        @endif
                    </div>
                </label>

                <label class="relative flex cursor-pointer rounded-2xl border-2 p-6 transition-all focus:outline-none {{ $settings['invoice_page_size'] == 'Thermal' ? 'border-orange-500 bg-orange-50/30' : 'border-slate-100 bg-white hover:border-slate-200' }}">
                    <input type="radio" name="invoice_page_size" value="Thermal" class="sr-only" {{ $settings['invoice_page_size'] == 'Thermal' ? 'checked' : '' }} onclick="this.closest('form').querySelectorAll('.border-orange-500').forEach(el => el.classList.remove('border-orange-500', 'bg-orange-50/30')); this.closest('label').classList.add('border-orange-500', 'bg-orange-50/30');">
                    <div class="flex w-full items-center justify-between">
                        <div class="flex items-center">
                            <div class="text-sm">
                                <p class="font-bold text-slate-900 uppercase tracking-tight">Thermal Printers</p>
                                <p class="text-xs text-slate-500 mt-1">Size (4"x6") - Label style printing</p>
                            </div>
                        </div>
                        @if($settings['invoice_page_size'] == 'Thermal')
                        <i data-lucide="check-circle" class="h-5 w-5 text-orange-500"></i>
                        @endif
                    </div>
                </label>
            </div>
            <p class="text-[9px] text-rose-500 mt-4 font-bold uppercase tracking-widest">Note: Custom fields are not allowed on Thermal Invoices.</p>
        </div>

        <!-- Custom Fields -->
        <div class="saas-card p-8">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-xl bg-purple-50 flex items-center justify-center text-purple-500">
                        <i data-lucide="list-plus" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Customize Fields</h3>
                </div>
                <button type="button" onclick="addCustomField()" class="text-[9px] font-black text-orange-600 uppercase tracking-widest hover:text-orange-700 transition-all flex items-center gap-1">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    Add Field
                </button>
            </div>

            <p class="text-xs text-slate-500 mb-6">This option enables you to add extra fields (e.g., PAN No, Support ID) along with values you want to show on your invoice.</p>

            <div id="custom-fields-container" class="space-y-4">
                @forelse($settings['invoice_custom_fields'] as $index => $field)
                <div class="flex items-center gap-4 group animate-in fade-in slide-in-from-top-2 duration-300">
                    <div class="flex-1 grid grid-cols-2 gap-4">
                        <input type="text" name="custom_field_keys[]" value="{{ $field['key'] }}" class="bg-slate-50 border-slate-100 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" placeholder="Field Name (e.g. GST No)">
                        <input type="text" name="custom_field_values[]" value="{{ $field['value'] }}" class="bg-slate-50 border-slate-100 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" placeholder="Value">
                    </div>
                    <button type="button" onclick="this.closest('.flex').remove()" class="h-10 w-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-rose-500 hover:text-white">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
                @empty
                <div id="no-fields-msg" class="text-center py-8 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No custom fields added</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <button type="submit" class="saas-btn-primary py-3 px-12 text-sm">
                Save Settings
            </button>
        </div>
    </form>
</div>

<template id="field-template">
    <div class="flex items-center gap-4 group animate-in fade-in slide-in-from-top-2 duration-300">
        <div class="flex-1 grid grid-cols-2 gap-4">
            <input type="text" name="custom_field_keys[]" class="bg-slate-50 border-slate-100 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" placeholder="Field Name">
            <input type="text" name="custom_field_values[]" class="bg-slate-50 border-slate-100 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" placeholder="Value">
        </div>
        <button type="button" onclick="this.closest('.flex').remove()" class="h-10 w-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-rose-500 hover:text-white">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
    </div>
</template>

<script>
function addCustomField() {
    const container = document.getElementById('custom-fields-container');
    const noMsg = document.getElementById('no-fields-msg');
    if (noMsg) noMsg.remove();
    
    const template = document.getElementById('field-template');
    const clone = template.content.cloneNode(true);
    container.appendChild(clone);
    lucide.createIcons();
}
</script>
@endsection
