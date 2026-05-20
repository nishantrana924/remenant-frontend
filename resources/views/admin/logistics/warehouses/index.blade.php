@extends('admin.layouts.app')

@section('content')
<div class="page-enter" x-data="{ 
    showCreate: {{ $errors->any() && !old('id') ? 'true' : 'false' }}, 
    showEdit: {{ $errors->any() && old('id') ? 'true' : 'false' }},
    currentWarehouse: {},
    editWarehouse(warehouse) {
        if(warehouse.contact_name && !warehouse.contact_person) {
            warehouse.contact_person = warehouse.contact_name;
        }
        this.currentWarehouse = warehouse;
        this.showEdit = true;
    }
}">
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", 'success'))</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", 'error'))</script>
    @endif
    @if($errors->any())
        <script>document.addEventListener('DOMContentLoaded', () => showToast("{{ $errors->first() }}", 'error'))</script>
    @endif
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.logistics.dashboard') }}" class="h-8 w-8 rounded bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 leading-tight">Pickup Locations</h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Manage Warehouses</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.logistics.warehouses.sync') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-1.5 px-4 py-1.5 bg-white border border-slate-200 rounded text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                    <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                    Sync
                </button>
            </form>
            <button @click="showCreate = true" class="flex items-center gap-1.5 px-4 py-1.5 bg-slate-900 border border-slate-900 rounded text-[10px] font-bold uppercase tracking-widest text-white hover:bg-slate-800 transition-all shadow-sm">
                <i data-lucide="plus" class="w-3 h-3"></i>
                Add New
            </button>
        </div>
    </div>

    <!-- Warehouse Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($items as $item)
        <div class="bg-white border rounded-lg shadow-sm overflow-hidden relative transition-all hover:border-slate-300 {{ $item->is_default ? 'border-blue-500 ring-1 ring-blue-500' : 'border-slate-200' }}">
            @if($item->is_default)
            <div class="absolute top-0 right-0">
                <div class="bg-blue-500 text-white text-[9px] font-bold uppercase px-3 py-1 rounded-bl-lg shadow-sm">
                    Default Origin
                </div>
            </div>
            @endif

            <div class="p-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-8 w-8 rounded bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400">
                        <i data-lucide="warehouse" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 leading-tight">{{ $item->name }}</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5">ID: {{ $item->nimbus_id ?? 'Local' }}</p>
                    </div>
                </div>

                @if($item->phone == '0000000000' || $item->address == 'N/A' || empty($item->address))
                <div class="mb-4 p-2 bg-amber-50 border border-amber-200 rounded flex items-start gap-2">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-500 shrink-0 mt-0.5"></i>
                    <p class="text-[10px] font-medium text-amber-800 leading-tight">Incomplete address details. Please edit to ensure successful dispatch.</p>
                </div>
                @endif

                <div class="space-y-2 mb-5">
                    <div class="flex gap-2 text-xs">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                        <span class="text-slate-700 font-medium truncate">{{ $item->contact_name ?? $item->contact_person }} ({{ $item->phone }})</span>
                    </div>
                    <div class="flex gap-2 text-xs">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5"></i>
                        <span class="text-slate-600 leading-snug">{{ $item->address }} {{ $item->address_2 }}<br>{{ $item->city }}, {{ $item->state }} - {{ $item->pincode }}</span>
                    </div>
                    @if($item->gst_number && $item->gst_number !== 'N/A')
                    <div class="flex gap-2 text-[10px] pt-1">
                        <span class="text-slate-400 font-bold uppercase">GST:</span>
                        <span class="text-slate-700 font-medium">{{ $item->gst_number }}</span>
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-slate-100">
                    @if(!$item->is_default)
                    <form action="{{ route('admin.logistics.warehouses.set-default', $item->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-1.5 bg-slate-50 text-[10px] font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-all rounded border border-slate-200">
                            Set Default
                        </button>
                    </form>
                    @else
                    <div class="flex-1"></div>
                    @endif
                    
                    <button @click='editWarehouse(@json($item))' class="px-3 py-1.5 bg-white text-slate-600 hover:bg-slate-50 transition-all rounded border border-slate-200 flex items-center justify-center" title="Edit">
                        <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                    </button>
                    
                    <form action="{{ route('admin.logistics.warehouses.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this warehouse?')" class="{{ $item->is_default ? '' : '' }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 bg-white text-rose-500 hover:bg-rose-50 transition-all rounded border border-slate-200 flex items-center justify-center" title="Delete">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 bg-white border border-dashed border-slate-300 rounded-lg flex flex-col items-center justify-center text-slate-500">
            <i data-lucide="warehouse" class="w-8 h-8 mb-3 text-slate-300"></i>
            <p class="text-sm font-bold text-slate-700">No Warehouses Found</p>
            <p class="text-xs text-slate-500 mt-1">Sync from Nimbus or create a new one to get started.</p>
        </div>
        @endforelse
    </div>

    <!-- Create Warehouse Slide-over -->
    <div x-show="showCreate" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 w-full max-w-lg bg-white shadow-xl z-[150] border-l border-slate-200 flex flex-col"
         x-cloak>
        
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Add Warehouse</h3>
            <button @click="showCreate = false" class="h-8 w-8 rounded hover:bg-slate-200 transition-all flex items-center justify-center text-slate-500">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6">
            <form action="{{ route('admin.logistics.warehouses.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Basic Details</h5>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Warehouse Name</label>
                        <input type="text" name="name" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" placeholder="e.g. Ambika Pinnacle Mall">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Contact Person</label>
                            <input type="text" name="contact_person" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" placeholder="e.g. Jimmy Thummar">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Phone Number</label>
                            <input type="text" name="phone" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" placeholder="10-digit number">
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Location</h5>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Address Line 1</label>
                        <input type="text" name="address" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" placeholder="Flat, Building, Street">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Address Line 2 (Optional)</label>
                        <input type="text" name="address_2" class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" placeholder="Landmark, Area">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">City</label>
                            <input type="text" name="city" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" placeholder="e.g. Surat">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">State</label>
                            <input type="text" name="state" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" placeholder="e.g. Gujarat">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pincode</label>
                            <input type="text" name="pincode" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" placeholder="e.g. 394101">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">GSTIN (Optional)</label>
                            <input type="text" name="gst_number" class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" placeholder="15-digit GST Number">
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-slate-900 text-white text-[11px] uppercase tracking-wider font-bold py-3.5 rounded-lg hover:bg-slate-800 transition-all shadow-md">Create Warehouse</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Warehouse Slide-over -->
    <div x-show="showEdit" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 w-full max-w-lg bg-white shadow-xl z-[150] border-l border-slate-200 flex flex-col"
         x-cloak>
        
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-blue-50/50">
            <div class="flex items-center gap-3">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Edit Warehouse</h3>
                <span class="text-[10px] text-blue-600 font-bold px-2 py-0.5 bg-white rounded shadow-sm border border-blue-100" x-text="currentWarehouse.nimbus_id || 'Local'"></span>
            </div>
            <button @click="showEdit = false" class="h-8 w-8 rounded hover:bg-slate-200 transition-all flex items-center justify-center text-slate-500">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6">
            <form :action="'/admin/logistics/warehouses/' + currentWarehouse.id" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Basic Details</h5>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Warehouse Name</label>
                        <input type="text" name="name" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" x-model="currentWarehouse.name" placeholder="e.g. Ambika Pinnacle Mall">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Contact Person</label>
                            <input type="text" name="contact_person" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" x-model="currentWarehouse.contact_person" placeholder="e.g. Jimmy Thummar">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Phone Number</label>
                            <input type="text" name="phone" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" x-model="currentWarehouse.phone" placeholder="10-digit number">
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Location</h5>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Address Line 1</label>
                        <input type="text" name="address" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" x-model="currentWarehouse.address" placeholder="Flat, Building, Street">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Address Line 2 (Optional)</label>
                        <input type="text" name="address_2" class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" x-model="currentWarehouse.address_2" placeholder="Landmark, Area">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">City</label>
                            <input type="text" name="city" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" x-model="currentWarehouse.city" placeholder="e.g. Surat">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">State</label>
                            <input type="text" name="state" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" x-model="currentWarehouse.state" placeholder="e.g. Gujarat">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pincode</label>
                            <input type="text" name="pincode" required class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" x-model="currentWarehouse.pincode" placeholder="e.g. 394101">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">GSTIN (Optional)</label>
                            <input type="text" name="gst_number" class="w-full text-sm border-slate-200 rounded px-3 py-2 focus:ring-slate-900 focus:border-slate-900" x-model="currentWarehouse.gst_number" placeholder="15-digit GST Number">
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-blue-600 text-white text-[11px] uppercase tracking-wider font-bold py-3.5 rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-blue-500/20">Update Warehouse</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Drawer Overlay -->
    <div x-show="showCreate || showEdit" @click="showCreate = false; showEdit = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[140]" x-cloak></div>
</div>
@endsection

