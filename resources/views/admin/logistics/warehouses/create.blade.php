@extends('admin.layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-slate-900 leading-tight">Add New Warehouse</h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto pb-12">
    <div class="mb-8">
        <a href="{{ route('admin.logistics.warehouses.index') }}" class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-all">
            <i data-lucide="arrow-left" class="w-3 h-3"></i>
            Back to Network
        </a>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase mt-4">Warehouse Configuration</h1>
    </div>

    <form action="{{ route('admin.logistics.warehouses.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="saas-card p-8">
            <div class="flex items-center gap-3 mb-8 pb-4 border-b border-slate-50">
                <div class="h-8 w-8 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500">
                    <i data-lucide="info" class="w-4 h-4"></i>
                </div>
                <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Basic Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Warehouse Name</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-900" placeholder="e.g. Primary Hub / Mumbai Warehouse">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Contact Person</label>
                    <input type="text" name="contact_person" required class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Phone Number</label>
                    <input type="text" name="phone" required class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-900">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">GST Number (Optional)</label>
                    <input type="text" name="gst_number" class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-900" placeholder="15-digit GSTIN">
                </div>
            </div>
        </div>

        <div class="saas-card p-8">
            <div class="flex items-center gap-3 mb-8 pb-4 border-b border-slate-50">
                <div class="h-8 w-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </div>
                <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Location Details</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Address Line 1</label>
                    <input type="text" name="address" required class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-900">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Address Line 2 (Optional)</label>
                    <input type="text" name="address_2" class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Pincode</label>
                    <input type="text" name="pincode" required class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">City</label>
                    <input type="text" name="city" required class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">State</label>
                    <input type="text" name="state" required class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-900">
                </div>
            </div>
        </div>

        <div class="saas-card p-8">
            <div class="flex items-center justify-between gap-6">
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-tight">Set as Default Warehouse</h3>
                    <p class="text-xs text-slate-400 mt-1">This warehouse will be used as the primary pickup location for all shipments.</p>
                </div>
                <label class="relative flex items-center cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="saas-btn-primary py-3 px-12 text-sm">Create Warehouse</button>
        </div>
    </form>
</div>
@endsection
