@extends('admin.layouts.app')

@section('content')
<div class="space-y-8 pb-24" x-data="{ search: '', showAddModal: false }">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Admin Management</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Manage Platform Administrators • Remenant Engine</p>
        </div>
        <button type="button" @click="showAddModal = true" class="h-12 px-6 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-orange-600 transition-all shadow-lg shadow-slate-200">
            Create Admin
        </button>
    </div>

    <!-- Admin Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="saas-card bg-slate-900 border-0 shadow-2xl shadow-slate-100">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">Total Administrators</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-white tracking-tighter">{{ $items->count() }}</h3>
                <i data-lucide="shield-check" class="w-6 h-6 text-slate-700"></i>
            </div>
        </div>
        <div class="saas-card">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">Active Now</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">{{ $items->whereNull('deleted_at')->count() }}</h3>
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
            </div>
        </div>
    </div>

    <!-- Admin Directory -->
    <div class="saas-card p-0 overflow-hidden border border-slate-100 shadow-xl shadow-slate-200/40">
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/20 flex items-center justify-between">
            <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Admin Directory</h3>
            <div class="relative w-72">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
                <input type="text" x-model="search" placeholder="Search admins..." class="saas-input pl-10 py-1.5 text-[10px] uppercase font-bold tracking-widest">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[8px] font-bold uppercase tracking-[0.25em] text-slate-400 bg-slate-50/50">
                        <th class="px-8 py-4">Administrator</th>
                        <th class="px-8 py-4">Role</th>
                        <th class="px-8 py-4">Phone</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($items as $user)
                    <tr x-show="!search || '{{ strtolower($user->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(search.toLowerCase())"
                        class="hover:bg-slate-50/50 transition-all group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-white font-bold text-sm border-4 border-slate-100 shadow-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-tight">{{ $user->name }}</h4>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase bg-orange-50 text-orange-600 border border-orange-100">Full Access</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">{{ $user->phone ?? 'N/A' }}</span>
                        </td>
                        <td class="px-8 py-6">
                            @if($user->deleted_at)
                            <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase bg-rose-50 text-rose-500 border border-rose-100">Deactivated</span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase bg-emerald-50 text-emerald-500 border border-emerald-100">Active</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.customers.show', $user->id) }}" class="h-8 w-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:border-orange-200 transition-all shadow-sm">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Admin Modal -->
    <template x-teleport="body">
        <div x-show="showAddModal" 
             x-cloak         x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-[150] bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-6" 
             @click.self="showAddModal = false">
            <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">New Admin</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Create platform admin credentials</p>
                    </div>
                    <button @click="showAddModal = false" class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>
                
                <form action="{{ route('admin.admins.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 mb-2">Name</label>
                            <input type="text" name="name" required class="w-full h-12 px-5 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-orange-500 focus:bg-white transition-all font-bold text-xs" placeholder="e.g. Rahul Sharma">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 mb-2">Email Address</label>
                            <input type="email" name="email" required class="w-full h-12 px-5 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-orange-500 focus:bg-white transition-all font-bold text-xs" placeholder="e.g. rahul@site.com">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 mb-2">Phone Number</label>
                            <input type="text" name="phone" class="w-full h-12 px-5 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-orange-500 focus:bg-white transition-all font-bold text-xs" placeholder="e.g. 9876543210">
                        </div>
                        <div x-data="{ showPassword: false }">
                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 mb-2">Password</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" 
                                       name="password" 
                                       required 
                                       minlength="8" 
                                       class="w-full h-12 pl-5 pr-12 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-orange-500 focus:bg-white transition-all font-bold text-xs" 
                                       placeholder="Min 8 characters">
                                <button type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors flex items-center justify-center h-8 w-8 rounded-lg hover:bg-slate-100">
                                    <i data-lucide="eye" class="w-4 h-4" x-show="!showPassword"></i>
                                    <i data-lucide="eye-off" class="w-4 h-4" x-show="showPassword" x-cloak></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex gap-3 pt-6">
                            <button type="button" @click="showAddModal = false" class="flex-1 h-12 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-all border border-slate-100">Cancel</button>
                            <button type="submit" class="flex-1 h-12 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-600 transition-all shadow-lg shadow-slate-200">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection
